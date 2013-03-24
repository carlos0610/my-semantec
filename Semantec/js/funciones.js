//volver a pagina anteior
function goBack()
  {
  window.history.back()
  }

function imprimir(value)
  {
  alert(value);
  }

// BAJA de ORDEN
function eliminarOrden(id,nombre){
    if(confirm('¿Confirma Eliminar Orden: '+nombre+' ?')==true)
    {
        window.location="delete-ordenes.php?orden_id="+id;
    }
}

//BAJA de CLIENTE

function eliminarCliente(id,nombre){
    
    if(confirm('¿Confirma eliminar Cliente: '+nombre+' ?')==true)
    {
        window.location="delete-clientes.php?cli_id="+id;       
       
    }
}
//BAJA de Abono

function eliminarAbono(id,nombre){
    
    if(confirm('¿Confirma eliminar Abono: '+nombre+' ?')==true)
    {
        window.location="delete-abono.php?idAbono="+id;             
    }
}

function eliminarUsuario(id,nombre){
    if(confirm('¿Confirma eliminar usuario: '+nombre+' ?')==true)
    {
        window.location="delete-usuarios.php?usu_id="+id;       
       
    }
    
    
}


function eliminarUsuarioPortal(id,nombre){
    if(confirm('¿Confirma eliminar usuario: '+nombre+' ?')==true)
    {
        window.location="delete-usuarios-portal.php?usu_id="+id;       
       
    }
      
}




function eliminarProveedor(id,nombre){
    
    if(confirm('¿Confirma eliminar proveedor: '+nombre+' ?')==true)
    {
        window.location="delete-proveedores.php?prv_id="+id;
    }
}



// BAJA Factura
function eliminarFactura(id,grup_id,codigo){
    
    if(confirm('¿Confirma eliminar Factura: '+codigo+' ?')==true)
    {
        window.location="delete-factura.php?fav_id="+id+"&grup_id="+grup_id;
    }
}


function pagarFactura(id,cuenta,codigo){
    
    if(confirm('¿Confirma el pago de la factura nro: '+codigo+'? ')==true)
    {
        //window.location="pagar-factura.php?fav_id="+id+"&ccc_id="+cuenta;
        window.location="form-alta-pago.php?fav_id="+id+"&ccc_id="+cuenta;
    }
}

function cancelarAdelanto(id,detalle_id){
    
    if(confirm('¿Confirma la anulación del adelanto : '+id+'? ')==true)
    {
        window.location="delete-adelantos.php?ord_det_id="+detalle_id;
        
    }
}

function confirmacionAdelanto(){
    alert('El adelanto ha sido cancelado correctamente');
    
}



function eliminarArchivo(id_file,ord_id){
     if(confirm('¿Desea eliminar el archivo adjunto? ')==true)
    {
        window.location="delete-files.php?id="+id_file+"&ord_id="+ord_id;   
    }    
}
function emitirAdelanto(pagina){
    var orden       = document.getElementById("txtOrden").value;
    var monto       = document.getElementById("txtAdelanto").value;
       
    
    if (monto > 0){
    
                    if(confirm('¿Confirma el adelanto para la orden : '+orden+'?')==true)
                        {
                        var desc =document.getElementById("txtDescripcion").value;
                        window.location="pagar-adelanto.php?orden="+orden+"&adelanto="+monto+"&desc="+desc+"&pagina="+pagina;
                        }    
        
                  
               } else {
        alert("El monto de adelanto tiene que ser mayor a 0.00");
                    }
}





function disableTxt(formulario,id){
    var check = (id=="S") ? true : false;

    var nombre
    if (formulario == 1)
        nombre = "frmAltaPrv";
    else
        nombre = "frmEditPrv";

    if(check){
        
        //Si seleccionó que 'S' tiene cuenta bancaria, activamos campo de nro de cuenta ,combo Tipo cuenta y campode CBU
        //Como el estado original es disabled, le enviamos un false para activarlos.

        document.forms[nombre].cue_nrobancaria.disabled=false;    //Habilitamos combobox de Nro cuenta bancaria   
        document.forms[nombre].cue_nrobancaria.value="";
        document.forms[nombre].cue_nrobancaria.focus();
        document.forms[nombre].cut_id.disabled=false;             //Habilitamos combobox Tipo cuenta
        document.forms[nombre].cue_cbu.disabled=false; 
        document.forms[nombre].ban_id.disabled=false; 
     
    }else{
        document.forms[nombre].cue_nrobancaria.disabled=true;
        document.forms[nombre].cut_id.disabled=true;              //Deshabilitamos combobox Tipo cuenta
        document.forms[nombre].cue_cbu.disabled=true;             //Deshabilitamos campo de texto CBU
        document.forms[nombre].cut_id.selectedIndex=0;            //Combo vuelve a 'Seleccione'
        document.forms[nombre].cue_nrobancaria.value="";
        document.forms[nombre].cue_cbu.value="";
        document.forms[nombre].ban_id.disabled=true; 
    }

}

function habilitarComboCliente(formulario){
    
    var nombre = formulario;
    var valor = true;    
    if(document.forms[nombre].chkSucursal.checked){
        valor = false;
        limpiarFrmAltaCliente();
         document.getElementById("cli_cuit_parteA").setAttribute('readOnly','readonly');
         document.getElementById("cli_cuit_parteB").setAttribute('readOnly','readonly');
         document.getElementById("cli_cuit_parteC").setAttribute('readOnly','readonly');
         document.getElementById("cli_nombre").setAttribute('readOnly','readonly');
         document.getElementById("cli_direccion_fiscal").setAttribute('readOnly','readonly');
         document.getElementById("error").style.visibility = "hidden";
        }
        else
            {
               limpiarFrmAltaCliente();
               document.getElementById("cli_nombre").value="";
               document.getElementById("cli_cuit_parteA").removeAttribute('readOnly');
               document.getElementById("cli_cuit_parteB").removeAttribute('readOnly');
               document.getElementById("cli_cuit_parteC").removeAttribute('readOnly');
               document.getElementById("cli_nombre").removeAttribute('readOnly');
               document.getElementById("cli_nombre").focus();
               document.getElementById("cli_direccion_fiscal").removeAttribute('readOnly');
            }
    document.forms[nombre].comboClientes.disabled = valor;   
}


