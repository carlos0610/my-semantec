<?php
    
        include("validar.php");

        include("conexion.php");
        $id = $_GET["id"];
        $sql0 = "SELECT `ban_nombre`, `ban_direccion`, `ban_telefono`  
                  FROM `banco` 
                  WHERE ban_id=$id";
        $banco = mysql_query($sql0);
        $fila_banco = mysql_fetch_array($banco);      
        

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>   
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <link rel="stylesheet" href="css/suggestionsBox.css" type="text/css" />
  </head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec.png" width="470" height="100" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <form action="edit-banco.php" method="post">
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="lista-bancos.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
          <tr>
            <td>Banco</td>
            <td><input type="text" value="<?php echo(utf8_encode($fila_banco["ban_nombre"])); ?>" id="ban_nombre" name="ban_nombre" class="campos" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Tel&eacute;fono</td>
            <td><input value="<?php echo($fila_banco["ban_telefono"]); ?>" type="text" style="text-align:left" class="campos" id="ban_telefono" name="ban_telefono" required/></td>
            <td></td>
          </tr>
          
          <tr>
            <td>Direcci&oacute;n</td>
            <td><input value="<?php echo(utf8_encode($fila_banco["ban_direccion"])); ?>" type="text" class="campos" id="ban_direccion" name="ban_direccion" required/></td>
            <td></td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar Banco" class="botones" />
                <input type="hidden" value="<?php echo($id); ?>" name="id" id="id" />
                <?php         if($_GET["actualizo"]==1)
        {
          echo  "Actualizado correctamente";
        } ?>
            </td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
          </tr>
      </table> 

      </form>  
      
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