<?php

session_start();

require("lib/lib.php");

$link=conectarse();

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
      $filtro7 = '';
  }else{
      $filtro7    = $_REQUEST[filtro7];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
      $filtro9    = $_REQUEST[filtro9];       
  }

  if (!isset($_REQUEST[filtro11])){
      $filtro11 = '*';
  }else{
      $filtro11    = $_REQUEST[filtro11];       
  }

  if (!isset($_REQUEST[filtro13])){
      $filtro13 = '*';
  }else{
      $filtro13    = $_REQUEST[filtro13];       
  }

  if (!isset($_REQUEST[filtro15])){
      $filtro15 = '*';
  }else{
      $filtro15    = $_REQUEST[filtro15];       
  }

  if (!isset($_REQUEST[filtro17])){
      $filtro17 = '*';
  }else{
      $filtro17    = $_REQUEST[filtro17];       
  }

  if (!isset($_REQUEST[filtro19])){
      $filtro19 = '*';
  }else{
      $filtro19    = $_REQUEST[filtro19];       
  }

  if (!isset($_REQUEST[filtro21])){
      $filtro21 = '*';
  }else{
      $filtro21    = $_REQUEST[filtro21];       
  }

  if (!isset($_REQUEST[filtro23])){
      $filtro23 = '*';
  }else{
      $filtro23    = $_REQUEST[filtro23];       
  }

 if($filtro<>'*'){
    $filtro2="and invl.depto='$filtro'";
 }else{
    $filtro2=" ";
 }

 if($filtro3<>'*'){
    $filtro4="and invl.subdepto='$filtro3'";
 }else{
    $filtro4=" ";
 }

 if($filtro5<>'*'){
    $filtro6="and invl.presentacion='$filtro5'";
 }else{
    $filtro6=" ";
 }
 
 if($filtro7<>''){
    $filtro8="and (invl.marca like '%$filtro7%' or invl.descripcion like '%$filtro7%')";
 }else{
    $filtro8="";
 }
 
 if($filtro9<>'*'){
    $filtro10="and invl.status='$filtro9'";
 }else{
    $filtro10=" ";
 }


 if($filtro13<>'*'){
    $filtro14="and invl.ctrl='$filtro13'";
 }else{
    $filtro14=" ";
 }

$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

  if($filtro11=='*'){
   $Sucursal='sucursalt';
 }elseif($filtro11==0){
   $Sucursal='sucursal0';
 }elseif($filtro11==1){
   $Sucursal='sucursal1';
 }elseif($filtro11==2){
   $Sucursal='sucursal2';
 }elseif($filtro11==3){
   $Sucursal='sucursal3';
 }elseif($filtro11==4){
   $Sucursal='sucursal4';
 }elseif($filtro11==5){
   $Sucursal='sucursal5';
 }elseif($filtro11==6){
   $Sucursal='sucursal6';
 }


  $FechaI = $_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI=date("Y-m-")."01";
  }

  $FechaF = $_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
    echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }


$Titulo = "Reporte de inventario de productos";

//$cOtA="select * from invl where $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 $filtro12 $filtro14 $filtro16 $filtro18 $filtro20 $filtro22 $filtro24 order by est.descripcion";

$cOtA="SELECT invl.id, invl.clave, invl.descripcion, invl.marca, invl.presentacion, invl.min, invl.max, dep.nombre, invl.subdepto, invl.status, invl.ctrl, invl.invgral, invl.invmatriz, invl.invtepex, invl.invhf, invl.invgralreyes, invl.invreyes, invl.existencia, invl.invcam, invl.invsnv
FROM invl 
inner JOIN dep ON dep.departamento=invl.depto
where clave<>'' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 $filtro14
ORDER BY invl.descripcion ASC";

/*
    $cSql1=mysql_query("SELECT count(*) as contar1 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.suc=1 and date_format(invldet.fecha,'%Y-%m-%d')>='$FechaI' and date_format(invldet.fecha,'%Y-%m-%d')<='$FechaF'");

*/

$OtA  = mysql_query($cOtA,$link);

require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Reporte de Inventario ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>

    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);

echo "<table align='center' width='97%' border='0'>";
echo "<tr>";
echo "<td align='left' ><FONT SIZE=4>$Titulo</font></td></tr>";

echo "<form name='form' method='post' action='repinvnet.php'>";

echo "<td align='left'><font class='content_txt'><b>Depto</b></font>";
echo "<select class='content_txt' name='filtro'>";
echo "<option value='*'>Todos*</option>";
$depA=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
while($dep=mysql_fetch_array($depA)){
    echo "<option value=$dep[departamento]>$Gfont $dep[departamento]&nbsp;$dep[nombre] </font></option>";
    if($dep[departamento]==$filtro){$DesSuc=$dep[nombre];}
}
echo "<option selected value='$filtro'>$Gfont <font size='1'>$filtro $DesSuc </font></option></p>";       
echo "</select>";
echo"</b></td>";


