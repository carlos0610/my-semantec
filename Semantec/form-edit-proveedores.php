<?php
    $titulo = "Formulario de modificaci&oacute;n de datos de un proveedor.";
        include("validar.php");

        include("conexion.php");
        $prv_id = $_GET["prv_id"];
        $sql0 = "SELECT prv_nombre, prv_cuit, iva_id, prv_rubro, zon_id, prv_direccion, prv_telefono, prv_notas
                  FROM proveedores p
                  WHERE p.prv_id=$prv_id";
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);

        $sql = "SELECT  iva_id, iva_nombre FROM iva_tipo";
        $resultado1 = mysql_query($sql);
        $sql = "SELECT  zon_id, zon_nombre FROM zonas";
        $resultado2 = mysql_query($sql);

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>    
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
      function lookup(prv_rubro) {
        if(prv_rubro.length == 0) {
          $('#suggestions').hide();
        } else {
          $.post("rpc-rubro2.php", {queryString: ""+prv_rubro+""}, function(data){
            if(data.length >0) {
              $('#suggestions').show();
              $('#autoSuggestionsList').html(data);
            }
          });
        }
      }
      
      function fill(thisValue) {
        $('#prv_rubro').val(thisValue);
        setTimeout("$('#suggestions').hide();", 200);
      }
    </script>
    <link rel="stylesheet" href="css/suggestionsBox.css" type="text/css" />
  </head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec-logo.jpg" width="470" height="100" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">


      <h2>Panel de control</h2>

      <form action="edit-proveedores.php" method="post">
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
          <tr>
            <td>Raz&oacute;n Social</td>
            <td><input type="text" value="<?php echo(utf8_encode($fila0["prv_nombre"])); ?>" class="campos" id="prv_nombre" name="prv_nombre" /></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><input value="<?php echo($fila0["prv_cuit"]); ?>" type="text" class="campos" id="prv_cuit" name="prv_cuit" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td>
                <select name="iva_id" id="iva_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["iva_id"]); ?>"<?php if($fila0["iva_id"]==$fila["iva_id"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["iva_nombre"]); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Rubro</td>
            <td>
                <input type="text" value="<?php echo($fila0["prv_rubro"]); ?>" class="campos" id="prv_rubro" name="prv_rubro" onkeyup="lookup(this.value);" onblur="fill();"  />
                  <div class="suggestionsBox" id="suggestions" style="display: none;">
                    <img src="images/upArrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow" />
                    <div class="suggestionList" id="autoSuggestionsList">
                      &nbsp;
                    </div>
                  </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Provincia/Zona</td>
            <td>
                <select name="zon_id" id="zon_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($resultado2)){
    ?>
                    <option value="<?php echo($fila["zon_id"]); ?>"<?php if($fila0["zon_id"]==$fila["zon_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["zon_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><input type="text" value="<?php echo(utf8_encode($fila0["prv_direccion"])); ?>" class="campos" id="prv_direccion" name="prv_direccion" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Tel&eacute;fono</td>
            <td><input type="text" value="<?php echo($fila0["prv_telefono"]); ?>" class="campos" id="prv_telefono" name="prv_telefono" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Notas</td>
            <td><textarea class="campos" id="prv_notas" name="prv_notas" rows="9"><?php echo(utf8_encode($fila0["prv_notas"])); ?></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar proveedor" class="botones" />
                <input type="hidden" value="<?php echo($prv_id); ?>" name="prv_id" id="prv_id" />
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