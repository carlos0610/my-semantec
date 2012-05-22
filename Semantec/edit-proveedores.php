<?php
        include("validar.php");
        $prv_id = $_POST["prv_id"];
        $prv_nombre = utf8_decode($_POST["prv_nombre"]);
        $prv_cuit = $_POST["prv_cuit"];
        $iva_id = $_POST["iva_id"];
        $prv_rubro = utf8_decode($_POST["prv_rubro"]);
        $zon_id = $_POST["zon_id"];
        $prv_direccion = utf8_decode($_POST["prv_direccion"]);
        $prv_telefono = $_POST["prv_telefono"];
        $prv_notas = utf8_decode($_POST["prv_notas"]);

        include("conexion.php");
        $sql = "UPDATE proveedores SET
        				prv_nombre = '$prv_nombre',
        				prv_cuit = '$prv_cuit',
        				iva_id = $iva_id,
        				prv_rubro = '$prv_rubro',
        				zon_id = $zon_id,
        				prv_direccion = '$prv_direccion',
        				prv_telefono = $prv_telefono,
        				prv_notas = '$prv_notas'
        		WHERE prv_id = $prv_id";
		mysql_query($sql);
		$_SESSION["prv_id"] = $prv_id;

		mysql_close();
		header("location:ver-alta-proveedores.php?action=2");

?>