function habilitarFecha(formulario){
    
    var nombre = formulario;
    var valor = true;    
    if(document.forms[nombre].ord_checkAbono.checked){ 
        valor = false;
         document.getElementById("ord_abono_fecha").setAttribute('readOnly','readonly');
         document.getElementById("ord_abono_fecha").focus();
        }
        else
            {
               document.getElementById("ord_abono_fecha").removeAttribute('readOnly');
            }
    document.forms[nombre].ord_abono_fecha.disabled = valor;   
}

function habilitarFechaOT(formulario){
    
    var nombre = formulario;
    var valor = true;    
    if(document.forms[nombre].ord_checkOT.checked){ 
        valor = false;
         document.getElementById("fecha_ot").setAttribute('readOnly','readonly');
         document.getElementById("fecha_ot").focus();
        }
        else
            {
               document.getElementById("fecha_ot").removeAttribute('readOnly');
            }
    document.forms[nombre].fecha_ot.disabled = valor;   
}




function habilitarRetenciones(formulario,numero){
    
    var nombre = formulario;
    var valor = true; 
    if(document.getElementById(nombre).checked)
    {
      valor = false;
    }
    document.getElementById("txtFecha"+numero).disabled = valor;   
    document.getElementById("txtPrefijo"+numero).disabled = valor;  
    document.getElementById("txtNro"+numero).disabled = valor;
    document.getElementById("txtImporte"+numero).disabled = valor;
    if(valor)
        document.getElementById("txtImporte"+numero).value = 0.00;
    
    
    if(nombre=="chkIva")
        {
            document.getElementById("comboIva").disabled = valor;  
        }
        
    if(nombre >= '4')// cuando son  IIBB
        {  
            document.getElementById("comboProvincias"+numero).disabled = valor;
            document.getElementById("txtJurisdiccion"+numero).disabled = valor;
        }
        
        actualizarDetallePago();
        
}
function habilitarRetencionesYActualizarDetalle(formulario,numero,ord_venta,cantidadTipoPago){
   habilitarRetenciones(formulario,numero);
   actualizarDetallePago(ord_venta,cantidadTipoPago);
}
function filtroTipoDePago(valorCombo,numero){
    var valor = true; 
    if(valorCombo!=0)
    {
      valor = false;
    }
    document.getElementById("txtNroOperacion"+numero).disabled = valor;   
    document.getElementById("comboBanco"+numero).disabled = valor;  
    document.getElementById("txtSucursal"+numero).disabled = valor; 
    document.getElementById("txtFechaEmision"+numero).disabled = valor;   
    document.getElementById("txtFechaVto"+numero).disabled = valor;  
    document.getElementById("txtFirmante"+numero).disabled = valor;  
    document.getElementById("txtCuit"+numero).disabled = valor;  
    document.getElementById("comboCuenta"+numero).disabled = valor;  
    document.getElementById("txtFechaTransferencia"+numero).disabled = valor;  
    document.getElementById("txtImportePago"+numero).disabled = valor;  
    document.getElementById("userfile"+numero).disabled = valor;  
    valor = true;
            $(".filaTransferencia"+numero).show();   // show( selectedEffect, options, 500, callback );
           // $(".filaCheque"+numero).show(); 
           $(".filaCheque"+numero).show(); 
    if(valorCombo=="1")//cheque
        {
                document.getElementById("comboCuenta"+numero).disabled = valor;  
                document.getElementById("txtFechaTransferencia"+numero).disabled = valor;  
                $(".filaTransferencia"+numero).hide(500,''); 
           //     $(".filaCheque"+numero).removeAttr( "style" ).hide().fadeIn(); 
        }
    if(valorCombo=="2")// Efectivo
        {
            document.getElementById("comboBanco"+numero).disabled = valor;  
            document.getElementById("txtSucursal"+numero).disabled = valor;  
            document.getElementById("txtFechaEmision"+numero).disabled = valor;  
            document.getElementById("txtFechaVto"+numero).disabled = valor;  
            document.getElementById("txtFirmante"+numero).disabled = valor;  
            document.getElementById("txtCuit"+numero).disabled = valor;  
            document.getElementById("comboCuenta"+numero).disabled = valor;  
            document.getElementById("txtFechaTransferencia"+numero).disabled = valor;  
            $(".filaTransferencia"+numero).hide(); 
            $(".filaCheque"+numero).hide(500,''); 
        }
    if(valorCombo=="3")//transferencia
        {
                document.getElementById("comboBanco"+numero).disabled = valor;  
                document.getElementById("txtSucursal"+numero).disabled = valor;  
                document.getElementById("txtFechaEmision"+numero).disabled = valor;  
                document.getElementById("txtFechaVto"+numero).disabled = valor;  
                document.getElementById("txtFirmante"+numero).disabled = valor;  
                document.getElementById("txtCuit"+numero).disabled = valor;  
                $(".filaCheque"+numero).hide(500,''); 
        }
}

function habilitarFiltros(nombre,gadet){
  if(document.getElementById(nombre).checked){ 
     document.getElementById(gadet).disabled=false;
     document.getElementById(gadet).focus();
  }else{
      document.getElementById(gadet).disabled=true  ;
  }
   
}

function habilitarFiltros(nombre,gadet,gadet2){
  if(document.getElementById(nombre).checked){ 
     document.getElementById(gadet).disabled=false;
     document.getElementById(gadet2).disabled=false;
     document.getElementById(gadet).focus();
  }else{
      document.getElementById(gadet).disabled=true;
      document.getElementById(gadet2).disabled=true;
  }
   
}




