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

$Titulo = "Hoja de envio de Estudios - Cilab";

$cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,ot.medico,ot.medicon,cli.cliente,cli.apellidop,cli.apellidom,cli.nombre,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,est.clavealt,maqdet.usrenvext FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$FecI' and maqdet.fenvext <='$FecF' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale order by maqdet.orden";

$UpA = mysql_query($cSql);

$Perlab2 = mysql_query("select * from perlab where alias='$Gusr'");

$Personal2 = mysql_fetch_array($Perlab2);

$Personal3 = $Personal1["profesion"] . ' ' . $Personal1["nombre"];

$cSql2 = "SELECT * FROM authuser WHERE uname='$Gusr'";

$UpA2 = mysql_query($cSql2);

$rg2 = mysql_fetch_array($UpA2);

$Sucur = '206';

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
        global $FechaI, $FechaF, $Fecha, $Personal3, $Sucur;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/cilab.jpg';
        $this->Image($image_file2, 8, 5, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', '', 8);

        $this->writeHTML('<table border="0"><tr><td width="20" height="25">&nbsp;</td><td width="450" align="right">Calle José del Castillo No. 3 Altos Col. San José Insurgentes</td></tr><tr><td width="20" height="25">&nbsp;</td><td width="450" align="right">Alcal. Benito Juárez, CDMX</td></tr><tr><td width="20" height="25">&nbsp;</td><td width="450" align="right">Tels. 01 (55) 24 54 79 01,  &nbsp; 01 (55) 24 54 79 02</td></tr><tr><td width="20" height="25">&nbsp;</td><td width="450" align="right">Lada sin costo 01800-161-6122</td></tr></table><hr>', false, 0);

        $this->SetFont('helvetica', 'B', 12);

        $this->writeHTML('<br><table width="1100" border="0"><tr><td width="1100" align="right">SOLICITUD DE ESTUDIOS DE MAQUILA No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td></tr></table>', false, 0);

        $Tit1 = '<br><table border="1" width="1165"><tr><td><table border="0" width="98%"><tr><td align="left" width="120">No. Cliente: </td><td align="left" width="120"> ' . $Sucur . ' </td><td align="left" width="250">Nombre de Laboratorio: </td><td align="left" width="400"> Laboratorio Clinico Duran </td><td width="100" align="left">Fecha: </td><td  width="180" align="left"> ' . $Fecha . ' </td></tr><tr><td align="left" width="240" colspan="2">Nombre de quien envía: </td><td align="left" width="650" colspan="2"> ' . $Personal3 . ' </td><td width="100" align="left">Reporte: </td><td  width="180" align="left">O Original &nbsp; O E-mail </td></tr></table></td></tr></table>';


        $this->SetFont('helvetica', '', 9);

        $this->writeHTML($Tit1, false, 0);
    }

    // Page footer
    function Footer() {
        global $CostoT, $Estudios;

        // Position at 15 mm from bottom
        $this->SetY(-10);
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
define("PDF_MARGIN_TOP", 46);
define("PDF_MARGIN_BOTTOM", 20);
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

$pdf->AddPage('P', 'letter'); //Orientacion P-Vertical L-Horizontal, tamaño de pagina

$pdf->SetFont('Helvetica', '', 8, '', 'false');

//***********   D A T O S   ***********

$Estudios = 0;

$Tit2 = '<table border="1" width="1170">';

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

    $Fechanac = $rg[fechan];
    $Fecha = date("Y-m-d");
    $array_nacimiento = explode("-", $Fechanac);
    $array_actual = explode("-", $Fecha);
    $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años 
    $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
    $dias = $array_actual[2] - $array_nacimiento[2]; // calculamos días 

    if ($dias < 0) {
        --$meses;

        //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
        switch ($array_actual[1]) {
            case 1: $dias_mes_anterior = 31;
                break;
            case 2: $dias_mes_anterior = 31;
                break;
            case 3: $dias_mes_anterior = 28;
                break;
            //                      if (bisiesto($array_actual[0])) 
            //                      { 
            //                          $dias_mes_anterior=29; break; 
            //                      } else { 
            //                          $dias_mes_anterior=28; break; 
            //                      } 
            case 4: $dias_mes_anterior = 31;
                break;
            case 5: $dias_mes_anterior = 30;
                break;
            case 6: $dias_mes_anterior = 31;
                break;
            case 7: $dias_mes_anterior = 30;
                break;
            case 8: $dias_mes_anterior = 31;
                break;
            case 9: $dias_mes_anterior = 31;
                break;
            case 10: $dias_mes_anterior = 30;
                break;
            case 11: $dias_mes_anterior = 31;
                break;
            case 12: $dias_mes_anterior = 30;
                break;
        }

        $dias = $dias + $dias_mes_anterior;
    }
    //
    //ajuste de posible negativo en $meses 
    if ($meses < 0) {
        --$anos;
        $meses = $meses + 12;
    }

    if ($anos >= '110') {
        $Edad = 'Verificar';
    } elseif ($anos == '0' and $meses == '0') {
        $Edad = $dias . ' Dias';
    } elseif ($anos == '0' and $meses >= '1') {
        $Edad = $meses . ' Meses';
    } elseif ($anos >= '1') {
        $Edad = $anos . ' A&ntilde;os ';
    }


    $Tit3 .= '<table border="1" width="1170"><tr style="background-color: ' . $Fdo . ';color: #000;" align="center"><td colspan="4" width="240">Apellido Paterno</td><td width="240">Apellido Materno</td><td width="240">Nombre(s) y/o Folio</td><td width="80">Clave</td><td width="210">Estudio(s) Solicitado(s)</td><td width="80">Precio</td><td width="75">Excl. Eli</td></tr><tr style="background-color: ' . $Fdo . ';color: #000;" align="center"><td colspan="4" width="240"><font size="7">' . utf8_encode(strtoupper($rg[apellidop])) . '</font></td><td width="240"><font size="7">' . utf8_encode(strtoupper($rg[apellidom])) . '</font></td><td width="240"><font size="7">' . utf8_encode(strtoupper($rg[nombre])) . '</font></td><td width="80"><font size="7">' . $rg[clavealt] . '</font></td><td width="210"><font size="6">' . $rg[descripcion] . '</font></td><td width="80" aling="right"><font size="7">' . number_format($rg3[costo], "2") . '</font></td><td width="75"> </td></tr><tr style="background-color: ' . $Fdo . ';color: #000;" align="center"><td width="52">Edad</td><td width="68"><font size="8">' . $Edad . '</font></td><td width="60">Sexo</td><td width="60"><font size="7">' . $rg[sexo] . '</font></td><td colspan="3" width="560">Tipo de Muestra &nbsp; O Plasma &nbsp; O Suero &nbsp; O Orina &nbsp; O Sangre T. &nbsp; O Otro</td><td width="210"><font size="6">' . $rg[obsenv] . '</font></td><td width="80"></td><td width="75"></td></tr></table>';

    $Tit3 .= '<table border="0" width="1170"><tr><td  width="1165" colspan="9"></td></tr></table>';

    $Estudios++;

    $CostoT += $rg3[costo];
}

