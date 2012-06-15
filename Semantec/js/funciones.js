
// BAJA de ORDEN
function eliminarOrden(id,nombre){
    if(confirm('Confirma Eliminar Orden: '+nombre+' ?')==true)
    {
        window.location="delete-ordenes.php?orden_id="+id;
    }
}

//BAJA de CLIENTE

function eliminarCliente(id,nombre){
    
    if(confirm('Confirma eliminar Cliente: '+nombre+' ?')==true)
    {
        window.location="delete-clientes.php?cli_id="+id;       
       
    }
}


function eliminarProveedor(id,nombre){
    
    if(confirm('Confirma eliminar proveedor: '+nombre+' ?')==true)
    {
        window.location="delete-proveedores.php?prv_id="+id;
    }
}
// BAJA Factura
function eliminarFactura(id){
    
    if(confirm('Confirma eliminar Factura: '+id+' ?')==true)
    {
        window.location="delete-factura.php?fav_id="+id;
    }
}


function pagarFactura(id,cuenta){
    
    if(confirm('¿Confirma el pago de la factura nro: '+id+'? ')==true)
    {
        window.location="pagar-factura.php?fav_id="+id+"&ccc_id="+cuenta;
        
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

function habilitarFiltros(nombre,gadet){
  if(document.getElementById(nombre).checked){ 
     document.getElementById(gadet).disabled=false;   
  }else{document.getElementById(gadet).disabled=true  ;}
   
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
    } else {
    percepciones = parseFloat(document.getElementById("txtPercepciones").value);
    document.getElementById("txtTotalFactura").value = total_iva + subtotal + percepciones;      
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
                document.getElementById("txtTotalFactura").value = parseFloat(total_iva) + parseFloat(subtotal);
            else
                document.getElementById("txtTotalFactura").value = parseFloat(total_iva) + parseFloat(subtotal) +  parseFloat(document.getElementById("txtPercepciones").value);    
}

function mostrarCuenta(id,nombre){
    
    if(confirm('Confirma eliminar Cliente: '+nombre+' ?')==true)
    {
        window.location="delete-clientes.php?cli_id="+id;       
       
    }
}

function validarFacturacion(costo,venta){

//Constantes de estado
var estadoEnviadoProveedor = 2;     
var estadoConfirmarProveedor = 10;

if((document.getElementById("est_id").value == 11)&(costo==0)&(venta==0))
    {
        document.getElementById("error").style.visibility = "visible"; 
        document.getElementById("guardarDetalle").style.visibility = "hidden";
    }
    else
        {
           document.getElementById("error").style.visibility = "hidden"; 
           document.getElementById("guardarDetalle").style.visibility = "visible";
        }
         
   /* SI EL ESTADO SELECCIONADO ES 'ENVIADO A PROVEEDOR' */
   if((document.getElementById("est_id").value == estadoEnviadoProveedor)){
       
       
            document.getElementById("fecha").style.visibility = "visible";
            document.getElementById("texto_respuesta").style.visibility = "visible";
            document.getElementById("texto_respuesta").textContent = "Fecha respuesta proveedor";
        }else if((document.getElementById("est_id").value != 9)) {
            document.getElementById("fecha").style.visibility = "hidden";
            document.getElementById("texto_respuesta").style.visibility = "hidden";           
        }  
               
        /* SI EL ESTADO SELECCIONADO ES 'APROBADO' */
   if((document.getElementById("est_id").value == estadoConfirmarProveedor)){
            document.getElementById("fecha").style.visibility = "visible";
            document.getElementById("texto_respuesta").style.visibility = "visible";
            document.getElementById("texto_respuesta").textContent = "Plazo de finalizacion";
        }else if((document.getElementById("est_id").value != 2)){
            document.getElementById("fecha").style.visibility = "hidden";
            document.getElementById("texto_respuesta").style.visibility = "hidden"; 
        }              
}


function validarAdelanto(costo){

if((document.getElementById("ord_det_monto").value > costo))
    {
        document.getElementById("errorAdelanto").style.visibility = "visible"; 
        document.getElementById("guardarDetalle").style.visibility = "hidden";
    }
    else
        {
           document.getElementById("errorAdelanto").style.visibility = "hidden"; 
           document.getElementById("guardarDetalle").style.visibility = "visible";
        }
}

// PASAR Cli_Id
function refrescarDatosDeCliente(id){
   window.location="ver-generar-factura-nueva.php?cli_id="+id+"&ocultar=si";  
}

// verificarCheckBoxs
function verificarCheckboxs(cantTotalCheckboxs,id){
    i=0;
    continua=false;
    url="ver-generar-factura-nueva.php?cli_id="+id;
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
                   url+="&ord_check"+elementoOrden+"="+eval("document.frmGenerarFactura.checkbox_ord_id"+i+".value"); 
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
