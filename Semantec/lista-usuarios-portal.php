<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
/* CALCULO PAGINADO */  ###############################################################################
    $sql0="SELECT u.id,c.cli_nombre,u.usu_nombre,u.usu_login FROM usuarios_portal u, clientes c
                           WHERE 
                           u.cli_id = c.cli_id
                           AND u.estado = 1";
    
    $tamPag=20;
    
    include("paginado.php");        
        $sql = "SELECT u.id,c.cli_nombre,u.usu_nombre,u.usu_login FROM usuarios_portal u, clientes c
                WHERE 
                u.cli_id = c.cli_id
                AND u.estado = 1";
                $sql .= " ORDER BY u.usu_login LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
        
        //echo $sql;
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
    <a href="#" id="logo"><img src="images/semantec.png" width="470" height="100" alt="logo" /></a>
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
      <h2>Panel de control - Listado de usuarios</h2>

      <table class="sortable" cellpadding="5">
          <tr>
            <td colspan="7"><div align="right"><a href="form-alta-usuarios-portal.php">Registrar nuevo usuario</a></div></td>
        </tr>
          <tr class="titulo">
            <td width="120">Usuario</td>
            <td width="384">Nombre</td>
            <td width="384">Cuenta asociada con</td>
            <td width="35">&nbsp;</td>
            <td width="35">&nbsp;</td>           
          <td width="35">
                <a href="form-configuracion.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
        </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
              <td><?php echo(utf8_encode($fila["usu_login"]));?> </td>
            <td><?php echo $fila["usu_nombre"];?></td>
            <td><?php echo($fila["cli_nombre"]);?></td>
            <td><a href="form-edit-usuarios-portal.php?usu_id=<?php echo($fila["id"]);?>">
                  <img src="images/editar.png" alt="editar" title="Modificar usuario" width="32" height="32" border="none" />                </a>              </td>       
            <td><a href="#" onClick="eliminarUsuarioPortal(<?php echo($fila["id"]);?>,'<?php echo($fila["usu_login"]);?>')">
                    <img src="images/eliminar.png" alt="eliminar" title="Eliminar usuario" width="32" height="32" border="none" />
                </a>                            </td>
            <td></td>
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
