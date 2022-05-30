<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
date_default_timezone_set("America/Mexico_City");

$link = conectarse();

if (isset($_REQUEST["busca"])) {

    if ($_REQUEST["busca"] == "ini") {

        $Fecha = date("Y-m-d");

        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', 'menu.php', $Fecha, $Fecha, '*', '');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST["busca"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST["busca"];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST["pagina"])) {
    $_SESSION['OnToy'][1] = $_REQUEST["pagina"];
}
if (isset($_REQUEST["orden"])) {
    $_SESSION['OnToy'][2] = $_REQUEST["orden"];
}
if (isset($_REQUEST["Sort"])) {
    $_SESSION['OnToy'][3] = $_REQUEST["Sort"];
}
if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][5] = $_REQUEST["FechaI"];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["FechaF"];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST["FiltroCia"])) {
    $_SESSION['OnToy'][7] = $_REQUEST["FiltroCia"];
}
if (isset($_REQUEST[Todo])) {
    $_SESSION['OnToy'][8] = $_REQUEST["Todo"];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$busca = strtolower($busca);
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$FechaI = $_SESSION["OnToy"][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION["OnToy"][6];          //Pagina a la que regresa con parametros  
$GnSuc = $_SESSION["OnToy"][7];          //Que sucursal estoy checando
$GTodo = $_SESSION["OnToy"][8];          //Todos

$Retornar = "<a href=" . $_SESSION["OnToy"][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

#Variables comunes;
$Titulo = "Avance por orden";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 6;             //Numero de query dentro de la base de datos

#Intruccion a realizar si es que mandan algun proceso
if ($op == 'Si') {                    //Elimina rg

    $lUp = mysql_query("DELETE FROM ot WHERE orden='$_REQUEST[cId]' limit 1");
    $Msj = "Registro eliminado";
    //$Msj = "Opcion deshabilitada";
} elseif ($op == 'rs') {
    $Up = mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
    $op = '';
}

if ($GnSuc == "*") {
    $Sucursal = "";
} else {
    $Sucursal = " and ot.suc='$GnSuc'";
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 6) {
    $Dsp = 'Filtro activo';
}

$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " cli.nombrec like '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " and cli.nombrec like '%$P[$i]%' ";
        }
    }
    //$Suc='*';
} else {
    $BusInt = " cli.nombrec like '%$busca%' ";
    // $Suc='*';
}


