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
    <script type="text/javascript">
      function lookup(cli_rubro) {
        if(cli_rubro.length == 0) {
          $('#suggestions').hide();
        } else {
          $.post("rpc-rubro.php", {queryString: ""+cli_rubro+""}, function(data){
            if(data.length >0) {
              $('#suggestions').show();
              $('#autoSuggestionsList').html(data);
            }
          });
        }
      }
      
      function fill(thisValue) {
        $('#cli_rubro').val(thisValue);
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
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <form action="edit-clientes.php" method="post">
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
            <td><input type="text" value="<?php echo(utf8_encode($fila_banco["cli_nombre"])); ?>" id="cli_nombre" name="cli_nombre" class="campos" <?php if (isset($fila_clientes["sucursal_id"])) echo "readOnly" ?>/></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><input type="text" value="<?php echo substr(($fila_banco["cli_cuit"]),0,2); ?>" style="text-align:right" min="10" class="campos2" id="cuit_parteA" name="cuit_parteA" maxlength="2" size="2" onKeyPress="pasaSiguiente(this, document.getElementById('cuit_parteB'), 2)"  required />
              -
                <input type="text" value="<?php echo substr(($fila_banco["cli_cuit"]),2,8); ?>" style="text-align:right" min="10000000" class="campos2" id="cuit_parteB" name="cuit_parteB" maxlength="8" size="8" onKeyPress="pasaSiguiente(this, document.getElementById('cuit_parteC'), 8)" required />
                -
            <input type="text" value="<?php echo substr(($fila_banco["cli_cuit"]),-1); ?>" style="text-align:right" min="0" class="campos2" id="cuit_parteC" name="cuit_parteC" maxlength="1" size="1" onChange="return autenticaCUIT();"  required /></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td>
                <select name="iva_id" id="iva_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($iva)){
    ?>
                    <option value="<?php echo($fila["iva_id"]); ?>"<?php if($fila_banco["iva_id"]==$fila["iva_id"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["iva_nombre"]); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>          
          <tr>
            <tr>
            <td>Provincia</td>
            <td>
                <select name="select1" id="select1" class="campos" onChange="cargaContenido(this.id)">
                <option value='0'>Seleccione</option>;   
    <?php
          while($fila_provincia = mysql_fetch_array($listado_provincias)){
    ?>
                    <option value="<?php echo($fila_provincia["id"]); ?>" <?php if($provincia["id"]==$fila_provincia["id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila_provincia["nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Sucursal</td>
            <td><input value="<?php echo($fila_clientes["sucursal"]); ?>" type="text" style="text-align:left" class="campos" id="sucursal" name="sucursal" /></td>
            <td></td>
          </tr>
          
          <tr>
            <td>Direcci&oacute;n</td>
            <td><input value="<?php echo(utf8_encode($fila_clientes["cli_direccion"])); ?>" type="text" class="campos" id="cli_direccion" name="cli_direccion" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n fiscal</td>
            <td><input value="<?php echo(utf8_encode($fila_clientes["cli_direccion_fiscal"])); ?>" type="text" class="campos" id="cli_direccion_fiscal" name="cli_direccion_fiscal" required <?php if (isset($fila_clientes["sucursal_id"])) echo "readOnly" ?>/></td>
            <td></td>
          </tr>
          
          
          <tr>
            <td>Tel&eacute;fono</td>
            <td><input value="<?php echo($fila_clientes["cli_telefono"]); ?>" type="text" style="text-align:right" class="campos" id="cli_telefono" name="cli_telefono" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Notas</td>
            <td><textarea class="campos" id="cli_notas" name="cli_notas" rows="9"><?php echo(utf8_encode($fila_clientes["cli_notas"])); ?></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar cliente" class="botones" onClick="return validarCombosDeUbicacion()"/>
                <input type="hidden" value="<?php echo($cli_id); ?>" name="cli_id" id="cli_id" />
                <input type="hidden" value="<?php echo($ubicacion_id); ?>" name="ubicacion_id" id="ubicacion_id" />
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