function editarOrdenDetalle(orden_id){
//	document.getElementById("frm").ord_id2.value=orden_id;
//	document.getElementById("frm").action="form-alta-ordenes-detalle.php?ord_id="+orden_id;
        document.getElementById("frm").action="form-alta-ordenes-detalle.php?ord_id="+document.getElementById("frm").ord_id.value;
//	alert(document.getElementById("frm").ord_id.value);
	document.getElementById("frm").submit();
}
// BAJA de ORDEN
function eliminarOrden(id,nombre){
    if(confirm('Confirma Eliminar Orden: '+nombre+' ?')==true)
    {
        window.location="delete-ordenes.php?orden_id="+id;
    }
}
