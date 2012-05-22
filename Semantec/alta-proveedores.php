<?php
        include("validar.php");
        $prv_nombre = $_POST["prv_nombre"];
        $prv_cuit = $_POST["prv_cuit"];
        $iva_id = $_POST["iva_id"];
        $prv_rubro = utf8_decode($_POST["prv_rubro"]);
        $zon_id = $_POST["zon_id"];
        $prv_direccion = utf8_decode($_POST["prv_direccion"]);
        $prv_telefono = $_POST["prv_telefono"];
        $prv_notas = utf8_decode($_POST["prv_notas"]);

        include("conexion.php");
        $sql = "INSERT INTO proveedores VALUES (
        										NULL,
        										'$prv_nombre',
        										'$prv_cuit',
        										$iva_id,
        										'$prv_rubro',
        										$zon_id,
        										'$prv_direccion',
        										$prv_telefono,
        										'$prv_notas'
        										)";
		mysql_query($sql);
		$_SESSION["prv_id"] = mysql_insert_id();

		mysql_close();
		header("location:ver-alta-proveedores.php?action=1");

?>