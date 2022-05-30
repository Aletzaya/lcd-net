<?php

  session_start();
#Saco los valores de las sessiones los cuales no cambian;

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
 	$Suc2="and cja.suc='$filtro3'";
 }else{
	$filtro4=" ";
	$Suc2=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and inst.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and inst.status='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and inst.condiciones='$filtro9'";
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
 }elseif($filtro3==5){
	$Sucursal='sucursal5';
}elseif($filtro3==6){
	$Sucursal='sucursal6';
}




   $Titulo = "Demanda de afluencia de pacientes por institucion del $FechaI al $FechaF";

   
    $cOtA="SELECT inst.institucion,inst.nombre,date_format(ot.fecha,'%Y-%m') as fecha,count(*),inst.condiciones,inst.promotorasig,inst.status,inst.condiciones,ot.suc,sum(ot.importe) as importe
	FROM ot
	INNER JOIN inst on inst.institucion=ot.institucion
	WHERE ot.institucion=inst.institucion $filtro4 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF' 
	GROUP BY ot.institucion,date_format(ot.fecha,'%Y-%m') order by ot.institucion, date_format(ot.fecha,'%Y-%m')";

	$OtA  = mysql_query($cOtA,$link);
/*
	$vta= "SELECT cja.fecha,sum(cja.importe),cja.orden,ot.orden,ot.institucion 
	from cja
	INNER JOIN ot on cja.orden=ot.orden
	where cja.orden=ot.orden $Suc2 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF' 
	GROUP BY ot.institucion,date_format(ot.fecha,'%Y-%m') order by ot.institucion, date_format(ot.fecha,'%Y-%m')";

	$vtaA  = mysql_query($vta,$link);

	$vtas = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

    while ($vta2 = mysql_fetch_array($vtaA)) {
	        $vtas[$x]= $vta2[1];
			$x+=1;
    }
*/

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

	  <title>Demanda Paciente Institucion</title>
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
<?php
 
 echo "<table align='center' width='100%' border='0'>";

 echo "<form name='form' method='post' action='repdemandainst.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
 
 echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
	 
 echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
 

 echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
 echo "<select size='1' name='filtro3' class='Estilo10'>";
 echo "<option value='*'>Todos*</option>";
 $SucA=mysql_query("SELECT id,alias FROM cia order by id");
 while($Suc=mysql_fetch_array($SucA)){
	 echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
	 if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
 }
 echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
 echo "</select>";
 echo"</b></td>";
 
 echo "<td align='left'>$Gfont<b><font size='1'>Institucion</b></font>";
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
 
 echo "<td align='left'>$Gfont<b><font size='1'>Condiciones</b></font>";
 echo "<select size='1' name='filtro9' class='Estilo10'>";
 echo "<option value='*'>Todos*</option>";
 echo "<option value='CONTADO'>Contado</option>";
 echo "<option value='CREDITO'>Credito</option>";
 echo "<option selected value='*'>$Gfont <font size='-1'>$filtro9</option></p>";		  
 echo "</select>";
 echo"</b></td>";
 
 echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
 echo "<select size='1' name='filtro7' class='Estilo10'>";
 echo "<option value='*'>Todos*</option>";
 echo "<option value='ACTIVO'>Activo</option>";
 echo "<option value='INACTIVO'>Inactivo</option>";
 echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7</option></p>";		  
 echo "</select>";
 echo "&nbsp; <input type='SUBMIT' value='Ok'>";

 echo"</b></font></td>";
 echo "</form>";
 echo "<td>";

 $Sql = str_replace("'", "!", $cOtA);  //Remplazo la comita p'k mande todo el string
 
 echo "</table>";

 $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
 $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
 $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
 $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
 $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
 $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon Inst </td><td align='center'>$Gfon Nombre</td>
 <td align='center'>$Gfon Condiciones</td><td align='center'>$Gfon Promotor</td>";



 for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
	 if (substr($i, 4, 2) == '13') {
		 $i = $i + 88;
	 }
	 $x = substr($i, 4, 2) * 1;
	 $Tit = $Tit . "<td align='center' colspan='2'>$Gfon $Mes[$x]</td>";
	 $Cmes+=1;
 }

 $Tit = $Tit . "<td align='center' colspan='2'>$Gfon Total</td><td align='center' colspan='2'>$Gfon Prom</td><td align='center'>$Gfon Ingreso</td><td align='center'>$Gfon Dif</td><td align='center'>$Gfon Vst</td></tr>";

 echo "<table width='100%' align='center' border='0'>";

 echo $Tit;
 $Inst = 'XXX';
 while ($reg = mysql_fetch_array($OtA)) {
	 if ($reg[institucion] <> $Inst) {

		 if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
		 if ($Inst <> 'XXX') {
			 $cTit = '';
			 $SubT = 0;
			 $SubTot = 0;				
			 for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
				 if (substr($i, 4, 2) == '13') {
					 $i = $i + 88;
				 }
/*
				 $cOtC= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
				 WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m')='$i'";

				 $OtC  = mysql_fetch_array(mysql_query($cOtC,$link));
				 
				 if($OtC[1]==0){
					 $vist=$Fdo;
				 }else{
					 $vist='#debcff';
				 }
				 */
				 $x = substr($i, 4, 2) * 1;
//                    $cTit = $cTit . "<td align='center'>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
				 $cTit = $cTit . "<td align='right' bgcolor='$vist' 
				 onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
				 onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntot[$x],'0')."</font></td><td align='right' bgcolor='$vist' 
				 onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
				 onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCnt[$x],'2')."</font></td>";
				 $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
				 $tCntot[$x] = $tCntot[$x] + $aCntot[$x];
				 $SubT += $aCnt[$x];
				 $SubTot += $aCntot[$x];
				 $GraT += $aCnt[$x];
				 $GraTot += $aCntot[$x];
				 $vtasTot += $vtas[$x];
			 }
			 
			 $Promedio= $SubT/$Cmes;
			 $Promedioot= $SubTot/$Cmes;
