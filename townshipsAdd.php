<?php
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
        include("modelsConfig.php");
	include("builder_function.php");
	AdminAuthentication();
	include('townshipsAddProcess.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."townshipsAdd.tpl");
	include("townships_suggest_auto.php");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
