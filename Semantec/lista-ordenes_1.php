<?php
    $titulo = "Listado de Ordenes de Servicio.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
         $tamPag=15;    
         // estados
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados";
        $resultado3 = mysql_query($sql);
        //proovedores
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1";
        $resultado2 = mysql_query($sql);
        //recibo los criterios y construyo la consulta
        $elementoBusqueda=$_POST['filtrartxt'];
        $proveedorFiltro=$_POST['prv_id'];
        $estado_id=$_POST['est_id'];
        
        //ordenes de los headers de las tablas
        $unOrden=$_POST['orden'];
        $contador=$_POST['contador'];
        if($contador=="")
         {$contadorinicial="3";}
        else{$contadorinicial=$contador;}
        //fin
        
        $sqlaux="";
        if($elementoBusqueda!="")
        {$sqlaux.="AND ord_codigo like '$elementoBusqueda%' ";}
        if($proveedorFiltro!="")
        {$sqlaux.="AND o.prv_id = $proveedorFiltro ";}
        if($estado_id!="")
        {$sqlaux.="AND o.est_id = $estado_id ";}
        
        //ordenamiento
        if($unOrden=="")
        {$unOrden=" o.ord_alta ";}
        if($contador%2)
            $unOrdenCompleta.=" $unOrden DESC ";
        else
            $unOrdenCompleta.=" $unOrden ASC ";
/*if($_POST['est_id'])
    {  */

$sql="SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo, ord_costo, ord_venta
                  FROM ordenes o, clientes c, estados e, proveedores p
                  WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1 ";
                    $sql.=$sqlaux;
               //     AND ord_codigo like '$elementoBusqueda%' 
               //     AND o.est_id = $estado_id
               //     AND o.prv_id = $proveedorFiltro
               //     ORDER BY o.ord_alta DESC ";
                    $sql0=$sql;
                    include("paginado.php");
                    $sql .= " ORDER BY $unOrdenCompleta   LIMIT ".$limitInf.",".$tamPag;  

                    
$resultado=mysql_query($sql);
 $cantidad = mysql_num_rows($resultado);   


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
      <script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="lista-ordenes.php?pagina="+pagina;
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


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <h2>Panel de control - Listado de Ordenes de Servicio</h2>

      
      <div id="buscador" >     
<form id="filtro" name="filtro" action="lista-ordenes.php<?php /*echo $PHP_SELF; */?>" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="15%">&nbsp;</td>
         <td width="34%">&nbsp;</td>
         <td width="51%">&nbsp;</td>
       </tr>
       <tr>
         <td><div align="right">Proovedor</div></td>
         <td><select name="prv_id" id="prv_id" class="campos" <?php if($proveedorFiltro==""){echo ("disabled");}?>>
           <?php while($fila2 = mysql_fetch_array($resultado2)){ ?>
           <option value="<?php echo($fila2["prv_id"]); ?>"<?php if($proveedorFiltro==$fila2["prv_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila2["prv_nombre"])); ?></option>
           <?php }?>
         </select></td>
         <td><input name="chkProovedor" type="checkbox" id="chkProovedor" value="si" onClick="habilitarFiltros('chkProovedor','prv_id')"  <?php if($proveedorFiltro!=""){echo ("checked");}?>></td>
       </tr>
       <tr>
         <td><div align="right">Estado</div></td>
         <td><select name="est_id" id="est_id" class="campos" value="si" <?php if($estado_id==""){echo ("disabled");}?>>
           <?php  while($fila3 = mysql_fetch_array($resultado3)){  ?>
           <option style="background-color:<?php echo($fila3["est_color"]); ?>" value="<?php echo($fila3["est_id"]); ?>"<?php if($estado_id==$fila3["est_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila3["est_nombre"])); ?></option>
           <?php    } ?>
         </select></td>
         <td><input name="chkEstado" type="checkbox" id="chkEstado" onClick="habilitarFiltros('chkEstado','est_id')" <?php if($estado_id!=""){echo ("checked");}?>></td>
       </tr>
       <tr>
         <td><div align="right">N° Orden </div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right" ></td>
         <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
       </tr> 
       
       <!--- Datos necesarios para el header  -->
       <input name="orden" type="hidden" id="orden" value="<?php echo $unOrden; ?>">
       <input name="contador" type="hidden" id="contador" value="<?php echo $contadorinicial ?>">
       
     </table>
     <p>&nbsp;</p>
     </form>
      </div>    
   
      <table class="listado" cellpadding="5">
          <tr class="titulo">
            <td width="70"><a href="#" onClick="agregarOrderBy('ord_codigo')">C&oacute;digo</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('ord_alta')">Fecha</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('cli_nombre')">Cliente</a></td>
            <td><a href="#" onClick="agregarOrderBy('ord_descripcion')">Descripci&oacute;n</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('prv_nombre')">Proveedor</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('est_nombre')">Estado</a></td>
            <td width="32">&nbsp;</td>            
            <td width="32">&nbsp;</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["ord_codigo"]);?></td>
            <td><?php echo(tfecha($fila["ord_alta"]));?></td>
            <td><?php echo(utf8_encode($fila["cli_nombre"]));?></td>
            <td><?php echo(nl2br(utf8_encode($fila["ord_descripcion"])));?></td>
            <td><?php echo(utf8_encode($fila["prv_nombre"]));?></td>
            <td>
                  <img src="images/estado.png" alt="estado" style="background-color:<?php echo($fila["est_color"]);?>">
                  <?php echo(utf8_encode($fila["est_nombre"]));?>
            </td>
            <td width="32"><a href="ver-alta-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>&action=0"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
            <td><a href="form-edit-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>"><img src="images/editar.png" alt="editar" title="Modificar orden" width="32" height="32" border="none" /></a></td>
            <td><a href="#" onClick="eliminarOrden(<?php echo($fila["ord_id"]);?>,'<?php echo($fila["ord_codigo"]);?>')">
                <img src="images/eliminar.png" alt="eliminar" title="Eliminar orden" width="32" height="32" border="none" /></a></td>
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
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
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
