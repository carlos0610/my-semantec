
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


