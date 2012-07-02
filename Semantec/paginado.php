<?php
 /* INICIO CALCULO PAGINADO */  ###############################################################################
    $result=mysql_query($sql0);
    $cant_registros=mysql_num_rows($result);

//pagina actual si no esta definida y l&iacute;mites
    if(!isset($_GET["pagina"]))
    {
       $pagina=1;
       $inicio=1;
       $final=$tamPag;
    }else{
       $pagina = $_GET["pagina"];
    }
    //c�lculo del l&iacute;mite inferior
    $limitInf=($pagina-1)*$tamPag;

    //c�lculo del n�mero de p�ginas
    $numPags=ceil($cant_registros/$tamPag);
    if(!isset($pagina))
    {
       $pagina=1;
       $inicio=1;
       $final=$tamPag;
    }else{
       $seccionActual=intval(($pagina-1)/$tamPag);
       $inicio=($seccionActual*$tamPag)+1;

       if($pagina<$numPags)
       {
          $final=$inicio+$tamPag-1;
       }else{
          $final=$numPags;
       }

       if ($final>$numPags){
          $final=$numPags;
       }
    }
 /* FIN CALCULO PAGINADO */  ###############################################################################  

/* INICIO DETALLE PAGINADO */

function verPaginado($cant_registros, $pagina, $inicio, $final, $numPags){
    
    $mensaje="Se han encontrado ".$cant_registros." registros. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span class='pagination'>";

    if($pagina>1)
    {
     //  $mensaje.="<a href='".$_SERVER["PHP_SELF"]."?pagina=".($pagina-1)."'>";
        $mensaje.="<a href='#' onclick='transferirFiltros(".($pagina-1).")'>";
       $mensaje.="anterior";
       $mensaje.="</a>  &nbsp; ";
    }

    for($i=$inicio;$i<=$final;$i++)
    {
       if($i==$pagina)
       {
            if($numPags>1){
               $mensaje.="<strong>".$i."</strong> ";
            }
       }else{
         // $mensaje.=" <a href='".$_SERVER["PHP_SELF"]."?pagina=".$i."'>";
           $mensaje.=" <a href='#' onclick='transferirFiltros(".($i).")' >";
          $mensaje.=$i;
          $mensaje.="</a> ";
       }
    }
    if($pagina<$numPags)
   {
   //    $mensaje.=" &nbsp; <a href='".$_SERVER["PHP_SELF"]."?pagina=".($pagina+1)."'>";
         $mensaje.=" <a href='#' onclick='transferirFiltros(".($pagina+1).")' >";
       $mensaje.="siguiente";
       $mensaje.="</a> ";
   }
        $mensaje.="</span>";
   
   return $mensaje;
}
  /* FIN DETALLE PAGINADO */
?>