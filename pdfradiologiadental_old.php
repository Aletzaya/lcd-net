<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
 $Usr=$check['uname'];
$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[busca];
$alterno = $_REQUEST[alterno]; 

$doc_title    = "Resultado";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
// Extend the TCPDF class to create custom Header and Footer


//********* RECOLECION DE DATOS *********

$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$EstA=mysql_query("select descripcion from est where estudio='$Estudio' ",$link);
$Est=mysql_fetch_array($EstA);
$EleA=mysql_query("select * from estudiospdf where id='$Orden' and usrelim='' order by idnvo",$link);
$EleB=mysql_query("select count(id) as contar from estudiospdf where id='$Orden' and usrelim='' order by idnvo",$link);
$Eleb=mysql_fetch_array($EleB);
$contar=$Eleb[contar];
$OtA=mysql_query("select ot.suc, ot.medico,cli.nombrec as nomcli,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,cli.afiliacion,ot.fecha,ot.observaciones,ot.servicio,ot.cliente from ot,cli,med where ot.orden='$Orden' and ot.cliente=cli.cliente and ot.medico=med.medico",$link);
$Ot=mysql_fetch_array($OtA);

$Team=$Ot[suc];

if($Team=='2'){
    $Team='OHF';
    $domicilio2="Fray Pedro de Gante No. 320,";
    $domicilio3="Col. Centro, Texcoco Edo. de Mex. C.P 56100";
    $telefonos="Tels: (01595) 95 4 45 86";

}elseif($Team=='3'){
      $Team='TPX';
    $domicilio2="Av Morelos No. 3, Tepexpan Acolman,";
    $domicilio3="Col. Centro C.P 55885";
    $telefonos="Tels: (01 594) 95 7 42 97 - 95 7 38 82";
   
}elseif($Team=='4'){
    $Team='RYS';
    $domicilio2="Carr. Federal Mexico-Puebla No. 128";
    $domicilio3="Los Reyes La Paz, Col. Centro  C.P 56400";
    $telefonos="Tels: (01 55) 58 55 60 24 - 58 58 43 07";

}elseif($Team=='5'){
    $Team='CAM';
    $domicilio2="Guanabana 35, Esq. Calz. Camarones,";
    $domicilio3="Col. Nueva Santa Maria C.P 02800";
    $telefonos="Tels: 53 41 66 50 - 53 42 48 77";

}else{
    $Team='MTRZ';
    $domicilio2="Fray Pedro de Gante No. 108,";
    $domicilio3="Col. Centro, Texcoco, Edo. de Mex. C.P 56100 ";
    $telefonos="Tels: (01 595) 95 4 11 40  -  95 4 62 96";
  
}

$Pweb="www.dulab.com.mx";
$facebook="Laboratorio Clinico Duran";
$correo="atencionaclientes@dulab.com.mx";


$Fecha2=date("Y-m-d");
$Fechanac  = $Ot[fechan];
$Fecha   = date("Y-m-d");
$array_nacimiento = explode ( "-", $Fechanac ); 
$array_actual = explode ( "-", $Fecha ); 
$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0) 
{ 
	--$meses; 

	//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
	switch ($array_actual[1]) { 
		   case 1:     $dias_mes_anterior=31; break; 
		   case 2:     $dias_mes_anterior=31; break; 
		   case 3:  	$dias_mes_anterior=28; break;  
		   case 4:     $dias_mes_anterior=31; break; 
		   case 5:     $dias_mes_anterior=30; break; 
		   case 6:     $dias_mes_anterior=31; break; 
		   case 7:     $dias_mes_anterior=30; break; 
		   case 8:     $dias_mes_anterior=31; break; 
		   case 9:     $dias_mes_anterior=31; break; 
		   case 10:     $dias_mes_anterior=30; break; 
		   case 11:     $dias_mes_anterior=31; break; 
		   case 12:     $dias_mes_anterior=30; break; 
	} 

	$dias=$dias + $dias_mes_anterior; 
} 

