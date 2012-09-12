<?php
		function verCUIT($cuit){
				$salida = substr($cuit, 0,2)."-".substr($cuit, 2,8)."-".substr($cuit, -1);
				return $salida;
		}

		function gfecha($fecha){
			//04/04/2012-> 2012-04-04
			$salida = substr($fecha, -4)."-".substr($fecha, 3, 2)."-".substr($fecha, 0,2);
			return $salida;
		}
                
                function fechaConDia1($fecha){
			//04/04/2012-> 2012-04-01
			$salida = substr($fecha, -4)."-".substr($fecha, 3, 2)."-";
                        $salida.="01";
			return $salida;
		}

		function mfecha($fecha){
			//2012-04-04 -> 04/04/2012
			$salida = substr($fecha, -2)."/".substr($fecha, 5,2)."/".substr($fecha, 0,4);
			return $salida;
		}
                function tfecha($fecha){
			//2012-04-04 00:00:00-> 04/04/2012
			$salida = substr($fecha, 8,2)."/".substr($fecha, 5,2)."/".substr($fecha, 0,4);
			return $salida;
		}
                function formatoNumeroFactura($c) {
                        printf("%08d<br>",  $c);
                } 
                
                function gfechaBD($fecha){
			//04/04/2012-> 2012-04-04 con los '' para la BD
			$salida ="'".substr($fecha, -4)."-".substr($fecha, 3, 2)."-".substr($fecha, 0,2)."'";
			return $salida;
		}
                
?>