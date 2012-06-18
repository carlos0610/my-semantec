<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
        
        $action = $_GET["action"];
        
        if ($action == 1){
            $prv_id = $_POST["comboProveedor"];
        }else {
            $prv_id = $_GET["prv_id"];           
        }
        
                        
        /* OBTENGO DATOS DE PROVEEDOR */
        $sql = "SELECT prv.prv_id,prv_nombre,prv_direccion,prv_cuit,iva_nombre,prv_telefono,cc.ccp_id, p.nombre as provincia,pa.nombre as partido,l.nombre as localidad
                FROM proveedores prv ,iva_tipo i,ubicacion u,provincias p, partidos pa,localidades l,cuentacorriente_prv cc
                WHERE prv.estado = 1 
                AND prv.prv_id = $prv_id
                AND prv.iva_id = i.iva_id
                AND prv.ubicacion_id = u.id
                AND u.provincias_id = p.id
                AND u.partidos_id = pa.id
                AND u.localidades_id = l.id
                AND prv.prv_id = cc.prv_id
                ORDER BY prv_nombre";
        
       $proveedor = mysql_query($sql); 
       $fila_datos_proveedor = mysql_fetch_array($proveedor); 
        
        
        
/* CALCULO PAGINADO */  ###############################################################################
    $sql0="SELECT o.ord_id,ord_codigo,c.cli_nombre,p.nombre as provincia,l.nombre as localidad,o.ord_descripcion,o.est_id,sum(od.ord_det_monto) as Adelantos,o.ord_costo Presupuesto,o.ord_costo - sum(od.ord_det_monto) as Saldo 
            FROM ordenes o, ordenes_detalle od,clientes c,ubicacion u,provincias p,localidades l
            WHERE 
            o.ord_id IN (select ord_id from ordenes where prv_id = $prv_id)
            AND o.ord_id = od.ord_id
            AND o.cli_id = c.cli_id
            AND u.id = c.ubicacion_id
            AND u.provincias_id = p.id
            AND u.localidades_id = l.id
            GROUP BY o.ord_id";
    
    
    $tamPag=100;
    
    include("paginado.php");        
        $sql = "SELECT o.ord_id,ord_codigo,c.cli_nombre,p.nombre as provincia,l.nombre as localidad,o.ord_descripcion,o.est_id,sum(od.ord_det_monto) as Adelantos,o.ord_costo Presupuesto,o.ord_costo - sum(od.ord_det_monto) as Saldo 
            FROM ordenes o, ordenes_detalle od,clientes c,ubicacion u,provincias p,localidades l ";
         
        $sql .= " WHERE 
            o.ord_id IN (select ord_id from ordenes where prv_id = $prv_id)
            AND o.ord_id = od.ord_id
            AND o.cli_id = c.cli_id
            AND u.id = c.ubicacion_id
            AND u.provincias_id = p.id
            AND u.localidades_id = l.id
            GROUP BY o.ord_id";
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
                
        $resultado = mysql_query($sql);
        
        $cantidad = mysql_num_rows($resultado);
        //$resultado2 = $resultado;
        

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
  <td colspan="4"><h2>Datos proveedor</h2></td>
  </tr>
<tr>
            <td width="15%" class="titulo">Proveedor:</td>
            <td colspan="3" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_proveedor["prv_nombre"]);?></td>
       </tr>
          <tr>
            <td class="titulo">Domiclio:</td>
            <td width="24%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_proveedor["prv_direccion"]);?></td>
            <td width="9%" class="titulo">Localidad:</td>
            <td width="52%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_proveedor["provincia"]);?>/<?php echo utf8_encode($fila_datos_proveedor["localidad"]);?></td>
       </tr>
          <tr>
            <td class="titulo">IVA:</td>
            <td style="background-color:#cbeef5"><?php echo $fila_datos_proveedor["iva_nombre"]?></td>
            <td class="titulo">Cuit:</td>
            <td style="background-color:#cbeef5"><?php echo (verCUIT($fila_datos_proveedor["prv_cuit"]))?></td>
          </tr>
          <tr>
            <td class="titulo">Nro. Cuenta corriente:</td>
            <td style="background-color:#cbeef5"><?php echo $fila_datos_proveedor["ccp_id"]?></td>
            <td class="titulo">Teléfono:</td>
            <td style="background-color:#cbeef5"><?php echo $fila_datos_proveedor["prv_telefono"]?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table>
   </div>
   <div id="adelanto">
     <table width="100%" border="0">
       <tr>
         <td colspan="4"><h2>Emitir un adelanto</h2></td>
       </tr>
       <tr>
         <td width="8%">&nbsp;</td>
         <td width="14%"><label>
           Orden:
               <select name="comboOrdenes" id="comboOrdenes">
                   <?php while($filita = mysql_fetch_array($resultado)){ ?>
                   <option value="<?php echo $filita['ord_id']?>"><?php echo $filita['ord_codigo']?></option>
                   <?php } mysql_data_seek($resultado, 0) ?>
                   
           </select>
         </label></td>
         <td width="16%">Adelanto: 
           <label>
           <input name="txtAdelanto" type="text" class="campos2" id="txtAdelanto" value="0.00" size="8">
         </label></td>
         <td width="62%">Descripción: 
           <label>
           <input name="txtDescripcion" type="text" class="campos" id="txtDescripcion" value="Ingrese una descripción">
           <input type="button" name="btnEmitir" id="btnEmitir" value="Emitir" onclick="emitirAdelanto()">
         </label></td>
       </tr>
     </table>
    </div>
   
   <!--end datos_cliente-->
   
   
   <div id="contenedor" style="height:auto;">
      <h2>Cuenta corriente de <?php echo utf8_encode($fila_datos_proveedor["prv_nombre"]);?></h2>

<table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="80">Código de orden</td>
            <td width="120">Cliente</td>
            <td width="449">Descripción</td>
            <td width="88">Presupuesto</td>
            <td width="83">Cancelado</td>
            <td width="83">Adelantos</td>
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
            <td><?php echo(utf8_encode($fila["cli_nombre"]));?> (<?php echo(utf8_encode($fila["provincia"]));?>)</td>
            <td><?php echo(utf8_encode($fila["ord_descripcion"]));?></td>
            <td><?php echo $fila["Presupuesto"];?></td>
            <td>&nbsp;</td>
            <td><?php echo $fila["Adelantos"];?></td>
    <td><?php echo $fila["Saldo"];?></td>
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}
            
             
            $totalDeuda += $fila["Saldo"];
            
          } // FIN_WHILE
          
          echo "<tr><td colspan=5 align=right>Total deuda con proveedor: <b>$totalDeuda</b> pesos</td></tr>";
          
  ?>
          <tr>
            <td colspan="6" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>   

     <div class="clear"></div>
          <br>
     <a href="form-seleccionar-proveedor.php?action=1"><input type="button" value="Volver" class="botones" /></a> &nbsp; &nbsp;
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
