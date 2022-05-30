<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $level       = $check['level'];

  $Recepcionista= "*";
	$Recepcionista='*';
  
	$Sucursal     =   $_REQUEST[Sucursal];
	//$Sucursal     =   $Sucursal[0];
	$sucursalt = $_REQUEST[sucursalt];
	$sucursal0 = $_REQUEST[sucursal0];
	$sucursal1 = $_REQUEST[sucursal1];
	$sucursal2 = $_REQUEST[sucursal2];
	$sucursal3 = $_REQUEST[sucursal3];
	$sucursal4 = $_REQUEST[sucursal4];
	$sucursal5 = $_REQUEST[sucursal5];
	$sucursal6 = $_REQUEST[sucursal6];

  $Institucion=$_REQUEST[Institucion];

  $Institucion1=$_REQUEST[Institucion];

  $Fecha=$_REQUEST[FechaI];

  $Fechai=$_REQUEST[FechaI];

  $Fechaf=$_REQUEST[FechaF];

  $Suc                  =       $_COOKIE['TEAM'];        //Sucursal 

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title>Corte de Caja </title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
<?php

  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}	

		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}
		if($sucursal6=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}


	if($_REQUEST[Institucion]=='*'){

   		if($Recepcionista=="*"){
			
	        if ($Sucursal <> '') {
				
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
						   cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
						   FROM cja,ot
						   WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND ot.orden=cja.orden AND ($Sucursal) 
						   ORDER BY ot.institucion,ot.orden,cja.id";
					
					$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND ($Sucursal)";

			}else{

				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
						   cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
						   FROM cja,ot
						   WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND ot.orden=cja.orden 
						   ORDER BY ot.institucion,ot.orden,cja.id";
					
					$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf'";
					
			}

	   }else{
		   
   	  if ($Sucursal <> '') {
				

				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				FROM cja,ot
				WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND ($Sucursal)
				order by ot.institucion,ot.orden,cja.id";
	
				$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista' AND ($Sucursal)";
				
			}else{
	
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				FROM cja,ot
				WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden order by ot.institucion,ot.orden,cja.id";
	
				$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista'";
			
			}

	   }
	}else{

	   if($Recepcionista=="*"){
		   
	     if ($Sucursal <> '') {
			   
			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND institucion='$Institucion' AND ($Sucursal)";

			   
		   }else{

			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND institucion='$Institucion'";
			  
		   }

	   }else{
		   
		  if ($Sucursal <> '') {
			  
			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista' AND institucion='$Institucion' AND ($Sucursal)";
			  
		  }else{

			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by cja.orden,cja.id";
	
	
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista' AND institucion='$Institucion'";
		  
		  }

	   }

	}


if($level==9 or $level==99 or $level==7){


	$UpA=mysql_query($cSql,$link);

}

$OtNumA=mysql_query($OtNum,$link);

$Ordenes=mysql_fetch_array($OtNumA);

$Hora=date("h:i:s");


?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>

    <td width="74%"><p align="left"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Laboratorio
        Clinico Duran</strong></font></p>
        <div> Institucion : <?php if($Institucion=='*'){ echo "*, Todas";}else{echo "$Institucion $Ins[nombre]";}?></div>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Corte de Caja del <?php echo "$Fechai &nbsp; de : $Fechaf &nbsp; Sucursal: $Sucursal2 ";?> hrs.<br>
        </font></p>
      </td>
  </tr>
