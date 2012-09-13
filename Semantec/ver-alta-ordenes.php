<?php
    include("validar.php");
        $pagina = $_GET["pagina"];
        $origen = $_GET["origen"];
        $origenOtroForm = $_GET["origenOtroForm"];
        if($origenOtroForm=='')
        {
         $origenOtroForm='interno';
        }
        $action = $_GET["action"]; 
        if($origen=='listadoOrdenes'){ // si viene de modificar un dato
            // datos de filtro de listado
                  $elementoBusqueda=$_GET['filtrartxt']; 
                 $_SESSION["prv_id"]= $proveedorFiltro=$_GET['prv_id']; 
                  $_SESSION["est_id"]= $estado_id=$_GET['est_id'];
                  $cli_id = $_GET['suc_id'];
                  $cli_idMaestro = $_GET['cli_id'];  
                  $unOrden=$_GET['orden'];
                  $contador=$_GET['contador'];
                  
        }
        else // si es listadoOrdenesDirecto viene directo del inco ver de listado ordenes
        { if($origen=='listadoOrdenesDirecto')
            {
        
                  $elementoBusqueda=$_POST['filtrartxt']; 
                  $proveedorFiltro=$_POST['prv_id']; 
                  $estado_id=$_POST['est_id'];
                  $cli_id = $_POST['suc_id'];
                  $cli_idMaestro = $_POST['cli_id'];  
                  $unOrden=$_POST['orden'];
                  $contador=$_POST['contador'];
                  //pruebas  con session 
                  $_SESSION["filtrartxt"]=$elementoBusqueda;
                  $_SESSION["prv_id"]=$proveedorFiltro;
                  $_SESSION["est_id"]=$estado_id; 
                  $_SESSION["suc_id"]=$cli_id;
                  $_SESSION["cli_id"]=$cli_idMaestro;
                  $_SESSION["orden"]=$unOrden;
                  $_SESSION["pagina"]=$pagina;
                  $_SESSION["action"]=$action;
            }else  // pruebo las variables session aplicadas en cambio de estado
            {
                  $elementoBusqueda=$_SESSION['filtrartxt']; 
                  $proveedorFiltro=$_GET['prv_id']; 
                  $estado_id=$_GET['est_id'];
                  $cli_id = $_SESSION['suc_id'];
                  $cli_idMaestro = $_SESSION['cli_id'];  
                  $unOrden=$_SESSION['orden'];
                  $contador=$_SESSION['contador'];
                  $pagina=$_SESSION['pagina'];
                  $actionOrigen = $_SESSION["action"];  
                  if($actionOrigen==0)
                     {$origen='listadoOrdenes';}
                  
            }
        }
          
        // fin 
    if($action == 0){
          $titulo = "Datos de Orden de Servicio";
          $ord_id = $_GET["ord_id"];
    }
    else if($action == 1){
          $titulo = "Se ha dado de alta la siguiente Orden de Servicio";
          $ord_id = $_SESSION["ord_id"];
    }
    else{ // 2
          $titulo = "Se han modificado los datos del la siguiente Orden de Servicio"; 
          $ord_id = $_SESSION["ord_id"];
    }
        include("funciones.php");

        
        include("conexion.php");
        include("Modelo/modeloAbonosDetalle.php");
        include("Modelo/modeloHistorialAbonos.php");
        include("Modelo/modeloRubros.php");
        
        $sql0 = "SELECT ord_codigo, ord_descripcion, cli_id, prv_id,
                        est_id, ord_alta, ord_plazo, ord_costo, ord_venta,es_abono,
                        fecha_aprobado_bajocosto, fecha_pendiente_facturacion,rub_id
                  FROM ordenes WHERE ord_id = $ord_id";
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);  // datos de la orden
        $cli_idBusqueda = $fila0["cli_id"];
        $prv_id = $fila0["prv_id"];
        $est_id = $fila0["est_id"];
        $rub_id = $fila0["rub_id"];

        $sql = "SELECT  cli_id, cli_nombre,sucursal FROM clientes WHERE cli_id = $cli_idBusqueda"; // datos de cliente
        $resultado1 = mysql_query($sql);
        $fila1 = mysql_fetch_array($resultado1);

        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE prv_id = $prv_id"; //datos del proveedor
        $resultado2 = mysql_query($sql);
        $fila2 = mysql_fetch_array($resultado2);

        $sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id = $est_id"; //datos del estado
        $resultado3 = mysql_query($sql);
        $fila3 = mysql_fetch_array($resultado3);
        
        
        //datos del rubro
        
        $rubros = getRubroNameById($rub_id);
        $fila4 = mysql_fetch_array($rubros);
        
        
        $pendienteFacturacion = 11;
        
        
        
        
        
        
        

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.datepick.js"></script>
  <script type="text/javascript" src="js/jquery.datepick-es.js"></script>
  <script type="text/javascript">
  $(function() {
      $('#ord_plazo').datepick();
  });
  </script>    

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
      
      <form id="filtro" name="filtro" action=''  method="POST">
          <input type="hidden" value="<?php echo($elementoBusqueda); ?>" name="filtrartxt" id="filtrartxt" />  
          <input type="hidden" value="<?php echo($proveedorFiltro); ?>" name="prv_id" id="prv_id" />  
          <input type="hidden" value="<?php echo($estado_id); ?>" name="est_id" id="est_id" />  
          <input type="hidden" value="<?php echo($cli_id); ?>" name="suc_id" id="suc_id" />  
          <input type="hidden" value="<?php echo($cli_idMaestro); ?>" name="cli_id" id="cli_id" /> 
          <input type="hidden" value="<?php echo($unOrden); ?>" name="orden" id="orden" /> 
          <input type="hidden" value="<?php echo($contador); ?>" name="contador" id="contador" /> 
      </form>

      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2" align="center"> <?php echo($titulo)?> </td>
            <td width="43" align="center">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td width="170"><b>C&oacute;digo de Orden</b></td>
            <td width="422"><big><b><?php echo($fila0["ord_codigo"]);?></b></big></td>
            <td rowspan="9" align="center"><br>
            </br></td>
          </tr>
          <tr>
            <td>Descripci&oacute;n de Orden</td>
            <td><?php echo(nl2br(utf8_encode($fila0["ord_descripcion"])));?></td>
          </tr>
          <tr>
            <td>Rubro</td>
            <td><?php echo(utf8_encode($fila4["rub_nombre"])); ?></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>
                <?php echo(utf8_encode($fila1["cli_nombre"]));?> (<?php echo(utf8_encode($fila1["sucursal"])); ?>)           </td>
          </tr>
          <tr>
            <td>Proveedor</td>
            <td>
                <?php echo(utf8_encode($fila2["prv_nombre"])); ?>            </td>
          </tr>
          <tr>
            <td>Estado</td>
            <td>
                <?php echo(utf8_encode($fila3["est_nombre"])); ?></td>
          </tr>
          <?php  if(($fila0["ord_plazo"] != "") and($fila0["ord_plazo"] != "0000-00-00 00:00:00")){?>
          <tr>
            <td>Plazo de finalización</td>
            <td><?php echo(tfecha($fila0["ord_plazo"]));?>            </td>
          </tr>
          <?php } ?> 
          
          <?php  if(($fila0["fecha_aprobado_bajocosto"] != "") 
                  and($fila0["fecha_aprobado_bajocosto"] != "0000-00-00 00:00:00")
                  ){?>
          <tr>
            <td>Fecha aprobado bajo costo</td>
            <td><?php echo(tfecha($fila0["fecha_aprobado_bajocosto"]));?>            </td>
          </tr>
          <?php } ?> 
          
          
          <?php  if(($fila0["fecha_pendiente_facturacion"] != "") 
                  and($fila0["fecha_pendiente_facturacion"] != "0000-00-00 00:00:00")
                  and ($est_id < 12)){?>
          <tr>
            <td>Fecha pendiente facturación</td>
            <td><?php echo(tfecha($fila0["fecha_pendiente_facturacion"]));?> </td>
          </tr>
          <?php } ?> 
          
          
          <tr>
            <td>Abono</td>
            <td><?php   
                        $estadoAbono=$fila0["es_abono"]; 
                        if($estadoAbono==1){ echo 'Sí  - Fecha : ',mfecha((getHistorialAbonos_FechaRegistroWithOrdenId($ord_id)));;} 
                        if($estadoAbono==0){ echo 'No';}
                        if($estadoAbono==2){ echo '<b><font color="#FF0000">Sucursal No registrada con Abono</font></b>';}
                        if($estadoAbono==3){ echo '<b><font color="#FF0000">Abono del mes ya utilizado, Modificar Fecha de inicio de abono</font></b>';}
            ?></td>
            </tr>  
            <?php if($estadoAbono!=1) {?>
          <tr>
            <td>Valor Costo de la Orden</td>
            <td><?php $valor_costo_orden=$fila0["ord_costo"]; echo($valor_costo_orden);?></td>
            </tr>          
          <tr>
            <td>Valor Venta de la Orden   </td>
            <td><?php $valor_venta_orden=$fila0["ord_venta"]; echo($valor_venta_orden);?></td>
          </tr>
          <?php } ?> 
          <?php if($estadoAbono==1){ 
              $consult=getAbonosDetalleWithCliId($fila0["cli_id"]);
              $filaAbono = mysql_fetch_array($consult);
              $valor_costo_Abono=$filaAbono["valor_costo"];
              $valor_venta_Abono=$filaAbono["valor_venta"];
              ?> 
            <tr>
            <td>Valor Costo Materiales</td>
            <td><?php $valor_costo_orden=$fila0["ord_costo"]; echo($valor_costo_orden);?></td>
            </tr> 
            <tr>
            <td>Valor Costo Abono </td>
            <td><?php echo($valor_costo_Abono) ;?>             </td>
            </tr>   
            
            <tr>
                <td><b>Valor Costo Total</b> </td>
            <td><?php echo number_format(($valor_costo_Abono+$valor_costo_orden), 2, '.', '');?> </td>
            </tr> 
            
            <tr>
            <td>Valor Venta Materiales</td>
            <td><?php $valor_venta_orden=$fila0["ord_venta"]; echo($valor_venta_orden);?></td>
          </tr>
            
          <tr>
            <td>Valor Venta Abono  </td>
            <td><?php echo($valor_venta_Abono);?>            </td>
          </tr>
          
                    <tr>
            <td><b>Valor Venta Total</b> </td>
            <td><?php echo number_format(($valor_venta_Abono+$valor_venta_orden), 2, '.', '');?></td>
          </tr>
             <?php } ?> 
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td>            </td>
          <td>
          <?php   if ($origenOtroForm=='interno')  {?>
              
              <?php if(($origen=='listadoOrdenes')or($origen=='listadoOrdenesDirecto')){ ?>
                 <a href="#" onClick="transferirFiltrosAOtroForm('filtro','lista-ordenes.php?pagina=<?php echo $pagina ?>')">
              <?php }else { // volver a listado ordenes detalle?> 
                 <a href="#" onClick="transferirFiltrosAOtroForm('filtro','lista-req-ordenes.php?pagina=<?php echo $pagina ?>')">               
              <?php }?>                   
                   <input type="button" value="Ir al Listado" class="botones" />
                </a>  
                <a href="form-alta-ordenes.php">
                   <input type="button" value="Agregar otra orden" class="botones" />
                </a>
                <span  <?php if(($origen=='listadoOrdenes3')or($origen=='listadoOrdenesDirecto3')){echo ("  style='visibility:hidden'");}?>>         
                   <a href="form-edit-ordenes-unificado.php?ord_id=<?php echo($ord_id)?>&action=2&est_id=<?php  echo $estado_id; ?>&prv_id=<?php echo $proveedorFiltro ?>">
                       <input type="button" value="Modificar datos" class="botones" />                           
                   </a>                </span>
          <?php }else {if($origenOtroForm=='altaOrden'){ ?>
                     <a href="lista-ordenes.php">
                        <input type="button" class="botones" value="Ir al Listado" />
                     </a>
                     <a href="form-alta-ordenes.php">
                      <input type="button" value="Agregar otra orden" class="botones" />
                     </a>
                     
                   <a href="form-edit-ordenes-unificado.php?ord_id=<?php echo($ord_id)?>&action=2&est_id=<?php  echo $estado_id; ?>&prv_id=<?php echo $proveedorFiltro ?>&origen=altaOrden">
                       <input type="button" value="Modificar datos" class="botones" />                           
                   </a>
                     <?php }else{ ?>
                  <input type="button" class="botones" value="Volver" onClick="goBack()" />
          <?php }
          }?>         </td>
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