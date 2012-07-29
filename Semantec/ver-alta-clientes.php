<?php
    include("validar.php");
    include("funciones.php");
    $origen = $_GET["origen"];
    $action = $_GET["action"];
    if($action == 0){
          $titulo = "Datos de Cliente";
          $cli_id = $_GET["cli_id"];
    }
    else 
    if($action == 1){
          $titulo = "Se ha dado de alta el siguiente cliente.";
          $cli_id = $_SESSION["cli_id"];
    }
    else
    if($action ==2)    { 
          $titulo = "Se han modificado los datos del siguiente cliente."; 
          $cli_id = $_SESSION["cli_id"];
    }else{
            $titulo = "Se ha dado de alta la siguiente sucursal."; 
          $cli_id = $_SESSION["cli_id"];
        
    }
        if($action ==4)    { 
          $titulo = " ¡ DIFICULTAD TRANSACCIONAL EN ALTA DE CLIENTE !"; 
          $cli_id = $_SESSION["cli_id"];
    }  

        include("conexion.php");
        
        //$query = $_SESSION["query"];
      //  unset($_SESSION["cli_id"]);        
        $sql = "SELECT cli_nombre,sucursal , cli_cuit, iva_tipo.iva_nombre,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad, cli_direccion,cli_direccion_fiscal, cli_telefono, cli_notas 
                FROM clientes,iva_tipo,ubicacion u,provincias p, partidos pa,localidades l
                WHERE cli_id =  $cli_id
        	AND clientes.iva_id = iva_tipo.iva_id
        	AND clientes.ubicacion_id = u.id
        	AND u.provincias_id = p.id
		AND u.partidos_id = pa.id
		AND u.localidades_id = l.id";
        
        
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


      <h2>Panel de control</h2>

      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td>Usuario</td>
            <td><?php echo(utf8_encode($cliente["cli_nombre"])); if ($action==3) echo " -- SUCURSAL (".$cliente['provincia'].")"; ?> </td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><?php echo(verCUIT($cliente["cli_cuit"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td><?php echo($cliente["iva_nombre"]); ?></td>
            <td></td>
          </tr> 

          <tr>
            <td>Provincia</td>
            <td><?php echo(utf8_encode($cliente["provincia"])); ?></td>
            <td></td>
          </tr>
          
          <tr>
            <td>Sucursal</td>
            <td><?php echo(utf8_encode($cliente["sucursal"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><?php echo(utf8_encode($cliente["cli_direccion"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n fiscal</td>
            <td><?php echo(utf8_encode($cliente["cli_direccion_fiscal"])); ?></td>
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
                <?php if ($origen!='externo')  {?>
                <a href="lista-clientes.php"><input type="button" value="Ir al Listado" class="botones" /></a> &nbsp; &nbsp; 
                <a href="form-edit-clientes.php?cli_id=<?php echo($cli_id)?>"><input type="button" value="Modificar datos" class="botones" /></a> &nbsp; &nbsp; 
                <a href="form-alta-clientes.php"><input type="button" value="Agregar otro cliente" class="botones" /></a> 
                <?php }else { ?>
                    <input type="button" class="botones" value="Volver" onclick="goBack()" />
                <?php } ?>
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