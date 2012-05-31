<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");

        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1";
        $resultado1 = mysql_query($sql);
        
        
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
      <h2>Panel de control - Cuenta corrientes de clientes</h2>

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
                    <option value="<?php echo($fila["cli_id"]); ?>"><?php echo($fila["cli_nombre"]); ?></option>
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
