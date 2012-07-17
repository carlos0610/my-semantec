<?php
    $titulo = "Formulario de alta de usuarios.";
        include("validar.php");

        include("conexion.php");
        
        $usu_id = $_GET["usu_id"];
        
        /* ROLES */
        $sql    = "SELECT rol_id,rol_nombre FROM roles";
        $roles    = mysql_query($sql);
        
        
        /* DATOS DE USUARIO*/
        $sql            = "SELECT r.rol_id,r.rol_nombre,u.usu_nombre,u.usu_login,u.usu_clave,u.usu_email FROM usuarios u, roles r 
                           WHERE u.rol_id = r.rol_id
                           AND u.usu_id = $usu_id";
        $resultado      = mysql_query($sql);
        $fila_usuario = mysql_fetch_array($resultado);
        
        

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

      <form action="edit-usuarios.php" method="post" name="frmEditUsuarios" >
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo(utf8_encode($titulo));?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td>Rol</td>
            <td><label>
              <select name="comboPerfil" class="campos" id="comboPerfil">
                <?php while($fila = mysql_fetch_array($roles)){?>
                <option value="<?php echo $fila["rol_id"]?>" <?php if($fila_usuario["rol_id"] == $fila["rol_id"]){echo(" selected=\"selected\"");} ?>>
                  <?php  echo $fila["rol_nombre"]?>
                </option>
                <?php }?>
              </select>
            </label></td>
            <td></td>
          </tr>
          <tr>
            <td>Nombre (a mostrar)</td>
            <td><input name="txtNombre" type="text" class="campos" id="txtNombre" value="<?php echo $fila_usuario["usu_nombre"]?>" required></td>
            <td></td>
          </tr>
          
          <tr>
            <td>Usuario</td>  
            <td><label>
            <input name="txtUsuario" type="text" class="campos" id="txtUsuario" value="<?php echo $fila_usuario["usu_login"]?>" maxlength="20" required>
            </label></td>
            <td></td>
          </tr>
          <tr>
            <td>Nueva clave</td>
            <td><input name="txtClave" type="password" class="campos" id="txtClave" maxlength="20" disabled>
              <label>
                  <input type="checkbox" name="chkCambiarClave" id="chkCambiarClave" onclick="habilitarFiltros('chkCambiarClave', 'txtClave')">
            </label></td>
            <td></td>
          </tr>
        
           <tr>
            <td>Email</td>
            <td><label>
              <input name="txtEmail" type="text" class="campos" id="txtEmail" value="<?php echo $fila_usuario["usu_email"]?>">
            </label></td>
            <td></td>
          </tr>
          
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar usuario" class="botones" style="visibility:visible" id="botonAgregar"  onclick=""/>
                <input type="hidden" value="<?php echo($usu_id); ?>" name="usu_id" id="usu_id" />            </td>
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