<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link        = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1]; //Numero de sucursal
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];       

$Vta       = $_SESSION[cVarVal][0];
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];

if($_REQUEST[Boton] == 'Cotizacion'){
                  
    //$FolCotizacion   = cZeros(IncrementaFolio('cotizacion'),5);
                        
    $Fecha = date("Y-m-d");
    $Hora1 = date("H:i");
    //$Hora2 = strtotime("-60 min",strtotime($Hora1));
    //$hora  = date("H:i",$Hora2);

    $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA = mysql_query("SELECT round(sum(precio*(1-descuento/100)),0) as importe FROM otdnvas WHERE usr='$Gusr' and venta='$Vta'");
    $Otd  = mysql_fetch_array($OtdA);
    
    //Busco si existe en ct, puede ser que se duplique
    $ExiCtA = mysql_query("SELECT id FROM ct WHERE suc='$Gcia' AND fecha='$Fecha' AND importe=$Otd[importe]");
    $ExiCt  = mysql_fetch_array($ExiCtA);
    
    if($ExiCt[id] == ''){
    
    $OtA  = mysql_query("SELECT * FROM otnvas WHERE usr='$Gusr' AND venta='$Vta'");
    $Ot   = mysql_fetch_array($OtA);
    	
    $cSql = "INSERT INTO ct
          (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,
          receta,importe,descuento,medicon,suc,folio,horae)
          VALUES
          ('$Ot[cliente]','$Fecha','$hora','$Ot[medico]','$Ot[fechar]','$Ot[fechae]','$Ot[inst]',
          '$Ot[diagmedico]','$Ot[observaciones]','$Ot[servicio]','$Gusr','$Ot[receta]',$Otd[importe],
          '$Ot[razon]','$Ot[Medicon]','$Gcia','$FolCotizacion','$Ot[horae]')";    

    
    EjecutaSql($cSql, 'ct');

    $busca = mysql_insert_id();

    $lUpA = mysql_query("SELECT otdnvas.estudio as idestudio,otdnvas.precio,otdnvas.descuento
            FROM otdnvas, est
            WHERE otdnvas.usr='$Gusr' AND otdnvas.venta='$Vta' AND otdnvas.estudio=est.id");    #Checo k bno halla estudios capturados

    $lBd = false;

    while ($lUp = mysql_fetch_array($lUpA)) {
        $cSql = "INSERT INTO ctd (id,estudio,precio,descuento)
                 VALUES
                 ($busca,'$lUp[idestudio]','$lUp[precio]','$lUp[descuento]')";
        
        EjecutaSql($cSql, 'cdt');
            
    }
        
    }else{
        
        $busca  = $ExiCt[id];
        
    }
    
    
    //header("Location: impct.php?busca=$Id");
    
}

$CiaA   =   mysql_query("SELECT alias,municipio FROM cia WHERE id='$Gcia'");
$Cia    =   mysql_fetch_array($CiaA);


$CliA   =   mysql_query("select * from cli where cliente='$Ot[cliente]'");
$Cli    =   mysql_fetch_array($CliA);

$MedA   =   mysql_query("SELECT nombrec FROM med WHERE id='$Ot[medico]'");
$Med    =   mysql_fetch_array($MedA);

$Fecha  = date("Y-m-d");

$Hora   = date("H:i");
//     $Hora1 = date("H:i");
//     $Hora2 = strtotime("-60 min",strtotime($Hora1));
//    $Hora  = date("H:i",$Hora2);

//$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$nMes       = substr($Fecha,5,2)*1; 

$FechaLet  = " &nbsp a ".substr($Fecha,8,2)." de ".$aMes[$Mes]." del ".substr($Fecha,0,4);

$Hora2	   = substr($Ot[hora],0,5); 
  
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
//require_once("importeletras.php");


//$htmlcontent = getHeadNews($db);

$doc_title    = "Formato";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";


//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

// **** Formato del tipo de hoja a imprimir
define ("PDF_PAGE_FORMAT", "A4");


define ("PDF_MARGIN_TOP", 5);      //Donde empieza el texto dentro del cuadro

define ("PDF_MARGIN_BOTTOM", 0);


//Paramentro como LogotipoImg,,Nombre de la Empresa,Sub titulo
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetHeaderData('logoDuran245.png','','','');
                    

// Tamaño de la letra pero de header(titulos);
define ("PDF_FONT_SIZE_MAIN", 8);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Impresion de formatos");

//Tamaño de la letra del body('time','BI',8) BI la hace renegrida;
$pdf->SetFont('helvetica', '', 8);

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

$pdf->AddPage();

// set barcode
$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
//$pdf->SetBarcode("Fabrica de Texcoco");

$aMes    = array("-","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$cFecha  = $Cia[ciudad]." a ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);

$pdf->writeHTML('<table border="0"><tr><td align="right"><h1>Laboratorio Clinico Duran &nbsp;</h1></td></tr>
                 <tr><td> &nbsp; </td></tr>
                 <tr><td align="right">Sucursal:'.$Gcia.' &nbsp;'.$Cia[alias].' &nbsp;  </td></tr>
                 <tr><td> &nbsp; </td></tr>
                 <tr><td align="right">'.$Cia[municipio].' '.$cFecha.' &nbsp;  </td></tr>
                 <tr><td> &nbsp; </td></tr>
                 <tr><td align="right"><h2>Cotizacion No: '.$busca.'&nbsp;</h2></td></tr>
                 </table>', true, 0, true, 0);            


$Head = '<br><br><table border="1" align="center">
      <tr bgcolor="#e1e1e1">
      <td align="center" width="80"  height="40">Estudio</td>
      <td align="center" width="450">Descripcion</td>
      <td align="center" width="450">Procedimiento</td>
      <td align="center" width="110">Precio</td></tr>';

$CtdA  =   mysql_query("SELECT ctd.estudio,est.descripcion,ctd.precio,ctd.descuento,est.condiciones,est.estudio as clvestudio 
           FROM ctd,est 
           WHERE ctd.estudio=est.id and ctd.id='$busca'");

$Datos = '<br><br><br><br><table align="center">';

while ($Ctd = mysql_fetch_array($CtdA)) {
     $Datos = $Datos . '<tr bgcolor="#c1c1c1"><td width="950" height="30"> &nbsp; <b>Estudio: </b>'.$Ctd[clvestudio].' &nbsp; ('.$Ctd[estudio].')</td><td width="110">&nbsp;</td></tr>'.                 
              '<tr><td width="950" height="30"><b>Descripcion: </b>'.$Ctd[descripcion].'</td><td width="110" align="right"> $ '.number_format($Ctd[precio]*(1-$Ctd[descuento]/100),"2").'</td></tr>'.                    
              '<tr><td width="950" height="30"><b>Condiciones: </b>'.$Ctd[condiciones].'</td><td width="110">&nbsp;</td></tr>'.                    
              '<tr><td width="950" height="40"> &nbsp; </td><td width="110"> &nbsp; </td></tr>';                    
     $nImporte += $Ctd[precio]*(1-$Ctd[descuento]/100);         
}

 $Datos = $Datos . '<tr><td width="950" height="30" align="right"> &nbsp; <b>Total ---> &nbsp; </b></td><td width="110" align="right"> $ '.number_format($nImporte,"2").'</td></tr>';                 

$pdf->writeHTML($Datos.'</table>', true, 0, true, 0);

/*
 *                       
     $Datos = $Datos . '<tr><td align="left" width="80" height="60"> &nbsp; '.$Ctd[estudio].'</td>'.
              '<td align="left" width="450">'.$Ctd[descripcion].'</td>'.
              '<td width="450">'.$Ctd[condiciones].'</td>'.
              '<td width="110" valign="bottom">'.number_format($Ctd[precio],"2").'</td></tr>';                    

 */

if($Enviar == 'Si'){
    //Close and output PDF document
    $FileOut = $Gusr . '.pdf';
    $pdf->Output($FileOut,'F');   //Con esto lo guarado con el nombre prueba en la raiz
}else{    
    $pdf->Output();
}
//============================================================+
// END OF FILE                                                 
//============================================================+

mysql_close();
?>
