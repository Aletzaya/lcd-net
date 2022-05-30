<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();
  
$Sucursal     =   $_REQUEST[Sucursal];
$sucursalt = $_REQUEST[sucursalt];
$sucursal0 = $_REQUEST[sucursal0];
$sucursal1 = $_REQUEST[sucursal1];
$sucursal2 = $_REQUEST[sucursal2];
$sucursal3 = $_REQUEST[sucursal3];
$sucursal4 = $_REQUEST[sucursal4];
$sucursal5 = $_REQUEST[sucursal5];
$sucursal6 = $_REQUEST[sucursal6];

$Institucion=$_REQUEST[Institucion];

$Fecha=$_REQUEST[FechaI];

$FechaI=$_REQUEST[FechaI];

$FechaF=$_REQUEST[FechaF];

if ($FechaI>$FechaF){

  $FechaF=date("Y-m-d");
  $FechaI=date("Y-m-")."01";
  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
}

if (!isset($FechaI)){

  $FechaF=date("Y-m-d");
  $FechaI=date("Y-m-")."01";

}

if($Institucion=='*'){

	$instit='Todas las Instituciones';

}else{
	
	$InsA   = mysql_query("SELECT * FROM inst where inst.id=$Institucion");
	
	$Ins = mysql_fetch_array($InsA);
	
	$instit=$Ins[alias];
}

$Titulo  = "Resumen Corte por Dia del $FechaI al $FechaF : $instit";    

require ("config.php");							   //Parametros de colores;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title>Resumen Gral. de Corte de Caja X Dia</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>

<?php
        
 	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="";
		$Sucursal2= " * - Todas ";
		$and="";
		$parentesisa="";
		$parentesisc="";

	}else{

		$and=" and ";
		$parentesisa="(";
		$parentesisc=")";
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucgasto= " dpag_ref.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
				$Sucgasto= $Sucgasto . " dpag_ref.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
				$Sucgasto= $Sucgasto . " OR dpag_ref.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
				$Sucgasto= $Sucgasto . " dpag_ref.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
				$Sucgasto= $Sucgasto . " OR dpag_ref.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
				$Sucgasto= $Sucgasto . " dpag_ref.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
				$Sucgasto= $Sucgasto . " OR dpag_ref.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
				$Sucgasto= $Sucgasto . " dpag_ref.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
				$Sucgasto= $Sucgasto . " OR dpag_ref.suc=4";
			}
		}	

		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=5";
				$Sucgasto= $Sucgasto . " dpag_ref.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
				$Sucgasto= $Sucgasto . " OR dpag_ref.suc=5";
			}
		}
		if($sucursal6=="1"){
			$Sucursal2= $Sucursal2 . "San Vicente ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
				$Sucgasto= $Sucgasto . " dpag_ref.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
				$Sucgasto= $Sucgasto . " OR dpag_ref.suc=6";
			}
		}
	}

if($Institucion=="*"){

	$CondifInst="";

}else{

	$CondifInst="and ot.institucion=$Institucion";

}
				
