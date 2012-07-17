<?php
    $titulo = "Formulario de alta de usuarios.";
        include("validar.php");

        include("conexion.php");
        
        $usu_id = $_SESSION["usu_id"];
        $action = $_GET["action"];
        
        /* */
        if ($action == 1){
            $titulo =  "Se ha dado de alta el siguiente usuario";
     
        }
        
        if ($action == 2){
            $titulo =  "Se ha modificado los datos del siguiente usuario";
     
        }

        $sql            = "SELECT r.rol_nombre,u.usu_nombre,u.usu_login,u.usu_clave,u.usu_email FROM usuarios u, roles r 
                           WHERE u.rol_id = r.rol_id
                           AND u.estado = 1
                           AND u.usu_id = $usu_id";
        $resultado      = mysql_query($sql);
        $fila = mysql_fetch_array($resultado);
        
        mysql_close();
        unset($_SESSION["usu_id"]);
        
        

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

      <form action="alta-usuarios.php" method="post" name="frmAltaCli" >
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo(utf8_encode($titulo));?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td>Rol</td>
            <td><?php echo $fila["rol_nombre"]?></td>
            <td></td>
          </tr>
          <tr>
            <td>Nombre (a mostrar)</td>
            <td><?php echo $fila["usu_nombre"]?></td>
            <td></td>
          </tr>
          
          <tr>
            <td>Usuario</td>  
            <td><label><?php echo $fila["usu_login"]?></label></td>
            <td></td>
          </tr>
          <tr>
            <td>Clave</td>
            <td><?php echo $fila["usu_clave"]?></td>
            <td></td>
          </tr>
        
           <tr>
            <td>Email</td>
            <td><label><?php echo $fila["usu_email"]?></label></td>
            <td></td>
          </tr>
          
          <tr>
            <td>&nbsp;</td>
            <td><a href="form-edit-usuarios.php?usu_id=<?php echo($usu_id)?>">
              </a><a href="lista-usuarios.php">
              <input type="button" value="Ir al Listado" class="botones" />
              </a><a href="form-edit-usuarios.php?usu_id=<?php echo($usu_id)?>">
              <input type="button" value="Modificar datos" class="botones" /> 
              </a><a href="form-alta-usuarios.php">
              <input type="button" value="Agregar otro usuario" class="botones" />
              </a>&nbsp; &nbsp;</td>
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