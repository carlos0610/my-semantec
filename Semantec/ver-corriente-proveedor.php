<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        
        $action = $_GET["action"];
        
        if ($action == 1){
            $prv_id = $_POST["comboProveedor"];
            $_SESSION['proveedor'] = $prv_id;
        }else   {
            $prv_id = $_GET["prv_id"];
                }
        
     
          $prv_id = $_SESSION['proveedor'];
          
          
        
                        
        /* OBTENGO DATOS DE PROVEEDOR */
        $sql = "SELECT prv.prv_id,prv_nombre,prv_direccion,prv_cuit,iva_nombre,prv_telefono,cc.ccp_id, p.nombre as provincia,pa.nombre as partido,l.nombre as localidad,prv.sucursal
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
        
        
        
/* CALCULO PAGINADO - DATOS DE LISTADO DE CUENTA CORRIENTE */  ###############################################################################
    /*CON LA MAGIA*/
       
       
     /* CREAMOS TABLA TEMPORAL QUE VA ALMACENAR EL REPORTE CON LOS ADELANTOS */  
        $sql = "CREATE TEMPORARY TABLE tabla_temp
					(
                                                ord_id int NOT NULL,
                                                ord_codigo varchar(30)  not null,
                                                cli_id     int        not null,
					    	cli_nombre varchar(100) not null,
                                                sucursal   varchar(100) ,
					    	provincia  varchar(100) not null,
					    	localidad  varchar(100) not null,
					    	ord_descripcion varchar(250) not null,
					    	est_id int not null,
					    	adelantos decimal(10,2) not null,
					    	costo decimal(10,2) not null,
					    	saldo_a decimal(10,2) not null,
					    	compras decimal(10,2),
					    	saldo_c decimal(10,2),
					    	PRIMARY KEY  (`ord_id`)
					    
					      );";  
       
        mysql_query($sql);
        
        /* INSERTAMOS EN LA TABLA TEMPORAL EL RESULTADO DE LA CONSULTA QUE AVERIGUA LOS ADELANTOS VS ORD_COSTO */
        
        $sql = "INSERT INTO tabla_temp	      
		SELECT o.ord_id,ord_codigo,c.cli_id,c.cli_nombre,c.sucursal,p.nombre as provincia,l.nombre as localidad,o.ord_descripcion,o.est_id,sum(od.ord_det_monto) as adelantos,round (o.ord_costo,2) as presupuesto ,o.ord_costo - sum(od.ord_det_monto) as Saldo,0,round (o.ord_costo,2) as presupuesto 
                FROM ordenes o, ordenes_detalle od,clientes c,ubicacion u,provincias p,localidades l
                WHERE 
                o.ord_id IN (select ord_id from ordenes where prv_id = $prv_id AND estado = 1)
                AND o.estado = 1
                AND o.ord_id = od.ord_id
                AND o.cli_id = c.cli_id
                AND u.id = c.ubicacion_id
                AND u.provincias_id = p.id
                AND u.localidades_id = l.id
                AND o.ord_costo <> 0
                AND o.est_id <> 8           
                GROUP BY o.ord_id;"; 
     
        mysql_query($sql);
        
            /* CREAMOS LA TABLA TEMPORAL 2 . QUE VA A ALMACENAR EL OTRO REPORTE */
       $sql =  "CREATE TEMPORARY TABLE tabla_temp2 (
            		ord_id int not null,
            		compras decimal(10,2) not null,
            		saldoc decimal(10,2) not null,
                        PRIMARY KEY (ord_id)
            
                        );";
       
       mysql_query($sql);
       
            /* INSERTAMOS EN LA TABLA TEMPORAL 2 , LA CONSULTA QUE COMPARA EL ORD COSTO VS LOS DETALLES DE FACTURA DE COMPRA */
       $sql = "INSERT INTO tabla_temp2
               SELECT dfc.det_fco_orden_id,sum(dfc.det_fco_preciounitario) as compras,o.ord_costo - SUM(dfc.det_fco_preciounitario) as saldoc 
               FROM detalle_factura_compra dfc, ordenes o,clientes c
               WHERE dfc.det_fco_orden_id = o.ord_id
               AND o.estado = 1
               AND o.prv_id = $prv_id
               AND o.cli_id = c.cli_id
               GROUP BY dfc.det_fco_orden_id;";
       
       mysql_query($sql);
       
       
       /*  ACTUALIZAMOS EL TOTAL FACTURADO DE COMPRAS Y EL SALDO DE COMPRAS VS ORD_COSTO EN LA TABLA TEMPORAL 1 DESDE LA TABLA TEMPORAL 2*/
       $sql = "UPDATE tabla_temp t1,tabla_temp2 t2
                SET t1.compras = t2.compras,
                t1.saldo_c = t2.saldoc
                WHERE t1.ord_id = t2.ord_id;";
       
       mysql_query($sql);
       
       
       
       
       $sql = "SELECT * FROM tabla_temp";
       $resultado = mysql_query($sql);
       
     
       $sql0 = $sql;
    
    $tamPag=20;
    
    include("paginado.php");        
        /*$sql = "SELECT o.ord_id,ord_codigo,c.cli_nombre,p.nombre as provincia,l.nombre as localidad,o.ord_descripcion,o.est_id,sum(od.ord_det_monto) as Adelantos,round (o.ord_costo,2) as costo,ROUND(SUM(dfc.det_fco_preciounitario)/2,2) as compras ,ROUND(o.ord_costo - SUM(dfc.det_fco_preciounitario)/2,2) as saldoc,o.ord_costo - sum(od.ord_det_monto) as Saldo 
            FROM ordenes o, ordenes_detalle od,clientes c,ubicacion u,provincias p,localidades l,detalle_factura_compra dfc";
         
        $sql .= " WHERE 
            o.ord_id IN (select ord_id from ordenes where prv_id = $prv_id)
            AND o.ord_id = od.ord_id
            AND o.cli_id = c.cli_id
            AND o.gru_id_compra is not null
            AND o.ord_id = dfc.det_fco_orden_id
            AND dfc.det_fco_orden_id = o.ord_id
				AND u.id = c.ubicacion_id
            AND u.provincias_id = p.id
            AND u.localidades_id = l.id
            GROUP BY o.ord_id";*/
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
         
        
        $resultado = mysql_query($sql);
        
        /*Hacemos un SUM para calcular el total de la deuda*/
        $total = mysql_query("SELECT sum(costo) as total from tabla_temp;");
        $totalDeuda = mysql_fetch_array($total);
        
        
        $cantidad = mysql_num_rows($resultado);
        
        

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
    
        /* DROPEAMOS LAS TABLAS TEMPORALES PARA QUE NO QUEDEN EN MEMORIA */
       $sql = "DROP TABLE tabla_temp;";
       mysql_query($sql);
       
       $sql = "DROP TABLE tabla_temp2;";
       mysql_query($sql);
        
        
       mysql_close();
        
        
