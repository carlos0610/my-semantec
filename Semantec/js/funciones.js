
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
        
        
        
        
}else{
        document.forms[nombre].cue_nrobancaria.disabled=true;
        document.forms[nombre].cut_id.disabled=true;              //Deshabilitamos combobox Tipo cuenta
        document.forms[nombre].cue_cbu.disabled=true;             //Deshabilitamos campo de texto CBU
        document.forms[nombre].cut_id.selectedIndex=0;            //Combo vuelve a 'Seleccione'
        document.forms[nombre].cue_nrobancaria.value="";
        document.forms[nombre].cue_cbu.value="";
        
}

}


