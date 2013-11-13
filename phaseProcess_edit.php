<?php

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case '1':
            $smarty->assign("error_msg", "This phase already exists!");
            break;
        case '2':
            $smarty->assign("error_msg", "Phase Config Mapping Cant be Changed. Inventory already added!");
            break;
    }
}

$projectId = $_REQUEST['projectId'];
$project = ResiProject::virtual_find($projectId);
$phaseId = $_REQUEST['phaseId'];
$preview = $_REQUEST['preview'];
$smarty->assign("preview", $preview);
$bookingStatuses = ResiProject::find_by_sql("select * from master_booking_statuses");
$smarty->assign("bookingStatuses", $bookingStatuses);

/* * *******code for delete phase********* */
if (isset($_REQUEST['delete'])) {
    $phase = ResiProjectPhase::virtual_find($phaseId);
    $phase->status = 'Inactive';
    $resDelete = $phase->virtual_save();
    if ($resDelete) {
        if ($preview == 'true')
            header("Location:show_project_details.php?projectId=" . $projectId);
        else
            header("Location:ProjectList.php?projectId=" . $projectId);
    }
}
/* * *******end code for delete phase***** */
$smarty->assign("phaseId", $phaseId);
$projectDetail = ResiProject::virtual_find($projectId);
$projectDetail = $projectDetail->to_custom_array();
$smarty->assign("ProjectDetail", array($projectDetail));

$phaseDetail = array();
$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active'), "order" => "phase_name asc"));
foreach($phases as $p){
    array_push($phaseDetail, $p->to_custom_array());
}

// Project Options and Bedroom Details
$optionsDetails = ProjectOptionDetail($projectId);
$smarty->assign("OptionsDetails", $optionsDetails);
$options = $project->get_all_options();
$smarty->assign("options", $options);
if (isset($phaseId) && $phaseId != -1) {
    $phase_options_temp = array();
    if($phaseId != '0'){
        $phase = ResiProjectPhase::virtual_find($phaseId);
        $smarty->assign("phase", $phase);
        $phase_options = $phase->get_all_options();
        if (count($phase_options) > 0){
            $phase_options_temp = $phase_options;
        }
    }
    $option_ids = array();
    foreach($phase_options_temp as $options) array_push($option_ids, $options->options_id);
    $bedrooms = ResiProjectOptions::optionwise_bedroom_details($option_ids, $phaseId);
    $bedrooms_hash = array();
    foreach($bedrooms as $bed) $bedrooms_hash[$bed->unit_type] = explode(",", $bed->beds);
    $smarty->assign("option_ids", $option_ids);
    $smarty->assign("phase_options", $phase_options);
    $smarty->assign("bedrooms_hash", $bedrooms_hash);
}

$phases = Array();
$old_phase_name = '';