function colocarvalor(nombre,valor){
  if(document.getElementById(nombre).checked){ 
     document.getElementById(nombre).value=valor;
  }else{
      document.getElementById(nombre).value=1  ;
  }
   
}
function habilitarFiltrosClienteSucursal(nombre,gadet,sucursal){
  if(document.getElementById(nombre).checked){ 
     document.getElementById(gadet).disabled=false;
     document.getElementById(gadet).focus();
  }else{
      document.getElementById(gadet).disabled=true  ;
      document.getElementById(sucursal).disabled=true  ;
  }
   
}

function habilitarCombo2(nombre,gadet){
    
  if(document.getElementById(nombre).value>0){ 
     document.getElementById(gadet).disabled=false;
     document.getElementById(gadet).focus();
     document.getElementById("filtro").submit();
  }else{
      document.getElementById(gadet).disabled=true  ;
  }

}
function agregarOrderBy(campo){
 document.getElementById("orden").value=campo;
  document.getElementById("contador").value++;
 document.getElementById("filtro").submit();
}
//pasar filtro a otro Form
function transferirFiltrosAOtroForm(nombreFiltro,url){

        document.getElementById(nombreFiltro).action=url;
        document.getElementById(nombreFiltro).submit();
}
function limpiarFrmAltaCliente(){
         document.getElementById("cli_cuit_parteA").value="";
         document.getElementById("cli_cuit_parteB").value="";
         document.getElementById("cli_cuit_parteC").value="";
         document.getElementById("cli_nombre").value="";
         document.getElementById("cli_direccion_fiscal").value="";
         
}






function validarSiNumeroYComa(numero){
    ok=true;

if (!/^[0-9]+(\.[0-9]+)?$/.test(numero)){
alert("El valor " + numero + " no es un número");
ok=false;
}
return ok;
}

function ActualizarTotal(cantidadDescripciones,factura){  
    iva = document.getElementById("comboIva").value;
    document.getElementById("btnConfirma").style.visibility = "visible"; 
    if (iva == 1){
         iva = 0.21;
    }else{
         iva = 0.105;   
         } 
     
    subtotal = 0;
    resta=0;
    resta=totalOrdenesVenta=document.getElementById("totalOrdenVentatxt").value; 
    numeroDescripcion=0;
    
        
        while(numeroDescripcion < cantidadDescripciones)
            {        
                numeroDescripcion++;
                if (validarSiNumeroYComa(document.getElementById("txtTotalItem"+numeroDescripcion).value))
                {
                     subtotal += parseFloat(document.getElementById("txtTotalItem"+numeroDescripcion).value); 
                     resta-=parseFloat(document.getElementById("txtTotalItem"+numeroDescripcion).value); 
                }else
                {document.getElementById("txtTotalItem"+numeroDescripcion).value="0.00";}
            }   //Fin_While
    
    
    
    document.getElementById("txtSubtotal").value = subtotal.toFixed(2);
    total_iva = subtotal * iva;    
    document.getElementById("txtIva_Ins").value = total_iva.toFixed(2);
 
    //1 = FACTURA VENTA
    //2 = FACTURA COMPRA
    
    if(factura == 1){ 
    document.getElementById("txtTotalFactura").value = (total_iva + subtotal).toFixed(2);
    totalOrdenesVenta=document.getElementById("totalOrdenVenta").value;  
    totalIva=totalOrdenesVenta*iva;
    totalOrdenesVenta2 = parseFloat(totalOrdenesVenta) + parseFloat(totalIva);
    
    document.getElementById('restaLabel').innerHTML ="Resta: $ "+(resta).toFixed(2);
    
//Aca empieza la magia
   
               alert('TOTAL TXT '+document.getElementById("totalOrdenVentatxt").value);
               alert('SUBTOTAL: '+subtotal);
   
    if(resta.toFixed(2)==0.00)
        {document.getElementById("btnConfirma").style.visibility = "visible"; }
    else{
            if(resta.toFixed(2)<0)
            {          
            
               alert("La factura supera el total aceptado");
               document.getElementById("btnConfirma").style.visibility = "hidden";}
           else{document.getElementById("btnConfirma").style.visibility = "hidden";}
        }
    } 
    // FACTURA COMPRA
    else {
    percepciones = parseFloat(document.getElementById("txtPercepciones").value);
    document.getElementById("txtTotalFactura").value =  subtotal + percepciones;      
    }
}

//ACTUALIZAR PARA NOTA DE CREDITO
function ActualizarTotalNotaCredito(cantidadDescripciones,factura){  
    iva = document.getElementById("comboIva").value;
    document.getElementById("btnConfirma").style.visibility = "visible"; 
    
    if (iva == 1){
         iva = 0.21;
    }else{
         iva = 0.105;   
         } 
     
    subtotal = 0;
    resta=0;
    resta=totalOrdenesVenta=document.getElementById("totalOrdenVentatxt").value; 
    numeroDescripcion=0;
    
        
        while(numeroDescripcion < cantidadDescripciones)
            {        
                numeroDescripcion++;
                if (validarSiNumeroYComa(document.getElementById("txtTotalItem"+numeroDescripcion).value))
                {
                     subtotal += parseFloat(document.getElementById("txtTotalItem"+numeroDescripcion).value); 
                     resta-=parseFloat(document.getElementById("txtTotalItem"+numeroDescripcion).value); 
                }else
                {document.getElementById("txtTotalItem"+numeroDescripcion).value="0.00";}
            }   //Fin_While
    
    
    
    document.getElementById("txtSubtotal").value = subtotal.toFixed(2);
    total_iva = subtotal * iva;    
    document.getElementById("txtIva_Ins").value = total_iva.toFixed(2);
 
    //1 = FACTURA VENTA
    //2 = FACTURA COMPRA
    
    if(factura == 1){ 
    document.getElementById("txtTotalFactura").value = (total_iva + subtotal).toFixed(2);
    totalOrdenesVenta=document.getElementById("totalOrdenVenta").value;  
    totalIva=totalOrdenesVenta*iva;
    totalOrdenesVenta2 = parseFloat(totalOrdenesVenta) + parseFloat(totalIva);
    
    document.getElementById('restaLabel').innerHTML ="Resta: $ "+(resta).toFixed(2);
    
//Aca empieza la magia
   

            if(resta.toFixed(2)<0)
            {          
            
               alert("La factura supera el total aceptado");
               document.getElementById("btnConfirma").style.visibility = "hidden";}

    } 
    // FACTURA COMPRA
    else {
    percepciones = parseFloat(document.getElementById("txtPercepciones").value);
    document.getElementById("txtTotalFactura").value =  subtotal + percepciones;      
    }
}