</table>
<font size="1" face="Arial, Helvetica, sans-serif">
<?php
  if(!$res=mysql_fetch_array($UpA)){
			echo "<div align='center'>";
			echo "<font face='verdana' size='2'>No se encontraron resultados</font>";
			echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=31'>";
			echo "Regresar</a></font>";
			echo "</div>";
  }else{

			echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
			echo "<tr><td colspan='15'><hr noshade></td></tr>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Inst.</font></th>";
			echo "<th align='CENTER' width='30%'><font size='1' face='Arial, Helvetica, sans-serif'>Nombre</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No Ordenes</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ventas</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Efectivo )</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Efec. )</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Efec.</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Banc. )</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Banc. )</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Bancos</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ingresos</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudos (CXC)</font></th>";
			echo "<tr><td colspan='15'><hr noshade></td></tr>";

			if($_REQUEST[Institucion]=='*'){
					$sql=$cSql;
        	$res=mysql_query($sql,$link);
	        $FechaAux=strtotime($Fecha);
    	    $nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
        	$FechaAnt=date("Y-m-d",$nDias);
	        $TotalIngreso=0;
					$Repetido2=0;
					$Orden1=0;
					$Ini=1;
					$Tarjeta=0;
					$Efectivo=0;
					$Cheque=0;
					$Transferencia=0;
					$TransferenciaR=0;
					$TarjetaR=0;
					$EfectivoR=0;
					$ChequeR=0;
					$status=="Pagada";
					$contaorden=0;

	        while($registro=mysql_fetch_array($res)) {
				 //$InstO=$registro[institucion];
						$AbnA=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha <= '$FechaAnt'",$link);
						$Abonado=mysql_fetch_array($AbnA);
						$AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND id<>'$registro[0]'",$link);
						$AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
						$DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
						$Dto=mysql_fetch_array($DtoA);              
						$Horaorden1=substr($registro[horaorden],0,5);
						$Horaordenc=$registro[3];
						$Adeudo1=($registro[6]-($Abonado[0] + $registro[4] + $AbonadoD[0] ));
					  if ($Adeudo1<=1){
			 				if ($registro[4]==$registro[6]){
								if ($Orden1==$registro[orden]){
										$status="Recup. Inmed.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=0;
										$Ingreso1=$registro[4];
										$Adeudo1=0;
									}else{
										$status="Pagado";
										$Ordenventa=$Dto[1];
										$Ordendesc=$Dto[0];
										$Ordenventot=$Dto[1]-$Dto[0];
										$Recupera=0;
										$Ingreso1=$registro[4];
									}
							}else{
								if ($Orden1==$registro[orden]){
									$Horaordenc3=$Horaordenc-$Horaordenc2;
									if ($Horaordenc3>=5){	
										$status="Recup. Inmed.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=0;
										$Ingreso1=$registro[4];
										$Adeudo1=0;
										//$Recupera3=$registro[4];
									}else{
										$status="Pagado";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=0;
										$Ingreso1=$registro[4];
										$Adeudo1=0;
									}
								}else{
									if ($registro[1]==$registro[10]){	
										if ($Horaorden1<=$HoraI){
											$status="Recuperac.";
											$Ordenventa=0;
											$Ordendesc=0;
											$Ordenventot=0;
											$Recupera=$registro[4];
											$Ingreso1=0;
										}else{
											$status="Pagado";
											$Ordenventa=$Dto[1];
											$Ordendesc=$Dto[0];
											$Ordenventot=$Dto[1]-$Dto[0];
											$Recupera=0;
											$Ingreso1=$registro[4];	
										}	
									}else{	
										$status="Recuperac.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=$registro[4];
										$Ingreso1=0;	
										$Recupera3=$registro[4];
									}
								}
							}
						}else{
								if ($Orden1==$registro[orden]){	
									if ($Abonado[0]<>0){	
										$status="Abono";
										$Ordenventa=$Dto[1];
										$Ordendesc=$Dto[0];
										$Ordenventot=0;
										$Recupera=$registro[4];
										$Ingreso1=0;	
									}else{
										$status="Recuperac.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=$registro[4];
										$Ingreso1=0;
										$Adeudo1=0;
									}	
								}else{
									if ($Abonado[0]<>0){	
										$status="Recuperac.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=$registro[4];
										$Ingreso1=0;
										$Adeudo1=0;
										$Recupera3=$registro[4];
									}else{
										$status="C / Adeudo";
										$Abonos=$Abonado[0];
										$Ordenventa=$Dto[1];
										$Ordendesc=$Dto[0];
										$Ordenventot=$Dto[1]-$Dto[0];
										$Recupera=0;
										$Ingreso1=$registro[4];
									}
								}					
						}

				 		if($InstO<>$registro[institucion]){
							  if($Ini==1){

									$contaorden=$contaorden+1;
									$Recupera2=$Recupera2+$Recupera;
									 $TotAdeudo=$TotAdeudo+$Adeudo1;
									 $Importe=$Importe+$Ordenventa-$Abonos;
									 $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
									 $Totdia=$TotalOrd;
									 $TotalIngreso=$TotalIngreso+$registro[4];
									 $Descuento=$Descuento+$Ordendesc;
									 $Ingreso=$Ingreso+$Ingreso1;
									 $IngTot=$Ingreso+$Recupera2;
									 $Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
									 $Recupera3=0;
									 $Abonos=0;
									 $Ini=$Ini+1;
									 $InstO=$registro[institucion];
									 $instnumero=$registro[institucion];

									if ($Orden1==$registro[orden] or $Fechai>$registro[fecha]){
											$no=$no+1;
									}else{
											$no=$no;
									}

								}else{
							 
									$InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$instnumero'");
									$NomB    = mysql_fetch_array($InstB);
									$instnombre=$NomB[nombre];
									$contaorden2=$contaorden-$no;
									//$contaorden2=$contaorden;
									$no=0;

									if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;
									$nRng++;

									echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
									echo "<td align='CENTER' width='10'><a href=javascript:winuni('comisionesinst.php?FecI=$Fechai&FecF=$Fechaf&Institucion=$instnumero&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]')><font size='1' face='Arial, Helvetica, sans-serif'>$instnumero</a></font></td>";
									echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$instnombre</font></td>";
									echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$contaorden2</font></td>";
									echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2')."</b></td>";
									echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo,"2")."</font></td>";
									echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($EfectivoR,"2")."</font></td>";
									echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo+$EfectivoR,"2")."</font></b></td>";
									echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$Cheque+$Transferencia,"2")."</font></td>";
									echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
									echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$Cheque+$Transferencia+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></b></td>";
									echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2')."</b></font></td>";
									echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Totdia,'2')."</b></font></td>";
									echo "</tr>";

									$Totgraldia=$Totgraldia+$Totdia;
									$Totgraladeudo=$Totgraladeudo+$TotAdeudo;
									$Totgralingreso=$Totgralingreso+$Ingreso;
									$Totgralrecupera=$Totgralrecupera+$Recupera2;
									$Totgralingtot=$Totgralingtot+$IngTot;
									$Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
									$Grantotalgral=$Grantotalgral+$Totalgral;
									$Totalefectivo=$Totalefectivo+$Efectivo;
									$TotalefectivoR=$TotalefectivoR+$EfectivoR;
									$Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
									$Totaltarjeta=$Totaltarjeta+$Tarjeta;
									$TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
									$Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;
									$Totalcheque=$Totalcheque+$Cheque;
									$TotalchequeR=$TotalchequeR+$ChequeR;
									$Totaltranferencia=$Totaltranferencia+$Transferencia;
									$TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
									$Graltotalcheque=$Graltotalcheque+$Gralcheque;
									$Totalgral1=$Totalgral1+$Gral1;
									$Totalgral2=$Totalgral2+$Gral2;
									$Totalgral3=$Totalgral3+$Gral3;

									$Recupera2=0;
									$TotAdeudo=0;
									$Importe=0;
									$TotalOrd=0;
									$Totdia=0;
									$TotalIngreso=0;
									$Descuento=0;
									$Ingreso=0;
									$IngTot=0;
									$Saldo=0;
									$Recupera3=0;
									$Abonos=0;
									$contaorden=0;
									$Tarjeta=0;
									$Efectivo=0;
									$Cheque=0;
									$TarjetaR=0;
									$EfectivoR=0;
									$Transferencia=0;
									$TransferenciaR=0;
									$ChequeR=0;
									$status=="Pagada";
							 							 
									$contaorden=$contaorden+1;
									$Recupera2=$Recupera2+$Recupera;
									$TotAdeudo=$TotAdeudo+$Adeudo1;
									$Importe=$Importe+$Ordenventa-$Abonos;
									$TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
									$Totdia=$TotalOrd;
									$TotalIngreso=$TotalIngreso+$registro[4];
									$Descuento=$Descuento+$Ordendesc;
									$Ingreso=$Ingreso+$Ingreso1;
									$IngTot=$Ingreso+$Recupera2;
									$Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
									$Recupera3=0;
									$Abonos=0;
									$Ini=$Ini+1;
									$InstO=$registro[institucion];
									$instnumero=$registro[institucion];
									$contaordenT=$contaordenT+$contaorden2;
									if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
										$no=$no+1;
									}else{
										$no=$no+0;
									}
								}
							
						}else{
							 	if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
									$no=$no+1;
								}else{
									$no=$no+0;
								}

								$Recupera2=$Recupera2+$Recupera;
								$TotAdeudo=$TotAdeudo+$Adeudo1;
								$Importe=$Importe+$Ordenventa-$Abonos;
								$TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
								$Totdia=$TotalOrd;
								$TotalIngreso=$TotalIngreso+$registro[4];
								$Descuento=$Descuento+$Ordendesc;
								$Ingreso=$Ingreso+$Ingreso1;
								$IngTot=$Ingreso+$Recupera2;
								$Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
								$Recupera3=0;
								$Abonos=0;
								$contaorden=$contaorden+1;
								$Ini=$Ini+1;
						}
						$Orden1=$registro[orden];
						$Horaordenc2=$Horaordenc;
						if( $status == "Recuperac." ){
								if($registro[tpago]=="Cheque"){
										$ChequeR=$ChequeR+$registro[4];
								}elseif($registro[tpago]=="Tarjeta"){
										$TarjetaR=$TarjetaR+$registro[4];
								}elseif($registro[tpago]=="Transferencia"){
										$TransferenciaR=$TransferenciaR+$registro[4];
								}else{
										$EfectivoR=$EfectivoR+$registro[4];
								}             
						}else{
								if($registro[tpago]=="Cheque"){
								  	$Cheque=$Cheque+$registro[4];
								}elseif($registro[tpago]=="Tarjeta"){
								  	$Tarjeta=$Tarjeta+$registro[4];
								}elseif($registro[tpago]=="Transferencia"){
								  	$Transferencia=$Transferencia+$registro[4];
								}else{
								  	$Efectivo=$Efectivo+$registro[4];
								}
						}

        	}//fin while

							$InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$instnumero'");
							$NomB    = mysql_fetch_array($InstB);
							$instnombre=$NomB[nombre];
							$contaorden2=$contaorden;	

							if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;
							$nRng++;
							  $contaordenT=$contaordenT+$contaorden2;

							echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";		

							echo "<td align='CENTER' width='10'><a href=javascript:winuni('comisionesinst.php?FecI=$Fechai&FecF=$Fechaf&Institucion=$instnumero&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]')><font size='1' face='Arial, Helvetica, sans-serif'>$instnumero</a></font></td>";
							echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$instnombre</font></td>";
							echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$contaorden2</font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2')."</b></td>";
							echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo,"2")."</font></td>";
							echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($EfectivoR,"2")."</font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo+$EfectivoR,"2")."</font></b></td>";
							echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$Cheque+$Transferencia,"2")."</font></td>";
							echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$Cheque+$Transferencia+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></b></td>";
							echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2')."</b></font></td>";
							echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Totdia,'2')."</b></font></td>";
							echo "</tr>";
     					echo "</tr>";

