<?php

    header('Content-Type: text/html; charset=utf-8');
    $modificar=$_GET['modificar']; 
    if($modificar==1)
         $titulo = "Formulario Modificación Abonos";
    else
         $titulo = "Ver Abonos";
        include("validar.php");
        include("conexion.php");
        include("modelo/modeloClientes.php");
        include("modelo/modeloAbono.php");
        include("modelo/modeloAbonosDetalle.php");
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
        $idAbono=$_GET['idAbono'];     
       
        $nombreAbono=getAbonoNombreWithId($idAbono);        
        $cantidadmsql=getAbonosDetalleWithAbonoId($idAbono);
        $cant=mysql_num_rows($cantidadmsql);

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
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
      
      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>        
      </table>

      <form action="alta-abonos.php?cantidadAbonos=<?php echo $cant ?>" method="post" name="formAbonoSucursal" >
         <table class="listados" cellpadding="5">
          <tr>
              <td>Nombre Abono :</td>
              <td>
                  <?php if($modificar==1){ ?>
                          <input type="text" name="nombre_abono" id="nombre_abono" value="<?php echo $nombreAbono ?>" class="campos" required>
                  <?php }else{ ?>
          <big><b>     <?php   echo $nombreAbono; }?> </b></big>
              </td>
          </tr>
             
             
          <tr>
            <td colspan="6" bgcolor="#0099CC"><div align="center" class="Estilo1">Valores de Abonos</div></td>
        </tr>
        </table>
          <?php         for ($i = 1; $i <= $cant; $i++) {
                        $fila = mysql_fetch_array($cantidadmsql)
           ?>
      
      
      
          
      <table class="listados">

          <tr>
            <td colspan="6" bgcolor="#CDDCDA">  
                <?php  $cod=$fila['cli_id'];  echo (utf8_encode((getClienteNombreCompletoWithId($cod))));?>            
            </td>
            </tr>          
          <tr>
            <td width="9%">Valor Visita:</td>
            <td width="21%">
                <?php if($modificar==1){ ?>
                <input type="text" style="text-align:right" class="campos2" id="abono_valor_visita<?php echo $i; ?>" name="abono_valor_visita<?php echo $i; ?>" value="<?php echo $fila['valor_visita']; ?>"  min="0" required  />
                                  <?php }else{ ?>
                 <big>     <?php   echo $fila['valor_visita']; }?></big>
            </td>
            <td width="10%">Valor Costo :</td>
            <td width="21%">
                <?php if($modificar==1){ ?>
                <input type="text" style="text-align:right" class="campos2" id="abono_costo<?php echo $i; ?>" name="abono_costo<?php echo $i; ?>" value="<?php echo $fila['valor_costo']; ?>"  min="0" required  />
                                  <?php }else{ ?>
                 <big>     <?php   echo $fila['valor_costo']; }?> </big>
            </td>
            <td width="9%">Valor Venta : </td>
            <td width="30%">
                <?php if($modificar==1){ ?>
                <input type="text" style="text-align:right" class="campos2" id="abono_venta<?php echo $i; ?>" name="abono_venta<?php echo $i; ?>" value="<?php echo $fila['valor_venta']; ?>"  min="0" required   />
                                      <?php }else{ ?>
                <big>     <?php   echo $fila['valor_venta']; }?> </big>
            </td>
            </tr>
            <input type="hidden" name="abono_cli<?php echo $i; ?>" id="abono_cli<?php echo $i; ?>" value="<?php echo $cod ?>" class="campos" >
        
      <?php } ?>
            <tr>
            <td colspan="6" bgcolor="#CDDCDA">       &nbsp;        </td>
            </tr> 
            <tr>
                <td ></td> <td ></td> <td ></td>
            <td colspan="6">   <input type="submit" value="Modificar Abonos" class="botones" style="visibility:visible" id="botonAgregarSucursales" onClick="verificarCheckboxsAbono(<?php echo $cantSucursales ?>,<?php echo $idMatriz ?>)" />          </td>
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
