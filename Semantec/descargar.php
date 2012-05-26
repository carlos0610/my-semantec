<?php
if(isset($_GET['id']))
{
// SI ELIGIO DESCARGAR EL ARCHIVO
include("conexion.php"); 
    
$id    = $_GET['id'];
$query = "SELECT file_name, file_type, file_size, file_content " .
         "FROM files WHERE id = '$id'";

$result = mysql_query($query) or die('Error, query failed');
list($name, $type, $size, $content) = mysql_fetch_array($result);

header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: attachment; filename=$name");
echo $content;
mysql_close();

exit;
}

?>
