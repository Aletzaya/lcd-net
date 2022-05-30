<?php

session_start();


include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


require("lib/lib.php");

$link=conectarse();


$Usr=$check['uname'];

$busca=$_REQUEST[busca];

$Sucursal  = $_REQUEST[Sucursal];
//$Sucursal     =   $Sucursal[0];
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


$FechaI=$_REQUEST[FechaI];
$FechaF=$_REQUEST[FechaF];

$Titulo="Relacion de Ordenes de trabajo del $FechaI al $FechaF";


$Urgentes=$_REQUEST[Urgentes];

$Servicio=$_REQUEST[Servicio];

if($Servicio=="*"){
	  $Servicio=" ";
}else{
$Servicio=" and ot.servicio='$Servicio'";
}


$DesctoS    = $_REQUEST[Descto];

$Fecha=date("Y-m-d");

$Hora=date("H:i");

$doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	//require_once('tcpdf2/tcpdf_include.php');
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
    
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc,otd.recibepac,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,otd.status,otd.recibeencaja,otd.entregapac,otd.usrvalida
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                       ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) AND ot.descuento <> ' ' $Recep $Servicio
                       order by ot.orden";
    
        } else {
    
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

// ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2;

    $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
    $NomI    = mysql_fetch_array($InstA);

    $Fecha   = date("Y-m-d");
    $Hora=date("H:i");

    // Logo
    $image_file2 = 'lib/DuranNvoBk.png';
    $this->Image($image_file2, 8, 5, 65, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 11);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr><tr><td width="30"></td><td width="800">Laboratorio Clínico Duran</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Fecha/Hora: '.$Fecha.' - '. $Hora.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.' Sucursal: '.$Sucursal2.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Institucion: '.$Institucion.' - '. $NomI[nombre].'</td></tr></table>', false, 0);

    $this->SetFont('Helvetica', 'BI', 8);

    $this->writeHTML('<br><br><table align="center" width="1150" border="0" cellspacing="1" cellpadding="0"><tr>
    <td align="center" bgcolor="#808B96" width="110">Suc-Inst-Ord</td>
    <td align="center" bgcolor="#808B96" width="300">Paciente</td>
    <td align="center" bgcolor="#808B96" width="150">Estudios</td>
    <td align="center" bgcolor="#808B96" width="280">Ent. Recep / Ent. Correo</td>
    <td align="center" bgcolor="#808B96" width="110">Precio</td>
    <td align="center" bgcolor="#808B96" width="110">Desc. %</td>
    <td align="center" bgcolor="#808B96" width="110">Importe</td>
    </tr></table><br>', false, 0);


    }

    // Page footer
    function Footer() {

        // Position at 15 mm from bottom
        $this->SetY(-10);
       // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "letter");

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 35);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Resultado".$Estudio);

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

$lBd=false;




