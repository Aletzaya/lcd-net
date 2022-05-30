<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

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

        $_SESSION["OnToy"] = array('', '', 'med.nombrec', 'Asc', $Retornar, '*', '*', '*','*','*');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
if (isset($_REQUEST[filtro])) {
    $_SESSION['OnToy'][5] = $_REQUEST[filtro];
}
if (isset($_REQUEST[filtro3])) {
    $_SESSION['OnToy'][6] = $_REQUEST[filtro3];
}
if (isset($_REQUEST[filtro5])) {
    $_SESSION['OnToy'][7] = $_REQUEST[filtro5];
}
if (isset($_REQUEST[filtro7])) {
    $_SESSION['OnToy'][8] = $_REQUEST[filtro7];
}
if (isset($_REQUEST[filtro9])) {
    $_SESSION['OnToy'][9] = $_REQUEST[filtro9];
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$filtro = $_SESSION[OnToy][5];
$filtro3 = $_SESSION[OnToy][6];
$filtro5 = $_SESSION[OnToy][7];
$filtro7 = $_SESSION[OnToy][8];
$filtro9 = $_SESSION[OnToy][9];

$Mnu = $_SESSION[Mnu];
$Cat='Medicos';

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
$Titulo = "Medicos";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 2;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 2) {
    $Dsp = 'Filtro activo';
}

if($filtro=='*'){
    $filtro2="";
}else{
   $filtro2="and med.clasificacion='$filtro'";
}

if($filtro3=='*'){
    $filtro4="";
}else{
   $filtro4="and med.status='$filtro3'";
}

if($filtro5=='*'){
    $filtro6="";
}else{
   $filtro6="and med.promotorasig='$filtro5'";
}

if($filtro7=='*'){
    $filtro8="";
}else{
   $filtro8="and med.zona='$filtro7'";
}

if($filtro9=='*'){
    $filtro10="";
}else{
   $filtro10="and med.ruta='$filtro9'";
}

  #Deshago la busqueda por palabras(una busqueda inteligte;

    $Palabras  = str_word_count($busca);  //Dame el numero de palabras
    if($Palabras > 1){
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" med.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and med.nombrec like '%$P[$i]%' ";}
     }
     //$Suc='*';

    }else{
            //$BusInt=" med.medico = '$busca' ";  
            $BusInt=" med.medico like '%$busca%' or med.nombrec like '%$busca%'";

            //$OrdenDef = 'med.medico';

            $_SESSION['OnToy'][2] = 'med.medico';
    // $Suc='*';
    }

#Armo el query segun los campos tomados de qrys;

if( $busca == ''){

    $cSql = "SELECT $Qry[campos],med.id,med.clasificacion,med.status,med.promotorasig,med.zona,med.ruta FROM $Qry[froms] WHERE id>=0 $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";   

}else{

    $cSql = "SELECT $Qry[campos],med.id,med.clasificacion,med.status,med.promotorasig,med.zona,med.ruta FROM med WHERE $BusInt";

}


#Armo el query segun los campos tomados de qrys;
//$cSql = "SELECT $Qry[campos],id FROM med WHERE $BusInt $Qry[filtro]";

//echo $cSql;

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-"," ", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
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
        <title>Medicos ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu,$Gusr);
    ?>
<?php
	  	  echo "<table align='right' width='50%' border='0' cellspacing='0' cellpadding='0'>";

            echo "<tr align='right'>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Ruta</b></font>";
            echo "<form name='form' method='post' action='medicos.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
            echo "<select size='1' name='filtro9' class='Estilo10' onchange=this.form.submit()>";
            echo "<option value='*'>Todos*</option>";
                 $RtaA=mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
               while ($Rta=mysql_fetch_array($RtaA)){
                     echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
                     if($Rta[id]==$filtro9){$Des1=$Rta[descripcion];}
               }
            echo "<option selected value='*'>$Gfont <font size='-1'>$filtro9 $Des1</option></p>";		  
            echo "</select>";
            echo"</b></td><p>";
            echo "</form>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Zona</b></font>";
            echo "<form name='form' method='post' action='medicos.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
            echo "<select size='1' name='filtro7' class='Estilo10' onchange=this.form.submit()>";
            echo "<option value='*'>Todos*</option>";
                 $ZnaA=mysql_query("SELECT zona,descripcion FROM zns order by zona");
            while($Zna=mysql_fetch_array($ZnaA)){
                  echo "<option value=$Zna[zona]> $Zna[zona]&nbsp;$Zna[descripcion]</option>";
                  if($Zna[zona]==$filtro7){$DesZna=$Zna[descripcion];}
            }
            echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7 $DesZna</option></p>";		  
            echo "</select>";
            echo"</b></td><p>";
            echo "</form>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Promotor</b></font>";
            echo "<form name='form' method='post' action='medicos.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
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
            echo"</b></td><p>";
            echo "</form>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Clasif</b></font>";
            echo "<form name='form' method='post' action='medicos.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
            echo "<select size='1' name='filtro' class='Estilo10' onchange=this.form.submit()>";
            echo "<option value='*'>Todos*</option>";
            echo "<option value='A'>A</option>";
            echo "<option value='B'>B</option>";
            echo "<option value='C'>C</option>";
            echo "<option value='D'>D</option>";
            echo "<option selected value='*'>$Gfont <font size='-1'>$filtro</option></p>";		  
            echo "</select>";
            echo"</b></td><p>";
            echo "</form>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Status</b></font>";
            echo "<form name='form' method='post' action='medicos.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
            echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
            echo "<option value='*'>Todos*</option>";
            echo "<option value='Activo'>Activo</option>";
            echo "<option value='Inactivo'>Inactivo</option>";
            echo "<option value='Defuncion'>Defuncion</option>";
            echo "<option value='Baja'>Baja</option>";
            echo "<option value='Otro'>Otro</option>";
            echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3</option></p>";		  
            echo "</select>";
            echo"</b></td>";
            echo "</form>";
            echo"</tr></table>";
  
?>
    <script src="./controladores.js"></script>
<?php
    //submenu();
//Tabla contenedor de brighs
    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr>';
    echo '<tr>';
    echo '<td height="380" valign="top">';
    
    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;


    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        //echo "<tr>";

        if ($RetSelec <> '') {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$RetSelec?Paciente=$rg[id]'>Seleccionar</a></td>";
        } else {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[id]'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('medicopdf.php?busca=$rg[id]')><i class='fa fa-print fa-2x' aria-hidden='true'></i></a></td>";
        }



        Display($aCps, $aDat, $rg);

        if ($Nivel >= 7) {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='historico.php?op=bc&busca=$rg[estudio]'>H.clinico</a></td>";
        } else {
            echo "<td class='Seleccionar' align='center'> - </td>";
        }

        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[estudio]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');><i class='fa fa-trash fa-2x' aria-hidden='true'></i> </a></td>";

        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

    CuadroInferior4($busca);


    echo '</body>';
    ?>

</html>
<?php
mysql_close();
?>