/*
			 $cOtB= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
			 WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m') Between '$Ini' And '$Fin'";

			 $OtB  = mysql_fetch_array(mysql_query($cOtB,$link));
//******************************
			 $vta= "select cja.fecha,sum(cja.importe),cja.orden,ot.orden,ot.institucion from cja,ot where cja.orden=ot.orden and ot.institucion='$Inst' and ot.fecha Between '$FechaI' And '$FechaF' $Suc2";

			 $vtaA  = mysql_fetch_array(mysql_query($vta,$link));
*/

//				$Venta=$vtaA[1];
//				$Venta=$reg[impcaja];


			 echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
			 <td align='center'><a href=javascript:winuni('comisionesinst.php?FecI=$FechaI&FecF=$FechaF&Institucion=$Inst&sucursal=$filtro3')>$Gfont $Inst</a>
			 </td><td width='22%'>$Gfont $Nombre </font></td><td align='center' width='15%'>$Gfont $Esp</font></td><td>$Gfont $Promotor</font></td>";
			 echo $cTit . "
			 <td align='right'>$Gfont ".number_format($SubTot,'0')."</td>
			 <td align='right'>$Gfont ".number_format($SubT,'2')."</td>
			 <td align='right'>$Gfont ".number_format($Promedioot,'0')." </td>
			 <td align='right'>$Gfont ".number_format($Promedio,'2')." </td>
			 <td align='right'>$Gfont ".number_format($vtasTot,'2')."</font></td>
			 <td align='right'>$Gfont ".number_format($SubT-$Venta,'2')."</font></td>
			 <td align='center'><a href=javascript:winuni('visitasmed.php?FechaI=$Ini&FechaF=$Fin&Med=$Med')>$Gfon $OtB[1]</a></font></td></tr>";

			 if($Promotor=='Promotor_A'){

				 $PromotorA1 += $SubTot;
				 $PromotorA2 += $SubTot;

			 }elseif($Promotor=='Promotor_B'){
				 
				 $PromotorB1 += $SubTot;
				 $PromotorB2 += $SubTot;

			 }elseif($Promotor=='Promotor_C'){
				 
				 $PromotorC1 += $SubTot;
				 $PromotorC2 += $SubTot;
				 
			 }elseif($Promotor=='Promotor_D'){
				 
				 $PromotorD1 += $SubTot;
				 $PromotorD2 += $SubTot;

			 }

			 $VentaT+=$Venta;				
			 $Venta = 0;
			 $Tvisit+=$OtB[1];
		 }

		 $Inst = $reg[institucion];
		 $Esp = $reg[condiciones]; 
		 $Nombre = $reg[nombre];
		 $Status = $reg[status];
		 $Promotor = $reg[promotorasig];
		 $clasificacion = $reg[clasificacion];
		 $ZnaA2=mysql_fetch_array(mysql_query("SELECT zona,descripcion FROM zns where zns.zona=$reg[zona]"));
		 $zona = $ZnaA2[descripcion];
		 $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
		 $aCntot = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
		 $nRng++;
	 
	 }
	 $Fec = $reg[fecha];
	 $Pos = 0 + substr($Fec, 5, 2);
	 $aCnt[$Pos] = $reg[importe];
	 $aCntot[$Pos] = $reg[3];
	 $Venta+=$reg[importe];
 }
 $cTit = '';
 $SubT = 0;
 $SubTot = 0;
 for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
	 if (substr($i, 4, 2) == '13') {
		 $i = $i + 88;
	 }
