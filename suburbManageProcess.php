<?php

    $accessSuburb = '';
    if( $suburbAuth == false )
       $accessSuburb = "No Access";
    $smarty->assign("accessSuburb",$accessSuburb);
    
    $smarty->assign("sort",$_GET['sort']);
    $smarty->assign("page",$_GET['page']);
    $cityId = $_REQUEST['citydd'];
    $smarty->assign('cityId',$cityId);
    /***********************************************************/
    /**
     * *********************************
     *  Get Sort And Page From  the URL
     * *********************************
     **/
    if(isset($_GET['page'])) {
        $Page = $_GET['page'];
    } else {
        $Page = 1;
    }
    //$RowsPerPage = DEF_PAGE_SIZE;
    $RowsPerPage ='20';
    $PageNum = 1;
    if(isset($_GET['page'])) {
        $PageNum = $_GET['page'];
    }
    $Offset = ($PageNum - 1) * $RowsPerPage;
    /**
     * *************************
     *  Create Sort For Pagging
     * *************************
     **/

    $Self = $_SERVER['PHP_SELF'];
    $NumberOnly = '[0-9]';
    $Sorting .= "<a href=\"$Self?page=1&sort=1&citydd={$cityId}\">" . $NumberOnly . "</a>";
    $LetterLinks = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    for ($i = 0; $i < count($LetterLinks); $i++) {
        $Sorting .= "&nbsp;";
        $Sorting .= "<a href=\"$Self?page=1&sort=".$LetterLinks[$i]."&citydd={$cityId}\">".$LetterLinks[$i]."</a>";
    }
    $Sorting .= "&nbsp;&nbsp;&nbsp;";
    $Sorting .= "<a href=\"$Self?page=1&sort=all&citydd={$cityId}\">View All</a>";
    /**
     * *******************
     *  Query For Pagging
     * *******************
     **/
     $localityDataArr = array();

    if($_REQUEST['suburbUrl']!=''){
        $QueryMember = "Select * FROM ".SUBURB." WHERE URL = '".$_REQUEST['suburbUrl']."' ORDER BY SUBURB_ID DESC";
    }
    else if ($_GET['sort'] == "1") {
        $QueryMember = "SELECT * FROM ".SUBURB." WHERE LABEL BETWEEN '0' AND '9'
            AND CITY_ID ='".$cityId ."' ORDER BY SUBURB_ID DESC";
    } else if ($_GET['sort'] == "all") {
        $QueryMember = "SELECT * FROM ".SUBURB." WHERE 
            CITY_ID ='".$cityId ."'  ORDER BY SUBURB_ID DESC";
    } else {
        $QueryMember = "SELECT * FROM ".SUBURB." WHERE  LEFT(LABEL,1)='".$_GET['sort']."'
            AND  CITY_ID ='".$cityId ."' ORDER BY SUBURB_ID DESC";
    }

    //echo $QueryMember;
    $QueryExecute 	= mysql_query($QueryMember) or die(mysql_error());
    $NumRows 		= mysql_num_rows($QueryExecute);
    $smarty->assign("NumRows",$NumRows);

    /**
     * *********************************
     *  Create Next and Previous Button
     * *********************************
     **/
    $PagingQuery = "LIMIT $Offset, $RowsPerPage";
    $QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;

    while($dataArr2 = mysql_fetch_assoc($QueryExecute_1)) {
                            array_push($localityDataArr, $dataArr2);
                     }

    $smarty->assign("localityDataArr", $localityDataArr);
    $MaxPage = (ceil($NumRows/$RowsPerPage))?ceil($NumRows/$RowsPerPage):'1' ;
    $Num = $_GET['num'];
    $Sort = $_GET['sort'];
    if ($PageNum > 1) {
        $Page = $PageNum - 1;
        $Prev = " <a href=\"$Self?page=$Page&sort=$Sort&citydd={$cityId}\">[Prev]</a> ";
        $First = " <a href=\"$Self?page=1&sort=$Sort&citydd={$cityId}\">[First Page]</a> ";
    } else {
        $Prev  = ' [Prev] ';
        $First = ' [First Page] ';
    }
    if ($PageNum < $MaxPage) {
        $Page = $PageNum + 1;
        $Next = " <a href=\"$Self?page=$Page&sort=$Sort&citydd={$cityId}\">[Next]</a> ";
        $Last = " <a href=\"$Self?page=$MaxPage&sort=$Sort&citydd={$cityId}\">[Last Page]</a> ";
    } else {
        $Next = ' [Next] ';
        $Last = ' [Last Page] ';
    }
    $Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";

    $smarty->assign("Pagginnation", $Pagginnation);
    $smarty->assign("Sorting", $Sorting);
    $statusArray = array("0"=>"Inactive","1"=>"Active"); 
    $smarty->assign("statusArray", $statusArray);

    //calling function for all the cities

    $cityArray = getAllCities();
    $smarty->assign("cityArray", $cityArray);
    $smarty->assign('dirname',$dirName);



    //adding new suburb
    $suburbSelect = Array();
    $QueryMember = "SELECT SUBURB_ID as id, LABEL as label, parent_suburb_id FROM ".SUBURB." WHERE 
            CITY_ID ='".$cityId ."'  ORDER BY LABEL ASC";

    $QueryExecute   = mysql_query($QueryMember) or die(mysql_error());
    while ($dataArr = mysql_fetch_array($QueryExecute))
    {
           array_push($suburbSelect, $dataArr);
    }
    $smarty->assign("suburbSelect", $suburbSelect);
    $smarty->assign("suburbUrl", $_REQUEST['suburbUrl']);
   // print_r($suburbSelect);



    // parent child suburb array




    
    //$str = json_encode(getHierArr($cityId));

    
    //$smarty->assign("suburb_str", $str);




?>