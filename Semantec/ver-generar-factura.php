<?php
    include("validar.php");

    /* $action = $_GET["action"]; // 0
    if($action == 0){
          $titulo = "Datos de Orden de Servicio";
          $ord_id = $_GET["ord_id"];
    }
    else if($action == 1){
          $titulo = "Se ha dado de alta la siguiente Orden de Servicio";
          $ord_id = $_SESSION["ord_id"];
    }
    else{  2
          $titulo = "Se han modificado los datos del la siguiente Orden de Servicio"; 
          $ord_id = $_SESSION["ord_id"];
    }
    
     
     */
        $ord_id = $_GET["ord_id"];
        include("funciones.php");
        include("conexion.php");
        
        $sql = "SELECT c.cli_nombre as Cliente,c.cli_direccion as Dirección,z.zon_nombre,i.iva_nombre,c.cli_cuit 
                FROM ordenes o,clientes c,zonas z,iva_tipo i
                    WHERE 
                    o.cli_id = c.cli_id
                    and o.ord_id = $ord_id
                    and c.zon_id = z.zon_id
                    and c.iva_id = i.iva_id";
        
       $cliente = mysql_query($sql); 
       $fila_datos_cliente = mysql_fetch_array($cliente); 
       mysql_close();
        //$nro = mysql_num_rows($datos_cliente);
        
        

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.datepick.js"></script>
  <script type="text/javascript" src="js/jquery.datepick-es.js"></script>
  <script type="text/javascript">
  $(function() {
      $('#ord_plazo').datepick();
  });
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
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
	 <table width="100%" border="0">
          <tr>
            <td width="15%">Señores:</td>
            <td colspan="3"><?php echo $sql?></td>
       </tr>
          <tr>
            <td>Domiclio:</td>
            <td width="8%"><?php echo $fila_datos_cliente["cli_direccion"]?></td>
            <td width="6%">Localidad:</td>
            <td width="71%"><?php echo $fila_datos_cliente["zon_nombre"]?></td>
          </tr>
          <tr>
            <td>IVA:</td>
            <td><?php echo $fila_datos_cliente["iva_nombre"]?></td>
            <td>Cuit:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Condiciones de venta:</td>
            <td>&nbsp;</td>
            <td>Remito:</td>
            <td>
              <input type="text" name="txtRemito" id="txtRemito">
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
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