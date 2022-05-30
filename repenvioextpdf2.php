<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
$link = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];


$FecI = $_REQUEST["FecI"];

$FecF = $_REQUEST["FecF"];

$Fechai = $FecI;

$Fechaf = $FecF;

$HoraI = $_REQUEST["HoraI"];

$HoraF = $_REQUEST["HoraF"];

$Titulo = $_REQUEST["Titulo"];

$Fecha = date("Y-m-d");

$Fecha2 = substr($Fecha, 0, 10);

$fch3 = explode("-", $Fecha2);
$tfecha3 = $fch3[2] . "-" . $fch3[1] . "-" . $fch3[0];

$Hora = date("H:i");

$SucursalD = $_REQUEST["SucursalDe"];

if ($SucursalD == "*") {
    $SucursalDe = "";
} else {
    $SucursalDe = "and ot.suc=$SucursalD";
}

$SucursalP = $_REQUEST["Proveedor"];

if ($SucursalP == "*") {
    $SucursalPara = "";
} else {
    $SucursalPara = "and maqdet.mext='$SucursalP'";
}

$personal = $_REQUEST["personal"];

if ($personal == "*") {
    $personale = "";
} else {
    $personale = "and maqdet.usrenvext='$personal'";
}

$Titulo = "Hoja de envio de Estudios - Orthin";

$cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,ot.medico,ot.medicon,cli.cliente,cli.apellidop,cli.apellidom,cli.nombre,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,est.clavealt,maqdet.usrenvext FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$FecI' and maqdet.fenvext <='$FecF' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale order by maqdet.orden";

$UpA = mysql_query($cSql);


$cSql2 = "SELECT * FROM authuser WHERE uname='$Gusr'";

$UpA2 = mysql_query($cSql2);

$rg2 = mysql_fetch_array($UpA2);

if ($rg2[team] == '4') {
    $Sucur = '20364';
} else {
    $Sucur = '21134';
}

require ("config.php");

$doc_title = "$Titulo";
$doc_subject = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// ********************  E N C A B E Z A D O  ****************


class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $FechaI, $FechaF, $Fecha, $Sucur, $tfecha3;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/logoorthin.jpg';
        $this->Image($image_file2, 8, 5, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', 'B', 10);

        $this->writeHTML('<table border="0"><tr><td width="200">&nbsp;</td><td width="600"></td></tr><tr><td width="200">&nbsp;</td><td width="600"></td></tr><tr><td width="200">&nbsp;</td><td width="650" align="center">Solicitud de Estudios por Base</td></tr><tr><td width="200">&nbsp;</td><td width="600"></td></tr></table><hr>', false, 0);

        $this->SetFont('helvetica', 'B', 8);

        $this->writeHTML('<br><table width="100" border="0"><tr><td width="400"></td><td width="300">Nombre y Clave de laboratorio: </td><td width="400">Laboratorio Clinico Duran S.A. de C.V. </td><td width="100">Fecha: </td><td width="300"> ' . $tfecha3 . '</td></tr></table>', false, 0);

        $Tit1 = '<br><table border="1" width="98%"><tr align="center"><th width="80" rowspan="2"><b><br />APELLIDO <br />PATERNO</b></th><th width="80" rowspan="2"><b><br />APELLIDO<br />MATERNO</b></th><th width="140" rowspan="2"><b><br /><br />NOMBRE (S)</b></th><th width="100"><b><br />FECHA DE NACIMIMENTO</b></th><th width="50"><b><br />SEXO <br /></b></th><th width="100" rowspan="2"><b><br /><br />ID CONSECUTIVO</b></th><th width="100"><b><br />FECHA DE <br /> SOLICITUD</b></th><th width="120" rowspan="2"><b><br />CENTRO DE <br /> PROCESAMIENTO</b></th><th width="15" rowspan="2"><b><br /><br />Uso Lab 1</b></th><th width="60" rowspan="2"><b><br />CLAVE DE <br /> CLIENTE</b></th><th width="15" rowspan="2"><b><br /><br />Uso Lab 2</b></th><th width="15" rowspan="2"><p class="verticalText"><b><br /><br />Uso Lab 3</b></p></th><th width="15" rowspan="2"><b><br /><br />Uso Lab 4</b></th><th width="15" rowspan="2"><b><br /><br />Uso Lab 5</b></th><th width="200" rowspan="2"><b><br />MEDICO</b></th><th width="60" rowspan="2"><b><br />ID PACIENTE</b></th><th colspan="2" width="70"><b><br />FECHA DE TOMA <br /> DE MUESTRA</b></th><th colspan="2" width="70"><b><br />HORA DE TOMA <br /> DE MUESTRA</b></th><th width="150" rowspan="2"><b><br />OBSERVACIONES</b></th><th width="70" rowspan="2"><b><br />CLAVE (s) DE <br /> ESTUDIO (S)</b></th></tr>';

        $Tit1 .= '<tr align="center"><th width="100"><b><br />(dd/mm/aaaa) </b></th><th width="50"><b><br />(M/F)</b></th><th width="100"><b><br />(dd/mm/aaaa)</b></th><th width="70"><b><br />(dd/mm/aaaa)</b></th><th width="70"><b><br />(hh:mm)</b></th></tr></table>';

        $this->SetFont('helvetica', '', 5);

        $this->writeHTML($Tit1, false, 0);
    }

    // Page footer
    function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -35, 'Pag. ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
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

