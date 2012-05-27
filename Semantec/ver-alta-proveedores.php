<?php
    include("validar.php");  
    $action = $_GET["action"];
    if($action == 0){
          $titulo = "Datos de Orden de Servicio";
          $prv_id = $_GET["prv_id"];
    }
    else if($action == 1){
      $titulo = "Se ha dado de alta el siguiente proveedor.";
      $prv_id = $_SESSION["prv_id"];
    }
      else{ // 2
      $titulo = "Se han modificado los datod del siguiente proveedor.";
      $prv_id = $_SESSION["prv_id"];
    }
       
        include("conexion.php");
        
        //$query = $_SESSION["query"];
        $tienecuenta = $_SESSION["tienecuenta"];
        unset($_SESSION["prv_id"]);
        
        
        $sql = "SELECT prv_nombre, prv_cuit, iva_tipo.iva_nombre, rubros.rub_nombre, zonas.zon_nombre, prv_direccion, prv_telefono,prv_fax,prv_cel,prv_alternativo,prv_urgencia,prv_web,prv_email,prv_notas FROM proveedores,rubros,iva_tipo,zonas WHERE prv_id = $prv_id and proveedores.iva_id = iva_tipo.iva_id
        and proveedores.rub_id = rubros.rub_id
        and proveedores.zon_id = zonas.zon_id";
        $zonas = mysql_query($sql);
        $fila_zonas = mysql_fetch_array($zonas);
        
        /* Si tiene cuenta obtenemos los datos de su cuenta de la tabla cuentabanco*/
        if ($tienecuenta){
        $sql   =  "SELECT cue.cue_nrobancaria,cut.cut_nombre,cue.cue_cbu FROM cuentabanco_prv cue,cuentatipo cut
                  WHERE prv_id = $prv_id
                  AND cue.cut_id = cut.cut_id";
        $banco = mysql_query($sql);
        $fila_banco = mysql_fetch_array($banco);
        }
        
        mysql_close();
        
        
?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>    
  </head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec-logo.jpg" width="470" height="100" alt="logo" /></a>
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


      <h2>Panel de control</h2>

      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
          <tr>
            <td>Razón Social</td>
            <td><?php echo(utf8_encode($fila_zonas["prv_nombre"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><?php echo($fila_zonas["prv_cuit"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td><?php echo($fila_zonas["iva_nombre"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Rubro</td>
            <td><?php echo($fila_zonas["rub_nombre"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Provincia/Zona</td>
            <td><?php echo($fila_zonas["zon_nombre"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><?php echo(utf8_encode($fila_zonas["prv_direccion"])); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Tel&eacute;fono</td>
            <td><?php echo($fila_zonas["prv_telefono"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Fax</td>
            <td><?php echo($fila_zonas["prv_fax"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Cel</td>
            <td><?php echo($fila_zonas["prv_cel"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Alternativo</td>
            <td><?php echo($fila_zonas["prv_alternativo"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Urgencia</td>
            <td><?php echo($fila_zonas["prv_urgencia"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Web</td>
            <td><?php echo($fila_zonas["prv_web"]); ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Email</td>
            <td><?php echo($fila_zonas["prv_email"]); ?></td>
            <td></td>
          </tr>
          <td>Tiene cuenta bancaria?</td>
            <td><?php if($tienecuenta) 
                        echo "Sí"; 
                       else echo 
                           "No"; ?></td>
            <td></td>
          </tr>
          <?php if($tienecuenta){ 
          echo"<tr>";
          echo"<td>Nro.Cuenta bancaria</td>";
          echo"<td>".$fila_banco["cue_nrobancaria"]."</td>";
          echo"<td></td>";  
          echo"</tr>";
          echo"<tr><td>Tipo de cuenta</td><td>".$fila_banco["cut_nombre"]."</td>
          </tr>
          <tr>
            <td>CBU</td>
            <td>".$fila_banco["cue_cbu"]."</td>";
            "<td></td>
          </tr> 
          ";
              
          }?>
          <tr>
           <td>Notas</td>
           <td><?php echo(utf8_encode($fila_zonas["prv_notas"])); ?></td>
           <td></td>
          </tr>
          
          
          <tr>
            <td>&nbsp;</td>
            <td>
                <a href="lista-proveedores.php"><input type="button" value="Ir al Listado" class="botones" /></a> &nbsp; &nbsp; 
                <a href="form-edit-proveedores.php?prv_id=<?php echo($prv_id)?>"><input type="button" value="Modificar datos" class="botones" /></a> &nbsp; &nbsp; 
                <a href="form-alta-proveedores.php"><input type="button" value="Agregar otro proveedor" class="botones" /></a>
            </td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
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