<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo="Relacion de comisiones";

  require("lib/lib.php");

  $link=conectarse();

  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta p�gina.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
  $Institucion=$_REQUEST[Institucion];
 // $Medico=$_REQUEST[Medico];
  $Status=$_REQUEST[Status];
	$Sucursal     =   $_REQUEST[sucursal];
	if($Sucursal=='*'){
		$Sucursal     =   '';
	}else{
		$Sucursal     =   "and ot.suc='$_REQUEST[sucursal]'";
	}

	$cero=$_REQUEST[cero];

	if (!isset($cero)){

		$cero='Todo';

	}else{

		$cero=$cero;
	}


  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  
  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);

	if ($cero<>'No'){

		$cSql="select orden,cliente,fecha,institucion,suc,pagada as ingreso,ot.status, ot.stenvmail from ot
		where
		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.institucion='$Institucion' $Sucursal and pagada='No' and importe>1 order by ot.orden ";

	}else{

		$cSql="select orden,cliente,fecha,institucion,suc,pagada as ingreso,ot.status, ot.stenvmail from ot
		where
		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.institucion='$Institucion' $Sucursal order by ot.orden ";

	}

  require ("config.php");

  ?>

  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html>
  <head>
  <meta charset="UTF-8">
  <title><?php echo $Titulo;?></title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  </head>
  <body bgcolor="#FFFFFF">
  
  <?php

  //echo $cSql;
  if(!$res=mysql_query($cSql,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados � hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
        echo "Recarga y/� limpia.</a></font>";
 		echo "</div>";
 	}else{

        //$registro=mysql_fetch_array($res);

        echo "<table width='100%' height='80' border='0'>";    //Encabezado
        echo "<tr><td width='26%' height='76'>";
        echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='70'> </p>";
        echo "</td>";
        echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
        echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de ordenes del $FecI &nbsp; al  $FecF</p>";
        echo "</td></tr></table>";
        echo "<p><strong><font size='3' face='Arial, Helvetica, sans-serif'>INST:_$Institucion -  $NomI[nombre]</strong>&nbsp; &nbsp;";

        if($cero=='Todo'){
			     echo "&nbsp; &nbsp;<a href='comisionesinst.php?FecI=$FecI&FecF=$FecF&Institucion=$Institucion&sucursal=*&cero=No'><font size='2' face='Arial, Helvetica, sans-serif'><b>Todas las Ot's</b></a></p>";
        }else{
        	 echo "&nbsp; &nbsp;<a href='comisionesinst.php?FecI=$FecI&FecF=$FecF&Institucion=$Institucion&sucursal=$_REQUEST[sucursal]&cero=Todo'><font size='2' face='Arial, Helvetica, sans-serif'><b>Quitar OT's Pagadas</b></a></p>";
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
        echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Ingreso</th>";
        echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Adeudo</th>";
        echo "<th with='30%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Entr.Pac.</th>";
        echo "<tr>";

        $ContadorTEst=1;

        while ($registro=mysql_fetch_array($res)){

				$cSqlb=mysql_query("select otd.orden,otd.estudio,otd.precio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe from otd
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

                 echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Institucion."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Suc."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td><td align='left' width='500'>";

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


                 echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font>";

             }

				$vta= "select cja.fecha,sum(cja.importe),cja.orden from cja where cja.orden='$Orden'";

				$vtaA  = mysql_fetch_array(mysql_query($vta,$link));

				$Ingreso=$vtaA[1];

				$Adeudo=$Importe-$Ingreso;

				if($Adeudo<='1'){
					$Adeudo='';
				}else{
					$Adeudo=$Adeudo;
				}

        if($registro[status]=='Entregada'){
            $status='Entregada';
        }else{
            $status=' ';
        }


        if($registro[stenvmail]=='Enviado'){
            $stenvmail='ENVIADO';

        }else{
            $stenvmail='';
        }
     

  

                 echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
                 echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Ingreso,'2')."</font></td>";
                 echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Adeudo,'2')."</font></td>";
                 echo "</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$status</font></td>";

                 if($registro[stenvmail]=='NO ENVIADO'){
                    echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$registro[orden]')><i class='fa fa-envelope-o' style='color:red;' aria-hidden='true'></i></a></td>";
                }elseif($registro[stenvmail]=='PARCIAL'){
                    echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$registro[orden]')><i class='fa fa-envelope-open-o' style='color:blue;' aria-hidden='true'></i></a></td>";
                }elseif($registro[stenvmail]=='ENVIADO'){
                    echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$registro[orden]')><i class='fa fa-paper-plane-o' style='color:black;' aria-hidden='true'></i></a></td>";
                }

                
                 echo "</tr>";
				 $ImporteM+=$Importe;
				 $IngresoM+=$Ingreso;
				 $Contador=$Contador+1;
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
				
		}

         echo "<tr><td>&nbsp;</td><td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Pacientes: &nbsp; </strong></td>
		 <td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;".number_format($Contador)."&nbsp;</strong></td><td>&nbsp;</td>
		 <td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;E s t u d i o s : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($nRng)."</strong></font></td>
		 <td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>G R A N &nbsp; T O T A L : </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($IngresoM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM-$IngresoM,'2')."</strong></font></td></tr>";
         echo "</table>";
         echo "<hr noshade style='color:3366FF;height:1px'>";

    }
	//fin while
	echo "<form name='form2' method='post' action='comisionesinst.php?FecI=$FecI&FecF=$FecF&Institucion=$Institucion&sucursal=$_REQUEST[sucursal]&cero=$cero'>";
    echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";

	echo '</table>';
    ?>
</body>
</html>
<?php
mysql_close();
?>