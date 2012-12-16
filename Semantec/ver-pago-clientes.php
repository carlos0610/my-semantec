<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");
        include("Modelo/modeloUsuarios.php");
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
    $sql0="select id,usu_id,grupo_fav_id,fecha,cli_id 
                from cobros 
                where cli_id = $cli_id and estado = 1 
                order by fecha DESC";
    $tamPag=100;
    
    include("paginado.php");        
        $sql = "select id,usu_id,grupo_fav_id,fecha,cli_id,monto_total 
                from cobros 
                where cli_id = $cli_id and estado = 1 
                order by fecha DESC";
                $sql .= " LIMIT ".$limitInf.",".$tamPag; 
                //echo "QUERY: ".$sql;
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
      
      <script type="text/javascript">
  
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
      <h2>Pagos <?php echo utf8_encode($fila_datos_cliente["cli_nombre"]);?></h2>

  <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="100">Nro Pago</td>
            <td width="100">Fecha -Hora</td>
            <td width="66">Registrado por</td>
            <td width="67">Monto</td>            
<td width="35">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
        </tr>
        
  <?php $numeroDeTablaDesplegable=0;
          while($fila = mysql_fetch_array($resultado)){ include("conexion.php");
              $numeroDeTablaDesplegable++;  
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
              <td><a href="ver-alta-pago-grupo.php?grupo_fav=<?php echo($fila["grupo_fav_id"]);?>"><?php echo($fila["id"]);?></a></td>
              <td><?php echo($fila["fecha"]);?></td>             
            <td><?php echo getUsuarioNombreWithId($fila["usu_id"]);?></td>
            <td align="right"><?php echo number_format($fila["monto_total"], 2, ',', '.');?></td>
            <tr>
                
                
                
             <td colspan="6"><div id="flecha<?php echo($numeroDeTablaDesplegable);?>" onclick="mostrar('ver<?php echo($numeroDeTablaDesplegable);?>', 'flecha<?php echo($numeroDeTablaDesplegable);?>')">  </div>
            
             <?php
              // listado de requerimientos  // listado de requerimientos  // listado de requerimientos
              // listado de requerimientos  // listado de requerimientos  // listado de requerimientos
              ?>
                <div id="ver<?php echo($numeroDeTablaDesplegable);?>" style="display:none;"> 
                  <!-- id <?php echo($numeroDeTablaDesplegable);?> -->
                  <!-- id <?php echo($numeroDeTablaDesplegable);?> -->
                  <table class="listados" cellpadding="5">
                    <tr class="titulo" style="background-color:#cdcdcd">
                       <td width="10"></td>
                      <td width="100">N° Factura</td>                      
                      <td width="400">Nota</td>
                    </tr>
              <?php  include("conexion.php");
                $grupo_Fav = $fila["grupo_fav_id"];
                $sql_req = "select fav_id,cod_factura_venta,files_id ,fav_fecha ,fav_nota,fav_remito,fav_condicion_vta,fav_vencimiento,fav_fecha_pago
                            from factura_venta 
                            where grupo_fac_pago=$grupo_Fav
                            ORDER BY fav_fecha_pago  DESC"; 
                $result_req = mysql_query($sql_req);
                while($fila_req = mysql_fetch_array($result_req)){
                  //  echo("<hr />". $sql_req ."<hr />");
          ?>
                    <tr class="lista" bgcolor="<?php echo($colores2[$j]);?>">
                      <td width="0"><?php ?></td>
                      <td width="100"><?php echo(($fila_req["cod_factura_venta"])); ?></td>
                      <td width="100"><?php echo(($fila_req["fav_nota"])); ?></td>
                    </tr>

              <?php
                    $j++;
                    if($j==$cant2){$j=0;}
                  }
                    $j = 0;
              ?>
                  </table>
                </div>
                  <hr />
              <?php
              // fin listado de requerimientos  // fin listado de requerimientos  // fin listado de requerimientos
              // fin listado de requerimientos  // fin listado de requerimientos  // fin listado de requerimientos
              ?>
              </td>
          </tr>
          
          
          
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
     <a href="form-seleccionar-cliente-pago.php"><input type="button" value="Volver" class="botones" /></a> &nbsp; &nbsp;
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
