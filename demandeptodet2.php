<?php

session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");
  date_default_timezone_set("America/Mexico_City");

$link=conectarse();



$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

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
  ?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  
    <head>
    <meta charset="UTF-8">
      <title>Lista de Trabajo ::..</title>
      <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />

<link href="estilos.css" rel="stylesheet" type="text/css"/>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
<link rel='icon' href='favicon.ico' type='image/x-icon' />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>

</head>

    <body>
    <?php
	  echo '<body topmargin="1">';
	  encabezados();
	  menu($Gmenu, $Gusr);
	  ?>
	  <script src="./controladores.js"></script>
  <?php
  ?>
      <table width="100%" border="0">
  <tr> 
    <td align="center" width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "$Titulo"; ?>&nbsp;</div></td>
  </tr>
</table>
  
    <?php
    echo "<table align='center' width='100%' border='0'>";

  echo "<hr noshade style='color:000099;height:1px'>";
	echo "<form name='form' method='post' action='demandeptodet2.php'>";

 // echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
 echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De:</b></font><input type='text' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

    echo "&nbsp;<b><font size='1'> Hra.I.: </b></font>";
  echo "<input type='text' value='$HoraI' name='HoraI' size='6' >&nbsp;";
		
	echo "$Gfont&nbsp;<font size='1'><b>A: </b></font><input type='text' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";


  echo "&nbsp;&nbsp;<b><font size='1'>Hra.F.: </b></font>";
  echo "<input type='text' value='$HoraF'  name='HoraF' size='6' >&nbsp;&nbsp;";
	

	echo "$Gfont <b><font size='1'>Suc </b></font>";
	echo "<select size='1' name='filtro3' class='Estilo10'>";

	$SucA=mysql_query("SELECT id,alias FROM cia order by id");
  echo "<option value='*'>Todos*</option>";
	while($Suc=mysql_fetch_array($SucA)){
		echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
		if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
    if($filtro3=='*'){$DesSuc=' Todos';}
	}

	
  echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
	echo "</select>";
	echo"</b>";

  echo "$Gfont <b><font size='1'>Inst </b></font>";

  echo "<select size='1' name='filtro5' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $InsA = mysql_query("SELECT institucion,alias FROM inst order by institucion");
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value=$Ins[institucion]>$Ins[institucion]&nbsp;$Ins[alias]</option>";
        if($Ins[institucion]==$filtro5){$DesInst=$Ins[alias];}
        if($filtro5=='*'){$DesInst=' Todos';}
  }   
  echo "<option selected value='$filtro5'>$Gfont <font size='-1'>$filtro5 $DesInst</option>";  
  echo "</select>";
  echo"</b>";

 if($filtro=='S/T'){
    $decripfiltro='** SIN TERMINAR **';
 }else{
    $decripfiltro=$filtro;
 }

    echo "$Gfont <b><font size='1'>Ruta Critica</b></font>";

  echo "<select size='1' name='filtro' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";  
  echo "<option value='DEPTO'>DEPTO</option>";  
  echo "<option value='RESUL'>RESUL</option>";  
  echo "<option value='TOMA/REALIZ'>TOMA/REALIZ</option>";  
  echo "<option value='PENDIENTE'>PENDIENTE</option>";  
  echo "<option value='TERMINADA'>TERMINADA</option>";  
  echo "<option value='S/T'>** SIN TERMINAR **</option>";  
  echo "<option selected value='$filtro'>$Gfont <font size='-1'>$decripfiltro</option>";  
  echo "</select>";
  echo"</b>";

  echo "<input type='hidden' name='filtro7' value='$filtro7'>"; 
  echo "<input type='hidden' name='filtro9' value='$filtro9'>"; 
  echo "<input type='hidden' name='filtroF' value='$filtroF'>";
  echo "<input type='hidden' name='filtroG' value='$filtroG'>"; 
  echo "<input type='hidden' name='contador' value='$contador'>"; 

  echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type='SUBMIT' value='Ok'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
  
  echo "<br>";

  echo "<br>";
