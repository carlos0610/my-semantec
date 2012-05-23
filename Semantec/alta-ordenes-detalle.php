<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_POST["ord_id"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $ord_det_monto=$_POST["ord_det_monto"];
        $cli_id = $_POST["cli_id"];
        $prv_id = $_POST["prv_id"];
        $est_id = $_POST["est_id"];
        $ord_alta = date("Y-m-d");
        $ord_plazo = gfecha($_POST["ord_plazo"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];


        

        include("conexion.php");
        $sql =  "UPDATE ordenes SET 
        						est_id = $est_id
                                                        WHERE ord_id = $ord_id";
        echo $sql; 				  
	mysql_query($sql);//alta de la orden
       // $ord_id = mysql_insert_id();

        $sql2 = "INSERT INTO ordenes_detalle VALUES (
                                                        NULL,
                                                        '$ord_descripcion',
                                                        $ord_det_monto,
                                                        $ord_id,
                                                        '$ord_alta',
                                                        NULL,
                                                        0,
                                                        1
                                                )";
        mysql_query($sql2);

	$_SESSION["ord_id"] = $ord_id;

	mysql_close();

	header("location:ver-alta-ordenes.php?action=1");

?>