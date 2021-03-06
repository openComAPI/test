<?php
//error_reporting(1);
//ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	
	
	
	if(!isset($_REQUEST['dbName']))
		$dbName  = 'staging_project';
	else 
		$dbName  = $_REQUEST['dbName'];
	
	$qry = "SELECT DISTINCT(TABLE_NAME) as TABLE_NAME FROM audit";
	$res = mysql_query($qry) or die(mysql_query());
	$arrName = array();
	
	while($data = mysql_fetch_assoc($res))
	{
		$tblName = $data['TABLE_NAME'];		
		$arrName[] = $tblName;
	}
	$arrName[] = 'comments_history';
	//$arrName[] = 'resi_builder';
	//$arrName[] = 'locality';
	//$arrName[] = 'suburb';
	foreach($arrName as $val)
	{
		createTableStructure($val,$dbName);
	}
	AdminAuthentication();
	
	
	
	/**********function for create audit table for triger use******************/
	function createTableStructure($tblName,$dbName)
	{
		$qry  = "SELECT COLUMN_NAME,COLUMN_DEFAULT,DATA_TYPE,NUMERIC_PRECISION,COLUMN_TYPE,COLUMN_DEFAULT,IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS
		WHERE
		table_name = '$tblName' AND  TABLE_SCHEMA = '$dbName'";
		$res  = mysql_query($qry) or die(mysql_error());
	
	
	
		$strTriger = "CREATE TRIGGER after_".$tblName."_update <br>
    			  AFTER UPDATE ON  ".$tblName."
    		     <br> FOR EACH ROW ";
	
		$trigerForInsert = "CREATE TRIGGER after_".$tblName."_insert
    				AFTER INSERT ON ".$tblName."
    				FOR EACH ROW ";
	
		$tblName = "_t_".$tblName;
	
		$strTriger .="<br> &nbsp;&nbsp;&nbsp;INSERT INTO ".$tblName." SET ";
		$trigerForInsert .="<br> &nbsp;&nbsp;&nbsp;INSERT INTO ".$tblName." SET ";
	
		$qryStr = 'CREATE TABLE '.$tblName." ( _t_transaction_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,<br>";
	
		$columns = array();
		$cnt = 0;
		while($data = mysql_fetch_assoc($res))
		{
			$cnt++;
			$strTriger .= "&nbsp;&nbsp;&nbsp;".$data['COLUMN_NAME']." = NEW.".$data['COLUMN_NAME'].",<br>";
			$trigerForInsert .= "&nbsp;&nbsp;&nbsp;".$data['COLUMN_NAME']." = NEW.".$data['COLUMN_NAME'].",<br>";
			if($data['IS_NULLABLE'] == 'NO')
				$nl = 'NOT NULL';
			else
				$nl = 'NULL';
	
			if($data['COLUMN_DEFAULT'] == '')
				$dflt = '';
			else
				$dflt = "DEFAULT '".$data['COLUMN_DEFAULT']."'";
	
			$qryStr .= $data['COLUMN_NAME']." ".$data['COLUMN_TYPE']." ".$nl." ".$dflt.",<br>";
			$columns[] = $data['COLUMN_NAME'];
		}
		$qryStr .="_t_transaction_date DATETIME,
				 <br> _t_operation enum('U', 'I', 'D'),
				 <br>_t_user_id INT(0)";
		//echo $qryStr = $qryStr." )";
	
		$strTriger .= "&nbsp;&nbsp;&nbsp;_t_transaction_date = NOW(),<br>
			      &nbsp;&nbsp;&nbsp;_t_operation = 'U',<br>
			      &nbsp;&nbsp;&nbsp;_t_user_id   = '0'";
	
		$trigerForInsert .= "&nbsp;&nbsp;&nbsp;_t_transaction_date = NOW(),<br>
					     &nbsp;&nbsp;&nbsp;_t_operation = 'I',<br>
					     &nbsp;&nbsp;&nbsp;_t_user_id   = '0'";
	
		$strForTrgrInsrt = implode(",", $columns);
		$newInsertQry = "INSERT INTO
		$tblName ( $strForTrgrInsrt,_t_transaction_date,_t_operation,_t_user_id)
		SELECT
		$strForTrgrInsrt,NOW(),'I','0'
		FROM ".str_replace("_t_","",$tblName);
	
		$trigerForDelete = str_replace("_update","_delete",$strTriger);
		$trigerForDelete = str_replace("AFTER UPDATE","AFTER DELETE",$trigerForDelete);
		$trigerForDelete = str_replace("operation = 'U'","operation = 'D'",$trigerForDelete);
		$trigerForDelete = str_replace("NEW.","OLD.",$trigerForDelete);
		echo "-- ".$cnt." Here are audit table/triger and starting insert statement for ".str_replace("_t_","",$tblName)." Table<br><br>";
		echo $qryStr = $qryStr." );<br><br>";
		echo $strTriger.";<br><br>";
		echo $trigerForInsert.";<br><br>";
		echo $trigerForDelete.";<br><br>";
		echo $newInsertQry.";<br><br><br><br>";
	}
	
	
?>