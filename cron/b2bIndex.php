<?php
ini_set('display_errors', '1');
ini_set('memory_limit', '3G');
set_time_limit(0);
error_reporting(E_ALL);

$currentDir = dirname(__FILE__);
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../modelsConfig.php');
require_once ($currentDir . '/../cron/cronFunctions.php');
require_once ($currentDir . '/../cron/b2bIndexTest.php');
require_once ($currentDir . '/../dbConfig.php');
require_once ($currentDir . '/../includes/send_mail_amazon.php');

define("INVALID_DATE", "0000-00-01");
define('MIN_B2B_DATE', B2BProperties::getB2BMinDate());
define('MAX_B2B_DATE', B2BProperties::getB2BScriptMaxDate());
define('B2B_DEMAND_START_DATE', B2BProperties::getB2BDemandStartDate());
define('CSV_FIELD_DELIMITER', '~#~');
define('CSV_LINE_DELIMITER', "\r\n");

define('B2B_SUCCESS_EMAIL_RECIPIENT', 'ankur.dhawan@proptiger.com');

$bulkInsert = FALSE;
if (isset($argv[1]) && $argv[1] == 'bulkInsert') {
    $bulkInsert = TRUE;
}

Logger::configure(dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");
$handle = fopen("/tmp/" . DInventoryPriceTmp::table_name() . ".csv", "w+");

ResiProject::connection()->query("set @@session.wait_timeout = 1800");

DInventoryPriceTmp::connection()->query("TRUNCATE TABLE d_inventory_prices_tmp");

$logger->info("\n\n\nDeleted All rows");

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();
$globalCondition = "rp.SKIP_B2B = 0 and rp.STATUS in ('Active', 'ActiveInCms') and rp.RESIDENTIAL_FLAG = 'Residential' and psm.project_status not in ('Cancelled', 'OnHold', 'NotLaunched') and rpp.status = 'Active' and li.status = 'Active' and li.listing_category = 'Primary' and rp.version = 'Website' and rpo.OPTION_CATEGORY in ('Actual', 'Logical')";

$aAllPhases = ResiProject::find_by_sql("select concat_ws('/', rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS) unique_key, rp.project_id, rp.project_name, rpp.phase_id, rpp.phase_name, rpp.phase_type, psm.display_name as construction_status, date_format(" . ResiProjectPhase::$custom_original_launch_date_string . ", '%Y-%m-01') ORIGINAL_LAUNCH_DATE, date_format(if(rpp.COMPLETION_DATE=0, NULL, rpp.COMPLETION_DATE), '%Y-%m-01') COMPLETION_DATE, date_format(" . ResiProjectPhase::$custom_launch_date_string . ", '%Y-%m-01') LAUNCH_DATE, rpo.option_type unit_type, rpo.bedrooms, FLOOR(avg(if(rpo.SIZE = 0 or rpo.SIZE is null, if(rpo.CARPET_AREA is null or rpo.CARPET_AREA = 0, null, rpo.CARPET_AREA*" . ResiProjectOptions::$carpet_area_factor . "), rpo.SIZE))) as AVERAGE_SIZE, l.LOCALITY_ID, l.LABEL as LOCALITY_NAME, s.SUBURB_ID, s.LABEL as SUBURB_NAME, c.CITY_ID, c.LABEL as CITY_NAME, rb.BUILDER_ID, rb.BUILDER_NAME, rbc.LABEL as builder_headquarter_city, mbs.name as booking_status from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id inner join resi_builder rb on rp.BUILDER_ID = rb.BUILDER_ID left join city rbc on rb.city_id = rbc.city_id inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join city c on s.CITY_ID = c.CITY_ID inner join resi_project_phase rpp on rp.project_id = rpp.project_id and rp.version = rpp.version left join master_booking_statuses mbs on rpp.BOOKING_STATUS_ID = mbs.id inner join listings li on (rpp.phase_id = li.phase_id and li.listing_category='Primary') inner join resi_project_options rpo on li.option_id = rpo.options_id and rp.project_id = rpo.project_id where $globalCondition group by unique_key");
removeInvalidPhaseData($aAllPhases);
$aAllIndexedPhases = indexArrayOnKey($aAllPhases, 'unique_key');

$logger->info("Project And Phase Details Retrieved");

$allPhaseConfigCount = count($aAllIndexedPhases);
$completedPhaseConfigCount = 0;
$completedPhaseCount = 0;

while ($completedPhaseConfigCount < $allPhaseConfigCount) {
    $aPhaseId = array();
    $aConfigKey = array();
    for ($i = 0; $i <= 5000 && $completedPhaseConfigCount < $allPhaseConfigCount; $i++) {
        $aPhaseId[] = $aAllPhases[$completedPhaseConfigCount]->phase_id;
        $aConfigKey[] = $aAllPhases[$completedPhaseConfigCount]->unique_key;
        $completedPhaseConfigCount = $completedPhaseConfigCount + 1;
    }
    $aPhaseId = array_unique($aPhaseId);


    $completedPhaseCount = $completedPhaseCount + count($aPhaseId);

    $aAllInventory = ProjectAvailability::getInventoryForIndexing($aPhaseId);
    $aAllPrice = ListingPrices::getPriceForIndexing($aPhaseId);
    $aAllSecondaryPrice = ProjectSecondaryPrice::getMonthWisePriceForPhases($aPhaseId);
    $logger->info("Price and inventory data retrieved");
    
    removeInvalidPhaseData($aAllInventory);
    removeInvalidPhaseData($aAllPrice);

    fillIntermediateMonths($aAllInventory);
    fillIntermediateMonths($aAllPrice);
    fillIntermediateMonths($aAllSecondaryPrice);

    $aAllInventory = indexArrayOnKey($aAllInventory, 'unique_key');
    $aAllPrice = indexArrayOnKey($aAllPrice, 'unique_key');
    $aAllSecondaryPrice = indexArrayOnKey($aAllSecondaryPrice, 'unique_key');

    createDocuments($aConfigKey, $aAllInventory, $aAllPrice, $aAllSecondaryPrice);
    $logger->info("Indexing complete for $completedPhaseCount phases");
}

if ($bulkInsert) {
    importTableFromTmpCsv(DInventoryPriceTmp::table_name());
    fclose($handle);
}

DInventoryPriceTmp::deleteEntriesBeforeLaunch();
DInventoryPriceTmp::updateSupplyAndLaunched();
DInventoryPriceTmp::setUnitdelivered();
DInventoryPriceTmp::updateProjectDominantType();
DInventoryPriceTmp::setLaunchDateMonthSales();
DInventoryPriceTmp::setInventoryOverhang();
DInventoryPriceTmp::setRateOfSale();

DInventoryPriceTmp::setPeriodAttributes();

DInventoryPriceTmp::deleteInvalidPriceEntries();
DInventoryPriceTmp::removeZeroSizes();
DInventoryPriceTmp::updateFirstPromoisedCompletionDate();
DInventoryPriceTmp::updateConstructionStatus();
DInventoryPriceTmp::populateDemand();

if (runTests()) {
    DInventoryPriceTmp::connection()->query("rename table d_inventory_prices to d_inventory_prices_old, d_inventory_prices_tmp to d_inventory_prices, d_inventory_prices_old to d_inventory_prices_tmp;");
    $logger->info("Migration successful.");
    sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'B2B Data Migration Successful', 'Migration successful on server: ' . exec('hostname') . ' .', '', '', array(B2B_SUCCESS_EMAIL_RECIPIENT));
} else {
    $logger->error("Test Cases Failed.");
    sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'B2B Data Migration Failed', 'Migration failed on server: ' . exec('hostname') . ' due to failure in test cases .', '', '',   array(B2B_SUCCESS_EMAIL_RECIPIENT));
}

