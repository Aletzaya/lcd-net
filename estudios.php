<?php

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

  if (!isset($_REQUEST[filtro5])){
      $filtro5 = '* Todos';
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
      $filtro11 = '* Todos';
  }else{
      $filtro11    = $_REQUEST[filtro11];       
  }

  if (!isset($_REQUEST[filtro13])){
      $filtro13 = '* Todos';
  }else{
      $filtro13    = $_REQUEST[filtro13];       
  }

  if (!isset($_REQUEST[filtro15])){
      $filtro15 = '* Todos';
  }else{
      $filtro15    = $_REQUEST[filtro15];       
  }

  if (!isset($_REQUEST[filtro17])){
      $filtro17 = '* Todos';
  }else{
      $filtro17    = $_REQUEST[filtro17];       
  }

  if (!isset($_REQUEST[filtro19])){
      $filtro19 = '* Todos';
  }else{
      $filtro19    = $_REQUEST[filtro19];       
  }

  if (!isset($_REQUEST[filtro21])){
      $filtro21 = '* Todos';
  }else{
      $filtro21    = $_REQUEST[filtro21];       
  }

  if (!isset($_REQUEST[filtro23])){
      $filtro23 = '* Todos';
  }else{
      $filtro23    = $_REQUEST[filtro23];       
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

 if($filtro7<>'*'){
    $filtro8="and (est.estudio like '%$filtro7%' or est.descripcion like '%$filtro7%')";
 }else{
    $filtro8="";
 }

 if($filtro9<>'*'){
    $filtro10="and est.activo='$filtro9'";
 }else{
    $filtro10=" ";
 }

 if($filtro13<>'* Todos'){
    $filtro14="and invl.ctrl='$filtro13'";
 }else{
    $filtro14=" ";
 }

 $Status2 = $_REQUEST[Status2];

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
/*$filtro9 = $_SESSION[OnToy][5];
$filtro3 = $_SESSION[OnToy][6];
$filtro7 = $_SESSION[OnToy][7];
$filtro5 = $_SESSION[OnToy][8];
*/
#Variables comunes;
$Titulo = "Catalogo de Estudios";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 10;             //Numero de query dentro de la base de datos
//
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
#Intruccion a realizar si es que mandan algun proceso
if ($op == 'Si') {                    //Elimina rg
    $ExiA = mysql_query("SELECT estudio FROM otd WHERE estudio='$_REQUEST[Estudio]' limit 1");
    $Exi = mysql_fetch_array($ExiA);

    if ($Exi[estudio] <> "") {
        $Msj = "No es posible eliminar el estudio, ya que existen ot's que hacen referencia a este estudio";
    } else {
        $lUp = mysql_query("DELETE FROM est WHERE id='$_REQUEST[cId]' and estudio='$_REQUEST[Estudio]' limit 1");
        $Msj = "Registro eliminado";
    }
}

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

}else{
    $BusInt=" est.estudio like '%$busca%' or est.descripcion like '%$busca%'";  
}

#Armo el query segun los campos tomados de qrys;

$listap=mysql_query("SELECT lista FROM inst where institucion=1");

$lista=mysql_fetch_array($listap);

$listas=$lista[lista];

$listaprec='lt'.$listas;


$listap3=mysql_query("SELECT lista FROM inst where institucion=3");

$lista3=mysql_fetch_array($listap3);

$listas3=$lista3[lista];

$listaprec3='lt'.$listas3;


$listap10=mysql_query("SELECT lista FROM inst where institucion=10");

$lista10=mysql_fetch_array($listap10);

$listas10=$lista10[lista];

$listaprec10='lt'.$listas10;


$listap20=mysql_query("SELECT lista FROM inst where institucion=20");

$lista20=mysql_fetch_array($listap20);

$listas20=$lista20[lista];

$listaprec20='lt'.$listas20;


$listap50=mysql_query("SELECT lista FROM inst where institucion=50");

$lista50=mysql_fetch_array($listap50);

$listas50=$lista50[lista];

$listaprec50='lt'.$listas50;


$listap60=mysql_query("SELECT lista FROM inst where institucion=60");

$lista60=mysql_fetch_array($listap60);

$listas60=$lista60[lista];

$listaprec60='lt'.$listas60;


