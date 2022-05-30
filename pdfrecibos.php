<?php

#Librerias
session_start();

require("lib/lib.php");
$link = conectarse();
$Cia = $_SESSION["Usr"][1];
$busca = $_REQUEST[busca];

$NomA = mysql_query("SELECT * FROM nom WHERE id='$busca' ");
$Nom = mysql_fetch_array($NomA);

$Fecha = strtotime($Nom[fecha]);
if ($Nom[nomina] == 'Semana') {
    $FechaI = strtotime("-6 days", $Fecha);
} else {
    $FechaI = strtotime("-14 days", $Fecha);
}

$cCiaA = mysql_query("SELECT razon FROM cia WHERE id='$Cia' ");
$cCia = mysql_fetch_array($cCiaA);
$Reg = "SELECT nomf.cuenta, emp.nombre, nomf.faltas, nomf.sueldo, nomf.septimo, nomf.ispt,
             nomf.sueldo + nomf.septimo - nomf.ispt, nomf.sueldod, depn.nombre, emp.departamento,
             nomf.asistencia, emp.credencial, nomf.impfallas, nomf.impretardos, nomf.ahorro, nomf.prestamo,
             nomf.pension, nomf.faltas,nomf.cobertura,nomf.primavac,nomf.otrosing, nomf.horasext, nomf.otrosegr,
             nomf.diastrab, nomf.festivos,emp.infonavit
                            FROM nomf, emp, depn
                            WHERE nomf.cuenta = emp.id AND emp.departamento = depn.id AND nomf.id = '$busca'
                            ORDER BY emp.departamento, emp.id";

$RegA = mysql_query($Reg);

if ($Nom[nomina] == 'Semana') {
    $DiasT = $cCia[diasemana];
} else {
    $DiasT = $cCia[diaquincena];
}

//$htmlcontent = getHeadNews($db);

$doc_title = "Recibos de pagos";
$doc_subject = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

// **** Formato del tipo de hoja a imprimir
define("PDF_PAGE_FORMAT", "A4");

//Paramentro como LogotipoImg,,Nombre de la Empresa,Sub titulo
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
if ($Cia == 1) {
    $pdf->SetHeaderData('logo.jpg', PDF_HEADER_LOGO_WIDTH, '', '');
} elseif ($Cia == 2) {
    $pdf->SetHeaderData('logo.jpg', PDF_HEADER_LOGO_WIDTH, '', '');
} else {
    $pdf->SetHeaderData('logo.jpg', PDF_HEADER_LOGO_WIDTH, '', '');
}
//define ("PDF_HEADER_LOGO", "logo_example.png");
//	Define el tamaño del margen superior e inferior;
define("PDF_MARGIN_TOP", 23);
define("PDF_MARGIN_BOTTOM", 15);

// Tamaño de la letra;
define("PDF_FONT_SIZE_MAIN", 8);

//Titulo que va en el encabezado del archivo pdf;
define("PDF_HEADER_TITLE", "Recibos de pagos");


//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->setLanguageArray($l); //set language items
//initialize document
//$pdf->AliasNbPages();

$pdf->AddPage();