//ajuste de posible negativo en $meses 
if ($meses < 0) 
{ 
	--$anos; 
	$meses=$meses + 12; 
} 

$Hora=date("H:i");
//     $Hora1 = date("H:i");
//     $Hora2 = strtotime("-60 min",strtotime($Hora1));
//    $Hora  = date("H:i",$Hora2);

// ********************  E N C A B E Z A D O  ****************

$Mes       = substr($Ot[fecha],5,2)*1;
$FechaLet  = substr($Ot[fecha],8,2)." de ".$aMes[$Mes]." del ".substr($Ot[fecha],0,4);

$nombrecli= ucwords(strtoupper($Ot[nomcli]));

if($Ot[sexo]=='M'){
	$sexo	='Masculino';
}else{
	$sexo	='Femenino';
}

$institucion= $Ot[institucion];

if($Ot[medico]=='MD'){
	$nombremed	= $Ot[medico] .' - '. $Ot[medicon];
}else{
	$nombremed	= $Ot[medico] .' - '. $Ot[nombrec];
}

$Nombrestud=$Est[descripcion];


//********** Codigo QR ***********//

$PNG_WEB_DIR = 'codeqr/';

include "phpqrcode/qrlib.php";    

$orden=$Orden;
$cliente=$Ot[cliente];
$matrixPointSize = 5;
$errorCorrectionLevel = 'L';
$datos='https://lcd-system.com/lcd-net/pdfradiologiadental.php?clnk='.$Estudio.'&Orden='.$orden.'&Estudio='.$Estudio.'&Depto=TERMINADA&op=im';
$filename = $PNG_WEB_DIR.$orden.'_'.$Estudio.'.png';

QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>'; 

//********** Codigo QR ***********//


class MYPDF extends TCPDF {

