<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $level       = $check['level'];

  $Usr= $Recepcionista=$_REQUEST[Recepcionista];
  
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

  $Fecha=$_REQUEST[Fecha];

  $HoraI=$_REQUEST[HoraI];

  $HoraF=$_REQUEST[HoraF];
  
  $Completo=$_REQUEST[Completo];
  
  if ($Completo=='Dia_Completo'){
	  
 	 $HoraI='00:00';

  	 $HoraF='23:59';
  }

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
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}

if($_REQUEST[Institucion]=='CONTA'){

 	if($Recepcionista=="*"){

        if ($Sucursal <> '*') {
			
			$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND ($Sucursal) order by cja.orden,cja.id";
	
			$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND ($Sucursal)";
			
			
		}else{

			$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' order by cja.orden,cja.id";
	
			$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85'";
			
		}
		
	}else{
		
        if ($Sucursal <> '*') {

			$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND cja.usuario='$Recepcionista' AND ($Sucursal) order by cja.orden,cja.id";
	
			$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85'  AND recepcionista='$Recepcionista' AND ($Sucursal)";

		}else{

			$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND cja.usuario='$Recepcionista' order by cja.orden,cja.id";
	
			$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85'  AND recepcionista='$Recepcionista'";
		
		}
		
	}
}else{

	if($_REQUEST[Institucion]=='*'){

   		if($Recepcionista=="*"){
			
	        if ($Sucursal <> '*') {
				
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
						   cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
						   FROM cja,ot
						   WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND ($Sucursal) 
						   ORDER BY ot.institucion,ot.orden,cja.id";
					
					$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND ($Sucursal)";

			}else{

				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
						   cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
						   FROM cja,ot
						   WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' 
						   ORDER BY ot.institucion,ot.orden,cja.id";
					
					$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha'";
					
			}

	   }else{
		   
   	        if ($Sucursal <> '*') {
				
				//$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.suc='$Suc' order by ot.orden,cja.id";
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				FROM cja,ot
				WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND ($Sucursal)
				order by ot.institucion,ot.orden,cja.id";
	
				//$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND cja.suc='$Suc'";
				$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND ($Sucursal)";
				
			}else{
	
				//$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.suc='$Suc' order by ot.orden,cja.id";
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				FROM cja,ot
				WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' 
				order by ot.institucion,ot.orden,cja.id";
	
				//$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND cja.suc='$Suc'";
				$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista'";
			
			}

	   }
	}else{

	   if($Recepcionista=="*"){
		   
	       if ($Sucursal <> '*') {
			   
			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion='$Institucion' AND ($Sucursal)";

			   
		   }else{

			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion='$Institucion'";
			  
		   }

	   }else{
		   
		  if ($Sucursal <> '*') {
			  
			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by cja.orden,cja.id";
	
			  //$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,inst.alias,ot.fecha,ot.hora as horaorden FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND ot.institucion=inst.institucion  AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' order by ot.orden";
	
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND institucion='$Institucion' AND ($Sucursal)";
			  
		  }else{

			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by cja.orden,cja.id";
	
			  //$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,inst.alias,ot.fecha,ot.hora as horaorden FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND ot.institucion=inst.institucion  AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' order by ot.orden";
	
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND institucion='$Institucion'";
		  
		  }

	   }

	}

}


//$InsA=mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'",$link);
//$Ins=mysql_fetch_array($InsA);

