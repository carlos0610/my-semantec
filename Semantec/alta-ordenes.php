<?php
        include("validar.php");
        include("funciones.php");

        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $cli_id = $_POST["cli_id"];
        $prv_id = $_POST["prv_id"];
        $est_id = $_POST["est_id"];
        $ord_alta = date("Y-m-d");
        $ord_plazo = gfecha($_POST["ord_plazo"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];


        

        include("conexion.php");
        $sql = "INSERT INTO ordenes VALUES (
        							NULL,
								'$ord_codigo',
        							'$ord_descripcion',
        						         $cli_id,
        							 $prv_id,
        							 $est_id,
        							'$ord_alta',
        							'$ord_plazo',
        							 $ord_costo,
                                                                 $ord_venta,
                                                                 1
        				    )";
	mysql_query($sql);//alta de la orden
        echo $sql;
        
        $ord_id = mysql_insert_id();

        $sql2 = "INSERT INTO ordenes_detalle VALUES (
                                                        NULL,
                                                        '$ord_descripcion',
                                                        0,
                                                        '$ord_id',
                                                        '$ord_alta',
                                                        NULL,
                                                        0,
                                                        1
                                                )";
        mysql_query($sql2);
        echo $sql2;
	$_SESSION["ord_id"] = $ord_id;

	mysql_close();
	header("location:ver-alta-ordenes.php?action=1");

?>