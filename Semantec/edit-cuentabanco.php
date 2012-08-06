<?php
        include("validar.php");

        $nombre = utf8_decode($_POST["nombre"]);
        $cut_id = $_POST["cut_id"];
        $ban_id= $_POST["ban_id"];
        $id= $_POST["id"];
        $nro= $_POST["nro"];
        $cue_cbu= $_POST["cue_cbu"];
       
        
        include("conexion.php");      

             
        $sql = "UPDATE `cuentabanco` 
                SET `cut_id`=$cut_id,
                    `ban_id`=$ban_id,
                    `nro`='$nro',
                    `nombre`='$nombre',
                    `cbu`='$cue_cbu'
                WHERE `id`= $id";
                    echo $sql;   
		mysql_query($sql);
		$_SESSION["id"] = $id;
		mysql_close();
		header("location:form-edit-cuentabanco.php?id=$id&actualizo=1");

?>