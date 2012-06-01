<?php
    $titulo = "Seleccion proveedor";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
        
        $action = $_GET["action"];

        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado = 1 and prv_id <> 1";
        $resultado1 = mysql_query($sql);
        
        if ($action == 1){
           $tituloPanel = "Cuenta corriente de clientes";
           $botonValue  = "Ver cuenta corriente";
           
        }else{
           $tituloPanel = "Registrar factura de compra";
           $botonValue  = "Registrar factura de compra";
        }
        
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
      <h2>Panel de control - <?php echo $tituloPanel?></h2>

      <form name="frmSeleccionarProveedor" action="<?php if ($action == 1) echo "ver-corriente-proveedor.php"; else echo "form-alta-compra.php";?>" method="post" ><table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="149">Seleccione proveedor</td>
            <td colspan="3"><div align="right"><a href="index-admin.php"><img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" /></a>
                <a href="index-admin.php"></a> </div></td>
          </tr>
  
          <tr>
            <td><label>
              <select name="comboProveedor" id="comboProveedor">
                  
                  <?php
          while($fila = mysql_fetch_array($resultado1)){
                    ?>
                    <option value="<?php echo($fila["prv_id"]); ?>"><?php echo($fila["prv_nombre"]); ?></option>
            <?php
                        }
                ?>
              </select>
            </label></td>
            <td width="473"><label>
              <input type="submit" name="btnVerCuenta" id="btnVerCuenta" value="<?php echo $botonValue ?>">
            </label></td>
            <td colspan="2">&nbsp;</td>
        </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
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
