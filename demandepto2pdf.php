<?php
session_start();

require("lib/lib.php");

require ("config.php");	

$link = conectarse();

$Usr = $check['uname'];

$busca = $_REQUEST[busca];

$Institucion = $_REQUEST[Institucion];
$Institucion1 = $_REQUEST[Institucion];
$Sucursal = $_REQUEST[Sucursal];
//$Sucursal     =   $Sucursal[0];
$sucursalt = $_REQUEST[sucursalt];
$sucursal0 = $_REQUEST[sucursal0];
$sucursal1 = $_REQUEST[sucursal1];
$sucursal2 = $_REQUEST[sucursal2];
$sucursal3 = $_REQUEST[sucursal3];
$sucursal4 = $_REQUEST[sucursal4];
$sucursal5 = $_REQUEST[sucursal5];
$sucursal6 = $_REQUEST[sucursal6];

$FecI = $_REQUEST[FechaI];

$FecF = $_REQUEST[FechaF];

$Fechai = $FecI;

$Fechaf = $FecF;

$Titulo = $_REQUEST[Titulo];

$Departamento = $_REQUEST[Departamento];
$Departamento1 = $_REQUEST[Departamento];
$Fecha = date("Y-m-d");

$Hora = date("H:i");


$doc_title    = "Relacion de Ordenes de trabajo";
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
    $Institucion2= $NomI[id]. $NomI[nombre];
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