foreach ($phaseDetail as $k => $val) {
    $p = Array();
    $p['id'] = $val['PHASE_ID'];
    $p['name'] = $val['PHASE_NAME'];
    if ($val['PHASE_ID'] == $phaseId) {
        $old_phase_name = $val['PHASE_NAME'];
    }
    array_push($phases, $p);
}
$smarty->assign("phases", $phases);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $current_phase = phaseDetailsForId($phaseId);

    // Assign vars for smarty
    $smarty->assign("phaseObject", $current_phase[0]);
    $smarty->assign("bookingStatus", $current_phase[0]['BOOKING_STATUS_ID']);
    $smarty->assign("phasename", $current_phase[0]['PHASE_NAME']);
    $smarty->assign("launch_date", $current_phase[0]['LAUNCH_DATE']);
    $smarty->assign("completion_date", $current_phase[0]['COMPLETION_DATE']);
    $smarty->assign("remark", $current_phase[0]['REMARKS']);
    
    $towerDetail = fetch_towerDetails_for_phase($projectId, $phaseId);
    $smarty->assign("TowerDetails", $towerDetail);
    
    $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
    $phase_quantity_hash = array();
    foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
    $isLaunchUnitPhase = ProjectSupply::isLaunchUnitPhase($phaseId);
    $isInventoryCreated = ProjectSupply::isInventoryAdded($phaseId);
    $smarty->assign("isInventoryCreated", $isInventoryCreated);
    $smarty->assign("isLaunchUnitPhase", $isLaunchUnitPhase);
    $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Apartment']));
    $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Villa']));
    $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Plot']));
}
/* * ********************************** */
if (isset($_POST['btnSave'])) {
    $phasename = $_REQUEST['phaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    $isLaunchedUnitPhase = $_REQUEST['isLaunchUnitPhase'];

    // Assign vars for smarty
    $smarty->assign("phasename", $phasename);
    $smarty->assign("launch_date", $launch_date);
    $smarty->assign("completion_date", $completion_date);
    $smarty->assign("remark", $remark);

    $PhaseExists = searchPhase($phaseDetail, $phasename);
    if ($PhaseExists != -1 && $phasename != $old_phase_name) {
        header("Location:phase_edit.php?projectId=" . $projectId . "&phaseId=" . $phaseId . "&error=1");
    } else {
        // Flats Config
        $flats_config = array();
        foreach ($_REQUEST as $key => $value) {
            if (substr($key, 0, 9) == "flat_bed_") {
                $beds = substr($key, 9);
                $flats_config[$beds] = $value;
            }
        }

        // Villas Config
        $villas_config = array();
        foreach ($_REQUEST as $key => $value) {
            if (substr($key, 0, 10) == "villa_bed_") {
                $beds = substr($key, 10);
                $villas_config[$beds] = $value;
            }
        }

        // Update
        ############## Transaction ##############
        ResiProjectPhase::transaction(function(){
            global $projectId, $phaseId, $phasename, $launch_date, $completion_date, $remark, $towers;
            if($phaseId != '0'){
                //          Updating existing phase
                $phase = ResiProjectPhase::virtual_find($phaseId);
                $phase->project_id = $projectId;
                $phase->phase_name = $phasename;
                $phase->launch_date = $launch_date;
                $phase->completion_date = $completion_date;
                $phase->remarks = $remark;
                $phase->booking_status_id = (($_REQUEST['bookingStatus'] != -1) ? $_REQUEST['bookingStatus'] : null);
                $phase->save();

                if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                    $phase->add_towers($towers);
                }
                if(isset($_POST['options'])){
                    $arr = $_POST['options'];
                    $arr = array_diff($arr, array(-1));
                    
                    if(ProjectSupply::isInventoryAdded($projectId, $phaseId)){
                        $existingOptions = ProjectOptionsPhases::optionsForPhase($phaseId);
                        $removedOptions = array_diff($existingOptions, $arr);
                        if(empty($existingOptions) || !empty($removedOptions)){
                            header("Location:phase_edit.php?projectId=" . $projectId . "&phaseId=" . $phaseId . "&error=2");
                            exit;
                        }
                    }
                    $phase->reset_options($arr);
                }
            }
        });
        #########################################
        // Phase Quantity
        if (sizeof($flats_config) > 0) {
            foreach ($flats_config as $key => $value) {
                ProjectSupply::addEditSupply($projectId, $phaseId, 'apartment', $key, $value['supply'], $isLaunchedUnitPhase ? $value['launched'] : $value['supply']);
            }
        }
        if (sizeof($villas_config) > 0) {
            foreach ($villas_config as $key => $value) {
                ProjectSupply::addEditSupply($projectId, $phaseId, 'villa', $key, $value['supply'], $isLaunchedUnitPhase ? $value['launched'] : $value['supply']);
            }
        }

        if ($_POST['plotvilla'] != '') {
            $supply = $_POST['supply'];
            ProjectSupply::addEditSupply($projectId, $phaseId, 'plot', 0, $_POST['supply'], $_POST['launched']);
        }

        $towerDetail = fetch_towerDetails_for_phase($projectId, $phaseId);
        $smarty->assign("TowerDetails", $towerDetail);

        $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
        $phase_quantity_hash = array();
        foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
        $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Apartment']));
        $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Villa']));
        $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Plot']));

        var_dump($phase_quantity_hash);
        
        $phaseDetail = fetch_phaseDetails($projectId);
        $phases = Array();
        foreach ($phaseDetail as $k => $val) {
            $p = Array();
            $p['id'] = $val['PHASE_ID'];
            $p['name'] = $val['PHASE_NAME'];
            array_push($phases, $p);
        }
        $smarty->assign("phases", $phases);
        $loc = "Location:phase_edit.php?projectId=$projectId";
        if($preview == 'true') $loc = $loc."&preview=true";
        header($loc);
    }
} else if ($_POST['btnExit'] == "Exit") {
    if ($preview == 'true')
        header("Location:show_project_details.php?projectId=" . $projectId);
    else
        header("Location:ProjectList.php?projectId=" . $projectId);
}
/* * *********************************** */
?>
