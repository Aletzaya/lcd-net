<?php

session_start();


include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


require("lib/lib.php");

require ("config.php");	

$link=conectarse();


$Usr=$check['uname'];

$busca=$_REQUEST[busca];

$Sucursal  = $_REQUEST[Sucursal];
$sucursalt = $_REQUEST[sucursalt];
$sucursal0 = $_REQUEST[sucursal0];
$sucursal1 = $_REQUEST[sucursal1];
$sucursal2 = $_REQUEST[sucursal2];
$sucursal3 = $_REQUEST[sucursal3];
$sucursal4 = $_REQUEST[sucursal4];
$sucursal5 = $_REQUEST[sucursal5];
$sucursal6 = $_REQUEST[sucursal6];

$Institucion  = $_REQUEST[Institucion];
$Depto    = $_REQUEST[Depto];
$Recepcionista   = $_REQUEST[Recepcionista];
$Recepcionista1   = $_REQUEST[Recepcionista];

$FechaI=$_REQUEST[FechaI];
$FechaF=$_REQUEST[FechaF];


$Titulo=$_REQUEST[Titulo];
$Urgentes=$_REQUEST[Urgentes];

$Servicio=$_REQUEST[Servicio];

$Servicio1=$_REQUEST[Servicio];

if($Servicio=="*"){
	  $Servicio=" ";
}else{
$Servicio=" and ot.servicio='$Servicio'";
}


$DesctoS    = $_REQUEST[Descto];

$Fecha=date("Y-m-d");

$Hora=date("H:i");

?>
<html>

<head>
<meta charset="UTF-8">
<title>Ordenes de Trabajo</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>



<?php
//if($Depto=="*"){
$InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
$NomI    = mysql_fetch_array($InstA);

if($Recepcionista=="*"){
	$Recep=" ";
}else{
	$Recep=" AND ot.recepcionista='$Recepcionista'";
}

if($Institucion=='LCD'){  
	$LCD=" AND ot.institucion<='20' AND ot.institucion<>'19' AND ot.institucion<>'18' AND ot.institucion<>'17' AND ot.institucion<>'16' 
	AND ot.institucion<>'15' AND ot.institucion<>'14' AND ot.institucion<>'13' AND ot.institucion<>'12' AND ot.institucion<>'11' 
	AND ot.institucion<>'9' AND ot.institucion<>'8' AND ot.institucion<>'7' AND ot.institucion<>'6' AND ot.institucion<>'5'
	 AND ot.institucion<>'4' AND ot.institucion<>'2'";
}else{
	if($Institucion=='SLCD'){
		$SLCD=" AND ot.institucion<>'20' AND ot.institucion<>'1' AND ot.institucion<>'3' AND ot.institucion<>'10'";
	}
		  
}

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
		$Sucursal2= $Sucursal2 . "Matriz - "; 
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

