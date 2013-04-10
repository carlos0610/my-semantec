<?php
        $titulo = "Listado de Facturas de compra";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1";
        $resultado1 = mysql_query($sql);
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1";
        $resultado2 = mysql_query($sql);
        //ordenes de los headers de las tablas
        $unOrden=$_POST['orden'];
        $contador=$_POST['contador'];
        if($contador=="")
         {$contadorinicial="3";}
        else{$contadorinicial=$contador;}
        //fin
        //recibo los criterios y construyo la consulta
        $elementoBusqueda=$_POST['filtrartxt'];
        $pagado=$_POST['pagado'];
        $cli_id=$_POST['cli_id'];
        $prv_id=$_POST['prv_id'];
        $sqlaux="";
        if($elementoBusqueda!="")
        {$sqlaux.=" AND f.fco_id like '$elementoBusqueda%' ";}
        if($pagado!="")
        {$sqlaux.=" AND $pagado (f.fav_fecha_pago) ";}
        if($cli_id!="")
        {$sqlaux.=" AND c.cli_id  = $cli_id ";}
        if($prv_id!="")
        {$sqlaux.=" AND p.prv_id  = $prv_id ";}
                        //ordenamiento parte 2
        if($unOrden=="")
        {$unOrden="  f.fco_fecha ";}
        if($contador%2)
            $unOrdenCompleta.=" $unOrden ASC ";
        else
            $unOrdenCompleta.=" $unOrden DESC ";
        //fin
    $tamPag=10;
    
    
        $sql = "SELECT distinct f.fco_id,f.fco_fecha ,f.files_id,f.fco_subtotal, f.fco_nota, p.prv_nombre  
                FROM factura_compra f, detalle_factura_compra df, proveedores p
                WHERE  f.estado = 1
                AND df.fco_id= f.fco_id
                AND f.prv_id= p.prv_id
                ";
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
      <script>
          function transferirFiltros(pagina)
{      
	document.getElementById("filtro").action="lista-facturas-compra.php?pagina="+pagina;
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
          
          
    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <h2>Panel de control - <?php echo $titulo ?></h2>

     <div id="buscador" >     
<form id="filtro" name="filtro" action="lista-facturas-compra.php" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="14%"><div align="right">Poveedor</div></td>
         <td width="34%"><select name="prv_id" id="prv_id" class="campos" <?php if($prv_id==""){echo ("disabled");}?>>
    <?php
          while($fila2 = mysql_fetch_array($resultado2)){
    ?>
                    <option value="<?php echo($fila2["prv_id"]); ?>"<?php if($prv_id==$fila2["prv_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila2["prv_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
         </td>       
         <td width="52%"><input name="chkprv" type="checkbox" id="chkprv" onClick="habilitarFiltros('chkprv','prv_id')" <?php if($prv_id!=""){echo ("checked");}?>></td>
       </tr>

       <tr>
         <td><div align="right">N° Factura </div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right" ></td>
         <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
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
            <td width="70"><a href="#" onClick="agregarOrderBy('fco_id')">Factura Nro</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('fco_fecha')">Fecha de emisión</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('prv_nombre')">Proveedor</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('fco_nota')">Nota</a></td>
            <td width="32">Archivo</td>
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
               $numeroDeTablaDesplegable++;   
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["fco_id"]);?></td>
            <td><?php echo(tfecha($fila["fco_fecha"]));?></td>
            <td><?php echo(utf8_encode($fila["prv_nombre"]));?></td>
            <td><?php echo(utf8_encode($fila["fco_nota"]));?></td>
                <?php //echo(utf8_encode($fila_req["files_id"]));
                      $id = $fila["files_id"] ?>
            <td width="60" align="center"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png title=Descargar /></a>";?></td>
            
            <td width="32" align="center"><a href="ver-alta-factura-compra.php?action=1&fav_id=<?php echo($fila["fco_id"]); ?>"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>  
            
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
                       <td width="100"></td>
                      <td width="600">Descripci&oacute;n</td>                      
                      <td width="100">Monto</td>
                    </tr>
              <?php
                $orden = $fila["fco_id"];
                $sql_req = "SELECT  fco_id , det_fco_descripcion , det_fco_preciounitario
                              FROM detalle_factura_compra df
                              WHERE df.fco_id  = $orden
                              ORDER BY det_fco_id  DESC";
                $result_req = mysql_query($sql_req);
                while($fila_req = mysql_fetch_array($result_req)){
                  //  echo("<hr />". $sql_req ."<hr />");
          ?>
                    <tr class="lista" bgcolor="<?php echo($colores2[$j]);?>">
                      <td width="0"><?php ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["det_fco_descripcion"])); ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["det_fco_preciounitario"])); ?></td>
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