define("PDF_PAGE_FORMAT", "letter");

//  Define el tamaño del margen superior e inferior;
define("PDF_MARGIN_TOP", 50);
define("PDF_MARGIN_BOTTOM", 18);
// Tamaño de la letra;
define("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define("PDF_HEADER_TITLE", "Reporte");

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 8));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
//$pdf->AliasNbPages();

$pdf->AddPage('L', 'letter'); //Orientacion P-Vertical L-Horizontal, tamaño de pagina

$pdf->SetFont('Helvetica', '', 5, '', 'false');

//***********   D A T O S   ***********

$Estudios = 1;

$Tit2 = '<table border="1" width="98%">';

while ($rg = mysql_fetch_array($UpA)) {

    if (($Estudios % 2) > 0) {
        $Fdo = '#FFFFFF';
    } else {
        $Fdo = '#e3f2ca';
    }

//    $nommed=$rg2[nombrec];

    if ($rg[medico] == 'MD') {

        $nommed = $rg[medicon];
    } else {

        $cSql2 = "SELECT * FROM med WHERE med.medico='$rg[medico]'";

        $UpA2 = mysql_query($cSql2);

        $rg2 = mysql_fetch_array($UpA2);

        $nommed = $rg2[nombrec];
    }


    $cSql3 = "SELECT * FROM est WHERE est.estudio='$rg[estudio]'";

    $UpA3 = mysql_query($cSql3);

    $rg3 = mysql_fetch_array($UpA3);

    $cSql4 = "SELECT fechaest FROM otd WHERE otd.estudio='$rg[estudio]' and otd.orden='$rg[ord]'";

    $UpA4 = mysql_query($cSql4);

    $rg4 = mysql_fetch_array($UpA4);

    $ftoma = substr($rg4[fechaest], 0, 10);

    $fch2 = explode("-", $ftoma);
    $tfecha2 = $fch2[2] . "-" . $fch2[1] . "-" . $fch2[0];

    $htoma = substr($rg4[fechaest], 11, 5);

    $fch = explode("-", $rg[fechan]);
    $tfecha = $fch[2] . "-" . $fch[1] . "-" . $fch[0];

    $Tit3 .= '<tr style="background-color: ' . $Fdo . ';color: #000;" align="center"><th width="80"><br />' . utf8_encode(strtoupper($rg[apellidop])) . '</th><th width="80"><br />' . utf8_encode(strtoupper($rg[apellidom])) . '</th><th width="140"><br />' . utf8_encode(strtoupper($rg[nombre])) . '</th><th width="100"><br />' . $tfecha . '</th><th width="50"><br />' . $rg[sexo] . '</th><th width="100"><br /></th><th width="100"><br /></th><th width="120"><br /></th><th width="15"><br /></th><th width="60"><br />' . $Sucur . '</th><th width="15"><br /></th><th width="15"><br /></th><th width="15"><br /></th><th width="15"><br /></th><th width="200"><br />' . $nommed . '</th><th width="60"><b><br /></b></th><th width="70"><br />' . $tfecha2 . '</th><th width="70"><br />' . $htoma . '</th><th width="150"><br />' . $rg[descripcion] . '</th><th width="70"><br />' . $rg[clavealt] . '</th></tr>';

    $Estudios++;
}

$Tit4 = '</table>';

$tbl = $Tit2 . $Tit3 . $Tit4;

$pdf->writeHTML($tbl, true, false, false, false, 'C');

$tbl2 = ' &nbsp;  &nbsp;  &nbsp; <a class="pg" href="repenvioextpdf2xls.php?FecI=' . $FecI . '&FecF=' . $FecF . '&HoraI=' . $HoraI . '&HoraF=' . $HoraF . '&SucursalDe=' . $SucursalD . '&Proveedor=' . $SucursalP . '&personal=' . $personal . '"><font size="2"><img src="images/excel.jpg" width="45")></a>';

$pdf->writeHTML($tbl2, true, false, false, false, 'C');

// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("Reporte.pdf", 'I');
?>

