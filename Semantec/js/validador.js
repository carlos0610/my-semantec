/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var req;
var respuesta;



function leer_doc(url) {
 req = false;
 
 // Llama objeto XMLHttpRequest
 if (window.XMLHttpRequest) {
   req = new XMLHttpRequest();
   if (req.overrideMimeType) {
     req.overrideMimeType('text/xml'); 
   }
 
 // Si no funciona intenta utiliar el objeto IE/Windows ActiveX 
 } else if (window.ActiveXObject) {
   req = new ActiveXObject("Microsoft.XMLHTTP"); 
 }
 
 if(req!=null){
   req.onreadystatechange = procesarRespuesta;
   req.open('GET', url, true);
   req.send(null);
 } 
 
}

function procesarRespuesta(){
    //NOTA: para q funcione correctamente no olvidarse q deben existir los id
 respuesta = req.responseXML; 
 var existe = respuesta.getElementsByTagName('existe').item(0).firstChild.data; 
   if (existe=="true")
   { 
    document.getElementById("botonAgregar").style.visibility = "hidden";
    document.getElementById("error").style.visibility = "visible";   
   }
   else
   {
     document.getElementById("botonAgregar").style.visibility = "visible";
     document.getElementById("error").style.visibility = "hidden";
   }
}

// Funciones llamdas del Form
function autentica(){
    
 usuario = document.getElementById("ord_codigo").value;
 url = "existeNumeroOrden.php?usuario=" + usuario;
 leer_doc(url);
 
 
}

function autenticaCUIT(){
    usuario = document.getElementById("cuit_parteA").value+
    document.getElementById("cuit_parteB").value+
    document.getElementById("cuit_parteC").value ;
    url = "existeNumeroCuit.php?usuario=" + usuario;
 leer_doc(url);
 document.getElementById("prv_cuit").value=(usuario.substring(0,11));
}

function autenticaClienteCUIT()
{
    if(document.getElementById("chkSucursal").checked==false)
    {
    
          usuario = document.getElementById("cli_cuit_parteA").value+
           document.getElementById("cli_cuit_parteB").value+
           document.getElementById("cli_cuit_parteC").value
            ;
 
            url = "existeNumeroCuitCliente.php?usuario=" + usuario;
            leer_doc(url);
        document.getElementById("cli_cuit").value=(usuario.substring(0,11));
        
    }
}

function VerificarProveedor(){
    
 usuario = document.getElementById("prv_idEdit").value;
 url = "esProveedorSinAsignar.php?usuario=" + usuario;
 document.getElementById("provedor_id").value = usuario;
 leer_doc(url);
}
//---------------------------ALTA COMPRA-------------------------------------
function leer_doc2(url) {
 req = false;
 // Llama objeto XMLHttpRequest
 if (window.XMLHttpRequest) {
   req = new XMLHttpRequest();
   if (req.overrideMimeType) {
     req.overrideMimeType('text/xml'); 
   }
 
 // Si no funciona intenta utiliar el objeto IE/Windows ActiveX 
 } else if (window.ActiveXObject) {
   req = new ActiveXObject("Microsoft.XMLHTTP"); 
 }

 if(req!=null){
   req.onreadystatechange = procesarRespuesta2;
   req.open('GET', url, true);
   req.send(null);
 } 
 
}

function procesarRespuesta2(){
    //NOTA: para q funcione correctamente no olvidarse q deben existir los id
   
 respuesta = req.responseXML;
 var existe = respuesta.getElementsByTagName('existe').item(0).firstChild.data;
     
   if (existe=="true")
   { 
    document.getElementById("incorrecto").style.visibility = "hidden";
    document.getElementById("correcto").style.visibility = "visible";   
    document.getElementById("btnConfirma").style.visibility = "visible"; 
   }
   else
   {
     document.getElementById("incorrecto").style.visibility = "visible";
     document.getElementById("correcto").style.visibility = "hidden";
      document.getElementById("btnConfirma").style.visibility = "hidden"; 
   }
}

// Funciones llamdas del Form  DE FACTURA!
function autenticaOrden(){
 usuario = document.getElementById("id_orden").value;
 url = "existeNumeroOrden.php?usuario=" + usuario;
 leer_doc2(url);
}

//---------------------------llenar datos de Cliente en Factura-------------------------------------
function leer_doc3(url) {
 req = false;
 // Llama objeto XMLHttpRequest
 if (window.XMLHttpRequest) {
   req = new XMLHttpRequest();
   if (req.overrideMimeType) {
     req.overrideMimeType('text/xml'); 
   }
 
 // Si no funciona intenta utiliar el objeto IE/Windows ActiveX 
 } else if (window.ActiveXObject) {
   req = new ActiveXObject("Microsoft.XMLHTTP"); 
 }

 if(req!=null){
   req.onreadystatechange = procesarRespuesta3;
   req.open('GET', url, true);
   req.send(null);
 } 
 
}

