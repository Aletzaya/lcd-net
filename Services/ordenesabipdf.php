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

$Institucion  = $_REQUEST[Institucion];
$Depto    = $_REQUEST[Depto];
$Recepcionista   = $_REQUEST[Recepcionista];


$FechaI=$_REQUEST[FechaI];
$FechaF=$_REQUEST[FechaF];


$Titulo=$_REQUEST[Titulo];
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

$doc_title    = " Demanda de estudios - Detallado ";
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

}

//********* RECOLECION DE DATOS *********
if ($DesctoS == "S") {

    if ($Sucursal <> '*') {

        $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF Sucursal: $Sucursal2";
        $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                   ot.horae,est.clavealt,ot.suc
                   FROM ot, cli, otd, est, med
                   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                   ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) AND ot.descuento <> ' ' $Recep $Servicio
                   order by ot.orden";
    } else {

        $Titulo = "Relacion de Ordenes de trabajo del $FechaI al $FechaF ";
        $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                   ot.horae,est.clavealt,ot.suc
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
                       ot.horae,est.clavealt,ot.suc
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                       ot.fecha <='$FechaF' AND ot.medico=med.medico AND ($Sucursal) $Recep $Servicio
                       order by ot.orden";
        }else{
            if($Institucion=='LCD'){  
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,ot.suc                           
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
                               ot.horae,est.clavealt,ot.suc                           
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
                               ot.horae,est.clavealt,ot.suc                           
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
                       ot.horae,est.clavealt,ot.suc 
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                       ot.fecha <='$FechaF' AND ot.medico=med.medico $Recep	$Servicio		   
                       order by ot.orden";
        }else{
            if($Institucion=='LCD'){

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,ot.suc 
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
                               ot.horae,est.clavealt,ot.suc 
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
                               ot.horae,est.clavealt,ot.suc
                               FROM ot, cli, otd, est, med
                               WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$FechaI' and
                               ot.fecha <='$FechaF' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Recep $Servicio
                               ORDER BY ot.orden";
                }
            }
        }     
    }
}
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

    $this->writeHTML('<br><br><table align="center" width="1150" border="0" cellspacing="1" cellpadding="0"><tr><td align="center" bgcolor="#808B96" width="70">Suc-Inst-Ord</td><td align="center" bgcolor="#808B96" width="150">Paciente</td><td align="center" bgcolor="#808B96" width="150">Estudios</td><td align="center" bgcolor="#808B96" width="320"></td><td align="center" bgcolor="#808B96" width="100">Precio</td><td align="center" bgcolor="#808B96" width="100">Desc. %</td><td align="center" bgcolor="#808B96" width="100">Importe</td></tr></table>', false, 0);


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
                $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0"><tr><td align="center" width="70"></td>
                <td align="center" width="150"></td><td align="center" width="150"></td>
                <td align="right" width="100" bgcolor="#808B96"><strong>'$Med1'</strong></td>
                <td align="right" width="100" bgcolor="#808B96"><strong>'$Med2'</strong></td>
                <td align="right" width="100" bgcolor="#808B96"><strong><u>'$Urgencia'</u></strong></td>
                <td align="center" width="320" bgcolor="#808B96"><strong>Total OT: $</strong></td>
                <td align="right" width="100" bgcolor="#808B96"><strong>'. number_format($Importe, "2") .'</strong></td>
                <td align="right" width="100" bgcolor="#808B96"><strong>'. number_format($Descuento) .'</strong></td>
                <td align="right" width="100" bgcolor="#808B96"><strong>'. number_format($Importe - $Descuento, "2") .'</strong></td></tr></table>';
                $pdf->writeHTML($html,true,false,true,false,'');
          
                    $ImporteT+=$Importe;
                    $DescuentoT+=$Descuento;
                    $Importe = 0;
                    $Descuento = 0;
                    $Ordenes++;
                    $Med1 = "A";
                    $Rec = "B";
                    $Urge2 = 0;
                }
            }
        }

                 











                
ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>