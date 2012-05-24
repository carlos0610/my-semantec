<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Semantec - Servicio de Mantenimiento T&eacute;cnico.";
        include("validar.php");
?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  </head>
  <body>
	
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
      <h2>Panel de control</h2>
   
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
      <p><a href="" class="current">ir al listado</a></p>
      <p><a href="" class="current">nueva cuenta</a></p>
     </section>
      
      <section class="cajas">
     <h3>Facturación</h3>
      <p><a href="" class="current">ir al listado</a></p>
     </section>
      
      <section class="cajas">
     <h3>Compras</h3>
      <p><a href="" class="current">ir al listado</a></p>
      <p><a href="" class="current">nueva compra</a></p>
     </section>
      
      
      
      
     <div class="clear"></div>
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
