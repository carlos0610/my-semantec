<?php
    
        include("validar.php");

        include("conexion.php");
        $id = $_GET["id"];
        $sql0 = "SELECT `id`, `cut_id`, `ban_id`, `nro`, `nombre`, `cbu`, `estado` 
                 FROM `cuentabanco` 
                 WHERE `id` =$id";
        $banco = mysql_query($sql0);
        $fila_banco = mysql_fetch_array($banco);      
        
        $sql = "select cut_id,cut_nombre FROM cuentatipo ORDER BY cut_nombre ";
        $tipocuenta = mysql_query($sql);
        
        $sql = "SELECT ban_id , ban_nombre FROM `banco` WHERE estado = 1 ORDER BY ban_nombre ";
        $bancos = mysql_query($sql);

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
      <form action="edit-cuentabanco.php" method="post">
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="lista-cuentasbanco.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>

          
          <td>Tipo Cuenta</td>
            <td>
          <select name="cut_id" id="cut_id" class="campos" required >
    <?php
          while($fila = mysql_fetch_array($tipocuenta)){
    ?>
                    <option value="<?php echo($fila["cut_id"]); ?>" <?php if($fila_banco["cut_id"]==$fila["cut_id"]){echo(" selected=\"selected\"");} ?>>
                        <?php echo(utf8_encode($fila["cut_nombre"])); ?>
                    </option>
    <?php
          }
    ?>
                </select>
                  </td>  
            <td></td>
          </tr>
          <tr>
            <td>Banco</td>
            <td>
          <select name="ban_id" id="ban_id" class="campos" required >
    <?php
          while($fila = mysql_fetch_array($bancos)){
    ?>
                    <option value="<?php echo($fila["ban_id"]); ?>" <?php if($fila_banco["ban_id"]==$fila["ban_id"]){echo(" selected=\"selected\"");} ?>> 
                        <?php echo(utf8_encode($fila["ban_nombre"])); ?>
                    </option>
    <?php
          }
    ?>
                </select>
                  </td>   
            <td></td>
          </tr>
          
          <tr>
            <td>Nombre</td>
            <td><input  type="text" class="campos" id="nombre" name="nombre" required  value="<?php echo(utf8_encode($fila_banco["nombre"])); ?>"/></td>
            <td></td>
          </tr>
          <tr>
            <td>Numero</td>
            <td>
                <input type="text" style="text-align:right" class="campos" id="nro" name="nro" required  value="<?php echo(utf8_encode($fila_banco["nro"])); ?>"/>
            </td>
            <td></td>
          </tr>
           <tr>
            <td>CBU</td>
            <td>
                <input type="text" style="text-align:right" class="campos" id="cue_cbu" name="cue_cbu" value="<?php echo(utf8_encode($fila_banco["cbu"])); ?>"/>
            </td>
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