$Tit4 = '</table>';

//$tbl = $Tit2.$Tit3.$Tit4;
$tbl = $Tit3;

$pdf->writeHTML($tbl, true, false, false, false, 'C');


$Tit1 = '<br><table border="1" width="1165"><tr><td  width="1165" colspan="9" align="right"><b>TOTAL POR ESTUDIOS SOLICITADOS &nbsp; &nbsp; &nbsp; ' . $Estudios . ' &nbsp; &nbsp; &nbsp;  $ ' . number_format($CostoT, "2") . '&nbsp; &nbsp; &nbsp; </b></td></tr></table>';

$Tit1 .= '<br><table border="0" width="1165"><tr><td  width="1165" colspan="9" align="CENTER"><b>NO LLENAR PARA USO EXCLUSIVO DE LABORATORIO DE REFERENCIA ELI</b></td></tr></table>';

$Tit1 .= '<br><table border="1" width="1165"><tr><td  width="1165" colspan="9" align="center"><br />Recibi $ __________________ Por concepto de: ___________________________ Nombre: ________________________ Firma:________________________</td></tr></table>';

$Tit1 .= '<br><table border="1" width="1165"><tr><td  width="1165" colspan="9" align="center"><br />Recepción  Eli Nombre: _____________________________ Fecha:____________________ Hora:____________________ Firma:_______________________<br /><br /> Capturó: ______________________________________ Vo. Bo.:_______________________________ ENVIÓ/IMPRIMIÓ:______________________________</td></tr></table><br />';

$pdf->writeHTML($Tit1, true, false, false, false, 'C');


// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("Reporte.pdf", 'I');
?>

