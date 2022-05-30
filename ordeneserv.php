<?php

session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");

$link=conectarse();

$Usr=$check['uname'];

$busca=$_REQUEST[busca];

$Sucursal     =   $_REQUEST[Sucursal];
$Institucion  =   $_REQUEST[Institucion];
$Depto        =   $_REQUEST[Depto];


$FecI       =   $_REQUEST[FecI];
$FecF       =   $_REQUEST[FecF];

$Fechai     =   $FecI;
$Fechaf     =   $FecF;

$Titulo     =   $_REQUEST[Titulo];
$Urgentes   =   $_REQUEST[Urgentes];

$Servicio   = $_REQUEST[Servicio];	//1.todos 2.Urgentes 3.Express


$Subdepto    = $_REQUEST[Subdepto]; // * Todos / TOMA / TRASLADOS / RXPORT

$Fecha=date("Y-m-d");

$Hora=date("H:i");
?>
<html>

<head>
<meta charset="UTF-8">
<title>Relacion Servicio Movil</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>



<?php
//if($Depto=="*"){
if ($Servicio == "2" OR $Servicio == "3") {  //Urgentes	
    if ($Servicio == "3") {
        $Serv = "Express";
    } else {
        $Serv = "Urgente";
    }


    
    $CiaA   = mysql_query("SELECT nombre FROM cia WHERE id='$Sucursal'");
    $Cia    = mysql_fetch_array($CiaA);
    
    if ($Subdepto == "*") {

        if ($Sucursal <> '*') {

            
            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";

            $cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion,, est.descripcion, otd.precio, 
                     otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, 
                     ot.institucion,ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, 
                     ot.descuento as descto,est.estpropio,est.muestras,est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                     FROM ot, cli, otd, est, med
                     WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                     ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.servicio='$Serv' 
					 and (est.subdepto='TOMA' or est.subdepto='TRASLADO' or est.subdepto='PORTATIL' or est.subdepto='RECOLECCION')
                     order by ot.orden, otd.estudio";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                       est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' 
                       AND ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.servicio='$Serv' 
					   and (est.subdepto='TOMA' or est.subdepto='TRASLADO' or est.subdepto='PORTATIL' or est.subdepto='RECOLECCION')
                       order by ot.orden, est.clavealt";
        }
        
    } else {

        if ($Sucursal <> '*' ) {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";

            $cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                      otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                      ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                      est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                      FROM ot, cli, otd, est, med
                      WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' 
                      AND ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.servicio='$Serv' and est.subdepto='$Subdepto'
                      order by ot.orden, otd.estudio";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: * todas, Institucion: $Institucion";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                       est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.servicio='$Serv' and est.subdepto='$Subdepto'
                       order by ot.orden, otd.estudio";
        }
    }
    
} elseif ($Servicio == 1) {  //Todos urgentes y de todo
    
    if ($Subdepto == "*") {

        if ($Sucursal <> '*') {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' 
					   and (est.subdepto='TOMA' or est.subdepto='TRASLADO' or est.subdepto='PORTATIL' or est.subdepto='RECOLECCION')
                       order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico 
					   and (est.subdepto='TOMA' or est.subdepto='TRASLADO' or est.subdepto='PORTATIL' or est.subdepto='RECOLECCION')
                       order by ot.orden,est.subdepto, est.clavealt";
        }
        
    } else {

        if ($Sucursal <> '*') {
            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";
            if($Institucion=='*'){            
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,
						   est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' and est.subdepto='$Subdepto'
                           order by ot.orden, otd.estudio";
            }else{
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,
						   est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.institucion='$Institucion' and est.subdepto='$Subdepto'
                           order by ot.orden, otd.estudio";                
            }    
        } else {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0] Institucion: $Institucion";
            
            if($Institucion=='*'){

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,
						   est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico and est.subdepto='$Subdepto'
                           order by ot.orden, otd.estudio";

            }else{

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,
						   est.entord,ot.horae,est.clavealt,ot.suc,est.subdepto,ot.observaciones
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.institucion='$Institucion' and est.subdepto='$Subdepto'
                           ORDER BY ot.orden, otd.estudio";                                
            }     
        }
    }
}

//echo $cSql;