#echo "\n";
#echo memory_get_usage()."\n";
#echo memory_get_usage(true)."\n";
#echo memory_get_peak_usage()."\n";
#echo memory_get_peak_usage(true)."\n";

function createDocuments($aConfigKey, $aAllInventory, $aAllPrice, $aAllSecondaryPrice) {
    global $logger;
    global $handle;
    global $bulkInsert;
    global $aAllIndexedPhases;

    $i = 0;

    foreach ($aConfigKey as $key) {
        $allMonths = getAllMonths($key);
        foreach ($allMonths as $currentMonth) {
            $monthKey = $key . "/" . $currentMonth;
            $prevMonthKey = $key . "/" . getMonthShiftedDate($currentMonth, -1);

            $configDetail = getConfigDetailForKey($monthKey);
            $secondaryPriceKey = $configDetail->phase_id . "/" . $currentMonth;

            $entry = array();
            $entry['unique_key'] = $monthKey;
            $entry['effective_month'] = $currentMonth;

            setConfigLevelValues($entry);
            
            $isMonthBetweenBoundaryDates = (MIN_B2B_DATE <= $currentMonth) && (MAX_B2B_DATE >= $currentMonth);
            if ($isMonthBetweenBoundaryDates && isset($aAllPrice[$monthKey])) {
                $entry['average_price_per_unit_area'] = $aAllPrice[$monthKey]->average_price_per_unit_area;
                $entry['average_total_price'] = $aAllPrice[$monthKey]->average_total_price;
            }
            if ($isMonthBetweenBoundaryDates && isset($aAllInventory[$monthKey])) {
                $entry['ltd_supply'] = $aAllInventory[$monthKey]->ltd_supply;
                $entry['ltd_launched_unit'] = $aAllInventory[$monthKey]->ltd_launched;
                $entry['inventory'] = $aAllInventory[$monthKey]->inventory;
                if (isset($aAllInventory[$prevMonthKey]) && $entry['effective_month'] > $entry['launch_date']) {
                    $entry['units_sold'] = $aAllInventory[$prevMonthKey]->inventory - $aAllInventory[$monthKey]->inventory;
                } elseif ($entry['effective_month'] === $entry['launch_date']) {
                    $entry['units_sold'] = $aAllInventory[$monthKey]->ltd_launched - $aAllInventory[$monthKey]->inventory;
                }
            }
            if ($isMonthBetweenBoundaryDates && isset($aAllSecondaryPrice[$secondaryPriceKey])){
                $entry['average_secondary_price_per_unit_area'] = $aAllSecondaryPrice[$secondaryPriceKey]->secondary_price;
            }
            $currentMonth = getMonthShiftedDate($currentMonth, 1);
            $new = new DInventoryPriceTmp($entry);
            saveToFileOrDb($new, $bulkInsert, $handle);
            $i++;
        }
    }
    $logger->info($i . " documents inserted in mysql");
}