//echo $cSql;
if($level==9 or $level==7 or $level==99){


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
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Corte de Caja del <?php echo "$Fecha &nbsp; de : $HoraI a $HoraF Sucursal: $Sucursal2 ";?> hrs.<br>
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
        echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=1'>";
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
							$AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha = '$Fecha' AND id<>'$registro[0]'",$link);
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
										$status="Pagado*";
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

					 		echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
							echo "<tr><td colspan='14'><hr noshade></td></tr>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>#Recibo</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No.Ord</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recepcionista</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Status O.</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Venta</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Descuento</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Vta</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ingreso</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Total</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudo</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Tpo.pago</font></th>";
							echo "<tr><td colspan='14'><hr noshade></td></tr>";
							echo"<tr>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[id]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[institucion] - $registro[orden]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[8]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[fecha]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>".substr($registro[3],0,5)."</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$status</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventa/*$Dto[1]*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordendesc/*$Dto[0]*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventot/*$Dto[1] - $Dto[0]*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso1,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera+$Ingreso1/*-$Recupera3*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Adeudo1,'2')."</font></td>";
							echo"<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[5]</font></td>";
							$contaorden=$contaorden+1;
							echo"</tr>";
							echo"<p><font size='1' face='Arial, Helvetica, sans-serif'>"; 
							//echo "</table>";
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
							 if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
								$no=$no+1;
							}else{
								$no=$no+0;
							}

						}else{
							//echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
							echo "<tr><td colspan='14'><hr></td></tr>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotalOrd,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera2,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($IngTot,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotAdeudo,'2')."</strong></font></td>";
							echo "<td><font size='1' face='Arial, Helvetica, sans-serif' color='#0066FF'></font></td>";
							echo "<tr><td colspan='14'><hr></td></tr>";
							echo "</table>";
							$contaorden2=$contaorden-$no;
							echo "<div align='center'>";
							echo "<p align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ORDENES ABIERTAS : $contaorden2";
							echo "</strong></font></div>";        
							echo "<br>";
							$no=0;
							echo "<table width='80%' border='0' align='center'>";
							echo "<tr>";
							echo "<td width='40%'><table width='25%' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td width='60%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>VENTAS TOTALES :</td>";
							echo "<td width='40%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2');//"</td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ADEUDOS :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotAdeudo,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>INGRESO DEL DIA :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Ingreso,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACIONES :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Recupera2,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL :</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2');
							echo "</tr>";
							$Tarjetas=$Tarjeta+$TarjetaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TARJETA :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$TarjetaR,'2');
							echo "</tr>";
							$Transferencias=$Transferencia+$TransferenciaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TRANSFERENCIA :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia+$TransferenciaR,'2');
							echo "</tr>";
						    $Cheques=$Cheque+$ChequeR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>CHEQUE :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Cheque+$ChequeR,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							$Totalgral=$IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR);
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL GRAL.:</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR),'2');
							echo "</tr>";
							echo "</table></td>";
							echo "<td><table width='90%' align='center' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TIPO PAGO</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>DEL DIA</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACION</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL</font></td>";
							echo "</tr>";
							$Gralefectivo=$Efectivo+$EfectivoR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Efectivo</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($EfectivoR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$EfectivoR,"2")."</font></td>";
							echo "</tr>";
							$Graltarjeta=$Tarjeta+$TarjetaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Tarjeta</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Tarjeta,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TarjetaR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Tarjeta+$TarjetaR,"2")."</font></td>";
							echo "</tr>";
							$Gralcheque=$Cheque+$ChequeR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Cheque</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Cheque,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ChequeR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Cheque+$ChequeR,"2")."</font></td>";
							echo "</tr>";
							$Graltrans=$Transferencia+$TransferenciaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Transferencia</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Transferencia,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TransferenciaR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Transferencia+$TransferenciaR,"2")."</font></td>";
							echo "</tr>";
							$Gral1=$Efectivo+$Tarjeta+$Cheque+$Transferencia;
							$Gral2=$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;
							$Gral3=$Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;
							echo "<tr bordercolor='#CCCC+$TransferenciaRCC'>";
							echo "<td>&nbsp;</td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,"2")."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
							echo "</tr>";
							echo "</table></td>";
							echo "</tr>";
							echo "<tr> </tr>";
							echo "<tr> </tr>";
							echo "</table>";
							echo "<hr noshade style='color:000099;height:1px'>";
							
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
							// $InstO=$registro[institucion];
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
							 							 
							 echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
							 echo "<tr></tr>";
							 echo "<tr></tr>";
							 echo "<tr></tr>";
							echo "<tr><td colspan='14'><hr noshade></td></tr>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>#Recibo</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No.Ord</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recepcionista</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Status O.</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Venta</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Descuento</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Total Vta</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ingreso</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Total</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudo</font></th>";
							echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Tpo.pago</font></th>";
							echo "<tr><td colspan='14'><hr noshade></td></tr>";
							echo"<tr>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[id]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[institucion] - $registro[orden]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[8]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[fecha]</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>".substr($registro[3],0,5)."</font></td>";
							echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$status</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventa/*$Dto[1]*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordendesc/*$Dto[0]*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventot/*$Dto[1] - $Dto[0]*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso1,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera+$Ingreso1/*-$Recupera3*/,'2')."</font></td>";
							echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Adeudo1,'2')."</font></td>";
							echo"<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[5]</font></td>";
							$contaorden=$contaorden+1;
							echo"</tr>";
							echo"<p><font size='1' face='Arial, Helvetica, sans-serif'>"; 
							//echo "</table>";
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
					  echo"<tr></tr>";
					  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[id]</font></td>";
					  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[institucion] - $registro[orden]</font></td>";
					  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[8]</font></td>";
					  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[fecha]</font></td>";
					  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>".substr($registro[3],0,5)."</font></td>";
					  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$status</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventa/*$Dto[1]*/,'2')."</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordendesc/*$Dto[0]*/,'2')."</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventot/*$Dto[1] - $Dto[0]*/,'2')."</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera,'2')."</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso1,'2')."</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera+$Ingreso1/*-$Recupera3*/,'2')."</font></td>";
					  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Adeudo1,'2')."</font></td>";
					  echo"<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[5]</font></td>";
					//  echo "</table>";
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
							echo "<tr><td colspan='14'><hr></td></tr>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotalOrd,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera2,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($IngTot,'2')."</strong></font></td>";
							echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotAdeudo,'2')."</strong></font></td>";
							echo "<td><font size='1' face='Arial, Helvetica, sans-serif' color='#0066FF'></font></td>";
							echo "<tr><td colspan='14'><hr></td></tr>";
							echo "</table>";
							$contaorden2=$contaorden-$no;						
							echo "<div align='center'>";
							echo "<p align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ORDENES ABIERTAS : $contaorden2";
							echo "</strong></font></div>";        
							echo "<br>";
							$no=0;
							echo "<table width='80%' border='0' align='center'>";
							echo "<tr>";
							echo "<td width='40%'><table width='25%' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td width='60%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>VENTAS TOTALES :</td>";
							echo "<td width='40%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2');//"</td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ADEUDOS :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotAdeudo,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>INGRESO DEL DIA :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Ingreso,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACIONES :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Recupera2,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL :</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2');
							echo "</tr>";
							$Tarjetas=$Tarjeta+$TarjetaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TARJETA :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$TarjetaR,'2');
							echo "</tr>";
							$Transferencias=$Transferencia+$TransferenciaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TRANSFERENCIA :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia+$TransferenciaR,'2');
							echo "</tr>";
						    $Cheques=$Cheque+$ChequeR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>CHEQUE :</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Cheque+$ChequeR,'2');
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							$Totalgral=$IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR);
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL GRAL.:</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR),'2');
							echo "</tr>";
							echo "</table></td>";
							echo "<td><table width='90%' align='center' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TIPO PAGO</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>DEL DIA</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACION</font></td>";
							echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL</font></td>";
							echo "</tr>";
							$Gralefectivo=$Efectivo+$EfectivoR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Efectivo</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($EfectivoR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$EfectivoR,"2")."</font></td>";
							echo "</tr>";
							$Graltarjeta=$Tarjeta+$TarjetaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Tarjeta</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Tarjeta,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TarjetaR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Tarjeta+$TarjetaR,"2")."</font></td>";
							echo "</tr>";
							$Gralcheque=$Cheque+$ChequeR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Cheque</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Cheque,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ChequeR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Cheque+$ChequeR,"2")."</font></td>";
							echo "</tr>";
							$Graltransferencia=$Transferencia+$TransferenciaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Transferencia</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Transferencia,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TransferenciaR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Transferencia+$TransferenciaR,"2")."</font></td>";
							echo "</tr>";
							$Gral1=$Efectivo+$Tarjeta+$Cheque+$Transferencia;
							$Gral2=$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;
							$Gral3=$Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td>&nbsp;</td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,"2")."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
							echo "</tr>";
							echo "</table></td>";
							echo "</tr>";
							echo "<tr> </tr>";
							echo "<tr> </tr>";
							echo "</table>";
							echo "<hr noshade style='color:000099;height:1px'>";

     		echo "</tr>";
		echo "</table>";


