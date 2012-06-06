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
 if (validarSiNumero(usuario))
 { 
 leer_doc(url);
 }
 else
     {
         document.getElementById("ord_codigo").value='';
     }
}

function autenticaCUIT(){
 usuario = document.getElementById("prv_cuit").value;
 url = "existeNumeroCuit.php?usuario=" + usuario;
 leer_doc(url);
 document.getElementById("prv_cuit").value=(usuario.substring(0,11));
}

function autenticaClienteCUIT(){
 usuario = document.getElementById("cli_cuit").value;
 url = "existeNumeroCuitCliente.php?usuario=" + usuario;
 leer_doc(url);
 document.getElementById("cli_cuit").value=(usuario.substring(0,11));
}

function VerificarProveedor(){
    
 usuario = document.getElementById("prv_id").value;
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

// Funciones llamdas del Form
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


function validarSoloNumero(object)
{
numero = object.value;
if (!/^([0-9])*$/.test(numero))
object.value = numero.substring(0,numero.length-1);
}

// ENGANCHES a VALIDADOREs-----------------------------
function validarCostoDeLaOrden(){
validarSoloNumero(document.getElementById("ord_costo"));
}

function validarVentaDeLaOrden(){
validarSoloNumero(document.getElementById("ord_venta"));
}