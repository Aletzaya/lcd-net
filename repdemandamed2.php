<?php
session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require ("config.php");	

require("lib/lib.php");

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
      $filtro9 = '*';
  }else{
	  $filtro9    = $_REQUEST[filtro9];       
  }

 if($filtro<>'*'){
 	$filtro2="and med.clasificacion='$filtro'";
 }else{
	$filtro2=" ";
 }

 if($filtro3<>'*'){
 	$filtro4="and ot.suc='$filtro3'";
 }else{
	$filtro4=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and med.promotorasig='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and med.zona='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and med.status='$filtro9'";
 }else{
	$filtro10=" ";
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

  $Titulo = "Demanda de afluencia de pacientes por medico del $FechaI al $FechaF";

	$cOtA="select med.medico,med.nombrec,date_format(ot.fecha,'%Y-%m') as fecha,count(*),med.especialidad,med.promotorasig,
	med.clasificacion,med.zona,med.status,ot.suc,sum(ot.importe) as importe, med.id from ot,med
	WHERE ot.medico=med.medico $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF'
	GROUP BY ot.medico,date_format(ot.fecha,'%Y-%m') order by ot.medico, date_format(ot.fecha,'%Y-%m')";

  $OtA  = mysql_query($cOtA,$link);

  $Mes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

  require ("config.php");

  ?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  
	<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
		<title>Demanda medico</title>
		<link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
  
		<link href="estilos.css" rel="stylesheet" type="text/css"/>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
		<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
		<link rel='icon' href='favicon.ico' type='image/x-icon' />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
  
	  </head>
	<?php
	  echo '<body topmargin="1">';
	  encabezados();
	  menu($Gmenu, $Gusr);
	  ?>
	  <script src="./controladores.js"></script>
        <h2 align='center' style="color: #5c6773"><?= $Titulo ?></h2>

    <?php

//echo "<p align='center'>$Titulo</p>";
  
echo "<br></br>";
   

    echo "<table align='center' width='100%' border='0'>";

	echo "<form name='form' method='post' action='repdemandamed2.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	
	echo "<td>&nbsp; $Gfont <font size='1'><b> DE: $FechaI </b></font><input type='hidden' readonly='readonly' name='FechaI' size='10' value ='$FechaI' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)></td>";
		
	echo "<td>&nbsp; $Gfont <font size='1'><b> A: $FechaF </b></font><input type='hidden' readonly='readonly' name='FechaF' size='10' value ='$FechaF' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td>";
	
	echo "</form>";

	echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
	echo "<form name='form' method='post' action='repdemandamed2.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	$SucA=mysql_query("SELECT id,alias FROM cia order by id");
	while($Suc=mysql_fetch_array($SucA)){
		echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
		if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
	}
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";

	echo "<td align='left'>$Gfont<b><font size='1'>Zona</b></font>";
	echo "<form name='form' method='post' action='repdemandamed2.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro7' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	$ZnaA=mysql_query("SELECT zona,descripcion FROM zns order by zona");
	while($Zna=mysql_fetch_array($ZnaA)){
		echo "<option value=$Zna[zona]> $Zna[zona]&nbsp;$Zna[descripcion]</option>";
		if($Zna[zona]==$filtro7){$DesZna=$Zna[descripcion];}
	}
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7 $DesZna</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";


	
	echo "<td align='left'>$Gfont<b><font size='1'>Promotor</b></font>";
	echo "<form name='form' method='post' action='repdemandamed2.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro5' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	echo "<option value='Promotor_A'>Promotor_A</option>";
	echo "<option value='Promotor_B'>Promotor_B</option>";
	echo "<option value='Promotor_C'>Promotor_C</option>";
	echo "<option value='Promotor_D'>Promotor_D</option>";
	echo "<option value='Promotor_E'>Promotor_E</option>";
	echo "<option value='Promotor_F'>Promotor_F</option>";
	echo "<option value='Base'>Base</option>";
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";


	
	echo "<td align='left'>$Gfont<b><font size='1'>Clasif</b></font>";
	echo "<form name='form' method='post' action='repdemandamed2.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	echo "<option value='A'>A</option>";
	echo "<option value='B'>B</option>";
	echo "<option value='C'>C</option>";
	echo "<option value='D'>D</option>";
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";



	echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
	echo "<form name='form' method='post' action='repdemandamed2.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro9' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	echo "<option value='Activo'>Activo</option>";
	echo "<option value='Inactivo'>Inactivo</option>";
	echo "<option value='Defuncion'>Defuncion</option>";
	echo "<option value='Baja'>Baja</option>";
	echo "<option value='Otro'>Otro</option>";
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro9</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";
    echo "</font></td><td>";




    $Sql = str_replace("'", "!", $cOtA);  //Remplazo la comita p'k mande todo el string
	
    echo "<form name='form2' method='get' action='menu.php'>";
  //echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
  echo "</form>";
  echo "</td></tr></table>";


    $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
    $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

    $Tit = "<tr bgcolor='#a2b2de'>
	<td align='center'>$Gfon M&eacute;dico </td>
	<td align='center' colspan='2'>$Gfon Nombre</td>
	<td align='center'>$Gfon Especialidad</td>
	<td align='center'>$Gfon Promotor</td>";

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $Tit = $Tit . "<td align='center'>$Gfon $Mes[$x]</td>";
		$Cmes+=1;
    }

    $Tit = $Tit . "<td align='center'>$Gfon Total</td><td align='center'>$Gfon Prom</td>
	<td align='center'>$Gfon Venta</td><td align='center'>$Gfon Clsf</td><td align='center'>$Gfon Zona</td>
	<td align='center'>$Gfon Vst</td></tr>";


    echo "<table width='100%' align='center' border='0'>";
    echo $Tit;
    $Med = 'XXX';
    while ($reg = mysql_fetch_array($OtA)) {
        if ($reg[medico] <> $Med) {

			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            if ($Med <> 'XXX') {
                $cTit = '';
                $SubT = 0;
				
                for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                    if (substr($i, 4, 2) == '13') {
                        $i = $i + 88;
					}
					
					$cOtC= "select medico,count(*),date_format(pgs.fecha,'%Y%m') from pgs
					WHERE pgs.medico='$Med' and date_format(pgs.fecha,'%Y%m')='$i'";
					
					$OtC  = mysql_fetch_array(mysql_query($cOtC,$link));
					
					if($OtC[1]==0){
						$vist=$Fdo;
					}else{
						$vist='#debcff';
					}
					
                    $x = substr($i, 4, 2) * 1;
//                    $cTit = $cTit . "<td align='center'>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
                    $cTit = $cTit . "<td align='center' bgcolor='$vist' 
					onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
					onMouseOut=this.style.backgroundColor='$vist';>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
                    $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                    $SubT += $aCnt[$x];
                    $GraT += $aCnt[$x];
                }
				
				$Promedio= $SubT/$Cmes;
				
				$cOtB= "select medico,count(*),date_format(pgs.fecha,'%Y%m') from pgs
				WHERE pgs.medico='$Med' and date_format(pgs.fecha,'%Y%m') Between '$Ini' And '$Fin'";
				
				$OtB  = mysql_fetch_array(mysql_query($cOtB,$link));


				

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
				<td align='center'><a href=javascript:winuni('comisionesmed.php?FecI=$FechaI&FecF=$FechaF&Institucion=*&$Sucursal=1&Promotor=$filtro5&Medico=$Med')>$Gfont $Med</a>
				</td><td><a href=javascript:winuni('medicose1.php?busca=$medid')>$Gfont $Nombre</a></font></td><td aling='rigth' width='5%'>$Gfont <font size='1'>$Status</font></td><td  width='15%'>$Gfont $Esp</font></td><td>$Gfont $Promotor</font></td>";
                echo $cTit . "<td align='center'>$Gfon ".number_format($SubT,'0')."</td><td align='center'>$Gfon ".number_format($Promedio,'2')." </td>
				<td align='right'>$Gfont ".number_format($Venta,'2')."</font><td align='center'>$Gfont $clasificacion</font></td><td align='center'>$Gfont $zona</font></td>
				<td align='center'><a href=javascript:winuni('visitasmed.php?FechaI=$Ini&FechaF=$Fin&Med=$Med')>$Gfon $OtB[1]</a></font></td></tr>";
				$VentaT+=$Venta;				
				$Venta = 0;
				$Tvisit+=$OtB[1];
            }
            $Med = $reg[medico];
            $Esp = $reg[especialidad]; 
            $Nombre = $reg[nombrec];
            $Status = $reg[status];
            $Promotor = $reg[promotorasig];
            $clasificacion = $reg[clasificacion];
		    $ZnaA2=mysql_fetch_array(mysql_query("SELECT zona,descripcion FROM zns where zns.zona=$reg[zona]"));
            $zona = $ZnaA2[descripcion];
            $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
			$nRng++;
		
        }
        $Fec = $reg[fecha];
        $Pos = 0 + substr($Fec, 5, 2);
        $aCnt[$Pos] = $reg[3];
		$Venta+=$reg[importe];
		$medid=$reg[id];
    }
    $cTit = '';
    $SubT = 0;
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
		$cOtC= "select medico,count(*),date_format(pgs.fecha,'%Y%m') from pgs
		WHERE pgs.medico='$Med' and date_format(pgs.fecha,'%Y%m')='$i'";
		
		$OtC  = mysql_fetch_array(mysql_query($cOtC,$link));
		
		if($OtC[1]==0){
			$vist=$Fdo;
		}else{
			$vist='#debcff';
		}
		
		$x = substr($i, 4, 2) * 1;
