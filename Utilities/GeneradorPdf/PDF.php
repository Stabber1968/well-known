<?php

include ("fpdf/fpdf.php");
class PDF extends FPDF 
  {
	  function llenarDescipciones($RangoLimite,$cadena)
	  {
	  	  $arr1 = array();
		  $arr = preg_split("/[\n]+/",$cadena);
		  //$wrapped = wordwrap(trim($cadena),$RangoLimite, '<br>');
		  //echo count($arr);
		  if(count($arr)==1){
		  //	$wrapped = wordwrap(trim($cadena),90, '<br>'); //JDSA
		  	$wrapped = wordwrap(trim($cadena),$RangoLimite, '<br>');
		  	$arr1 = explode('<br>', trim($wrapped));
		  }else{
			  for($i=0;$i<=count($arr)-1;$i++){
				  $wrapped = wordwrap(trim($arr[$i]),$RangoLimite, '<br>');
				  $arr2 = explode('<br>', trim($wrapped));
				  $arr1 = array_merge($arr1, $arr2);
				  //$arr1[] = $arr2;
		  	  }
		 //$arr2 = explode('<br>', trim($wrapped));
 		 }
		 //print_r($arr1);
		  return $arr1;
	  }
	  function tipoDeDetalle($arreglo,$montoMinimo)
	  {
		  $miBol= true;
		  $Total=0;
		  foreach($arreglo as $d)
		  {
			 $Total= $Total + $d->Precio ;
		  }
		  if ($montoMinimo>=$Total)
		  {
			 $miBol = false;
		  }
		  return $miBol;
	  }
	  function ObtenerMesLiteral($mes) 
	  {
      	setlocale(LC_TIME, 'spanish');
    	$nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
    	return $nombre;
	  }
  }
  ?>