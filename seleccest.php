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
    $ExiA = mysql_query("SELECT idestudio FROM otd WHERE idestudio='$_REQUEST[cId]' limit 1");
    $Exi = mysql_fetch_array($ExiA);

    if ($Exi[idestudio] <> "") {
        $Msj = "No es posible eliminar el estudio, ya que existen ot's que hacen referencia a este estudio";
    } else {
        $lUp = mysql_query("DELETE FROM est WHERE id='$_REQUEST[cId]' limit 1");
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
 //$Suc='*';

}else{
    $BusInt=" est.estudio like '%$busca%' or est.descripcion like '%$busca%'";  
    //$OrdenDef = 'med.medico';

  //  $_SESSION['OnToy'][2] = 'est.estudio';
// $Suc='*';
}

#Armo el query segun los campos tomados de qrys;

if( $busca == ''){

    $sql = "SELECT $Qry[campos], estudio, subdepto, dep.departamento,dep.nombre as nombredepto FROM est,dep where est.estudio<>'' and est.depto=dep.departamento and est.activo='Si' $filtro2 $filtro4 $filtro8 order by estudio";   

}else{

    $sql = "SELECT $Qry[campos], estudio,subdepto, dep.departamento,dep.nombre as nombredepto FROM est,dep WHERE est.estudio<>'' and est.depto=dep.departamento and est.activo='Si' $filtro2 $filtro4 $filtro8 order by estudio";

}

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "-", "-", " ", "-", "-");      //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Catalogo de Estudios</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <?php
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
    echo '<body topmargin="1">';

echo "<table align='center' width='97%' border='0'>";
echo "<tr>";
echo "<td align='center' colspan='6'><FONT class='content_txt' SIZE='3'><b>$Titulo</b></font></td></tr>";

    echo "<form name='form' method='post' action='seleccest.php'>";

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

    echo "<td align='left'>$Gfont<b><font size='1'>Busca: </b></font>";
    echo "<input type='text' name='filtro7' size='30' maxlength='30' placeholder='Clave o Descripcion' value='$filtro7'>";
    echo"</td>";

    echo "<td><input type='SUBMIT' value='Buscar'></td>";
    echo "<input type='hidden' value='$_REQUEST[Dir]' name='Dir'></input>";
    echo "<input type='hidden' value='$_REQUEST[Est]' name='Est'></input>";

    echo "</form>";
    echo "</font>";

    echo "<td align='center'>";
    echo "<a href='$_REQUEST[Dir].php?busca=$_REQUEST[Est]'><i class='fa fa-reply fa-2x' aria-hidden='true'></i>$Gfon Regresar </a></font>";
    echo "</td>";

    echo "</tr></table><br>";

echo "<table width='97%' align='center' border='0'>";

echo "<tr bgcolor='#a2b2de'><td align='center' width='5%'>$Gfon #</td><td align='center'>$Gfon - </td><td align='center' width='5%'>$Gfon Clave</td><td align='center' width='25%'>$Gfon Descripcion</td><td align='left'>$Gfon Departamento </td><td align='center'>$Gfon Subdepartamento</td></tr>";

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

        echo "<td class='Seleccionar' align='center'>$Gfont $nRng </td>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_REQUEST[Dir].php?Estudio=$rg[estudio]&Est=si&busca=$_REQUEST[Est]'>Seleccionar</a></td>";


        echo "<td class='Seleccionar' align='left'>$Gfont $rg[estudio]</td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[descripcion]</td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[nombredepto]</td>";

        echo "<td class='Seleccionar' align='left'>$Gfont $rg[subdepto]</td>";

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

echo "</form>";
echo "</div>";
    echo '</body>';
    ?>

</html>
