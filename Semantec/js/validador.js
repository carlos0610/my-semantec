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
 usuario = document.getElementById("prv_cuit").value;
 url = "existeNumeroCuit.php?usuario=" + usuario;
 leer_doc(url);
}

function autenticaClienteCUIT(){
 usuario = document.getElementById("cli_cuit").value;
 url = "existeNumeroCuitCliente.php?usuario=" + usuario;
 leer_doc(url);
}

function VerificarProveedor(){
 usuario = document.getElementById("prv_id").value;
 url = "esProveedorSinAsignar.php?usuario=" + usuario;
 leer_doc(url);
}

