<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_POST["ord_id"];
        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $cli_id = $_POST["cli_id"];
        $prv_id = $_POST["prv_id"];
        $est_id = $_POST["est_id"];
        $ord_alta = date("Y-m-d");    // << -------
        $ord_plazo = gfecha($_POST["ord_plazo"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];


        

        include("conexion.php");
        $sql = "UPDATE ordenes SET 
					ord_codigo = '$ord_codigo',
        				ord_descripcion = '$ord_descripcion',
        				cli_id = $cli_id,
        				prv_id = $prv_id,
        				est_id = $est_id,
                                        ord_alta= '$ord_alta',
        				ord_plazo = '$ord_plazo',
        				ord_costo = $ord_costo,
                                        ord_venta = $ord_venta
        			WHERE ord_id = $ord_id";

	mysql_query($sql);//modificacion de la orden


	$_SESSION["ord_id"] = $ord_id;

	mysql_close();
	header("location:ver-alta-ordenes.php?action=2");

?>