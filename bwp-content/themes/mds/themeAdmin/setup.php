<?php 
session_start();
if($_SESSION["loggedin"] == true){
    include '../../../../bwp-includes/settings.php';
    include 'information.php';

    $db->rawQuery('DELETE FROM bwp_settings_meta WHERE type_cat="image"');
    for ($i=0; $i < count($imageSize); $i++) { 
        $db->rawQuery('INSERT INTO bwp_settings_meta (type_cat, setType, type, type_meta) VALUES ("'.$imageSize[$i]['type_cat'].'", "'.$imageSize[$i]['setType'].'", "'.$imageSize[$i]['type'].'", "'.$imageSize[$i]['type_meta'].'");');
    }
}

?>