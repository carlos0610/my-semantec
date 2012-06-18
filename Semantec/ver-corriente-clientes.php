<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
        
        $cli_id = $_POST["comboCliente"];  
                        
        /* OBTENGO DATOS DE CLIENTE */
        $sql = "SELECT c.cli_nombre,c.cli_direccion,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad,i.iva_nombre,c.cli_cuit,cc.ccc_id 
                FROM clientes c,ubicacion u,iva_tipo i,cuentacorriente_cliente cc,provincias p,partidos pa,localidades l
                    WHERE 
                    c.cli_id = $cli_id
                    and cc.cli_id = c.cli_id
                    and c.iva_id = i.iva_id
                    and c.ubicacion_id = u.id
                    and u.provincias_id = p.id
                    and u.partidos_id = pa.id
                    and u.localidades_id = l.id";
        
       $cliente = mysql_query($sql); 
       $fila_datos_cliente = mysql_fetch_array($cliente); 
        
        
        
/* CALCULO PAGINADO */  ###############################################################################
    $sql0="SELECT o.ord_id,o.ord_codigo,f.fav_id,o.ord_descripcion,o.ord_venta,o.est_id 
                FROM ordenes o,cuentacorriente_cliente cc ,factura_venta f,grupo_ordenes g_o
                WHERE cc.cli_id = o.cli_id 
                AND cc.cli_id = $cli_id 
                AND o.estado = 1 
                AND cc.estado = 1 
                AND o.est_id >= 12
                AND g_o.gru_id = f.gru_id
                AND g_o.gru_id = o.gru_id";
    $tamPag=100;
    
    include("paginado.php");        
        $sql = "SELECT o.ord_id,o.ord_codigo,f.fav_id,o.ord_descripcion,o.ord_venta,o.est_id 
                    FROM ordenes o,cuentacorriente_cliente cc ,factura_venta f,grupo_ordenes g_o
                    WHERE cc.cli_id = o.cli_id 
                    AND cc.cli_id = $cli_id 
                    AND o.estado = 1 
                    AND cc.estado = 1 
                    AND o.est_id >= 12
                    AND g_o.gru_id = f.gru_id
                    AND g_o.gru_id = o.gru_id";
                $sql .= " LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        
        
        $totalDeuda = 0;
        $estadoPagado = 14;  //ESTADO HARDCODEADO del Estado Finalizado.
        
        
        
        mysql_close();
        
        
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
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start datos_cliente-->
   <div id="datos_cliente">
   <table width="100%" border="0" id="dataTable">
<tr>
  <td colspan="4"><h2>Datos cliente</h2></td>
  </tr>
<tr>
            <td width="15%" class="titulo">Cliente:</td>
            <td colspan="3" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_cliente["cli_nombre"]);?></td>
       </tr>
          <tr>
            <td class="titulo">Domiclio:</td>
            <td width="24%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_cliente["cli_direccion"]);?></td>
            <td width="9%" class="titulo">Localidad:</td>
            <td width="52%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_cliente["provincia"]);?>/<?php echo utf8_encode($fila_datos_cliente["localidad"]);?></td>
       </tr>
          <tr>
            <td class="titulo">IVA:</td>
            <td style="background-color:#cbeef5"><?php echo $fila_datos_cliente["iva_nombre"]?></td>
            <td class="titulo">Cuit:</td>
            <td style="background-color:#cbeef5"><?php echo (verCUIT($fila_datos_cliente["cli_cuit"]))?></td>
          </tr>
          <tr>
            <td class="titulo">Nro. Cuenta corriente:</td>
            <td style="background-color:#cbeef5"><?php echo $fila_datos_cliente["ccc_id"]?></td>
            <td class="titulo">&nbsp;</td>
            <td style="background-color:#cbeef5">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table>
   </div>
   
   <!--end datos_cliente-->
   
   
   <div id="contenedor" style="height:auto;">
      <h2>Ordenes realizadas por <?php echo utf8_encode($fila_datos_cliente["cli_nombre"]);?></h2>

  <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="100">Código de orden</td>
            <td width="100">Nro de factura</td>
            <td width="467">Descripción</td>
            <td width="66">Valor venta</td>
            <td width="67">Cancelada</td>
            <td width="73">Saldo</td>
<td width="35">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
        </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
              <td><a href="ver-alta-ordenes.php?ord_id=<?php echo($fila["ord_id"]);?>&action=0" target="_blank"><?php echo($fila["ord_codigo"]);?></a></td>
              <td><?php echo($fila["fav_id"]);?></td>
              <td><?php echo(utf8_encode($fila["ord_descripcion"]));;?></td>
            <td><?php echo $fila["ord_venta"];?></td>
            <td><?php if ($fila["est_id"]==$estadoPagado){
                        echo "Sí";
                        }
                    else
                        {
                        echo "No";
                        }
                ?></td>       
          <td><?php if ($fila["est_id"]==$estadoPagado){
                         echo 0;
                        }
                    else
                        {
                        echo $fila["ord_venta"];
                        }
                ?></td>
    <td>&nbsp;</td>
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}
            
             if ($fila["est_id"]!=$estadoPagado)   //SI CANCELO ORDEN, SUMAMOS AL $totalDeuda
            $totalDeuda += $fila["ord_venta"];
          } // FIN_WHILE
          
          echo "<tr><td colspan=5 align=right>Total deuda cliente: <b>$totalDeuda</b> pesos</td></tr>";
          
  ?>
          <tr>
            <td colspan="5" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>   
      
     <div class="clear"></div>
     <br>
     <a href="form-seleccionar-cliente.php"><input type="button" value="Volver" class="botones" /></a> &nbsp; &nbsp;
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
