<?php
	//$sever = "190.228.29.195";
$sever = "localhost:3306";
	
//$usuario = "semantec";
$usuario = "root";
$clave = "";
//$clave = "s3m4nt3c";
	
$base = "semantec";
	
$link = mysql_connect($sever, $usuario, $clave);
	
mysql_select_db($base, $link);

?>