function getAllMonths($configKey) {
    global $aAllIndexedPhases;

    $months = array();
    if (!is_null($aAllIndexedPhases[$configKey]->launch_date) && $aAllIndexedPhases[$configKey]->launch_date != INVALID_DATE) {
        $months[] = $aAllIndexedPhases[$configKey]->launch_date;
    }
    if (!is_null($aAllIndexedPhases[$configKey]->completion_date) && $aAllIndexedPhases[$configKey]->completion_date != INVALID_DATE) {
        $months[] = $aAllIndexedPhases[$configKey]->completion_date;
    }

    $month = MIN_B2B_DATE;
    while ($month <= MAX_B2B_DATE) {
        $months[] = $month;
        $month = getMonthShiftedDate($month, 1);
    }
    $months = array_unique($months);
    asort($months);
    return $months;
}

function removeInvalidPhaseData(&$aData) {
    global $logger;
    global $aProjectPhaseCount;

    $result = array();
    foreach ($aData as $value) {
        if ($value->phase_type == 'Actual' || $aProjectPhaseCount[$value->project_id] == 1) {
            $result[] = $value;
        }
    }
    $logger->info("Remove invalid phase operation complete");
    $aData = $result;
}

function fillIntermediateMonths(&$aData) {
    global $logger;
    $aNewData = array();
    $count = count($aData);
    for ($i = 0; $i < $count; $i++) {
        $currData = $aData[$i];
        array_push($aNewData, clone $currData);

        $fillTill = MAX_B2B_DATE;
        if (isset($aData[$i + 1]) && $currData->key_without_month === $aData[$i + 1]->key_without_month) {
            $fillTill = getMonthShiftedDate($aData[$i + 1]->effective_month, -1);
        }

        while (substr($currData->effective_month, 0, 10) < $fillTill) {
            $nextMonth = getMonthShiftedDate($currData->effective_month, 1);
            $currData->unique_key = str_replace(substr($currData->effective_month, 0, 10), $nextMonth, $currData->unique_key);
            $currData->effective_month = $nextMonth;
            array_push($aNewData, clone $currData);
        }
    }
    $logger->info("Filling missing months operation complete");
    $aData = $aNewData;
}