if( $busca == ''){

    $sql = "SELECT $Qry[campos], estudio, subdepto, dep.departamento,dep.nombre as nombredepto,$listaprec as ltp,$listaprec3 as ltp3,$listaprec10 as ltp10,$listaprec20 as ltp20,$listaprec50 as ltp50,$listaprec60 as ltp60, est.id FROM est,dep where est.estudio<>'' and est.depto=dep.departamento $filtro2 $filtro4 $filtro8 $filtro10 order by estudio";   

}else{

    $sql = "SELECT $Qry[campos], estudio,subdepto, dep.departamento,dep.nombre as nombredepto,$listaprec as ltp,$listaprec3 as ltp3,$listaprec10 as ltp10,$listaprec20 as ltp20,$listaprec50 as ltp50,$listaprec60 as ltp60, est.id FROM est,dep WHERE est.estudio<>'' and est.depto=dep.departamento $filtro2 $filtro4 $filtro8 $filtro10 order by estudio";

}

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "-", "-", " ", "-", "-");      //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;

if($Status2=='Exportar'){

}else{

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Catalogo de Estudios</title>
        <?php require ("./config_add.php"); ?>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu, $Gusr);
    ?>
    <script src="./controladores.js"></script>

<?php
 }
if($Status2=='Exportar'){
    header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename=nombre_archivo.xls'); 
}

echo "<table align='center' width='98%' border='0'>";
echo "<tr>";
echo "<td align='center' colspan='7'><FONT class='content_txt' SIZE='3'><b>$Titulo</b></font></td></tr>";

if($Status2=='Exportar'){

}else{

    echo "<form name='form' method='post' action='estudios.php'>";

    echo "<td align='left'><font class='letrap'><font size='1'><b>Depto: </b></font>";
    echo "<select class='letrap' name='filtro'  onchange=this.form.submit()>";
    echo "<option value='*'>* Todos</option>";
    $depA=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
    while($dep=mysql_fetch_array($depA)){
        echo "<option value=$dep[departamento]>$Gfont $dep[departamento]&nbsp;$dep[nombre] </font></option>";
        if($dep[departamento]==$filtro){$DesSuc=$dep[nombre];}
    }
    echo "<option selected value='$filtro'><font size='1'>$filtro $DesSuc </font></option></p>";       
    echo "</select>";
    echo"</b></td>";

    echo "<td align='left'>$Gfont<b><font size='1'>Subdepto: </b></font>";
    echo "<select size='1' name='filtro3' class='letrap' onchange=this.form.submit()>";
    echo "<option value='*'>* Todos</option>";
    $subdepA=mysql_query("SELECT id,subdepto,nombre FROM depd where depd.departamento=$filtro order by id");
    while($subdep=mysql_fetch_array($subdepA)){
        echo "<option value=$subdep[subdepto]> $subdep[id]&nbsp;$subdep[subdepto]</option>";
        if($subdep[subdepto]==$filtro3){
            $Desdep=$subdep[id];
        }else{
            $Desdep='';
        }
    }
    echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$Desdep &nbsp; $filtro3</option></p>";       
    echo "</select>";
    echo"</b></td>";

    echo "<td align='left'>$Gfont<b><font size='1'>Status: </b></font>";
    echo "<select size='1' name='filtro9' class='letrap' onchange=this.form.submit()>";
    echo "<option value='*'>* Todos</option>";
    echo "<option value='Si'>Activo</option>";
    echo "<option value='No'>Inactivo</option>";
        if($filtro9=='Si'){
            $Dests='Activo';
        }elseif($filtro9=='No'){
            $Dests='Inactivo';
        }else{
            $Dests='* Todos';
        }
    echo "<option selected value='$filtro9'>$Gfont <font size='-1'>$Dests</option></p>";       
    echo "</select>";
    echo"</b></td>";
    echo "</font></td>";

    echo "<td align='left'>$Gfont<b><font size='1'>Busca: </b></font>";
    echo "<input type='text' name='filtro7' size='30' maxlength='30' placeholder='Clave o Descripcion' value='$filtro7'>";
    echo"</td>";

    echo "<td><input type='SUBMIT' value='Buscar'></td>";

      echo "</form>";

    echo "<td><a class='edit' href=javascript:wingral('estudiose.php?busca=Nuevo')><b>Agregar &nbsp; <b><i class='fa fa-plus-square' aria-hidden='true'></i></a></td>";

      echo "</font>";

    echo "</tr></table><br>";
}

$Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
echo "<table width='98%' align='center' border='0'>";
$Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";

