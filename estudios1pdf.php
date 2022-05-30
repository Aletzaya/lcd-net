<?php
#Librerias
session_start();



require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST[busca])) {

    if ($_REQUEST[busca] == ini) {

        $Pos = strrpos($_REQUEST[Ret], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST[Ret] . '&';
        } else {
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST[Ret] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'est.estudio', 'Asc', $Retornar, '* Todos','* Todos','* Todos','* Todos');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST[pagina])) {
    $_SESSION['OnToy'][1] = $_REQUEST[pagina];
}
if (isset($_REQUEST[orden])) {
    $_SESSION['OnToy'][2] = $_REQUEST[orden];
}
if (isset($_REQUEST[Sort])) {
    $_SESSION['OnToy'][3] = $_REQUEST[Sort];
}
if (isset($_REQUEST[Ret])) {
    $_SESSION['OnToy'][4] = $_REQUEST[Ret];
}

 if (!isset($_REQUEST[filtro])){
      $filtro = '*';
  }else{
      $filtro    = $_REQUEST[filtro];       
  }

  if (!isset($_REQUEST[filtro3])){
      $filtro3 = '*';
  }else{
      $filtro3    = $_REQUEST[filtro3];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
      $filtro9    = $_REQUEST[filtro9];       
  }

 if($filtro<>'*'){
    $filtro2="and est.depto='$filtro'";
 }else{
    $filtro2=" ";
 }

 if($filtro3<>'*'){
    $filtro4="and est.subdepto='$filtro3'";
 }else{
    $filtro4=" ";
 }

 if($filtro9<>'*'){
    $filtro10="and est.activo='$filtro9'";
 }else{
    $filtro10=" ";
 }


 $doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	//require_once('tcpdf2/tcpdf_include.php');


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$Cat='Estudios';

$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$Titulo = "Catalogo de Estudios";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 10;             //Numero de query dentro de la base de datos



#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 10) {
    $Dsp = 'Filtro activo';
}

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" est.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and est.descripcion like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
    $BusInt=" est.estudio like '%$busca%' or est.descripcion like '%$busca%'";  
    //$OrdenDef = 'med.medico';

  //  $_SESSION['OnToy'][2] = 'est.estudio';
// $Suc='*';
}

#Armo el query segun los campos tomados de qrys;

$listap=mysql_query("SELECT lista FROM inst where institucion=1");

$lista=mysql_fetch_array($listap);

$listas=$lista[lista];

$listaprec='lt'.$listas;

if( $busca == ''){

    $sql = "SELECT $Qry[campos], estudio, subdepto, dep.departamento,dep.nombre as nombredepto,$listaprec as ltp FROM est,dep where est.estudio<>'' and est.depto=dep.departamento $filtro2 $filtro4 $filtro8 $filtro10 order by estudio";   

}else{

    $sql = "SELECT $Qry[campos], estudio,subdepto, dep.departamento,dep.nombre as nombredepto,$listaprec as ltp FROM est,dep WHERE est.estudio<>'' and est.depto=dep.departamento $filtro2 $filtro4 $filtro8 $filtro10 order by estudio";

}



$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "-", "-", " ", "-", "-");      //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];


    

require ("config.php");          //Parametros de colores;

    
  // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $FechaI,$FechaF,$Titulo,$Sucursal2;

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

    $this->writeHTML('<table border="0" width="700"><tr><td width="30"></td><td width="800">Catalogo de Estudios</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 8);

    $this->writeHTML('<br><br><table align="center"  border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td align="center" bgcolor="#808B96" width="90">Clave</td>
    <td align="center" bgcolor="#808B96" width="260">Descripcion</td>
    <td align="center" bgcolor="#808B96" width="170">Departamento</td>
    <td align="center" bgcolor="#808B96" width="140">Subdepartamento</td>
    <td align="center" bgcolor="#808B96" width="70">status</td>
    <td align="center" bgcolor="#808B96" width="90">Prec.Lcd</td>
    <td align="center" bgcolor="#808B96" width="50">Bas</td>
    <td align="center" bgcolor="#808B96" width="50">Eqp</td>
    <td align="center" bgcolor="#808B96" width="50">Muest</td>
    <td align="center" bgcolor="#808B96" width="50">Cont</td>
    <td align="center" bgcolor="#808B96" width="50">Descr</td>
    <td align="center" bgcolor="#808B96" width="50">Admin</td>
    <td align="center" bgcolor="#808B96" width="50">Atn</td></tr></table><br>', false, 0);



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
define ("PDF_MARGIN_BOTTOM", 12);
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


$res = mysql_query($sql);

$Pos = strrpos($_SERVER[PHP_SELF], ".");
$cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
$uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #
$nRng=1;

while ($rg = mysql_fetch_array($res)) {
    if( ($nRngg % 2) > 0 ){
        $Fdo='#FFFFFF';
      }
        else{
            $Fdo='#CDCDFA';
      }    //El resto de la division;

    $pdf->SetFont('Helvetica', '', 7, '', 'false');

    $html='<table align="center" border="0" cellspacing="1" cellpadding="0">
    <tr bgcolor='.$Fdo.'>
    <td align="left" width="90" >'.$rg[estudio].'</td>
    <td align="left" width="260">'.$rg[descripcion].'</td>
    <td align="left" width="170">'.$rg[nombredepto].'</td>
    <td align="left" width="140">'.$rg[subdepto].'</td>';

    if($rg[activo]=='Si'){
        $Status='Activo';
    }elseif($rg[activo]=='No'){
        $Status='Inactivo';
    }else{
        $Status='-';
    }

    $html.= '<td align="center" width="70">'.$Status.'</td>
    <td align="right" width="90">$ '.number_format($rg[ltp],"2").'</td>';


    if ($rg[bloqbas] == 'Si') {
        $imagen1="OKShield.png";

    } else {
        $imagen1="ErrorCircle.png";

    }

    if ($rg[bloqeqp] == 'Si') {
                $imagen2="OKShield.png";
    } else {
                $imagen2="ErrorCircle.png";

    }


    if ($rg[bloqmue] == 'Si') {
        $imagen3="OKShield.png";
    } else {
        $imagen3="ErrorCircle.png";
    }

    if ($rg[bloqcon] == 'Si') {
        $imagen4="OKShield.png";
    } else {
        $imagen4="ErrorCircle.png";
    }

    if ($rg[bloqdes] == 'Si') {
        $imagen5="OKShield.png";
    } else {
        $imagen5="ErrorCircle.png";
    }

    if ($rg[bloqadm] == 'Si') {
        $imagen6="OKShield.png";
    } else {
        $imagen6="ErrorCircle.png";
    }
    
    if ($rg[bloqatn] == 'Si') {
        $imagen7="OKShield.png";
    } else {
        $imagen7="ErrorCircle.png";
    }






    $html.= '<td align="center" width="50"><img src="images/'.$imagen1.'" > </a></td>
             <td align="center" width="50"><img src="images/'.$imagen2.'" > </a></td>
             <td align="center" width="50"><img src="images/'.$imagen3.'" > </a></td>
             <td align="center" width="50"><img src="images/'.$imagen4.'" > </a></td>
             <td align="center" width="50"><img src="images/'.$imagen5.'" > </a></td>
             <td align="center" width="50"><img src="images/'.$imagen6.'" > </a></td>
             <td align="center" width="50"><img src="images/'.$imagen7.'" > </a></td></tr></table>';

    $pdf->writeHTML($html,false,false,true,false,'');
    $nRngg++;

}





ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>