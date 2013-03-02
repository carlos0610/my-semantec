<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        include("Modelo/modeloAbonosDetalle.php");
        include("Modelo/modeloFacturaCompra.php");
        include("Modelo/modeloOrdenes.php");
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
                                                ord_det_fecha datetime  not null,
                                                cli_id     int        not null,
					    	cli_nombre varchar(100) not null,
                                                sucursal   varchar(100) ,

                                                ord_plazo datetime   null,
                                                fecha_pendiente_facturacion datetime   null,
                                                es_abono     int        not null,

					    	provincia  varchar(100) not null,
					    	localidad  varchar(100) not null,
					    	ord_descripcion varchar(250) not null,
					    	est_id int not null,
					    	adelantos decimal(10,2) not null,
					    	costo decimal(10,2) not null,
					    	saldo_a decimal(10,2) not null,
					    	compras decimal(10,2),
					    	saldo_c decimal(10,2),
					    	costo_abono decimal(10,2),
                                                fecha_recepcion_ot date,
                                                retraso_ot integer,
					    	PRIMARY KEY  (`ord_id`)
					    
					      )";  
       
        mysql_query($sql);
        
        /* INSERTAMOS EN LA TABLA TEMPORAL EL RESULTADO DE LA CONSULTA QUE AVERIGUA LOS ADELANTOS VS ORD_COSTO */
        
        $sql = "INSERT INTO tabla_temp	      
		SELECT o.ord_id,ord_codigo,max(ord_det_fecha),c.cli_id,c.cli_nombre,c.sucursal, o.ord_plazo, o.fecha_pendiente_facturacion, o.es_abono,
                       p.nombre as provincia,l.nombre as localidad,o.ord_descripcion,o.est_id,
                       sum(od.ord_det_monto) as adelantos,
                       round (o.ord_costo,2) as presupuesto ,
                       o.ord_costo - sum(od.ord_det_monto) as Saldo,
                       0,
                       round (o.ord_costo,2) as presupuesto,0 ,o.fecha_recepcion_ot,datediff(now(),o.fecha_recepcion_ot)
                FROM ordenes o, ordenes_detalle od,clientes c,ubicacion u,provincias p,localidades l
                WHERE 
                o.ord_id IN (select ord_id from ordenes where prv_id = $prv_id AND estado = 1)
                AND o.estado = 1
                AND o.ord_id = od.ord_id
                AND o.cli_id = c.cli_id
                AND u.id = c.ubicacion_id
                AND u.provincias_id = p.id
                AND u.localidades_id = l.id
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
       //SELECT dfc.det_fco_orden_id,sum(dfc.det_fco_preciounitario) as compras,o.ord_costo - SUM(dfc.det_fco_preciounitario) as saldoc 
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
      
       /*** CREAMOS LA TABLA TEMPORAL 3 PARA GUARDAR EL VALOR DE COSTO DE LOS ABONOS ***/
       $sql = "CREATE TEMPORARY TABLE tabla_temp3 (
            		cli_id int not null,
            		valor_costo decimal(10,2) not null
            		); ";
       
       mysql_query($sql);
       
       /*** OBTENEMOS EL VALOR DE COSTO DEL ABONO DE LAS ORDENES QUE ESTAN MARCADAS CON ABONO ***/
       $sql = "INSERT INTO tabla_temp3
					 SELECT cli_id,valor_costo 
                        FROM `abonos_detalle` 
                        WHERE `cli_id`IN (select cli_id from tabla_temp where es_abono = 1)
                        AND estado=1
                        ORDER BY abonos_id;";
       
       mysql_query($sql);
       /*UPDATEAMOS LA TABLA TEMP 1 CON LA DATA QUE METIMOS EN LA T3*/
       $sql = "UPDATE tabla_temp t1,tabla_temp3 t3
					 SET t1.costo_abono = t3.valor_costo,t1.saldo_a = t1.saldo_a + t3.valor_costo
					 WHERE t1.cli_id = t3.cli_id
					 AND est_id < 12 
					 AND t1.es_abono = 1";
       
            mysql_query($sql);
            
       $sql = "UPDATE tabla_temp t1,abono_de_orden a
					 SET t1.costo_abono = a.valor_costo ,t1.saldo_a = t1.saldo_a + a.valor_costo
					 WHERE t1.ord_id = a.ord_id 
					 AND est_id >= 12
					 AND t1.es_abono = 1
					 AND a.abono_id in (SELECT h.abonos_detalle_id FROM historial_abonos h where ordenes_ord_id = t1.ord_id);";     
            
            mysql_query($sql);
       
       $sql = "SELECT * FROM tabla_temp";
       
       /*Filtro para ver ordenes con saldo pendiente */
       $filtro = $_POST["chkFiltroEstado"];
       $opcionComboFiltro = 0;
       
       
       if ($filtro != ""){
           $opcionComboFiltro =$_POST["comboFiltro"];
       switch($opcionComboFiltro){
         case 1:    $sql.=" ";break;
         case 2:    $sql.=" WHERE saldo_a <> 0";break;
         case 3:    $sql.=" WHERE saldo_a = 0";break;  // quitar elANS es _abono porq antes revisar q haga la suma  correcta costo de orde mas abono 
             
        }
       }
        
       $filtro2 = $_POST["chkFiltroOT"];
       
        if ($filtro2!=""){
            $desde = $_POST["fecha_inicio"];
            $hasta = $_POST["fecha_fin"];
            $desde1 = gfecha($desde);
            $hasta1 = gfecha($hasta);
           
            if ($filtro != ""){
                $sql.=" AND fecha_recepcion_ot BETWEEN convert('$desde1',datetime) AND convert('$hasta1 23:59:59',datetime)";
            }else{
                $sql.=" WHERE fecha_recepcion_ot BETWEEN convert('$desde1',datetime) AND convert('$hasta1 23:59:59',datetime)";
            }
        }
       
       $resultado = mysql_query($sql);  
       $sql0 = $sql;
       
      
    
    $tamPag=15;
    
    include("paginado.php");        
       
   $sql .= " LIMIT ".$limitInf.",".$tamPag;
         
        
        $resultado = mysql_query($sql); 
        
        /*Hacemos un SUM para calcular el total de la deuda*/
        $total = mysql_query("SELECT sum(saldo_c) as total_compra,sum(saldo_a) as total_adelanto,sum(costo) as total from tabla_temp;");
        $totalDeuda = mysql_fetch_array($total);
        
        // paso al excel
        $cc_arreglo = mysql_query($sql0);
        $array=array();
        while ($row=mysql_fetch_array($cc_arreglo)) {
         $array[]=$row;
        }  
        $_SESSION["cc_arreglo"]     = $array;
        // ----Fin---
        $cantidad = mysql_num_rows($resultado);
        
        

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
    
        /* DROPEAMOS LAS TABLAS TEMPORALES PARA QUE NO QUEDEN EN MEMORIA */
       $sql = "DROP TABLE tabla_temp;";
       mysql_query($sql);
       
       $sql = "DROP TABLE tabla_temp2;";
       mysql_query($sql);
       
       $sql = "DROP TABLE tabla_temp3;";
       mysql_query($sql);
       
       /*OBTENEMOS EL PARAMETRO DE FECHA DE VENCIMIENTO DE OT*/
       
       $sql = "SELECT valor FROM parametros WHERE par_id = 3";
       $result = mysql_query($sql);
       $vencimiento_ot = mysql_fetch_array($result);
       
       // combo de ordenes proveedor
       $resultado1 =getOrdenesWithProvId($prv_id);
       
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
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.datepick.js"></script>
  <script type="text/javascript" src="js/jquery.datepick-es.js"></script>
  <script type="text/javascript" src="js/validador.js"></script>
  <script type="text/javascript">
  $(function() {
     
    $('#fecha_inicio').datepick();
        /* Obtenemos mes y a;o actual */
                var fecha = new Date();
                var mes  = fecha.getMonth()+1;
                var anho = fecha.getFullYear();
        /*Armamos la fecha para setear el primer dia del mes por defecto como -fecha de inicio- */
                var primerDiaDelMesActual =("01/"+mes+"/"+anho);
      
     // $("#fecha_inicio").datepick("setDate" , primerDiaDelMesActual);
  })
  
  $(function() {
      $('#fecha_fin').datepick();
  }); 
  
  function mostrar(obj, trig){
    var elDiv = document.getElementById(obj);
    var laFlecha = document.getElementById(trig);  
    //alert( elDiv.style.display );
    if( elDiv.style.display == 'none' ){ 
      elDiv.style.display = 'block';
      //laFlecha.style.background = '#fff url(../images/arrows.png) no-repeat 3px 1px';
      laFlecha.style.backgroundPosition = '3px 1px';
    }
    else{ 
      elDiv.style.display = 'none';
      //laFlecha.style.background = '#fff url(../images/arrows.png) no-repeat 3px -15px';
      laFlecha.style.backgroundPosition = '3px -15px';
    }
    
  }

 </script>
  
  
  
  </head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="mainMasAncho">

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
            <td width="40%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_datos_proveedor["prv_direccion"]);?></td>
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
   <!--end datos_cliente-->
   
   <!-- adelanto -->
   <div id="adelanto">
     <table width="100%" border="0">
       <tr>
         <td colspan="4"><h2>Emitir un adelanto</h2></td>
       </tr>
       <tr>
         <td width="8%">&nbsp;</td>
         <td width="14%">
        
          <!--       <input type="text" name="txtOrden" id="txtOrden" value="" class="campos2" onChange="autenticaOrdenCCProveedor()">
               <span id="error" style="font-family: Verdana, Arial, Helvetica,sans-serif;font-size: 9pt;color: #CC3300;position:relative;visibility:hidden;">La Orden NO existente</span>
          -->
         
         
         
               <select name="txtOrden" id="txtOrden" class="campos2"  >
                    <option value='0'>Seleccione</option>;
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["ord_codigo"]); ?>">
                        <?php echo($fila["ord_codigo"]); ?></option>
    <?php
          }
    ?>
                </select>
         
         
         
         
         
         
         </td>
         <td width="16%">Adelanto: 
           <label>
           <input name="txtAdelanto" type="text" class="campos2" id="txtAdelanto" value="0.00" size="8" OnKeyUp="return validarReal('txtAdelanto');">
         </label></td>
         <td width="62%">Descripción: 
           <label>
           <input name="txtDescripcion" type="text" class="campos" id="txtDescripcion" value="Ingrese una descripción">
           <input type="button" class="botones" name="btnEmitir" id="btnEmitir" value="Emitir" onClick="emitirAdelanto('<?php echo $pagina?>')">
         </label></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
     </table>
    </div>
   <!-- div adelantos -->
   
   <div id="filtros">
       <form id="filtro" name="filtro" action="ver-corriente-proveedor.php" method="POST">
   <table width="100%" border="0">
   <tr>
     <td colspan="5"><h2>Filtrar por estado de orden <input type="checkbox" name="chkFiltroEstado" id="chkFiltroEstado" onClick="habilitarFiltros('chkFiltroEstado','comboFiltro')" <?php if ($filtro!="") echo "checked"?>></h2></td>
   <tr>
     <td width="8%">&nbsp;</td>
     <td width="19%"><label>
       <select name="comboFiltro" class="campos2"  id="comboFiltro" <?php if ($filtro==""){?>disabled <?php } ?>>
           <option value="0">Seleccione </option>
         <option value="1" <?php if ($opcionComboFiltro==1) echo "selected"?> >Todos</option>
         <option value="2" <?php if ($opcionComboFiltro==2) echo "selected"?>>Pendientes de pago</option>
         <option value="3" <?php if ($opcionComboFiltro==3) echo "selected"?>>Canceladas</option>
       </select>
     </label></td>
     <td colspan="2"><label>
     
     </label></td>
     <td width="40%">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="5"><h2>Filtrar órdenes por fecha de recepción de OT <input type="checkbox" name="chkFiltroOT" id="chkFiltroOT" onClick="habilitarFiltros('chkFiltroOT','fecha_inicio','fecha_fin')" <?php if($filtro2!="") echo "checked";?>></h2></td>
     </tr>
   <tr>
     <td>Desde</td>
     <td><label>
       <input type="text" class="campos2"  name="fecha_inicio" id="fecha_inicio" <?php if ($filtro2!=""){ ?> value="<?php echo $desde;?>" <?php } else {?> disabled <?php } ?>>
     </label></td>
     <td width="9%">Hasta</td>
     <td width="24%"><label>
             <input type="text" class="campos2"  name="fecha_fin" id="fecha_fin" <?php if ($filtro2!=""){ ?> value="<?php echo $hasta;?>" <?php } else {?> disabled <?php } ?> >
     </label></td>
     <td><input type="submit" name="filtrar" id="filtrar" value="Filtrar" class="botones" onClick="return validaSeleccione('comboFiltro', 'Seleccione un filtro')"></td>
   </tr>
   </table>
     </form>
   </div>
   
   
   <div id="contenedor" style="height:auto;">
       <form id="filtro" name="filtro" action="ver-corriente-proveedor.php" method="POST">
      <h2>Cuenta corriente de <?php echo utf8_encode($fila_datos_proveedor["prv_nombre"]);?>  - <br/><?php if ($filtro2 !="") echo "Viendo OT recibidas desde el $desde hasta $hasta";?>
             <a href="exportacion/exportarExcelCC-Proveedor.php?sql=<?php echo $sql0;?>&nombre=<? echo $fila_datos_proveedor["prv_nombre"] ?>"> 
                 <img src="images/icon-header-xls.png" alt="Listado Excel" title="Listado Excel" width="32" height="32" border="none" />
             </a></h2>  

