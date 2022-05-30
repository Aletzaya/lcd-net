<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link  = conectarse();
  date_default_timezone_set("America/Mexico_City");
  //header("Location: impeti.php?busca=$busca&pagina=$pagina");
  $Usr    = $_COOKIE['USERNAME'];
  $Fecha=date("Y-m-d");
  $Hora  = date("H:i");


?>
<html>

<head>
<meta charset="UTF-8">
<title>Impresion de etiquetas</title>

</head>

<body bgcolor='#FFFFFF' >

<script>

function bisiesto($anio_actual){ 
	$bisiesto=false; 
	//probamos si el mes de febrero del año actual tiene 29 días 
	  if (checkdate(2,29,$anio_actual)){ 
			$bisiesto=true; 
	  } 
		return $bisiesto; 
} 

</script>

<?php

$busca = $_REQUEST[busca];
 
$Est   = $_REQUEST[Est];  
  
$OtA   = mysql_query("SELECT ot.fecha,ot.cliente,cli.nombrec,cli.sexo,cli.fechan,ot.servicio,ot.institucion,ot.medico,ot.medicon 
         FROM ot,cli,inst 
         WHERE ot.orden='$busca' and ot.cliente=cli.cliente and inst.institucion=ot.institucion");
         //echo "select ot.fecha,ot.cliente,cli.nombrec from ot,cli where ot.orden='$busca' and ot.cliente=cli.cliente";

if($_REQUEST[op] =="1"){        //Para agregar uno nuevo;  
   
     $OtdA = mysql_query("SELECT otd.estudio,est.descripcion,est.depto,dep.nombre 
             FROM otd,est,dep 
             WHERE otd.estudio='$Est' AND otd.orden='$busca' AND est.estudio='$Est' AND dep.departamento=est.depto");
     //echo "select otd.estudio,est.descripcion,est.depto,dep.nombre from otd,est,dep where otd.estudio='$Est' and otd.orden='$busca' and est.estudio='$Est' and dep.departamento=est.depto";

	  $Up  = mysql_query("UPDATE otd set etiquetas = etiquetas + 1, uno='$Fecha $Hora', impeti='$Usr'
	         WHERE orden='$busca' and estudio='$Est'"); 
	  $NumA  = mysql_query("SELECT otd.estudio 
		   FROM otd 
		   WHERE otd.orden='$busca' AND mid(otd.uno,1,4)='0000'");

	 if(mysql_num_rows($NumA)==0){
        	  $lUp = mysql_query("UPDATE ot SET etiqueta='Si' WHERE orden='$busca'");
	 } 
}else{
   
     $OtdA = mysql_query("SELECT otd.estudio,est.descripcion,est.depto,dep.nombre 
             FROM otd,est,dep 
             WHERE otd.orden='$busca' AND otd.estudio=est.estudio AND est.depto=dep.departamento and est.depto<>2");
 
	  $Up  = mysql_query("UPDATE otd set etiquetas = etiquetas + 1, uno='$Fecha $Hora', impeti='$Usr'
	         WHERE orden='$busca'");
	  $NumA  = mysql_query("SELECT otd.estudio 
		   FROM otd 
		   WHERE otd.orden='$busca' AND mid(otd.uno,1,4)='0000'");

	 if(mysql_num_rows($NumA)==0){
        	  $lUp = mysql_query("UPDATE ot SET etiqueta='Si',status='En Toma' WHERE orden='$busca'");
	 } 
}

$Ot  = mysql_fetch_array($OtA);
	 
$MedA = mysql_query("SELECT nombrec FROM med WHERE medico='$Ot[medico]'");

$Med  = mysql_fetch_array($MedA); 

      $Fechanac  = $Ot[fechan];
      $Fecha   = date("Y-m-d");
	  $array_nacimiento = explode ( "-", $Fechanac ); 
	  $array_actual = explode ( "-", $Fecha ); 
	  $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
	  $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
	  $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

		if ($dias < 0) 
		{ 
			--$meses; 
		
			//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
			switch ($array_actual[1]) { 
				   case 1:     $dias_mes_anterior=31; break; 
				   case 2:     $dias_mes_anterior=31; break; 
				   case 3:  
//						if (bisiesto($array_actual[0])) 
//						{ 
//							$dias_mes_anterior=29; break; 
//						} else { 
							$dias_mes_anterior=28; break; 
//						} 
				   case 4:     $dias_mes_anterior=31; break; 
				   case 5:     $dias_mes_anterior=30; break; 
				   case 6:     $dias_mes_anterior=31; break; 
				   case 7:     $dias_mes_anterior=30; break; 
				   case 8:     $dias_mes_anterior=31; break; 
				   case 9:     $dias_mes_anterior=31; break; 
				   case 10:     $dias_mes_anterior=30; break; 
				   case 11:     $dias_mes_anterior=31; break; 
				   case 12:     $dias_mes_anterior=30; break; 
			} 
		
			$dias=$dias + $dias_mes_anterior; 
		} 

		//ajuste de posible negativo en $meses 
		if ($meses < 0) 
		{ 
			--$anos; 
			$meses=$meses + 12; 
		} 
			
		$nomcliente = ucwords(strtolower(substr($Ot[nombrec],0,50)));

while($Otd = mysql_fetch_array($OtdA)){


     echo "<table width='100%' border='0' align='center'>";

     echo "<tr>";

     echo "<td width='100%' height='50' align='center'>";

        echo "<input name='Imp' face=''Calibri, Courier, mono' type='submit' onClick='print()' value='No.Orden: $Ot[institucion] - $busca'><font size='2'><strong> $Hora </strong>";

  		  //echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;#Ord: $busca</font>";

  		  echo "<br>";

  		  echo "<font size='2' face='Calibri, Courier, mono'><strong>$nomcliente</strong></font>";

  		  echo "<br>";
		
		  if($Ot[servicio]=='Ordinaria'){
			  $serv="ORDINARIA";
		  }else{
			  if($Ot[servicio]=='Urgente'){
			 	  $serv="URGENTE";
			  }else{
				  if($Ot[servicio]=='Express'){
					  $serv="EXPRESS";
				  }else{
					  if($Ot[servicio]=='Hospitalizado'){
						  $serv="HOSPITALIZADO";
					  }else{
						  $serv="NOCTURNO";
					  }
				  }
			  }
		  }
  		  echo "<font size='1' face='Calibri, Courier, mono'><strong>Edad: ".$anos." a&ntilde;os &nbsp; $meses Meses &nbsp; Sexo:$Ot[sexo] &nbsp; $Ot[fecha] </strong>";

		  echo "<br>";

		  echo "<div align='center'><font size='2' face='Calibri, Courier, mono'><strong><u> $Otd[estudio] </u><font size='1' face='Calibri, Courier, mono'> - &nbsp;$Otd[descripcion] - <u> $serv </u><div></strong>";

	echo "</td>";

	echo "</tr>";	

	echo "<tr>";		 
		 
	echo "<td width='50%' align='center' height='30' valign='middle'>";

	echo "<font size='1' face='Calibri, Courier, mono'><strong><img src='barcode.php?text=".$busca."&size=33&codetype=Code128&print=false' /></strong></font>";

	//echo "<div id='barcode'></div>";

	echo "</td>";

	echo "</tr>";

	echo "</table>";
   
   echo "<br>";		//Espacios entre etiqueta y etiqueta;

   
}

echo "</body>";

echo "</html>";

mysql_close();
?>
