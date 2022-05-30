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

  $Fecha=$_REQUEST[FechaI];

  $Suc                  =       $_COOKIE['TEAM'];        //Sucursal 

  $Sucursal = $_REQUEST[Sucursal];

  $Institucion=$_REQUEST[Institucion];

  $Fechai=$_REQUEST[FechaI];

  $Fechaf=$_REQUEST[FechaF];


  $Titulo=$_REQUEST[Titulo];

  ?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Reporte de Pagos / Adeudos</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>
  
  
  
  <?php


  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
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
			$Sucursal2= $Sucursal2 . "San Vicente - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}

	if ($Sucursal <> '*') {
		
		$cSql="SELECT cja.id,cja.fecha as cjafecha,cja.orden,cja.hora,cja.importe as cjaimporte,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden,ot.suc
				   FROM cja,ot
				   WHERE cja.fecha>='$Fechai' and cja.fecha <='$Fechaf' AND ot.orden=cja.orden and ($Sucursal) 
				   ORDER BY ot.institucion,ot.orden";
			
			$OtNum="SELECT count(orden) FROM ot WHERE  fecha>='$Fechai' and
                       fecha <='$Fechaf' AND ($Sucursal)";

	}else{

		$cSql="SELECT cja.id,cja.fecha as cjafecha,cja.orden,cja.hora,cja.importe as cjaimporte,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				   FROM cja,ot
				   WHERE cja.fecha>='$Fechai' and cja.fecha <='$Fechaf' AND ot.orden=cja.orden
				   ORDER BY ot.institucion,ot.orden";
			
			$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and
                       fecha <='$Fechaf'";
			
	}


$UpA=mysql_query($cSql,$link);

$OtNumA=mysql_query($OtNum,$link);

$Ordenes=mysql_fetch_array($OtNumA);

$Hora=date("h:i:s");

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
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ventas</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudos</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Efec.</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Tarj.</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Cheque</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Transfer.</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Total</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.Efec.</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.Tarj.</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.Cheque</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.Transfer.</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.Total</font></th>";
echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total</font></th>";

