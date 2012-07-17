<?php
    $titulo = "Se han modificado las siguientes alertas.";
        include("validar.php");

        include("conexion.php");
        
        
        $sql = "SELECT par_id,valor FROM parametros";
        $resultado = mysql_query($sql);
        
        
        
        
        

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>   
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
      function lookup(cli_rubro) {
        if(cli_rubro.length == 0) {
          $('#suggestions').hide();
        } else {
          $.post("rpc-rubro.php", {queryString: ""+cli_rubro+""}, function(data){
            if(data.length >0) {
              $('#suggestions').show();
              $('#autoSuggestionsList').html(data);
            }
          });
        }
      }
      
      function fill(thisValue) {
        $('#cli_rubro').val(thisValue);
        setTimeout("$('#suggestions').hide();", 200);
      }
    </script>
    <link rel="stylesheet" href="css/suggestionsBox.css" type="text/css" />
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


      <h2>Panel de control</h2>

      <form action="form-configuracion.php" method="post" name="frmEditAlertas" >
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo(utf8_encode($titulo));?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <?php $fila = mysql_fetch_array($resultado);?>
          
          <tr>
            <td width="176">Alertarme de respuesta de proveedor :</td>
            <td width="394"><label>
            <?php echo $fila["valor"]?> día/s antes</label></td>
            <td></td>
          </tr>
          <?php $fila = mysql_fetch_array($resultado);?>
          
          <tr>
            <td>Alertarme de respuesta de vencimiento de orden :</td>  
            <td><label>
<?php echo $fila["valor"]?>            día/s antes</label></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp; &nbsp; 
            <input type="submit" value="Regresar" class="botones" style="visibility:visible" id="botonAgregar"  />            </td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
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