//   echo "<form name='form1' method='post' action='demandeptodet2.php?filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";

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


  echo "<br>";

  echo"</b></td>";
  echo "</form>";

  echo"<tr><td colspan='9'>&nbsp</td></tr>";

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

    $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon <b>Suc</b></td><td align='center'>$Gfon <b>Inst - Orden</b></td>
	<td align='center'>$Gfon <b>Fecha/Hora de Captura</b></td><td align='center'>$Gfon <b>Nombre del paciente</b></td><td align='center'>$Gfon <b>Ruta Critica</b></td><td align='center'>$Gfon <b>Fecha/Hora Toma</b></td><td align='center'>$Gfon <b>Usr Toma</b></td><td align='center'>$Gfon <b>Fecha/Hora Capt</b></td><td align='center'>$Gfon <b>Obs</b></td></tr>";

    echo $Tit;

	$canti1=0;

	$importotal1=0;

	$estudio1='';


	$canti2=0;

	$importotal2=0;

	$estudio2='';

  $contestudio2=1;

    //echo "<table width='100%' align='center' border='0'>";
  $subdepto2='';

  	while ($reg1 = mysql_fetch_array($OtA)) {

  			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

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

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
            <td align='center' colspan='9'>$Gfon <b>Total Estudios ----> $contestudio2</b></td></tr>";

            $contestudio2=1;

            echo "<tr><td align='right' colspan='7'></td>
            <td align='left' colspan='2' bgcolor='#f9e79f'>$Gfon <b>Total Estudios del Subdepartamento ----> $contestudio3</b></td></tr>";

            echo "<tr><td align='center' colspan='9'>$Gfon <b><hr></b></td></tr>";

            $contestudio3=0;

          }

          echo "<tr bgcolor='#f9e79f' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#f9e79f';>
          <td align='center' colspan='9'>$Gfon <b>$reg2[nombre] - $reg1[subdepto]</b></td></tr>";

          $subdepto2=$reg1[subdepto];

          $estudio2='';

          $contestudio2=1;

        }else{

          $subdepto2=$reg1[subdepto];

        }

        if($reg1[estudio]<>$estudio2){

          if( $estudio2<>''){
            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
            <td align='center' colspan='9'>$Gfon <b>Total Estudios ----> $contestudio2</b></td></tr>";

            $contestudio3=$contestudio3+$contestudio2;

            $contestudio2=1;


          }

          echo "<tr bgcolor='#bfc9ca' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#bfc9ca';>
          <td align='left' colspan='9'>$Gfon <b>$reg1[estudio] - $reg1[descripcion]</b></td></tr>";

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
            $imagen1="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
        }else{  
            $imagen1="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
        }

        if($reg1[dos]<>'0000-00-00 00:00:00'){
            $imagen2="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
        }else{  
            $imagen2="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
        }

        if($reg1[tres]<>'0000-00-00 00:00:00'){
            $imagen3="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
        }else{  
            $imagen3="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
        }

        if($reg1[cuatro]<>'0000-00-00 00:00:00'){
            $imagen4="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
        }else{  
            $imagen4="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
        }

        if($reg1[cinco]<>'0000-00-00 00:00:00'){
            $imagen5="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
        }else{  
            $imagen5="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
        }



      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
      <td align='center'>$Gfont $reg1[suc]</td><td align='center'>$Gfont $reg1[institucion] - $reg1[orden]</td><td align='center'>$Gfont $reg1[fecha] - $reg1[hora]</td><td>$Gfont $reg1[nombrec] </font></td><td align='center'>$Gfont <font size='1' color=$color><b>Etq-$imagen1 Tom-$imagen2 Cap-$imagen3 Imp-$imagen4 Repc-$imagen5 </b></font></td><td align='center'>$Gfont $reg1[fechaest]</font></td><td align='center'>$Gfont $reg1[usrest]</font></td><td align='center'>$Gfont $reg1[tres] - $reg1[capturo]</font></td>";
       
      
      if($reg1[obsest]<>''){
            echo "<td align='center'><a class='pg' href=javascript:winuni2('obsest.php?Orden=$reg1[orden]&Estudio=$reg1[estudio]')><img src='lib/messageon.png' border='0'></a></td></tr>";      
        }else{
            echo "<td align='center'>&nbsp;</td></tr>";                  
        } 

        echo "<tr><td align='center' colspan='9'>$Gfont <b>$reg1[observaciones]</b></font></td></tr>";

			$nRng++;
		}

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
    <td align='center' colspan='9'>$Gfon <b>Total Estudios ----> $contestudio2</b></td></tr>";

            $contestudio3=$contestudio3+$contestudio2;

    echo "<tr><td align='right' colspan='7'></td>
    <td align='left' colspan='2' bgcolor='#f9e79f'>$Gfon <b>Total Estudios del Subdepartamento ----> $contestudio3</b></td></tr>";

    echo "<tr><td align='center' colspan='9'>$Gfon <b><hr></b></td></tr>";


    echo "</table>";




    echo "<table align='center' width='50%' border='0'>";

    $totalest=$laboratorio+$Administrativo+$rx+$Especiales+$Servicios+$Externos+$Mixto;

    echo "<tr bgcolor='#bfc9ca'><td align='center'>$Gfon <b>Departamento</td><td align='center'>$Gfon <b>Estudios</td></tr>";
    echo "<tr><td align='center'>$Gfon Laboratorio</td><td align='center'>$Gfon $laboratorio</td></tr>";
    echo "<tr bgcolor=$Gfdogrid><td align='center'>$Gfon Rayos x e Imagen</td><td align='center'>$Gfon $rx</td></tr>";
    echo "<tr><td align='center'>$Gfon Especiales</td><td align='center'>$Gfon $Especiales</td></tr>";
    echo "<tr bgcolor=$Gfdogrid><td align='center'>$Gfon Servicios </td><td align='center'>$Gfon $Servicios</td></tr>";
    echo "<tr><td align='center'>$Gfon Externos</td><td align='center'>$Gfon $Externos</td></tr>";
    echo "<tr bgcolor=$Gfdogrid><td align='center'>$Gfon Mixto</td><td align='center'>$Gfon $Mixto</td></tr>";
    echo "<tr><td align='center'>$Gfon Administrativo</td><td align='center'>$Gfon $Administrativo</td></tr>";
    echo "<tr bgcolor='#bfc9ca'><td align='center'>$Gfon <b>Total</td><td align='center'>$Gfon <b>$totalest</td></tr>";

    echo "</table>";

    echo "<div align='center'>";
    echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
    echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
    echo "</div>";

    echo "<div align='center'>";
    echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('demandeptodet2pdf.php?filtro7=$filtro7&filtro9=$filtro9&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&FechaI=$FechaI&FechaF=$FechaF&HoraI=$HoraI&HoraF=$HoraF')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

    mysql_close();
?>

</body>

</html>
<?php
mysql_close();
?>
