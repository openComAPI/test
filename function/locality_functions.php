<?php

/* Created a URL given a label of locality */
function createLocalityURL($localityLabel, $cityLabel) {
    $localityLabel = trim(strtolower($localityLabel));
    $cityLabel = trim(strtolower($cityLabel));
    $cleanLocalityLabel = preg_replace( '/\s+/', '-', $localityLabel);
    return "property-in-".$cleanLocalityLabel."-".$cityLabel."-real-estate.php";
}

?>