// set barcode
$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
//$pdf->SetBarcode("Fabrica de Texcoco");
//$pdf->writeHTML('<table border="1" cellspacing="0" cellpadding="2" width="100%"><tr><th height="90" width="100" align="left">LOGO</th><th width="640">'.$cCia[0].'</th></tr></table>', true, 0, true, 1);
while ($rg = mysql_fetch_array($RegA)) {

    $Ingresos = $rg[sueldo] + $rg[septimo] + $rg[cobertura] + $rg[primavac] + $rg[otrosing] + $rg[horasext] + $rg[festivos];
    $Egresos = $rg[ispt] + $rg[ahorro] + $rg[prestamo] + $rg[pension] + $rg[otrosegr] + $rg[infonavit];
    $pdf->writeHTML('<b>' . $cCia[0] . '</b>', true, 0);
    $pdf->writeHTML('Recibo de pago de salario del ' . $Nom[fechai] . '  al ' . $Nom[fechaf], true, 0);
    $pdf->writeHTML('No.Cta: ' . $rg[credencial] . ' ' . $rg[1] . '            Depto.:' . ucfirst(strtolower($rg[8])) . '          D.trabs: ' . $rg[diastrab] . '     No.faltas: ' . $rg[faltas] . '   ', true, 1);

    $pdf->writeHTML("
   <table width='80%' border='0' cellpadding='0' cellspacing='0'>   
   <tr><th align='right'><b>Percepciones</b></th><th>-</th><th align='left'><b>Deducciones</b></th><th>-</th></tr>
   <tr><th>Sueldo</th><th align='right'>" . number_format($rg[sueldo], "2") . "  </th><th>I.s.p.t.</th><th align='right'>" . number_format($rg[ispt], "2") . "</th></tr>
   <tr><th>Septimo dia</th><th align='right'>" . number_format($rg[septimo], "2") . "  </th><th>Caja de ahorro</th><th align='right'>" . number_format($rg[ahorro], "2") . "</th></tr>
   <tr><th>Cobertura</th><th align='right'>" . number_format($rg[cobertura], "2") . "  </th><th>Pago prestamo</th><th align='right'>" . number_format($rg[prestamo], "2") . "</th></tr>
   <tr><th>Prima Vacacional</th><th align='right'>" . number_format($rg[primavac], "2") . "  </th><th>Pension</th><th align='right'>" . number_format($rg[pension], "2") . "</th></tr>
   <tr><th>Otras percepciones</th><th align='right'>" . number_format($rg[otrosing], "2") . "  </th><th>Infonavit</th><th align='right'>" . number_format($rg[infonavit], "2") . "</th></tr>
   <tr><th>Horas extras</th><th align='right'>" . number_format($rg[horasext], "2") . "  </th><th>Otras deducciones</th><th align='right'>" . number_format($rg[otrosegr], "2") . "</th></tr>
   <tr><th>Festivo lab</th><th align='right'>" . number_format($rg[festivos], "2") . "  </th><th>&nbsp;</th><th align='right'>&nbsp;</th></tr>
   <tr><th>Total ingresos </th><th align='right'>" . number_format($Ingresos, "2") . "  </th><th>Total egresos </th><th align='right'>" . number_format($Egresos, "2") . "</th></tr>
   <tr><th>&nbsp;</th><th align='right'>&nbsp;</th><th align='right'><b>A pagar</b></th><th align='right'><b>" . number_format($Ingresos - $Egresos, "2") . "</b></th></tr>
   </table>", true, 0);

    $pdf->writeHTML('Recibi de conformidad: ____________________________________________', true, 0);
    $pdf->writeHTML('<small>Recibi la cantidad indicada que cumple a la fecha el importe de mi salario, tiempo extra, percepciones y demas prestaciones que tengo derecho, sin que se me adeude alguna cantidad por algun concepto</small><br><hr>', true, 0);

    $rg = mysql_fetch_array($RegA);
    $Ingresos = $rg[sueldo] + $rg[septimo] + $rg[cobertura] + $rg[primavac] + $rg[otrosing] + $rg[horasext] + $rg[festivos];
    $Egresos = $rg[ispt] + $rg[ahorro] + $rg[prestamo] + $rg[pension] + $rg[otrosegr] + $rg[infonavit];

    $pdf->writeHTML('<b>' . $cCia[0] . '</b>', true, 0);
    $pdf->writeHTML('Recibo de pago de salario del ' . $Nom[fechai] . '  al ' . $Nom[fechaf], true, 0);
    $pdf->writeHTML('No.Cta: ' . $rg[credencial] . ' ' . $rg[1] . '            Depto.:' . ucfirst(strtolower($rg[8])) . '          D.trabs: ' . $rg[diastrab] . '     No.faltas: ' . $rg[faltas] . '   ', true, 1);

    $pdf->writeHTML("
   <table width='80%' border='0' cellpadding='0' cellspacing='0'>   
   <tr><th align='right'><b>Percepciones</b></th><th>-</th><th align='left'><b>Deducciones</b></th><th>-</th></tr>
   <tr><th>Sueldo</th><th align='right'>" . number_format($rg[sueldo], "2") . "  </th><th>I.s.p.t.</th><th align='right'>" . number_format($rg[ispt], "2") . "</th></tr>
   <tr><th>Septimo dia</th><th align='right'>" . number_format($rg[septimo], "2") . "  </th><th>Caja de ahorro</th><th align='right'>" . number_format($rg[ahorro], "2") . "</th></tr>
   <tr><th>Cobertura</th><th align='right'>" . number_format($rg[cobertura], "2") . "  </th><th>Pago prestamo</th><th align='right'>" . number_format($rg[prestamo], "2") . "</th></tr>
   <tr><th>Prima Vacacional</th><th align='right'>" . number_format($rg[primavac], "2") . "  </th><th>Pension</th><th align='right'>" . number_format($rg[pension], "2") . "</th></tr>
   <tr><th>Otras percepciones</th><th align='right'>" . number_format($rg[otrosing], "2") . "  </th><th>Infonavit</th><th align='right'>" . number_format($rg[infonavit], "2") . "</th></tr>
   <tr><th>Horas extras</th><th align='right'>" . number_format($rg[horasext], "2") . "  </th><th>Otras deducciones</th><th align='right'>" . number_format($rg[otrosegr], "2") . "</th></tr>
   <tr><th>Festivo lab</th><th align='right'>" . number_format($rg[festivos], "2") . "  </th><th>&nbsp;</th><th align='right'>&nbsp;</th></tr>
   <tr><th>Total ingresos </th><th align='right'>" . number_format($Ingresos, "2") . "  </th><th>Total egresos </th><th align='right'>" . number_format($Egresos, "2") . "</th></tr>
   <tr><th>&nbsp;</th><th align='right'>&nbsp;</th><th align='right'><b>A pagar</b></th><th align='right'><b>" . number_format($Ingresos - $Egresos, "0") . "</b></th></tr>
   </table>", true, 0);

    $pdf->writeHTML('Recibi de conformidad: ____________________________________________', true, 0);
    $pdf->writeHTML('<small>Recibi la cantidad indicada que cumple a la fecha el importe de mi salario, tiempo extra, percepciones y demas prestaciones que tengo derecho, sin que se me adeude alguna cantidad por algun concepto</small><br><hr>', true, 0);
}

// output some content
$pdf->SetFont("vera", "BI", 20);
//$pdf->Cell(0,10,"TEST Bold-Italic Cell",1,1,'C');
// output some UTF-8 test content
//$pdf->AddPage();
$pdf->SetFont("FreeSerif", "", 12);
//$utf8text = file_get_contents("utf8test.txt", false); // get utf-8 text form file
//$pdf->SetFillColor(230, 240, 255, false);
$pdf->Write(5, $utf8text, '', 1);


//Close and output PDF document
$pdf->Output();

//============================================================+
// END OF FILE                                                 
//============================================================+

mysql_close();
?>

