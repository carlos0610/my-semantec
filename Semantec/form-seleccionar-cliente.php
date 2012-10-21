<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");

        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1 and sucursal_id is null order by cli_nombre";
        $resultado1 = mysql_query($sql);
        
        
        /* LISTADO DE MOVIMIENTOS DE CUENTAS CORRIENTES DE CLIENTE*/
        
        $sql0 = "SELECT distinct (ccc_id),c.cli_id,c.cli_nombre,c.sucursal,cc.fav_id,f.cod_factura_venta,sum(o.ord_venta) as 'Monto',f.fav_fecha_pago,u.usu_nombre  
                FROM detalle_corriente_cliente cc, clientes c, factura_venta f, ordenes o,grupo_ordenes g_o,cobros co,usuarios u
                WHERE
                cc.fav_id  	= f.fav_id
                AND f.gru_id  	= g_o.gru_id
                AND g_o.gru_id 	= o.gru_id
                AND o.cli_id	= c.cli_id
                AND c.cli_id  in (SELECT cli_id from clientes where estado = 1)
                AND cc.estado = 1
                AND co.fav_id = cc.fav_id
                AND u.usu_id  = co.usu_id
                group by f.fav_id
                order by fav_fecha_pago desc;";
        
        $listado_movimientos = mysql_query($sql0);
        /*FIN_LISTADO*/
        
        
        
        /* PAGINACIÓN */
        $tamPag=10;       
        include("paginado.php");
        
        $sql = "SELECT distinct (ccc_id),c.cli_id,c.cli_nombre,c.sucursal,cc.fav_id,f.cod_factura_venta,sum(o.ord_venta) as 'Monto',f.fav_fecha_pago,u.usu_nombre  
                FROM detalle_corriente_cliente cc, clientes c, factura_venta f, ordenes o,grupo_ordenes g_o,cobros co,usuarios u
                WHERE
                cc.fav_id  	= f.fav_id
                AND f.gru_id  	= g_o.gru_id
                AND g_o.gru_id 	= o.gru_id
                AND o.cli_id	= c.cli_id
                AND c.cli_id  in (SELECT cli_id from clientes where estado = 1)
                AND cc.estado = 1
                AND co.fav_id = cc.fav_id
                AND u.usu_id  = co.usu_id
                group by f.fav_id
                order by fav_fecha_pago desc";
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
        $resultado = mysql_query($sql);
        //$cantidad = mysql_num_rows($resultado);
        
        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        /*FIN_PAGINACIÓN*/
        
        
        
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
      <form name="frmSeleccionarCliente" action="ver-corriente-clientes.php" method="post" ><table class="listados" cellpadding="5">
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
   <h2>Últimos movimientos de cuentas corrientes</h2>
   <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="80">Cuenta corriente</td>
            <td width="240">Cliente</td> 
            <td width="79">Sucursal</td>
            <td width="79">Factura nro</td> 
            <td width="115">Monto de pago</td>
            <td width="115">Fecha de pago</td>
            <td width="95">Registrado por</td>
            <td width="72">&nbsp;</td>
        </tr>
  <?php
          while($fila = mysql_fetch_array($listado_movimientos)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["ccc_id"]);?></td>
            <td>
            <a href="ver-alta-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>&action=0&origen=externo">
            <?php echo(utf8_encode($fila["cli_nombre"]));?>
            </a>          
            </td>
            <td><?php echo(utf8_encode($fila["sucursal"]));?></td>
            <td align="center"><?php echo $fila["cod_factura_venta"];?></td>          
            <td><?php echo $fila["Monto"];?></td>     
            <td width="115"><?php echo $fila[fav_fecha_pago];?> </td>
            <td width="95" align="center"><?php echo $fila[usu_nombre]; ?></td>
            <td width="72" align="center"><a href="ver-alta-factura.php?fav_id=<?php echo($fila["fav_id"]); ?>&origen=externo"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
        </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
          <tr>
            <td colspan="8" class="pie_lista"><?php 
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