//                    $cTit = $cTit . "<td align='center'>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
		$cTit = $cTit . "<td align='center' bgcolor='$vist' 
		onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
					onMouseOut=this.style.backgroundColor='$vist';>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
        $SubT+=$aCnt[$x];
        $GraT+=$aCnt[$x];
		$tCnt[$x] = $tCnt[$x] + $aCnt[$x];
    }
	$Promedio= $SubT/$Cmes;
				
	$cOtB= "select medico,count(*) from pgs
	WHERE pgs.medico='$Med' and pgs.fecha Between '$FechaI' And '$FechaF'";

	if( ($nRng % 2) > 2 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
	$OtB  = mysql_fetch_array(mysql_query($cOtB,$link));
	echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
	<td align='center'><a href=javascript:winuni('comisionesmed.php?FecI=$FechaI&FecF=$FechaF&Institucion=*&$Sucursal=1&Promotor=$filtro5&Medico=$Med')>$Gfont $Med</a></td>
	<td><a href=javascript:winuni('medicose1.php?busca=$medid')>$Gfont $Nombre</a></font></td><td aling='rigth'>$Gfont <font size='1'>$Status</font></td><td>$Gfont $Esp</font></td><td>$Gfont $Promotor</font></td>";
	echo $cTit . "<td align='center'>$Gfon ".number_format($SubT,'0')."</td><td align='center'>$Gfon ".number_format($Promedio,'2')." </td>
	<td align='right'>$Gfont ".number_format($Venta,'2')."</font><td align='center'>$Gfont $clasificacion</font></td>
	<td align='center'>$Gfont $zona</font></td>
	<td align='center'><a href=javascript:winuni('visitasmed.php?FechaI=$Ini&FechaF=$Fin&Med=$Med')>$Gfon $OtB[1]</a></font></td></tr>";
	$Tvisit+=$OtB[1];
    $cTit = '';
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
		
        $cTit = $cTit . "<td align='center'>$Gfon2 <b>".number_format($tCnt[$x],'0')."</b></font></td>";
    }
	$PromedioG= $GraT/$Cmes;
	$VentaT+=$Venta;
    echo "<tr bgcolor='#a2b2de' aling='center'><td>$Gfon2 <b> &nbsp; Totales: </b></td><td colspan='2'><b>$Gfon2 &nbsp; Medicos --> $nRng</b></td>
	<td>&nbsp;</td><td align='right'>$Gfon2 &nbsp;</font></td>";
    echo $cTit. "<td align='center'>$Gfon2 <b>".number_format($GraT,'0')."</b></td>
	<td align='center'>$Gfon2 <b>".number_format($PromedioG/$GraT,'2')."</b>
	</td><td align='right'>$Gfon2 <b>".number_format($VentaT,'2')."</b></td>
	<td align='right'>&nbsp;</b></td>
	<td align='right'>&nbsp;</td>
	<td align='center'>$Gfon2 <b>".number_format($Tvisit,'0')."</b></td></tr>";

    echo "</table>";

	$filtr2="FechaI=$FechaI&FechaF=$FechaF&FechaI2=$FechaI2&FechaF2=$FechaF2&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9";



    mysql_close();


echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";


echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('repdemandamed2pdf.php?Status2=Exportar&$filtr2')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

	?>

	</body>
	
	</html>