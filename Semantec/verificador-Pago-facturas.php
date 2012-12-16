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
    //    alert('transfiriendo..');
	document.getElementById("formVerificadorPagosFactura").submit();
  };
  </script>

  <? echo '<big><b>Cargando... </b></big><img src="images/loader.gif"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" />'; 
  while ($i < $cantTotal)
{ include("conexion.php");  // borrrARR!!!!1
    $i++; //echo $i,'---';
    $CodigoOrden=$_POST["chckCodFactura$i"];
    if($CodigoOrden!=''){       
        $n++; }}
    $cantFav=$n;
  ?>
  
<body onload="mandarFormulario()">
       
    
    
    
    
    
<form name="formVerificadorPagosFactura" id="formVerificadorPagosFactura" method="post" enctype="multipart/form-data" 
      action="form-alta-pago-grupo-fav.php?ccc_id=20&cli_id=<? echo $cli_id ?>&cant=<? echo $cantFav ?>" >

<?php 
$i=0;
$n=0;
while ($i < $cantTotal)
{ //include("conexion.php");  // borrrARR!!!!1
    $i++; //echo $i,'---';
    $CodigoOrden=$_POST["chckCodFactura$i"];
    if($CodigoOrden!=''){       
        $n++;
    //    echo 'envio : ',$n,'>>>',$CodigoOrden;
    //    $filaFactura = getTotalAPagarConIva($CodigoOrden);
    //   $facturaTotal = mysql_fetch_array($filaFactura);
   //    echo "total de factura: ", $facturaTotal["total"];
  //      echo "nuermo de fac: ",getFacturasCodigoWhitID($CodigoOrden),"<br>";
      
?>
       <input type="hidden" name="o<?php echo $n; ?>"  id="o<?php echo $n; ?>" value="<?php echo ($CodigoOrden); ?>" > 
<?  
    }

}
?>

</form>
</body>
</html> 