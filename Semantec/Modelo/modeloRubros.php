<?php

function getRubrosAll(){
    
    $sql = "SELECT rub_id,rub_nombre FROM rubros WHERE estado = 1;";
    $result = mysql_query($sql);
    
    return $result;
}

function getRubroNameById($id){
    $sql =  "SELECT rub_nombre FROM rubros WHERE rub_id = $id";
    $result = mysql_query($sql);
    return $result;
}



?>
