<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"cli_id"=>"clientes",
"suc_id"=>"clientes"
);


$relacionSelects = array(
"cli_id"=>"sucursal_id",
"suc_id"=>"sucursal_id",   
    
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];
$selectCampo2=$_GET["selected"];
if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
        $relacion=$relacionSelects[$selectDestino];
        
	include 'conexion.php';
	//conectar();
	$consulta=mysql_query("SELECT cli_id, cli_nombre,sucursal FROM $tabla WHERE $relacion='$opcionSeleccionada' order by cli_nombre") or die(mysql_error());
	//desconectar();
	echo $selectCampo2;
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' class=campos>";
	echo "<option value='0'>Elige</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select  
                $option="<option value='".$registro[0]."'";
		/*echo "<option value='".$registro[0]."'    <?php if($registro[0]==$selectCampo2){echo(" selected=\"selected\"");} ?>   */  
                if($registro[0]==$selectCampo2){
                    $option.= " selected=\"selected\ "; }
                $option.= ">".$registro[1]."(".utf8_decode($registro[2]).")</option>";
                echo $option;
	}			
	echo "</select>";
        echo  $option;
}       
?>