if ($busca == '') {

    $SqlB3 = "SELECT ot.orden
    FROM ot
    where ot.fecha='$FechaI' limit 1";

    $Sql3 = mysql_query($SqlB3, $link);

    $S3 = mysql_fetch_array($Sql3);


    $SqlB4 = "SELECT ot.orden
    FROM ot
    where ot.fecha='$FechaF' order by orden desc limit 1";

    $Sql4 = mysql_query($SqlB4, $link);

    $S4 = mysql_fetch_array($Sql4);

    $cSql = "SELECT $Qry[campos],cli.numveces,ot.suc,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,IF (ot.pagada = 'No','F5B7B1','') statuspago,cli.cliente
    FROM ot
    INNER JOIN cli on cli.cliente=ot.cliente
    WHERE  ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $Sucursal";
} elseif ($busca < 'a') {

    $cSql = "SELECT $Qry[campos],cli.numveces,ot.suc,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,IF (ot.pagada = 'No','F5B7B1','') statuspago,cli.cliente
    FROM ot
    INNER JOIN cli on cli.cliente=ot.cliente
    WHERE ot.orden >= '$busca'";
} else {

    $cSql = "SELECT $Qry[campos],cli.numveces,ot.suc,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,IF (ot.pagada = 'No','F5B7B1','') statuspago,cli.cliente
    FROM ot
    INNER JOIN cli on cli.cliente=ot.cliente
    WHERE $BusInt";
}

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "-", "-", "", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("Status", "", "", "Recep", "", "","Entrega", "", "","Pagado", "", "","Correos", "", "","Sucursal", "", "","Visitas", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Concentrado de ordenes</title>
            <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="js/jquery-1.8.2.min.js"></script>
            <script src="jquery-ui/jquery-ui.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script type="text/javascript">
                function() {
                $("#accordion").accordion();
                }
                function() {
                $("#tabs").tabs();
                }

            </script>
    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    PonTitulo($Titulo);
    menu($Gmenu, $Gusr);
    ?>
    <script src="./controladores.js"></script>
<?php
    ?>
    <br>
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
            <?php
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
            ?>
            <span class='content1'> Fec.inicial: </span>
            <input type='text' name='FechaI' value='<?= $FechaI ?>' size='10' class='content5'/>
            <i class='fa fa-calendar fa-lg' aria-hidden='true' style='color:green' onclick=displayCalendar(document.forms[0].FechaI, 'yyyy-mm-dd', this)></i> 
            <span class='content1'> Fec.final: </span>
            <input type='text' name='FechaF' value='<?= $FechaF ?>' size='10' class='content5'/>
            <i class='fa fa-calendar fa-lg' aria-hidden='true' style='color:green' onclick=displayCalendar(document.forms[0].FechaI, 'yyyy-mm-dd', this)></i> 
            &nbsp; <input type='submit' name='Boton' value='enviar' class='letrap'/>
            &nbsp; &nbsp; <a  class='edit' href='?Todo=*'><img src='images/rest.png'> Ver todo </a> 

        </form>

        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
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

                        $nSuc = $rg[suc];
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        $Sucursal = $aSucursal[$nSuc];
                        if ($rg[statuspago]) {
                            $Fdo = $rg[statuspago];
                        }
                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor = '#b7e7a7';this.style.cursor = 'hand' onMouseOut=this.style.backgroundColor = '$Fdo';>";
                        //echo "<tr>";
                        echo "<td onclick='subMenu($rg[orden])'class='edit center' align='center'><a class='edit'>Detalle</a></td>";

                        echo "<td align='left' class='letrap center'><a class='letra' href=javascript:wingral('impot.php?busca=$rg[orden]')><i class='fa fa-print fa-lg' style='color:#3374FF' aria-hidden='true'></i></a></td>";

                        Display($aCps, $aDat, $rg);

                        if ($Nivel >= 7) {
                            echo "<td class='Seleccionar center' align='center'><a class='edit' href='historico.php?op=bc&busca=$rg[id]'>H.clinico</a></td>";
                        } else {
                            echo "<td class='Seleccionar center' align='center'> - </td>";
                        }

                         if ($rg[encaja] == 'Si' OR $rg[status] == 'Entregada') {
                            echo "<td align='center'><a href=javascript:winuni('ordenrec.php?Recibeencaja=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></a></td>";
                        } else {
                            echo "<td align='center'><a href=javascript:winuni('ordenrec.php?Recibeencaja=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-times' style='color:red;' aria-hidden='true'></i></a></td>";
                        }

                        if ($rg[status] <> 'Entregada') {
                            echo "<td align='center'><a href=javascript:winmed('ordenpac.php?Entregapac=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-times' style='color:red;' aria-hidden='true'></i></a></td>";
                        } else {
                            echo "<td align='center'><a href=javascript:winmed('ordenpac.php?Entregapac=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></a></td>";
                        }
                        if ($rg[pagada] == 'Si') {
                            echo "<td><a class='edit' style='color:green;' aria-hidden='true'>$rg[pagada]</a></td>";
                        } else {
                            echo "<td><a class='edit'  style='color:red;' aria-hidden='true' href=javascript:winuni('ingreso2.php?busca=$rg[orden]&boton=Enviar')><b>$rg[pagada]</b></td>";
                        }
                        if ($rg[entemailpac] == '1' or $rg[entemailmed] == '1' or $rg[entemailinst] == '1') {
                            echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-envelope-o' aria-hidden='true'></i></a></td>";
                        } else {
                            echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')>$Gfont No</a></td>";
                        }
                        echo "<td class='content1'>$Sucursal</td>";
                        echo "<td class='letrap'><a class='content1' href=javascript:winuni('repots.php?busca=$rg[cliente]')><b>$rg[numveces]</b></a></td>";
                        

                        //echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');>Eliminar</a></td>";

                        echo "</tr>";

                        $nRng++;
                    }
                    ?>

                </td>
            </tr>
        </table>
        <script type="text/javascript">
            function subMenu(orden){
            $('#contenedor').load('phpsqlajax.php?Orden=' + orden, function (response, status, xhr) {
            if (status === "error") {
            var msg = "Sorry but there was an error: ";
            }});
            }
        </script>
        
        <div id="contenedor"></div>

        <?php
        PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

        CuadroInferior2($busca);


        echo '</body>';
        ?>

</html>
<?php
mysql_close();