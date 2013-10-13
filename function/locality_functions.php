<?php

/* Created a URL given a label of locality */
function createLocalityURL($localityLabel, $cityLabel, $id, $type) {
    $localityLabel = trim(strtolower($localityLabel));
    $cityLabel = trim(strtolower($cityLabel));
    $cleanLocalityLabel = preg_replace( '/\s+/', '-', $localityLabel);
    $id = getIdByType( $id, $type );
    return $cityLabel."/"."property-sale-".$localityLabel.'-'.$id;
}

function createProjectURL($city, $locality, $builderName, $projectName, $projectId){
    $city = trim(strtolower($city));
    $locality = trim(strtolower($locality));
    $builder = trim(strtolower($builderName));
    $project = trim(strtolower($projectName));
    $projectId = getIdByType($projectId, 'project');
    $projectURL = $city.'/'.$locality.'/'.$builder.'-'.$project.'-'.$projectId;
    return preg_replace( '/\s+/', '-', $projectURL);
}

function createBuilderURL($builderName, $builderId){
    $builder = trim(strtolower($builderName));
    $builderId = getIdByType($builderId, 'builder');
    $builderURL = $builder.'-'.$builderId;
    return preg_replace( '/\s+/', '-', $builderURL);
}   

function getIdByType( $id, $id_type ) {
    $txt = "";
    if ( !empty( $id_type ) && is_numeric( $id ) ) {
        $add_value = -1;
        switch( $id_type ) {
            case 'city':
                $add_value = 0;
                break;
            case 'suburb':
                $add_value = 10000;
                break;
            case 'locality':
                $add_value = 50000;
                break;
            case 'builder':
                $add_value = 100000;
                break;
            case 'project':
                $add_value = 500000;                                                                                                                  
                break;
            case 'property':
                $add_value = 5000000;
                break;
        }
        if ( $add_value != -1 ) {
            $txt = $add_value + ( int )$id;
            $txt = "$txt";
        }
    }
    return $txt;
}

function getTypeById( $id ) { 
    if ( !is_numeric( $id ) ) { 
        return -1; 
    }   
    $id = (int)trim( $id );
    if ( $id < 1000 ) {                                                                                                                               
        return URL_CITY_TYPE;
    }   
    elseif ( $id > 10000 && $id < 50000 ) { 
        return URL_SUBURB_TYPE;
    }   
    elseif ( $id > 50000 && $id < 100000 ) { 
        return URL_LOCALITY_TYPE;
    }   
    elseif ( $id > 100000 && $id < 500000 ) { 
        return URL_BUILDER_TYPE;
    }   
    elseif ( $id > 500000 && $id < 1000000 ) { 
        return URL_PROJECT_TYPE;
    }   
    elseif ( $id > 5000000 && $id < 10000000 ) { 
        return URL_PROPERTY_TYPE;
    }   
    return 0;
}

?>
