<html> 
<link rel="icon" href="images/favicon.gif" type="image/x-icon"/>
<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/> 
<?php

$cli_id=$_GET['cli_id'];
$cantTotal=$_GET['cant'];
$i=0;
$n=0;

        include("validar.php");
        include("conexion.php");
        include("Modelo/modeloFacturaVenta.php");
?>
  <script type="text/javascript">
  function mandarFormulario() {
    
	document.getElementById("formVerificadorNotasCredito").submit();
  };
  </script>

  <? echo '<big><b>Cargando... </b></big><img src="images/loader.gif"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" />'; 
  while ($i < $cantTotal)
{ include("conexion.php");  
    $i++; 
    $CodigoOrden=$_POST["chckCodFactura$i"];
    if($CodigoOrden!=''){       
        $n++; }}
    $cantFav=$n;
  ?>
  
<body onload="mandarFormulario()">
       
    
    
<form name="formVerificadorNotasCredito" id="formVerificadorNotasCredito" method="post" enctype="multipart/form-data" 
      action="" >

<?php 
$i=0;
$n=0;
while ($i < $cantTotal)
{ 
    $i++; 
    $CodigoOrden=$_POST["chckCodFactura$i"];
    if($CodigoOrden!=''){       
        $n++;
    
      
?>
       <input type="hidden" name="o<?php echo $n; ?>"  id="o<?php echo $n; ?>" value="<?php echo ($CodigoOrden); ?>" > 
<?  
    }

}
?>

</form>
</body>
</html> 
