<?php

    /*******Auth code******/
    $builderAuth = isUserPermitted('builder', 'manage');
    $smarty->assign("builderAuth", $builderAuth);
    
    $bulkProjUpdateAuth = isUserPermitted('bulk-project-update', 'read');
    $smarty->assign("bulkProjUpdateAuth", $bulkProjUpdateAuth);
    
    $cityAuth = isUserPermitted('city', 'manage');
    $smarty->assign("cityAuth", $cityAuth);
    
    $dailyPerformanceReportAuth = isUserPermitted('daily-performance-report', 'read');
    $smarty->assign("dailyPerformanceReportAuth", $dailyPerformanceReportAuth);
    
    $dataCollectionFlowAuth = isUserPermitted('data-clloection-flow', 'read');
    $smarty->assign("dataCollectionFlowAuth", $dataCollectionFlowAuth);
    
    $imageAuth = isUserPermitted('image', 'add');
    $smarty->assign("imageAuth", $imageAuth);
    
    $labelAuth = isUserPermitted('label', 'add');
    $smarty->assign("labelAuth", $labelAuth);
    
    $localityAuth = isUserPermitted('locality', 'manage');
    $smarty->assign("localityAuth", $localityAuth);
    
    $migrateAuth = isUserPermitted('migrate', 'perform');
    $smarty->assign("migrateAuth", $migrateAuth);
    
    $prokectAuth = isUserPermitted('project', 'manage');
    $smarty->assign("prokectAuth", $prokectAuth);
    
    $suburbAuth = isUserPermitted('suburb', 'manage');
    $smarty->assign("suburbAuth", $suburbAuth);
    
    $urlAuth = isUserPermitted('url', 'redirect');
    $smarty->assign("urlAuth", $urlAuth);
        
    $brokerAuth = isUserPermitted('broker', 'manage');
    $smarty->assign("brokerAuth", $brokerAuth);
    
    $urlEditAuth = isUserPermitted('url-edit', 'url-edit');
    $smarty->assign("urlEditAuth", $urlEditAuth);
     $urlEditAccess = 0;
    if($urlEditAuth == true)
        $urlEditAccess = 1;
    $smarty->assign("urlEditAccess",$urlEditAccess);
    
    $specialAccessAuth = isUserPermitted('projectSpecialAttrs', 'manage');
    $smarty->assign("specialAccessAuth", $specialAccessAuth);
    $specialAccess = 0;
    if($specialAccessAuth == true)
        $specialAccess = 1;
    $smarty->assign("specialAccess",$specialAccess);
?>