<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();
  
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

  $Fechai=$_REQUEST[FechaI];

  $Fechaf=$_REQUEST[FechaF];

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title>Resumen Gral. de Corte de Caja </title>
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
			$Sucursal2= $Sucursal2 . "Camarones - ";
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
				
$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario as recepcionista,ot.fecha as fechaot,ot.hora as horaorden,ot.suc,ot.institucion 
	   FROM cja,ot
	   WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND ot.orden=cja.orden $and $parentesisa $Sucursal $parentesisc $CondifInst
	   ORDER BY recepcionista,cja.orden,cja.id,cja.hora";

$UpA=mysql_query($cSql,$link);

?>

<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>

    <td width="74%"><p align="left"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Laboratorio
        Clinico Duran</strong></font></p>
        <div> Institucion : <?php if($Institucion=='*'){ echo "*, Todas";}else{echo "$Institucion $Ins[nombre]";}?></div>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Resumen Gral. de Corte de Caja del <?php echo "$Fecha &nbsp; de : $HoraI a $HoraF Sucursal: $Sucursal2 ";?> hrs.<br>
        Recepcionista : &nbsp;
        <?php if($Recepcionista=="*"){echo "*, &nbsp;todos";}else{echo $Recepcionista;}?>
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
		echo "<tr><td colspan='17'><hr noshade></td></tr>";
		echo "<th align='CENTER' width='10%'><font size='1' face='Arial, Helvetica, sans-serif'>Nombre</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No Ordenes</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ventas</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Efectivo )</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Efec. )</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Efec.</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Tarjeta )</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Tarjeta )</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Tarjeta</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Transfer )</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>( Recup. Transfer )</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Transfer</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ingresos</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudos (CXC)</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Gastos</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total</font></th>";
		echo "<tr><td colspan='17'><hr noshade></td></tr>";

  	$res=mysql_query($cSql,$link);
		$Ini=1;
		$Tarjeta=0;
		$Efectivo=0;
		$Transferencia=0;
		$TransferenciaR=0;
		$TarjetaR=0;
		$EfectivoR=0;
		$contaorden=0;
		$Recep="";
		$nRng=0;

    while($registro=mysql_fetch_array($res)) {

    	if($Recep<>strtoupper($registro[recepcionista])){

    		if($Ini==1){

					if ($registro[fecha]>$registro[fechaot]){

						if($registro[tpago]=="Efectivo"){
							$EfectivoR=$EfectivoR+$registro[importe];
						}elseif($registro[tpago]=="Tarjeta"){
							$TarjetaR=$TarjetaR+$registro[importe];
						}elseif($registro[tpago]=="Transferencia"){
							$TransferenciaR=$TransferenciaR+$registro[importe];
						}

					}else{

						$RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registro[orden]' and id<'$registro[id]' and usuario<>'$registro[recepcionista]'",$link);
						$Regrecup=mysql_fetch_array($RegrecupA);

						if($Regrecup[ids]==0){

							if($registro[tpago]=="Efectivo"){
								$Efectivo=$Efectivo+$registro[importe];
							}elseif($registro[tpago]=="Tarjeta"){
								$Tarjeta=$Tarjeta+$registro[importe];
							}elseif($registro[tpago]=="Transferencia"){
								$Transferencia=$Transferencia+$registro[importe];
							}

							if($Orden2<>$registro[orden]){
								$contaorden++;
								$DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
								$Dto=mysql_fetch_array($DtoA);
								$Ordenventot=$Dto[1]-$Dto[0];
								$Totdia=$Totdia+$Ordenventot;
								$Totgraldia=$Totgraldia+$Ordenventot;
								$Orden2=$registro[orden];
							}

						}else{

								if($registro[tpago]=="Efectivo"){
									$EfectivoR=$EfectivoR+$registro[importe];
								}elseif($registro[tpago]=="Tarjeta"){
									$TarjetaR=$TarjetaR+$registro[importe];
								}elseif($registro[tpago]=="Transferencia"){
									$TransferenciaR=$TransferenciaR+$registro[importe];
								}

						}

					}

					$Ini++;

				}else{

					if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

					$Recepc[$nRng]=$Recep;
					
					$nRng++;

					$IngTot=$Efectivo+$EfectivoR+$Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR;
 
 					$GastosA=mysql_query("SELECT sum(monto) as tgastos FROM dpag_ref WHERE fechapago>='$Fechai' and fechapago<='$Fechaf' and usr='$Recep' and cancelada=0",$link);

					$Gasto=mysql_fetch_array($GastosA);

					$Gastos=$Gasto[tgastos];

					$GastosT=$GastosT+$Gasto[tgastos];

					echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
					echo "<td align='left' width='10'><a href=javascript:winuni('resgralrecep.php?FecI=$Fechai&FecF=$Fechaf&Recepcionista=$Recep&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]&Institucion=$Institucion')><font size='1' face='Arial, Helvetica, sans-serif'>$Recep</a></font></td>";
					echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$contaorden</font></td>";
					echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2')."</b></td>";
					echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo,"2")."</font></td>";
					echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($EfectivoR,"2")."</font></td>";
					echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo+$EfectivoR,"2")."</font></b></td>";
					echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta,"2")."</font></td>";
					echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TarjetaR,"2")."</font></td>";
					echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$TarjetaR,"2")."</font></b></td>";
					echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia,"2")."</font></td>";
					echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TransferenciaR,"2")."</font></td>";
					echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia+$TransferenciaR,"2")."</font></b></td>";
					echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2')."</b></font></td>";
					echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Totdia,'2')."</b></font></td>";
					echo "<td align='right'><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Gastos,'2')."</font></td>";
					echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Gastos,'2')."</b></font></td>";
					echo "</tr>";

					$Totalefectivo=$Totalefectivo+$Efectivo;
					$TotalefectivoR=$TotalefectivoR+$EfectivoR;
					$Totaltarjeta=$Totaltarjeta+$Tarjeta;
					$TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
					$Totaltranferencia=$Totaltranferencia+$Transferencia;
					$TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
					$contaordenT=$contaordenT+$contaorden;

					$Tarjeta=0;
					$Efectivo=0;
					$Transferencia=0;
					$TransferenciaR=0;
					$TarjetaR=0;
					$EfectivoR=0;
					$contaorden=0;
					$Totdia=0;

					if ($registro[fecha]>$registro[fechaot]){

						if($registro[tpago]=="Efectivo"){
							$EfectivoR=$EfectivoR+$registro[importe];
						}elseif($registro[tpago]=="Tarjeta"){
							$TarjetaR=$TarjetaR+$registro[importe];
						}elseif($registro[tpago]=="Transferencia"){
							$TransferenciaR=$TransferenciaR+$registro[importe];
						}
							
					}else{

						$RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registro[orden]' and id<'$registro[id]' and usuario<>'$registro[recepcionista]'",$link);
						$Regrecup=mysql_fetch_array($RegrecupA);

						if($Regrecup[ids]==0){

							if($registro[tpago]=="Efectivo"){
								$Efectivo=$Efectivo+$registro[importe];
							}elseif($registro[tpago]=="Tarjeta"){
								$Tarjeta=$Tarjeta+$registro[importe];
							}elseif($registro[tpago]=="Transferencia"){
								$Transferencia=$Transferencia+$registro[importe];
							}

							if($Orden2<>$registro[orden]){
								$contaorden++;
								$DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
								$Dto=mysql_fetch_array($DtoA);
								$Ordenventot=$Dto[1]-$Dto[0];
								$Totdia=$Totdia+$Ordenventot;
								$Totgraldia=$Totgraldia+$Ordenventot;
								$Orden2=$registro[orden];
							}

						}else{

								if($registro[tpago]=="Efectivo"){
									$EfectivoR=$EfectivoR+$registro[importe];
								}elseif($registro[tpago]=="Tarjeta"){
									$TarjetaR=$TarjetaR+$registro[importe];
								}elseif($registro[tpago]=="Transferencia"){
									$TransferenciaR=$TransferenciaR+$registro[importe];
								}

						}
					}
				
				}

    	}else{

				if ($registro[fecha]>$registro[fechaot]){

					if($registro[tpago]=="Efectivo"){
						$EfectivoR=$EfectivoR+$registro[importe];
					}elseif($registro[tpago]=="Tarjeta"){
						$TarjetaR=$TarjetaR+$registro[importe];
					}elseif($registro[tpago]=="Transferencia"){
						$TransferenciaR=$TransferenciaR+$registro[importe];
					}
						
				}else{

						$RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registro[orden]' and id<'$registro[id]' and usuario<>'$Recep'",$link);
						$Regrecup=mysql_fetch_array($RegrecupA);

						if($Regrecup[ids]==0){

							if($registro[tpago]=="Efectivo"){
								$Efectivo=$Efectivo+$registro[importe];
							}elseif($registro[tpago]=="Tarjeta"){
								$Tarjeta=$Tarjeta+$registro[importe];
							}elseif($registro[tpago]=="Transferencia"){
								$Transferencia=$Transferencia+$registro[importe];
							}

							if($Orden2<>$registro[orden]){
								$contaorden++;
								$DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
								$Dto=mysql_fetch_array($DtoA);
								$Ordenventot=$Dto[1]-$Dto[0];
								$Totdia=$Totdia+$Ordenventot;
								$Totgraldia=$Totgraldia+$Ordenventot;
								$Orden2=$registro[orden];
							}

						}else{

								if($registro[tpago]=="Efectivo"){
									$EfectivoR=$EfectivoR+$registro[importe];
								}elseif($registro[tpago]=="Tarjeta"){
									$TarjetaR=$TarjetaR+$registro[importe];
								}elseif($registro[tpago]=="Transferencia"){
									$TransferenciaR=$TransferenciaR+$registro[importe];
								}

						}

				}

				$Ini++;

    	}

			$Recep=strtoupper($registro[recepcionista]);
		}

		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;
							
		$Recepc[$nRng]=$Recep;

		$nRng++;

		$IngTot=$Efectivo+$EfectivoR+$Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR;

 		$GastosA=mysql_query("SELECT sum(monto) as tgastos FROM dpag_ref WHERE fechapago>='$Fechai' and fechapago<='$Fechaf' and usr='$Recep' and cancelada=0",$link);

		$Gasto=mysql_fetch_array($GastosA);

		$Gastos=$Gasto[tgastos];

		$GastosT=$GastosT+$Gasto[tgastos];

		echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		echo "<td align='left' width='10'><a href=javascript:winuni('resgralrecep.php?FecI=$Fechai&FecF=$Fechaf&Recepcionista=$Recep&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]&Institucion=$Institucion')><font size='1' face='Arial, Helvetica, sans-serif'>$Recep</a></font></td>";
		echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$contaorden</font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2')."</b></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo,"2")."</font></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($EfectivoR,"2")."</font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Efectivo+$EfectivoR,"2")."</font></b></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta,"2")."</font></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TarjetaR,"2")."</font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$TarjetaR,"2")."</font></b></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia,"2")."</font></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TransferenciaR,"2")."</font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia+$TransferenciaR,"2")."</font></b></td>";
		echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2')."</b></font></td>";
		echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Totdia,'2')."</b></font></td>";
		echo "<td align='right'><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Gastos,'2')."</font></td>";
		echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-$Gastos,'2')."</b></font></td>";

		$Totalefectivo=$Totalefectivo+$Efectivo;
		$TotalefectivoR=$TotalefectivoR+$EfectivoR;
		$Totaltarjeta=$Totaltarjeta+$Tarjeta;
		$TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
		$Totaltranferencia=$Totaltranferencia+$Transferencia;
		$TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
		$contaordenT=$contaordenT+$contaorden;

	}

