<?php

    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de alta de una Orden de Servicio.";
        include("validar.php");
        include("conexion.php");
        include("Modelo/modeloClientes.php");
        $sql = "SELECT sucursal_id,sucursal,cli_id,cli_nombre,p.nombre as provincia 
           FROM clientes,ubicacion u,provincias p, partidos pa,localidades l
           WHERE 
 	   clientes.ubicacion_id = u.id
           AND u.provincias_id = p.id
           AND u.partidos_id = pa.id
           AND u.localidades_id = l.id
           AND clientes.estado = 1
           AND sucursal_id is null
           ORDER BY cli_nombre,provincia";        
        $resultado1 = mysql_query($sql);
        $idMatriz=$_GET['idMatriz'];
        $nombreAbono=$_GET['nombreabono'];
        $fase=$_GET['fase'];
        $cant=$_GET['cant'];

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/validador.js"></script>
  <script type="text/javascript" src="js/select_dependientes_cliente_sucursal.js"></script>

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


      <h2>Panel de control</h2>
      
      <form action="form-alta-abonos.php" method="post" name="formSelectSucursales" enctype="multipart/form-data" >
      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
           <?php if($fase!=2) { ?>
           <tr>
              <td>Nombre Abono</td>
              <td><input type="text" name="nombre_abono" id="nombre_abono" value="<?php echo $nombreAbono ?>" class="campos" required></td>
          </tr>
            <td>Cliente</td>
            <td>
                <select name="cli_id" id="cli_id" class="campos" required onChange="actualizarListadoAbono(this.value)">
                    <option value='0'>Seleccione</option>;
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["cli_id"]); ?>"<?php if($fila["cli_id"]==$idMatriz){echo "selected";}?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>

          
          <?php if($idMatriz!=''){ ?>        
            <td>Sucursal</td>
            <td>

    <?php  $resultadoSucursalCliente=getClientesWithSucursalId($idMatriz);
          $cantSucursales= mysql_num_rows($resultadoSucursalCliente); 
          $i=0; if($cantSucursales>0){?>
          <input type="checkbox" value="" id="checkbox_SelectAll" onClick="CheckboxsSeleccionarTodos(<?php echo $cantSucursales ?>)" >SELECCIONAR TODO<br>
        <?php }else{echo "No posee Sucursales";}
   
   while($fila = mysql_fetch_array($resultadoSucursalCliente)){
          $i++;
    ?>
                    <input type="checkbox" value="<?php echo($fila["cli_id"]); ?>" id="checkbox_sucursal_id<?php echo $i ?>">
                   <?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)<br>
    <?php
          }
    ?>
            </td>
          <?php } ?>
          
            
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="buttom" value="Aceptar Sucursales" class="botones" style="visibility:visible" id="botonAgregarSucursales" onClick="verificarCheckboxsAbono(<?php echo $cantSucursales ?>,<?php echo $idMatriz ?>)" />
            </td>
            <td></td>
          </tr>   
          <?php } ?>
          
      </table>
      </form>  

        
          <?php   if ($fase==2) {     ?>
      <form action="alta-abonos.php?cantidadAbonos=<?php echo $cant ?>" method="post" name="formAbonoSucursal" >
         <table class="listados" cellpadding="5">
          <tr>
              <td>Nombre Abono</td>
              <td><input type="text" name="nombre_abono" id="nombre_abono" value="<?php echo $nombreAbono ?>" class="campos" required></td>
          </tr>
             
             
          <tr>
            <td colspan="6" bgcolor="#0099CC"><div align="center" class="Estilo1">Complete Valores de Abonos</div></td>
        </tr>
        </table>
          <?php         for ($i = 1; $i <= $cant; $i++) {
           ?>
      
      
      
          
      <table class="listados">

          <tr>
            <td colspan="6" bgcolor="#CDDCDA">   <?php  $cod=$_GET["suc_check$i"];  echo (utf8_encode((getClienteNombreCompletoWithId($cod))));?>            </td>
            </tr>          
          <tr>
            <td width="9%">Valor Visita:</td>
            <td width="21%"><input type="text" style="text-align:right" class="campos2" id="abono_valor_visita<?php echo $i; ?>" name="abono_valor_visita<?php echo $i; ?>" value="0"  min="0" required  /></td>
            <td width="10%">Valor Costo :</td>
            <td width="21%"><input type="text" style="text-align:right" class="campos2" id="abono_costo<?php echo $i; ?>" name="abono_costo<?php echo $i; ?>" value="0"  min="0" required  /></td>
            <td width="9%">Valor Venta : </td>
            <td width="30%"><input type="text" style="text-align:right" class="campos2" id="abono_venta<?php echo $i; ?>" name="abono_venta<?php echo $i; ?>" value="0"  min="0" required   /></td>
            </tr>
            <input type="hidden" name="abono_cli<?php echo $i; ?>" id="abono_cli<?php echo $i; ?>" value="<?php echo $cod ?>" class="campos" >
        
      <?php } ?>
            <tr>
            <td colspan="6" bgcolor="#CDDCDA">       &nbsp;        </td>
            </tr> 
            <tr>
                <td ></td> <td ></td> <td ></td>
            <td colspan="6">   <input type="submit" value="Registrar Abonos" class="botones" style="visibility:visible" id="botonAgregarSucursales" onClick="verificarCheckboxsAbono(<?php echo $cantSucursales ?>,<?php echo $idMatriz ?>)" />          </td>
            </tr>  
            </table>
        </form>
      <?php    }?>
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
