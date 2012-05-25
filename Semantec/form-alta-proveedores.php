<?php
    $titulo = "Formulario de alta de un proveedor.";
        include("validar.php");

        include("conexion.php");
        $sql = "SELECT  iva_id, iva_nombre FROM iva_tipo";
        $resultado1 = mysql_query($sql);
        $sql = "SELECT  zon_id, zon_nombre FROM zonas";
        $zonas = mysql_query($sql);
        $sql = "SELECT rub_id, rub_nombre FROM rubros";
        $rubros =  mysql_query($sql);
        $sql = "select cut_id,cut_nombre FROM cuentatipo";
        $tipocuenta = mysql_query($sql);
        

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

      <form action="alta-proveedores.php" method="post">
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
            <td><input type="text" class="campos" id="prv_nombre" name="prv_nombre" /></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><input type="number" class="campos" id="prv_cuit" name="prv_cuit" maxlength="11" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td>
                <select name="iva_id" id="iva_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["iva_id"]); ?>"><?php echo($fila["iva_nombre"]); ?></option>
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
                <select name="rub_id" id="rub_id" class="campos">
                <?php
          while($fila = mysql_fetch_array($rubros)){
                ?>
                    <option value="<?php echo($fila["rub_id"]); ?>"><?php echo($fila["rub_nombre"]); ?></option>
                <?php
                    }
                    ?>          
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Provincia/Zona</td>
            <td>
                <select name="zon_id" id="zon_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($zonas)){
    ?>
                    <option value="<?php echo($fila["zon_id"]); ?>"><?php echo(utf8_encode($fila["zon_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><input type="text" class="campos" id="prv_direccion" name="prv_direccion" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Tel&eacute;fono</td>
            <td><input type="text" class="campos" id="prv_telefono" name="prv_telefono" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Fax</td>
            <td><input type="text" class="campos" id="prv_fax" name="prv_fax" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Cel</td>
            <td><input type="text" class="campos" id="prv_cel" name="prv_cel" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Alternativo</td>
            <td><input type="text" class="campos" id="prv_alternativo" name="prv_alternativo" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Urgencia</td>
            <td><input type="text" class="campos" id="prv_urgencia" name="prv_urgencia" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Web</td>
            <td><input type="text" class="campos" id="prv_web" name="prv_web" value="http://"/></td>
            <td></td>
          </tr>
          <tr>
            <td>Email</td>
            <td><input type="text" class="campos" id="prv_email" name="prv_email" value="ejemplo@email.com"/></td>
            <td></td>
          </tr>
          <tr>
            <td>Tiene cuenta bancaria?</td>
            <td>Sí<input type="radio" class="" id="rbt_cuenta" name="rbt_cuenta" value="1"/>&nbsp;No<input type="radio" class="" id="rbt_cuenta" name="rbt_cuenta" value="0" checked/></td>
            <td></td>
          </tr>
          <tr>
            <td>Nro.Cuenta bancaria</td>
            <td><input type="text" class="campos" id="cue_nrobancaria" name="cue_nrobancaria"/></td>
            <td></td>
          </tr>
          <tr>
              <td>Tipo de cuenta</td><td>
          <select name="cut_id" id="cut_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($tipocuenta)){
    ?>
                    <option value="<?php echo($fila["cut_id"]); ?>"><?php echo(utf8_encode($fila["cut_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
                  </td>          
          </tr>
          <tr>
            <td>CBU</td>
            <td><input type="text" class="campos" id="cue_cbu" name="cue_cbu"/></td>
            <td></td>
          </tr> 
          <tr>
            <td>Notas</td>
            <td><textarea class="campos" id="prv_notas" name="prv_notas" rows="9"></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Agregar proveedor" class="botones" />
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