//******** Usuario con gastos sin ot's capturadas **********//
	$contador=0;
	$condiciongasto=' ';
	$nRng2=$nRng-1;
	
	while($contador<=$nRng2){

		if ($contador>=50) {
        break;
    }

		$condiciongasto=$condiciongasto.' and usr<>"'.$Recepc[$contador].'" ';
		$contador++;
	}

	$GastosB=mysql_query("SELECT usr,sum(monto) as tgastos FROM dpag_ref WHERE fechapago>='$Fechai' and fechapago<='$Fechaf' and cancelada=0 $and $parentesisa $Sucgasto $parentesisc $condiciongasto group by usr order by usr ",$link);


	while($Gastosb=mysql_fetch_array($GastosB)){

		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

		$Gastos=$Gastosb[tgastos];
		$GastosT=$GastosT+$Gastos;

		echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		echo "<td align='left' width='10'><a href=javascript:winuni('resgralrecep.php?FecI=$Fechai&FecF=$Fechaf&Recepcionista=$Gastosb[usr]&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]&Institucion=$Institucion')><font size='1' face='Arial, Helvetica, sans-serif'>$Gastosb[usr]</a></font></td>";
		echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></b></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></b></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></b></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
		echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></font></b></td>";
		echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></b></font></td>";
		echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'></b></font></td>";
		echo "<td align='right'><div align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Gastos,'2')."</font></td>";
		echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format(0-$Gastos,'2')."</b></font></td>";
		$nRng++;
	}


