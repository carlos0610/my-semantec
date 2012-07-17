<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Área Configuración.";
        include("validar.php");
        include("conexion.php");


?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.datepick.js"></script>
  <script type="text/javascript" src="js/jquery.datepick-es.js"></script>
 
  <script type="text/javascript" src="js/validador.js"></script>
  </head>
  <body>
	      <?php	if($_SESSION["rol_id"] !=1){
			header("location:index-admin.php");
	}
?>
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec.png" width="470" height="100" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
         
         <span id="mensaje_top" style="text-align:right;">
             <a href="index-admin.php"><img src="images/home.png"  alt="volver index" title="volver index" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
             <?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">


      <h2>Panel de Configuración</h2>
      <div id="contenedor1">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          
      <a href="lista-bancos.php"><input type="button" value="Banco ABM" class="botones" /></a>
      <a href="lista-rubros.php"><input type="button" value="Rubro ABM" class="botones" /></a>

      <a href="lista-usuarios.php">
      <input type="button" value="Usuarios" class="botones" />
      </a>
      <a href="form-edit-alertas.php">
      <input type="button" value="Alertas" class="botones" />
      </a>
      </div>
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