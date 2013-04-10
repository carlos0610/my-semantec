<?php
        $titulo = "Reporte de IVA";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1 and sucursal_id is null";
        $resultado1 = mysql_query($sql);
        //recibo los criterios y construyo la consulta
        
        
        
        
            if (isset($_REQUEST["chckCliente"])){
                            $cli_id        = $_POST['comboClientes'];
                            $consulta      = " (select cli_id from clientes where sucursal_id = $cli_id or cli_id = $cli_id)";
                            
                            while ($fila = mysql_fetch_array($resultado1)){
                                    if ($fila["cli_id"] == $_REQUEST["comboClientes"]){
                                        $nombre_cliente = $fila["cli_nombre"];
                                        }
                                        
                            }
                            mysql_data_seek($resultado1, 0);        
                            $tituloReporte = " DE ".$nombre_cliente;
                            } 
            else            {
                            $cli_id         =   "";
                            $consulta       =   " (select cli_id from clientes where estado = 1)";
                            $tituloReporte  = " DE TODOS LOS CLIENTES";
                            }
        
        
        
              
        $filtrar = $_POST['filtrar'];
        
           if(isset($filtrar)) {
               $fecha_ini = $_POST["fecha_inicio"];
               $fecha_fin = $_POST["fecha_fin"];
               
               
               $fecha_ini = gfecha($fecha_ini);
               $fecha_fin = gfecha($fecha_fin);
            
               
               
               
               
            $sql = "SELECT ROUND(SUM((det.det_fav_precio * (select valor/100 from iva where idiva = det.idiva))),2) as total_iva  FROM detalle_factura_venta det,factura_venta fa
                                    WHERE
                                    det.fav_id = fa.fav_id
                                    AND
                                    det.fav_id in ( 
                                                    SELECT DISTINCT (fa.fav_id) from detalle_factura_venta det,factura_venta fa,grupo_ordenes g_o,ordenes o
                                                        WHERE
                                                            fa.gru_id = g_o.gru_id
                                                            AND 
                                                            g_o.gru_id = o.gru_id
                                                            AND
                                                            o.cli_id  in $consulta
                                                            AND
                                                            det.fav_id = fa.fav_id
                                                    )
                                    AND fa.fav_fecha BETWEEN '$fecha_ini 00:00:00' AND '$fecha_fin 23:59:59'";
            
                        
        }
                      
        $resultado = mysql_query($sql);
        

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
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.datepick.js"></script>
  <script type="text/javascript" src="js/jquery.datepick-es.js"></script>
  <script type="text/javascript">
  $(function() {
      
      $('#fecha_inicio').datepick();
        /* Obtenemos mes y a;o actual */
                var fecha = new Date();
                var mes  = fecha.getMonth()+1;
                var anho = fecha.getFullYear();
        /*Armamos la fecha para setear el primer dia del mes por defecto como -fecha de inicio- */
                var primerDiaDelMesActual =("01/"+mes+"/"+anho);
      
      $("#fecha_inicio").datepick("setDate" , primerDiaDelMesActual);
      
      $('#fecha_fin').datepick();
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
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <h2>Panel de control - <?php echo $titulo; ?></h2>

     <div id="buscador" >     
<form name="filtro" action="<?php echo $PHP_SELF;?>" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="14%"><div align="right">Cliente</div></td>
         <td width="34%"><select name="comboClientes" id="comboClientes" class="campos" <?php if($cli_id==""){echo ("disabled");}?>>
           <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_id==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?></option>
           <?php
          }
    ?>
         </select></td>
         <td width="52%"><input name="chckCliente" type="checkbox" id="chckCliente" onClick="habilitarFiltros('chckCliente','comboClientes')" <?php if($cli_id!=""){echo ("checked");}?>></td>
       </tr>
       <tr>
         <td><div align="right">Desde</div></td>
         <td><input type="text" name="fecha_inicio" id="fecha_inicio" class="campos2"></td>
         <td>Hasta 
           <input type="text" name="fecha_fin" id="fecha_fin" class="campos2">
           <input type="submit" name="filtrar" value="filtrar" class="botones" ></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
     </table>
     <p>&nbsp;</p>
</form>
      </div>  
      
      
   </div>
      <div class="clear">
        <div align="center">REPORTE DE IVA  <?php echo $tituloReporte?></div>
      </div>
      <table class="sortable" cellpadding="5">
          
          <tr class="titulo">
            <td width="70">Total iva facturado</td>
            <td width="100">Desde</td>
            <td>Hasta</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo $fila["total_iva"]?></td>
            <td><?php echo mfecha($fecha_ini) ?></td>
            <td><?php echo mfecha($fecha_fin) ?></td>
            <?php //echo(utf8_encode($fila_req["files_id"]));
                      $id = $fila["files_id"] ?>
            <td>&nbsp;</td>
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
      </table>
<table  class="listados" cellpadding="5">
          <tr>
            <td colspan="8" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
          //  echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>   

      
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
