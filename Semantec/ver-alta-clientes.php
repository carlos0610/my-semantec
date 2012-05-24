<?php
    $action = $_GET["action"];
    if($action == 1){
          $titulo = "Se ha dado de alta el siguiente cliente.";
    }
    else{ // 2
          $titulo = "Se han modificado los datos del siguiente cliente."; 
    }
        include("validar.php");

        include("conexion.php");
        $cli_id = $_SESSION["cli_id"];
        //$query = $_SESSION["query"];
        unset($_SESSION["cli_id"]);
        
        
        $sql = "SELECT cli_nombre, cli_cuit, iva_tipo.iva_nombre, rubros.rub_nombre, zonas.zon_nombre, cli_direccion, cli_telefono, cli_notas 
        FROM clientes,rubros,iva_tipo,zonas 
        WHERE cli_id = $cli_id 
        and clientes.cli_rubro = rubros.rub_id
        and clientes.zon_id = zonas.zon_id
        and clientes.iva_id = iva_tipo.iva_id";
        
        
        $resultado0 = mysql_query($sql);
        $cliente = mysql_fetch_array($resultado0);
    

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
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">


      <h2>Panel de control</h2>

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
            <td>Razón Social</td>
            <td><?php echo(utf8_encode($cliente["cli_nombre"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><?php echo($cliente["cli_cuit"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td><?php echo($cliente["iva_nombre"]); ?></td>
            <td></td>
          </tr>          
          <tr>
            <td>Provincia/Zona</td>
            <td><?php echo($cliente["zon_nombre"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><?php echo(utf8_encode($cliente["cli_direccion"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Tel&eacute;fono</td>
            <td><?php echo($cliente["cli_telefono"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Notas</td>
            <td><?php echo(utf8_encode($cliente["cli_notas"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <a href="lista-clientes.php"><input type="button" value="Ir al Listado" class="botones" /></a> &nbsp; &nbsp; 
                <a href="form-edit-clientes.php?cli_id=<?php echo($cli_id)?>"><input type="button" value="Modificar datos" class="botones" /></a> &nbsp; &nbsp; 
                <a href="form-alta-clientes.php"><input type="button" value="Agregar otro cliente" class="botones" /></a>
            </td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
          </tr>
      </table> 
      
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