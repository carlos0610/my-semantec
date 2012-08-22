<?php
	function getFilesWithId($id){
		 $sql = "SELECT `id`, `file_name`, `file_type`, `file_size`, `file_content`, `tabla`, `publico` 
                     FROM `files` 
                     WHERE `id`=$id";
             return       $abono = mysql_query($sql);
             
	}
        
        function getFilePortalWithId($id){
                 $res='-';
                 $abono = getFilesWithId($id);
                 if($abono){
                 $fila = mysql_fetch_array($abono);
                 if($fila['publico']==1)
                     $res='Sí';
                 else
                     $res='No';}
                 return   $res;  
             
	}
?>
