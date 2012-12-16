<?php
    $titulo = "Listado de Ordenes de Servicio.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        include("Modelo/modeloFiles.php");
        
        //Clientes
         $sql = "SELECT sucursal_id,sucursal,cli_id,cli_nombre,p.nombre as provincia 
           FROM clientes,ubicacion u,provincias p, partidos pa,localidades l
           WHERE 
 	   clientes.ubicacion_id = u.id
           AND u.provincias_id = p.id
           AND u.partidos_id = pa.id
           AND u.localidades_id = l.id
           AND clientes.estado = 1
           AND sucursal_id is null
           ORDER BY cli_nombre,provincia";
        $resultado1 = mysql_query($sql);
           //borro las session
            unset($_SESSION["filtrartxt"]);
             unset(     $_SESSION["prv_id"]);
            unset(      $_SESSION["est_id"]);
             unset(     $_SESSION["suc_id"]);
            unset(      $_SESSION["cli_id"]);
           unset(       $_SESSION["orden"]);
           unset(       $_SESSION["pagina"]);
                 // estados
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados";
        $resultado3 = mysql_query($sql);
                //proovedores
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1 ORDER BY prv_nombre ASC ";
        $resultado2 = mysql_query($sql);
                //recibo los criterios y construyo la consulta
        $elementoBusqueda=$_POST['filtrartxt'];
        $proveedorFiltro=$_POST['prv_id'];
        $estado_id=$_POST['est_id'];
               //filtros nuevos Cliente Sucursal
        $cli_id = $_POST['suc_id'];
        $cli_idMaestro = $_POST['cli_id'];
        if($cli_idMaestro=="")
         {$cli_idMaestro="0";}
        $sql = "SELECT cli_id, cli_nombre,sucursal 
                FROM clientes
           WHERE sucursal_id =$cli_idMaestro
           AND clientes.estado = 1
           ORDER BY cli_nombre,sucursal";
        $resultadoSucursales = mysql_query($sql);
   
                //ordenes de los headers de las tablas PARTE 1
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
         if($cli_id!="")
            if($cli_id=="todasLasSucursales")
                {$sqlaux.=" AND c.sucursal_id = $cli_idMaestro ";}
            else
                {$sqlaux.=" AND o.cli_id = $cli_id ";}
                
                /* Si viene desde la cuenta corriente */
                $action = $_GET["action"];
                if ($action == 1){
                    $codigo = $_GET["orden"];
                    $sqlaux=" AND ord_codigo like '$codigo'";
                }
                
                /* Si viene desde delete-adelantos */
                if ($action == 2){
                    $codigo = $_GET["orden"];
                    $sqlaux=" AND ord_id = $codigo";
                }
                
                
                
                //ordenamiento parte 2
        if($unOrden=="")
             {$unOrden=" o.ord_alta ";}
        if($unOrden=="ord_codigo") // valores para ordenar de forma numerica expresada en varchar 
             {$unOrdenCompleta=" ABS ";}
        if($contador%2)
            $unOrdenCompleta.=" ( $unOrden ) ASC ";
        else
            $unOrdenCompleta.=" ( $unOrden ) DESC ";
        //fin
   $tamPag=10;       
        
       
        $sql = "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo, ord_costo, ord_venta,c.sucursal
                  FROM ordenes o, clientes c, estados e, proveedores p
                  WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id
                    AND o.estado = 1 ";
                    $sql.=$sqlaux;
                    $sql0=$sql;
                    include("paginado.php");
                    
                $sql .= " ORDER BY  $unOrdenCompleta LIMIT ".$limitInf.",".$tamPag; 
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
      <script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="lista-req-ordenes.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>
  <script type="text/javascript" src="js/select_dependientes_cliente_sucursal.js"></script>
  </head>
  
  <body  <?php if($action == 2){?>onload="confirmacionAdelanto()"<? } ?>>
	
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
      <h2>Panel de control - Listado de Órdenes de Servicio</h2>
      <div id="buscador" >     
    <form id="filtro" name="filtro" action="lista-req-ordenes.php" method="POST" >
     <table width="100%" border="0">
       <tr>
         <td width="15%">&nbsp;</td>
         <td width="34%">&nbsp;</td>
         <td width="51%">&nbsp;</td>
       </tr>
       <tr>
         <td><div align="right">Proveedor</div></td>
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
         <td><div align="right">Cliente</div></td>
         <td><select name="cli_id" id="cli_id" class="campos" required onChange="habilitarCombo2('cli_id','suc_id')" <?php if($cli_id==""){echo ("disabled");}?>>
          <option value='0'>Seleccione</option>
          
    
           <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_idMaestro==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
           <?php
          }
    ?>
         </select></td>
         <td><label>
           <input type="checkbox" name="chkCliente" id="chkCliente" onClick="habilitarFiltrosClienteSucursal('chkCliente','cli_id','suc_id')" <?php if($cli_idMaestro!="0"){echo ("checked");}?>>
         </label></td>
       </tr>
       <tr>
         <td><div align="right">Sucursal</div></td>
         <td><select name="suc_id" id="suc_id" class="campos" required <?php if($cli_id==""){echo ("disabled");}?>>
           <option value='todasLasSucursales'>Todas las Sucursales</option>
           
           
           
           <?php
          while($fila = mysql_fetch_array($resultadoSucursales)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_id==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
           <?php
          }
    ?>
           
           
           
           </select></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td><div align="right">N° Orden</div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right;" ></td>
         <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
       </tr>
        <!--- Datos necesarios para el header PARTE 3 -->
       <input name="orden" type="hidden" id="orden" value="<?php echo $unOrden; ?>">
       <input name="contador" type="hidden" id="contador" value="<?php echo $contadorinicial ?>">
       <!--- FIN PARTE 3 -->
     </table>
     <p>&nbsp;</p>
     </form>
      </div>  
      
      <table class="lista" cellpadding="5">
          <tr class="titulo">
            <td width="70"><a href="#" onClick="agregarOrderBy('ord_codigo')"><span style="color: white">C&oacute;digo</span></a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('cli_nombre')"><span style="color: white">Cliente</span></a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('ord_alta')"><span style="color: white">Fecha Alta</span></a></td>
            <td>Descripci&oacute;n</td>
            <td width="100"><a href="#" onClick="agregarOrderBy('prv_nombre')"><span style="color: white">Proveedor</span></a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('est_nombre')"><span style="color: white">Estado</span></a></td>
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
              <td><a href="#"    onClick="transferirFiltrosAOtroForm('filtro','form-edit-ordenes-unificado.php?ord_id=<?php echo($fila["ord_id"]); ?>&action=1&pagina=<?php echo $pagina ?>')">
                  <?php echo($fila["ord_codigo"]);?></a></td>
            <td><?php echo(utf8_encode($fila["cli_nombre"]));?>(<?php echo(utf8_encode($fila["sucursal"]))?>)</td>
            <td><?php echo(tfecha($fila["ord_alta"]));?></td>
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
                      <td width="100">Adelanto</td>
                      <td width="100">Usuario </td>
                      <td width="100">Archivo</td>
                      <td width="100">Portal</td>
                      <td width="100">Cancelar</td>
                      
                    </tr>
              <?php
                $orden = $fila["ord_id"];
                $sql_req = "SELECT ord_det_id, ord_id, ord_det_descripcion, ord_det_fecha, usu_nombre, ord_det_monto ,nombre_estado,files_id
                              FROM ordenes_detalle
                              WHERE ord_id = $orden
                              AND   estado = 1 
                              ORDER BY ord_det_fecha DESC"; echo $sql_req;
                $result_req = mysql_query($sql_req);
                while($fila_req = mysql_fetch_array($result_req)){

                  //  echo("<hr />". $sql_req ."<hr />");
          ?>
                    <tr class="lista" bgcolor="<?php echo($colores2[$j]);?>">
                      <td width="600"><?php echo(utf8_encode($fila_req["ord_det_descripcion"])); ?></td>
                      <td width="100"><?php echo(tfecha($fila_req["ord_det_fecha"])); ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["nombre_estado"])); ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["ord_det_monto"])); ?></td>
                      <td width="100"><?php echo(utf8_encode($fila_req["usu_nombre"])); ?></td>
                      <?php //echo(utf8_encode($fila_req["files_id"]));
                         $id = $fila_req["files_id"]; 
                         $ord_id = $fila["ord_id"];
                         $ord_det_id = $fila_req["ord_det_id"];?>                      
                      <td width="100" style="text-align: center;"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png /></a>";?></td>
                      <td width="100"><?php echo(getFilePortalWithId($id)) ?></td>
                      <td width="100" style="text-align: center;"><?php if($fila_req["ord_det_monto"]>0){?><a href="#"><img src="images/adelanto_cancel.png" alt="Cancelar adelanto" onclick="cancelarAdelanto('<?echo $ord_id?>', '<?echo $ord_det_id?>')" /></a><?}?></td>
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
