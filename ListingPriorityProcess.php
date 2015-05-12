<?php
//calling function for all the cities

$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$bankArray = BankList::arrBank();
$smarty->assign("bankArray",$bankArray);
$smarty->assign('dirname',$dirname);

$project_id = $_REQUEST['project'];


/*
    static function getLocalityByCity($ctid,$companyLocality = null) {
        $conditions = array("a.city_id = ? and a.status = ? and locality.status = ?", $ctid, 'Active', 'Active');
        $join = 'INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)';
        $join .= 'INNER JOIN city c ON(a.city_id = c.city_id)';
    if($companyLocality == 1)
        $suburbOrder = 'suburbname,';
    else
        $suburbOrder = '';

        $getLocality = Locality::find('all',array('joins' => $join, 
               "conditions" => $conditions, "select" => "locality.locality_id,locality.label, c.label as cityname,a.label as suburbname","order"=>"$suburbOrder locality.label asc"));
        return $getLocality;

*/

//select cu.id,cu.name,cu.company_id from company c join company_users cu on c.id = cu.company_id where c.status='Active';
$brokerArray = array();
//$join = 'INNER JOIN company c ON(c.id=company_users.company_id)';
$broker = Company::find('all', array('conditions'=>array("type = 'Broker' and status = 'Active'" )));
//print_r($brokerArray);
foreach ($broker as $v) {
    $tmp['id'] = $v->id;
    $tmp['name'] = $v->name;
    array_push($brokerArray, $tmp);
}
//print_r($brokerArray);
$smarty->assign("brokerArray",$brokerArray);
$smarty->assign('dirname',$dirname);


$orderby = 'ASC';
if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$cityId = $_REQUEST['citydd']; 
if($cityId=='')
    $cityId=2;
//echo '*********************************', $cityId, '############################################ \n\n';

	
$smarty->assign('cityId',$cityId);
$smarty->assign('url12', TYPEAHEAD_API_URL);
$smarty->assign('url13', project_detail);
$smarty->assign('url_phase_id',phase_detail);
$smarty->assign('url14', LISTING_API_URL);
$smarty->assign('url15', ADMIN_USER_LOGIN_API_URL);

$suburbArr = array();
$suburbArr = Suburb::SuburbArr($cityId);
$smarty->assign('suburbArr',$suburbArr);
	

$locArr = array();
$locArr = Locality::getLocalityByCity($cityId);
//print_r($locArr);
$smarty->assign('localityArr',$locArr);


$dirctionsArr = array();
$res = MasterDirections::find('all');
foreach ($res as $v) {
    $tmp = array();
    $tmp['id'] = $v->id;
    $tmp['direction'] = $v->direction;
    array_push($dirctionsArr, $tmp);
}


$smarty->assign('dirctionsArr',$dirctionsArr);
//print_r($dirctionsArr);
$resaleListings = array();


 
// And you're ready to go!
//$url = "http://api.tuxx.co.uk/demo/server/time.php&app_id=" . $appID . "&app_key=" . $appKey;

$uriLogin = ADMIN_USER_LOGIN_API_URL; //master
//$uriLogin = "https://qa.proptiger-ws.com/app/v1/login?username=admin-10@proptiger.com&password=1234&rememberme=true"; //normal user

//$uriListing = "https://qa.proptiger-ws.com/data/v1/entity/user/listing?cityId=2&fields=seller,id,property&start=0&rows=10";
$uriListing = LISTING_API_URL."?listingCategory=Resale&cityId={$cityId}&start=0&fields=seller,seller.fullName,id,listing,listing.facing,listing.jsonDump,listing.description,listing.remark,listing.homeLoanBankId,listing.flatNumber,listing.noOfCarParks,listing.negotiable,listing.transferCharges,listing.plc,property,property.propertyId,property.project,property.projectId,property.project.builder,property.project.locality,property.project.locality.suburb,property.project.locality.suburb.city,listingAmenities.amenity,listingAmenities.amenity.amenityMaster,label,masterAmenityIds,name,unitType,unitName,size,currentListingPrice,localityId,floor,pricePerUnitArea,price,otherCharges,jsonDump,latitude,longitude,amenityDisplayName,isDeleted,bedrooms,bathrooms,amenityId";
//$uri = "https://qa.proptiger-ws.com/data/v1/entity/user/listing";
//$dataArr = array();
//$dataArr['sellerId'] = "1216008";
//$dataJson = json_encode($dataArr);
/*
try{ 

    

    $responseLogin = \Httpful\Request::post($uriLogin)                  // Build a PUT request...
    ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
    ->body('')             // attach a body/payload...
    ->send(); 


    $header = $responseLogin->headers;
    $header = $header->toArray();
    $ck = $header['set-cookie'];
    
    $ck_new = "";
    for($i = 0; $i < strlen($ck); $i++)  {
        if($ck[$i] == ';')  {
            break;
        }
        $ck_new = $ck_new.$ck[$i];
    }


   //var_dump($responseLogin);
   
    if($ck_new!='')
    {    
        $responseLists = \Httpful\Request::get($uriListing)->addHeader("COOKIE", $ck_new )->send(); 
        //var_dump($responseLists->body);
        if($responseLists->body->statusCode=="2XX"){
            $data = $responseLists->body->data;
            //var_dump($responseLists->body);
            foreach ($data as $k => $v){ 
                //$uriListingDetail =  "https://qa.proptiger-ws.com/data/v1/entity/user/listing/".$v->id;                
                //$responseListingDetail = \Httpful\Request::get($uriListingDetail)->addHeader("COOKIE", $ck_new )->send();
                //if($responseListingDetail->body->statusCode=="2XX"){
                $seller_id = $v->seller->id;
                $company_id='';
                if($seller_id){
                    $Sql = "SELECT c.name, c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=".$seller_id." and c.status = 'Active' and cu.status='Active' ";
                    //echo $Sql;
                    $Sel = array();
                    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
                    $cnt = 0;
                    if (mysql_num_rows($ExecSql) > 0) {
                        $Res = mysql_fetch_assoc($ExecSql);
                        $broker_name = $Res['name'];
                        $broker_id = $Res['id'];
                           
                    }
                    $v->seller->brokerName = $broker_name; 
                    $v->seller->brokerId = $broker_id; 
                }
                
                $tmp['json'] = htmlentities(json_encode($v));
                $tmp['val'] = $v;
                    array_push($resaleListings,$tmp);
                //}

            }
        }

    }




*/
//print("<pre>");
//print_r($resaleListings);

$jsonListing = htmlentities(json_encode($resaleListings));

$smarty->assign('resaleListings',$resaleListings);
$smarty->assign('jsonListing',$jsonListing);

$bookingStatus = BookingStatuses::find("all");
$bStatusList =array();
foreach($bookingStatus as $bookingStatus){
    $bStatusList[$bookingStatus->id] = $bookingStatus->display_name;
}
$smarty->assign("bStatusList", $bStatusList);

$typeArr = Company::getCompanyByType("VendorClassified"); 
$smarty->assign("comptype", $typeArr);
//code pagination --------------------------------------------



?>
