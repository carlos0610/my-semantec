<?php
    $titulo = "Formulario de modificaci&oacute;n de datos de un cliente.";
        include("validar.php");

        include("conexion.php");
        $cli_id = $_GET["cli_id"];
        $sql0 = "SELECT cli_nombre, cli_cuit, iva_id, cli_rubro, ubicacion_id, cli_direccion,cli_direccion_fiscal, cli_telefono, cli_notas
                  FROM clientes c
                  WHERE c.cli_id=$cli_id";
        $clientes = mysql_query($sql0);
        $fila_clientes = mysql_fetch_array($clientes);
        
        $ubicacion_id = $fila_clientes["ubicacion_id"];

        $sql = "SELECT  iva_id, iva_nombre FROM iva_tipo";
        $iva = mysql_query($sql);
          
        /* COMBOS DE PROVINCIAS , PARTIDOS, LOCALIDADES */
        
        $sql = "SELECT id, nombre FROM provincias";
        $listado_provincias =   mysql_query($sql);
        
        
        $sql = "SELECT id, nombre FROM partidos";
        $listado_partidos   =   mysql_query($sql);
        
        $sql = "SELECT id, nombre FROM localidades";
        $listado_localidades  =    mysql_query($sql);
        
        /* OBTENIENDO ID DE PROV, PARTIDOS Y LOCALDAD DE ACUERDO A SU UBICACIÓN_ID */
        
        $sql = "SELECT p.id ,p.nombre from ubicacion u, provincias p 
                WHERE u.id = $ubicacion_id
                and u.provincias_id = p.id";
        
        $resultado = mysql_query($sql);
        $provincia = mysql_fetch_array($resultado);
        
        
        $sql = "SELECT p.id ,p.nombre FROM ubicacion u, partidos p 
                WHERE u.id = $ubicacion_id
                and u.partidos_id = p.id";
        
        $resultado = mysql_query($sql);
        $partidos = mysql_fetch_array($resultado);
        
        
        $sql =  "SELECT l.id ,l.nombre FROM ubicacion u, localidades l 
                 WHERE u.id = $ubicacion_id
                 AND u.localidades_id= l.id";
        $resultado = mysql_query($sql);
        $localidades = mysql_fetch_array($resultado);
        

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


      <h2>Panel de control</h2>

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
            <td><input type="text" value="<?php echo(utf8_encode($fila_clientes["cli_nombre"])); ?>" id="cli_nombre" name="cli_nombre" class="campos" /></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><input type="number" style="text-align:right" maxlength="11" value="<?php echo($fila_clientes["cli_cuit"]); ?>" id="cli_cuit" name="cli_cuit" class="campos" readOnly/></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td>
                <select name="iva_id" id="iva_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($iva)){
    ?>
                    <option value="<?php echo($fila["iva_id"]); ?>"<?php if($fila_clientes["iva_id"]==$fila["iva_id"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["iva_nombre"]); ?></option>
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
                        <td>Partido</td>
                        <td><label>
                                <select name="select2" id="select2" class="campos" onChange="cargaContenido(this.id)">
                                <option value="0">Selecciona opci&oacute;n...</option>
                                <?php
          while($fila_partidos = mysql_fetch_array($listado_partidos)){
    ?>
                    <option value="<?php echo($fila_partidos["id"]); ?>" <?php if($partidos["id"]==$fila_partidos["id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila_partidos["nombre"])); ?></option>
    <?php
          }
    ?>
                                </select>
                        </label></td>
                      </tr>
          
          <tr>
                        <td>Localidad</td>
                        <td><label>
                                <select name="select3" id="select3" class="campos">
                                <option value="0">Selecciona opci&oacute;n...</option>
                               
                                <?php
                     while($fila_localidades = mysql_fetch_array($listado_localidades)){
    ?>
                    <option value="<?php echo($fila_localidades["id"]); ?>" <?php if($localidades["id"]==$fila_localidades["id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila_localidades["nombre"])); ?></option>
    <?php
          }
    ?>
                                </select>
                                
                                
                                
                                
                        </label></td>
                      </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><input value="<?php echo(utf8_encode($fila_clientes["cli_direccion"])); ?>" type="text" class="campos" id="cli_direccion" name="cli_direccion" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n fiscal</td>
            <td><input value="<?php echo(utf8_encode($fila_clientes["cli_direccion_fiscal"])); ?>" type="text" class="campos" id="cli_direccion_fiscal" name="cli_direccion_fiscal" required/></td>
            <td></td>
          </tr>
          
          
          <tr>
            <td>Tel&eacute;fono</td>
            <td><input value="<?php echo($fila_clientes["cli_telefono"]); ?>" type="number" style="text-align:right" class="campos" id="cli_telefono" name="cli_telefono" /></td>
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
                <input type="submit" value="Modificar cliente" class="botones" />
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