// otro actualizar solo de factura compra

function ActualizarTotalFacturaCompra(cantidadDescripciones,factura){
    
    
    iva = document.getElementById("comboIva").value;
    document.getElementById("btnConfirma").style.visibility = "visible"; 
    

    if (iva == 1){
         iva = 0.21;
    }else{
         iva = 0.105;   
         } 
     
    subtotal = 0;
    numeroDescripcion=0;
    
    while(numeroDescripcion < cantidadDescripciones)
    {
        
        numeroDescripcion++;
        if (validarSiNumeroYComa(document.getElementById("txtTotalItem"+numeroDescripcion).value))
        {
             subtotal += parseFloat(document.getElementById("txtTotalItem"+numeroDescripcion).value); 
        }else
        {document.getElementById("txtTotalItem"+numeroDescripcion).value="0.00";}
    }
    document.getElementById("txtSubtotal").value = subtotal.toFixed(2);
    
    total_iva = subtotal * iva;    
    document.getElementById("txtIva_Ins").value = total_iva.toFixed(2);
 
    //1 = FACTURA VENTA
    //2 = FACTURA COMPRA
    
    if(factura == 1){ 
    document.getElementById("txtTotalFactura").value = (total_iva + subtotal).toFixed(2);
    totalOrdenesVenta=document.getElementById("totalOrdenVenta").value;  
    totalIva=totalOrdenesVenta*iva;
    totalOrdenesVenta2 = parseFloat(totalOrdenesVenta) + parseFloat(totalIva);
    document.getElementById('totalLabel').innerHTML ="Total Órdenes venta: $ "+(totalOrdenesVenta2).toFixed(2);
    if(document.getElementById("txtTotalFactura").value==totalOrdenesVenta2)
        {document.getElementById("btnConfirma").style.visibility = "visible"; }
        else{alert("La factura no iguala el total aceptado");
          document.getElementById("btnConfirma").style.visibility = "hidden"; }
    } else {
    percepciones = parseFloat(document.getElementById("txtPercepciones").value);
    document.getElementById("txtTotalFactura").value =  subtotal + percepciones;      
    }
}


function actualizarIva(factura){
    

    iva = document.getElementById("comboIva").value;
    
    if (iva == 1){
           iva = 0.21;
            }else{
                iva = 0.105;   
                } 
         
         subtotal = document.getElementById("txtSubtotal").value;
         total_iva = subtotal * iva;
         document.getElementById("txtIva_Ins").value = total_iva;
         
         //1 = FACTURA VENTA
         //2 = FACTURA COMPRA
            if (factura == 1)
            {
                document.getElementById("txtTotalFactura").value = (parseFloat(total_iva) + parseFloat(subtotal)).toFixed(2);
            
                    totalOrdenesVenta=document.getElementById("totalOrdenVenta").value;  
                     totalIva=totalOrdenesVenta*iva;
                     totalOrdenesVenta2 = parseFloat(totalOrdenesVenta) + parseFloat(totalIva);
                     //document.getElementById('totalLabel').innerHTML ="Total Órdenes venta: $ "+(totalOrdenesVenta2).toFixed(2);
                     /*
                     if(document.getElementById("txtTotalFactura").value>totalOrdenesVenta2)
                     {
                         alert("La factura supera el monto total aceptado");
                         document.getElementById("btnConfirma").style.visibility = "hidden"; 
                     }else{document.getElementById("btnConfirma").style.visibility = "visible";} */
            }
            //FACTURA COMPRA
            else
                document.getElementById("txtTotalFactura").value = parseFloat(total_iva) + parseFloat(subtotal) +  parseFloat(document.getElementById("txtPercepciones").value);    
}

function mostrarCuenta(id,nombre){
    
    if(confirm('¿Confirma eliminar Cliente: '+nombre+' ?')==true)
    {
        window.location="delete-clientes.php?cli_id="+id;       
       
    }
}


function mostrarMensaje(mensaje){
    alert(mensaje);
}

function actualizarListadoAbono(id){
        nombreAbono=document.getElementById("nombre_abono").value;
        window.location="form-alta-abonos.php?idMatriz="+id+"&nombreabono="+nombreAbono;       

}