function getConfigDetailForKey($key){
    global $aAllIndexedPhases;
    return $aAllIndexedPhases[substr($key, 0, -11)];
}

function setConfigLevelValues(&$entry) {
    global $aAllIndexedPhases;

    $key = $entry['unique_key'];

    $configDetails = getConfigDetailForKey($key);

    $entry['country_id'] = 1;
    $entry['country_name'] = 'India';
    $entry['project_id'] = $configDetails->project_id;
    $entry['project_name'] = $configDetails->project_name;
    $entry['phase_id'] = $configDetails->phase_id;
    $entry['phase_name'] = $configDetails->phase_name;
    $entry['booking_status'] = $configDetails->booking_status;
    $entry['phase_type'] = $configDetails->phase_type;
    $entry['locality_id'] = $configDetails->locality_id;
    $entry['locality_name'] = $configDetails->locality_name;
    $entry['suburb_id'] = $configDetails->suburb_id;
    $entry['suburb_name'] = $configDetails->suburb_name;
    $entry['city_id'] = $configDetails->city_id;
    $entry['city_name'] = $configDetails->city_name;
    $entry['builder_id'] = $configDetails->builder_id;
    $entry['builder_name'] = $configDetails->builder_name;
    $entry['builder_headquarter_city'] = $configDetails->builder_headquarter_city;
    $entry['construction_status'] = computeConstructionStatus($configDetails,$entry['effective_month']);
    $entry['construction_status_quarter'] = computeConstructionStatus($configDetails,date_format(lastDayOf('quarter', $entry['effective_month']),'Y-m-d'));
    $entry['construction_status_year'] = computeConstructionStatus($configDetails,date_format(lastDayOf('year', $entry['effective_month']),'Y-m-d'));
    $entry['construction_status_financial_year'] = computeConstructionStatus($configDetails,date_format(lastDayOf('financial_year', $entry['effective_month']),'Y-m-d'));
    $entry['unit_type'] = $configDetails->unit_type;   
    $entry['bedrooms'] = intval($configDetails->bedrooms);
    $entry['average_size'] = $configDetails->average_size;
    $entry['completion_date'] = $configDetails->completion_date;
    $entry['launch_date'] = $configDetails->launch_date;

    $entry['quarter'] = firstDayOf('quarter', $entry['effective_month']);
    $entry['half_year'] = firstDayOf('half_year', $entry['effective_month']);
    $entry['year'] = firstDayOf('year', $entry['effective_month']);
    $entry['financial_year'] = firstDayOf('financial_year', $entry['effective_month']);
}

// Refer to MIDL-495 for logic
function computeConstructionStatus($configDetails, $effectiveMonth) {
	
	$completionDate   = $configDetails->completion_date;
	$launchDate       = $configDetails->original_launch_date;
	$status = 'Pre Launch';


	if (!empty($launchDate) && strcmp($effectiveMonth,$launchDate)>=0) {
	
		
		$status = 'Under Construction';
		
	}

	if (!empty($completionDate) && strcmp($effectiveMonth,$completionDate)>=0) {
		
		$status = 'Completed';
		
	
	}
	return $status;
}