$UpA = mysql_query($cSql, $link);


 // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$sucursal6,$Titulo,$Sucursal2,$Institucion2;

    $InstA   = mysql_query("SELECT institucion as id, nombre FROM inst WHERE institucion='$Institucion'");
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

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
<tr>
<td align="center" bgcolor="#808B96" width="90">Depto</td>
<td align="center" bgcolor="#808B96" width="250">Subdepto</td>
<td align="center" bgcolor="#808B96" width="250">#Estudios</td>
<td align="center" bgcolor="#808B96" width="180">Sub-total</td>
<td align="center" bgcolor="#808B96" width="180">Descuentos</td>
<td align="center" bgcolor="#808B96" width="180">Importe</td>
</tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

        $Subtotal = 0;
        $Total = 0;
        $Descuentos = 0;
        $Noveces = 0;
        $subdep = " ";
        $orden = 0;
        $nordenes = 0;
        $nRng = 0;

        while ($registro = mysql_fetch_array($UpA)) {
            if (($nRng % 2) > 0) {
                $Fdo = '#FFFFFF';
            } else {
                $Fdo = '#D5D8DC';
            }
            if ($subdep == $registro[8]) {
                $departamento1 = $departamento;
                $subdepartamento1 = $subdepartamento;
            } else {
                $departamento = $registro[7];
                $subdepartamento = $registro[8];
                if ($Noveces <> 0) {
                    

                    $pdf->SetFont('Helvetica', '', 7, '', 'false');

                    $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
                    <tr bgcolor='.$Fdo.'>
                    <td align="center" width="90">'.$departamento1.'</td>
                    <td align="center" width="250" >'.$subdepartamento1.'</td>
                    <td align="center" width="250"> '. number_format($Noveces2) . '</td>
                    <td align="right" width="180">' . number_format($Subtotal2, "2") . '</td>
                    <td align="right" width="180">' . number_format($Descuentos2, "2") . '</td>
                    <td align="right" width="180">' . number_format($Total2, "2") . '</td>
                    </tr>
                    </table>';
                    $pdf->writeHTML($html,true,false,true,false,'');

                    $Noveces3 = $Noveces3 + $Noveces2;
                    $Nordenes3 = $Nordenes3 + $Nordenes2;
                    $Descuentos3 = $Descuentos3 + $Descuentos2;
                    $Subtotal3 = $Subtotal3 + $Subtotal2;
                    $Total3 = $Total3 + $Total2;
                    $Noveces2 = 0;
                    $Nordenes2 = 0;
                    $Descuentos2 = 0;
                    $Subtotal2 = 0;
                    $Total2 = 0;
                    $departamento1 = $departamento;
                    $subdepartamento1 = $subdepartamento;
                    $nRng++;
                }
            }
            $departamento1 = $departamento;
            $subdepartamento1 = $subdepartamento;

            $Noveces2 = $Noveces2 + $registro[3];
            $Nordenes2 = $Nordenes2 + $registro[6];
            $Descuentos2 = $Descuentos2 + $registro[5];
            $Subtotal2 = $Subtotal2 + $registro[4];
            $Total2 = $Total2 + ($registro[4] - $registro[5]);

            $Noveces = $Noveces2 + $registro[3];
            $Nordenes = $Nordenes2 + $registro[6];
            $Descuentos = $Descuentos2 + $registro[5];
            $Subtotal = $Subtotal2 + $registro[4];
            $Total = $Total2 + ($registro[4] - $registro[5]);
            $Cuenta = $Cuenta + $registro[6];
            $subdep = $registro[8];
            
        }//fin while

        $Noveces3 = $Noveces3 + $Noveces2;
        $Nordenes3 = $Nordenes3 + $Nordenes2;
        $Descuentos3 = $Descuentos3 + $Descuentos2;
        $Subtotal3 = $Subtotal3 + $Subtotal2;
        $Total3 = $Total3 + $Total2;

        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
        <tr>
        <td align="center"  width="90" bgcolor="#D5D8DC">'.$departamento1.'</td>
        <td align="center"  width="250" bgcolor="#D5D8DC">'.$subdepartamento1.'</td>
        <td align="center"  width="250" bgcolor="#D5D8DC"> '. number_format($Noveces2) . '</td>
        <td align="right"  width="180" bgcolor="#D5D8DC">' . number_format($Subtotal2, "2") . '</td>
        <td align="right"  width="180" bgcolor="#D5D8DC">' . number_format($Descuentos2, "2") .' </td> 
        <td align="right"  width="180" bgcolor="#D5D8DC">' . number_format($Total2, "2") .' </td>
        </tr>
        </table>';
        $pdf->writeHTML($html,true,false,true,false,'');
        

        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
        <tr>
        <td align="CENTER" width="90"></td>
        <td align="CENTER" width="250" bgcolor="#808B96">No. Ordenes:'. number_format($registro2[0]).'</td>
        <td align="center" width="250" bgcolor="#808B96">'.number_format($Noveces3) .'</td>
        <td align="right" width="180" bgcolor="#808B96">'.number_format($Subtotal3, "2") .'</td>
        <td align="right"  width="180" bgcolor="#808B96">'.number_format($Descuentos3, "2") .'</td>
        <td align="right" width="180" bgcolor="#808B96">'.number_format($Total3, "2") .'</td>
        </tr>
        </table><br>';
        $pdf->writeHTML($html,true,false,true,false,'');



        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
        <tr>
        <td width="80"></td>
        <td align="left" bgcolor="#808B96" width="80" >Depto</td>
        <td align="left" bgcolor="#808B96" width="300" >Nombre</td>
        <td align="right" bgcolor="#808B96" width="100">No. Estudios</td>
        <td align="right" bgcolor="#808B96" width="80"></td>
        <td align="right" bgcolor="#808B96" width="120">Sub-total</td>
        <td align="right" bgcolor="#808B96" width="100">&nbsp; Desctos </td>
        <td align="right" bgcolor="#808B96" width="100">I m p o r t e</td>
        <td align="right" bgcolor="#808B96" width="80"></td>
        </tr>
        </table>';
        $pdf->writeHTML($html,true,false,true,false,'C');



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
        		<td align="left" width="80" bgcolor="#FFF"> </td>
        		<td align="center" width="80">'.$dm[departamento].'</td>
        		<td align="left" width="300">'.$dm[nombre].'</td>
        		<td align="right" width="100">'.number_format($dm[ordenes],"2").' &nbsp; </td>
        		<td align="center" width="80">'.number_format(($dm[ordenes]/$Noveces3)*100,"0").' % </td>
        		<td align="right" width="120">'.number_format($dm[precio],"2").' &nbsp; </td>
        		<td align="right" width="100">'.number_format($dm[importe],"2").' &nbsp; </td>
        		<td align="right" width="100">'.number_format($dm[precio]-$dm[importe],"2").' &nbsp; </td>
        		<td align="center" width="80">'.number_format((($dm[precio]-$dm[importe])/$Total3)*100,"0").' % </td>
        		</tr>';
        		$nRng++;
        }

$html.='</table>';

$pdf->writeHTML($html,true,false,true,false,'');



                
ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>
<?php
mysql_close();
?>