/*
	 $cOtC= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
	 WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m')='$i'";

	 $OtC  = mysql_fetch_array(mysql_query($cOtC,$link));
	 
	 if($OtC[1]==0){
		 $vist=$Fdo;
	 }else{
		 $vist='#debcff';
	 }
	 */
	 $x = substr($i, 4, 2) * 1;
//                    $cTit = $cTit . "<td align='center'>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
	 $cTit = $cTit . "<td align='right' bgcolor='$vist' 
	 onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
				 onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntot[$x],'0')."</font></td><td align='right' bgcolor='$vist' 
	 onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
				 onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCnt[$x],'2')."</font></td>";
	 $SubT+=$aCnt[$x];
	 $GraT+=$aCnt[$x];
	 $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
	 $SubTot+=$aCntot[$x];
	 $GraTot+=$aCntot[$x];
	 $tCntot[$x] = $tCntot[$x] + $aCntot[$x];
 }
 $Promedio= $SubT/$Cmes;
 $Promedioot= $SubTot/$Cmes;
/*

 $cOtB= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
 WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m') Between '$Ini' And '$Fin'";

 if( ($nRng % 2) > 2 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
 $OtB  = mysql_fetch_array(mysql_query($cOtB,$link));
 */
 echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
 <td align='center'><a href=javascript:winuni('comisionesinst.php?FecI=$FechaI&FecF=$FechaF&Institucion=$Inst&$Sucursal=$filtro3')>$Gfont $Inst</a></td>
 <td>$Gfont $Nombre</font></td><td align='center'>$Gfont $Esp</font></td><td>$Gfont $Promotor</font></td>";
 echo $cTit . "<td align='right'>$Gfont ".number_format($SubTot,'0')."</td><td align='right'>$Gfont ".number_format($SubT,'2')."</td><td align='right'>$Gfont ".number_format($Promedioot,'0')." </td><td align='right'>$Gfont ".number_format($Promedio,'2')." </td>
 <td align='right'>$Gfont ".number_format($Venta,'2')."</font></td><td align='right'>$Gfont ".number_format($SubT-$Venta,'2')."</font></td>
 <td align='center'><a href=javascript:winuni('visitasmed.php?FechaI=$Ini&FechaF=$Fin&Med=$Med')>$Gfon $OtB[1]</a></font></td></tr>";
 $Tvisit+=$OtB[1];
 $cTit = '';
 for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
	 if (substr($i, 4, 2) == '13') {
		 $i = $i + 88;
	 }
	 $x = substr($i, 4, 2) * 1;
	 
	 $cTit = $cTit . "<td align='right'>$Gfon2 <b>".number_format($tCntot[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($tCnt[$x],'2')."</b></font></td>";
 }
 $PromedioG= $GraT/$Cmes;
 $PromedioGot= $GraTot/$Cmes;
 $VentaT+=$Venta;
 echo "<tr bgcolor='#a2b2de' aling='center'><td>$Gfon2 <b> &nbsp; Totales: </b></td><td><b>$Gfon2 &nbsp; Instituciones --> $nRng</b></td>
 <td>&nbsp;</td><td align='right'>$Gfon2 &nbsp;</font></td>";
 echo $cTit. "<td align='right'>$Gfon2 <b>".number_format($GraTot,'0')."</b></td><td align='right'>$Gfon2 <b>".number_format($GraT,'2')."</b></td>
 <td align='right'>$Gfon2 <b>".number_format($PromedioGot/$GraTot,'0')."</b>
 </td><td align='right'>$Gfon2 <b>".number_format($PromedioG/$GraT,'2')."</b>
 </td><td align='right'>$Gfon2 <b>".number_format($VentaT,'2')."</b></td><td align='right'>$Gfon2 <b>".number_format($GraT-$VentaT,'2')."</b></td>
 <td align='center'>$Gfon2 <b>".number_format($Tvisit,'0')."</b></td></tr>";

 echo "<tr aling='center'><td></td><td></td>
 <td>&nbsp;</td><td align='right'>$Gfon2 &nbsp;</font></td><td align='right'></td><td align='right'></td><td align='right'></td><td align='right'></td><td align='right'></td><td align='right'></td><td align='center'></td></tr>";

 echo "<tr aling='center'><td></td><td></td>
 <td>&nbsp;</td><td align='center' bgcolor='#a2b2de'>$Gfon2 <b>Promotor A</b></font></td>";

 echo $cTit. "<td align='right' bgcolor='#a2b2de'>$Gfon2 <b>".number_format($GraTot,'0')."</b></td><td align='right' bgcolor='#a2b2de'>$Gfon2 <b>".number_format($GraT,'2')."</b></td><td align='right'></td><td align='right'></td><td align='right'></td><td align='right'></td><td align='center'></td></tr>";

 echo "</table>";
 mysql_close();



echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";


echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('repdemandainstpdf.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";


?>

</html>
<?php
mysql_close();
?>

