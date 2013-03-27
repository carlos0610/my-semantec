<?php
	$q=$_GET['q'];
	$my_data=mysql_real_escape_string($q);
        include ("../conexion.php");
	$mysqli=mysqli_connect($sever,$usuario,$clave,$base) or die("Database Error");
	$sql="SELECT ord_codigo FROM ordenes WHERE ord_codigo LIKE '%$my_data%' ORDER BY ord_codigo";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo $row['ord_codigo']."\n";
		}
	}
?>