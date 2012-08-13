<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Semantec - Servicio de Mantenimiento T&eacute;cnico.";
    include("validar.php");
    
    $fila_plazo_proveedor = -1;
    include("conexion.php");
    
    
    
    /* COMPROBAR SI HAY ALERTA DE ÓRDENES SIN ENVIAR A PROVEEDOR * #REFACTORIZAR# */
    
   $sql = "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 1
		    ORDER BY o.ord_alta DESC";
   
   $resultado = mysql_query($sql);
   $fila_orden_sinenviar = mysql_num_rows($resultado);   //Nro de órdenes sin enviar a proveedor.
    
   
    /* COMPROBAR SI HAY ALERTA DE PROVEEDOR* #REFACTORIZAR# */
    
        $sql0 =    "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 2
		    AND DATEDIFF(ord_plazo_proveedor,now()) <= (SELECT valor from parametros where par_id = 1) 
                    ORDER BY o.ord_alta DESC";
        $alerta_plazo_proveedor = mysql_query($sql0);
        $fila_plazo_proveedor = mysql_num_rows($alerta_plazo_proveedor);
        
        /* COMPROBAR SI HAY ALERTA DE ORDENES SIN CONFIRMAR A PROVEEDOR */
        
        
        $sql = "SELECT ord_id, ord_codigo, u.usu_login,ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p,usuarios u
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 9
                    AND o.usu_id = u.usu_id";
        $alerta_orden_sinaprobar = mysql_query($sql);
        $fila_orden_sinaprobar = mysql_num_rows($alerta_orden_sinaprobar);
        
     /* COMPROBAR SI HAY ALERTA DE PLAZO DE FINALIZACION DEL TRABAJO #REFACTORIZAR# */   
        
        $sql ="SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id > 8
		    AND DATEDIFF(ord_plazo,now()) <= (SELECT valor from parametros where par_id = 2) 
                    ORDER BY o.ord_alta DESC";
        $alerta_plazo_finalizado = mysql_query($sql);
        $fila_plazo_finalizado = mysql_num_rows($alerta_plazo_finalizado);
        

        //echo "FILA : ".$fila_plazo_proveedor;
        
        
        
       
?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  </head>
  <body onLoad="">
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec.png" width="578" height="102" alt="logo" /></a>
	  <!-- form login -->

