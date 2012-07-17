<?php
    $titulo = "Listado de proveedores.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
         //ordenes de los headers de las tablas
        $unOrden=$_POST['orden'];
        $contador=$_POST['contador'];
        if($contador=="")
         {$contadorinicial="3";}
        else{$contadorinicial=$contador;}
        //fin
        $elementoBusqueda=$_POST['filtrartxt'];
        if($elementoBusqueda!="")
        {$sqlaux.=" AND prv_nombre like '$elementoBusqueda%' ";}
                        //ordenamiento parte 2
        if($unOrden=="")
        {$unOrden=" prv_nombre ";}
        if($contador%2)
            $unOrdenCompleta.=" $unOrden DESC ";
        else
            $unOrdenCompleta.=" $unOrden ASC ";
        //fin
    $tamPag=20;
    

        $sql = "SELECT prv_id, prv_nombre,prv_cuit,prv_telefono FROM proveedores where estado = 1 and prv_id > 1 ";
                $sql.=$sqlaux;
                $sql0=$sql;
                include("paginado.php");
        $sql .= " ORDER BY $unOrdenCompleta LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
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
      		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
		<script src="js/jquery.uitablefilter.js" type="text/javascript"></script>
      		<script language="javascript">
		$(function() {
		  theTable = $("#latabla");
		  $("#q").keyup(function() {
			$.uiTableFilter(theTable, this.value);
		  });
		});
		</script>
                  <script>
          function transferirFiltros(pagina)
{      
	document.getElementById("filtro").action="lista-proveedores.php?pagina="+pagina;
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
      <h2>Panel de control - Listado de proveedores</h2>
      
<div id="buscador" >     
<form id="filtro" name="filtro" action="lista-proveedores.php" method="POST">
     <table width="100%" border="0">
       <tr>
         <td><div align="right">Nombre: </div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:left" >
             <input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
         <td></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
              <!--- Datos necesarios para el header PARTE 3 -->
       <input name="orden" type="hidden" id="orden" value="<?php echo $unOrden; ?>">
       <input name="contador" type="hidden" id="contador" value="<?php echo $contadorinicial ?>">
       <!--- FIN PARTE 3 -->
     </table>
     <p>&nbsp;</p>
</form>
      </div>  

   		<div id="busqueda"></div>

<table class="listados" cellpadding="5" id="latabla">
          <tr class="titulo">
            <td><a href="#" onClick="agregarOrderBy('prv_nombre')">Nombre</a></td>
            <td width="90"><a href="#" onClick="agregarOrderBy('prv_cuit')">CUIT</a></td>
            <td width="90"><a href="#" onClick="agregarOrderBy('prv_telefono')">Tel&eacute;fono</a></td>
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
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo(utf8_encode($fila["prv_nombre"]));?></td>
            <td><?php echo(verCUIT($fila["prv_cuit"]));?></td>
            <td><?php echo($fila["prv_telefono"]);?></td>
            <td>
                <a href="ver-alta-proveedores.php?prv_id=<?php echo($fila["prv_id"]);?>&action=0">
                  <img src="images/detalles.png" alt="editar" title="ver detalle" width="32" height="32" border="none" />
                </a>
            </td>
            <td>
                <a href="form-edit-proveedores.php?prv_id=<?php echo($fila["prv_id"]);?>">
                  <img src="images/editar.png" alt="editar" title="Modificar proveedor" width="32" height="32" border="none" />
                </a>
            </td>
            <td>
                <a href="#" onClick="eliminarProveedor(<?php echo($fila["prv_id"]);?>,'<?php echo($fila["prv_nombre"]);?>')">
                    <img src="images/eliminar.png" alt="eliminar" title="Eliminar proveedor" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
          </table>   
      <table  class="listados" cellpadding="5">   
          <tr>
            <td colspan="5" class="pie_lista"><?php 
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
