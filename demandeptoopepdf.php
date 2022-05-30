<?php

	session_start();

	include_once ("auth.php");

	include_once ("authconfig.php");

	include_once ("check.php");

	require("lib/lib.php");

	$Usr=strtoupper ($check['uname']);

	$Titulo="Demanda de estudios - Detallado";

	$link=conectarse();
  
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

	$Institucion2=$_REQUEST[Institucion];

	$FecI=$_REQUEST[FechaI];

	$FecF=$_REQUEST[FechaF];

	$Fechai=$FecI;

	$Fechaf=$FecF;

	$Titulo=" Demanda de estudios - Detallado ";

	$Departamento=$_REQUEST[Departamento];

	$Servicio=$_REQUEST[Servicio];

	if($Servicio=="*"){
	  $Servicio=" ";
	}else{
	  $Servicio=" and ot.servicio='$Servicio'";
	}

	$Fecha=date("Y-m-d");

	$Hora=date("H:i");

	$doc_title    = " Demanda de estudios - Detallado ";
	$doc_subject  = "recibos unicode";
	$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	//require_once('tcpdf2/tcpdf_include.php');

	$InstA = mysql_query("SELECT institucion as id, nombre FROM inst WHERE institucion='$Institucion'");
	$NomI = mysql_fetch_array($InstA);

	$Sucursal= " ";
	
	if($sucursalt=="1"){  
	
		$Sucursal=" ";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}	

		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}
		if($sucursal6=="1"){
			$Sucursal2= $Sucursal2 . "San Vicente - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}



	if($Sucursal==" "){
		$Sucursal=" ";
	}else{
		$Sucursal= "AND (".$Sucursal.")";
	}

	if($Departamento=="*"){
		$Departamento=" ";
	}else{
		$Departamento= "AND dep.departamento=$Departamento";
	}

	if($Institucion=="*"){
		$Institucion2="*- Todos";
		$Institucion=" ";
	}else{
		$Institucion2= $NomI[id].   $NomI[nombre];
		$Institucion= "AND ot.institucion=$Institucion";
	}

	$Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf ";

    $cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, dep.departamento
		FROM ot
		LEFT JOIN otd on otd.orden=ot.orden
		LEFT JOIN est on otd.estudio=est.estudio 
		LEFT JOIN dep on est.depto=dep.departamento 
		WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' $Servicio $Departamento $Institucion $Sucursal 
		GROUP BY est.depto, est.subdepto, otd.estudio";

	$registro3=mysql_query("SELECT count(distinct ot.orden)
		FROM ot
		LEFT JOIN otd on otd.orden=ot.orden
		LEFT JOIN est on otd.estudio=est.estudio 
		LEFT JOIN dep on est.depto=dep.departamento 
		WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' $Servicio $Departamento $Institucion $Sucursal");

	$registro2=mysql_fetch_array($registro3);

$UpA=mysql_query($cSql,$link);

// ********************  E N C A B E Z A D O  ****************

	class MYPDF extends TCPDF {

	    //Page header
	    function Header() {
	    	global $Institucion2,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$sucursal6,$Titulo,$Sucursal2;

	   	$InstA   = mysql_query("SELECT SELECT institucion as id, nombre FROM inst WHERE institucion='$Institucion'");
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

        $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Institucion: '.$Institucion2.'</td></tr></table>', false, 0);

        $this->SetFont('Helvetica', 'BI', 8);

		$this->writeHTML('<br><br><table align="center" width="1150" border="1" cellspacing="1" cellpadding="0">
        <tr>
        <td align="center" bgcolor="#808B96" width="100">Depto</td>
        <td align="center" bgcolor="#808B96" width="250">Subdepto</td>
        <td align="center" bgcolor="#808B96" width="250">Estudio</td>
        <td align="center" bgcolor="#808B96" width="450">Descripcion</td>
        <td align="center" bgcolor="#808B96" width="100">#Est</td></tr></table>', false, 0);


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

//$FechaAux=strtotime($Fecha);
//$nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
//$FechaAnt=date("Y-m-d",$nDias);

        $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;

        while($registro=mysql_fetch_array($UpA)) {
    		if($subdep==$registro[8]){
				$departamento=" ";
				$subdepartamento=" ";
			}else{
				$departamento=$registro[7];
				$subdepartamento=$registro[8];
		        //$html = '<table align="center" width="1150" border="1" cellspacing="1" cellpadding="0"><tr><td></td><td></td><td></td><td></td><td colspan="5"><hr></td></tr></table>';
		       // $pdf->writeHTML($html,true,false,true,false,'C');

	    		if($Noveces<>0){
			        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
                    <tr>
                    <td align="center" width="100"></td>
                    <td align="center" width="250"></td>
                    <td align="center" width="250"></td>
                    <td align="center" width="450" bgcolor="#808B96"><strong>T o t a l</strong></td>
                    <td align="center" width="100" bgcolor="#808B96"><strong>'.number_format($Noveces2).'</strong></td>
                    </tr></table>';
			        $pdf->writeHTML($html,true,false,true,false,'');
        		     $Noveces3=$Noveces3+$Noveces2;
				     $Nordenes3=$Nordenes3+$Nordenes2;
		             $Descuentos3=$Descuentos3+$Descuentos2;
		             $Subtotal3=$Subtotal3+$Subtotal2;
		             $Total3=$Total3+$Total2;
		             $Noveces2=0;
		             $Nordenes2=0;
        		     $Descuentos2=0;
		             $Subtotal2=0;
        		     $Total2=0;

				}
			}

		$pdf->SetFont('Helvetica', '', 7, '', 'false');

		$html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0"><tr>
		  <td width="100">'.$departamento.'</td>
		  <td width="250"><strong>'.$subdepartamento.'</strong></td>
		  <td width="250">'.$registro[0].'</td>
		  <td align="left" width="450">'.$registro[1].'</td>
		  <td align="right" width="100">'.number_format($registro[3]).'</td>
		</tr></table>';

			$pdf->writeHTML($html,true,false,true,false,'');

             $Noveces2=$Noveces2+$registro[3];
		     $Nordenes2=$Nordenes2+$registro[6];
             $Descuentos2=$Descuentos2+$registro[5];
             $Subtotal2=$Subtotal2+$registro[4];
             $Total2=$Total2+($registro[4]-$registro[5]);

             $Noveces=$Noveces2+$registro[3];
		     $Nordenes=$Nordenes2+$registro[6];
             $Descuentos=$Descuentos2+$registro[5];
             $Subtotal=$Subtotal2+$registro[4];
             $Total=$Total2+($registro[4]-$registro[5]);
			 $Cuenta=$Cuenta+$registro[6];
			 $subdep=$registro[8];
		

        }//fin while
   		     $Noveces3=$Noveces3+$Noveces2;
		     $Nordenes3=$Nordenes3+$Nordenes2;
             $Descuentos3=$Descuentos3+$Descuentos2;
             $Subtotal3=$Subtotal3+$Subtotal2;
             $Total3=$Total3+$Total2;

        $pdf->SetFont('Helvetica', '', 7, '', 'false');

        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
        <tr>
        <td align="center" width="100"></td>
        <td align="center" width="250"></td>
        <td align="center" width="250"></td>
        <td align="center" bgcolor="#808B96" width="450"><strong>T o t a l</strong></td>
		<td align="center" bgcolor="#808B96" width="100"><strong>'.number_format($Noveces2).'</strong></td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
        <td align="center" width="100"></td>
        <td align="center" width="250"></td>
        <td align="CENTER" bgcolor="#808B96" width="250"><strong> No. Ordenes : '.number_format($registro2[0]).'</strong></td>
        <td align="CENTER" bgcolor="#808B96" width="450"><strong>Total Gral.</strong></td>
        <td align="center" bgcolor="#808B96" width="100"><strong>'.number_format($Noveces3).'</strong></td>
        </tr>
        </table><br><br><br>';

        $pdf->writeHTML($html,false,false,true,false,'C');

        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
       	<tr>
       	<td align="center" width="250"></td>
       	<td align="center" bgcolor="#808B96" width="100"><strong>Depto</strong></td>
       	<td align="center" bgcolor="#808B96" width="320"><strong>Nombre</strong></td>
       	<td align="center" bgcolor="#808B96" width="100"><strong>No. Estudios</strong></td>
       	<td align="center" bgcolor="#808B96" width="100"><strong> - </strong></td>
       	</tr>
        </table>';

        $pdf->writeHTML($html,true,false,true,false,'');

		$cSqla="SELECT dep.departamento,dep.nombre,count(otd.orden) as ordenes,sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as importe,count(distinct otd.orden)
		FROM ot
		LEFT JOIN otd on otd.orden=ot.orden
		LEFT JOIN est on otd.estudio=est.estudio 
		LEFT JOIN dep on est.depto=dep.departamento 
		WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' $Servicio $Departamento $Institucion $Sucursal 
		GROUP BY dep.departamento";

		$UpB=mysql_query($cSqla,$link);

		$html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">';

        while($dm=mysql_fetch_array($UpB)) {

				if (($nRng % 2) > 0) {
				    $Fdo = '#E1E1E1';
				} else {
				    $Fdo = '#ABB2B9';
				}

  	      		$html.='<tr bgcolor='.$Fdo.'>
        		<td align="left" width="250" bgcolor="#FFF"> </td>
        		<td align="center" width="100">'.$dm[departamento].'</td>
        		<td align="left" width="320">'.$dm[nombre].'</td>
        		<td align="right" width="100">'.number_format($dm[ordenes],"2").' &nbsp; </td>
        		<td align="center" width="100">'.number_format(($dm[ordenes]/$Noveces3)*100,"0").' % </td>
        		</tr>';
        		$nRng++;
        }

$html.='</table>';

$pdf->writeHTML($html,true,false,true,false,'');

//</body>
//</html>
ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>
