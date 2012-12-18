<?php

function getProvincias(){
    
    $sql = "SELECT id,nombre,jurisdiccion FROM provincias ORDER BY nombre";
    $result = mysql_query($sql);
    
    return $result;
}


?>
