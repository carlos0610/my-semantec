<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
/* CALCULO PAGINADO */  ###############################################################################
    $sql0="SELECT `rub_id`, `rub_nombre` 
           FROM `rubros` 
           WHERE `estado`=1
           ";
    
    $tamPag=20;
    
    include("paginado.php");        
        $sql = "SELECT `rub_id`, `rub_nombre` 
                FROM `rubros` 
                WHERE `estado`=1
                ";
                $sql .= " ORDER BY rub_nombre  LIMIT ".$limitInf.",".$tamPag; 
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
      <h2>Panel de Configuración Rubros</h2>
      
     <form action="alta-rubro.php" method="post">
      <table class="forms" cellpadding="1">
          <tr>
             <td><b>Nuevo Rubro</b></td> 
          </tr>
          <tr>
            <td>Rubro Nombre</td>
            <td><input type="text" value="" id="rub_nombre" name="rub_nombre" class="campos" required/></td>
            <td></td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Agregar Rubro" class="botones" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td >&nbsp;</td>
          </tr>
      </table> 

      </form>  
      
      
      
      
      
      
      
      
      
      <table class="sortable" cellpadding="3">
          <tr class="titulo">
            <td>Rubro Nombre</td>
            <td width="32">&nbsp;</td>
            <td width="32">
                <a href="form-configuracion.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
              <td><?php echo(utf8_encode($fila["rub_nombre"])); ?></td>   
            <td>
                <a href="form-edit-rubro.php?id=<?php echo($fila["rub_id"]);?>">
                  <img src="images/editar.png" alt="editar" title="Modificar cliente" width="32" height="32" border="none" />
                </a>  
              </td>
            <td><a href="#" onclick="eliminarItem(<?php echo($fila["rub_id"]);?>,'<?php echo($fila["rub_nombre"]);?>','delete-rubro.php?id=')">
                    <img src="images/eliminar.png" alt="eliminar" title="Eliminar cliente" width="32" height="32" border="none" />
                </a></td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
      </table>
      <table class="listados" cellpadding="5">
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