function validarFacturacion(costo,venta,esAbono){

//Constantes de estado
//var estado= document.getElementById("est_id").value;

var estadoIngresoDeOrden                    = '1';
var estadoEnviadoProveedor                  = '2';     
var estadoAprobadoBajoCosto                 = '3';
var estadoEstadoRespuestaDelProveedor       = '4';
var estadoPresupuestoEnviadoAlCliente       = '5';
var estadoRechazadoParaRenegociacion        = '6';
var estadoRenegociacion                     = '7';
var estadoRechazado                         = '8';
var estadoAprobado                          = '9'; // verificar
var estadoConfirmacionAlProveedor           = '10';
var estadoFinalizadoPendienteFacturacion    = '11';
var estadoFinalizadoFacturado               = '12';
var estadoFinalizadoPago                    = '14'; //El estado 13 esta eliminado

var nombre='frm';
var estado= document.forms[nombre].est_idDetalle.value;
  

if((estado == 11)&(costo==0)&(venta==0))
    {
              
        if(esAbono!=1){ // si Abono no va a ocultar el boton guardar 
        document.getElementById("error").style.visibility = "visible"; 
        document.getElementById("guardarDetalle").style.visibility = "hidden";
        }
    }
    else
        {
           document.getElementById("error").style.visibility = "hidden"; 
           document.getElementById("guardarDetalle").style.visibility = "visible";
        }
          
 switch (estado)
{
  case estadoEnviadoProveedor:  {   
            document.forms[nombre].fecha_detalle.style.visibility = "visible";
            document.forms[nombre].fecha_detalle.disabled = false;
            document.getElementById("texto_respuesta_detalle").style.visibility = "visible";
            document.getElementById("texto_respuesta_detalle").textContent = "Fecha respuesta proveedor";
        
            /*MOSTRAR LABEL , CHECKBOX Y FECHA DE RECEPCION OT*/
            document.forms[nombre].fecha_ot.style.visibility = "visible";
            document.forms[nombre].fecha_ot.disabled = true;
            document.getElementById("texto_fecha_ot").style.visibility = "visible";
            document.getElementById("texto_fecha_ot").textContent = "Recepción OT : ";
            document.getElementById("ord_checkOT").style.visibility = "visible";
        
        
        
             /*LIMPIAR-OCULTAR PRESUPUESTO*/
            document.forms[nombre].txtPresupuesto.style.visibility = "hidden";
            document.getElementById("texto_presupuesto_detalle").style.visibility = "hidden";

            }
            break;
  case estadoConfirmacionAlProveedor: {
            document.forms[nombre].fecha_detalle.style.visibility = "visible";
            document.forms[nombre].fecha_detalle.disabled = false;
            document.getElementById("texto_respuesta_detalle").style.visibility = "visible";
            document.getElementById("texto_respuesta_detalle").textContent = "Plazo de finalizacion";
            
            /*MOSTRAR LABEL Y FECHA DE RECEPCION OT*/
            document.forms[nombre].fecha_ot.style.visibility = "visible";
            document.forms[nombre].fecha_ot.disabled = true;
            document.getElementById("texto_fecha_ot").style.visibility = "visible";
            document.getElementById("texto_fecha_ot").textContent = "Recepción OT : ";
            document.getElementById("ord_checkOT").style.visibility = "visible";
            
            
            
            
             /*LIMPIAR-OCULTAR PRESUPUESTO*/
            document.forms[nombre].txtPresupuesto.style.visibility = "hidden";
            document.getElementById("texto_presupuesto_detalle").style.visibility = "hidden";

        }   
            break;
  case estadoAprobadoBajoCosto: {  
            document.forms[nombre].fecha_detalle.style.visibility = "visible";
            document.forms[nombre].fecha_detalle.disabled = false;
            document.getElementById("texto_respuesta_detalle").style.visibility = "visible";
            document.getElementById("texto_respuesta_detalle").textContent = "Fecha aprobado : ";
           
           /*MOSTRAR LABEL Y FECHA DE RECEPCION OT*/
            document.forms[nombre].fecha_ot.style.visibility = "visible";
            document.forms[nombre].fecha_ot.disabled = true;
            document.getElementById("texto_fecha_ot").style.visibility = "visible";
            document.getElementById("texto_fecha_ot").textContent = "Recepción OT : ";
            document.getElementById("ord_checkOT").style.visibility = "visible";
           
           
             /*LIMPIAR-OCULTAR PRESUPUESTO*/
            document.forms[nombre].txtPresupuesto.style.visibility = "hidden";
            document.getElementById("texto_presupuesto_detalle").style.visibility = "hidden";
        }  
            break;
  case estadoFinalizadoPendienteFacturacion:{
            document.forms[nombre].fecha_detalle.style.visibility = "visible";
            document.forms[nombre].fecha_detalle.disabled = false;
            document.getElementById("texto_respuesta_detalle").style.visibility = "visible";
            document.getElementById("texto_respuesta_detalle").textContent = "Fecha Finalizado : ";
            
            /*MOSTRAR LABEL Y FECHA DE RECEPCION OT*/
            document.forms[nombre].fecha_ot.style.visibility = "visible";
            document.forms[nombre].fecha_ot.disabled = true;
            document.getElementById("texto_fecha_ot").style.visibility = "visible";
            document.getElementById("texto_fecha_ot").textContent = "Recepción OT : ";
            document.getElementById("ord_checkOT").style.visibility = "visible";
            
            /*LIMPIAR-OCULTAR PRESUPUESTO*/
            document.forms[nombre].txtPresupuesto.style.visibility = "hidden";
            document.getElementById("texto_presupuesto_detalle").style.visibility = "hidden";
            
        }
            break;
 case estadoPresupuestoEnviadoAlCliente: { 
            document.forms[nombre].txtPresupuesto.style.visibility = "visible";
            document.forms[nombre].txtPresupuesto.disabled = false;
            document.getElementById("texto_presupuesto_detalle").style.visibility = "visible";
            document.getElementById("texto_presupuesto_detalle").textContent = "Nro. Presupuesto  ";
            
            
             /*MOSTRAR LABEL Y FECHA DE RECEPCION OT*/
            document.forms[nombre].fecha_ot.style.visibility = "visible";
            document.forms[nombre].fecha_ot.disabled = true;
            document.getElementById("texto_fecha_ot").style.visibility = "visible";
            document.getElementById("texto_fecha_ot").textContent = "Recepción OT : ";
            document.getElementById("ord_checkOT").style.visibility = "visible";
            
            
            /*LIMPIAR-OCULTAR FECHA*/
            document.forms[nombre].fecha_detalle.style.visibility = "hidden";
            document.forms[nombre].fecha_detalle.disabled = true;
            document.getElementById("texto_respuesta_detalle").style.visibility = "hidden";
            
        } break;
            
            
  default:  {
            document.forms[nombre].fecha_detalle.style.visibility = "hidden";
            document.forms[nombre].fecha_detalle.disabled = true;
            document.getElementById("texto_respuesta_detalle").style.visibility = "hidden";
            
            document.forms[nombre].txtPresupuesto.style.visibility = "hidden";
            document.forms[nombre].txtPresupuesto.disabled = true;
            document.getElementById("texto_presupuesto_detalle").style.visibility = "hidden";
            
            if (estado != estadoIngresoDeOrden && estado != estadoRechazado && estado != estadoRechazadoParaRenegociacion && estado != estadoRenegociacion){
                       
                         /*MOSTRAR LABEL Y FECHA DE RECEPCION OT*/
                        document.forms[nombre].fecha_ot.style.visibility = "visible";
                        document.forms[nombre].fecha_ot.disabled = true;
                        document.getElementById("texto_fecha_ot").style.visibility = "visible";
                        document.getElementById("texto_fecha_ot").textContent = "Recepción OT : ";
                        document.getElementById("ord_checkOT").style.visibility = "visible";
                } else {
                        /*OCULTAR LABEL Y FECHA DE RECEPCION OT*/
                        
                        document.forms[nombre].fecha_ot.style.visibility = "hidden";
                        document.forms[nombre].fecha_ot.disabled = true;
                        document.getElementById("texto_fecha_ot").style.visibility = "hidden";
                        document.getElementById("texto_fecha_ot").textContent = "Recepción OT : "; 
                        document.getElementById("ord_checkOT").style.visibility = "hidden";
                    
                    
                }
                
                
            
            
            
        }   
} 
       
}


