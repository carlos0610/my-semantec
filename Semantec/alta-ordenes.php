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
        $usu_nombre = $_SESSION["usu_nombre"]; 
        $est_nombre = $_POST["est_nombre"];

        

        include("conexion.php");
        $sql = "INSERT INTO ordenes (ord_codigo,ord_descripcion,cli_id,prv_id,est_id,ord_alta,ord_plazo,ord_costo,ord_venta,estado) VALUES (
        							
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
        $_SESSION["query"] = $sql;
        echo $sql;
        
        $ord_id = mysql_insert_id();

        $sql2 = "INSERT INTO ordenes_detalle VALUES (
                                                        NULL,
                                                        '$ord_descripcion',
                                                        0,
                                                        '$ord_id',
                                                        '$ord_alta',
                                                        NULL,
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre'
                                                )";
        mysql_query($sql2);
        echo $sql2;
	$_SESSION["ord_id"] = $ord_id;
        mysql_close();
	header("location:ver-alta-ordenes.php?action=1");

?>