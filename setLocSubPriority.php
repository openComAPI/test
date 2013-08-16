<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();
$cityId = $_GET['cityId'];
$highPrio = getAvaiHighPriority($cityId);
?>
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript"> 
jQuery(document).ready(function(){
    $('#suburbsearch').autocomplete({source:"suggest_auto.php?type=suburb&cityId=<?php echo $cityId?>", minLength:2});
    $('#localitysearch').autocomplete({source:"suggest_auto.php?type=locality&cityId=<?php echo $cityId?>", minLength:2});
});
jQuery(document).ready(function(){
    $( "#priority_form").submit(function() {
        if($('#suburbsearch').val() === '' && $('#localitysearch').val() === ''){
            alert("Please add suburb/priority");
            return false;
        }
        var sub         = $('#suburbsearch').val();
        var loc         = $('#localitysearch').val();
        var prior       = $('#priority').val();
        var cityId      = $('#cityId').val();
        var autoadjust  = $("#autoadjust").is(':checked') ? 1 : 0;
        $.ajax({
            type: "POST",
            url: '/savePriority.php',
            data: { sub: sub, loc: loc, prio:prior, autoadjust:autoadjust, cityId:cityId },
            success:function(msg){
               if(msg == 1){
                   alert("Priority Successfully updated");
                   parent.location.reload(true); 
               }
            }
        })
  });
});
function show_loc_inst(){
    var w = window.open("Surprise", "_blank",'width=300,height=120');
    var d = w.document.open();
    d.write("<!DOCTYPE html><html><body>Check the checkbox to auto shift the priorities, if desired.If this is not selected, then multiple areas  could be at the same priority(which is fine, if that is what you want)</body></html>");
    d.close();
}
</script>
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.2.custom.css" />
<style type="text/css">
<!--
/* style the auto-complete response */
li.ui-menu-item { font-size:12px !important; }
-->
</style> 
<TABLE cellSpacing=1 cellPadding=4 width="80%" align=center border=0>
<form id="priority_form" onsubmit="return false;">
  <TBODY>
    <TR class = "headingrowcolor">
        <TD class=whiteTxt width=12% align="right">Suburb Name or Id:</TD>
        <TD class=whiteTxt width=15% align="left"><input type="text" id="suburbsearch" value="<?php if($_GET['mode']=='edit' && $_GET['type']=='SUBURB'){ echo $_GET['id'];}?>" /></TD>
    </TR>
    <TR>
        <TD class=whiteTxt width=12% align="right">Locality Name or Id:</TD>
        <TD class=whiteTxt width=15% align="left"><input type="text" id="localitysearch" value="<?php if($_GET['mode']=='edit' && $_GET['type']=='LOCALITY'){ echo $_GET['id'];}?>" /></TD>
    </TR>
    <TR>
        <TD class=whiteTxt width=12% align="right">Priority:</TD>
        <TD class=whiteTxt width=15% align="left"><input type="text" id="priority" value="<?php echo $highPrio;?>" /></TD>
    </TR>
    <TR>
        <TD class=whiteTxt width=12% align="right"><input type="checkbox" name="autoadjust" id="autoadjust" value="" /></TD>
        <TD class=whiteTxt width=15% align="left">Auto Adjust Priorities&nbsp;<img src="images/exclamation.png" id="autoimg" border="0" onclick="show_loc_inst();" style="cursor:pointer;" /></TD>
    </TR>
    <TR>
        <TD class=whiteTxt width=12% align="center"><input type="hidden" name="cityId" id="cityId" value="<?php echo $cityId;?>" /></TD>
        <TD class=whiteTxt width=15% align="center"><input type="submit" id="submit" name="submit" value="Add Priority" /></TD>
    </TR>
    
    
  </TBODY>
</FORM>
</TABLE>

<div id="inline2" style="display: none;">
	<p>
		<img src="instruction_locality.png" /> &nbsp;&nbsp; <a href="javascript:;" onclick="$.fancybox.close();">Close</a>
	</p>
</div>