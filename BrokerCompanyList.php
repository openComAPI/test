<?php

/**
 * @author AKhan
 * @copyright 2013
 */

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
date_default_timezone_set('Asia/Kolkata');
AdminAuthentication();	
include("modelsConfig.php");
include('brokercompanyManageProcess.php');

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."manageBrokerCompany.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");




?>