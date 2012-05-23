function editarRequerimiento(orden_id){
    alert(orden_id);
	document.getElementById("frm").ord_id2.value=orden_id;
	document.getElementById("frm").action="form-alta-ordenes-detalle.php";
	alert(document.getElementById("frm").ord_id.value);
	document.getElementById("frm").submit();
}