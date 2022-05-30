<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();
  
	$Sucursal  = $_REQUEST[Sucursal];
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

  $Fechai=$_REQUEST[FechaI];

  $Fechaf=$_REQUEST[FechaF];

  $Titulo=$_REQUEST[Titulo];

  ?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Reporte de Pagos / Adeudos2</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>
  
  <?php

  if($Institucion=='*'){
  	$SeleccInst="";
  	$SeleccInst2="";
  }else{
  	$SeleccInst=" WHERE ot.institucion=$Institucion ";
  	$SeleccInst2=" and ot.institucion=$Institucion ";
  }

  $InstA   = mysql_query("SELECT nombre FROM inst $SeleccInst order by institucion");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	$Sucursal3= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal3=" and ";
			$Sucursal= $Sucursal . " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){
			$Sucursal3=" and "; 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal3=" and "; 
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal3=" and "; 
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal3=" and "; 
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}

		if($sucursal5=="1"){
			$Sucursal3=" and "; 
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}

		if($sucursal6=="1"){
			$Sucursal3=" and "; 
			$Sucursal2= $Sucursal2 . "San Vicente - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}
		
$cSql="SELECT orden,institucion,importe,suc FROM ot WHERE ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' $SeleccInst2 $Sucursal3 ORDER BY ot.institucion,ot.orden ";

$UpA=mysql_query($cSql,$link);

$Hora=date("H:i:s");

require ("config.php");

?>
<table width="95%" height="80" border="0" align="center">
  <tr>
    <td width="26%" height="76">
      <p align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"></p>
      </td>
    <td width="74%"><p align="left"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Laboratorio
        Clinico Duran</strong></font></p>
        <div> Institucion : <?php if($Institucion=='*'){ echo "*, Todas";}else{echo "$Institucion $Ins[nombre]";}?></div>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Corte de Caja del <?php echo "$Fecha &nbsp; de : $HoraI a $HoraF Sucursal: $Sucursal2 ";?> hrs.<br>
        Recepcionista : &nbsp;
        <?php if($Recepcionista=="*"){echo "*, &nbsp;todos";}else{echo $Recepcionista;}?>
        </font></p>
      </td>
  </tr>
</table>
<font size="1" face="Arial, Helvetica, sans-serif">
<?php
echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr><td colspan='15'><hr noshade></td></tr>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Inst.</font></th>";
echo "<th align='CENTER' width='20%'><font size='1' face='Arial, Helvetica, sans-serif'>Nombre</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No Ordenes</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ventas</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Efectivo )</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Efec. )</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Banc. )</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Banc. )</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ingresos</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudos (CXC)</font></th>";
echo "<tr><td colspan='15'><hr noshade></td></tr>";

$contaorden=0;

$ingresos=0;

$Int0=''; 

while($res=mysql_fetch_array($UpA)) {

		$InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$res[institucion]'");
		$NomB    = mysql_fetch_array($InstB);

		$ingresod	=	mysql_query("SELECT sum(importe), tpago FROM cja WHERE orden='$res[orden]'");
		$ingreso  =	mysql_fetch_array($ingresod); 

		$DtoA=mysql_query("SELECT sum( precio - (precio * ( descuento /100 ) )) as importe FROM otd WHERE orden ='$res[orden]'");

		$Dto=mysql_fetch_array($DtoA);            

		if($Int0==''){
				$contaorden++;
		}else{

			if($res[institucion]==$Int0){
				$contaorden++;           
			}else{

				$cajA="SELECT cja.id,cja.fecha,cja.orden,sum(cja.importe) as importe,cja.tpago,ot.status,cja.usuario,ot.institucion,ot.fecha FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' and cja.orden=ot.orden AND ot.institucion='$res[institucion]' order by cja.orden,cja.id";

				$caj=mysql_query($cajA);

				$cajse=mysql_fetch_array($caj);


				if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

				echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
				echo "<th align='CENTER'><a href=javascript:winuni('comisionesinst.php?FecI=$Fechai&FecF=$Fechaf&Institucion=$Int0&sucursal=$Sucursalind')><font size='1' face='Arial, Helvetica, sans-serif'>$instnumero</a></font></th>";
				echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$instnombre</font></th>";
				echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".$contaorden."</font></th>";
				echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($importeventa,'2')."</font></th>";
				echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ingresose,'2')."</font></th>";
				echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($cajse[importe],'2')."</font></th>";
				echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ingresosb,'2')."</font></th>";
				echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ingresos,'2')."</font></th>";
				echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($importeventa-$ingresos,'2')."</font></th>";
				echo "</tr>";
				$ingresosT	=	$ingresosT+$ingresos;
				$ingresoseT	=	$ingresoseT+$ingresose;
				$ingresosbT	=	$ingresosbT+$ingresosb;
				$importeventaT=$importeventaT+$importeventa;
				$contaordenT=$contaordenT+$contaorden;
				$contaorden=1;
				$importeventa=0;
				$ingresos=0;
				$ingresose=0;
				$ingresosb=0;
				$nRng++;

			}

		}

		$ingresos	=	$ingresos+$ingreso[0];

		if($ingreso[tpago]=='Efectivo'){
			$ingresose	=	$ingresose+$ingreso[0];
		}else{
			$ingresosb	=	$ingresosb+$ingreso[0];
		}

		$importeventa=$importeventa+$Dto[importe];
		$Int0=$res[institucion];
		$instnumero=$res[institucion];
		$instnombre=$NomB[nombre];

}

$contaordenT=$contaordenT+$contaorden;
$ingresosT	=	$ingresosT+$ingresos;
$ingresoseT	=	$ingresoseT+$ingresose;
$ingresosbT	=	$ingresosbT+$ingresosb;
$importeventaT=$importeventaT+$importeventa;

if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
echo "<th align='CENTER'><a href=javascript:winuni('comisionesinst.php?FecI=$Fechai&FecF=$Fechaf&Institucion=$Int0&sucursal=$Sucursalind')><font size='1' face='Arial, Helvetica, sans-serif'>$instnumero</a></font></th>";
echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$instnombre</font></th>";
echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".$contaorden."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($importeventa,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ingresose,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ingresosb,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ingresos,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($importeventa-$ingresos,'2')."</font></th>";
echo "</tr>";

echo "<tr><td colspan='15'><hr noshade></td></tr>";
echo "<tr height='20'>";
echo "<th align='CENTER'></font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Totales: </font></th>";
echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".$contaordenT."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($importeventaT,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($ingresoseT,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($ingresosbT,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($ingresosT,'2')."</font></th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($importeventaT-$ingresosT,'2')."</font></th>";
echo "</tr>";

echo "</table>";

//inicio del while para adeudos, recuperaciones y descuentos
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=31&fechas=1&Fechai=$Fechai&Fechaf=$Fechaf'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";
echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('impcorte2pdf.php?Fechai=$Fechai&Fechaf=$Fechaf&Institucion=$Institucion&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";
echo "</form>";
echo "</div>";

?>
</body>
</html>
<?php
mysql_close();
?>