<table class="listadosMasAncho" cellpadding="5">
          <tr class="titulo">
            <td width="190">Código de orden</td>
            <td width="190">Cliente</td>
            <td width="700">Descripción</td>
            <td width="88">Recepción OT</td>
            <td width="100">Ord costo</td>
            <td width="100">Abono</td>
            <td width="100">Adelantos</td>           
            <td width="100">Saldo</td>
            <td width="100">Facturado</td>          
            <td width="100">Resta facturar</td>
        </tr>
  <?php
          $totalSaldoValor=0;
          $totalFacturado=0;
          $totalOrdenes=0;
          $totalRestaFacturar=0;
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" <?php if($fila["saldo_a"] != 0 && $fila["retraso_ot"] > $vencimiento_ot["valor"]){?> bgcolor="66FF99" <?php }else {?> bgcolor="<?php echo($colores[$i]);}?>">
              
              
              
              
              
                <td><a href="lista-req-ordenes.php?orden=<?php echo($fila["ord_codigo"]);?>&action=1" target="_blank"><?php echo($fila["ord_codigo"]);?></a></td>
                <td><a href="ver-alta-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>&action=0"><?php echo(utf8_encode($fila["cli_nombre"]));?> (<?php echo(utf8_encode($fila["provincia"]));?>/<?php echo(utf8_encode($fila["sucursal"]));?>)</a></td>
                <td>
                    <?php echo(utf8_encode($fila["ord_descripcion"]));?>
                </td>
                <td style="text-align: center;">
                    <?php 
                        if($fila["fecha_recepcion_ot"]!='')
                            {echo tfecha($fila["fecha_recepcion_ot"]);}
                        else
                            {echo '-';} 
                     ?>
                </td>
                <td style="text-align:right;">
                    <?php // COSTO ORDEN
                        $ordenCosto=$fila["costo"];
                        $totalOrdenes+=$ordenCosto;
                        echo number_format($ordenCosto,2,',','.');
                     ?>
                </td>
                <td style="text-align:right;">
                    <?php // ABONO
                     $abonoValor=$fila["costo_abono"];
                    echo number_format($abonoValor,2,',','.');
