<?php

include("appWideConfig.php");
include("dbConfig.php");
include("httpful.phar");

$page = filter_input(INPUT_GET, "page");
$size = filter_input(INPUT_GET, "size");
$projectId = filter_input(INPUT_GET, "project");
$listingId = filter_input(INPUT_GET, "listingId");
$cityId = filter_input(INPUT_GET, "city");
$search_term = filter_input(INPUT_GET, "search_term");
$search_value = filter_input(INPUT_GET, "search_value");
$search_range = filter_input(INPUT_GET, "search_range");
$range_from = filter_input(INPUT_GET, "range_from");
$range_to = filter_input(INPUT_GET, "range_to");
$gpid = filter_input(INPUT_GET, "gpid");


$start = $page * $size;
if (!isset($cityId) || $cityId == '') {
    $cityId = 2;
}
$filterArr = array("and" => array(array("equal" => array("cityId" => $cityId))));
if (isset($projectId) && !empty($projectId) && ($projectId != "null") && ($projectId != "")) {
    $filterArr["and"][] = array("equal" => array("projectId" => $projectId));
}
if (isset($listingId) && !empty($listingId) && ($listingId != "null") && ($listingId != "")) {
    $filterArr["and"][] = array("equal" => array("listingId" => $listingId));
}
if (isset($search_term) && !empty($search_term) && ($search_term != "null") && ($search_term != "")) {
    $filterArr["and"][] = array("equal" => array($search_term => $search_value));
}
if (isset($search_range) && !empty($search_range) && ($search_range != "null") && ($search_range != "")) {
    if ($range_from != "" || $range_to != "") {
        $tempRange["range"][$search_range]["from"] = ($range_from != "") ? (int) $range_from : 1;
    }
    if ($range_to != "") {
        $tempRange["range"][$search_range]["to"] = (int) $range_to;
    }
    $filterArr["and"][] = $tempRange;
}
$gpidFilter = "";
if (isset($gpid) && $gpid != "") {
    $gpidFilter = "gpid=" . $gpid . "&";
}
$tbsorterArr = array();

$filter = json_encode($filterArr);
$fields = '"fields":["imageCount","description","seller","id","fullName","currentListingPrice","pricePerUnitArea","price","otherCharges","property","project","locality","suburb","city","label","name","builder","unitName","size","unitType","createdAt","projectId","propertyId","phaseId","updatedBy","sellerId","jsonDump","remark","homeLoanBankId","flatNumber","noOfCarParks","negotiable","transferCharges","plc","listingAmenities","amenity","amenityMaster","masterAmenityIds","floor","latitude","longitude","amenityDisplayName","isDeleted","bedrooms","bathrooms","amenityId","imagesCount","listingId","bookingStatusId","facingId","towerId"]}';
$uriListing = RESALE_LISTING_API_V2_URL . '?' . $gpidFilter . 'selector={"paging":{"start":' . $start . ',"rows":' . $size . '},"filters":' . $filter . "," . $fields . '}';

try {
    $responseLists = \Httpful\Request::get($uriListing)->send();
    if ($responseLists->body->statusCode == "2XX") {
        $data = $responseLists->body->data;
        $tbsorterArr['total_rows'] = $responseLists->body->totalCount;
        $tbsorterArr['headers'] = array("Serial", "Listing Id", "City", "Broker Name", "Project", "Listing", "Price", "Created Date", "Photo", "Save", "Delete");
        $tbsorterArr['rows'] = array();
        foreach ($data as $index => $row) {
            $brokerName = "";
            $row->sellerId->id = $row->sellerId;
            if ($row->sellerId) {
                list($row->seller->brokerId, $row->seller->brokerName) = getBroker($row->sellerId);
            }
            if ($row->currentListingPrice->pricePerUnitArea != 0) {
                $price = "Price Per Unit Area - " . $row->currentListingPrice->pricePerUnitArea;
            } else {
                $price = "Price - " . $row->currentListingPrice->price;
            }
            if ($row->currentListingPrice->otherCharges != 0) {
                $price .= "<br>Other Charges - " . $row->currentListingPrice->otherCharges;
            }
            $v->property->project->description = '';
            $v->property->project->locality->description = '';
            $v->property->project->locality->suburb->description = '';
            $v->property->project->locality->suburb->city->description = '';
            $data_rows = array(
                "Serial" => $start + $index + 1,
                "City" => $row->property->project->locality->suburb->city->label,
                "BrokerName" => $row->seller->brokerName,
                "Project" => $row->property->project->name . ", " . $row->property->project->builder->name,
                "Listing" => $row->property->unitName . "-" . $row->property->size . "-" . $row->property->unitType,
                "Price" => $price,
                "Save" => json_encode($row),
                "ListingId" => $row->id,
                "CreatedDate" => date("Y-m-d", ($row->createdAt) / 1000),
                "Photo" => ($row->imageCount > 0) ? "Done" : "Not Done",
                "Delete" => ''
            );
            array_push($tbsorterArr['rows'], $data_rows);
        }
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}
echo json_encode($tbsorterArr);

function getBroker($seller_id) {
    if ($seller_id) {
        $Sql = "SELECT c.name, c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=" . $seller_id . " and c.status = 'Active' and cu.status='Active' ";
        $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
        if (mysql_num_rows($ExecSql) > 0) {
            $Res = mysql_fetch_assoc($ExecSql);
            return array($Res['id'], $Res['name']);
        }
        return array(null, null);
    }
}

?>