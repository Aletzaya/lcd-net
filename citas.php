<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

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
        $Fecha = date("Y-m-d");

        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', 'menu.php', $Fecha, $Fecha, '*', '');
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
if (isset($_REQUEST[FechaI])) {
    $_SESSION['OnToy'][5] = $_REQUEST[FechaI];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST[FechaF])) {
    $_SESSION['OnToy'][6] = $_REQUEST[FechaF];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST[FiltroCia])) {
    $_SESSION['OnToy'][7] = $_REQUEST[FiltroCia];
}
if (isset($_REQUEST[Todo])) {
    $_SESSION['OnToy'][8] = $_REQUEST[Todo];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$FechaI = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION[OnToy][6];          //Pagina a la que regresa con parametros  
$GnSuc = $_SESSION[OnToy][7];          //Que sucursal estoy checando
$GTodo = $_SESSION[OnToy][8];          //Todos

$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$Titulo = "Consulta de citas";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 64;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " $OrdenDef LIKE  '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " AND $OrdenDef like '%$P[$i]%' ";
        }
    }
} else {
    $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}


$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "-", "-", "", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("Sucursal", "", "", "Dia", "", "", "No.cita", "", "", "Hora", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$aDia = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Citas ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

        <script> type = "text/javascript" src = "lib/dhtmlgoodies_calendar.js?random=90090518" ></script>

    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu);


    echo "<form name='frmfiltro' method='get' action=" . $_SERVER['PHP_SELF'] . ">";

    if ($Gcia == '0') {
        echo "<select class='content5' name='FiltroCia' onChange='frmfiltro.submit();'>";
        $CiaA = mysql_query("SELECT id,alias FROM cia ORDER BY id");
        while ($Cia = mysql_fetch_array($CiaA)) {
            echo '<option value=' . $Cia[id] . '>' . $Cia[alias] . '</option>';
            if ($Cia[id] == $GnSuc) {
                echo '<option selected value="' . $Cia[id] . '">' . $Cia[alias] . '</option>';
            }
        }
        echo "<option value='*'>* Todos</option>";
        if ($GnSuc == '*') {
            echo "<option selected value='*'>* todos</select>";
        }
        echo '</select>';
    }

    echo "<span class='content1'> Fec.inicial: </span>";
    echo "<input type='date' name='FechaI' value='$FechaI' size='10' class='content5'> ";
    echo "<span class='content1'> Fec.final: </span>";
    echo "<input type='date' name='FechaF' value='$FechaF' size='10' class='content5'> ";
    echo " &nbsp; <input type='submit' name='Boton' value='enviar' class='letrap'>";
    echo " &nbsp; &nbsp; <a  class='edit' href=javascript:wingral('citasprogramadas.php?busca=ini')><img src='images/rest.png'> Ver por horario y sucursal </a> ";
    echo "</form>";


#Armo el query segun los campos tomados de qrys;
    if ($GnSuc == '*') {                //Que sucursal estoy checando
        $cSql = "SELECT $Qry[campos],ot.suc,ot.citanum FROM inst,ot LEFT JOIN cli ON ot.cliente=cli.cliente 
              WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.servicio='Cita' AND 
              ot.institucion=inst.institucion AND $BusInt";
    } else {

        $cSql = "SELECT $Qry[campos],ot.suc,ot.citanum FROM inst,ot LEFT JOIN cli ON ot.cliente=cli.cliente 
              WHERE ot.servicio='Cita' AND ot.institucion=inst.institucion 
              AND ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.suc='$GnSuc' 
              AND $BusInt";
    }

//echo $cSql;
//Tabla contenedor de brighs
    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr>';
    echo '<td height="380" valign="top">';

    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $sql = $cSql . $cWhe . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
    //echo $sql;

    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {
        $aHrs = SPLIT(",", $rg[citanum]); // Dia | Cita | Hora;
        $NumDia = $aHrs[0];
        $Dia = $aDia[$NumDia];

        $nSuc = $rg[suc];
        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;
        $Sucursal = $aSucursal[$nSuc];
        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        //echo "<tr>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[orden]'>Editar</a></td>";

        echo "<td align='left' class='letrap'><a class='letra' href=javascript:winuni('impot.php?busca=$rg[orden]')><img src='images/printer.png'></a></td>";

        Display($aCps, $aDat, $rg);
        /*
          if($Nivel >= 7 ){
          echo "<td class='Seleccionar' align='center'><a class='edit' href='historico.php?op=bc&busca=$rg[id]'>H.clinico</a></td>";
          }else{
          echo "<td class='Seleccionar' align='center'> - </td>";
          }
         */
        echo "<td  class='content1'>$Sucursal</td>";
        echo "<td  class='content1'>$Dia</td>";
        echo "<td  class='content1'>$aHrs[1]</td>";
        echo "<td  class='content1'>$aHrs[2]</td>";

        //echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');>Eliminar</a></td>";

        echo "</tr>";
        $nRng++;
    }
    echo "</table>";

    echo '</td>';
    echo '</tr>';
    echo '</table>';

    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

    CuadroInferior($busca);
    echo '</body>';
    ?>
    <script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>