$CpoAf=mysql_query("SELECT cja.id,cja.fecha as fechas,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario as recepcionista,ot.fecha as fechaot,ot.hora as horaorden,ot.suc,ot.institucion 
FROM cja,ot
WHERE cja.fecha>='$FechaI' and cja.fecha<='$FechaF' AND ot.orden=cja.orden $and $parentesisa $Sucursal $parentesisc $CondifInst
ORDER BY fechas,cja.orden,cja.id,cja.hora");

?>

<table width="92%" border="0" align="center">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>

    <td width="74%"><p align="left"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Laboratorio
        Clinico Duran</strong></font></p>
        <div> Institucion : <?php if($Institucion=='*'){ echo "*, Todas";}else{echo "$Institucion $Ins[nombre]";}?></div>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Resumen Gral. de Corte de Caja X Dia del <?php echo "$FechaI al  $FechaF  Sucursal: $Sucursal2 ";?><br>
        </font></p>
      </td>
  </tr>
</table>
<br>

<?php
 
$Depto='';
$FecT=$FechaI;
$cantidadt=0;
$importet=0;
$Gfont="<font size='2' face='Arial, Helvetica, sans-serif'>";

echo "<table width='90%' align='center' border='0' class='textosItalicos'>";
echo "<tr class='content_txt' bgcolor='a6b6e2' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='a6b6e2';>";
echo "<td align='center'>$Gfont<b> Fecha </b></font></td>";
echo "<td align='center' colspan='2'>$Gfont<b> Ventas </b></font></td>";
echo "<td align='center' colspan='2'>$Gfont<b> Efectivo </b></font></td>";	
echo "<td align='center' colspan='2'>$Gfont<b> Tarjeta </b></font></td>";	
echo "<td align='center' colspan='2'>$Gfont<b> Transferencia </b></font></td>";
echo "<td align='center' colspan='2'>$Gfont<b> Ingresos </b></font></td>";		
echo "<td align='center'>$Gfont<b> Adeudos </b></font></td>";	
echo "<td align='center'>$Gfont<b> Gastos </b></font></td>";	
echo "<td align='center'>$Gfont<b> Total x Dia </b></font></td>";
echo "</tr>";  

echo "<tr class='content_txt' bgcolor='a6b6e2' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='a6b6e2';>";
echo "<td align='center'> &nbsp; </td>";
echo "<td align='center'>$Gfont<b> No. </b></font></td>";	
echo "<td align='center'>$Gfont<b> Importe </b></font></td>";	
echo "<td align='center'>$Gfont<b> Del Dia </b></font></td>";	
echo "<td align='center'>$Gfont<b> Recup. </b></font></td>";	
echo "<td align='center'>$Gfont<b> Del Dia </b></font></td>";	
echo "<td align='center'>$Gfont<b> Recup. </b></font></td>";		
echo "<td align='center'>$Gfont<b> Del Dia </b></font></td>";	
echo "<td align='center'>$Gfont<b> Recup. </b></font></td>";		
echo "<td align='center' colspan='2'>$Gfont<b> </b></font></td>";	
echo "<td align='center'>$Gfont<b> </b></font></td>";	
echo "<td align='center'>$Gfont<b> </b></font></td>";	
echo "<td align='center'>$Gfont<b>  </b></font></td>";	
echo "</tr>";  

$FecT=$FechaI;
if($FechaI<>$FechaF){
		
	do{

		while($rgf=mysql_fetch_array($CpoAf)){  
			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

			if( $FecT==$rgf[fechas]){

				if( $rgf[fechas]<>$rgf[fechaot]){
					if($rgf[tpago]=='Efectivo'){
						$totalxdiaefer+=$rgf[importe];
					}elseif($rgf[tpago]=='Tarjeta'){	
						$totalxdiatarr+=$rgf[importe];
					}elseif($rgf[tpago]=='Transferencia'){
						$totalxdiatrar+=$rgf[importe];
					}
				}else{
					if($rgf[tpago]=='Efectivo'){
						$totalxdiaefe+=$rgf[importe];
					}elseif($rgf[tpago]=='Tarjeta'){	
						$totalxdiatar+=$rgf[importe];
					}elseif($rgf[tpago]=='Transferencia'){
						$totalxdiatra+=$rgf[importe];
					}
				}

				if( $rgf[fechas]==$rgf[fechaot] and $Ord<>$rgf[orden]){

					$totalimportexdia+=$rgf[importeot];
					$contar++;
					$contarT++;

				}

			}

			$Ord = $rgf[orden];
			
		}

		$totalimportexdiaT+=$totalimportexdia;
		$totalxdiaefet+=$totalxdiaefe;
		$totalxdiatart+=$totalxdiatar;
		$totalxdiatrat+=$totalxdiatra;

		$totalxdiaefetr+=$totalxdiaefer;
		$totalxdiatartr+=$totalxdiatarr;
		$totalxdiatratr+=$totalxdiatrar;

		$Totalingresos=$totalxdiaefet+$totalxdiatart+$totalxdiatrat+$totalxdiaefetr+$totalxdiatartr+$totalxdiatratr;

		$totalimportexdiaTG+=$totalimportexdiaT;

		$totalxdiaefetg+=$totalxdiaefet;
		$totalxdiatartg+=$totalxdiatart;
		$totalxdiatratg+=$totalxdiatrat;

		$totalxdiaefetrg+=$totalxdiaefetr;
		$totalxdiatartrg+=$totalxdiatartr;
		$totalxdiatratrg+=$totalxdiatratr;

		$Totalingresosg+=$Totalingresos;

		echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		echo "<td align='center'>$Gfont $FecT </td>";

		echo "<td align='center'>$Gfont $contar </font></td><td align='right'>$Gfont ".number_format($totalimportexdiaT,2)." </font></td>";	

		if($totalxdiaefet==0){
			$totalxdiaefet='';
		}else{
			$totalxdiaefet=number_format($totalxdiaefet,2);
		}

		if($totalxdiaefetr==0){
			$totalxdiaefetr='';
		}else{
			$totalxdiaefetr=number_format($totalxdiaefetr,2);
		}

		echo "<td align='right'>$Gfont ".$totalxdiaefet." </font></td><td align='right'>$Gfont ".$totalxdiaefetr." </font></td>";	

		if($totalxdiatart==0){
			$totalxdiatart='';
		}else{
			$totalxdiatart=number_format($totalxdiatart,2);
		}

		if($totalxdiatartr==0){
			$totalxdiatartr='';
		}else{
			$totalxdiatartr=number_format($totalxdiatartr,2);
		}

		echo "<td align='right'>$Gfont ".$totalxdiatart." </font></td><td align='right'>$Gfont ".$totalxdiatartr." </font></td>";

		if($totalxdiatrat==0){
			$totalxdiatrat='';
		}else{
			$totalxdiatrat=number_format($totalxdiatrat,2);
		}

		if($totalxdiatratr==0){
			$totalxdiatratr='';
		}else{
			$totalxdiatratr=number_format($totalxdiatratr,2);
		}
		echo "<td align='right'>$Gfont ".$totalxdiatrat." </font></td><td align='right'>$Gfont ".$totalxdiatratr." </font></td>";	

		echo "<td align='right' colspan='2'>$Gfont ".number_format($Totalingresos,2)." </font></td>";	

		$GastosA=mysql_query("SELECT sum(monto) as tgastos FROM dpag_ref WHERE fechapago='$FecT' and cancelada=0",$link);

		$Gasto=mysql_fetch_array($GastosA);

		$Gastos=$Gasto[tgastos];

		$GastosT=$GastosT+$Gasto[tgastos];

		$GastosTg+=$GastosT;

		$Adeudos=$totalimportexdiaT-$Totalingresos;

		$Adeudostg+=$Adeudos;

		$Importet=$Totalingresos-$GastosT;

		$Importetg+=$Importet;

		echo "<td align='right'>$Gfont ".number_format($Adeudos,2)."</td>";	

		echo "<td align='right'>$Gfont ".number_format($GastosT,2)." </font></td>";	

		echo "<td align='right'>$Gfont ".number_format($Importet,2)." </font></td>";	

		echo "</tr>";  

		$totalxdiaefet=$totalxdiatart=$totalxdiatrat=0;
		$totalxdiaefetr=$totalxdiatartr=$totalxdiatratr=0;
		$totalxdiaefe=$totalxdiatar=$totalxdiatra=0;
		$totalxdiaefer=$totalxdiatarr=$totalxdiatrar=0;
		$totalimportexdiaT=0;
		$contar=0;
		$totalimportexdia=0;

		$GastosT=0;

		$Gastos=0;

		$FecT = date('Y-m-d', strtotime("$FecT + 1 day"));
		$nRng++;
		mysql_data_seek($CpoAf, 0);

	}while($FecT<>$FechaF);

}

while($rgf=mysql_fetch_array($CpoAf)){  
		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

		if( $FecT==$rgf[fechas]){

			if( $rgf[fechas]<>$rgf[fechaot]){
				if($rgf[tpago]=='Efectivo'){
					$totalxdiaefer+=$rgf[importe];
				}elseif($rgf[tpago]=='Tarjeta'){	
					$totalxdiatarr+=$rgf[importe];
				}elseif($rgf[tpago]=='Transferencia'){
					$totalxdiatrar+=$rgf[importe];
				}
			}else{
				if($rgf[tpago]=='Efectivo'){
					$totalxdiaefe+=$rgf[importe];
				}elseif($rgf[tpago]=='Tarjeta'){	
					$totalxdiatar+=$rgf[importe];
				}elseif($rgf[tpago]=='Transferencia'){
					$totalxdiatra+=$rgf[importe];
				}
			}

			if( $rgf[fechas]==$rgf[fechaot] and $Ord<>$rgf[orden]){

				$totalimportexdia+=$rgf[importeot];
				$contar++;
				$contarT++;

			}
		}

		$Ord = $rgf[orden];

}


$totalimportexdiaT+=$totalimportexdia;
$totalxdiaefet+=$totalxdiaefe;
$totalxdiatart+=$totalxdiatar;
$totalxdiatrat+=$totalxdiatra;

$totalxdiaefetr+=$totalxdiaefer;
$totalxdiatartr+=$totalxdiatarr;
$totalxdiatratr+=$totalxdiatrar;

$Totalingresos=$totalxdiaefet+$totalxdiatart+$totalxdiatrat+$totalxdiaefetr+$totalxdiatartr+$totalxdiatratr;

$totalimportexdiaTG+=$totalimportexdiaT;

$totalxdiaefetg+=$totalxdiaefet;
$totalxdiatartg+=$totalxdiatart;
$totalxdiatratg+=$totalxdiatrat;

$totalxdiaefetrg+=$totalxdiaefetr;
$totalxdiatartrg+=$totalxdiatartr;
$totalxdiatratrg+=$totalxdiatratr;

$Totalingresosg+=$Totalingresos;

echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
echo "<td align='center'>$Gfont $FecT </font></td>";

echo "<td align='center'>$Gfont $contar </font></td><td align='right'>$Gfont ".number_format($totalimportexdiaT,2)." </font></td>";	

if($totalxdiaefet==0){
	$totalxdiaefet='';
}else{
	$totalxdiaefet=number_format($totalxdiaefet,2);
}

if($totalxdiaefetr==0){
	$totalxdiaefetr='';
}else{
	$totalxdiaefetr=number_format($totalxdiaefetr,2);
}

echo "<td align='right'>$Gfont ".$totalxdiaefet." </font></td><td align='right'>$Gfont ".$totalxdiaefetr." </font></td>";	

if($totalxdiatart==0){
	$totalxdiatart='';
}else{
	$totalxdiatart=number_format($totalxdiatart,2);
}

if($totalxdiatartr==0){
	$totalxdiatartr='';
}else{
	$totalxdiatartr=number_format($totalxdiatartr,2);
}

echo "<td align='right'>$Gfont ".$totalxdiatart." </font></td><td align='right'>$Gfont ".$totalxdiatartr." </font></td>";

if($totalxdiatrat==0){
	$totalxdiatrat='';
}else{
	$totalxdiatrat=number_format($totalxdiatrat,2);
}

if($totalxdiatratr==0){
	$totalxdiatratr='';
}else{
	$totalxdiatratr=number_format($totalxdiatratr,2);
}
echo "<td align='right'>$Gfont ".$totalxdiatrat." </font></td><td align='right'>$Gfont ".$totalxdiatratr." </font></td>";	

echo "<td align='right' colspan='2'>$Gfont ".number_format($Totalingresos,2)." </font></td>";	

$GastosT=0;

$Gastos=0;

$GastosA=mysql_query("SELECT sum(monto) as tgastos FROM dpag_ref WHERE fechapago='$FecT' and cancelada=0",$link);

$Gasto=mysql_fetch_array($GastosA);

$Gastos=$Gasto[tgastos];

$GastosT=$GastosT+$Gasto[tgastos];

$GastosTg+=$GastosT;

$Adeudos=$totalimportexdiaT-$Totalingresos;

$Adeudostg+=$Adeudos;

$Importet=$Totalingresos-$GastosT;

$Importetg+=$Importet;

echo "<td align='right'>$Gfont ".number_format($Adeudos,2)."</td>";	

echo "<td align='right'>$Gfont ".number_format($GastosT,2)." </font></td>";	

echo "<td align='right'>$Gfont ".number_format($Importet,2)." </font></td>";	

echo "</tr>";  

$totalxdiaefet=$totalxdiatart=$totalxdiatrat=0;
$totalxdiaefetr=$totalxdiatartr=$totalxdiatratr=0;

$FecT = date('Y-m-d', strtotime("$FecT + 1 day"));
$nRng++;

echo "</tr>";  

echo "<tr class='content_txt' bgcolor='a6b6e2' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='a6b6e2';>";
echo "<td align='center'> &nbsp; </td>";
echo "<td align='center'>$Gfont<b> ".number_format($contarT,0)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($totalimportexdiaTG,2)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($totalxdiaefetg,2)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($totalxdiaefetrg,2)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($totalxdiatartg,2)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($totalxdiatartrg,2)." </b></font></td>";		
echo "<td align='right'>$Gfont<b> ".number_format($totalxdiatratg,2)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($totalxdiatratrg,2)." </b></font></td>";			
echo "<td align='right' colspan='2'>$Gfont<b> ".number_format($Totalingresosg,2)." </b></font></td>";	
echo "<td align='right'>$Gfont <b>".number_format($Adeudostg,2)."</b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($GastosTg,2)." </b></font></td>";	
echo "<td align='right'>$Gfont<b> ".number_format($Importetg,2)." </b></font></td>";	
echo "</tr>"; 

echo "<tr class='content_txt' bgcolor='a6b6e2' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='a6b6e2';>";

echo "<td align='center'>$Gfont<b> </b></font></td>";
echo "<td align='center' colspan='2'>$Gfont<b> </b></font></td>";
echo "<td align='center' colspan='2'>$Gfont<b> ".number_format($totalxdiaefetg+$totalxdiaefetrg,2)." </b></font></td>";	
echo "<td align='center' colspan='2'>$Gfont<b> ".number_format($totalxdiatartg+$totalxdiatartrg,2)." </b></font></td>";	
echo "<td align='center' colspan='2'>$Gfont<b> ".number_format($totalxdiatratg+$totalxdiatratrg,2)." </b></font></td>";
echo "<td align='center' colspan='2'>$Gfont<b></b></font></td>";		
echo "<td align='center'>$Gfont<b></b></font></td>";	
echo "<td align='center'>$Gfont<b></b></font></td>";	
echo "<td align='center'>$Gfont<b></b></font></td>";
echo "</tr>";  
echo "</table>";

echo "<br>";
echo "<br>";
				
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=33&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "</body>";

echo "</html>";

?>	     

