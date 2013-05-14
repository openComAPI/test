<?php

$localityid = $_REQUEST['localityid'];
$smarty->assign("localityid", $localityid);

$cityId = $_REQUEST['c'];

if(isset($_POST['btnExit'])){
	header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
}

if (isset($_POST['btnSave'])) {

		$txtCityName			=	trim($_POST['txtCityName']);
		$txtCityUrl				=	trim($_POST['txtCityUrl']);
		$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
		$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
		$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
		$status					=	trim($_POST['status']);
		$desc					=	trim($_POST['desc']);
		$old_loc_url			=	trim($_POST['old_loc_url']);	

		
		$smarty->assign("txtCityName", $txtCityName);
		$smarty->assign("txtCityUrl", $txtCityUrl);
		$smarty->assign("old_loc_url", $old_loc_url);
		$smarty->assign("txtMetaTitle", $txtMetaTitle);
		$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
		$smarty->assign("txtMetaDescription", $txtMetaDescription);
		$smarty->assign("status", $status);	
		$smarty->assign("desc", $desc);
		
		 if( $txtCityUrl == '')   {
			 $ErrorMsg["txtCityUrl"] = "Please enter locality URL.";
		 } else {
				
				if(!preg_match('/^property-in-[a-z0-9\-]+\.php$/',$txtCityUrl)){
					$ErrorMsg["txtCityUrl"] = "Please enter a valid url that contains only small characters, numerics & hyphen";
				}
			}

		if( $txtMetaTitle == '')   {
			 $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
		   }
		if( $txtMetaKeywords == '')  {
			 $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
		   }
		if( $txtMetaDescription == '')  {
			 $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
		   }

		/*******locality url already exists**********/
			$qryLocalityUrl = "SELECT * FROM ".LOCALITY." WHERE URL = '".$txtCityUrl."'";
			if($localityid != '')
				$qryLocalityUrl .= " AND LOCALITY_ID != $localityid";
			echo $qryLocalityUrl;
			$resUrl     = mysql_query($qryLocalityUrl) or die(mysql_error());
			if(mysql_num_rows($resUrl)>0)
			{
				$ErrorMsg["urlLoc"] = "This URL Already exists";
			}
		/*******end locality url already exists*******/ 

		   if(!is_array($ErrorMsg))
		   {
				 $updateQry = "UPDATE ".LOCALITY." SET 
					 
					  META_TITLE			=	'".$txtMetaTitle."',
					  META_KEYWORDS		    =	'".$txtMetaKeywords."',
					  META_DESCRIPTION		=	'".$txtMetaDescription."',
					  ACTIVE				=	'".$status."',
					  URL					=	'".$txtCityUrl."',
					  DESCRIPTION			=	'".$desc."' WHERE LOCALITY_ID='".$localityid."'";
					   
				mysql_query($updateQry);
				insertUpdateInRedirectTbl($txtCityUrl,$old_loc_url);
			    header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
		}
		else
		{
			$smarty->assign("ErrorMsg", $ErrorMsg);
		}
	}	
	

if($localityid!=''){

	$localityDetailsArray	=   ViewLocalityDetails($localityid);
	$txtCityName			=	trim($localityDetailsArray['LABEL']);
	$txtCityUrl				=	trim($localityDetailsArray['URL']);
	$old_loc_url			=	trim($localityDetailsArray['URL']);
	$txtMetaTitle			=	trim($localityDetailsArray['META_TITLE']);
	$txtMetaKeywords		=	trim($localityDetailsArray['META_KEYWORDS']);
	$txtMetaDescription		=	trim($localityDetailsArray['META_DESCRIPTION']);
	$status					=	trim($localityDetailsArray['ACTIVE']);
	$desc					=	trim($localityDetailsArray['DESCRIPTION']);

	$smarty->assign("txtCityName", $txtCityName);
	$smarty->assign("txtCityUrl", $txtCityUrl);
	$smarty->assign("old_loc_url", $old_loc_url);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("status", $status);	
	$smarty->assign("desc", $desc);
}
 
?>