//**********  G A S T O S  *************
$SucursalGas= "";
	
if($sucursalt=="1"){  

	$SucursalGas="*";
	$Sucursal2= " * - Todas ";
}else{

	if($sucursal0=="1"){  
		$SucursalGas= " dpag_ref.suc=0";
		$Sucursal2= "Administracion - ";
	}
	
	if($sucursal1=="1"){ 
		$Sucursal2= $Sucursal2 . "Laboratorio - "; 
		if($SucursalGas==""){
			$SucursalGas= $SucursalGas . " dpag_ref.suc=1";
		}else{
			$SucursalGas= $SucursalGas. " OR dpag_ref.suc=1";
		}
	}
	
	if($sucursal2=="1"){
		$Sucursal2= $Sucursal2 . "Hospital Futura - ";
		if($SucursalGas==""){
			$SucursalGas= $SucursalGas . " dpag_ref.suc=2";
		}else{
			$SucursalGas= $SucursalGas . " OR dpag_ref.suc=2";
		}
	}
	
	if($sucursal3=="1"){
		$Sucursal2= $Sucursal2 . "Tepexpan - ";
		if($SucursalGas==""){
			$SucursalGas= $SucursalGas . " dpag_ref.suc=3";
		}else{
			$SucursalGas= $SucursalGas . " OR dpag_ref.suc=3";
		}
	}
	
	if($sucursal4=="1"){
		$Sucursal2= $Sucursal2 . "Los Reyes - ";
		if($SucursalGas==""){
			$SucursalGas= $SucursalGas . " dpag_ref.suc=4";
		}else{
			$SucursalGas= $SucursalGas . " OR dpag_ref.suc=4";
		}
	}	

	if($sucursal5=="1"){
		$Sucursal2= $Sucursal2 . "Camarones - ";
		if($SucursalGas==""){
			$SucursalGas= $SucursalGas . " dpag_ref.suc=5";
		}else{
			$SucursalGas= $SucursalGas . " OR dpag_ref.suc=5";
		}
	}
	if($sucursal6=="1"){
		$Sucursal2= $Sucursal2 . "Camarones - ";
		if($SucursalGas==""){
			$SucursalGas= $SucursalGas . " dpag_ref.suc=6";
		}else{
			$SucursalGas= $SucursalGas . " OR dpag_ref.suc=6";
		}
	}
}