//***** INICIA REPORTE ***//

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

                $pdf->SetFont('Helvetica', '', 5, '', 'false');

                $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
                <tr>
                <td  width="100" ><FONT SIZE="6">'.$Med1.'-&nbsp;</font></td>
                <td align="left" width="280"><FONT SIZE="6">'.$Med2.'</font></td>
                <td width="200" color="red">'.$Urgencia.'</td>
                <td align="center"  width="250" bgcolor="#808B96">Total OT: $</td>
                <td align="center"  width="115" bgcolor="#808B96">'. number_format($Importe, "2") .'</td>
                <td align="center"  width="115" bgcolor="#808B96">-</td>
                <td align="center"  width="115" bgcolor="#808B96">'. number_format($Importe - $Descuento, "2") .'</td>
                </tr>
                </table><br>_________________________________________________________________________________________________________________________________________________________________________________________________________________<br>';
                $pdf->writeHTML($html,false,false,true,false,'');
     

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
                $entregcorreo="email.png";
                }else{
                     $entregcorreo="blanco.png";
                }

     
                if ($rg[tentregamost] == '1') {
                     $entregrecepc="trato.png";
                }else{
                     $entregrecepc="blanco.png";
                }

                $str = $rg[2];
                $str1 = strtoupper($str);

               $pdf->SetFont('Helvetica', '', 6, '', 'false');

                $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
                <tr>
                <td width="30">'.$rg[suc].'</td><td width="30">'.$rg[institucion].'</td><td  width="80">'.$rg[orden].'</td>
                <td align="left" width="300">'.$str1.'&nbsp; '.$rg[numveces].' vecs</td>
                <td   align="left" width="360">Fecha Cap.: '.$rg[fecha].'&nbsp;&nbsp;&nbsp;Fec.ent: '.$rg[fechae].''.substr($rg[horae],0,5).' &nbsp; T.Entr. &nbsp; <img src="images/'.$entregrecepc.'" /></td>
                <td align="center" width="50">/<img src="images/'.$entregcorreo.'" /></td>
                <td>Hora Cap.: '.$rg[hora].'</td>
                <td width="110">'.$Rec.'</td>
                <td width="100">'.$rg[servicio].'</td>
                </tr>                </table>';
                $pdf->writeHTML($html,false,false,true,false,'');
        

                $Orden = $rg[orden];
            }
			


			if($Institucion==94){
				$clavealterna=$rg[clavealt];
			}else{
				$clavealterna=' ';
			}

            $entregrecep1="blanco.png";

            if($rg[recibepac] <> ''){
                $entregrecep1="trato.png";
            }else{
                if($rg[status]=="" or $rg[status]=="DEPTO"){
                    $entregrecep1="espera.PNG";
                }elseif($rg[status]=="PENDIENTE"){
                    $entregrecep1="pendiente.PNG";
                }else{
                    if($rg[status]=="TERMINADA" and $rg[recibeencaja]<>""){
                        $entregrecep1="recepc.PNG";
                    }elseif($rg[status]=="TOMA/REALIZ" or $rg[status]=="RECOLECCION" or $rg[status]=="RESUL"){
                        $entregrecep1="proceso.PNG";
                    }elseif($rg[status]=="CAPTURA"){
                        $entregrecep1="Captura.PNG";
                    }elseif($rg[status]=="TERMINADA" and $rg[usrvalida]==""){
                        $entregrecep1="sinvalidar.PNG";
                    }elseif($rg[status]=="TERMINADA" and $rg[usrvalida]<>""){
                        $entregrecep1="terminada.PNG";
                    }
                }
            }
			
            $cons = "SELECT * FROM logenvio WHERE logenvio.orden='$Orden' and logenvio.estudio='$rg[estudio]' order by id desc";

            $reg = mysql_query($cons);

            if (!$regenvio = mysql_fetch_array($reg)) {

                $entregvirtual = "blanco.png";
            } else {

                $entregvirtual = "email.png";
            }



                $pdf->SetFont('Helvetica', '', 5, '', 'false');

               $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
               <tr>
               <td width="110"></td>
               <td align="right" width="200" >'.$rg[estudio].'&nbsp; '.$clavealterna.' &nbsp;</td>
               <td align="left" width="400">'.$rg[descripcion].'&nbsp;&nbsp;'.$rg[estpropio]/$rg[muestras]/$rg[entord].'</td>
               <td align="right" width="60"><img src="images/'.$entregrecep1.'" /></td><td align="left" width="60">&nbsp;/&nbsp;<img src="images/'.$entregvirtual.'" /></td>
               <td align="center"  width="115">' . number_format($rg[precio], "2") . '</td>
               <td align="center"  width="115">'. number_format($rg[descuento], "2") . '</td>
               <td align="center"  width="115"> '. number_format($rg[8], "2") . '</td></tr>
               </table>';

               $pdf->writeHTML($html,false,false,true,false,'');
            
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

       $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
       <tr>
       <td  width="100" ><FONT SIZE="6">'.$Med1.'-&nbsp;</font></td>
       <td align="left" width="280"><FONT SIZE="6<">'.$Med2.'</font></td>
       <td width="200" color="red">'.$Urgencia.'</td>
       <td align="center"  width="250" bgcolor="#808B96">Total OT: $</td>
       <td align="center"  width="115" bgcolor="#808B96">'. number_format($Importe, "2") .'</td>
       <td align="center"  width="115" bgcolor="#808B96">-</td>
       <td align="center"  width="115" bgcolor="#808B96">'. number_format($Importe - $Descuento, "2") .'</td>
       </tr>
       </table><br>_____________________________________________________________________________________________________________________________________________________<br>';
       $pdf->writeHTML($html,false,false,true,false,'');

       $Ordenes++;		
       $ImporteT+=$Importe;
       $DescuentoT+=$Descuento;



       $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
       <tr>
       <td></td>
       <td></td>
       <td align="right" width="150">No. Ordenes : '.$Ordenes.'</td>
       <td align="center" width="150">No. Estudios : '.$Estudios.'</td>
       <td align="center"  width="280" bgcolor="#808B96">GRAN TOTAL --> $</td>
       <td align="center"  width="100" bgcolor="#808B96">'.number_format($ImporteT,"2").'</td>
       <td align="center"  width="100" bgcolor="#808B96">'.number_format($DescuentoT,'2').'</td>
       <td align="center"  width="100" bgcolor="#808B96">'.number_format($ImporteT-$DescuentoT,"2").'</td>
       </tr>
       </table>';


$pdf->writeHTML($html,false,false,true,false,'C');

                
ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>