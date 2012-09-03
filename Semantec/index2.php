<?php
    $titulo = "Semantec - Servicio de Mantenimiento T&eacute;cnico."
?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>    
  <style type="text/css">
<!--
.Estilo1 {font-family: Georgia, "Times New Roman", Times, serif}
-->
  </style>
</head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec.png" width="520" height="102" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
    <form action="file:///C|/Users/DIEGOALV/Desktop/login.php" method="post">
      <table>
        <tr>
          <td>usuario</td>
          <td><input type="text" id="usu_login" name="usu_login" class="campos"></td>
          <td>clave</td>
          <td><input type="password" id="usu_clave" name="usu_clave" class="campos"></td>
          <td><input type="submit" value="ingresar" class="botones"></td>
        </tr>
      </table>
    </form>
<?php
    if($_GET["error"]== 1){
?>
    <span id="mensaje_top">Usuario y/o clave incorrectos.</span>
<?php
    }
?>
    </div>

    </header>
    <!--fin header-->


    <!--start contenedor1-->

    <div id="contenedor1">
   
      <div><header>
      
          <table width="100%" border="0">
            <tr>
              <td width="50%"><hgroup>
          <h1>Semantec</h1><br>
		<h2>Bienvenido al portal del cliente</h2>
      </hgroup></td>
              <td width="50%"><div align="center" class="Estilo1"> Bienvenido al portal del cliente, donde usted lalaladasd dasdad mca cmasdadadlldasdadddddddadsadadadadadadadadad erer asdadad erdasdad.</div></td>
            </tr>
          </table>
          
     </header>
      </div>
      
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