//******** Totales **********//

	$Totgralingtot=$Totalefectivo+$TotalefectivoR+$Totaltarjeta+$TotaltarjetaR+$Totaltranferencia+$TotaltranferenciaR;

if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>TOTALES GENERALES:</b></font></td>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><b>$contaordenT</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;<b>$&nbsp;".number_format($Totgraldia,'2')."</b></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totalefectivo,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotalefectivoR,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totalefectivo+$TotalefectivoR,"2")."</font></b></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltarjeta,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotaltarjetaR,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltarjeta+$TotaltarjetaR,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltranferencia,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotaltranferenciaR,"2")."</b></font></td>";
echo "<td><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltranferencia+$TotaltranferenciaR,"2")."</b></font></td>";
echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgralingtot,'2')."</b></font></td>";
echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgraldia-$Totgralingtot,'2')."</b></font></td>";
echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($GastosT,'2')."</b></font></td>";
echo "<td align='right'><div align='right'><b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgralingtot-$GastosT,'2')."</b></font></td>";
echo "</tr>";
echo "</table>";
echo "<br>";
echo "<br>";
				
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=32&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('impcortepdf.php?Fecha=$Fecha&sucursalt=$sucursalt&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&Institucion=$Institucion1&Recepcionista=$Recepcionista&HoraI=$HoraI&HoraF=$HoraF')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

echo "</form>";
echo "</div>";

?>
</body>
</html>
<?php
mysql_close();
?>