?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");    
?>
<script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="ver-corriente-proveedor.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>      
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


   <!--start datos_proveedor-->
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
            <td width="52%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_proveedor["provincia"]);?>/<?php echo utf8_encode($fila_datos_proveedor["sucursal"]);?></td>
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
           <input name="txtAdelanto" type="text" class="campos2" id="txtAdelanto" value="0.00" size="8" OnKeyUp="return validarReal('txtAdelanto');">
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
       <form id="filtro" name="filtro" action="ver-corriente-proveedor.php" method="POST">
      <h2>Cuenta corriente de <?php echo utf8_encode($fila_datos_proveedor["prv_nombre"]);?></h2>

<table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="80">Código de orden</td>
            <td width="120">Cliente</td>
            <td width="449">Descripción</td>
            <td width="88">Ord costo</td>
            <td width="83">Adelantos</td>
            <td width="83">Saldo</td>
            <td width="83">Facturado</td>          
            <td width="73">Resta facturar</td>
            
<td width="35">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
        </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
                <td><a href="ver-alta-ordenes.php?ord_id=<?php echo($fila["ord_id"]);?>&action=0" target="_blank"><?php echo($fila["ord_codigo"]);?></a></td>
                <td><a href="ver-alta-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>&action=0"><?php echo(utf8_encode($fila["cli_nombre"]));?> (<?php echo(utf8_encode($fila["provincia"]));?>/<?php echo(utf8_encode($fila["sucursal"]));?>)</a></td>
                <td><?php echo(utf8_encode($fila["ord_descripcion"]));?></td>
                <td><?php echo $fila["costo"];?></td>
                <td><?php echo $fila["adelantos"];?></td>
                <td><?php echo $fila["saldo_a"];?></td>
                <td><?php echo $fila["compras"];?></td>
                <td><?php echo $fila["saldo_c"];?></td>
    
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}
            
             
           // $totalDeuda += $fila["saldo_a"];
            
          } // FIN_WHILE
          
          echo "<tr><td colspan=8 align=right>Total deuda con proveedor: <b>".$totalDeuda['total']."</b> pesos</td></tr>";
          
  ?>
          <tr>
            <td colspan="8" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>   
</form>
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