echo "<tr bgcolor='#a2b2de'><td align='center' width='5%'>$Gfon #</td><td align='center'>$Gfon PDF </td><td align='center' width='5%'>$Gfon Clave</td><td align='center' width='25%'>$Gfon Descripcion</td><td align='left'>$Gfon Departamento </td><td align='center'>$Gfon Subdepartamento</td><td align='center'>$Gfon Status</td><td align='center'>$Gfon Prec.Lcd</td><td align='center'>$Gfon Prec.Tpx</td><td align='center'>$Gfon Prec.Rys</td><td align='center'>$Gfon Prec.Ohf</td><td align='center'>$Gfon Prec.Cam</td><td align='center'>$Gfon Prec.Svc</td><td align='center'>$Gfon Bas</td><td align='center'>$Gfon Eqp</td><td align='center'>$Gfon Mstr</td><td align='center'>$Gfon Cont</td><td align='center'>$Gfon Desc</td><td align='center'>$Gfon Admn</td><td align='center'>$Gfon Atn</td><td align='center'>$Gfon Elem</td><td align='center'>$Gfon - </td></tr>";

    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #
    $nRng=1;
    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        //echo "<tr>";

        if ($RetSelec <> '') {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$RetSelec?Paciente=$rg[id]'>$nRng &nbsp; <i class='fa fa-pencil fa-2x' aria-hidden='true'></i> &nbsp;</a></td>";
        } else {
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('$cLink?busca=$rg[estudio]')>$nRng &nbsp; <i class='fa fa-pencil fa-2x' aria-hidden='true'></i> &nbsp; </a></td>";
        }
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('estudiopdf.php?busca=$rg[estudio]')><i class='fa fa-print fa-2x' aria-hidden='true'></i></a></td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[estudio]</td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[descripcion]</td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[nombredepto]</td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[subdepto]</td>";

        if($rg[activo]=='Si'){
            $Status='Activo';
        }elseif($rg[activo]=='No'){
            $Status='Inactivo';
        }else{
            $Status='-';
        }

        echo "<td class='Seleccionar' align='center'>$Gfont $Status</td>";

        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('estudiosadmin.php?busca=$rg[estudio]')>$Gfont <font color='blue'> <b>$ ".number_format($rg[ltp],"2")."<b></a></td>";

        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('estudiosadmin.php?busca=$rg[estudio]')>$Gfont <font color='blue'> <b>$ ".number_format($rg[ltp3],"2")."<b></a></td>";

        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('estudiosadmin.php?busca=$rg[estudio]')>$Gfont <font color='blue'> <b>$ ".number_format($rg[ltp10],"2")."<b></a></td>";

        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('estudiosadmin.php?busca=$rg[estudio]')>$Gfont <font color='blue'> <b>$ ".number_format($rg[ltp20],"2")."<b></a></td>";

        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('estudiosadmin.php?busca=$rg[estudio]')>$Gfont <font color='blue'> <b>$ ".number_format($rg[ltp50],"2")."<b></a></td>";

        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('estudiosadmin.php?busca=$rg[estudio]')>$Gfont <font color='blue'> <b>$ ".number_format($rg[ltp60],"2")."<b></a></td>";

        if ($rg[bloqbas] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }
        
        if ($rg[bloqeqp] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }

        if ($rg[bloqmue] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }
        
        if ($rg[bloqcon] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }

        if ($rg[bloqdes] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }

        if ($rg[bloqadm] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }
        
        if ($rg[bloqatn] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }        

        if ($rg[bloqele] == 'Si') {
            echo "<td align='center'><i class='fa fa-check-circle'  style='color:green' aria-hidden='true'></i></td>";
        } else {
            echo "<td align='center'><i class='fa fa-times-circle' aria-hidden='true' style='color:#DC7633'></i></td>";
        }

        if($Gusr=='nazario' or $Gusr=='NAZARIO'){

            echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[estudio]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si&Estudio=$rg[estudio]');>&nbsp; <i class='fa fa-trash fa-2x' aria-hidden='true'></i> &nbsp;</a></td>";

        }else{

            echo "<td align='center'> - </td>";

        }

        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    echo "<br>";
    echo "<br>";

    echo "<div align='center'>";
    echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
    echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
    echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('estudios1pdf.php?filtro=$filtro&filtro3=$filtro3&filtro9=$filtro9')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";
echo " &nbsp;  &nbsp;  &nbsp; <a class='edit' href='estudios.php?Status2=Exportar&filtro=$filtro&filtro3=$filtro3&filtro9=$filtro9'><img src='excel.png' height='30px' width='40px' /></a>";  

echo "</form>";
echo "</div>";
echo '</body>';
?>

</html>