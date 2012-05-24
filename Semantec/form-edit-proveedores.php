<?php
    $titulo = "Formulario de modificaci&oacute;n de datos de un proveedor.";
        include("validar.php");

        include("conexion.php");
        $prv_id = $_GET["prv_id"];
        $sql0 = "SELECT prv_nombre, prv_cuit, iva_tipo.iva_nombre, rubros.rub_nombre, zonas.zon_nombre, prv_direccion, prv_telefono,prv_fax,prv_cel,prv_alternativo,prv_urgencia,prv_web,prv_email,prv_notas FROM proveedores,rubros,iva_tipo,zonas WHERE prv_id = $prv_id and proveedores.iva_id = iva_tipo.iva_id
        and proveedores.rub_id = rubros.rub_id
        and proveedores.zon_id = zonas.zon_id";
    
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);

        $sql = "SELECT  iva_id, iva_nombre FROM iva_tipo";
        $iva = mysql_query($sql);
        
                    
        $sql = "SELECT  zon_id, zon_nombre FROM zonas";
        $zonas = mysql_query($sql);       
             
        $sql    = "SELECT rub_id,rub_nombre FROM rubros";
        $rubros = mysql_query($sql);
               
        $sql = "select cut_id,cut_nombre FROM cuentatipo";
        $tipocuenta = mysql_query($sql);
        
        
        
        $sql ="SELECT cue.cue_nrobancaria,cut.cut_nombre,cue.cue_cbu FROM cuentabanco_prv cue,cuentatipo cut
               WHERE prv_id = $prv_id
               AND cue.cut_id = cut.cut_id";
        $banco = mysql_query($sql);
        $filas =  mysql_num_rows($banco);
        
        if($filas > 0){
            $fila_banco = mysql_fetch_array($banco);
            $_SESSION["tienecuenta"] = true;
        }else{
            $_SESSION["tienecuenta"] = false;         
        }
        

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
            <td><input type="text" value="<?php echo(utf8_encode($fila0["prv_nombre"])); ?>" class="campos" id="prv_nombre" name="prv_nombre" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><input value="<?php echo($fila0["prv_cuit"]); ?>" type="number" maxlength="11" class="campos" id="prv_cuit" name="prv_cuit" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td>
                <select name="iva_id" id="iva_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($iva)){
    ?>
                    <option value="<?php echo($fila["iva_id"]); ?>"<?php if($fila0["iva_nombre"]==$fila["iva_nombre"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["iva_nombre"]); ?></option>
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
                    <option value="<?php echo($fila["rub_id"]); ?>" <?php if($fila0["rub_nombre"]==$fila["rub_nombre"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["rub_nombre"]); ?></option>
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
                    <option value="<?php echo($fila["zon_id"]); ?>"<?php if($fila0["zon_nombre"]==$fila["zon_nombre"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["zon_nombre"])); ?></option>
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
            <td>Fax</td>
            <td>
            <input type="text" value="<?php echo($fila0["prv_fax"]); ?>" class="campos" id="prv_fax" name="prv_fax" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Cel</td>
            <td><input type="text" value="<?php echo($fila0["prv_cel"]); ?>" class="campos" id="prv_cel" name="prv_cel" /></td>
         <td></td>
          </tr>
          <tr>
            <td>Alternativo</td>
            <td><input type="text" value="<?php echo($fila0["prv_alternativo"]); ?>" class="campos" id="prv_alternativo" name="prv_alternativo" /></td>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Urgencia</td>
            <td><input type="text" value="<?php echo($fila0["prv_urgencia"]); ?>" class="campos" id="prv_urgencia" name="prv_urgencia" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Web</td>
            <td>
            <input type="text" value="<?php echo($fila0["prv_web"]); ?>" class="campos" id="prv_web" name="prv_web" />           
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Email</td>
            <td>
            <input type="text" value="<?php echo($fila0["prv_email"]); ?>" class="campos" id="prv_email" name="prv_email" />
            </td>
            <td></td>
          </tr>
          <td>Tiene cuenta bancaria?</td>
            <td><?php if($filas>0) 
                        echo "Sí"; 
                       else echo 
                           "No"; ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Nro.Cuenta bancaria</td>
            <td><input type="text" class="campos" id="cue_nrobancaria" name="cue_nrobancaria" value="<? if($filas>0) 
                                                                                                    echo $fila_banco["cue_nrobancaria"];
                                                                                                    else
                                                                                                    echo 0;    ?>"/></td>
            <td></td>
          </tr>
          <tr>
              <td>Tipo de cuenta</td><td>
          <select name="cut_id" id="cut_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($tipocuenta)){
    ?>
                    <option value="<?php echo($fila["cut_id"]); ?>" <?php if($fila["cut_nombre"]==$fila_banco["cut_nombre"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cut_nombre"])); ?></option>
                    
                    
    <?php
          }
    ?>
                </select>
                  </td>          
          </tr>
          <tr>
            <td>CBU</td>
            <td><input type="text" class="campos" id="cue_cbu" name="cue_cbu" value="<? if($filas>0){ 
                                                                                                   echo $fila_banco["cue_cbu"];
                                                                                                   }else{
                                                                                                    echo 0;}?>"/></td>
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