    //Page header
    function Header() {
    	global $nombrecli,$anos,$meses,$sexo,$nombremed,$institucion,$Orden,$FechaLet,$Nombrestud,$Estudio,$dias;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/DuranNvoBk.png';
        $this->Image($image_file2, 8, 5, 80, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        
        if($anos>0){
        	$edad=$anos.' A&ntilde;os ';
        }else{
        	if($meses>0){
        		$edad=$meses.' Meses ';
        	}else{
        		$edad=$dias.' dias ';
        	}
        }

        $this->SetFont('helvetica', 'B', 10);

        $this->writeHTML('<table width="100"><tr><td width="78">&nbsp;</td><td width="700">PACIENTE: '.$nombrecli.'</td></tr></table><br>', false, 0);

        $this->SetFont('helvetica', '', 10);

        $this->writeHTML('<table width="100"><tr><td width="550">&nbsp;</td><td width="700"> Edad: '.$edad.' &nbsp;&nbsp;&nbsp; Sexo: '.$sexo.'</td></tr></table><br>', false, 0);

        $this->SetFont('helvetica', '', 10);

        $this->writeHTML('<table width="100"><tr><td width="550">&nbsp;</td><td width="700">Médico: '.$nombremed.'</td></tr></table><br>', false, 0);

        $this->writeHTML('<table width="100"><tr><td width="550">&nbsp;</td><td width="700">Orden: '.$institucion.' - '.$Orden.' &nbsp;&nbsp; Fecha: '.$FechaLet.'</td></tr></table><br>', false, 0);

        $this->writeHTML('<hr><br>', false, 0);

        $this->SetFont('helvetica', 'BI', 13);

        $this->writeHTML('<table width="100"><tr><td width="1200"><div align="center">'.$Estudio.' - '.$Nombrestud.'</div></td></tr></table>', false, 0);

    }

    // Page footer
    function Footer() {
		global  $Pweb,$facebook,$correo,$domicilio2,$domicilio3,$telefonos,$filename;
        // Position at 15 mm from bottom
        $this->SetY(-35);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -35, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');


		$this->SetFont('helvetica', 'BI', 8);
		$this->SetY(-20);
		$this->writeHTML('<table width="100"><tr><td width="1200"><div align="center"> '.$domicilio2.'   '.$domicilio3.'    '.$telefonos.'</div></td></tr></table>', false, 0);

        $this->Image($filename, 190, 250, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);


		$this->SetFont('helvetica', 'BI', 8);
		$this->SetY(-16);
		$this->writeHTML('<table width="100"><tr><td width="1200"><div align="center"><img src="web.jpg" height="30px" width="30px" />'.$Pweb.'   <img src="face.jpg" height="30px" width="30px" /> '.$facebook.' <img src="CORREO.jpg" height="30px" width="30px" />   '.$correo.'</div></td></tr></table>', false, 0);
    }
}


// ******************** F I N     E N C A B E Z A D O  ****************

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
define ("PDF_MARGIN_TOP", 52);
define ("PDF_MARGIN_BOTTOM", 33);
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

$pdf->SetFont('Helvetica', '', 10, '', 'false');

//***********   D A T O S   ***********

$nRng=0;

$html .='<br><br><table width="800" border="0" cellspacing="0" cellpadding="0"><tr>';

while($Ele=mysql_fetch_array($EleA)){

	$nRng++;

	$filename2=$Ele[archivo];

	if($nRng==1){

		if($contar==1){

			$html .='<td width="450"></td><td width="400"><img src="../lcd/estudios/'.$filename2.'" alt="test alt attribute" width="300" height="350" border="0" /></td>';

		}else{

			$html .='<td width="200"></td><td width="400"><img src="../lcd/estudios/'.$filename2.'" alt="test alt attribute" width="300" height="350" border="0" /></td>';

		}


	}elseif($nRng==2){

		$html .='<td width="100"></td><td width="400"><img src="../lcd/estudios/'.$filename2.'" alt="test alt attribute" width="300" height="350" border="0" /></td>';

	}elseif($nRng==3){


		if($contar==3){

			$html .='</tr><tr><td colspan="6"></td></tr><tr><td colspan="6"></td></tr><tr><td width="450"></td><td width="400"><img src="../lcd/estudios/'.$filename2.'" alt="test alt attribute" width="300" height="350" border="0" /></td>';

		}else{

			$html .='</tr><tr><td colspan="6"></td></tr><tr><td colspan="6"></td></tr><tr><td width="200"></td><td width="400"><img src="../lcd/estudios/'.$filename2.'" alt="test alt attribute" width="300" height="350" border="0" /></td>';

		}


	}elseif($nRng==4){

		$html .='<td width="100"></td><td width="400"><img src="../lcd/estudios/'.$filename2.'" alt="test alt attribute" width="300" height="350" border="0" /></td>';

	}

}

$html .='</tr></table>';

$html .='<br><br><br><hr><table width="800" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="6">Observaciones: </td></tr></table>';

	$CapA=mysql_query("select texto,capturo,medico from otd where orden='$Orden' and estudio='$Estudio'",$link);

	$Cap=mysql_fetch_array($CapA);

	$CapB=mysql_query("select id,nombre from pertec where id='$Cap[medico]'",$link);

	$Capb=mysql_fetch_array($CapB);

$html .='<br><table width="800" border="0" cellspacing="0" cellpadding="0"><tr><td width="150"></td><td colspan="6">'.$Cap[texto].'</td></tr></table>';

$html .='<br><br><br><hr><br><br><table width="1170" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="6" align="center">Tec. '.$Capb[nombre].' </td></tr></table>';

$html .='<table width="1170" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="6" align="center">Realizado Por </td></tr></table>';

$pdf->writeHTML($html,false,false,false,false,'');

// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("$Orden'_'$Estudio'.pdf'", 'I');

?>