echo "<td align='left'>$Gfont<b><font size='1'>Subdepto</b></font>";
echo "<select size='1' name='filtro3' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
$subdepA=mysql_query("SELECT id,subdepto,nombre FROM depd where depd.departamento=$filtro order by id");
while($subdep=mysql_fetch_array($subdepA)){
    echo "<option value=$subdep[subdepto]> $subdep[id]&nbsp;$subdep[subdepto]</option>";
    if($subdep[subdepto]==$filtro3){$Desdep=$subdep[subdepto];}
}
echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3</option></p>";       
echo "</select>";
echo"</b></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Presentacion</b></font>";
echo "<select size='1' name='filtro5' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Piezas'>Piezas</option>";
echo "<option value='Cajas'>Cajas</option>";
echo "<option value='Paquetes'>Paquetes</option>";
echo "<option value='Bolsas'>Bolsas</option>";
echo "<option value='Kilos'>Kilos</option>";
echo "<option value='Litros'>Litros</option>";
echo "<option selected value='$filtro5'>$Gfont <font size='-1'>$filtro5</option></p>";       
echo "</SELECT>";
echo "</b></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
echo "<select size='1' name='filtro9' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Activo'>Activo</option>";
echo "<option value='Inactivo'>Inactivo</option>";
echo "<option selected value='$filtro9'>$Gfont <font size='-1'>$filtro9</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</font></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Busca</b></font>";
echo "<input type='text' name='filtro7' size='30' maxlength='30' placeholder='Marca o Descripcion' value='$filtro7'>";
echo"</td>";

  echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
  echo "<select size='1' name='filtro11' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $SucA=mysql_query("SELECT id,alias FROM cia order by id");
  while($Suc=mysql_fetch_array($SucA)){
    echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
    if($Suc[id]==$filtro11){$DesSuc2=$Suc[alias];}
  }
  echo "<option selected value='$filtro11'>$Gfont <font size='-1'>$filtro11 $DesSuc2</option></p>";     
  echo "</select>";
  echo"</b></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Ctrl</b></font>";
echo "<select size='1' name='filtro13' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Pendiente'>Pendiente</option>";
echo "<option value='Revision'>Revision</option>";
echo "<option value='Verificado'>Verificado</option>";
echo "<option selected value='$filtro13'>$Gfont <font size='-1'>$filtro13</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</font></td>";

echo "<td><input type='SUBMIT' value='Ok'></td>";

  echo "</form>";
  echo "</font><td>";

echo "<form name='form2' method='get' action='menu.php'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";
echo "</td></tr></table>";

$Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
echo "<table width='97%' align='center' border='0'>";
$Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";

if($Sucursal=='sucursalt'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon Matriz</td><td align='center'>$Gfon Tpx</td><td align='center'>$Gfon OHF</td><td align='center'>$Gfon CAM</td><td align='center'>$Gfon SNV</td><td align='center' bgcolor='#7dcea0'>$Gfon GralRys</td><td align='center'>$Gfon Reyes</td><td align='center' bgcolor='#7dcea0'>$Gfon Exist</td>";
}elseif($Sucursal=='sucursal0'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td>";
}elseif($Sucursal=='sucursal1'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon Matriz</td>";
}elseif($Sucursal=='sucursal2'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon OHF</td>";
}elseif($Sucursal=='sucursal3'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon Tpx</td>";
 }elseif($Sucursal=='sucursal4'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon GralRys</td><td align='center'>$Gfon Reyes</td>";
 }elseif($Sucursal=='sucursal5'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon CAM</td>";
 }elseif($Sucursal=='sucursal6'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon SNV</td>";
 }

echo "<tr bgcolor='#a2b2de'><td align='center'>$Gfon #</td><td align='center'>$Gfon Clave </td><td align='center' width='20%'>$Gfon Descripcion</td><td align='center'>$Gfon Marca</td><td align='center'>$Gfon Presentacion</td><td align='center'>$Gfon Min - Max</td><td align='center'>$Gfon Depto</td><td align='center'>$Gfon Subdepto</td>".$Tit."<td align='center'>$Gfon Status</td><td align='center'>$Gfon - </td><td align='center'>$Gfon Ctrl</td></tr>";