//                       if($fila["es_abono"]==1){
//                           echo $abonoValor=getAbonoDetalle_ValorCostoWithCliId($fila["cli_id"]);}
//                     else
//                         {echo '0.00';}
                     $totalOrdenes+=$abonoValor;
                    ?>
                </td>
                <td style="text-align:right;">
                    <?php echo number_format($fila["adelantos"],2,',','.');?>
                </td>               
                <td style="text-align:right; background-color: darksalmon;">
                    <?php // SALDO
                          $saldoValor=$fila["saldo_a"];
                          $totalSaldoValor+=$saldoValor;
                          echo number_format($saldoValor, 2, ',', '.'); ?>
                </td>
                <td style="text-align:right;">
                    <?php  // FACTURADO
                        $Facturado=$fila["compras"];
                        $totalFacturado+=$Facturado;
                        echo number_format($Facturado,2,',','.');
                     ?>
                </td>
                <td style="text-align:right; background-color:dodgerblue;">  
                    <?php //Resta FACTURAR echo $fila["saldo_c"];  
                        $RestaFacturar=$saldoValor-$Facturado;
                        $totalRestaFacturar+=$RestaFacturar;
                        echo number_format($RestaFacturar,2,',','.');
                    ?>
                </td>
                
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}
            
             
           // $totalDeuda += $fila["saldo_a"];
            
          } // FIN_WHILE
          

       //    echo "<tr><td colspan=8 align=right>Monto total de órdenes : <b>".number_format($totalOrdenes,2,'.',' ')."</b> pesos";
  ?>
          <tr>
            <td colspan="10" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>   
