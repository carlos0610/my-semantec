<?php

include("validar.php");
include("funciones.php");


$prv_id= $_GET["usuario"];


if ($prv_id==1) {
    echo("<?xml version=\"1.0\" ?><existe>true</existe>");
} else {
    echo("<?xml version=\"1.0\" ?><existe>false</existe>");
}
?>