if ($DesctoS == "S") {

    if ($Sucursal <> '*') {

        $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF Sucursal: $Sucursal2";
        $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                   ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                   FROM ot, cli, otd, est, med
                   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                   ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) AND ot.descuento <> ' ' $Recep $Servicio
                   order by ot.orden";

    } else {

        $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF ";
        $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                   ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                   FROM ot, cli, otd, est, med
                   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                   ot.fecha <='$FechaF' AND ot.medico=med.medico AND ot.descuento <> ' ' $Recep $Servicio
                   order by ot.orden";
    }
    
} else {

    if ($Sucursal <> '*') {
        $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF Sucursal: $Sucursal2";
        if($Institucion=='*'){            
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                       ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) $Recep $Servicio
                       order by ot.orden";
        }else{
            if($Institucion=='LCD'){  
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                           ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) $Recep
                           $LCD $Servicio
                           order by ot.orden";                
            }else{
                if($Institucion=='SLCD'){  
                    $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                               otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                               ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                               ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida        
                               FROM ot, cli, otd, est, med
                               WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                               ot.fecha <='$FechaF AND ot.medico=med.medico AND ($Sucursal) $Recep
                               $SLCD $Servicio
                               order by ot.orden";                
                }else{
                    $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";
                    $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                               otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                               ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                               ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida        
                               FROM ot, cli, otd, est, med
                               WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                               ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) AND ot.institucion='$Institucion' $Recep $Servicio
                               order by ot.orden";    
                }
            } 
        }
    } else {

        $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";
        
        if($Institucion=='*'){

            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                       ot.fecha <='$FechaF' AND ot.medico=med.medico $Recep	$Servicio		   
                       order by ot.orden";
        }else{
            if($Institucion=='LCD'){

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                           ot.fecha <='$FechaF' AND ot.medico=med.medico $Recep	
                           $LCD $Servicio
                           order by ot.orden";
            }else{
                if($Institucion=='SLCD'){  
                    $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                               otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                               ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                               ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                               FROM ot, cli, otd, est, med
                               WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                               ot.fecha <='$FechaF' AND ot.medico=med.medico $Recep	
                               $SLCD $Servicio
                               order by ot.orden";
                }else{
                    $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";
                    $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                               otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                               ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                               ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                               FROM ot, cli, otd, est, med
                               WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                               ot.fecha <='$FechaF' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Recep $Servicio
                               ORDER BY ot.orden";
                }
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
    echo "<tr><td colspan='6'><hr noshade></td></tr>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Suc-Inst-Ord</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Paciente</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudios</font></td>";
	echo "<td bgcolor='#808B96' align='CENTER'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Ent. Recep / Ent. Correo</font></td>";
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Capturo</font></td>";		
    echo "<td align='CENTER' bgcolor='#808B96'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Servicio</font></td>";
    echo "<tr><td colspan='6'><hr noshade></td></tr>";
   
   
    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
    $Estudios=0;
    while ($rg = mysql_fetch_array($UpA)) {
            if ($Orden <> $rg[orden]) { 
                if ($Orden <> 0) { 
                    echo "<td align='right'><font color='#000000' size='1' class='letrap'>$Med1&nbsp;-&nbsp;</font></td>";
                    echo "<td align='left'><font color='#000000' size='1' class='letrap'>$Med2</font></td>";
                    echo "<td align='left' ><font color='#f51111' size='2' face='Arial, Helvetica, sans-serif'color='#e36d12'><u>$Urgencia</u></font></td>";
                    echo "<td align='right' class='letrap'></td>";
                    echo "<td align='right'class='letrap'></td>";
                    echo "<td align='center' class='letrap'></td>";
                    echo "</tr>";

                    echo "<tr><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td></tr>";
                    
                    $ImporteT+=$Importe;
                    $DescuentoT+=$Descuento;
                    $Importe = 0;
                    $Descuento = 0;
                    $Ordenes++;
                    $Med1 = "A";
                    $Rec = "B";
                    $Urge2 = 0;
                }


                $Rec = $rg[recepcionista];

                if ($rg[entemailpac] == '1' or $rg[entemailmed] == '1' or $rg[entemailinst] == '1') {
                    $entregcorreo="<i class='fa fa-envelope-o fa-lg' style='color:#008080;' aria-hidden='true'></i>";
                }else{
                    $entregcorreo="";
                }

                if ($rg[tentregamost] == '1') {
                    $entregrecepc="<i class='fa fa-handshake-o fa-lg' style='color:#2ECC71' aria-hidden='true'></i>";
                }else{
                    $entregrecepc="";
                }
                
                echo "<tr>";
                echo "<td align='CENTER'><font size='2' class='letrap' color='#FF0000'>$rg[suc]</font><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;-&nbsp;$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></td>";
                echo "<td align='left'><font size='1' class='letrap'> &nbsp; $rg[2] &nbsp; $rg[numveces] vecs</font></td>";
                echo "<td align='left'><font size='1' class='letrap'>Fecha Cap.: $rg[fecha]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fec.ent: $rg[fechae] ".substr($rg[horae],0,5)."&nbsp;&nbsp;&nbsp;&nbsp; T.Entr. $entregrecepc / $entregcorreo</font></td>";
                echo "<td align='CENTER'><font size='1' class='letrap'>Hora Cap.: $rg[hora]</font></td>";
                echo "<td align='CENTER'><font size='1'face='Arial, Helvetica, sans-serif'color='#e36d12'>$Rec</font></td>";
                echo "<td align='CENTER'><font size='1' class='letrap'>$rg[servicio]</font></td>";
                echo "</tr>";
                $Orden = $rg[orden];
            }
			
			if($Institucion==94){
				$clavealterna=$rg[clavealt];
			}else{
				$clavealterna=' ';
			}

            $entregrecep="";

            if($rg[recibepac] <> ''){
                $entregrecep="<i class='fa fa-handshake-o fa-lg' aria-hidden='true' style='color:#2ECC71' ></i>";
            }else{
                if($rg[status]=="" or $rg[status]=="DEPTO"){
                    $entregrecep="Espera";
                }elseif($rg[status]=="PENDIENTE"){
                    $entregrecep="Pendiente";
                }else{
                    if($rg[status]=="TERMINADA" and $rg[recibeencaja]<>""){
                        $entregrecep="Recepc.";
                    }elseif($rg[status]=="TOMA/REALIZ" or $rg[status]=="RECOLECCION" or $rg[status]=="RESUL"){
                        $entregrecep="Proceso";
                    }elseif($rg[status]=="CAPTURA"){
                        $entregrecep="Captura";
                    }elseif($rg[status]=="TERMINADA" and $rg[usrvalida]==""){
                        $entregrecep="Sin Validar";
                    }elseif($rg[status]=="TERMINADA" and $rg[usrvalida]<>""){
                        $entregrecep="Terminada";
                    }
                }
            }
			
            $cons = "SELECT * FROM logenvio WHERE logenvio.orden='$Orden' and logenvio.estudio='$rg[estudio]' order by id desc";

            $reg = mysql_query($cons);

            if (!$regenvio = mysql_fetch_array($reg)) {

                $entregvirtual = "";
            } else {

                $entregvirtual = "<i class='fa fa-envelope-o fa-lg' style='color:#008080;' aria-hidden='true'></i>";
            }


            echo "<tr>";
            echo "<td>"; 
            echo "<td align='right'><font size='1' class='letrap'>$rg[estudio]&nbsp; $clavealterna &nbsp;</font></td>";
            echo "<td align='left'><font size='1' class='letrap'>$rg[descripcion]&nbsp;&nbsp;$rg[estpropio]/$rg[muestras]/$rg[entord]</font></td>";
            echo "<td align='center' style='font-size:9px'>$entregrecep / $entregvirtual</td>";
            echo "<td align='right'><font size='1' class='letrap'></td>";
            echo "<td align='center'><font size='1' class='letrap'></td>";
            echo "</tr>";
            $Estudios++;
            $Importe+=$rg[precio];
            $Descuento+=($rg[precio] * ($rg[descuento] / 100));
            $Med = $rg[medico];
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
    }



     echo "<tr>";
     echo "<td align='right'><font color='#000000' size='1' class='letrap'>$Med1&nbsp;-&nbsp;</font></td>";
  	 echo "<td align='left'><font color='#000000' size='1' class='letrap'>$Med2</font></td>";
     echo "<td align='left'><font color='#f51111' size='2' face='Arial, Helvetica, sans-serif'color='#e36d12'<u>$Urgencia</u></font></td>";
	 echo "<td align='right'><hr><font class='letrap'></td>";
   	 echo "<td align='right'><hr><font class='letrap'></td>";
     echo "</tr>"; 
    		
	 echo "<tr><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td><td><hr>&nbsp;</td></tr>";
   	 $Ordenes++;		
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;	
    	
     echo "<tr><td colspan='6'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<td>";
     echo "<td align='right'><font size='1' class='letrap'>No. Ordenes : ".number_format($Ordenes,"0")."</font></td>";
     echo "<td align='center'><font size='1' class='letrap'>No. Estudios : $Estudios</font></td>";
     echo "<td align='right'><font size='1' class='letrap'></td>";
	 echo "<td align='right'><font size='1' class='letrap'></td>";
   	 echo "<td align='center'><font size='1' class='letrap'></td>";
     echo "</tr>"; 




	 $FechaI=$_REQUEST[FechaI];
  	 $FechaF=$_REQUEST[FechaF];

              
     echo "</table>";

echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=1&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('ordenesabiopepdf.php?FechaI=$FechaI&FechaF=$FechaF&Institucion=$Institucion&Servicio=$Servicio1&Descto=$DesctoS&Recepcionista=$Recepcionista1&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";



 echo "</form>";
 echo "</div>";

?>
</body>
</html>
<?php
mysql_close();
?>
