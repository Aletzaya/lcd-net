<?php

#Librerias
session_start();

require("lib/lib.php");

$link=conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

  $FechaI	=	$_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF	=	$_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
	  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
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
      $filtro9 = 'Atencion';
  }else{
	  $filtro9   = $_REQUEST[filtro9];       
  }

/*if($filtro<>'*'){
 	$filtro2="and med.clasificacion='$filtro'";
 }else{
	$filtro2=" ";
 }*/

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
 }elseif($filtro3==5){
   $Sucursal='sucursal5';
 }elseif($filtro3==6){
   $Sucursal='sucursal6';
 }

  $Titulo = "Reporte de Productividad del $FechaI al $FechaF";

  $SqlB3 = "SELECT ot.orden
    FROM ot
    where ot.fecha='$FechaI' order by hora limit 1";

  $Sql3 = mysql_query($SqlB3);

  $S3 = mysql_fetch_array($Sql3);


  $SqlB4 = "SELECT ot.orden
    FROM ot
    where ot.fecha='$FechaF' order by orden desc limit 1";
  //echo $SqlB4;
  $Sql4 = mysql_query($SqlB4);

  $S4 = mysql_fetch_array($Sql4);

  $AtencionA = "SELECT otd.usrest as usuario,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
      FROM ot
      INNER JOIN otd on otd.orden=ot.orden
      INNER JOIN est on est.estudio=otd.estudio
      WHERE ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $filtro4 $filtro6 $filtro8
      GROUP BY otd.usrest,date_format(ot.fecha,'%Y-%m') order by otd.usrest,date_format(ot.fecha,'%Y-%m')";

  $CapturaA = "SELECT otd.capturo as usuario,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
      FROM ot
      INNER JOIN otd on otd.orden=ot.orden
      INNER JOIN est on est.estudio=otd.estudio
      WHERE ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $filtro4 $filtro6 $filtro8
      GROUP BY otd.capturo,date_format(ot.fecha,'%Y-%m') order by otd.capturo,date_format(ot.fecha,'%Y-%m')";

  $ProcesoA = "SELECT otd.proceso as usuario,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
      FROM ot
      INNER JOIN otd on otd.orden=ot.orden
      INNER JOIN est on est.estudio=otd.estudio
      WHERE ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $filtro4 $filtro6 $filtro8
      GROUP BY otd.proceso,date_format(ot.fecha,'%Y-%m') order by otd.proceso,date_format(ot.fecha,'%Y-%m')";

  $Atencion  = mysql_query($AtencionA,$link);
  $Captura  = mysql_query($CapturaA,$link);
  $Proceso  = mysql_query($ProcesoA,$link);

  $Mes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
  $tCntot = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

  require ("config.php");
  ?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta charset="UTF-8">
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>Reporte de general </title>
          <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
          <link rel='icon' href='favicon.ico' type='image/x-icon' />
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
          <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
          <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script> 

      </head>
  
      <?php
        echo '<body topmargin="1">';
        encabezados();
        menu($Gmenu, $Gusr);
        ?> 
        <script src="./controladores.js"></script>
        <h2 style="color: #5c6773">Reporte de Productividad General del <?= $FechaI ?> al <?= $FechaF ?></h2>

    <?php


    echo "<table align='center' width='100%' border='0'>";

  echo "<form name='form' method='post' action='repoperativo5.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";

  echo "<td align='center'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

  echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td>";
  
  echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
  echo "<select size='1' name='filtro3' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $SucA=mysql_query("SELECT id,alias FROM cia order by id");
  while($Suc=mysql_fetch_array($SucA)){
    echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
    if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
  }
  echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";     
  echo "</select>";
  echo"</b></td>";

  echo "<td align='left'>$Gfont<b><font size='1'>Inst.</b></font>";
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
  echo"</b></td>";

  echo "<td align='left'>$Gfont<b><font size='1'>Depto</b></font>";
  echo "<select size='1' name='filtro7' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
	$Depto=mysql_query("select departamento,nombre from dep",$link);
	while ($Depto1=mysql_fetch_array($Depto)){
	       echo "<option value='$Depto1[0]'>$Gfont <font size='-2'>$Depto1[0] - $Depto1[nombre]</font></option>";
	       if($Depto1[departamento]==$filtro7){$Desdepto=$Depto1[nombre];} 
	}
  echo "<option selected value='$filtro7'>$Gfont <font size='-1'>$filtro7 $Desdepto</option>";  
  echo "</select>";
  echo "&nbsp; <input type='SUBMIT' value='Ok'>";

  echo"</b></td>";

  echo "</form>";

  echo "</font></td><td>";

    $Sql = str_replace("'", "!", $cOtA);  //Remplazo la comita p'k mande todo el string
	
    echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
    echo "</td></tr></table>";

    $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
    $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
    $Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
    $Tit = "<tr bgcolor='#a2b2de'><td align='center' colspan='2' >$Gfon <b>Usuario</b></td>";
    $Tit2 = "<tr bgcolor='#a2b2de'><td align='center' colspan='2'></td>";

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $Tit = $Tit . "<td align='center' colspan='6'>$Gfon <b>$Mes[$x]</b></td>";
        $Tit2 = $Tit2 . "<td align='center' colspan='2'>$Gfon Atn</b></td><td align='center' colspan='2'>$Gfon Proc</b></td><td align='center' colspan='2'>$Gfon Capt</b></td>";
		$Cmes+=1;
    }

    $Tit = $Tit;
    $Tit2 = $Tit2;

    echo "<table width='100%' align='center' border='0'>";

    echo $Tit.$Tit2;
    $capt = 'XXX';



    $usrB=mysql_query("SELECT * FROM authuser order by uname",$link);
      while($regusr=mysql_fetch_array($usrB)){
          $Usrb=strtoupper($regusr[uname]);       
  // *************** A t e n c i o n **************

          $contador=0;
          $cTit = '';
          $cTit2 = '';
          $cTit3 = '';
          $SubT = 0;
          $SubTot = 0;

   //      $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $taCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntot = array("0","0","0","0","0","0","0","0","0","0","0","0","0");


          while($rega=mysql_fetch_array($Atencion)){

              $usuA=strtoupper($rega[usuario]);

              if($Usrb==$usuA){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $capt=$rega[usuario];

                      $Fec = $rega[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCnt[$Pos] = $rega[2];
                      $aCntot[$Pos] = $aCntot[$Pos]+($rega[precios]-$rega[descuentos]);
                      $contador=1;

                      $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                      $taCnt[$x] = $taCnt[$x] + $aCntot[$x];
                      $taCntt[$x] = $taCntt[$x] + $aCntot[$x];
                      $SubT += $aCnt[$x];
                      $SubTot += $aCntot[$x];
                      $GraT += $aCnt[$x];
                      $GraTot += $aCntot[$x];   

                  }
              }

              if($aCnt[$x]==0){
                $resulaten='';
              }else{
                $resulaten=number_format($aCnt[$x],'0');
              }

          }

              $cTit = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfon <a href=javascript:winuni('repatencion.php?FecI=$FechaI&FecF=$FechaF&usuario=$capt&Sucursal=$filtro3&Institucion=$filtro5&Depto=$filtro7')>$Gfont <b>".$resulaten."</b></a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($taCnt[$x],'2')."</font></td>";


          $aCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $taCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntotp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");


          while($regb=mysql_fetch_array($Proceso)){

              $usuP=strtoupper($regb[usuario]);

              if($Usrb==$usuP){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;
                      $capt1=$regb[usuario];

                      $Fec = $regb[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCntp[$Pos] = $regb[2];
                      $aCntotp[$Pos] = $aCntotp[$Pos]+($regb[precios]-$regb[descuentos]);
                      $contador=1;

                      $tCntp[$x] = $tCntp[$x] + $aCntp[$x];
                      $taCntp[$x] = $taCntp[$x] + $aCntotp[$x];
                      $taCnttp[$x] = $taCnttp[$x] + $aCntotp[$x];
                      $SubT += $aCntp[$x];
                      $SubTot += $aCntotp[$x];
                      $GraT += $aCntp[$x];
                      $GraTot += $aCntotp[$x];   

                  }
              }

              if($aCntp[$x]==0){
                $resulproceso='';
              }else{
                $resulproceso=number_format($aCntp[$x],'0');
              }

          }

              $cTit3 = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfon <a href=javascript:winuni('repoperativod4.php?FecI=$FechaI&FecF=$FechaF&usuario=$capt1&Sucursal=$filtro3&Institucion=$filtro5&Depto=$filtro7')>$Gfont <b>".$resulproceso."</b></a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($taCntp[$x],'2')."</font></td>";
  // *************** C a p t u r a  **************

          $cTit2 = '';
        


              $aCntc = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
              $taCntc = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
              $aCntotc = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

          while($regc=mysql_fetch_array($Captura)){

              $usuC=strtoupper($regc[usuario]);

              if($Usrb==$usuC){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $capt2=$regc[usuario];

                      $Fec = $regc[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCntc[$Pos] = $regc[2];
                      $aCntotc[$Pos] = $aCntotc[$Pos]+($regc[precios]-$regc[descuentos]);
                      $contador=1;

                      $tCntc[$x] = $tCntc[$x] + $aCntc[$x];
                      $taCntc[$x] = $taCntc[$x] + $aCntotc[$x];
                      $taCntct[$x] = $taCntct[$x] + $aCntotc[$x];
                      $SubT += $aCntc[$x];
                      $SubTot += $aCntotc[$x];
                      $GraT += $aCntc[$x];
                      $GraTot += $aCntotc[$x];   
                  }

              }

              if($aCntc[$x]==0){
                $resulcaptura='';
              }else{
                $resulcaptura=number_format($aCntc[$x],'0');
              }

          }

              $cTit2 = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfon <a href=javascript:winuni('repcaptura.php?FecI=$FechaI&FecF=$FechaF&usuario=$capt2&Sucursal=$filtro3&Institucion=$filtro5&Depto=$filtro7')>$Gfont <b>".$resulcaptura."</a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntotc[$x],'2')."</font></td>";

              if($contador==1){

                if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

                if($Usrb==''){
                  $Usrb='SIN_REGISTRO';
                }else{
                  $Usrb=$Usrb;
                }
        
                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
                <td align='center' width='100'><a href=javascript:winuni('repprodgral.php?FecI=$FechaI&FecF=$FechaF&usuario=$Usrb&Sucursal=$filtro3&Institucion=$filtro5&Depto=$filtro7')>$Gfont <b>$Usrb</a></b></td>
                <td align='center' width='100'><a href=javascript:winuni('grafprodu.php?FecI=$FechaI&FecF=$FechaF&usuario=$Usrb&Sucursal=$filtro3&Institucion=$filtro5&Depto=$filtro7')><i class='fa fa-pie-chart fa-1x' aria-hidden='true'></i></a></td>
                </td>";
                echo $cTit . $cTit3 . $cTit2;

                $nRng++;

              }

          $aCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

          mysql_data_seek($Atencion, 1);
          mysql_data_seek($Captura, 1);
          mysql_data_seek($Proceso, 1);
          //mysql_data_seek($Impresion, 1);

      }

    $cTit = '';
    $cTit3 = '';
    $cTit2 = '';
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
    
        $cTit = $cTit . "<td align='center'>$Gfon2 <b>".number_format($tCnt[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCntt[$x],'2')."</b></font></td>";

        $cTit3 = $cTit3 . "<td align='center'>$Gfon2 <b>".number_format($tCntp[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCnttp[$x],'2')."</b></font></td>";

        $cTit2 = $cTit2 . "<td align='center'>$Gfon2 <b>".number_format($tCntc[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCntct[$x],'2')."</b></font></td>";
    }

    $PromedioG= $GraT/$Cmes;
    $VentaT+=$Venta;

    if($capt==''){
      $capt='SIN_REGISTRO';
    }else{
      $capt=$capt;
    }
    
    echo "<tr bgcolor='#a2b2de' aling='center' ><td colspan='2'>$Gfon2 <b> &nbsp; Totales: Personal --> $nRng</b></td>";
    echo $cTit.$cTit3.$cTit2. "</tr>";

    echo "</table>";
    echo "<br>";
    echo "<br>";

    mysql_close();
?>

</body>

</html>