<div id ="login">
         <span id="mensaje_top" style="text-align:right;">
          <?php if($_SESSION["rol_id"] == 1){?>
             Configuración
          <a href="form-configuracion.php"><img src="images/icono-formulario.jpg"  alt="configuración" title="configuración" width="32" height="32" border="none" valign="middle" hspace="8"></a>
          <?php } ?>
              
          <?php echo($_SESSION["usu_nombre"]); ?>
          <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8"></a>
         </span>

    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <h2></h2>
   
     <section class="cajas">
     <h3>Órdenes</h3>
      <p><a href="lista-ordenes.php" class="current">Ir al listado</a></p>
      <p><a href="form-alta-ordenes.php" class="current">Nueva orden</a></p>
      <p><a href=lista-req-ordenes.php class="current">Seguimiento de órdenes</a></p>
     </section>


     <section class="cajas">
     <h3>Proveedores</h3>
      <p><a href="lista-proveedores.php" class="current">Ir al listado</a></p>
      <p><a href="form-alta-proveedores.php" class="current">Nuevo proveedor</a></p>
     </section>
  

     <section class="cajas">
     <h3>Clientes</h3>
      <p><a href="lista-clientes.php" class="current">Ir al listado</a></p>
      <p><a href="form-alta-clientes.php" class="current">Nuevo cliente</a></p>
      <p><a href="form-alta-abonos.php" class="current">Registro abonos</a></p>
     </section>
      
           
     <section class="cajas">
     <h3>Cuenta corriente</h3>
      <p><a href="form-seleccionar-cliente.php" class="current">De cliente</a></p>
      <p><a href="form-seleccionar-proveedor.php?action=1" class="current">De proveedor</a></p>
     </section>
      
      <section class="cajas">
     <h3>Facturación</h3>
      <p><a href="lista-facturas.php" class="current">Ir al listado</a></p>
      <p><a href="ver-generar-factura-nueva.php?cli_id=1&ocultar=si" class="current">Nueva factura</a></p>
      <p><a href="reporte-iva.php" class="current">Reporte IVA</a></p>
      <p><a href="reporte-retenciones.php" class="current">Reporte Retenciones</a></p>
     </section>
      
      <section class="cajas">
     <h3>Compras</h3>
      <p><a href="lista-facturas-compra.php" class="current">Ir al listado</a></p>
      <p><a href="form-seleccionar-proveedor.php?action=2" class="current">Nueva compra</a></p>
     </section>
      
      
      
      
     <div class="clear">
     <table width="100%" border="0">
         
         <tr>
           <td colspan="5" ><div align="center"></div></td>
         </tr>
         <tr>
           <td colspan="5" >&nbsp;</td>
         </tr>
         <tr>
           <td colspan="5" class="botones"><div align="center">ALERTAS</div></td>
         </tr>
         <tr>
              <td width="18%">Órdenes sin enviar a proveedor:</td>
               
                <?php if ($fila_orden_sinenviar > 0) { ?>
         
    <td width="4%"><div align="right"><img src="images/warning.png" width="32" height="32"></div></td>
    <td width="36%"><h5><a href="#" onclick="popup('lista-alertas-sinenviar.php', 'Alerta')">Hay órdenes que no fueron enviadas a ningún proveedor.</h5></td>
          
         <?php } else { ?>
           
            <td width="7%"><div align="right"><img src="images/ok.png" width="32" height="32"></div></td>
            <td width="35%"><h5>Sin novedades</h5></td>
            
               <?php } ?>
            </tr>
         
         
         <tr>
           <td width="18%">Respuesta de proveedor:</td>
  
      <?php if ($fila_plazo_proveedor > 0) { ?>
         
    <td width="4%"><div align="right"><img src="images/warning.png" width="32" height="32"></div></td>
    <td width="36%"><h5><a href="#" onclick="popup('lista-alertas.php', 'Alerta')">Hay órdenes que no recibieron respuesta de proveedor </h5></td>
          
         <?php } else { ?>
           
            <td width="7%"><div align="right"><img src="images/ok.png" width="32" height="32"></div></td>
            <td width="35%"><h5>Sin novedades</h5></td>
            
               <?php } ?>
            </tr>
            <tr>
           <td width="18%">Órdenes aprobadas sin confirmar a proveedor:</td>
  
      <?php if ($fila_orden_sinaprobar > 0) { ?>
         
    <td width="4%"><div align="right"><img src="images/warning.png" width="32" height="32"></div></td>
    <td width="36%"><h5><a href="#" onclick="popup('lista-alertas-sinconfirmar.php', 'Alerta')">Hay órdenes que fueron aprobadas y no se confirmaron al proveedor </h5></td>
          
         <?php } else { ?>
           
            <td width="7%"><div align="right"><img src="images/ok.png" width="32" height="32"></div></td>
            <td width="35%"><h5>Sin novedades</h5></td>
            
               <?php } ?>
            </tr>
            
            
            <tr>
              <td width="18%">Órdenes por vencer:</td>
               
                <?php if ($fila_plazo_finalizado > 0) { ?>
         
    <td width="4%"><div align="right"><img src="images/warning.png" width="32" height="32"></div></td>
    <td width="36%"><h5><a href="#" onclick="popup('lista-alertas-finalizado.php', 'Alerta')">Hay órdenes que no fueron terminadas y están proximas a vencer.  </h5></td>
          
         <?php } else { ?>
           
            <td width="7%"><div align="right"><img src="images/ok.png" width="32" height="32"></div></td>
            <td width="35%"><h5>Sin novedades</h5></td>
            
               <?php } ?>
            </tr>
            </table>
                        

         
     </div>
   </div>
   <!--end contenedor-->



  </div>
   <!-- fin main --><!-- fin main --><!-- fin main --><!-- fin main --><!-- fin main -->
   
   <!--start footer-->
   <footer>
<?php
    include("footer.php");
?>
   </footer>
   <!--fin footer-->

   </body>
</html>
