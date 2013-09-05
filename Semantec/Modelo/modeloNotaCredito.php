<?php

        function getMontoTotalWhitGrupoNotaId($gfn_id){
           $sql = "select SUM( det_nrc_precio) 
                   from detalle_nota_credito 
                   Where nrc_id = $gfn_id ";  
           return $factura = mysql_query($sql);       
        }
               
       function getMontoTotalWhitFavId($fav_id){
           $sql = "select nrc_id,
                          SUM( det_nrc_precio) as monto , 
                          (select nrc_codigo 
                           from nota_credito n 
                           where n.nrc_id = d.nrc_id and n.grupo_fac_pago is null) as codigo_nc
                   from detalle_nota_credito d
                   Where nrc_id = (select grupo_nota_credito 
                                   from factura_venta 
                                   Where fav_id =$fav_id) ";  
           return $factura = mysql_query($sql);   
        }
        
        function getNCIDWhitgrupoFavId($grupo_fav_idpago){
           $sql = "select nrc_id, nrc_codigo 
                           from nota_credito n 
                           where  n.grupo_fac_pago = $grupo_fav_idpago";  
           return $factura = mysql_query($sql);   
        }
        
        function getmontoTotalWhitNCID($NC_id){
           $sql = "select SUM( det_nrc_precio) as monto 
                   from detalle_nota_credito 
                   where nrc_id = $NC_id"; 
           return $factura = mysql_query($sql);   
        }
        
         function getNCWhitCliId($cli_id){
           $sql = "select * from nota_credito where cli_id =$cli_id order by nrc_fecha asc "; 
           return $factura = mysql_query($sql);   
        }
        

        
?>
