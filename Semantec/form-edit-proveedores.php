<?php
    $titulo = "Formulario de modificaci&oacute;n de datos de un proveedor.";
        include("validar.php");

        include("conexion.php");
        $prv_id = $_GET["prv_id"];
        $sql0 = "SELECT prv_nombre, prv_cuit, iva_tipo.iva_nombre, rubros.rub_nombre, u.id as ubicacion_id, prv_direccion, prv_telefono,prv_fax,prv_cel,prv_alternativo,prv_urgencia,prv_web,prv_email,prv_notas 
		FROM proveedores,rubros,iva_tipo,ubicacion u,provincias p, partidos pa,localidades l 
		WHERE prv_id = $prv_id
		AND proveedores.iva_id = iva_tipo.iva_id
                AND proveedores.rub_id = rubros.rub_id
                AND proveedores.ubicacion_id = u.id
                AND u.provincias_id = p.id
		AND u.partidos_id = pa.id
		AND u.localidades_id = l.id";
    
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);

        
        $ubicacion_id = $fila0["ubicacion_id"];
        
        
        
        $sql = "SELECT  iva_id, iva_nombre FROM iva_tipo";
        $iva = mysql_query($sql);
        
                                     
        $sql    = "SELECT rub_id,rub_nombre FROM rubros";
        $rubros = mysql_query($sql);
               
        $sql = "select cut_id,cut_nombre FROM cuentatipo";
        $tipocuenta = mysql_query($sql);
        
        $sql = "SELECT ban_id , ban_nombre FROM `banco` ";
        $bancos = mysql_query($sql);
        
        
        /* COMBOS DE PROVINCIAS , PARTIDOS, LOCALIDADES */
        
        $sql = "SELECT id, nombre FROM provincias";
        $listado_provincias =   mysql_query($sql);
        
        
        $sql = "SELECT id, nombre FROM partidos";
        $listado_partidos   =   mysql_query($sql);
        
        $sql = "SELECT id, nombre FROM localidades";
        $listado_localidades  =    mysql_query($sql);
        
        
        /* OBTENIENDO ID DE PROV, PARTIDOS Y LOCALDAD DE ACUERDO A SU UBICACIÓN_ID */
        
        $sql = "SELECT p.id ,p.nombre from ubicacion u, provincias p 
                WHERE u.id = $ubicacion_id
                and u.provincias_id = p.id";
        
        $resultado = mysql_query($sql);
        $provincia = mysql_fetch_array($resultado);
        
        
        $sql = "SELECT p.id ,p.nombre FROM ubicacion u, partidos p 
                WHERE u.id = $ubicacion_id
                and u.partidos_id = p.id";
        
        $resultado = mysql_query($sql);
        $partidos = mysql_fetch_array($resultado);
        
        
        $sql =  "SELECT l.id ,l.nombre FROM ubicacion u, localidades l 
                 WHERE u.id = $ubicacion_id
                 AND u.localidades_id= l.id";
        $resultado = mysql_query($sql);
        $localidades = mysql_fetch_array($resultado);
        
        
       $sql ="SELECT cue.cue_nrobancaria,cut.cut_nombre,cue.cue_cbu , b.ban_nombre AS nombreBanco FROM cuentabanco_prv cue,cuentatipo cut , banco b
               WHERE prv_id = $prv_id
               AND cue.cut_id = cut.cut_id
               AND cue.ban_id = b.ban_id";
        $banco = mysql_query($sql);
        $banco2 = mysql_query($sql);
        $filas =  mysql_num_rows($banco);
        
        $fila_banco = mysql_fetch_array($banco2);
        
        if($filas > 0){            
            $_SESSION["tienecuenta"] = true;
        }else{
            $_SESSION["tienecuenta"] = false;         
        }
        

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>    
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
      function lookup(prv_rubro) {
        if(prv_rubro.length == 0) {
          $('#suggestions').hide();
        } else {
          $.post("rpc-rubro2.php", {queryString: ""+prv_rubro+""}, function(data){
            if(data.length >0) {
              $('#suggestions').show();
              $('#autoSuggestionsList').html(data);
            }
          });
        }
      }
      
      function fill(thisValue) {
        $('#prv_rubro').val(thisValue);
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
    <a href="#" id="logo"><img src="images/semantec-logo.jpg" width="470" height="100" alt="logo" /></a>
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
    <form action="edit-proveedores.php" method="post" name="frmEditPrv">
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo(utf8_encode($titulo))?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
          <tr>
            <td>Raz&oacute;n Social</td>
            <td><input type="text" value="<?php echo(utf8_encode($fila0["prv_nombre"])); ?>" class="campos" id="prv_nombre" name="prv_nombre" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT</td>
            <td><input type="text" value="<?php echo substr(($fila0["prv_cuit"]),0,2); ?>" style="text-align:right" min="10" class="campos2" id="cuit_parteA" name="cuit_parteA" maxlength="2" size="2" onKeyPress="pasaSiguiente(this, document.getElementById('cuit_parteB'), 2)"  required />
              -
                <input type="text" value="<?php echo substr(($fila0["prv_cuit"]),2,8); ?>" style="text-align:right" min="10000000" class="campos2" id="cuit_parteB" name="cuit_parteB" maxlength="8" size="8" onKeyPress="pasaSiguiente(this, document.getElementById('cuit_parteC'), 8)" required />
                -
            <input type="text" value="<?php echo substr(($fila0["prv_cuit"]),-1); ?>" style="text-align:right" min="0" class="campos2" id="cuit_parteC" name="cuit_parteC" maxlength="1" size="1" onChange="return autenticaCUIT();"  required /></td></td>
            <td></td>
          </tr>
          <tr>
            <td>Condici&oacute;n de IVA</td>
            <td>
                <select name="iva_id" id="iva_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($iva)){
    ?>
                    <option value="<?php echo($fila["iva_id"]); ?>"<?php if($fila0["iva_nombre"]==$fila["iva_nombre"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["iva_nombre"]); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Rubro</td>
            <td>
                <select name="rub_id" id="rub_id" class="campos">
                <?php
          while($fila = mysql_fetch_array($rubros)){
                ?>
                    <option value="<?php echo($fila["rub_id"]); ?>" <?php if($fila0["rub_nombre"]==$fila["rub_nombre"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["rub_nombre"])); ?></option>
                <?php
                    }
                    ?>          
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Provincia</td>
            <td>
                <select name="select1" id="select1" class="campos" onChange="cargaContenido(this.id)">
                <option value='0'>Seleccione</option>;   
    <?php
          while($fila_provincia = mysql_fetch_array($listado_provincias)){
    ?>
                    <option value="<?php echo($fila_provincia["id"]); ?>" <?php if($provincia["id"]==$fila_provincia["id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila_provincia["nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          
          <tr>
                        <td>Partido</td>
                        <td><label>
                                <select name="select2" id="select2" class="campos" onChange="cargaContenido(this.id)">
                                <option value="0">Selecciona opci&oacute;n...</option>
                                <?php
          while($fila_partidos = mysql_fetch_array($listado_partidos)){
    ?>
                    <option value="<?php echo($fila_partidos["id"]); ?>" <?php if($partidos["id"]==$fila_partidos["id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila_partidos["nombre"])); ?></option>
    <?php
          }
    ?>
                                </select>
                        </label></td>
                      </tr>
          
          <tr>
                        <td>Localidad</td>
                        <td><label>
                                <select name="select3" id="select3" class="campos">
                                <option value="0">Selecciona opci&oacute;n...</option>
                               
                                <?php
                     while($fila_localidades = mysql_fetch_array($listado_localidades)){
    ?>
                    <option value="<?php echo($fila_localidades["id"]); ?>" <?php if($localidades["id"]==$fila_localidades["id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila_localidades["nombre"])); ?></option>
    <?php
          }
    ?>
                                </select>
                                
                                
                                
                                
                        </label></td>
                      </tr>
          <tr>
            <td>Direcci&oacute;n</td>
            <td><input type="text" value="<?php echo(utf8_encode($fila0["prv_direccion"])); ?>" class="campos" id="prv_direccion" name="prv_direccion" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Tel&eacute;fono</td>
            <td><input type="text" style="text-align:right" value="<?php echo($fila0["prv_telefono"]); ?>" class="campos" id="prv_telefono" name="prv_telefono" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Fax</td>
            <td>
            <input type="text" style="text-align:right" value="<?php echo($fila0["prv_fax"]); ?>" class="campos" id="prv_fax" name="prv_fax" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Cel</td>
            <td><input type="text" style="text-align:right" value="<?php echo($fila0["prv_cel"]); ?>" class="campos" id="prv_cel" name="prv_cel" /></td>
         <td></td>
          </tr>
          <tr>
            <td>Alternativo</td>
            <td><input type="text" style="text-align:right" value="<?php echo($fila0["prv_alternativo"]); ?>" class="campos" id="prv_alternativo" name="prv_alternativo" /></td>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Urgencia</td>
            <td><input type="text" style="text-align:right" value="<?php echo($fila0["prv_urgencia"]); ?>" class="campos" id="prv_urgencia" name="prv_urgencia" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Web</td>
            <td>
            <input type="text" value="<?php echo($fila0["prv_web"]); ?>" class="campos" id="prv_web" name="prv_web" />           
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Email</td>
            <td>
            <input type="email" value="<?php echo($fila0["prv_email"]); ?>" class="campos" id="prv_email" name="prv_email" />
            </td>
            <td></td>
          </tr>
          <td>Tiene cuenta bancaria?</td>
            <td>Sí<input type="radio" class="" id="rbt_cuenta" name="rbt_cuenta" value="1" onclick="disableTxt(2,'S')" <? if($filas>0) echo "checked"?>/>&nbsp;No<input type="radio" class="" id="rbt_cuenta" name="rbt_cuenta" value="0" onclick="disableTxt(2,'N')" <? if($filas==0) echo "checked"?>/></td>
            <td></td>
          </tr>
          <tr>
            <td>Nro.Cuenta bancaria</td>
            <td><input type="number" style="text-align:right" class="campos" id="cue_nrobancaria" name="cue_nrobancaria" value="<? if($filas>0) 
                                                                                                    echo $fila_banco["cue_nrobancaria"];
                                                                                                        
                                                                                                        ?>" <? if($filas==0)
                                                                                                            echo " disabled";?>/></td>
            
            
            <td></td>
          </tr>
          <tr>
              <td>Tipo de cuenta</td><td>
          <select name="cut_id" id="cut_id" class="campos" <? if($filas==0) echo " disabled";?>>
    <?php
          while($fila = mysql_fetch_array($tipocuenta)){
              
    ?>
                    <option value="<?php echo($fila["cut_id"]); ?>" <?php if($fila["cut_nombre"]==$fila_banco["cut_nombre"]){echo(" selected=\"selected\"");} ?> ><?php echo(utf8_encode($fila["cut_nombre"])); ?></option>
                    
                    
    <?php
          }
    ?>
                </select>
                  </td>          
          </tr>
          <tr>
            <td>CBU</td>
            <td><input type="text" style="text-align:right" class="campos" id="cue_cbu" name="cue_cbu" value="<? if($filas>0){ 
                                                                                                   echo $fila_banco["cue_cbu"];
                                                                                                   }?>" <? if($filas==0)
                                                                                                            echo " disabled";?>/></td>
            <td></td>
          </tr>        
          <tr>
              <td>Banco</td><td>
          <select name="ban_id" id="ban_id" class="campos" >
    <?php
          while($fila = mysql_fetch_array($bancos)){
    ?>
                    <option value="<?php echo($fila["ban_id"]); ?>" <?php if($fila["ban_nombre"]==$fila_banco["nombreBanco"]){echo(" selected=\"selected\"");} ?>  > <?php echo(utf8_encode($fila["ban_nombre"])); ?> </option>
    <?php
          }
    ?>
                </select>
                  </td>          
          </tr>
          
          
          
            <tr>
            <td>Notas</td>
            <td><textarea class="campos" id="prv_notas" name="prv_notas" rows="9"><?php echo(utf8_encode($fila0["prv_notas"])); ?></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar proveedor" class="botones" onclick="return validarCombosDeUbicacion()"/>
                <input type="hidden" value="<?php echo($prv_id); ?>" name="prv_id" id="prv_id" />
                <input type="hidden" value="<?php echo($ubicacion_id); ?>" name="ubicacion_id" id="ubicacion_id" />
            </td>
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