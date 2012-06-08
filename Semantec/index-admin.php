<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Semantec - Servicio de Mantenimiento T&eacute;cnico.";
    include("validar.php");
    
    $fila_plazo_proveedor = -1;
    include("conexion.php");
    
    /* COMPROBAR SI HAY ALERTA DE PROVEEDOR* #REFACTORIZAR# */
    
        $sql0 =    "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 2
		    AND DATEDIFF(ord_plazo_proveedor,now()) = 1 
                    ORDER BY o.ord_alta DESC";
        $alerta_plazo_proveedor = mysql_query($sql0);
        $fila_plazo_proveedor = mysql_num_rows($alerta_plazo_proveedor);
        
     /* COMPROBAR SI HAY ALERTA DE PLAZO DE FINALIZACION DEL TRABAJO #REFACTORIZAR# */   
        
        $sql ="SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id > 8
		    AND DATEDIFF(ord_plazo,now()) = 1 
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
    <a href="#" id="logo"><img src="images/semantec-logo.jpg" width="470" height="100" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
         <span id="mensaje_top" style="text-align:right;">
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
     <h3>Ordenes</h3>
      <p><a href="lista-ordenes.php" class="current">ir al listado</a></p>
      <p><a href="form-alta-ordenes.php" class="current">nueva orden</a></p>
      <p><a href=lista-req-ordenes.php class="current">seguimiento de ordenes</a></p>
     </section>


     <section class="cajas">
     <h3>Proveedores</h3>
      <p><a href="lista-proveedores.php" class="current">ir al listado</a></p>
      <p><a href="form-alta-proveedores.php" class="current">nuevo proveedor</a></p>
     </section>
  

     <section class="cajas">
     <h3>Clientes</h3>
      <p><a href="lista-clientes.php" class="current">ir al listado</a></p>
      <p><a href="form-alta-clientes.php" class="current">nuevo cliente</a></p>
     </section>
      
           
     <section class="cajas">
     <h3>Cuenta corriente</h3>
      <p><a href="form-seleccionar-cliente.php" class="current">de cliente</a></p>
      <p><a href="form-seleccionar-proveedor.php?action=1" class="current">de proveedor</a></p>
     </section>
      
      <section class="cajas">
     <h3>Facturación</h3>
      <p><a href="lista-facturas.php" class="current">ir al listado</a></p>
      <p><a href="ver-generar-factura-nueva.php?cli_id=1&ocultar=si" class="current">nueva factura</a></p>
     </section>
      
      <section class="cajas">
     <h3>Compras</h3>
      <p><a href="" class="current">ir al listado</a></p>
      <p><a href="form-seleccionar-proveedor.php?action=2" class="current">nueva compra</a></p>
     </section>
      
      
      
      
     <div class="clear">
     <table width="100%" border="0">
         
         <tr>
           <td colspan="5" class="botones"><div align="center">ALERTAS</div></td>
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
              <td width="18%">Órdenes finalizadas:</td>
               
                <?php if ($fila_plazo_finalizado > 0) { ?>
         
    <td width="4%"><div align="right"><img src="images/warning.png" width="32" height="32"></div></td>
    <td width="36%"><h5><a href="#" onclick="popup('lista-alertas-finalizado.php', 'Alerta')">Hay órdenes que no fueron terminadas y vence el plazo mañana.  </h5></td>
          
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
