<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo="Relacion de comisiones";

  require("lib/lib.php");

  $link=conectarse();

  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta página.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
  $Recepcionista=$_REQUEST[Recepcionista];
 // $Medico=$_REQUEST[Medico];
  $Status=$_REQUEST[Status];
  
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

	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="";
		$and="";
		$parentesisa="";
		$parentesisc="";

		$Sucursal2= " * - Todas ";
	}else{

		$and=" and ";
		$parentesisa="(";
		$parentesisc=")";
	
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

	$cero=$_REQUEST[cero];

	if (!isset($cero)){

		$cero='Todo';

	}else{

		$cero=$cero;
	}


  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  

	if ($cero<>'No'){

		$cSql="select orden,cliente,fecha,institucion,suc,pagada as ingreso,ot.status,stenvmail from ot
		where
		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.recepcionista='$Recepcionista' and pagada='No' $and $parentesisa $Sucursal $parentesisc  order by ot.orden ";

	}else{

		$cSql="select orden,cliente,fecha,institucion,suc,pagada as ingreso,ot.status,stenvmail from ot
		where
		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.recepcionista='$Recepcionista' $and $parentesisa $Sucursal $parentesisc order by ot.orden ";

	}

	$cSql2="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario as recepcionista,ot.fecha as fechaot,ot.hora as horaorden,ot.suc,ot.institucion,ot.cliente
		   FROM cja,ot
		   WHERE cja.fecha>='$FecI' and cja.fecha<='$FecF' AND ot.orden=cja.orden and cja.usuario='$Recepcionista'
		   ORDER BY cja.orden,cja.id,cja.hora";

	$UpA2=mysql_query($cSql2,$link);


  require ("config.php");

?>

  <html>
 
  <head>
  <meta charset="UTF-8">
  <title>Sistema de Laboratorio clinico</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>

<?php

 //***************** Recuperaciones **************//

echo "<table width='100%' height='80' border='0'>";    //Encabezado
echo "<tr><td width='26%' height='76'>";
echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='61'></p>";
echo "</td>";
echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de ordenes del $FecI &nbsp; al  $FecF</p>";
echo "</td></tr></table>";

echo "<hr noshade style='color:3366FF;height:1px'>";

echo "<p><strong><font size='3' face='Arial, Helvetica, sans-serif'>Recepcionista:_$Recepcionista - Recuperaciones</strong>&nbsp; &nbsp;";

echo "<hr>";

echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr>";
echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Inst.</th>";
echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Suc.</th>";
echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Orden</th>";
echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Fecha</th>";
echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Paciente</th>";
echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Estudios</th>";
echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Ingreso</th>";
echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Tpago</th>";
echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Entr.Pac.</th>";
echo "<tr>";

  while ($registros=mysql_fetch_array($UpA2)){
		

		if ($registros[fecha]>$registros[fechaot]){

				$cSqld=mysql_query("select otd.orden,otd.estudio,otd.precio,otd.descuento,otd.precio,otd.precio-(otd.precio*(otd.descuento/100)) as importe from otd
						where
						otd.orden=$registros[orden] order by otd.estudio",$link);

				$cSqle=mysql_query("select nombrec from cli
						where
						cli.cliente=$registros[cliente]",$link);

				$registro5=mysql_fetch_array($cSqle);

				$Orden=$registros[orden];
				$Suc=$registros[suc];
				$Inst=$registros[institucion];
				$Paciente=$registro5[nombrec];
				$Fecha=$registros[fecha];

				if( ($nRng2 % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CCCCCC';}    //El resto de la division;
				$nRng2++;

				echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Inst."</font></td>";
				echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Suc."</font></td>";
				echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
				echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
				echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td><td align='left' width='500'>";

				while ($registro6=mysql_fetch_array($cSqld)){

					$Estudios=$registro6[estudio];
							
					if($registro6[descuento]>0){
						$DESCTO="(DESCTO)";
					}else{
						$DESCTO=" ";
					}

					$Estudios=$DESCTO." ".$Estudios.", ".$registros[estudio];
					$Importe+=$registro6[importe];
					$ContadorTEst+=$ContadorTEst;
					$nRng++;


		      echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font>";

		   }

				$vta2= "select cja.fecha,sum(cja.importe),cja.orden from cja where cja.orden='$Orden'";

				$Ingreso=$registros[importe];
				$Tpago=$registros[tpago];

				$Adeudo=$Importe-$Ingreso;

		    if($registros[status]=='Entregada'){
		        $status='Entregada';
		    }else{
		    	  if($registros[stenvmail]=='ENVIADO'){
		    	  	$status='ENVIADO';
						}elseif($registros[stenvmail]=='PARCIAL'){
							$status='PARCIAL';
						}else{
							$status=' ';
						}
		    }

		   echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</font></td>";
		   echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$Tpago</font></td>";
		   echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$status</font></td>";
		   echo "</tr>";
			 $ImporteM+=$Importe;
			 $IngresoM+=$Ingreso;
			 $Contador=$Contador+1;
		   $Institucion=$registros[institucion];
		   $Estudios=$registros[estudio];

		   if($registros[descuento]>0){
		      $Estudios="(DESCTO)".$registros[estudio];
		      /*$Comision=0;*/
		   }

				$Importe=$registro6[importe];
				$Suc=$registros[suc];
				$Orden=$registros[orden];
				$Paciente=$registros[nombrec];
				$Fecha=$registros[fecha];


				if($Tpago=="Efectivo"){
					$EfectivoR=$EfectivoR+$Ingreso;
				}elseif($Tpago=="Tarjeta"){
					$TarjetaR=$TarjetaR+$Ingreso;
				}elseif($Tpago=="Transferencia"){
					$TransferenciaR=$TransferenciaR+$Ingreso;
				}
		
		}else{

				$RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registros[orden]' and id<'$registros[id]' and usuario<>'$registros[recepcionista]'",$link);
				$Regrecup=mysql_fetch_array($RegrecupA);

				if($Regrecup[ids]<>0){

						$cSqld=mysql_query("select otd.orden,otd.estudio,otd.precio,otd.descuento,otd.precio,otd.precio-(otd.precio*(otd.descuento/100)) as importe from otd
								where
								otd.orden=$registros[orden] order by otd.estudio",$link);

						$cSqle=mysql_query("select nombrec from cli
								where
								cli.cliente=$registros[cliente]",$link);

						$registro5=mysql_fetch_array($cSqle);

						$Orden=$registros[orden];
						$Suc=$registros[suc];
						$Paciente=$registro5[nombrec];
						$Fecha=$registros[fecha];
						$Inst=$registros[institucion];

						if( ($nRng2 % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CCCCCC';}    //El resto de la division;
						$nRng2++;

						echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Inst."</font></td>";
						echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Suc."</font></td>";
						echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
						echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
						echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td><td align='left' width='500'>";

						while ($registro6=mysql_fetch_array($cSqld)){

							$Estudios=$registro6[estudio];
									
							if($registro6[descuento]>0){
								$DESCTO="(DESCTO)";
							}else{
								$DESCTO=" ";
							}

							$Estudios=$DESCTO." ".$Estudios.", ".$registros[estudio];
							$Importe+=$registro6[importe];
							$ContadorTEst+=$ContadorTEst;
							$nRng++;


				      echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font>";

				   }

						$vta2= "select cja.fecha,sum(cja.importe),cja.orden from cja where cja.orden='$Orden'";

						$Ingreso=$registros[importe];
						$Tpago=$registros[tpago];

						$Adeudo=$Importe-$Ingreso;

				    if($registros[status]=='Entregada'){
				        $status='Entregada';
				    }else{
				    	  if($registros[stenvmail]=='ENVIADO'){
				    	  	$status='ENVIADO';
								}elseif($registros[stenvmail]=='PARCIAL'){
									$status='PARCIAL';
								}else{
									$status=' ';
								}
				    }

				   echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</font></td>";
						echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$Tpago</font></td>";

				   echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$status</font></td>";
				   echo "</tr>";
					 $ImporteM+=$Importe;
					 $IngresoM+=$Ingreso;
					 $Contador=$Contador+1;
				   $Institucion=$registros[institucion];
				   $Estudios=$registros[estudio];

				   if($registros[descuento]>0){
				      $Estudios="(DESCTO)".$registros[estudio];
				      /*$Comision=0;*/
				   }

						$Importe=$registro6[importe];
						$Suc=$registros[suc];
						$Orden=$registros[orden];
						$Paciente=$registros[nombrec];
						$Fecha=$registros[fecha];

						if($Tpago=="Efectivo"){
							$EfectivoR=$EfectivoR+$Ingreso;
						}elseif($Tpago=="Tarjeta"){
							$TarjetaR=$TarjetaR+$Ingreso;
						}elseif($Tpago=="Transferencia"){
							$TransferenciaR=$TransferenciaR+$Ingreso;
						}

				}

		}

}

echo "<tr><td>&nbsp;</td><td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Pacientes: &nbsp; </strong></td>
<td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;".number_format($Contador)."&nbsp;</strong></td><td>&nbsp;</td>
<td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;E s t u d i o s : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($nRng)."</strong></font></td>
<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>G R A N &nbsp; T O T A L : </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($IngresoM,'2')."</strong></font></td></tr>";
echo "</table>";
echo "<br><br>";

echo "<hr noshade style='color:3366FF;height:1px'>";
echo "<br>";

$ContadorT=$Contador;
$Contador=0;
$nRng=0;
$IngresoM=0;
$ImporteM=0;
$Importe=0;
$Ingreso=0;
$IngresoM=0;
$Adeudo=0;

 //***************** Ordenes del dia **************//


  //echo $cSql;
  if(!$res=mysql_query($cSql,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{

        //$registro=mysql_fetch_array($res);

        echo "<table width='100%' height='80' border='0'>";    //Encabezado
        echo "<p><strong><font size='3' face='Arial, Helvetica, sans-serif'>Recepcionista:_$Recepcionista - Ordenes Capturadas </strong>&nbsp; &nbsp;";

        if($cero=='Todo'){
			     echo "&nbsp; &nbsp;<a href='resgralrecep.php?FecI=$FecI&FecF=$FecF&Recepcionista=$Recepcionista&&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]&cero=No'><font size='2' face='Arial, Helvetica, sans-serif'><b>Todas las Ot's</b></a></p>";
        }else{
        	 echo "&nbsp; &nbsp;<a href='resgralrecep.php?FecI=$FecI&FecF=$FecF&Recepcionista=$Recepcionista&sucursalt=$_REQUEST[sucursalt]&sucursal0=$_REQUEST[sucursal0]&sucursal1=$_REQUEST[sucursal1]&sucursal2=$_REQUEST[sucursal2]&sucursal3=$_REQUEST[sucursal3]&sucursal4=$_REQUEST[sucursal4]&sucursal5=$_REQUEST[sucursal5]&sucursal6=$_REQUEST[sucursal6]&cero=Todo'><font size='2' face='Arial, Helvetica, sans-serif'><b>Quitar OT's Pagadas</b></a></p>";
        }

        echo "<hr>";

				echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
				echo "<tr>";
				echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Inst.</th>";
				echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Suc.</th>";
				echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Orden</th>";
				echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Fecha</th>";
				echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Paciente</th>";
				echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Estudios</th>";
				echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Importe</th>";
				echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Abonos</th>";
				echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Adeudo</th>";
				echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Entr.Pac.</th>";
				echo "<tr>";

    
//***************** Ordenes **************//

        $ContadorTEst=1;

        while ($registro=mysql_fetch_array($res)){

					$cSqlb=mysql_query("select otd.orden,otd.estudio,otd.precio,otd.descuento,otd.precio,otd.precio-(otd.precio*(otd.descuento/100)) as importe from otd
					where
					otd.orden=$registro[orden] order by otd.estudio",$link);

					$cSqlc=mysql_query("select nombrec from cli
					where
					cli.cliente=$registro[cliente]",$link);

					$registro3=mysql_fetch_array($cSqlc);

					$Institucion=$registro[institucion];
					$Orden=$registro[orden];
					$Suc=$registro[suc];
					$Paciente=$registro3[nombrec];
					$Fecha=$registro[fecha];

					if( ($nRng2 % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CCCCCC';}    //El resto de la division;
					$nRng2++;

         echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".$Institucion."</b></font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".$Suc."</b></font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".$Orden."</b></font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".$Fecha."</b></font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".$Paciente."</b></font></td><td align='left' width='500'>";

         $Contador=$Contador+1;

				while ($registro2=mysql_fetch_array($cSqlb)){

					$Estudios=$registro2[estudio];
					//$Contador=1;
					
					if($registro2[descuento]>0){
						$DESCTO="(DESCTO)";
					}else{
						$DESCTO=" ";
					}
					//$Importe=$registro2[importe];
          $Estudios=$DESCTO." ".$Estudios.", ".$registro[estudio];
          $Importe+=$registro2[importe];
					$ContadorTEst+=$ContadorTEst;
					$nRng++;

          echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".$Estudios."</b></font>";

        }

        if($registro[status]=='Entregada'){
            $status='Entregada';
        }else{
        	  if($registro[stenvmail]=='ENVIADO'){
        	  	$status='ENVIADO';
						}elseif($rg[stenvmail]=='PARCIAL'){
							$status='PARCIAL';
						}else{
							$status=' ';
						}
        }

        $vta2= mysql_query("select sum(cja.importe) from cja where cja.orden='$Orden'",$link);

				$vtaA2  = mysql_fetch_array($vta2);

				$Ingresos  = $vtaA2[0];

				$Adeudo=$Importe-$Ingresos;
				
				echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".number_format($Importe,'2')."</b></font></td>";

				echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".number_format($Ingresos,'2')."</b></font></td>";

				echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>".number_format($Adeudo,'2')."</b></font></td>";

				echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><b>$status</b></font></td>";


		    echo "</tr>";

				$vta= mysql_query("select cja.fecha,cja.importe,cja.orden,cja.tpago from cja where cja.fecha>='$FecI' and cja.fecha<='$FecF' AND cja.orden='$Orden' and cja.usuario='$Recepcionista'",$link);

				//$vtaA  = mysql_fetch_array($vta);

				while ($vtaA=mysql_fetch_array($vta)){

						$Ingreso=$vtaA[1];

						$Tpago=$vtaA[tpago];

		        echo "<tr bgcolor='#D4E6F1'>";
						echo "</td><td colspan='7'></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</font></td>";
						echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$Tpago</font></td>";
						echo "</tr>";

					 $ImporteM+=$Importe;
					 $IngresoM+=$Ingreso;
					 $IngresoMs+=$Ingresos;
		       $Institucion=$registro[institucion];
		       $Estudios=$registro[estudio];

		       if($registro[descuento]>0){
		          $Estudios="(DESCTO)".$registro[estudio];
		          /*$Comision=0;*/
		       }

		       $Importe=$registro2[importe];
		       $Suc=$registro[suc];
		       $Orden=$registro[orden];
				 		 $Paciente=$registro[nombrec];
			 		 $Fecha=$registro[fecha];

			 		if($Tpago=="Efectivo"){
						$Efectivo=$Efectivo+$Ingreso;
					}elseif($Tpago=="Tarjeta"){
						$Tarjeta=$Tarjeta+$Ingreso;
					}elseif($Tpago=="Transferencia"){
						$Transferencia=$Transferencia+$Ingreso;
					}

				}
				
		}

         echo "<tr><td>&nbsp;</td><td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Pacientes: &nbsp; </strong></td>
		 <td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;".number_format($Contador)."&nbsp;</strong></td><td>&nbsp;</td>
		 <td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;E s t u d i o s : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($nRng)."</strong></font></td>
		 <td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>G R A N &nbsp; T O T A L : </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($IngresoM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM-$IngresoM,'2')."</strong></font></td></tr>";
         echo "</table>";
           echo "<br>";
           echo "<br>";

         echo "<hr noshade style='color:3366FF;height:1px'>";
	$ContadorT=$ContadorT+$Contador;
    }
	//fin while

//***************** Gastos **************//

	$GastosA=mysql_query("SELECT * FROM dpag_ref WHERE fechapago>='$FecI' and fechapago<='$FecF' and usr='$Recepcionista' and cancelada=0",$link);

  echo "<br>";

	echo "<p><strong><font size='3' face='Arial, Helvetica, sans-serif'>Gastos </strong>&nbsp; &nbsp;</p>";
  
  echo "<hr>";
	echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
	echo "<tr>";
	echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Id</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Concepto</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Recibe</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Observaciones</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Autoriza</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Importe</th>";
	echo "<tr>";

  while ($Gasto=mysql_fetch_array($GastosA)){

			if( ($nRng2 % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CCCCCC';}    //El resto de la division;
			
			$nRng2++;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			echo "<td with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$Gasto[id]</td>";
			echo "<td with='30%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$Gasto[concept]</td>";
			echo "<td with='30%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$Gasto[recibe]</td>";
			echo "<td with='30%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$Gasto[observaciones]</td>";
			echo "<td with='30%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$Gasto[autoriza]</td>";
			echo "<td with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($Gasto[monto],2)."</td>";
			echo "<tr>";
			$GastoT=$GastoT+$Gasto[monto];

	}

  echo "<tr>";
	echo "<td with='10%' align='right' colspan='5'><font size='2' face='Arial, Helvetica, sans-serif'><b>Total: </b></td>";
	echo "<td with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'><b>$&nbsp;".number_format($GastoT,2)."</b></td>";
	echo "<tr>";

	echo '</table>';

	echo "<br>";


//***************** Totales **************//


	echo "<hr noshade style='color:3366FF;height:1px'>";


	echo "<br>";

	echo "<p><strong><font size='3' face='Arial, Helvetica, sans-serif'>Totales </strong>&nbsp; &nbsp;</p>";
  
  echo "<hr>";

	echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
	echo "<tr>";
	echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Ots</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Efectivo</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Efectivo Recup</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Tarjeta</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Tarjeta Recup</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Transferencia</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Transfer Recup</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Ingresos</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Gastos</th>";
	echo "<th with='30%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Total</th>";
	echo "<tr>";

  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
	echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$ContadorT</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($Efectivo,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($EfectivoR,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($Tarjeta,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($TarjetaR,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($Transferencia,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($TransferenciaR,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($Efectivo+$EfectivoR+$Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format($GastoT,'2')."</th>";
	echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$&nbsp;".number_format(($Efectivo+$EfectivoR+$Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR)-$GastoT,'2')."</th>";
	echo "<tr>";

	echo '</table>';

  echo "<br><br>";
  
  echo "<hr noshade style='color:3366FF;height:1px'>";

	echo "<form name='form2' method='post' action='resgralrecep.php?FecI=$FecI&FecF=$FecF&Recepcionista=$Recepcionista&sucursal=$_REQUEST[sucursal]&cero=$cero'>";
    echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";

    ?>
</body>
</html>
<?php
mysql_close();
?>