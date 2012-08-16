<?php
	function getClientesWithSucursalId($id){
		 $sql = "SELECT sucursal_id,sucursal,cli_id,cli_nombre,p.nombre as provincia 
           FROM clientes,ubicacion u,provincias p, partidos pa,localidades l
           WHERE 
 	   clientes.ubicacion_id = u.id
           AND u.provincias_id = p.id
           AND u.partidos_id = pa.id
           AND u.localidades_id = l.id
           AND clientes.estado = 1
           AND sucursal_id = $id
           AND id_abono is null
           ORDER BY cli_nombre,provincia";
             return       $Clientes = mysql_query($sql);
             
	}
        
        function getClienteNombreCompletoWithId($id){
           $sql = "SELECT sucursal_id,sucursal,cli_id,cli_nombre,p.nombre as provincia 
           FROM clientes,ubicacion u,provincias p, partidos pa,localidades l
           WHERE 
 	   clientes.ubicacion_id = u.id
           AND u.provincias_id = p.id
           AND u.partidos_id = pa.id
           AND u.localidades_id = l.id
           AND clientes.estado = 1
           AND clientes.cli_id=$id
           ORDER BY cli_nombre,provincia"; 
           $Clientes = mysql_query($sql);
            $fila = mysql_fetch_array($Clientes); 
            $nombrecompleto=$fila["cli_nombre"];
            $nombrecompleto.=' ( ';
            $nombrecompleto.=$fila["provincia"];
            $nombrecompleto.=' / ';
            $nombrecompleto.=$fila["sucursal"];
             $nombrecompleto.=' )'; 
            
            return $nombrecompleto;
            }
            function setClienteEsAbono($idCliente,$idAbono){
                 $sql =" UPDATE `clientes` 
                         SET `id_abono`=$idAbono
                         WHERE cli_id=$idCliente
                     ";
                 return $Clientes = mysql_query($sql);
            }
        
?>