function procesarRespuesta3(){
    //NOTA: para q funcione correctamente no olvidarse q deben existir los id
   
 respuesta = req.responseXML;
 var existe = respuesta.getElementsByTagName('senores').item(0).firstChild.data;
document.getElementById('nombre').innerHTML = existe;
document.getElementById('domicilio').innerHTML = respuesta.getElementsByTagName('domicilio').item(0).firstChild.data;
document.getElementById('localidad').innerHTML = respuesta.getElementsByTagName('zona').item(0).firstChild.data;
document.getElementById('iva').innerHTML = respuesta.getElementsByTagName('iva_nombre').item(0).firstChild.data;
document.getElementById('cuit').innerHTML = respuesta.getElementsByTagName('cuit').item(0).firstChild.data;
}

// Funciones llamdas del Form
function rellenarDatosCliente(){   
    
var d = document;
var val = "ricardo";
var lab = d.getElementById('nombre');
lab.innerHTML = val;
    
    
 usuario = document.getElementById("cli_id").value;
 url = "getDatosDeCliente.php?usuario=" + usuario;
 leer_doc3(url);
}
// validadores GENERALES-----------------------------
function validarSiNumero(numero){
    ok=true;

if (!/^([0-9])*$/.test(numero)){
alert("El valor " + numero + " no es un número");
ok=false;
}
return ok;
}


/*function validarSoloNumero(object)
{
numero = object.value;
if (!/^([0-9])*$/.test(numero))
object.value = numero.substring(0,numero.length-1);
}  */
function validarSoloNumero(object)
{
numero = object.value;
var patron = /^[0-9]+[\.]$/;
if (!/^[0-9]+[\.]{0,1}\d*$/.test(numero)&& numero.search(patron))
object.value = numero.substring(0,numero.length-1);
}
// ENGANCHES a VALIDADOREs-----------------------------
function validarCostoDeLaOrden(){
validarSoloNumero(document.getElementById("ord_costo"));
}
function validarReal(nombreCampo){
validarSoloNumero(document.getElementById(nombreCampo));
}
function validarVentaDeLaOrden(){
validarSoloNumero(document.getElementById("ord_venta"));
}

function validarTotales(){
validarSoloNumeroyPunto(document.getElementById("ord_venta"));
}

//---------------------------llenar datos de Form Alta Cliente-------------------------------------
function leer_doc4(url) {
 req = false;
 // Llama objeto XMLHttpRequest
 if (window.XMLHttpRequest) {
   req = new XMLHttpRequest();
   if (req.overrideMimeType) {
     req.overrideMimeType('text/xml'); 
   }
 
 // Si no funciona intenta utiliar el objeto IE/Windows ActiveX 
 } else if (window.ActiveXObject) {
   req = new ActiveXObject("Microsoft.XMLHTTP"); 
 }

 if(req!=null){
   req.onreadystatechange = procesarRespuesta4;
   req.open('GET', url, true);
   req.send(null);
 } 
 
}

function procesarRespuesta4(){
    //NOTA: para q funcione correctamente no olvidarse q deben existir los id
    // para cargar en un label usar :.innerHTML en lugar de value.
    respuesta = req.responseXML;
    var existe = respuesta.getElementsByTagName('senores').item(0).firstChild.data;
    var cuit=respuesta.getElementsByTagName('cuit').item(0).firstChild.data;
    var direccion = respuesta.getElementsByTagName('direccion').item(0).firstChild.data;

document.getElementById('cli_nombre').value = existe;
document.getElementById('cli_direccion_fiscal').value = direccion;

        document.getElementById("cli_cuit_parteA").value=(cuit.substring(0,2));
        document.getElementById("cli_cuit_parteB").value=(cuit.substring(3,11));
        document.getElementById("cli_cuit_parteC").value=(cuit.substring(12,13));


document.getElementById('cli_cuit').value = respuesta.getElementsByTagName('cuit').item(0).firstChild.data;
document.getElementById('iva_id').selected = respuesta.getElementsByTagName('iva_id').item(0).firstChild.data;



}

// Funciones llamdas del Form
function rellenarDatosClienteSucursal(){
    if (document.getElementById("comboClientes").value != 0){
        usuario = document.getElementById("comboClientes").value;
        url = "getDatosDeClienteSucursal.php?usuario=" + usuario;
        leer_doc4(url);
 } else {
     limpiarFrmAltaCliente();
     
 }
}