if($SucursalGas=='*'){

		if($Usr=='*'){

			$GastosA ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
			. "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
			. "FROM dpag_ref "
			. "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
			. "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
			. "WHERE"
			. " date(dpag_ref.fechapago)='$Fecha' "
			. "AND dpag_ref.cancelada=0";

		}else{

			$GastosA ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
			. "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
			. "FROM dpag_ref "
			. "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
			. "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
			. "WHERE"
			. " date(dpag_ref.fechapago)='$Fecha' "
			. "AND dpag_ref.usr='$Usr' "
			. "AND dpag_ref.cancelada=0";

		}


}else{

		if($Usr=='*'){

			$GastosA ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
			. "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
			. "FROM dpag_ref "
			. "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
			. "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
			. "WHERE"
			. " date(dpag_ref.fechapago)='$Fecha' "
			. "AND dpag_ref.cancelada=0 "
			. "AND ($SucursalGas)";
			
		}else{

			$GastosA ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
			. "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
			. "FROM dpag_ref "
			. "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
			. "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
			. "WHERE"
			. " date(dpag_ref.fechapago)='$Fecha' "
			. "AND dpag_ref.usr='$Usr' "
			. "AND dpag_ref.cancelada=0 "
			. "AND ($SucursalGas)";
			

		}


}


		//echo $cSql;
		$Gastos = mysql_query($GastosA);

		echo "<div align='center'>";
		echo "<p align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> G A S T O S :";
		echo "</strong></font></div>";        
		echo "<br>";		

      echo "<table width='95%' align='center' border='0' cellpadding='1' cellspacing='2' class='content_txt'>";                                
      //echo "<tr class='textosItalicos' bgcolor='#cccccc'>";              
      echo "<tr height='21' background='lib/bartit.gif'>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Suc</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Id</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Referencia</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Cuenta</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Tipo P.</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Recibe</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Autoriza</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Usr</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Hospi</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Concepto</font></th>";
      echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
      echo "</tr>"; 	

      while($Gasto=mysql_fetch_array($Gastos)){          

            if( ($nRngg % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#CDCDFA';}    //El resto de la division;
            
            echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'> $Gasto[suc] </font></td>"; 
            echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'> $Gasto[id] </font></td>"; 
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'> $Gasto[referencia] </font></td>";	
            echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'> $Gasto[cuenta] </font></td>";
            echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$Gasto[fechapago] &nbsp; </font></td>";	
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$Gasto[concepto] &nbsp; </font></td>";
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>".ucwords($Gasto[recibe])." &nbsp; </font></td>";
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$Gasto[autoriza] &nbsp; </font></td>";
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$Gasto[usr] &nbsp; </font></td>";
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$Gasto[hospi] &nbsp; </font></td>";
            echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$Gasto[concept] &nbsp; </font></td>";
            echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Gasto[monto],2)." &nbsp; </font></td>";	
            echo "</tr>";            
            $nImporteg        += $Gasto[monto];              
            $nRngg ++;                        
      }      
      echo "<tr class='content_txt' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";  
      echo "<td align='left' bgcolor='#FFF'>  </td>";  
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='left' bgcolor='#FFF'>  </td>";	
      echo "<td align='left' bgcolor='#FFF'>  </td>";
      echo "<td align='right' bgcolor='$Fdo'><b><font size='1' face='Arial, Helvetica, sans-serif'> Total</b>&nbsp; </font></td>";	
      echo "<td align='right' bgcolor='$Fdo'><b><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($nImporteg,2)." </font></b>&nbsp; </td>";	
      echo "</tr>";
	  echo "</table>";
	  echo "<br>";
	  echo "<hr noshade style='color:000099;height:1px'>";

//**********  T O T A L      G E N E R A L   *************

							echo "<div align='center'>";
							echo "<p align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL ORDENES ABIERTAS : ".number_format($Ordenes[0],"0");
							echo "</strong></font></div>";        
							echo "<br>";
							
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
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TRANSFERENCIA GENERAL:</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totaltranferencia+$TotaltranferenciaR,'2');
							echo "</tr>";

							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>CHEQUE GENERAL:</td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totalcheque+$TotalchequeR,'2');
							echo "</tr>";

							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>EFECTIVO GRAL.:</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Grantotalgral,'2');
							echo "</tr>";

							echo "<tr bordercolor='#FFF'>";
							echo "<td bgcolor='#FFF'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>COSTOS GRAL.:</td>";
							echo "<td bgcolor='#FFF'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($nImporteg,'2');
							echo "</tr>";
							
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL A ENTREGAR:</td>";
							echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Grantotalgral-$nImporteg,'2');
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
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalefectivo,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalefectivoR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Graltotalefectivo,"2")."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Tarjeta</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totaltarjeta,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotaltarjetaR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Graltotaltarjeta,"2")."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Cheque</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalcheque,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotalchequeR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Graltotalcheque,"2")."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Transferencias</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totaltranferencia,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TotaltranferenciaR,"2")."</font></td>";
							echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totaltranferencia+$TotaltranferenciaR,"2")."</font></td>";
							echo "</tr>";
							echo "<tr bordercolor='#CCCCCC'>";
							echo "<td>&nbsp;</td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalgral1,"2")."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalgral2,"2")."</font></td>";
							echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Totalgral3,"2")."</font></td>";
							echo "</tr>";
							echo "</table></td>";
							echo "</tr>";
							echo "<tr> </tr>";
							echo "<tr> </tr>";
							echo "</table>";
							echo "<hr noshade style='color:000099;height:1px'>";

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
        echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr><td colspan='15'><hr noshade></td></tr>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>#Recibo</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No.Ord</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recepcionista</font></th>";
		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Status O.</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Venta</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Descuento</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Total Vta</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Ingreso</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Total</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudo</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Tpo.pago</font></th>";
        echo "<tr><td colspan='15'><hr noshade></td></tr>";
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
              $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha = '$Fecha' AND id<>'$registro[0]'",$link);
              $AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
              $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
              $Dto=mysql_fetch_array($DtoA);              
			  $Horaorden1=substr($registro[horaorden],0,5);
			  $Horaordenc=$registro[3];
			  $Adeudo1=($registro[6]-($Abonado[0] + $registro[4] + $AbonadoD[0] ));
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
			 			 
