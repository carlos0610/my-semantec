<?php

         function haveCuentaWhitPrvId($prv_id){  
                          
            $sql = "select cue_id 
                    from cuentabanco_prv 
                    where prv_id =$prv_id;";
           
            $cuenta = mysql_query($sql);                       
           $fila=mysql_fetch_array($cuenta);
           $codigo=$fila['cue_id'];  
           if(!empty($codigo)){
                return true;
            }
           return false;
           //return $codigo;
         }
?>
