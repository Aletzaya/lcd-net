<?php

   session_start();

   $tda=$_SESSION['tda'];

   $Exi='exi'.$tda;

   include("lib/lib.php");

   $link=conectarse();

   if(!isset($_REQUEST[Nombre])){$NomFile="reporte.xls";}else{$NomFile=$_REQUEST[Nombre];}

   $cSql=$_REQUEST[cSql];

   $cSql=str_replace("\'","'",$cSql);

   $cSql=str_replace("!","'",$cSql);

  //$cSql=str_replace("_"," ",$cSql);  &&lo quito por k hay funciones como date_format();
    
   $cSql=str_replace("|","*",$cSql);  //&&lo quito por k hay funciones como date_format();

   $cSql=str_replace("^",">",$cSql);
   
   $cSql    = str_replace("~","+",$cSql);                      //Remplazo la comita p'k mande todo el string

   //echo $cSql;
   
   //break;
   
   $result = mysql_query($cSql,$link);

   $count = mysql_num_fields($result);

   if(!isset($_REQUEST[Nombre])){
      for ($i = 0; $i < $count; $i++){
          $header .= strtoupper(mysql_field_name($result, $i))."\t";
      }

      while($row = mysql_fetch_row($result)){
        $line = '';
	    foreach($row as $value){
	       if(!isset($value) || $value == ""){
    	       $value = "\t";
	       }else{
    	      // $value = str_replace('"', '""', $value);
	          $value = '"' . $value . '"' . "\t";
	       }
	       $line .= $value;
	     }
	     $data .= trim($line)."\n";
	  }
	  $data = str_replace("\r", "", $data);

	  if ($data == "") {
	     $data = "\nLa tabla se encuentra vacia, Registros no encontrados\n";
	  }
   	  header("Content-type: application/octet-stream");
	  header("Content-Disposition: attachment; filename=$NomFile");
	  header("Pragma: no-cache");
	  header("Expires: 0");
	  echo $header."\n".$data;

}else{

      while($row = mysql_fetch_row($result)){
        $line = '';
        foreach($row as $value){
           if(!isset($value) || $value == ""){
               $value = "\t";
           }else{
              // $value = str_replace('"', '""', $value);
              $value = '"' . $value . '"';
           }
           $line .= $value;
         }
         $data .= trim($line)."\n";
      }

      $data = str_replace("\r", "", $data);

      if ($data == "") {
         $data = "\nLa tabla se encuentra vacia, Registros no encontrados\n";
      }

      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=$NomFile");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $header."\n".$data;



}
?>