<?php
        $titulo = "Listado de Facturas.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        include("Modelo/modeloRetenciones.php");
        
        
        
        $retenciones = getListarTodoRetenciones();
        
          
        $filtrar = $_POST['filtrar'];
        $resultado = null;
           if(isset($filtrar)) {
                    $fecha_ini_sinconvertir = $_POST["fecha_inicio"];
                    $fecha_fin_sinconvertir = $_POST["fecha_fin"];
                    $fecha_ini = gfecha($fecha_ini_sinconvertir);
                    $fecha_fin = gfecha($fecha_fin_sinconvertir);
                    $ret_id        = $_POST['comboRetenciones'];
                    
                    if ($ret_id != 0)
                        $resultado = getRetencionesByIdAndFecha($ret_id, $fecha_ini, $fecha_fin);
                    else
                        $resultado = getRetencionesByFecha($fecha_ini, $fecha_fin);
                    
           }

              

       
        

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
      <?php if(!isset($filtrar)){ ?>
      $("#fecha_inicio").datepick("setDate" , primerDiaDelMesActual);
      <? } ?>
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
      <h2>Panel de control - Reporte de retenciones</h2>

     <div id="buscador" >     
<form name="filtro" action="<?php echo $PHP_SELF;?>" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="14%"><div align="right">Retenci&oacute;n</div></td>
         <td width="34%"><select name="comboRetenciones" id="comboRetenciones" class="campos2" >
           <option value="0">Ver todas</option>      
           <?php
          while($fila = mysql_fetch_array($retenciones)){
    ?>
           <option value="<?php echo($fila["ret_id"]); ?>"<?php if($ret_id==$fila["ret_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["nombre"])); ?></option>
           <?php
          }
    ?>
         </select></td>
       </tr>
       <tr>
         <td><div align="right">Desde</div></td>
         <td><input type="text" name="fecha_inicio" id="fecha_inicio" class="campos2" <? if(isset($filtrar)) echo "value=$fecha_ini_sinconvertir"; ?>></td>
         <td>Hasta 
           <input type="text" name="fecha_fin" id="fecha_fin" class="campos2" <? if(isset($filtrar)) echo "value=$fecha_fin_sinconvertir"; ?>>
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
      <div class="clear">
        <div align="center">REPORTE DE RETENCIONES</div>
      </div>
      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="20">Pago</a>  
            <td width="30">Retención</td>  
            <td width="40">Fecha</td>
            <td width="30">Prefijo</td>
            <td width="30">Nro</td>
            <td width="50">Importe</td>
            <td width="50">Ver pago</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
  <?php
          if ($resultado != null){
                $total = 0;
          while($fila = mysql_fetch_array($resultado)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo $fila["id"]?></td> 
            <td><?php echo $fila["nombre"]?></td>
            <td><?php echo mfecha($fila["ret_fecha"])?></td>
            <td><?php echo $fila["ret_prefijo"];?></td>
            <td><?php echo $fila["ret_codigo"];?></td>
            <td><?php echo $fila["ret_importe"]?></td>
            <td></td>
            <td align="center"><a href="ver-alta-pago-grupo.php?grupo_fav=<?php echo $fila["grupo_fav_id"]?>&action=1" target="_blank"><img src="images/detalles.png"/></a></td>           
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}
            
            $total += $fila["ret_importe"];
            $nombreRetencion = $fila["nombre"];
          }
          }
  ?>
      

          <tr>
            <td colspan="8" class="pie_lista"><?php 
            if(isset($filtrar))
                   if($ret_id == 0)   
                         echo "El total acumulado por retenciones es de $total pesos";
                  else
                         echo "El total acumulado por ".$nombreRetencion ." es de $total pesos";
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
