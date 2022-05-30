<?php

session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");
  date_default_timezone_set("America/Mexico_City");

$link=conectarse();

  $FechaI	=	$_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF	=	$_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
	  echo '<script language="javascript">alert("Fechas 1 incorrectas... Verifique");</script>';  
  }  


  $HoraI=$_REQUEST[HoraI];

  if (!isset($HoraI)){
      $HoraI = '07:00';
  }

  $HoraF=$_REQUEST[HoraF];

  if (!isset($HoraF)){
      $HoraF = date('20:00');
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

  if (!isset($_REQUEST[filtro5])){
      $filtro5 = '*';
  }else{
	  $filtro5    = $_REQUEST[filtro5];       
  }

  if (!isset($_REQUEST[filtro7])){
      $filtro7 = '*';
  }else{
	  $filtro7    = $_REQUEST[filtro7];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
	  $filtro9    = $_REQUEST[filtro9];       
  }


 if($filtro=='S/T'){
 	$filtro2="and (otd.status<>'TERMINADA')";
 }elseif($filtro<>'*'){
  $filtro2="and otd.status='$filtro'";
 }else{
	$filtro2=" ";
 }

 if($filtro3<>'*'){
 	$filtro4="and ot.suc='$filtro3'";
 }else{
	$filtro4=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and ot.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and est.depto='$filtro7'";
 }else{
	$filtro8=" ";
  $contador=0;
 }

 $submenu=$_REQUEST[submenu];
 $filtroF=$_REQUEST[filtroF];
if($filtroF=='*'){
   $contador=0;
}else{
   $contador=$_REQUEST[contador];$filtroG=$filtroF;
}

$Fecha=date("Y-m-d");

$Hora=date("H:i");
 
 if($filtro9<>'*'){
    if($contador==0){
      $filtroA=" and ";
      $filtroB=" ";
      $filtroC=" ";
      $filtroD=" est.subdepto='$filtro9' ";
      $filtroE=" est.subdepto='$filtro9' ";
      $filtro10=$filtroA.$filtroB.$filtroD.$filtroC;
      $contador=1;
    }elseif($contador==1){
      $filtroA=" or ";
      $filtroB=" and (";
      $filtroC=" ) ";
      $filtroD=" est.subdepto='$filtro9' ";
      
      $filtroE="est.subdepto='$filtroF'".$filtroA.$filtroD;
      $filtro10=$filtroB.$filtroE.$filtroC;
      $contador=2;
    }elseif($contador>=2){
      $filtroA=" or ";
      $filtroB=" and (";
      $filtroC=" ) ";
      $filtroG=$_REQUEST[filtroG];
      $filtroD=" est.subdepto='$filtro9' ";
      $filtroE="est.subdepto='$filtroF'".$filtroA."est.subdepto='$filtroG'".$filtroA.$filtroD;
      $filtro10=$filtroB.$filtroE.$filtroC;
      $contador=3;
    }
 }else{
    $filtroA=" ";
    $filtroB=" ";
    $filtroC=" ";
    $filtroD=" ";
    $filtroE=" ";
    $filtroF=" ";
    $filtroG=" ";
    $filtro10=" ";
    $contador=0;
 }


 if($filtro3=='*'){
	 $Sucursal='sucursalt';
 }elseif($filtro3==0){
 	 $Sucursal='sucursal0';
 }elseif($filtro3==1){
 	 $Sucursal='sucursal1';
 }elseif($filtro3==2){
 	 $Sucursal='sucursal2';
 }elseif($filtro3==3){
 	 $Sucursal='sucursal3';
 }elseif($filtro3==4){
 	 $Sucursal='sucursal4';
 }

   if($filtro7=="*"){
    $marcat='#003399';
    $marca1=' ';
    $marca2=' ';
    $marca3=' ';
    $marca4=' ';
    $marca6=' ';
  }else{
  if($filtro7=="1"){
    $marca1='#003399';
    $marca2=' ';
    $marca3=' ';
    $marca4=' ';
    $marca6=' ';
    }else{
    if($filtro7=="2"){
    $marca1=' ';
    $marca2='#003399';
    $marca3=' ';
    $marca4=' ';
    $marca6=' ';
      }else{
      if($filtro7=="3"){
    $marca1=' ';
    $marca2=' ';
    $marca3='#003399';
    $marca4=' ';
    $marca6=' ';
        }else{
        if($filtro7=="4"){
    $marca1=' ';
    $marca2=' ';
    $marca3=' ';
    $marca4='#003399';
    $marca6=' ';
          }else{
          if($filtro7=="6"){
    $marca1=' ';
    $marca2=' ';
    $marca3=' ';
    $marca4=' ';
    $marca6='#003399';
            }else{
            $marca=' ';
          }
        }
      }
    }
  }
  }

$SubA=mysql_query("SELECT departamento,subdepto FROM depd where departamento=$filtro7",$link);

//  if($submenu<>1){
      $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,est.subdepto,otd.usrest
        FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio and date_format(otd.fechaest,'%Y-%m-%d')>='$FechaI' and date_format(otd.fechaest,'%Y-%m-%d')<='$FechaF' and substr(otd.fechaest, 12,5)>='$HoraI' and substr(otd.fechaest, 12,5)<='$HoraF' and ot.orden=otd.orden  $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 Order BY est.depto, est.subdepto, otd.estudio";

  $OtA  = mysql_query($SqlA,$link);
//  }

  $Titulo = "Demanda de Lista de Trabajo del $FechaI al $FechaF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";

  require ("config.php");


  
 $doc_title    = "Imprimir Lista de Trabajo ";
 $doc_subject  = "recibos unicode";
 $doc_keywords = "keywords para la busqueda en el PDF";
 
 require_once('tcpdf/config/lang/eng.php');
 require_once('tcpdf/tcpdf.php');


 // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2;

    $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
    $NomI    = mysql_fetch_array($InstA);

    $Fecha   = date("Y-m-d");
    $Hora=date("H:i");

    // Logo
    $image_file2 = 'lib/DuranNvoBk.png';
    $this->Image($image_file2, 8, 5, 50, '', 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 11);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr><tr><td width="30"></td><td width="800">Laboratorio Clínico Duran</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Fecha/Hora: '.$Fecha.' - '. $Hora.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.'</td></tr></table>', false, 0);


    $this->SetFont('helvetica', '', 8);

    $this->writeHTML('<br><br><br><table align="center"  border="1" cellspacing="1" cellpadding="0">
<tr bgcolor="#a2b2de">
<td align="center" width="40">Suc</td>
<td align="center" width="90">Inst - Orden</td>
<td align="center" width="150">Fecha/Hora de Captura</td>
<td align="center" width="200">Nombre del paciente</td>
<td align="center" width="300">Ruta Critica</td>
<td align="center" width="150">Fecha/Hora Toma</td>
<td align="center" width="85">Usr Toma</td>
<td align="center" width="145">Fecha/Hora Capt</td>
</tr></table><br><br>', false, 0);




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
define ("PDF_MARGIN_TOP", 38);
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


if ($filtro7 == '*') {
    $colorT = 'bgcolor="#519145"';
} elseif ($filtro7 == '1') {
    $color1 = 'bgcolor="#519145"';
} elseif ($filtro7 == '2') {
    $color2 = 'bgcolor="#519145"';
} elseif ($filtro7 == '3') {
    $color3 = 'bgcolor="#519145"';
} elseif ($filtro7 == '4') {
    $color4 = 'bgcolor="#519145"';
} elseif ($filtro7 == '6') {
    $color6 = 'bgcolor="#519145"';
} elseif ($filtro7 == '9') {
    $color9 = 'bgcolor="#519145"';
} elseif ($filtro7 == '10') {
    $color10 = 'bgcolor="#519145"';
} else {
    $colorT = 'bgcolor="#519145"';
}

?>
<table border="0" width="100%">
    <tr style="height: 24px;" bgcolor="#84B2D1">
        <td class="sbmenu" align="center" style="width: 100px" <?= $colorT; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=*">
                Todos *
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 100px" <?= $color1; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=1&Subdpt=*&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Laboratorio
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 100px" <?= $color2; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=2&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Rayos X y USG
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 100px" <?= $color3; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=3&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Especiales
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 100px" <?= $color4; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=4&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Servicios
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 100px" <?= $color6; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=6&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Externos
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 125px" <?= $color9; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=9&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Laboratorio Biologia Molecular
            </a>
        </td>
        <td class="sbmenu" align="center" style="width: 125px" <?= $color10; ?> >
            <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=10&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                Estudios de Gabinete
            </a>
        </td>
    </tr>
</table>
<?php
if ($filtro8 <> " ") {
    ?>
    <table border="0" style="width: 100%;">
        <tr style="height: 24px;" bgcolor="#7AD169">
            <?php
            $sql = "SELECT departamento,subdepto FROM depd where departamento=$filtro7";
            $SubA = mysql_query($sql);
            if ($Subdpto == $SubD[subdepto]) {
                $colorsub = 'bgcolor="#84B2D1"';
            } else {
                $colorsub = 'bgcolor="#519145"';
            }
            ?>
            <td class="sbmenu" align="center" <?= $colorsub; ?> >
                <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=<?= $filtro7 ?>&filtro9=*">
                    Todos *
                </a>
            </td>
            <?php
            while ($SubD = mysql_fetch_array($SubA)) {

                if ($filtro9 == $SubD[subdepto]) {
                    $colorsub = 'bgcolor="#84B2D1"';
                } else {
                    $colorsub = 'bgcolor="#519145"';
                }
                ?>
                <td class="sbmenu" align="center" <?= $colorsub; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?filtro7=<?= $filtro7 ?>&filtro9=<?= $SubD[subdepto] ?>&filtro=<?= $filtro ?>&filtro3=<?= $filtro3 ?>&filtro5=<?= $filtro5 ?>&FechaI=<?= $FechaI ?>&FechaF=<?= $FechaF ?>&HoraI=<?= $HoraI ?>&HoraF=<?= $HoraF ?>">
                        <?= $SubD[subdepto] ?>
                    </a>
                </td>
                <?php
            }
            ?>
        </tr>
    </table>
    <?php
}


if($filtro9=="*"){
$marcat='#003399';
}else{
$marcat=' ';
}


$pdf->SetFont('Helvetica', '', 6, '', 'false');

$Tit ='<table align="center" border="0">';



$canti1=0;

$importotal1=0;

$estudio1='';


$canti2=0;

$importotal2=0;

$estudio2='';

$contestudio2=1;


$subdepto2='';

  while ($reg1 = mysql_fetch_array($OtA)) {

    if( ($nRng % 2) > 0 ){
        $Fdo='FFFFFF';
      }
        else{
            $Fdo=$Gfdogrid;
      }    //El resto de la division;

      

          $difcant=$canti1-$canti2;
          $difimport=$importotal1-$importotal2;

    $est2="SELECT * FROM dep where departamento=$reg1[depto]";

    $est3  = mysql_query($est2,$link);

    $reg2 = mysql_fetch_array($est3);

    if($reg1[depto]==1){

      $laboratorio=$laboratorio+1;

    }elseif($reg1[depto]==2){

      $rx=$rx+1;

    }elseif($reg1[depto]==3){

      $Especiales =$Especiales +1;

    }elseif($reg1[depto]==4){

      $Servicios  =$Servicios  +1;

    }elseif($reg1[depto]==6){

      $Externos   =$Externos   +1;

    }elseif($reg1[depto]==7){

      $Mixto    =$Mixto    +1;


    }elseif($reg1[depto]==8){

      $Administrativo     =$Administrativo     +1;

    }


    if($reg1[subdepto]<>$subdepto2){

      if( $estudio2<>''){

        $contestudio3=$contestudio3+$contestudio2;


        $Tit.='<tr bgcolor='.$Fdo.'>
        <td align="center" colspan="9">Total Estudios ---->'. $contestudio2.'</td></tr>';
        


        $contestudio2=1;

        $Tit.='<tr>
        <td align="right" colspan="7"></td>
        <td align="left" colspan="2" bgcolor="#f9e79f">Total Estudios del Subdepartamento ----> '.$contestudio3.'</td></tr>';

        $Tit.='<tr>
        <td align="center" colspan="9"></td></tr>';

        $contestudio3=0;

      }


      $Tit.='<tr bgcolor="#f9e79f">
      <td align="center" colspan="9">'.$reg2[nombre].' - '.$reg1[subdepto].'</td></tr>';

      $subdepto2=$reg1[subdepto];

      $estudio2='';

      $contestudio2=1;

    }else{

      $subdepto2=$reg1[subdepto];

    }

    if($reg1[estudio]<>$estudio2){

      if( $estudio2<>''){
          

        $Tit.='<tr bgcolor='.$Fdo.'>
        <td align="center" colspan="9">Total Estudios ---->'.$contestudio2.'</td></tr>';

        $contestudio3=$contestudio3+$contestudio2;

        $contestudio2=1;


      }

      $Tit.='<tr bgcolor="#bfc9ca">
      <td align="left" colspan="9">'.$reg1[estudio].' - '.$reg1[descripcion].'</td></tr>';

      $estudio2=$reg1[estudio];

    }else{

      $estudio2=$reg1[estudio];

      $contestudio2++;

    }

    if($reg1[status]=='TERMINADA'){

      $color='#000000';

    }else{

      $color='#003399';

    }

    $fechaest1=substr($reg1[fechaest], 0,10);

    if($reg1[fecha]<>$fechaest1){
      
        $sta="--- ".$reg1[status]." ---";

        $Fdo='#ffb18c';

    }else{

        $sta=$reg1[status];
    }


    
    if($reg1[etiquetas]>=1){
      $imagen1="OKShield.png";
  }else{	
      $imagen1="ErrorCircle.png";
  }
    

  if($reg1[dos]<>'0000-00-00 00:00:00'){
      $imagen2="OKShield.png";
  }else{	
      $imagen2="ErrorCircle.png";
  }
     

  if($reg1[tres]<>'0000-00-00 00:00:00'){
      $imagen3="OKShield.png";
  }else{	
      $imagen3="ErrorCircle.png";
  }
      

  if($reg1[cuatro]<>'0000-00-00 00:00:00'){
      $imagen4="OKShield.png";
  }else{	
      $imagen4="ErrorCircle.png";
  }
      

  if($reg1[cinco]<>'0000-00-00 00:00:00'){
      $imagen5="OKShield.png";
  }else{	
      $imagen5="ErrorCircle.png";
  }



  

    $Tit.='<tr bgcolor='.$Fdo.'>
    <td align="center" width="40">'.$reg1[suc].'</td>
    <td align="center" width="90">'.$reg1[institucion].' - '.$reg1[orden].'</td>
    <td align="left" width="150">'.$reg1[fecha].' - '.$reg1[hora].'</td>
    <td align="left" width="200">'.$reg1[nombrec].'</td>
    <td align="left" width="60">Etq-<img src="images/'.$imagen1.'" ></img></td>
    <td align="left" width="60">Tom-<img src="images/'.$imagen2.'" ></img></td>
    <td align="left" width="60">Cap-<img src="images/'.$imagen3.'" ></img></td>
    <td align="left" width="60">Imp-<img src="images/'.$imagen4.'" ></img></td>
    <td align="left" width="60">Repc-<img src="images/'.$imagen5.'"></img></td>
    <td align="left" width="150">'.$reg1[fechaest].'</td>
    <td align="center" width="85">'.$reg1[usrest].'</td>
    <td align="center" width="145">'.$reg1[tres].'</td></tr>';

        $nRng++;
    }


 $Tit.='<tr bgcolor='.$Fdo.'>
 <td align="center" colspan="9">Total Estudios ----> '.$contestudio2.'</td></tr>';

        $contestudio3=$contestudio3+$contestudio2;

$Tit.='<tr>
<td align="right" colspan="7"></td>
<td align="left" colspan="2" bgcolor="#f9e79f">Total Estudios del Subdepartamento ----> '.$contestudio3.'</td></tr>';

$Tit.='<tr>
<td align="center" colspan="9"></td></tr></table><br><br><br><br>' ;


$pdf->writeHTML($Tit,false,false,true,false,'C');













$pdf->SetFont('Helvetica', '', 7, '', 'false');

$totalest=$laboratorio+$Administrativo+$rx+$Especiales+$Servicios+$Externos+$Mixto;


$Tit1 ='<table align="center" width="100%" border="0">
<td align="center" width="70"></td>
<td align="center" width="90" ></td>
<td align="center" width="90"></td>
<td align="center" width="90" ></td>



<tr bgcolor="#bfc9ca">
<td align="center" >Departamento</td>
<td align="center" >Estudios</td></tr>

<tr>
<td align="center">Laboratorio</td>
<td align="center">'.$laboratorio.'</td></tr>

<tr bgcolor='.$Gfdogrid.'>
<td align="center">Rayos x e Imagen</td>
<td align="center">'.$rx.'</td></tr>

<tr>
<td align="center">Especiales</td>
<td align="center">'.$Especiales.'</td></tr>

<tr bgcolor='.$Gfdogrid.'>
<td align="center">Servicios </td>
<td align="center">'.$Servicios.'</td></tr>

<tr>
<td align="center">Externos</td>
<td align="center">'.$Externos.'</td></tr>


<tr bgcolor='.$Gfdogrid.'>
<td align="center">Mixto</td>
<td align="center">'.$Mixto.'</td></tr>

<tr>
<td align="cente">Administrativo</td>
<td align="center">'.$Administrativo.'</td></tr>


<tr bgcolor="#bfc9ca">
<td align="center">Total</td>
<td align="center">'.$totalest.'</td></tr></table>';

$pdf->writeHTML($Tit1,false,false,true,false,'');



            
ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>