echo "<tr><td colspan='15'><hr noshade></td></tr>";
  if(!$res=mysql_fetch_array($UpA)){
        echo "<div align='center'>";
        echo "<font face='verdana' size='2'>No se encontraron resultados</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=30'>";
        echo "Regresar</a></font>";
         echo "</div>";
     }else{
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
			$TarjetaR=0;
			$EfectivoR=0;
			$ChequeR=0;
			$TransferenciaR=0;
			$status="";
			$contaorden=0;

	        while($registro=mysql_fetch_array($res)) {
				 //$InstO=$registro[institucion];
				      $AbnA=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha <= '$FechaAnt'",$link);
            		  $Abonado=mysql_fetch_array($AbnA);
		              $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' fecha>='$Fechai' and fecha <='$Fechaf' AND id<>'$registro[id]'",$link);
        		      $AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
		              $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
        		      $Dto=mysql_fetch_array($DtoA);
   		              $DtoA2=mysql_query("SELECT importe FROM ot WHERE orden ='$registro[orden]'",$link);
        		      $Dto2=mysql_fetch_array($DtoA2);              
					  $Horaorden1=substr($registro[horaorden],0,5);
					  $Horaordenc=$registro[3];
					  $Adeudo1=($registro[importe]-($Abonado[0] + $registro[cjaimporte] + $AbonadoD[0] ));
					  			$status="";
					  if ($Adeudo1<=1){
			 				if ($registro[cjaimporte]==$registro[importe]){
								$status="Pagado";
								$Ordenventa=$Dto[1];
								$Ordendesc=$Dto[0];
								//$Ordenventot=$Dto[1]-$Dto[0];
								$Ordenventot=$Dto2[importe];
								$Recupera=0;
								$Ingreso1=$registro[cjaimporte];	
							}else{
								if ($Orden1==$registro[orden]){
									$Horaordenc3=$Horaordenc-$Horaordenc2;
									if ($Horaordenc3>=5){	
										$status="Recup. Inmed.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=0;
										$Ingreso1=$registro[cjaimporte];
										$Adeudo1=0;
										//$Recupera3=$registro[cjaimporte];
									}else{
										$status="Pagado";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=0;
										$Ingreso1=$registro[cjaimporte];
										$Adeudo1=0;
									}
								}else{
									if ($registro[cjafecha]==$registro[fecha]){	
										if ($Horaorden1<=$HoraI){
											$status="Recuperac.";
											$Ordenventa=0;
											$Ordendesc=0;
											$Ordenventot=0;
											$Recupera=$registro[cjaimporte];
											$Ingreso1=0;
										}else{
											$status="Pagado";
											$Ordenventa=$Dto[1];
											$Ordendesc=$Dto[0];
											//$Ordenventot=$Dto[1]-$Dto[0];
											$Ordenventot=$Dto2[importe];
											$Recupera=0;
											$Ingreso1=$registro[cjaimporte];	
										}	
									}else{	
										$status="Recuperac.";
										$Ordenventa=0;
										$Ordendesc=0;
										$Ordenventot=0;
										$Recupera=$registro[cjaimporte];
										$Ingreso1=0;	
										$Recupera3=$registro[cjaimporte];
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
								$Recupera=$registro[cjaimporte];
								$Ingreso1=0;	
							}else{
								$status="Recuperac.";
								$Ordenventa=0;
								$Ordendesc=0;
								$Ordenventot=0;
								$Recupera=$registro[cjaimporte];
								$Ingreso1=0;
								$Adeudo1=0;
							}	
						}else{
							if ($Abonado[0]<>0){	
								$status="Recuperac.";
								$Ordenventa=0;
								$Ordendesc=0;
								$Ordenventot=0;
								$Recupera=$registro[cjaimporte];
								$Ingreso1=0;
								$Adeudo1=0;
								$Recupera3=$registro[cjaimporte];
							}else{
								$status="C / Adeudo";
								$Abonos=$Abonado[0];
								$Ordenventa=$Dto[1];
								$Ordendesc=$Dto[0];
								//$Ordenventot=$Dto[1]-$Dto[0];
								$Ordenventot=$Dto2[importe];

								$Recupera=0;
								$Ingreso1=$registro[cjaimporte];
							}
						}					
					}
				 if($InstO<>$registro[institucion]){
					 if($Ini==1){

					 		
							$Recupera2=$Recupera2+$Recupera;
							 $TotAdeudo=$TotAdeudo+$Adeudo1;
							 $Importe=$Importe+$Ordenventa-$Abonos;
							 $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
							 $Totdia=$TotalOrd;
							 $TotalIngreso=$TotalIngreso+$registro[cjaimporte];
							 $Descuento=$Descuento+$Ordendesc;
							 $Ingreso=$Ingreso+$Ingreso1;
							 $IngTot=$Ingreso+$Recupera2;
							 $Saldo=$Saldo+($registro[importe]-($Abonado[0] + $registro[cjaimporte]));
							 $Recupera3=0;
							 $Abonos=0;
							 $Ini=$Ini+1;
							 $InstO=$registro[institucion];
							 if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
								$no=$no+1;
							}else{
								$no=$no+0;
							}


						}else{


							$InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$InstO'");
  							$NomB    = mysql_fetch_array($InstB);

  							if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;


							echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
							echo "<th align='CENTER'><a href=javascript:winuni('comisionesinst.php?FecI=$Fechai&FecF=$Fechaf&Institucion=$InstO&sucursal=$Sucursalind')><font size='1' face='Arial, Helvetica, sans-serif'>$InstO</a></font></th>";
							echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$NomB[nombre]</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Totdia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotAdeudo,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Efectivo,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Tarjeta,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Cheque,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Transferencia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($EfectivoR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TarjetaR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ChequeR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TransferenciaR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,'2')."</font></th>";

							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,'2')."</font></th>";
							echo "</tr>";

							$no=0;

							$nRng++;	
							

							$Tarjetas=$Tarjeta+$TarjetaR;
	
							$Totalgral=$IngTot-($Tarjeta+$TarjetaR);

							$Gralefectivo=$Efectivo+$EfectivoR;

							$Graltarjeta=$Tarjeta+$TarjetaR;

							$Gralcheque=$Cheque+$ChequeR;

							$Gral1=$Efectivo+$Tarjeta+$Cheque;
							$Gral2=$EfectivoR+$TarjetaR+$ChequeR;
							$Gral3=$Efectivo+$Tarjeta+$Cheque+$EfectivoR+$TarjetaR+$ChequeR;
							
							$Totgraldia=$Totgraldia+$Totdia;
							 $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
							 $Totgralingreso=$Totgralingreso+$Ingreso;
							 $Totgralrecupera=$Totgralrecupera+$Recupera2;
							 $Totgralingtot=$Totgralingtot+$IngTot;
							 $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
							 $Grantotalgral=$Grantotalgral+$Totalgral;
							 $Totalefectivo=$Totalefectivo+$Efectivo;
							 $TotalTransferencia=$TotalTransferencia+$Transferencia;
							 $TotalTransferenciaR=$TotalTransferenciaR+$TransferenciaR;
							 $TotalefectivoR=$TotalefectivoR+$EfectivoR;
							 $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
							 $Totaltarjeta=$Totaltarjeta+$Tarjeta;
							 $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
							 $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;
							 $Totalcheque=$Totalcheque+$Cheque;
							 $TotalchequeR=$TotalchequeR+$ChequeR;
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
							// $InstO=$registro[institucion];
							$contaorden=0;
							 $Tarjeta=0;
							 $Efectivo=0;
							 $Cheque=0;
							 $Transferencia=0;
							 $TarjetaR=0;
							 $EfectivoR=0;
							 $ChequeR=0;
							 $TransferenciaR=0;
							 $status="";
							 							 
							//echo "</table>";
							$Recupera2=$Recupera2+$Recupera;
							 $TotAdeudo=$TotAdeudo+$Adeudo1;
							 $Importe=$Importe+$Ordenventa-$Abonos;
							 $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
							 $Totdia=$TotalOrd;
							 $TotalIngreso=$TotalIngreso+$registro[cjaimporte];
							 $Descuento=$Descuento+$Ordendesc;
							 $Ingreso=$Ingreso+$Ingreso1;
							 $IngTot=$Ingreso+$Recupera2;
							 $Saldo=$Saldo+($registro[importe]-($Abonado[0] + $registro[cjaimporte]));
							 $Recupera3=0;
							 $Abonos=0;
							 $Ini=$Ini+1;
							 $InstO=$registro[institucion];
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

//			          echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
					  
					//  echo "</table>";
					 $Recupera2=$Recupera2+$Recupera;
					 $TotAdeudo=$TotAdeudo+$Adeudo1;
					 $Importe=$Importe+$Ordenventa-$Abonos;
					 $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
					 $Totdia=$TotalOrd;
					 $TotalIngreso=$TotalIngreso+$registro[cjaimporte];
					 $Descuento=$Descuento+$Ordendesc;
					 $Ingreso=$Ingreso+$Ingreso1;
					 $IngTot=$Ingreso+$Recupera2;
					 $Saldo=$Saldo+($registro[importe]-($Abonado[0] + $registro[cjaimporte]));
					 $Recupera3=0;
					 $Abonos=0;
					 $contaorden=$contaorden+1;
					 //$InstO=$registro[institucion];
					 $Ini=$Ini+1;
				 //}else{
					//echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
				 //}	   
			 		//$Orden1=$registro[orden];
     
			 }
					$Orden1=$registro[orden];
					$Horaordenc2=$Horaordenc;
					if( $status == "Recuperac." ){
		                 if($registro[tpago]=="Tarjeta"){
        			            $TarjetaR=$TarjetaR+$registro[cjaimporte];
		                 }elseif($registro[tpago]=="Cheque"){
                			    $ChequeR=$ChequeR+$registro[cjaimporte];
		                 }elseif($registro[tpago]=="Transferencia"){
		                    	$TransferenciaR=$TransferenciaR+$registro[cjaimporte];
		                 }elseif($registro[tpago]=="Efectivo"){
        		            	$EfectivoR=$EfectivoR+$registro[cjaimporte];
                		 }          
		             }else{
		                 if($registro[tpago]=="Cheque"){
        		            $Cheque=$Cheque+$registro[cjaimporte];
                		 }elseif($registro[tpago]=="Tarjeta"){
		                    $Tarjeta=$Tarjeta+$registro[cjaimporte];
		           		 }elseif($registro[tpago]=="Transferencia"){
		                    $Transferencia=$Transferencia+$registro[cjaimporte];
        		         }elseif($registro[tpago]=="Efectivo"){
                		    $Efectivo=$Efectivo+$registro[cjaimporte];
	                 }

				}

        }//fin while
							$InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$InstO'");
  							$NomB    = mysql_fetch_array($InstB);

							echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$InstO</font></th>";
							echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$NomB[nombre]</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Totdia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotAdeudo,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Efectivo,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Tarjeta,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Cheque,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Transferencia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($EfectivoR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TarjetaR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ChequeR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TransferenciaR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,'2')."</font></th>";

							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,'2')."</font></th>";
							echo "</tr>";

							
							 $Totgraldia=$Totgraldia+$Totdia;
							 $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
							 $Totgralingreso=$Totgralingreso+$Ingreso;
							 $Totgralrecupera=$Totgralrecupera+$Recupera2;
							 $Totgralingtot=$Totgralingtot+$IngTot;
							 $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
							 $Grantotalgral=$Grantotalgral+$Totalgral;
							 $Totalefectivo=$Totalefectivo+$Efectivo;
							 $TotalTransferencia=$TotalTransferencia+$Transferencia;
							 $TotalTransferenciaR=$TotalTransferenciaR+$TransferenciaR;
							 $TotalefectivoR=$TotalefectivoR+$EfectivoR;
							 $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
							 $Totaltarjeta=$Totaltarjeta+$Tarjeta;
							 $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
							 $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;
							 $Totalcheque=$Totalcheque+$Cheque;
							 $TotalchequeR=$TotalchequeR+$ChequeR;
							 $Graltotalcheque=$Graltotalcheque+$Gralcheque;
							 $Totalgral1=$Totalgral1+$Gral1;
							 $Totalgral2=$Totalgral2+$Gral2;
 							 $Totalgral3=$Totalgral3+$Gral3;

							echo "<tr><td colspan='15'><hr noshade></td></tr>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total de Instituciones $nRng</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totgraldia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totgraladeudo,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totalefectivo,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totaltarjeta,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totalcheque,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($TotalTransferencia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totalefectivo+$Totaltarjeta+$Totalcheque+$TotalTransferencia,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($TotalefectivoR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($TotaltarjetaR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($TotalchequeR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($TotalTransferenciaR,'2')."</font></th>";
							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($TotalefectivoR+$TotaltarjetaR+$TotalchequeR+$TotalTransferenciaR,'2')."</font></th>";

							echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$ ".number_format($Totalefectivo+$Totaltarjeta+$Totalcheque+$TotalTransferencia+$TotalefectivoR+$TotaltarjetaR+$TotalchequeR+$TotalTransferenciaR,'2')."</font></th>";
							echo "<tr><td colspan='15'><hr noshade></td></tr>";
							echo "</table>";
				
							echo "<table width='100%' border='0' align='center'>";
							echo "<tr>";
							echo "<td width='50%'><table width='60%' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td width='60%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>VENTAS TOTALES GENERALES:</td>";
							echo "<td width='40%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgraldia,'2');//"</td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ADEUDOS GENERALES:</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgraladeudo,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>INGRESO DEL DIA GENERAL:</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgralingreso,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACIONES GENERALES:</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgralrecupera,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL GENERALES:</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgralingtot,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TARJETA GENERAL:</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totgraltarjetas,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
		
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>GRAN TOTAL GRAL.:</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Grantotalgral,'2');
							echo "</tr>";
							echo "</table></td>";
							echo "<td><table width='80%' align='center' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TIPO PAGO GENERAL</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>DEL DIA GENERAL</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACION GENERAL</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>GRAN TOTAL GENERAL</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Efectivo</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalefectivo,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalefectivoR,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalefectivoR+$Totalefectivo,'2')."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Tarjeta</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totaltarjeta,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotaltarjetaR,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotaltarjetaR+$Totaltarjeta,'2')."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Cheque</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalcheque,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalchequeR,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalchequeR+$Totalcheque,'2')."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Transferencia</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalTransferencia,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalTransferenciaR,'2')."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalTransferenciaR+$TotalTransferencia,'2')."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td>&nbsp;</td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totgralingreso,'2')."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalefectivoR+$TotaltarjetaR,'2')."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totgralingreso+$TotalefectivoR+$TotaltarjetaR,'2')."</font></td>";
							echo "</tr>";
							echo "</table></td>";
							echo "</tr>";
							echo "<tr> </tr>";
							echo "<tr> </tr>";
							echo "</table>";
							echo "<hr noshade style='color:000099;height:1px'>";

     		echo "</tr>";
		echo "</table>";
	 }
    }//fin if
//inicio del while para adeudos, recuperaciones y descuentos
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=30&fechas=1&Fechai=$Fechai&Fechaf=$Fechaf'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";


echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('impcorte2pdf.php?Fechai=$Fechai&Fechaf=$Fechaf&Institucion=$Institucion&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";
echo "</form>";
echo "</div>";

?>
</body>
</html>