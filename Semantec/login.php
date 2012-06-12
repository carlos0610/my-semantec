<?php
	$usu_login = $_POST["usu_login"];
	$usu_clave = $_POST["usu_clave"];

	include("conexion.php");

	$sql = "SELECT usu_id,usu_nombre FROM usuarios WHERE usu_login = '$usu_login' AND usu_clave = '$usu_clave'";
	$resultado = mysql_query($sql);
	$cant = mysql_num_rows($resultado);


	if($cant != 1){
		header("location:index.php?error=1");
	}
	else{
		session_start();
		$_SESSION["login"] = "ok";
		$fila = mysql_fetch_array($resultado);
		$_SESSION["usu_nombre"] = $fila["usu_nombre"];
                $_SESSION["usu_id"]     = $fila["usu_id"];
		header("location:index-admin.php");
	}
	mysql_close();
?>