echo"</font>";
echo"<tr>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[id]</font></td>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[institucion] - $registro[orden]</font></td>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[8]</font></td>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[fecha]</font></td>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>".substr($registro[3],0,5)."</font></td>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$status</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventa/*$Dto[1]*/,'2')."</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordendesc/*$Dto[0]*/,'2')."</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ordenventot/*$Dto[1] - $Dto[0]*/,'2')."</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera,'2')."</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso1,'2')."</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera+$Ingreso1/*-$Recupera3*/,'2')."</font></td>";
  echo"<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Adeudo1,'2')."</font></td>";
  echo"<td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$registro[5]</font></td>";
echo"</tr>";
echo"<p><font size='1' face='Arial, Helvetica, sans-serif'>";
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
        echo "<tr><td colspan='15'><hr></td></tr>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotalOrd,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Recupera2,'2')."</strong></font></td>";
        echo "<td  align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</strong></font></td>";
        echo "<td  align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($IngTot,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotAdeudo,'2')."</strong></font></td>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif' color='#0066FF'></font></td>";
        echo "<tr><td colspan='15'><hr></td></tr>";
        echo "</table>";
     
        echo "<div align='center'>";
	    echo "<p align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ORDENES ABIERTAS : ".number_format($Ordenes[0],"0");
        echo "</strong></font></div>";        
        echo "<br>";
		
		echo "<table width='80%' border='0' align='center'>";
	    echo "<tr>";
				echo "<td width='40%'><table width='25%' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
				echo "<tr bordercolor='#CCCCCC'>";
		   		echo "<td width='60%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>VENTAS TOTALES :</td>";
				echo "<td width='40%'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Totdia,'2');//"</td>";
				echo "</tr>";
		 		echo "<tr bordercolor='#CCCCCC'>";
		   		echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>ADEUDOS :</td>";
				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($TotAdeudo,'2');
				echo "</tr>";
 				echo "<tr bordercolor='#CCCCCC'>";
		   		echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>INGRESO DEL DIA :</td>";
				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Ingreso,'2');
				echo "</tr>";
		 		echo "<tr bordercolor='#CCCCCC'>";
   				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACIONES :</td>";
				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Recupera2,'2');
				echo "</tr>";
		 		echo "<tr bordercolor='#CCCCCC'>";
   				echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL :</td>";
				echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot,'2');
				echo "</tr>";
				echo "<tr bordercolor='#CCCCCC'>";
   				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TARJETA :</td>";
				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Tarjeta+$TarjetaR,'2');
				echo "</tr>";
				$Transferencias=$Transferencia+$TransferenciaR;
				echo "<tr bordercolor='#CCCCCC'>";
				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TRANSFERENCIA :</td>";
				echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($Transferencia+$TransferenciaR,'2');
				echo "</tr>";
				echo "<tr bordercolor='#CCCCCC'>";
   				echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL GRAL.:</td>";
				echo "<td bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;$&nbsp;".number_format($IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR),'2');
				echo "</tr>";
				echo "</table></td>";
	    		echo "<td><table width='90%' align='center' border='1' align='center' cellpadding='1' cellspacing='0' bordercolor='#FFFFFF'>";
	        	echo "<tr bordercolor='#CCCCCC'>";
	    	    echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TIPO PAGO</font></td>";
	    	    echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>DEL DIA</font></td>";
	    	    echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>RECUPERACION</font></td>";
    	    	echo "<td bgcolor='#CCCCCC'><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>TOTAL</font></td>";
		        echo "</tr>";
        		echo "<tr bordercolor='#CCCCCC'>";
		        echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Efectivo</font></td>";
	    	    echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo,"2")."</font></td>";
	    	    echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($EfectivoR,"2")."</font></td>";
        		echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$EfectivoR,"2")."</font></td>";
		        echo "</tr>";
        		echo "<tr bordercolor='#CCCCCC'>";
	    	    echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Tarjeta</font></td>";
	    	    echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Tarjeta,"2")."</font></td>";
		        echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TarjetaR,"2")."</font></td>";
        		echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Tarjeta+$TarjetaR,"2")."</font></td>";
		        echo "</tr>";
        		echo "<tr bordercolor='#CCCCCC'>";
	    	    echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Cheque</font></td>";
	    	    echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Cheque,"2")."</font></td>";
		        echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ChequeR,"2")."</font></td>";
        		echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Cheque+$ChequeR,"2")."</font></td>";
		        echo "</tr>";

		        
		        echo "<tr bordercolor='#CCCCCC'>";
	    	    echo "<td><div align='center'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Transferencias</font></td>";
	    	    echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Transferencia,"2")."</font></td>";
		        echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($TransferenciaR,"2")."</font></td>";
        		echo "<td><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Transferencia+$TransferenciaR,"2")."</font></td>";
		        echo "</tr>";
		        echo "<tr bordercolor='#CCCCCC'>";
	    	    echo "<td>&nbsp;</td>";
	    	    echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,"2")."</font></td>";
        		echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
		        echo "<td  bgcolor='#CCCCCC'><div align='right'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2")."</font></td>";
        		echo "</tr>";
			    echo "</table></td>";
		echo "</tr>";
		echo "</table>";
		echo "<hr noshade style='color:000099;height:1px'>";
	 }
    }//fin if
//inicio del while para adeudos, recuperaciones y descuentos


echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=4&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
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
