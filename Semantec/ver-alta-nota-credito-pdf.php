<?php
    include("validar.php");

        $nrc_id = $_GET["nrc_id"];
        include("funciones.php");
        include("conexion.php");
        
        $sql = "SELECT c.cli_nombre,c.cli_direccion,i.iva_nombre,c.cli_cuit ,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad,  c.cli_direccion_fiscal,c.sucursal_id
                FROM ordenes o,clientes c,iva_tipo i,factura_venta f,grupo_ordenes g_o , ubicacion u,provincias p, partidos pa,localidades l
                WHERE 
                f.fav_id in (select fv.fav_id from nota_credito nc
										INNER JOIN grupo_fav_nota gfv
										ON nc.gfn_id = gfv.gfn_id
										INNER JOIN factura_venta fv
										ON fv.grupo_nota_credito = gfv.gfn_id
										WHERE nc.nrc_id = $nrc_id)
                and f.gru_id    = g_o.gru_id
                and g_o.gru_id = o.gru_id
                and o.cli_id = c.cli_id
                AND c.ubicacion_id = u.id
                AND u.provincias_id = p.id
                AND u.partidos_id = pa.id
                AND u.localidades_id = l.id
                and c.iva_id = i.iva_id";
        
       $cliente = mysql_query($sql); 
       $fila_datos_cliente = mysql_fetch_array($cliente); 
       
              //Busco Sucursal Matriz
       $SucursalMatriz=$fila_datos_cliente['sucursal_id'];
       $SucursalMatrizLocalidad=$fila_datos_cliente["provincia"];
       if($SucursalMatriz!=null)
       {
           $sql = "SELECT  c.cli_id , c.sucursal_id, c.ubicacion_id, c.cli_nombre,c.cli_cuit, c.iva_id, c.cli_rubro, c.cli_direccion, c.cli_direccion_fiscal, c.cli_telefono, c.sucursal,
                           p.nombre as provincia,pa.nombre as partido,l.nombre as localidad
                   FROM  clientes c , ubicacion u,provincias p, partidos pa,localidades l
                   WHERE c.cli_id       = $SucursalMatriz
                   AND c.ubicacion_id   = u.id
                   AND u.provincias_id  = p.id
                   AND u.partidos_id    = pa.id
                   AND u.localidades_id = l.id
                    ";
           $sucursal = mysql_query($sql); 
           $fila_sucursal_Matriz = mysql_fetch_array($sucursal); 
           $SucursalMatrizLocalidad=$fila_sucursal_Matriz['provincia']; 
       }
       //+++++configuracion  de descripciones a imprimir en pantalla+++++
       $numeroDescripcion   =0;
       $totalDescripcion    =5;
       
       
      //DIOGETE $sql = "select fav_fecha,fav_nota from factura_venta where ord_id = $ord_id";
       $sql = "select nrc_codigo,idiva,nrc_fecha,gfn_id,nrc_nota from nota_credito
                WHERE nrc_id  = $nrc_id";
       $fecha_factura = mysql_query($sql);
       $fila_fecha_factura = mysql_fetch_array($fecha_factura);
      //busca Codigo de Ordenes asociadas a esta factura dado un  gru_id , transformar en funcion
       $grupo_id=$fila_fecha_factura["gfn_id"]; 
       $sql = "select fav_id,cod_factura_venta from factura_venta where grupo_nota_credito = $grupo_id";  
       $codigo_de_ordenesFav = mysql_query($sql);
       
      
       $sql = "select * from detalle_nota_credito where nrc_id = $nrc_id";
       $descripcion_factura = mysql_query($sql);
       $descripcion_factura2 = mysql_query($sql);
       $fila_descripcion = mysql_fetch_array($descripcion_factura2);
       
       
       $idiva=$fila_descripcion["idiva"];
       $sql = "select * from iva where idiva = $idiva";
       $descrio_iva = mysql_query($sql);
       $fila_iva = mysql_fetch_array($descrio_iva);
       
       mysql_close();
       
        $subtotal = 0;
       
        $fav_idPDF= ($nrc_id);
        
        
        
       require('fpdf.php');

