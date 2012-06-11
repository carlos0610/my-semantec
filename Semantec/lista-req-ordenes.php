<?php
    $titulo = "Listado de Ordenes de Servicio.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
/* CALCULO PAGINADO */  ###############################################################################
    $sql0 =  "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo, ord_costo, ord_venta
                  FROM ordenes o, clientes c, estados e, proveedores p
                  WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id
                    AND o.estado = 1 
                    ORDER BY o.ord_alta DESC ";
    $tamPag=10;
    
    include("paginado.php");        
        $sql = "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo, ord_costo, ord_venta
                  FROM ordenes o, clientes c, estados e, proveedores p
                  WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id
                    AND o.estado = 1 
                    ORDER BY o.ord_alta DESC ";
                    
                $sql .= " LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        $j = 0;
        $colores2 = array("#fefefe","#efefef");
        $cant2 = count($colores2);
        $numeroDeTablaDesplegable=0;
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
      <h2>Panel de control - Listado de Órdenes de Servicio</h2>

      <table class="sortable" cellpadding="5">
          <tr class="titulo">
            <td width="70">C&oacute;digo</td>
            <td width="100">Cliente</td>
            <td>Descripci&oacute;n</td>
            <td width="100">Proveedor</td>
            <td width="100">Estado</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
             $numeroDeTablaDesplegable++;   
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
              <td><a href=form-edit-ordenes.php?ord_id=<?php echo($fila["ord_id"]);?>><?php echo($fila["ord_codigo"]);?></a></td>
            <td><?php echo(utf8_encode($fila["cli_nombre"]));?></td>
            <td><?php echo(nl2br(utf8_encode($fila["ord_descripcion"])));?></td>
            <td><?php echo(utf8_encode($fila["prv_nombre"]));?></td>
            <td>
                  <img src="images/estado.png" alt="estado" style="background-color:<?php echo($fila["est_color"]);?>">
                  <?php echo(utf8_encode($fila["est_nombre"]));?>
            </td>
            <td>&nbsp;</td>
          </tr>
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
                      <td width="600">Descripci&oacute;n</td>
                      <td width="100">Fecha</td>
                      <td width="100">Estado</td>
                      <td width="100">Usuario </td>
                      <td witdh="100">Archivo</td>
                    </tr>
              <?php
                $orden = $fila["ord_id"];
                $sql_req = "SELECT ord_det_id, ord_id, ord_det_descripcion, ord_det_fecha, usu_nombre, ord_det_monto ,nombre_estado,files_id
                              FROM ordenes_detalle
                              WHERE ord_id = $orden
                              AND   estado = 1 
                              ORDER BY ord_det_fecha DESC";
                $result_req = mysql_query($sql_req);
                while($fila_req = mysql_fetch_array($result_req)){

                  //  echo("<hr />". $sql_req ."<hr />");
          ?>
                    <tr class="lista" bgcolor="<?php echo($colores2[$j]);?>">
                      <td width="600"><?php echo(utf8_encode($fila_req["ord_det_descripcion"])); ?></td>
                      <td width="100"><?php echo(tfecha($fila_req["ord_det_fecha"])); ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["nombre_estado"])); ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["usu_nombre"])); ?></td>
                      <?php //echo(utf8_encode($fila_req["files_id"]));
                         $id = $fila_req["files_id"] ?>
                      <td width="100"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png /></a>";?></td>
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
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
      </table>
      <table  class="listados" cellpadding="5">
          <tr>
            <td colspan="6" class="pie_lista"><?php 
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
