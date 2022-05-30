<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
 $Usr=$check['uname'];
$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[Orden];
$alterno = $_REQUEST[alterno]; 

$doc_title    = "Resultado";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
// Extend the TCPDF class to create custom Header and Footer


if($alterno=='0'){
	$tabla='elepdf';
}elseif($alterno=='1'){
	$tabla='elealtpdf';
}elseif($alterno=='2'){
	$tabla='elealtpdf2';
}elseif($alterno=='3'){
	$tabla='elealtpdf3';
}elseif($alterno=='4'){
	$tabla='elealtpdf4';
}

//********* RECOLECION DE DATOS *********

$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$EstA=mysql_query("select descripcion from est where estudio='$Estudio' ",$link);
$Est=mysql_fetch_array($EstA);
$EleA=mysql_query("select * from $tabla where estudio='$Estudio' order by id",$link);
$OtA=mysql_query("select ot.medico,cli.nombrec as nomcli,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,cli.afiliacion,ot.fecha,ot.observaciones,ot.servicio from ot,cli,med where ot.orden='$Orden' and ot.cliente=cli.cliente and ot.medico=med.medico",$link);
$Ot=mysql_fetch_array($OtA);
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
$nMes = array(" ", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
$NMes = substr($Ot[fechan], 5, 2) * 1;
$Fechanc = substr($Ot[fechan], 8, 2) . " / " . $nMes[$NMes] . " / " . substr($Ot[fechan], 0, 4);

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

$orden = $Orden;
$cliente = $Ot[cliente];
$matrixPointSize = 5;
$errorCorrectionLevel = 'L';
$datos = 'https://lcd-system.com/lcd-net/resultapdfmc.php?clnk=' . $Estudio . '&Orden=' . $orden . '&Estudio=' . $Estudio . '&Depto=TERMINADA&op=im&alterno=' . $alterno . '';
$filename = $PNG_WEB_DIR . $orden . '_' . $Estudio . '.png';

QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>'; 
//********** Codigo QR ***********//

class MYPDF extends TCPDF {

    //Page header
    function Header() {
    	global $nombrecli,$anos,$meses,$sexo,$nombremed,$institucion,$Orden,$FechaLet,$Nombrestud,$Estudio,$dias,$Fechanc;;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';

        //Image( filename, left, top, width, height, type, link, align, resize, dpi, align, ismask, imgmask, border, fitbox, hidden, fitonpage); 
        /**********
        filename : name of the file containing the image
		left : from left
		top: from top
		width: width of the image. Zero for automatically calculated
		height : height of the image. Zero for automatically calculated.
		type: JPG, JPEG, PNG. If not specified, the type is inferred from the file extension.
		link:URL or identifier returned by AddLink()
		align: T (top), M (middle), B (bottom), N (next line)
		resize: true/false. If true resize (reduce) the image to fit :w and :h (requires RMagick library); if false do not resize; if 2 force resize in all cases (upscaling and downscaling).
		dpi: dot-per-inch resolution used on resize. Recommended 300
		align: align L (left), C (center), R (right)
		ismask: true if this image is a mask, false otherwise
		imgmask: image object returned by this function or false.
		border: 0: no border (default) 1: frame or a string containing some or all of the following characters (in any order): L: left T: top R: right B: bottom
		fitbox: If true scale image dimensions proportionally to fit within the (:w, :h) box.
		hidden: true/false
		fitonpage: if true the image is resized to not exceed page dimensions.
		**********/
        $image_file = 'lib/RESULTADOS_MC1-03.png';
  		$this->Image($image_file, 5, 8, 206, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
         //$image_file2 = 'lib/logo1.jpg';
        //$this->Image($image_file2, 8, 5, 80, '', 'JPG', '', 'T', false,00, '', false, false, 0, false, false, false);

        
        if($anos>0){
        	$edad=$anos.' A&ntilde;os ';
        }else{
        	if($meses>0){
        		$edad=$meses.' Meses ';
        	}else{
        		$edad=$dias.' dias ';
        	}
        }

	  $this->SetFont('Helvetica', '', 7, '', 'false');

	  $this->writeHTML('<br><table width="1000">
	  	<tr>
	    <td colspan="2">
	    </td>
	  	</tr>
	  	<tr>
	    <td colspan="2">
	    </td>
	  	</tr>
	  	<tr>
	    <td colspan="2">
	    </td>
	  	</tr>
	  <tr>
	  <td width="600" height="25">
	  </td>
	  <td width="500"><strong>
	    '.$nombrecli.' &nbsp; &nbsp; '.$edad.' 
	   </strong></td>
	  </tr>
  	  <tr>
  	  <td width="600" height="25">
	  </td>
	    <td width="580">
	    <strong>Fech. Nac: </strong> '.$Fechanc.'
	  </td>
	  </tr>
	  <tr>
	  	  <td width="600" height="25">
	  </td>
	    <td width="580">
	    <strong>Sexo: </strong> '.$sexo.'  &nbsp; &nbsp; <strong> Fecha: </strong> '.$FechaLet.'
	  </td>
	  </tr>
	  <tr>
	  <td width="600" height="25">
	  </td>
	    <td width="500">
	    <strong>Médico: </strong> '.$nombremed.'
	    </td>
	  </tr>
	  <tr>
	  <td width="600" height="25">
	  </td>
	    <td width="150">
	    <strong>Orden: </strong> '.$Orden.'
	  </td>
	  </tr> 
	</table>', false, 0);

    $this->SetFont('helvetica', 'BI', 10);

    $this->writeHTML('<br><br><br><table width="100"><tr><td width="1200"><div align="center">'.$Estudio.' - '.$Nombrestud.'</div></td></tr></table>', false, 0);
    }

function Footer() {

	global  $filename;

   $this->SetY(-60);

  $this->SetFont('helvetica', '', 8);

  $this->writeHTML('<table width="1200"><tr><td width="50"></td><td width="1150">** Encargado de la toma de muestra <strong> Movicare Servicios Integrales S.A.P.I. de C.V. </strong></td></tr></table>', false, 0);

  $this->writeHTML('<table width="1200"><tr><td width="50"></td><td width="1150">** Estudio subrogado por <strong> Laboratorio Clinico Duran S.A. de C.V. </strong></td></tr></table>', false, 0);

  $image_file = 'lib/RESULTADOS_MC2-03.png';
  $this->Image($image_file, 5, 80, 150, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
  $image_file = 'lib/RESULTADOS_MC3-03.png';
  $this->Image($image_file, 61, 80, 150, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
 // $image_file = 'lib/firma_jess.jpg';
  $this->Image($filename, 100, 255, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
define ("PDF_MARGIN_TOP", 55);
define ("PDF_MARGIN_BOTTOM", 70);
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

$pdf->SetFont('Helvetica', '', 9, '', 'false');

//***********   D A T O S   ***********

while($Ele=mysql_fetch_array($EleA)){

	$ResA=mysql_query("select * from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Ele[id]' order by elemento",$link);

	$Res=mysql_fetch_array($ResA);

	$descripcion= $Ele[descripcion];
//	$descripcion= utf8_encode(ucwords(strtolower($Ele[descripcion])));

	$nota=$Ele[nota];
 	$nota=nl2br($nota); //respeta salto de linea
 	$nota=ucfirst($nota); //Coloca la primer letra en mayuscula en un parrafo

	if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#e3f2ca';}

	if($Ele[tipo]=='n'){

		$Alineacion=$Ele[alineacion];

		$pdf->SetFont('Helvetica', '', 8, '', 'false');	

			if($Ele[valref]=='Si'){
				
				if($Res[$Ele[tipo]]>$Ele[max]){
					$imagen2='mayor';
				}else{
					$imagen2='menor';
				}

				$imagen='<img src="images/'.$imagen2.'.png" alt="test alt attribute" width="20" height="20" border="0" />';

				$valref='<td width="70" align="center" height="30">'.$Ele[min].'</td><td width="20" align="center" height="30">  -  </td><td width="70" align="center" height="30">'.$Ele[max].'</td>';

			}else{

				$imagen='';
				$valref='<td width="70" align="center" height="30"></td><td width="20" align="center" height="30"></td><td width="70" align="center" height="30"></td>';
			}



			if($Res[$Ele[tipo]]>$Ele[max] or $Res[$Ele[tipo]]<$Ele[min]){


				if($Res[$Ele[tipo]]==0){

					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth">'.$imagen.'</td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30" border="1"><b>*** En Proceso </b></td>'.$valref.'<td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';

				}else{

					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth">'.$imagen.'</td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30" border="1"><b>'.number_format($Res[$Ele[tipo]],2).'   '.$Ele[unidad].'</b></td>'.$valref.'<td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';

				}
			}else{

				$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30">'.number_format($Res[$Ele[tipo]],2).'   '.$Ele[unidad].'</td>'.$valref.'<td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';

			}

	}elseif($Ele[tipo]=='t'){

			if($Ele[condiciona]==''){  

	 			$restexto=nl2br($Res[$Ele[tipo]]); //respeta salto de linea
			
				$pdf->SetFont('Helvetica', '', 8, '', 'false');

				$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="750" align="left" height="30" colspan="6">'.$restexto.'</td></tr></table>';
			}else{

				$ResB=mysql_query("select * from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Ele[condiciona]' order by elemento",$link);

				$Resb=mysql_fetch_array($ResB);

				$EleC=mysql_query("select * from $tabla where estudio='$Estudio' and id='$Ele[condiciona]'",$link);

				$Elec2=mysql_fetch_array($EleC);

				if($Resb[$Elec2[tipo]]<>''){ 

		 			$restexto=nl2br($Res[$Ele[tipo]]); //respeta salto de linea
				
					$pdf->SetFont('Helvetica', '', 8, '', 'false');

					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="750" align="left" height="30" colspan="6">'.$restexto.'</td></tr></table>';
				}else{

					$html ='';
				}


			}

	}elseif($Ele[tipo]=='c'){

			if($Ele[condiciona]==''){  

				if($Ele[valref]=='Si'){
				
					$imagen='<img src="images/clear.gif" alt="test alt attribute" width="20" height="20" border="0" />';

					$valref=$Ele[vtexto];

				}else{

					$imagen='';
					$valref='';
				}

				$pdf->SetFont('Helvetica', '',8, '', 'false');

				if($Res[$Ele[tipo]]==$Ele[vtexto]){
					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30">'.$Res[$Ele[tipo]].' '.$Ele[unidad].'</td><td width="160" align="center" height="30" colspan="3">'.$valref.'</td><td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';
				}else{
					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth">'.$imagen.'</td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30"><b>'.$Res[$Ele[tipo]].' '.$Ele[unidad].'</b></td><td width="160" align="center" height="30" colspan="3">'.$valref.'</td><td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';
				}

			}else{

				$ResB=mysql_query("select * from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Ele[condiciona]' order by elemento",$link);

				$Resb=mysql_fetch_array($ResB);

				$EleC=mysql_query("select * from $tabla where estudio='$Estudio' and id='$Ele[condiciona]'",$link);

				$Elec2=mysql_fetch_array($EleC);

				if($Resb[$Elec2[tipo]]<>''){ 

					if($Ele[valref]=='Si'){
					
						$imagen='<img src="images/clear.gif" alt="test alt attribute" width="20" height="20" border="0" />';

						$valref=$Ele[vtexto];

					}else{

						$imagen='';
						$valref='';
					}

					$pdf->SetFont('Helvetica', '', 8, '', 'false');

					if($Res[$Ele[tipo]]==$Ele[vtexto]){
						$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30">'.$Res[$Ele[tipo]].' '.$Ele[unidad].'</td><td width="160" align="center" height="30" colspan="3">'.$valref.'</td><td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';
					}else{
						$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth">'.$imagen.'</td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30"><b>'.$Res[$Ele[tipo]].' '.$Ele[unidad].'</b></td><td width="160" align="center" height="30" colspan="3">'.$valref.'</td><td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';
					}

				}else{

					$html ='';

				}
			}

	}elseif($Ele[tipo]=='d'){

			$pdf->SetFont('Helvetica', '', 8, '', 'false');

			$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30">'.$Res[$Ele[tipo]].' '.$Ele[unidad].'</td><td width="70" align="center" height="30"></td><td width="20" align="center" height="30">  -  </td><td width="70" align="center" height="30"></td><td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';

	}elseif($Ele[tipo]=='l'){

			$pdf->SetFont('Helvetica', '', 8, '', 'false');

			if($Res[$Ele[tipo]]=='N'){
				$logico='Negativo';
			}elseif($Res[$Ele[tipo]]=='S'){
				$logico='Positivo';
			}else{
				$logico='** En Proceso';				
			}

			$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: '.$Fdo.';color: #000;"><td width="20" align="rigth"></td><td width="400" align="center">'.$descripcion.'</td><td width="180" align="center" height="30">'.$logico.'</td><td width="160" align="center" height="30" colspan="3">'.$Ele[vlogico].'</td><td width="410" height="30" style="text-align:justify;"><font size="8">'.$nota.'</font></td></tr></table>';

	}elseif($Ele[tipo]=='e'){

			$Alineacion=$Ele[alineacion];

			$pdf->SetFont('Helvetica', '', 8, '', 'false');

			$descripcion= ucwords(strtoupper($Ele[descripcion]));

			$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: #aeb5ee;color: #000;"><td width="1170" align="'.$Alineacion.'"><b>'.$descripcion.'</b></td></tr></table>';

	}elseif($Ele[tipo]=='s'){

			if($Ele[condiciona]==''){  

				$Alineacion=$Ele[alineacion];

				$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

				$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: #e8eeae;color: #000;"><td width="1170" align="'.$Alineacion.'"><b>'.$descripcion.'</b></td></tr></table>';
			}else{

				$ResB=mysql_query("select * from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Ele[condiciona]' order by elemento",$link);

				$Resb=mysql_fetch_array($ResB);

				$EleC=mysql_query("select * from $tabla where estudio='$Estudio' and id='$Ele[condiciona]'",$link);

				$Elec2=mysql_fetch_array($EleC);

				if($Resb[$Elec2[tipo]]<>''){ 

					$Alineacion=$Ele[alineacion];

					$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: #e8eeae;color: #000;"><td width="1170" align="'.$Alineacion.'"><b>'.$descripcion.'</b></td></tr></table>';

				}else{

					$html ='';

				}

			}

	}elseif($Ele[tipo]=='v'){

			if($Ele[condiciona]==''){  

				$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

				$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr><td width="1170" align="left"></td></tr></table>';
			}else{

				$ResB=mysql_query("select * from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Ele[condiciona]' order by elemento",$link);

				$Resb=mysql_fetch_array($ResB);

				$EleC=mysql_query("select * from $tabla where estudio='$Estudio' and id='$Ele[condiciona]'",$link);

				$Elec2=mysql_fetch_array($EleC);

				if($Resb[$Elec2[tipo]]<>''){ 

					$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

					$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr><td width="1170" align="left"></td></tr></table>';

				}else{

					$html ='';
					
				}				
			}

	}elseif($Ele[tipo]=='z'){

			$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

			$html ='<table width="800" border="0" cellspacing="0" cellpadding="5"><tr style="background-color: #c2eeae ;color: #000;"><td width="420" align="center" colspan="2"><b>Elemento</b></td><td width="180" align="center" height="30"><b>Resultado</b></td><td width="160" align="center" height="30" colspan="3"><b>Valores de Ref.</b></td><td width="410" align="center" height="30"><b></b></td></tr></table></td>';

	}

	$pdf->writeHTML($html,false,false,true,false,'');

	if($Ele[celdas]=='Si'){

		$Celda1=$Ele[celda1];
 		$Celda1=nl2br($Celda1); //respeta salto de linea
 		$Celda1=ucfirst($Celda1); //Coloca la primer letra en mayuscula en un parrafo

 		$Celda2=$Ele[celda2];
 		$Celda2=nl2br($Celda2); //respeta salto de linea
 		$Celda2=ucfirst($Celda2); //Coloca la primer letra en mayuscula en un parrafo

 		$Celda3=$Ele[celda3];
 		$Celda3=nl2br($Celda3); //respeta salto de linea
 		$Celda3=ucfirst($Celda3); //Coloca la primer letra en mayuscula en un parrafo

		$html ='<table width="800" border="0" cellspacing="10" cellpadding="5"><tr><td width="390" height="30" style="text-align:justify;"><font size="8">'.$Celda1.'</font></td><td width="390" height="30" style="text-align:justify;"><font size="8">'.$Celda2.'</font></td><td width="390" height="30" style="text-align:justify;"><font size="8">'.$Celda3.'</font></td></tr></table>';

		$pdf->writeHTML($html,false,false,true,false,'');

	}
	
	$nRng++;
}

	$CapA=mysql_query("select impest,statustom from otd where orden='$Orden' and estudio='$Estudio'",$link);

	$Cap=mysql_fetch_array($CapA);

	//$Perlab=mysql_query("select * from perlab where alias='$Cap[impest]'",$link);

	$Perlab=mysql_query("select * from perlab where alias='$Usr'",$link);

	$Personal=mysql_fetch_array($Perlab);

	$Perlab1=mysql_query("select * from perlab where id=1",$link);

	$Personal1=mysql_fetch_array($Perlab1);

	$pdf->SetFont('Helvetica', '', 9, '', 'false');

if($Personal[cedula]<>''){

	$html ='<br><table width="800" border="0" cellspacing="0" cellpadding="0"><tr><td width="150"></td><td width="400" align="center"><b> A T E N T A M E N T E </b></td><td width="100"></td><td width="400" align="center"><b> R E A L I Z O </b></td></tr><tr><td width="150"></td><td width="400" align="center"><img src="Firma2.jpg" alt="test alt attribute" width="250" height="120" border="0" /></td><td width="100"></td><td width="300" align="center"></td></tr><tr><td width="150"></td><td width="400" align="center">'.$Personal1[profesion].' '.$Personal1[nombre].'</td><td width="100"></td><td width="400" align="center">'.$Personal[profesion].' '.$Personal[nombre].'</td></tr><tr><td width="150"></td><td width="400" align="center">Cedula Profesional '.$Personal1[cedula].'</td><td width="100"></td><td width="400" align="center">Cedula Profesional '.$Personal[cedula].'</td></tr></table>';

}else{

	$html ='<br><table width="1000" border="0" cellspacing="0" cellpadding="0"><tr><td width="350"></td><td width="400" align="center"><b> A T E N T A M E N T E </b></td></tr><tr><td width="350"></td><td width="400" align="center"><img src="Firma2.jpg" alt="test alt attribute" width="250" height="100" border="0" /></td></tr><tr><td width="350"></td><td width="400" align="center">'.$Personal1[profesion].' '.$Personal1[nombre].'</td></tr><tr><td width="350"></td><td width="400" align="center">Cedula Profesional '.$Personal1[cedula].'</td></tr></table>';
}	


$pdf->writeHTML($html,false,false,false,false,'');


$pdf->SetFont('Helvetica', 'I', 7, '', 'false');

$html ='<br><table width="800" border="0" cellspacing="0" cellpadding="5"><tr><td>'.$Ot[servicio].' / '.$Cap[statustom].' / '.$Ot[observaciones].' / '.$Ot[diagmedico].'</td></tr></table>';

//$pdf->writeHTML($html,false,false,false,false,'');

/*
$html ='<br><br><table width="800" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="QRCode/LCD_QRCode.png" alt="test alt attribute" width="100" height="100" border="0" /> Laboratorio Clinico Duran S.A. de C.V. / www.dulab.com.mx </td></tr></table>';

$pdf->writeHTML($html,false,false,false,false,'C');
*/

// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("$Orden'_'$Estudio'.pdf'", 'I');
mysql_close();
?>