$UpA=mysql_query($cSql,$link);




?>


<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>
    <td width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "$Titulo"; ?>&nbsp;</div></td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Suc-Inst-Ord</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Paciente</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudios</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'></td>";		
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Precio</font></td>";		
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Desc. %</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Importe</font></td>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";


    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
    $Estudios=0;
	$Serv2=0;
    while ($rg = mysql_fetch_array($UpA)) {
		            $ImporteT+=$Importe;
                    $DescuentoT+=$Descuento;
                    $Importe = 0;
                    $Descuento = 0;
                    $Ordenes++;
                    $Med1 = "A";
                    $Rec = "B";
                    $Urge2 = 0;
                $Rec = $rg[recepcionista];
                $Obs=$rg[observaciones];



                echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
                echo "<tr>";
                echo "<th align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif' color='#FF0000'>$rg[suc]</font><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;-&nbsp;$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2]</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha Cap.: $rg[fecha]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fec.ent: $rg[fechae] ".substr($rg[horae],0,5)."</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora Cap.: $rg[hora]</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif' color='#e36d12'>$Rec</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descto]</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[servicio]</font></th>";
                echo "</tr>";




                
				$cSq   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                		otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                		ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                		ot.horae,est.clavealt,ot.suc,est.subdepto
                		FROM ot, cli, otd, est, med
                		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                        ot.fecha <='$Fechaf' AND ot.medico=med.medico and ot.orden=$rg[orden]
                        order by ot.orden,est.subdepto, est.clavealt";
				$Up=mysql_query($cSq,$link);
				while ($reg = mysql_fetch_array($Up)) {
	                echo "<tr>";
		            echo "<th>";
					echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$reg[estudio]&nbsp; $reg[clavealt] &nbsp; - </font></th>";
					echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$reg[descripcion]&nbsp;&nbsp;$reg[estpropio]/$reg[muestras]/$reg[entord]</font></th>";
					echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$reg[subdepto]</font></th>";
					echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($reg[precio], '2') . "</font></th>";
					echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($reg[descuento]) . "</font></th>";
					echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($reg[8], '2') . "</font></th>";
					echo "</tr>";						
				



            $Estudios++;
            $Importe+=$reg[precio];
            $Descuento+=($reg[precio] * ($reg[descuento] / 100));
            $Med = $reg[medico];
				}
            if ($rg[estudio] == "URG") {
                $Urge = 1;
            } else {
                $Urge = 0;
            }
            $Urge2 = $Urge2 + $Urge;
            if ($Med1 <> $Med) {
                $Med1 = $Med;
                $Med2 = $rg[nombrec];
                $Med3 = $rg[medicon];
                if ($Med1 == "MD") {
                    $Med2 = $Med3;
                }
            }
            if ($rg[servicio] == "Urgente" or $Urge2 <> 0) {
                $Urgencia = "* * *  U R G E N C I A  * * * ";
            } else {
                $Urgencia = " ";
            }
	 echo "<tr>";
     echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
  	 echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
     echo "<th align='left'><font color='#f51111' size='2' face='Arial, Helvetica, sans-serif'color='#e36d12'><u>$Urgencia</u></strong></font></th>";
	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></th>";
   	 echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento)."</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($Importe-$Descuento,'2')."</font></th>";
     echo "</tr>"; 
     echo "<th colspan='7' align='center'><font color='#225c87' size='1' face='Arial, Helvetica, sans-serif'>$Obs</font></th>";
    }
    		
	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
	
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;	

     echo "<tr><td colspan='8'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ImporteT,'2')."</font></th>";
   	 echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($DescuentoT)."</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteT-$DescuentoT,'2')."</font></th>";
     echo "</tr>"; 

	 $FechaI=$_REQUEST[FechaI];
  	 $FechaF=$_REQUEST[FechaF];

              
     echo "</table>";

echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=20&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";

//echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('ordenesabipdf.php?FechaI=$FechaI&FechaF=$FechaF&Institucion=$Institucion&Servicio=$Servicio1&Descto=$DesctoS&Recepcionista=$Recepcionista1&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";



 echo "</form>";
 echo "</div>";

?>
</body>
</html>
<?php
mysql_close();
?>

