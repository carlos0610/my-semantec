<?php

function getProvincias(){
    
    $sql = "SELECT id,nombre FROM provincias ORDER BY nombre";
    $result = mysql_query($sql);
    
    return $result;
}


?>
