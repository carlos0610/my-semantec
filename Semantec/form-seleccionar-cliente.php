<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");

        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1 and sucursal_id is null order by cli_nombre";
        $resultado1 = mysql_query($sql);
        
        
        /* LISTADO DE MOVIMIENTOS DE CUENTAS CORRIENTES DE CLIENTE*/
        
        $sql0 = "CREATE TEMPORARY TABLE temp1(
						fav_id int not null,
						fav_codigo varchar(64) not null,
						cod_pago int not null,
					    fav_fecha_pago datetime not null,
						subtotal varchar(32) not null,
						iva 	 varchar(32) not null,
						total    varchar(32) not null,
						cli_nombre varchar(64) not null,
                                                grupo_fac_pago int not null
                                            );";
        
        mysql_query($sql0);
        
        $sql0 = "INSERT INTO temp1
                    SELECT f.fav_id,f.cod_factura_venta,c.id as pago,f.fav_fecha_pago,FORMAT(SUM(dfv.det_fav_precio),2) as subtotal,FORMAT(SUM(dfv.det_fav_precio)*0.21,2) as iva,FORMAT(SUM(dfv.det_fav_precio)+SUM(dfv.det_fav_precio)*0.21,2) as total,0,f.grupo_fac_pago from factura_venta f
                    INNER JOIN cobros c
                    ON c.grupo_fav_id = f.grupo_fac_pago
                    INNER JOIN detalle_factura_venta dfv
                    ON dfv.fav_id = f.fav_id
                    WHERE f.fav_fecha_pago is not null
                    GROUP BY f.cod_factura_venta
                    ORDER BY fav_fecha_pago desc;";
        
        mysql_query($sql0);
        
        $sql0 = "CREATE TEMPORARY TABLE temp2 (
			cli_nombre varchar(64) not null ,
			fav_id integer not null
                                                );";
        
        mysql_query($sql0);
        
        $sql0 = "INSERT INTO temp2
                    select DISTINCT(cli_nombre),fv.fav_id from clientes c
                    INNER JOIN ordenes o
                    ON o.cli_id = c.cli_id
                    INNER JOIN grupo_ordenes go
                    ON go.gru_id = o.gru_id
                    INNER JOIN factura_venta fv
                    ON fv.gru_id = go.gru_id
                    WHERE fv.fav_id in (select fav_id from temp1);";
        
        mysql_query($sql0);
        
        $sql0 = "UPDATE temp1 t1,temp2 t2
                    SET t1.cli_nombre = t2.cli_nombre
                    WHERE t1.fav_id = t2.fav_id;";
        
        mysql_query($sql0);
        
        $sql0 = "select * from temp1";
        $listado_movimientos = mysql_query($sql0);
        
        
        // paso al excel
        $listado_movimientos_arreglo = mysql_query($sql0);
        $array=array();
        while ($row=mysql_fetch_array($listado_movimientos_arreglo)) {
         $array[]=$row;
        }  
        $_SESSION["ccClienteovimientos"]     = $array;
        /*FIN_LISTADO*/
        
        
        
        /* PAGINACIÓN */
        $tamPag=10;       
        include("paginado.php");
        
        $sql = "select * from temp1";
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
        $resultado = mysql_query($sql);
        //$cant_registros = mysql_num_rows($resultado);
        
        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        /*FIN_PAGINACIÓN*/
        
        mysql_query("DROP TABLE temp1");
        mysql_query("DROP TABLE temp2");
        
        
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
	document.getElementById("filtro").action="form-seleccionar-cliente.php?pagina="+pagina;
	document.getElementById("filtro").submit();
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

   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
   
      <h2>Panel de control - Cuenta corrientes de clientes</h2>
	<div id="seleccion" style="height:auto">	
      <form id="filtro" name="filtro" action="form-seleccionar-cliente.php" method="POST">
          <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="149">Seleccione cliente</td>
            <td colspan="3"><div align="right"><a href="index-admin.php"><img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" /></a>
                <a href="index-admin.php"></a> </div></td>
          </tr>
  
          <tr>
            <td><label>
              <select name="comboCliente" id="comboCliente">
                  
                  <?php
          while($fila = mysql_fetch_array($resultado1)){
                    ?>
                    <option value="<?php echo($fila["cli_id"]); ?>"><?php echo(utf8_encode($fila["cli_nombre"])); ?></option>
            <?php
                        }
                ?>
              </select>
            </label></td>
            <td width="473"><label>
              <input type="submit" name="btnVerCuenta" id="btnVerCuenta" value="Ver cuenta corriente">
            </label></td>
            <td colspan="2">&nbsp;</td>
        </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>          
      </table>   
     </form>

     <div class="clear"></div>
     </div>
   <!--end seleccion-->
   <div id="resumen">
   <h2>Últimos movimientos de cuentas corrientes        &nbsp; &nbsp; &nbsp; &nbsp; 
       <a href="exportacion/exportarExcelCC-ClienteMovimiento.php?sql=<?php echo $sql0;?>"> 
                 <img src="images/icon-header-xls.png" alt="Listado Excel" title="Listado Excel" width="32" height="32" border="none" />
             </a></h2>  
   <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="240">Cliente</td> 
            <td width="79">Factura nro</td>
            <td width="60">Nro de pago</td>
            <td width="115">Fecha de pago</td>
            <td width="115">Monto factura</td>
            <td width="95">Ver pago</td>
            
        </tr>
  <?php
          while($fila = mysql_fetch_array($listado_movimientos)){
              
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["cli_nombre"]);?></td>
            <td>
            <a href="ver-alta-factura.php?fav_id=<?php echo($fila["fav_id"]); ?>&origen=externo">
            <?php echo(utf8_encode($fila["fav_codigo"]));?></a>
            </a>          
            </td>
            <td align="center"><?php echo $fila["cod_pago"];?></td>
            <td align="center" width="115"><?php echo mfecha(substr($fila["fav_fecha_pago"], 0, 10));?> </td>
            <td align="center"><?php echo $fila["total"];?></td>     
            <td width="95" align="center"><a href="ver-alta-pago-grupo.php?grupo_fav=<?php echo $fila["grupo_fac_pago"];?>"><img src="images/detalles.png"/></a></td>
                        
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
          <tr>
            <td colspan="6" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>
   
   </div>
   
   
   
   
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
