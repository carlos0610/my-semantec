<?php

        function getFacturasCompras(){
               $sql = "SELECT distinct f.fco_id,f.fco_fecha ,f.files_id,f.fco_subtotal,fco_subtotal, f.fco_nota, p.prv_nombre, df.det_fco_descripcion 
                FROM factura_compra f, detalle_factura_compra df, proveedores p
                WHERE  f.estado = 1
                AND df.fco_id= f.fco_id
                AND f.prv_id= p.prv_id
                ";
                $sql.=$sqlaux;
               $sql .= " ORDER BY f.fco_fecha DESC";                 
           return $resultado = mysql_query($sql);       
        }
        
                function getFacturasComprasWithProveedorId($id){
               $sql = "SELECT distinct f.fco_id,f.fco_fecha ,f.files_id,f.fco_subtotal,fco_subtotal, f.fco_nota,  df.det_fco_descripcion 
                FROM factura_compra f, detalle_factura_compra df
                WHERE  f.estado = 1
                AND df.fco_id= f.fco_id
                AND f.prv_id= $id
                ";
                $sql.=$sqlaux;
               $sql .= " ORDER BY f.fco_fecha DESC";                 
           return $resultado = mysql_query($sql);       
        }

?>
