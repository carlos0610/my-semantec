<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
/* CALCULO PAGINADO */  ###############################################################################
    $sql0="SELECT cli_id, cli_nombre, cli_cuit, cli_telefono FROM clientes where estado = 1";
    $tamPag=20;
    
    include("paginado.php");        
        $sql = "SELECT cli_id, cli_nombre, cli_cuit, cli_telefono FROM clientes where estado = 1";
                $sql .= " LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
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
      <h2>Panel de control - Listado de clientes</h2>

      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td>Nombre</td>
            <td width="90">CUIT</td>
            <td width="90">Tel&eacute;fono</td>
            <td width="32">&nbsp;</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["cli_nombre"]);?></td>
            <td><?php echo(verCUIT($fila["cli_cuit"]));?></td>
            <td><?php echo($fila["cli_telefono"]);?></td>
            <td>
                <a href="form-edit-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>">
                  <img src="images/editar.png" alt="editar" title="Modificar cliente" width="32" height="32" border="none" />
                </a>  
              </td>
            <td><a href="#" onclick="eliminarCliente(<?php echo($fila["cli_id"]);?>,'<?php echo($fila["cli_nombre"]);?>')">
                    <img src="images/eliminar.png" alt="eliminar" title="Eliminar cliente" width="32" height="32" border="none" />
                </a></td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
          <tr>
            <td colspan="5" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
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
