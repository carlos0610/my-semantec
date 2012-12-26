<?php
    function getCobroWithGrupoFav($fav_id){
        
        $sql = "SELECT Select id , usu_id , grupo_fav_id , fecha  , cli_id , monto_total from cobros Where grupo_fav_id=$fav_id AND estado=1";
                
                return $detallesPago = mysql_query($sql);
        
    }

?>
