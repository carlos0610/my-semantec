<?php

    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        include("Modelo/modeloUsuarios.php");
        //ordenes de los headers de las tablas
        $unOrden=$_POST['orden'];
        $contador=$_POST['contador'];
        if($contador=="")
         {$contadorinicial="3";}
        else{$contadorinicial=$contador;}
        //fin
        
        $elementoBusqueda=$_POST['filtrartxt'];
        if($elementoBusqueda!="")
        {$sqlaux.=" AND nombre like '$elementoBusqueda%' ";}
        
                //ordenamiento parte 2
        if($unOrden=="")
        {$unOrden=" nombre ";}
        if($contador%2)
            $unOrdenCompleta.=" $unOrden DESC ";
        else
            $unOrdenCompleta.=" $unOrden ASC ";
        //fin
    
    $tamPag=20;
          
        $sql = "SELECT `id`, `nombre`, `fecha_alta`, `usu_id`, `estado` 
                FROM `abonos` 
                WHERE   estado = 1  ";
                $sql.=$sqlaux;
                $sql0=$sql;
                include("paginado.php");
                
                $sql .= "ORDER BY $unOrdenCompleta  LIMIT ".$limitInf.",".$tamPag; 
               
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
  <script>
          function transferirFiltros(pagina)
{      
	document.getElementById("filtro").action="lista-abonos.php?pagina="+pagina;
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
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <h2>Panel de control - Listado de clientes</h2>

      
      
           <div id="buscador" >     
<form id="filtro" name="filtro" action="lista-abonos.php" method="POST">
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
      
      
      
      
      
      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td><a href="#" onClick="agregarOrderBy('nombre')">Nombre</a></td>
            <td width="90"><a href="#" onClick="agregarOrderBy('fecha_alta')">Fecha Alta</a></td>
            <td width="120"><a href="#" onClick="agregarOrderBy('usu_id')">Creado por</a></td>
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
              <td><?php echo(utf8_encode($fila["nombre"])); ?> </td>
            <td><?php echo(tfecha($fila["fecha_alta"]));?></td>
            <td><?php  echo getUsuarioNombreWithId($fila["usu_id"]);?></td>
            <td>
                <a href="ver-abono.php?idAbono=<?php echo($fila["id"]);?>&origen=listado">
                  <img src="images/detalles.png" alt="editar" title="ver detalle" width="32" height="32" border="none" />
                </a>  
            </td>       
            <td>
                <a href="ver-abono.php?idAbono=<?php echo($fila["id"]);?>&modificar=1&origen=listado">
                  <img src="images/editar.png" alt="editar" title="Modificar cliente" width="32" height="32" border="none" />
                </a>  
              </td>
            <td><a href="#" onclick="eliminarAbono(<?php echo($fila["id"]);?>,'<?php echo($fila["nombre"]);?>')">
                    <img src="images/eliminar.png" alt="eliminar" title="Eliminar cliente" width="32" height="32" border="none" />
                </a></td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
      </table>
      <table class="listados" cellpadding="5">
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