function validarAdelanto(costo){
// validacion q no este vacio
valorAdelanto=document.getElementById("ord_det_monto").value;
if((document.getElementById("ord_det_monto").value == ''))
    document.getElementById("ord_det_monto").value = 0;
if((document.getElementById("ord_det_monto").value > costo))
    {
        document.getElementById("errorAdelanto").style.visibility = "visible"; 
    //    document.getElementById("guardarDetalle").style.visibility = "hidden";
    }
    else
        {
          if(confirm('¿Confirma guardar con Adelanto: $ '+valorAdelanto+' ?')==true)
            {
                  document.getElementById("errorAdelanto").style.visibility = "hidden"; 
                  document.getElementById("guardarDetalle").style.visibility = "visible";
                  document.getElementById("altaordenDetalle").submit();
             }
        }
}

// PASAR Cli_Id
function refrescarDatosDeCliente(id){ 
    codigoFactura=document.getElementById('cod_factura').value;
    fechaalta=document.getElementById('fechaalta').value;
    if(document.getElementById('cli_id').selectedIndex != 0){
                        window.location="ver-generar-factura-nueva.php?cli_id="+id+"&ocultar=si&cod_factura="+codigoFactura+"&fechaalta="+fechaalta;  
                            } else {
                        window.location="ver-generar-factura-nueva.php?cli_id=0&ocultar=si";  
                                
                            }
}

// verificarCheckBoxs
function verificarCheckboxs(cantTotalCheckboxs,id){
    i=0;
    //borrar si se saca fecha y codigo de factura
    cod_factura=document.getElementById("cod_factura").value;
    fechaalta=document.getElementById("fechaalta").value;
    //
    continua=false;
    url="ver-generar-factura-nueva.php?cli_id="+id+"&cod_factura="+cod_factura+"&fechaalta="+fechaalta;
    elementoOrden=0;
    condicionveanta=document.getElementById("condicion_venta").value;
    remito=document.getElementById("txtRemito").value;
        while (i<cantTotalCheckboxs)
        {
            i++;
            if(eval("document.frmGenerarFactura.checkbox_ord_id"+i+".checked"))
                {
                   continua=true;
                } 
        }
    if(continua){
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;       
            if(eval("document.frmGenerarFactura.checkbox_ord_id"+i+".checked"))
                {
                   elementoOrden++; 
                   url+="&o"+elementoOrden+"="+eval("document.frmGenerarFactura.checkbox_ord_id"+i+".value"); 
                } 
                else
                    {
                        eval("document.frmGenerarFactura.checkbox_ord_id"+i+".style.visibility = 'hidden'")
                    }
        }
        url+="&cant="+elementoOrden+"&condicionventa="+condicionveanta+"&remito="+remito+"&ocultar=no";      
   window.location=url;  
    }
    else
        {
            alert("debe seleccionar una orden");
        }
}



// verificarCheckBoxs del nuevo POST
function verificarCheckboxsNuevo(cantTotalCheckboxs,id){
    i=0;
    //borrar si se saca fecha y codigo de factura
    cod_factura=document.getElementById("cod_factura").value;
    fechaalta=document.getElementById("fechaalta").value;
    //
    continua=false;
    elementoOrden=0;
    condicionveanta=document.getElementById("condicion_venta").value;
    remito=document.getElementById("txtRemito").value;
        while (i<cantTotalCheckboxs)
        {
            i++;
            if(eval("document.formferificadorOrdenes.checkbox_ord_id"+i+".checked"))
                {
                   continua=true;
                } 
        }
    if(continua){
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;       
            if(eval("document.formferificadorOrdenes.checkbox_ord_id"+i+".checked"))
                {
                   elementoOrden++; 
                } 
                else
                    {
                        eval("document.formferificadorOrdenes.checkbox_ord_id"+i+".style.visibility = 'hidden'")
                    }
        }       
        document.getElementById("formferificadorOrdenes").action="verificador-generar-factura-nueva.php?cli_id="+id+"&cod_factura="+cod_factura+"&fechaalta="+fechaalta+"&cant="+elementoOrden+"&cantTotal="+cantTotalCheckboxs+"&condicionventa="+condicionveanta+"&remito="+remito+"&ocultar=no";
       document.getElementById("formferificadorOrdenes").submit();
    }
    else
        {
            alert("debe seleccionar una orden");
        }
}



