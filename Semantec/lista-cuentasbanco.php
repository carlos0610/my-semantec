<?php
    $titulo = "Listado de clientes.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
/* CALCULO PAGINADO */  ###############################################################################
    $sql0="SELECT `id`, `cut_id`, `ban_id`, `nro`, `nombre`, `cbu`, `estado` 
           FROM `cuentabanco` 
           WHERE `estado` =1";
    
    $tamPag=20;
    
    include("paginado.php");        
        $sql = "SELECT `id`, `cut_id`, `ban_id`, `nro`, `nombre`, `cbu`, `estado` 
                FROM `cuentabanco` 
                WHERE `estado` =1
                ";
                $sql .= " ORDER BY nombre  LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        $sql = "select cut_id,cut_nombre FROM cuentatipo ORDER BY cut_nombre ";
        $tipocuenta = mysql_query($sql);
        
        $sql = "SELECT ban_id , ban_nombre FROM `banco` WHERE estado = 1 ORDER BY ban_nombre ";
        $bancos = mysql_query($sql);
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
    <a href="#" id="logo"><img src="images/semantec.png" width="470" height="100" alt="logo" /></a>
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
      <h2>Panel de Configuración Cuenta Banco</h2>
      
     <form action="alta-cuentabanco.php" method="post">
      <table class="forms" cellpadding="1">
          <tr>
             <td><b>Nueva Cuenta Banco</b></td> 
          </tr>
          <tr>
            <td>Tipo Cuenta</td>
            <td>
          <select name="cut_id" id="cut_id" class="campos" required >
    <?php
          while($fila = mysql_fetch_array($tipocuenta)){
    ?>
                    <option value="<?php echo($fila["cut_id"]); ?>"><?php echo(utf8_encode($fila["cut_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
                  </td>  
            <td></td>
          </tr>
          <tr>
            <td>Banco</td>
            <td>
          <select name="ban_id" id="ban_id" class="campos" required >
    <?php
          while($fila = mysql_fetch_array($bancos)){
    ?>
                    <option value="<?php echo($fila["ban_id"]); ?>"> <?php echo(utf8_encode($fila["ban_nombre"])); ?> </option>
    <?php
          }
    ?>
                </select>
                  </td>   
            <td></td>
          </tr>
          
          <tr>
            <td>Nombre</td>
            <td><input  type="text" class="campos" id="nombre" name="nombre" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Numero</td>
            <td>
                <input type="text" style="text-align:right" class="campos" id="nro" name="nro" required/>
            </td>
            <td></td>
          </tr>
           <tr>
            <td>CBU</td>
            <td>
                <input type="text" style="text-align:right" class="campos" id="cue_cbu" name="cue_cbu" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Agregar Banco" class="botones" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td >&nbsp;</td>
          </tr>
      </table> 

      </form>  
      
      
      
      
      
      
      
      
      
      <table class="sortable" cellpadding="3">
          <tr class="titulo">
            <td>Nombre</td>
            <td width="90">Banco</td>
            <td width="120">Tipo Cuenta</td>
            <td width="120">Número</td>
            <td width="120">CBU</td>
            <td width="32">&nbsp;</td>
            <td width="32">
                <a href="form-configuracion.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
              <td><?php echo(utf8_encode($fila["nombre"])); ?></td>
            <td><?php echo(utf8_encode($fila["ban_id"]));?></td>
            <td><?php echo($fila["cut_id"]);?></td>   
            <td><?php echo($fila["nro"]);?></td> 
            <td><?php echo($fila["cbu"]);?></td> 
            <td>
                <a href="form-edit-cuentabanco.php?id=<?php echo($fila["id"]);?>">
                  <img src="images/editar.png" alt="editar" title="Modificar cliente" width="32" height="32" border="none" />
                </a>  
              </td>
            <td><a href="#" onclick="eliminarItem(<?php echo($fila["ban_id"]);?>,'<?php echo($fila["ban_nombre"]);?>','delete-banco.php?id=')">
                    <img src="images/eliminar.png" alt="eliminar" title="Eliminar cliente" width="32" height="32" border="none" />
                </a></td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
      </table>
      <table class="listados" cellpadding="5">
          <tr>
            <td colspan="5" class="pie_lista"><?php 
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