while ($reg = mysql_fetch_array($OtA)) {

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
    
    $condicion=0;
/*
    $deptoA=mysql_fetch_array(mysql_query("SELECT departamento,nombre FROM dep where dep.departamento=$reg[depto]"));
    $depto = $deptoA[nombre];

    $cSql1=mysql_query("SELECT count(*) as contar1 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.suc=1 and date_format(invldet.fecha,'%Y-%m-%d')>='$FechaI' and date_format(invldet.fecha,'%Y-%m-%d')<='$FechaF'");

    $reg1 = mysql_fetch_array($cSql1);

    $cSql2=mysql_query("SELECT count(*) as contar2 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.suc=2 and date_format(invldet.fecha,'%Y-%m-%d')>='$FechaI' and date_format(invldet.fecha,'%Y-%m-%d')<='$FechaF'");

    $reg2 = mysql_fetch_array($cSql2);

    $cSql3=mysql_query("SELECT count(*) as contar3 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.suc=3 and date_format(invldet.fecha,'%Y-%m-%d')>='$FechaI' and date_format(invldet.fecha,'%Y-%m-%d')<='$FechaF'");

    $reg3 = mysql_fetch_array($cSql3);

    $cSql4=mysql_query("SELECT count(*) as contar4 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.suc=4 and date_format(invldet.fecha,'%Y-%m-%d')>='$FechaI' and date_format(invldet.fecha,'%Y-%m-%d')<='$FechaF'");

    $reg4 = mysql_fetch_array($cSql4);
*/
    if($Sucursal=='sucursalt'){

        $General=$reg[invgral];

        if($General==0){
          $ExistG='';
        }else{
          $ExistG=$General;
          $condicion=1;
        }

        $Matriz=$reg[invmatriz];

        if($Matriz==0){
          $ExistM='';
        }else{
          $ExistM=$Matriz . ' | ';
          $condicion=1;
        }

        $ContarM=$reg1[contar1];

        if($ContarM==0){
          $ExistCM='';
        }else{
          $ExistCM=$ContarM;
          $condicion=1;
        }

        $Tpex=$reg[invtepex];

        if($Tpex==0){
          $ExistTpx='';
        }else{
          $ExistTpx=$Tpex . ' | ';
          $condicion=1;
        }

        $ContarTpx=$reg3[contar3];

        if($ContarTpx==0){
          $ExistCTpx='';
        }else{
          $ExistCTpx=$ContarTpx;
          $condicion=1;
        }

        $Ohf=$reg[invhf];

        if($Ohf==0){
          $ExistOhf='';
        }else{
          $ExistOhf=$Ohf . ' | ';
          $condicion=1;
        }

        $ContarOhf=$reg2[contar2];

        if($ContarOhf==0){
          $ExistCOhf='';
        }else{
          $ExistCOhf=$ContarOhf;
          $condicion=1;
        }


        $Cam=$reg[invcam];

        if($Cam==0){
          $ExistCam='';
        }else{
          $ExistCam=$Cam . ' | ';
          $condicion=1;
        }

        $Snv=$reg[invsnv];

        if($Snv==0){
          $ExistSnv='';
        }else{
          $ExistSnv=$Snv . ' | ';
          $condicion=1;
        }

        $GeneralRys=$reg[invgralreyes];

        if($GeneralRys==0){
          $ExistGRys='';
        }else{
          $ExistGRys=$GeneralRys;
          $condicion=1;
        }

        $Rys=$reg[invreyes];

        if($Rys==0){
          $ExistRys='';
        }else{
          $ExistRys=$Rys . ' | ';
          $condicion=1;
        }

        $ContarRys=$reg4[contar4];

        if($ContarRys==0){
          $ExistCRys='';
        }else{
          $ExistCRys=$ContarRys;
          $condicion=1;
        }

        $Contarcam=$reg5[contar5];

        if($Contarcam==0){
          $ExistCcam='';
        }else{
          $ExistCcam=$Contarcam;
          $condicion=1;
        }

        $Contarsnv=$reg6[contar6];

        if($Contarsnv==0){
          $ExistCsnv='';
        }else{
          $ExistCsnv=$Contarsnv;
          $condicion=1;
        }

        $Existencias= $reg[existencia];

        if($Existencias==0){
          $ExistenciasG='';
        }else{
          $ExistenciasG=$Existencias;
          $condicion=1;
        }

      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistG</font></td><td align='center'>$Gfont $ExistM <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=1')>$ExistCM</a></font></td><td align='center'>$Gfont $ExistTpx <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=3')>$ExistCTpx</font></a></td><td align='center'>$Gfont $ExistOhf <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=2')>$ExistCOhf</font></a></td><td align='center'>$Gfont $ExistCam <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=2')>$ExistCcam</font></a></td><td align='center'>$Gfont $ExistSnv <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=2')>$ExistCsnv</font></a></td><td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistGRys</font></td><td align='center'>$Gfont $ExistRys <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=4')>$ExistCRys</font></a></td><td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistenciasG</font></td>";
      
    }elseif($Sucursal=='sucursal0'){
              
        $General=$reg[invgral];

        if($General==0){
          $ExistG='';
        }else{
          $ExistG=$General;
          $condicion=1;
        }

      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistG</font></td>";

    }elseif($Sucursal=='sucursal1'){
 
        $General=$reg[invgral];

        if($General==0){
          $ExistG='';
        }else{
          $ExistG=$General;
          $condicion=1;
        }

        $Matriz=$reg[invmatriz];

        if($Matriz==0){
          $ExistM='';
        }else{
          $ExistM=$Matriz . ' | ';
          $condicion=1;
        }

        $ContarM=$reg1[contar1];

        if($ContarM==0){
          $ExistCM='';
        }else{
          $ExistCM=$ContarM;
          $condicion=1;
        }


      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistG</font></td><td align='center'>$Gfont $ExistM <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=1')>$ExistCM</a></td>";

    }elseif($Sucursal=='sucursal2'){
              
        $General=$reg[invgral];

        if($General==0){
          $ExistG='';
        }else{
          $ExistG=$General;
          $condicion=1;
        }

        $Ohf=$reg[invhf];

        if($Ohf==0){
          $ExistOhf='';
        }else{
          $ExistOhf=$Ohf . ' | ';
          $condicion=1;
        }

        $ContarOhf=$reg2[contar2];

        if($ContarOhf==0){
          $ExistCOhf='';
        }else{
          $ExistCOhf=$ContarOhf;
          $condicion=1;
        }

      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistG</font></td><td align='center'>$Gfont $ExistOhf <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=2')>$ExistCOhf</font></a></td>";

    }elseif($Sucursal=='sucursal3'){
              
        $General=$reg[invgral];

        if($General==0){
          $ExistG='';
        }else{
          $ExistG=$General;
          $condicion=1;
        }

        $Tpex=$reg[invtepex];

        if($Tpex==0){
          $ExistTpx='';
        }else{
          $ExistTpx=$Tpex . ' | ';
          $condicion=1;
        }

        $ContarTpx=$reg3[contar3];

        if($ContarTpx==0){
          $ExistCTpx='';
        }else{
          $ExistCTpx=$ContarTpx;
          $condicion=1;
        }

      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistG</font></td><td align='center'>$Gfont $ExistTpx <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=3')>$ExistCTpx</font></a></td>";

    }elseif($Sucursal=='sucursal4'){
      
        $GeneralRys=$reg[invgralreyes];

        if($GeneralRys==0){
          $ExistGRys='';
        }else{
          $ExistGRys=$GeneralRys;
          $condicion=1;
        }

        $Rys=$reg[invreyes];

        if($Rys==0){
          $ExistRys='';
        }else{
          $ExistRys=$Rys . ' | ';
          $condicion=1;
        }

        $ContarRys=$reg4[contar4];

        if($ContarRys==0){
          $ExistCRys='';
        }else{
          $ExistCRys=$ContarRys;
          $condicion=1;
        }


      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $ExistGRys</font></td><td align='center'>$Gfont $ExistRys <a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=4')>$ExistCRys</font></a></td>";
    }

    if($condicion==1){
      
        $nRng++;

        if($reg[existencia]<$reg[min]){
          $color='red';
          $estado='Faltante';
        }elseif($reg[existencia]>$reg[max]){
          $color='blue';
          $estado='Excedido';
        }else{
          $color='black';
          $estado='Ok';
        }

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>$Gfont $nRng</td><td>$Gfont <a href=javascript:wingral('movimientosinv.php?busca=$reg[id]')><img src='images/movimientos.png' onmouseover='this.width=25;this.height=25;' onmouseout='this.width=20;this.height=20;' width='20' height='20' alt='' /></a> - <a href=javascript:wingral('invlabe2.php?busca=$reg[id]')><font size='1'> $reg[clave]</a></td><td><a href=javascript:wingral('movimientosinvdet.php?busca=$reg[id]&suc=*')>$Gfont $reg[descripcion]</font></a></td><td>$Gfont $reg[marca]</font></td><td>$Gfont $reg[presentacion]</font></td><td align='center'>$Gfont $reg[min] - $reg[max]</font></td><td>$Gfont $depto</font></td><td>$Gfont $reg[subdepto]</font></td>".$Tit2."<td align='center'>$Gfont $reg[status]</font></td><td align='center'>$Gfont <font color='$color'>$estado</font></td><td align='center'>$Gfont $reg[ctrl]</font></td></tr>";

    }

}

echo "<tr bgcolor='#a2b2de'><td>&nbsp;</td><td>&nbsp;</td><td align='center' >$Gfont <b>Total de estudios:  $nRng </b></font></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

echo "</table>";

?>

</body>

<script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>