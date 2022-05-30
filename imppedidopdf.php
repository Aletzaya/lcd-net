<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=$check['uname'];
  $Team=$check['team'];

  $Titulo="Pedido";

  $link=conectarse();

  $tamPag=10;

  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30

  $HeA = mysql_query("SELECT *
         FROM pedido
         WHERE pedido.id = '$busca'");

  $He=mysql_fetch_array($HeA);


//$htmlcontent = getHeadNews($db);

$doc_title    = "Pedidos";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once("importeletras.php");
//require_once('tcpdf2/tcpdf_include.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true,'UTF-8',false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "A4");

$Team='LCD - Matriz';
$pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','                                                                                                                                                 Movimiento al almacen                                                           Concepto: '.$He[concepto]);//define ("PDF_HEADER_LOGO", "logo_example.png");


//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Almacen");

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
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<br></br><table align="center" width="100%" border="0"><tr><td align="left"><b> No.Pedido: </b>'.$He[id].'</td><td align="left"><b>Fecha: </b>'.$He[fecha].' - '.$He[hora].'</td><td align="left"><b>Almacen: </b>'.$He[unidad].'</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$html='<table align="center" width="100%" border="0"><tr><td align="left"><b>Departamento: </b>'.$He[depto].'</td><td align="left"><b>Status: </b>'.$He[status].'</td><td align="left"></td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$html='<table align="center" width="100%" border="0"><tr style="background-color: #0d486a; color:#FFF;"><td align="center" width="120" height="30"><b> Producto </b></td><td align="center" width="400"><b> Descripcion </b></td><td align="center" width="100"><b> Cantidad </b></td><td align="center" width="130"><b> Costo </b></td><td align="center" width="130"><b> Importe </b></td><td align="center" width="130"><b> Existencia </b></td><td align="center" width="130"><b> Status </b></td></tr></table>';

$pdf->writeHTML($html,false,false,true,false,'');
  
  
$Sql = "SELECT pedidod.clave, pedidod.cantidad, pedidod.costo, invl.descripcion, pedidod.existencia
         FROM pedidod, invl
         WHERE pedidod.id = '$busca' AND pedidod.clave = invl.clave
         ORDER BY pedidod.clave";

$Rolls = $Peso = $Defectos = 0;
$res   = mysql_query($Sql,$link);

$pdf->SetFont('Helvetica', '', 7, '', 'false');

while( $rg = mysql_fetch_array($res) ){

      $cantidad2 = $rg[existencia]-$rg[cantidad];

       if($rg[existencia]<='0'){
         $staped='Faltante';
         $colorletra='#FF0000';
       }else{
         if($cantidad2 >= '1'){
          $staped='Disponible';
            $colorletra='#666600';         
          }else{
          if($cantidad2 == '0'){
            $staped='Ultima Pza';
                $colorletra='#FF3300';
             }else{
              $staped='Incompleto'; 
                $colorletra='#FF0000';       
           }
        }
       }

  if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#e3f2ca';}

  $Sql2 = "SELECT *
       FROM invl
       WHERE invl.clave = '$rg[clave]'";

  $res2   = mysql_query($Sql2,$link);
  $rg2 = mysql_fetch_array($res2);

  $html='<table align="center" width="100%" border="0"><tr style="background-color: '.$Fdo.';color: #000;">
  <td align="left" width="120" height="30">'.$rg[clave].'</td>
  <td align="left" width="400">'.$rg[descripcion].'</td>
  <td align="center" width="100">'.$rg[cantidad].'</td>
  <td align="right" width="130">'.number_format($rg[costo]/$rg2[pzasmedida],"2").'</td>
  <td align="right" width="130">'.number_format(($rg[costo]/$rg2[ pzasmedida])*$rg[cantidad],"2").'</td>
  <td align="center" width="130">'.$rg[existencia].'</td><td align="center" width="130"><font color='.$colorletra.'>'.$staped.'</font></td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  $Costo += $rg[costo]/$rg2[pzasmedida];  
  $Importe += ($rg[costo]/$rg2[pzasmedida])*$rg[cantidad];  
  $Cantidad += $rg[cantidad];  

  $nRng++;
                       
}   

$html='<table align="center" width="100%" border="0"><tr style="background-color: #0d486a; color:#FFF;">
<td align="center" width="120" height="30"></td><td align="center" width="400"><b>Productos registrados: '.number_format($nRng,"0").'</b></td>
<td align="center" width="100"><b>'.number_format($Cantidad,"0").'</b></td><td align="right" width="130"><b>'.number_format($Costo,"2").'</b></td>
<td align="right" width="130"><b>'.number_format($Importe,"2").'</b></td><td align="center" width="130"></td><td align="center" width="130"></td></tr></table>';

  $pdf->writeHTML($html,true,false,true,false,'');

  $Imp = impletras($Importe,'pesos');

  $html='<table align="center" width="100%" border="0"><tr><td align="right" width="1050" height="30">'.$Imp.'</td></tr></table>';

  $pdf->writeHTML($html,true,false,true,false,'');


  $html='<table align="center" width="100%" border="0"><tr><td align="center" width="1050" height="30"></td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  $html='<table align="center" width="100%" border="0"><tr><td align="center" width="1050" height="30">____________________________________________</td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  $html='<table align="center" width="100%" border="0"><tr><td align="center" width="1050" height="30">Solicita: '.utf8_encode($He[recibio]).'</td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  ob_end_clean();

$pdf->Output();

?>