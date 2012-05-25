<?php
	header('Content-Type: text/html; charset=utf-8');
	session_start();
	if($_SESSION["login"]!="ok"){
			header("location:index.php?error=1");
	}
?>