</form>
     <div class="clear"></div>
     <?php       include("conexion.php"); 
                $resultado = getFacturasComprasWithProveedorId($prv_id);

                  $i = 0;
                  $colores = array("#fff","#e8f7fa");
                  $cant = count($colores);

                ?>
     
     <div>
         <big> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Mostrar Datos de Compra </big>
            <div id="flechaEspecial" onclick="mostrar('ver1', 'flechaEspecial')">  <br></div>    </div> 
            
           <table class="listados" cellpadding="5" id="ver1" style="display:none;">
          <tr class="titulo">
            <td width="100"><a href="#" onClick="agregarOrderBy('fco_id')">Factura Nro</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('fco_fecha')">Fecha de emisión</a></td>
            <td width="300"><a href="#" onClick="agregarOrderBy('det_fco_descripcion')">Descripción</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('fco_subtotal')">Monto</a></td>
          </tr>
         
  <?php  $totalMonto=0;
          while($fila = mysql_fetch_array($resultado)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>"> 
            <td><?php echo($fila["fco_id"]);?></td>
            <td><?php echo(tfecha($fila["fco_fecha"]));?></td>
            <td><?php echo(utf8_encode($fila["det_fco_descripcion"]));?></td>                  
            <td style="text-align:right;">
                <?php
                    $totalMonto+=$fila["fco_subtotal"];
                    echo number_format(($fila["fco_subtotal"]), 2, ',', '.');?>
            </td> 
            
          </tr>
  <?php  
            $i++;
            if($i==$cant){$i=0;}

          }
          
  ?>    
      </table>
     
     
     
<? 
          echo "<tr><td colspan=8 align=right><big>Monto total de órdenes : <b>".number_format($totalDeuda['total'], 2, ',', '.')."</b> pesos</big></tr></td> 
              <tr><td colspan=8 align=right><big> ----- Monto total de Compras : <b>".number_format($totalMonto, 2, ',', '.')."</b> pesos</big></tr></td> 
              <br>";
          echo "<tr><td colspan=8 align=right><big>Total deuda con proveedor: <b style=color:red>".number_format($totalDeuda['total_adelanto'], 2, ',', '.')."</b> pesos ---- Total deuda del proveedor: <b style=color:blue>".number_format(($totalDeuda['total_compra']-$totalMonto), 2, ',', '.')."</b> pesos</big></td></tr>";


?>

     
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