$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->SetMargins(12, 1 ,10);// margenes izq, top , derecha
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,1);  
// DATOS DE FACTURA PDF      Cell(unNumero)--Mover a la derecha ; Ln(unNumero) Salto de Linea
//Numero de FActura
$pdf->Cell(120);
//$pdf->Cell(40,6,$fav_id,0,1,R); Numero de FActura
$pdf->Ln(14);//antes 3
//Fecha
$pdf->Cell(120);
$pdf->Cell(40,10,tfecha($fila_fecha_factura["nrc_fecha"]),0,1,R);
$pdf->Ln(24);
// Sres
$interlineado=7.2;
$pdf->Cell(25);
$pdf->Cell(160,$interlineado,($fila_datos_cliente["cli_nombre"]),0,1);
//Domicilio - Localidad
$pdf->Cell(25);
$pdf->Cell(80,$interlineado,($fila_datos_cliente["cli_direccion_fiscal"]),0);
$pdf->Cell(35);// antes 40
$pdf->Cell(40,$interlineado,($SucursalMatrizLocalidad),0,1);
//IVA - CUIT
$pdf->Cell(25);
$pdf->Cell(80,$interlineado,$fila_datos_cliente["iva_nombre"],0);
$pdf->Cell(35);// antes 40
$pdf->Cell(40,$interlineado,verCUIT($fila_datos_cliente["cli_cuit"]),0,1);
//Condiciones de Venta - Remito
$pdf->Cell(45);
//$pdf->Cell(80,$interlineado,utf8_decode($fila_fecha_factura["fav_condicion_vta"]),0);
$pdf->Cell(15);//// antes 20
//$pdf->Cell(15,$interlineado,'REMITO:');
//$pdf->Cell(40,$interlineado,$fila_fecha_factura["fav_remito"],0,1);
// DEscripcion - Total
  $pdf->Ln(14);
  $POsXoriginal=$pdf->GetX();
  while($item = mysql_fetch_array($descripcion_factura)){
       $precio_item = $item["det_nrc_precio"]; 
       
       $POsYoriginal=$pdf->GetY();
       $pdf->MultiCell(140,6,utf8_decode($item["det_nrc_descripcion"]),0,L);
       $POsYDescpuesDeTExto=$pdf->GetY();
       $pdf-> SetXY(193,$POsYoriginal);  // ACA MOVES POS de PRECIO
      
         if($item["det_nrc_precio"]>0)
        {
            $pdf->Cell(1,8,'$',0,0,R);
            $pdf->Cell(17,8,number_format($item["det_nrc_precio"], 2, ',', '.'),0,0,R);
        }
        else
        { $pdf->Cell(17,8,'S/C',0,0,R);}
        $pdf-> SetXY($POsXoriginal,$POsYDescpuesDeTExto);
        
  $subtotal += $precio_item;
  }
//Vencimiento - SUBTOTAL
$pdf-> SetY(222);
$pdf->Cell(42);//antes 40
//$pdf->Cell(40,18,$fila_fecha_factura["fav_vencimiento"]);
$pdf->Cell(99);
//$pdf->Cell(10,18,'$',0,0,R);
$pdf->Cell(54,18,'$  '.number_format($subtotal, 2, ',', '.'),0,1,R);
//NOTA - IVA INSC - TOTAL IVA INSC
$pdf->Cell(22);
$pdf->MultiCell(90,12,utf8_decode($fila_fecha_factura["nrc_nota"]),0);

$pdf->SetXY(148,236); // despues de un multycell no puede ir cell 
        
$pdf->Cell(26,13,number_format($fila_iva["valor"], 2, ',', '.'),0,0,R);
$iva_total = $subtotal*0.21;
$pdf->Cell(19);
$pdf->Cell(1,12,'$',0,0,R);
$pdf->Cell(17,12,number_format($iva_total, 2, ',', '.'),0,1,R);
//IVA NO INSC
$pdf->Cell(180);
$pdf->Cell(17,11,'-',0,1,R);
// TOTAL
$pdf->Cell(180);
$pdf->Cell(1,10,'$',0,0,R);
$pdf->Cell(18,10,number_format($iva_total + $subtotal, 2, ',', '.'),0,0,R);
  
$pdf->Output();
?>



