<?php
    $titulo = "Semantec - Servicio de Mantenimiento T&eacute;cnico."
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
    <form method="post">
      <table>
        <tr>
          <td>usuario</td>
          <td><input type="text" id="usu_login" name="usu_login" class="campos"></td>
          <td>clave</td>
          <td><input type="password" id="usu_clave" name="usu_clave" class="campos"></td>
          <td><input type="submit" value="ingresar" class="botones"></td>
        </tr>
        </tr>
      </table>
    </form>
    </div>

    </header>
    <!--fin header-->


    <!--start contenedor1-->

    <div id="contenedor1">
   
      <div class="derecha">
      <img src="images/picture2.png" width="550" height="316"  alt="banner">
    </div>
     <header>
      <hgroup>
          <h1>Semantec </h1>
          <h2>Servicio de Mantenimiento T&eacute;cnico</h2>
      </hgroup>
     </header>
    </div>
    <!--fin contenedor1-->


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