// verificarCheckBoxs del nuevo POST
function verificarCheckboxsFavPagos(cantTotalCheckboxs){
    i=0; 
    continua=false;
        while (i<cantTotalCheckboxs)
        {
            i++;
            if(eval("document.formVerificadorFacturas.chckCodFactura"+i+".checked"))
                {
                   continua=true;
                } 
        }
    if(continua){   
       document.getElementById("formVerificadorFacturas").submit();
    }
    else
        {
            alert("Debe seleccionar al menos una Factura");
        }
}

function verificarCheckboxsDeForm(cantTotalCheckboxs,formulario,nombre_checkbox){
    i=0; 
    continua=false;
        while (i<cantTotalCheckboxs)
        {
            i++;
            if(eval("document."+formulario+"."+nombre_checkbox+i+".checked"))
                {
                   continua=true;
                } 
        }
    if(continua){   
       document.getElementById(formulario).submit();
    }
    else
        {
            alert("Debe seleccionar al menos una Factura");
        }
}

// verificarCheckBoxs


function verificarCheckboxsAbono(cantTotalCheckboxs,id){
    i=0;
    continua=false;
    nombre_abono=document.getElementById("nombre_abono").value;
    url="form-alta-abonos.php?idMatriz="+id+"&nombreabono="+nombre_abono;
    elementoOrden=0;
    

        while (i<cantTotalCheckboxs)// verifica q almenos un check estre tildado
        {
            i++;
            if(eval("document.formSelectSucursales.checkbox_sucursal_id"+i+".checked"))
                {
                   continua=true;
                } 
        }
    if(continua){
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;       
            if(eval("document.formSelectSucursales.checkbox_sucursal_id"+i+".checked"))
                {
                   elementoOrden++; 
                   url+="&suc_check"+elementoOrden+"="+eval("document.formSelectSucursales.checkbox_sucursal_id"+i+".value"); 
                } 
                else
                    {
                        eval("document.formSelectSucursales.checkbox_sucursal_id"+i+".style.visibility = 'hidden'")
                    }
        }
        url+="&cant="+elementoOrden+"&fase=2";      
   window.location=url;  
    }
    else
        {
            alert("Debe seleccionar una Sucursal");
        }
}
function CheckboxsSeleccionarTodos(cantTotalCheckboxs){
    elementoOrden=0;
    resultado=eval("document.formSelectSucursales.checkbox_SelectAll.checked");
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;       
           eval("document.formSelectSucursales.checkbox_sucursal_id"+i+".checked="+resultado);
        }
    }
    
    function habilitarModuloEstado(){
    resultado=eval("document.frm.checkCambiarEstado.checked");    
    resultado=!resultado;
           document.getElementById("ord_descripcionDetalle").disabled = resultado;  
           document.getElementById("checkPortadaDescripcion").disabled = resultado; 
           document.getElementById("est_idDetalle").disabled = resultado; 
           document.getElementById("ord_det_monto").disabled = resultado; 
           document.getElementById("userfile").disabled = resultado; 
           document.getElementById("checkPortada").disabled = resultado; 
           document.getElementById("fecha_detalle").disabled = resultado; 
           if(!resultado)
               document.getElementById("ord_descripcionDetalle").focus();
        
    }
    
function CheckboxsSeleccionarTodosFacturaVenta(cantTotalCheckboxs){
    elementoOrden=0;  
    resultado=document.getElementById('checkbox_SelectAll').checked; 
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;      
           eval("document.formferificadorOrdenes.checkbox_ord_id"+i+".checked="+resultado); 
        }
    }
    
 function CheckboxsSeleccionarTodosFacturaVentaFav(cantTotalCheckboxs){
    elementoOrden=0;  
    resultado=document.getElementById('checkbox_SelectAll').checked; 
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;      
           eval("document.formVerificadorFacturas.chckCodFactura"+i+".checked="+resultado); 
        }
    }

function verificarCheckboxsFacturaCompra(cantTotalCheckboxs,id){
    i=0;
    continua=false;
    url="form-alta-compra.php?comboProveedor="+id;
    elementoOrden=0;
        while (i<cantTotalCheckboxs)
        {
            i++;
            if(eval("document.frmGenerarFactura.checkbox_ord_id"+i+".checked"))
                {
                   continua=true;
                } 
        }
    if(continua){ 
    i=0;
    while (i<cantTotalCheckboxs)
        {
            i++;       
            if(eval("document.frmGenerarFactura.checkbox_ord_id"+i+".checked"))
                {
                   elementoOrden++; 
                   url+="&ord_check"+elementoOrden+"="+eval("document.frmGenerarFactura.checkbox_ord_id"+i+".value"); 
                } 
                else
                    {
                        eval("document.frmGenerarFactura.checkbox_ord_id"+i+".style.visibility = 'hidden'")
                    }
        }
        url+="&cant="+elementoOrden+"&ocultar=no";      
   window.location=url;  
    }
    else
        {
            alert("debe seleccionar una orden");
        }
}

/* ALERTA DE PLAZOS*/

function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=1050,height=500,scrollbars=yes,toolbar=1');

return false;
}


/* PASAR focus de CUIT*/

function pasaSiguiente(actual, siguiente, longitud)
  {
      numero = actual.value;
     if (!/^([0-9])*$/.test(numero))
     {
        actual.value = numero.substring(0,numero.length-1);
     }
     else
         {

         if((actual.value.length + 1) == longitud)
                siguiente.focus();
         }
  }
  
  
  function validarCombosDeUbicacion(){
      
     if(document.getElementById('select1').selectedIndex == 0){
         window.alert("Debe seleccionar una provincia");
         return false;
     }
     
     if(document.getElementById('select2').selectedIndex == 0){
         window.alert("Debe seleccionar un partido");
         return false;
     }
     
     if(document.getElementById('select3').selectedIndex == 0){
         window.alert("Debe seleccionar una localidad");
         return false;
     }
 
     return true;     
      
  }
  
    function validarCombosDeUbicacionSoloProvincia(){
      
     if(document.getElementById('select1').selectedIndex == 0){
         window.alert("Debe seleccionar una provincia");
         return false;
     }
     
 
     return true;     
      
  }

