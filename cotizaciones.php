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

        $Fecha = date("Y-m-d");

        $_SESSION["OnToy"] = array('', '', 'ct.id', 'Asc', 'menu.php', $Fecha, $Fecha, '*', '');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fechao = date("Y-m-d");

#Variables comunes;
$Titulo = "Cotizaciones";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 65;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 6) {
    $Dsp = 'Filtro activo';
}

if ($_REQUEST[CapturaOt]) {
    $rst = mysql_query("SELECT capturaot FROM ct WHERE id = $_REQUEST[rg]");
    $tt = mysql_fetch_array($rst);
    if ($tt[capturaot] == 0) {
        $sql = "SELECT * FROM ct WHERE id=$_REQUEST[rg]";
        $Rsc = mysql_query($sql);
        $cc = mysql_fetch_array($Rsc);
        $sql = "UPDATE otnvas SET cliente = '$cc[cliente]', inst='$cc[institucion]', fechae='$cc[fecha]',horae='$cc[hora]',medico='$cc[medico]',receta='$cc[receta]',"
                . "diagmedico='$cc[diagmedico]',observaciones='$cc[observaciones]',servicio='$cc[servicio]',medicon='$cc[medicon]',lista='$cc[lista]',paciented='$cc[paciented]' "
                . "WHERE usr = '$Gusr' AND venta=5;";
        mysql_query($sql);
        $sql = "SELECT * FROM ctd WHERE id=$_REQUEST[rg]";
        $Rsc = mysql_query($sql);
        while ($cc = mysql_fetch_array($Rsc)) {
            $sql = "INSERT INTO otdnvas (usr,estudio,descripcion,precio,descuento,venta,fechaest) VALUES ('$Gusr','$cc[estudio]','$cc[descripcion]','$cc[precio]','$cc[descuento]',5,'$Fechao');";
            if (!mysql_query($sql)) {
                echo "Error SQL " . $sql;
            }
        }
        mysql_query("UPDATE ct SET capturaot=1 WHERE id = $_REQUEST[rg]");
        header("Location: ordenesnvas.php?Venta=5");
    }
}

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
if ($_REQUEST[Boton] === 'enviar') {
    $cSql = "SELECT $Qry[campos],ct.id,ct.suc,ct.paciented,IF(ct.capturaot=0,'EC7063','58D68D') capturaot FROM inst,ct LEFT JOIN cli ON ct.cliente=cli.cliente "
            . "WHERE ct.institucion=inst.institucion AND $BusInt $Qry[filtro] "
            . "AND ct.fecha >= '" . $_SESSION['OnToy'][5] . "' AND ct.fecha <= '" . $_SESSION['OnToy'][6] . "' "
            . "AND ct.suc = '" . $_SESSION['OnToy'][7] . "'";
} else {
    $cSql = "SELECT $Qry[campos],ct.id,ct.suc,ct.paciented,IF(ct.capturaot=0,'EC7063','58D68D') capturaot FROM inst,ct LEFT JOIN cli ON ct.cliente=cli.cliente "
            . "WHERE ct.institucion=inst.institucion";
}
//echo $cSql;

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-", " ", "-", "-",  " ", "-", "-","Sucursal", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("Captura Ot's", "", "","Pac Div Inst", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cotizaciones</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>
    <body topmargin="1">
        <?php
        encabezados();

        menu($Gmenu,$Gusr);
        ?>
        <script src="./controladores.js"></script>
        <table><tr><td height="10px;"></td></tr></table>
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">

            <?php
            if ($Gcia == '0') {
                echo "<select class='content5' name='FiltroCia'>";
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
            ?>
            <span class='content1'> Fec.inicial: </span>
            <input type='date' name='FechaI' value='<?= $_SESSION['OnToy'][5] ?>' size='10' class='content5'></input>
            <span class='content1'> Fec.final: </span>
            <input type='date' name='FechaF' value='<?= $_SESSION['OnToy'][6] ?>' size='10' class='content5'></input>
            <input type='submit' name='Boton' value='enviar' class='letrap'></input>
            <a class='edit' href='?Todo=*'><i class='fa fa-eye fa-2x edit' aria-hidden='true'></i> Ver todo </a>
        </form>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();
                    $res = mysql_query($cSql);
                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
                    $sql = $cSql . $cWhe . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    $res = mysql_query($sql);
                    $Pos = strrpos($_SERVER[PHP_SELF], ".");
                    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';
                    while ($rg = mysql_fetch_array($res)) {
                        $nSuc = $rg[suc];
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        $Sucursal = $aSucursal[$nSuc];
                        echo "<tr bgcolor='$Fdo' style='height:20px;' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[id]'>Editar</a></td>";
                        echo "<td><a class='letra' href=javascript:wingral('imppdfcoti.php?busca=$rg[id]')><i class='fa  fa-file-pdf-o fa-lg' style='color:#3374FF' aria-hidden='true'></i></a></td>";
                        echo "<td><a class='letra' href=javascript:wingral('imppdfcoti1.php?busca=$rg[id]')><i class='fa  fa-file-pdf-o fa-lg' style='color:red' aria-hidden='true'></i></a></td>";

                        echo "<td align='left' class='letrap'> &nbsp; " . $aSucursal[$nSuc] . "</td>";
                        Display($aCps, $aDat, $rg);
                        if ($rg[capturaot] === "EC7063") {
                            echo "<td align='center' class='letrap'><a href=javascript:confirmar('Desea_continual_con_folio_$rg[id]','cotizaciones.php?CapturaOt=Si&rg=$rg[id]')><i style='color:#$rg[capturaot]'class='fa fa-circle fa-2x' aria-hidden='true'></i><a></td>";
                        } else {
                            echo "<td align='center' class='letrap'><a href=javascript:confirmar('¡¡¡El_folio_$rg[id]_ya_fue_transferido!!!','cotizaciones.php?busca=ini')><i style='color:#$rg[capturaot]'class='fa fa-circle fa-2x' aria-hidden='true'></i><a></td>";
                        }
                        echo "<td align='center' class='letrap'>$rg[paciented]</td>";

                        echo "</tr>";
                        $nRng++;
                    }
                    ?>
                </td>
            </tr>
        </table>

        <?php
        PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

        CuadroInferior($busca);
        ?>
    </body>
</html>
<?php
mysql_close();
?>
