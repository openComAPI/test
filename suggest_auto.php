<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
AdminAuthentication();

if ( !isset($_REQUEST['term']) )
    exit;

$data = array();
if($_REQUEST['type'] == 'suburb')
{
    $rs = mysql_query('select LABEL, PRIORITY, SUBURB_ID FROM '.SUBURB.' where CITY_ID="'.$_REQUEST["cityId"].'" AND (LABEL like "'. mysql_real_escape_string($_REQUEST['term']) .'%" OR SUBURB_ID like "'. mysql_real_escape_string($_REQUEST['term']) .'%") order by LABEL ASC limit 0,10');
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' => $row['LABEL'] .' - '. $row['SUBURB_ID'],
                'value' => $row['SUBURB_ID']
            );
        }
    }
}
else if($_REQUEST['type'] == 'locality')
{
    $rs = mysql_query('select LABEL, PRIORITY, LOCALITY_ID FROM '.LOCALITY.' where CITY_ID="'.$_REQUEST["cityId"].'" AND (LABEL like "'. mysql_real_escape_string($_REQUEST['term']) .'%"  OR LOCALITY_ID like "'. mysql_real_escape_string($_REQUEST['term']) .'%")  order by LABEL ASC limit 0,10');
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' => $row['LABEL'] .' - '. $row['LOCALITY_ID'],
                'value' => $row['LOCALITY_ID']
            );
        }
    }
}
echo json_encode($data);
flush();
?>  