//**********  T O T A L      G E N E R A L   *************

							 $Totgraldia=$Totgraldia+$Totdia;
							 $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
							 $Totgralingreso=$Totgralingreso+$Ingreso;
							 $Totgralrecupera=$Totgralrecupera+$Recupera2;
							 $Totgralingtot=$Totgralingtot+$IngTot;
							 $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
							 $Totgraltransferencias=$Totgraltransferencias+$Transferencias;
							 $Grantotalgral=$Grantotalgral+$Totalgral;
							 $Totalefectivo=$Totalefectivo+$Efectivo;
							 $TotalefectivoR=$TotalefectivoR+$EfectivoR;
							 $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
							 $Totaltarjeta=$Totaltarjeta+$Tarjeta;
							 $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
							 $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;

							 $Totalcheque=$Totalcheque+$Cheque;
							 $TotalchequeR=$TotalchequeR+$ChequeR;
							 $Graltotalcheque=$Graltotalcheque+$Gralcheque;

							 $Totaltranferencia=$Totaltranferencia+$Transferencia;
							 $TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
							 $Graltotaltranferencia=$Graltotaltranferencia+$Graltransferencia;

							 $Totalgral1=$Totalgral1+$Gral1;
							 $Totalgral2=$Totalgral2+$Gral2;
 							 $Totalgral3=$Totalgral3+$Gral3;

 							echo "<tr height='20' bgcolor='#CCCCCC'>";		

							echo "<td align='CENTER' width='10'></td>";
							echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>TOTALES GENERALES:</b></font></td>";
							echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><b>$contaordenT</b></font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;<b>$&nbsp;".number_format($Totgraldia,'2')."</b></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totalefectivo,"2")."</b></font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotalefectivoR,"2")."</b></font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totalefectivo+$TotalefectivoR,"2")."</font></b></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltarjeta+$Totaltranferencia+$TotalCheque,"2")."</b></font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotaltarjetaR+$TotaltranferenciaR+$TotalChequeR,"2")."</b></font></td>";
							echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltarjeta+$Totaltranferencia+$TotalCheque+$TotaltarjetaR+$TotaltranferenciaR+$TotalChequeR,"2")."</font></b></td>";
							echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgralingtot,'2')."</b></font></td>";
							echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgraldia-$Totgralingtot,'2')."</b></font></td>";
							echo "</tr>";
							echo "</table>";
			}else{
        $sql=$cSql;
        $res=mysql_query($sql,$link);
        $FechaAux=strtotime($Fecha);
        $nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
        $FechaAnt=date("Y-m-d",$nDias);
        $TotalIngreso=0;
				$Repetido2=0;
				$Orden1=0;
				$Tarjeta=0;
				$Efectivo=0;
				$Cheque=0;
				$TarjetaR=0;
				$EfectivoR=0;
				$Transferencia=0;
				$TransferenciaR=0;
				$ChequeR=0;
				$status=="Pagada";
        while($registro=mysql_fetch_array($res)) {
						$AbnA=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha <= '$FechaAnt'",$link);
						$Abonado=mysql_fetch_array($AbnA);
						$AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND id<>'$registro[0]'",$link);
						$AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
						$DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
						$Dto=mysql_fetch_array($DtoA);              
						$Horaorden1=substr($registro[horaorden],0,5);
						$Horaordenc=$registro[3];
						$Adeudo1=($registro[6]-($Abonado[0] + $registro[4] + $AbonadoD[0] ));

						$InstO=$registro[institucion];
						$instnumero=$registro[institucion];

						if ($Adeudo1<=1){
								if ($registro[4]==$registro[6]){
										$status="Pagado";
										$Ordenventa=$Dto[1];
										$Ordendesc=$Dto[0];
										$Ordenventot=$Dto[1]-$Dto[0];
										$Recupera=0;
										$Ingreso1=$registro[4];	
								}else{
										if ($Orden1==$registro[orden]){
												$Horaordenc3=$Horaordenc-$Horaordenc2;
												if ($Horaordenc3>=5){	
													$status="Recup. Inmed.";
													$Ordenventa=0;
													$Ordendesc=0;
													$Ordenventot=0;
													$Recupera=0;
													$Ingreso1=$registro[4];
													$Adeudo1=0;
													//$Recupera3=$registro[4];
												}else{
													$status="Pagado";
													$Ordenventa=0;
													$Ordendesc=0;
													$Ordenventot=0;
													$Recupera=0;
													$Ingreso1=$registro[4];
													$Adeudo1=0;
												}
										}else{
												if ($registro[1]==$registro[10]){	
													if ($Horaorden1<=$HoraI){
														$status="Recuperac.";
														$Ordenventa=0;
														$Ordendesc=0;
														$Ordenventot=0;
														$Recupera=$registro[4];
														$Ingreso1=0;
													}else{
														$status="Pagado";
														$Ordenventa=$Dto[1];
														$Ordendesc=$Dto[0];
														$Ordenventot=$Dto[1]-$Dto[0];
														$Recupera=0;
														$Ingreso1=$registro[4];	
													}	
												}else{	
													$status="Recuperac.";
													$Ordenventa=0;
													$Ordendesc=0;
													$Ordenventot=0;
													$Recupera=$registro[4];
													$Ingreso1=0;	
													$Recupera3=$registro[4];
												}
										}
								}
						}else{
							if ($Orden1==$registro[orden]){	
								if ($Abonado[0]<>0){	
									$status="Abono";
									$Ordenventa=$Dto[1];
									$Ordendesc=$Dto[0];
									$Ordenventot=0;
									$Recupera=$registro[4];
									$Ingreso1=0;	
								}else{
									$status="Recuperac.";
									$Ordenventa=0;
									$Ordendesc=0;
									$Ordenventot=0;
									$Recupera=$registro[4];
									$Ingreso1=0;
									
								}	
							}else{
								if ($Abonado[0]<>0){	
									$status="Recuperac.";
									$Ordenventa=0;
									$Ordendesc=0;
									$Ordenventot=0;
									$Recupera=$registro[4];
									$Ingreso1=0;
									$Adeudo1=0;
									$Recupera3=$registro[4];
								}else{
									$status="C / Adeudo";
									$Abonos=$Abonado[0];
									$Ordenventa=$Dto[1];
									$Ordendesc=$Dto[0];
									$Ordenventot=$Dto[1]-$Dto[0];
									$Recupera=0;
									$Ingreso1=$registro[4];
								}
							}					
						}
						$Orden1=$registro[orden];
						$Horaordenc2=$Horaordenc;
						if( $status == "Recuperac." ){
								if($registro[tpago]=="Cheque"){
									$ChequeR=$ChequeR+$registro[4];
								}elseif($registro[tpago]=="Tarjeta"){
									$TarjetaR=$TarjetaR+$registro[4];
								}elseif($registro[tpago]=="Transferencia"){
									$TransferenciaR=$TransferenciaR+$registro[4];
								}else{
									$EfectivoR=$EfectivoR+$registro[4];
								}             
		        }else{
								if($registro[tpago]=="Cheque"){
									$Cheque=$Cheque+$registro[4];
								}elseif($registro[tpago]=="Tarjeta"){
									$Tarjeta=$Tarjeta+$registro[4];
								}elseif($registro[tpago]=="Transferencia"){
									$Transferencia=$Transferencia+$registro[4];
								}else{
									$Efectivo=$Efectivo+$registro[4];
								}
	          }

						$Recupera2=$Recupera2+$Recupera;
						$TotAdeudo=$TotAdeudo+$Adeudo1;
						$Importe=$Importe+$Ordenventa-$Abonos;
						$TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
						$Totdia=$TotalOrd;
						$TotalIngreso=$TotalIngreso+$registro[4];
						$Descuento=$Descuento+$Ordendesc;
						$Ingreso=$Ingreso+$Ingreso1;
						$IngTot=$Ingreso+$Recupera2;
						$Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
						$Recupera3=0;
						$Abonos=0;

        }//fin while

				$InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$instnumero'");
				$NomB    = mysql_fetch_array($InstB);
				$instnombre=$NomB[nombre];
				$contaorden2=$contaorden;	

				if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;
				$nRng++;
				$contaordenT=$contaordenT+$contaorden2;

				echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";		

				echo "<td align='CENTER' width='10'><a href=javascript:winuni('comisionesinst.php?FecI=$Fechai&FecF=$Fechaf&Institucion=$instnumero&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]')><font size='1' face='Arial, Helvetica, sans-serif'>$instnumero</a></font></td>";
				echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$instnombre</font></td>";
				echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$Ordenes[0]</font></td>";
				echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2')."</b></td>";
				echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo,"2")."</font></td>";
				echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($EfectivoR,"2")."</font></td>";
				echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo+$EfectivoR,"2")."</font></b></td>";
				echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$Cheque+$Transferencia,"2")."</font></td>";
				echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
				echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$Cheque+$Transferencia+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></b></td>";
				echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2')."</b></font></td>";
				echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Totdia,'2')."</b></font></td>";
				echo "</tr>";
				echo "</table></td>";
			}
	}//fin if
//inicio del while para adeudos, recuperaciones y descuentos

echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=31&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('impcorte3pdf.php?FechaI=$Fechai&FechaF=$Fechaf&sucursalt=$sucursalt&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&Institucion=$Institucion1')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

echo "</form>";
echo "</div>";

?>
</body>
</html>
<?php
mysql_close();
?>