function PedirConfirmacionNotaCredito(mensaje){
    // BORRRAR CUANDO SE QUITE LO DE FECHAS SINO LAS PERAS
    codigonota=document.getElementById('cod_nota_credito').value;
    fechaalta=document.getElementById('fechaalta').value;
    
    
    
        
    if (codigonota=='')
        {
            alert('Debe ingresar un número de NC');
            document.getElementById('cod_nota_credito').focus();
            return false;
        }
                
    document.getElementById('cod_notaOculto').value=codigonota;
    document.getElementById('fechaaltaOculto').value=fechaalta;
    
    
    
    if((confirm('¿Confirma '+mensaje+' ?'))==true)
    {
        return true;
    }else{
        return false;
        
    }
}


  // Confirmacion de factura nueva
function PedirConfirmacionFacturaVenta(nombre,frm){
    // BORRRAR CUANDO SE QUITE LO DE FECHAS SINO LAS PERAS
    codigofac=document.getElementById('cod_factura').value;
    fechaalta=document.getElementById('fechaalta').value;
    otrocodigo=document.getElementById('cod_factura').value;
    venta=document.getElementById('condicion_venta').value;
    remito=document.getElementById('txtRemito').value;
    
    if (remito=='')
        {
            alert('Debe ingresar Remito');
            document.getElementById('txtRemito').focus();
            return false;
        }
        
    if (venta=='')
        {
            alert('Debe ingresar condición venta');
            document.getElementById('condicion_venta').focus();
            return false;
        }
            if (otrocodigo=='')
        {
            alert('Debe ingresar código de factura');
            document.getElementById('cod_factura').focus();
            return false;
        }
    
    document.getElementById('codFactura').value=codigofac;
    document.getElementById('fechaaltaOculto').value=fechaalta;
    document.getElementById('cod_facturaOculto').value=otrocodigo;
    document.getElementById('condicion_ventaOculto').value=venta;
    document.getElementById('txtRemitoOculto').value=remito;
    
    if((confirm('¿Confirma '+nombre+' ?'))==true)
    {
        //document.getElementById(frm).submit();
        return true;
    }else{
        return false;
        
    }
}
  
  
// Confirmacion de factura nueva
function PedirConfirmacion(nombre,frm){
    
    
    if((confirm('¿Confirma '+nombre+' ?'))==true)
    {
        //document.getElementById(frm).submit();
        return true;
    }else{
        return false;
        
    }
}

// eliminar generico

function eliminarItem(id,nombre,url){
    
    if(confirm('¿Confirma eliminar: '+nombre+' ?')==true)
    {
        window.location=url+id;   
    }
}

function validaSeleccione(id,mensaje,chek){
    if(chek.checked ==1){
    if (document.getElementById(id).selectedIndex == 0){
            alert (mensaje);
            return false;
        }
        }
        return true;
  
}

function actualizarDetallePago(ord_venta,cantidadTipoPago,cantIIBB){
    
    //alert("Ord venta "+ord_venta);

    ganancias   = parseFloat(document.getElementById("txtImporte1").value);
    iva         = parseFloat(document.getElementById("txtImporte2").value);
    suss        = parseFloat(document.getElementById("txtImporte3").value);
   // iibb        = parseFloat(document.getElementById("txtImporte4").value);
    //cargamos los requerimientos IIBB
        iibb=0.00;
        numeroRetencion=4;
     for ($i = 1; $i <= cantIIBB; $i++) 
     {
         unImporteIIBB    = parseFloat(document.getElementById("txtImporte"+numeroRetencion).value);
          iibb=(parseFloat(iibb)+parseFloat(unImporteIIBB) ).toFixed(2);
          numeroRetencion++;
     }
    
    /* Completamos los datos */
    totalDeposito=0.00;
     for ($i = 1; $i <= cantidadTipoPago; $i++) 
     {
         deposito    = parseFloat(document.getElementById("txtImportePago"+$i).value);
          totalDeposito=(parseFloat(totalDeposito)+parseFloat(deposito) ).toFixed(2);
     }
    document.getElementById("txtDeposito").value    = (parseFloat(totalDeposito)).toFixed(2);
    document.getElementById("txtGanancias").value   = (parseFloat(ganancias)).toFixed(2);
    document.getElementById("txtIva").value         = (parseFloat(iva)).toFixed(2);
    document.getElementById("txtIIBB").value        = (parseFloat(iibb)).toFixed(2);
    document.getElementById("txtSUSS").value        = (parseFloat(suss)).toFixed(2);
    
    depositoActual=document.getElementById("txtDeposito").value;
    document.getElementById("txtTotal").value = (parseFloat(ganancias) +parseFloat(iva) + parseFloat(iibb) +parseFloat(suss)+parseFloat(depositoActual)).toFixed(2);
    total = parseFloat(document.getElementById("txtTotal").value);
    
    
    //alert("Ord venta: "+ord_venta+" total: "+total);   
    if (parseFloat(ord_venta) != total)
        document.getElementById("botonRegistrar").style.visibility = "hidden";
    else
        document.getElementById("botonRegistrar").style.visibility = "visible";   
}
  function generarTipoPago(idForm,operacion)
{    if(operacion=='suma')
        document.getElementById("cantidadTip").value++;
    else
        {
        if(document.getElementById("cantidadTip").value>1)           
          document.getElementById("cantidadTip").value--;      
        }
        document.getElementById(idForm).submit();
}

  function generarIIBB(idForm,operacion)
{    if(operacion=='suma')
        document.getElementById("cantidadIIBB").value++;
    else
        {
        if(document.getElementById("cantidadIIBB").value>1)           
          document.getElementById("cantidadIIBB").value--;      
        }
        document.getElementById(idForm).submit();
}