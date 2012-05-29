<?php

include ("conexion.php");

$proc = mssql_init('usp_registrar_factura',$conn);
mssql_bind($proc,'@ParameterOne',$ParameterOne,SQLVARCHAR);
mssql_bind($proc,'@ParameterTwo',$ParameterTwo,SQLVARCHAR);
mssql_bind($proc,'@ParameterThree',$ParameterThree,SQLVARCHAR);
if ($result = mssql_execute($proc)) 
?>
