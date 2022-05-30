<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

$Menu = $_REQUEST[Menu];


$Titulo = "Relacion Estudios por Depto $_REQUEST[FechaI] al $_REQUEST[FechaF]";


$Fechai = $_REQUEST[FechaIni];

$Fechaf = $_REQUEST[FechaFin];

$HoraI = $_REQUEST[HoraIni];

$HoraF = $_REQUEST[HoraFin];

$Titulo = $_REQUEST[Titulo];

$Generapago = $_REQUEST[generapago];

$Fecha = date("Y-m-d");

$Hora = date("H:i");

//$Fechai = date("Y-m-d H:i");

$SucursalD = $_REQUEST[DeSucursal];

if ($SucursalD == "*") {
    $SucursalDe = "";
} else {
    $SucursalDe = "and ot.suc=$SucursalD";
}

$SucursalP = $_REQUEST[Proveedor];

if ($SucursalP == "*") {
    $SucursalPara = "";
} else {
    $SucursalPara = "and maqdet.mext='$SucursalP'";
}

$personal = $_REQUEST[Personal];

if ($personal == "*") {
    $personale = "";
} else {
    $personale = "and maqdet.usrenvext='$personal'";
}

if ($Generapago == 'Si') {
    $SucursalP = $SucursalP === "*" ? 0 : $SucursalP;
    $Inst = 'FecI=' . $Fechai . '&FecF=' . $Fechaf . '&HoraI=' . $HoraI . '&HoraF=' . $HoraF . '&SucursalDe=' . $SucursalD . '&Proveedor=' . $SucursalP . '&personal=' . $personal;
    $Sql = "INSERT INTO generapago (instruccion,fecha,usr,cancel,proveedor)
      VALUES
      ('$Inst','" . $_REQUEST["FecI"] . "','$Gusr','','$SucursalP')";
    //echo $Sql;
    if (!$rs = mysql_query($Sql)) {
        echo "Error en SQL :" . $Sql . " Error .- " . mysql_error();
    }
}

$cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,maqdet.usrenvext
FROM maqdet, ot, cli, est
WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$Fechai' and
maqdet.fenvext <='$Fechaf' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale
order by maqdet.orden";

$SqlA = mysql_query($cSql);

require("lib/lib.php");

$link = conectarse();

$Titulo = "Menu de opciones multiples";

$FechaI = date("Y-m-d");
$FechaF = date("Y-m-d");
$Fecha = date("Y-m-d");

require ("config.php");
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Documento sin título</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <script>
        $(document).ready(function () {
            $("#HoraIni").val("07:00");
            $("#HoraFin").val("14:00");
            $("#Personal").val("*");
        });
    </script>

    <body topmargin="1">
        <h2 align="center">Reporte de envío externo por rango de fechas </h2>
        <a href=javascript:close();><i class="fa fa-window-close" style="color:red;" aria-hidden="true"></i></a>
        <form name='form1' method='get' action='<?= $_SERVER['PHP_SELF'] ?>'>
            <table id="table0" width='85%'align='center' class='letrasubt' border='0' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                <tr>
                    <td align='right'>Fecha Inicial: </td>
                    <td><input type='date' class='letrap'  name='FechaIni' value ='<?= $Fecha ?>'/></td>
                    <td align='right'>Fecha Final: </td>
                    <td><input type='date' class='letrap'  name='FechaFin' value ='<?= $Fecha ?>'/></td>
                </tr>
                <tr>
                    <td align='right'>Hora Inicial: </td>
                    <td><input type='time' class='letrap'  name='HoraIni' id="HoraIni" value ='<?= $Fecha ?>'/></td>
                    <td align='right'>Hora Final: </td>
                    <td><input type='time' class='letrap'  name='HoraFin' id="HoraFin" value ='<?= $Fecha ?>'/></td>
                </tr>
                <tr><td align='right'>De sucursal : </td>
                    <td class='letrap'>
                        <select class="letrap" name='DeSucursal'>
                            <?php
                            $CiaA = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 AND id < 50 ORDER BY id");
                            echo "<option value='*'> * | T o d a s </option>";
                            while ($Cia = mysql_fetch_array($CiaA)) {
                                echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td align='right'>Para Proveedor : </td>
                    <td>
                        <select class="letrap" name='Proveedor'>
                            <option value='*'> *  T o d o s </option>
                            <?php
                            $SucB = mysql_query("SELECT id,alias FROM mql ORDER BY id");
                            while ($Sucp = mysql_fetch_array($SucB)) {
                                echo "<option value='$Sucp[id]'>$Sucp[id] - $Sucp[alias]</option>";
                            }
                            ?>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td align='right'>Personal: </td>
                    <td colspan="3"><input type='text' class='letrap'  name='Personal' id="Personal"/></td>
                </tr>
                <tr align="center">
                    <td colspan="4" style="align-content: center;">
                        <input class="letrap" type='SUBMIT' value='Enviar'/> &nbsp;&nbsp;&nbsp;
                        <input type="hidden" name="Op" value="Busca">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        $SucursalPara = ($_REQUEST["Proveedor"] == "*") ? "" : "and maqdet.mext='" . $_REQUEST["Proveedor"] . "'";
        $SucursalDe = $_REQUEST["DeSucursal"] == "*" ? "" : "and ot.suc=" . $_REQUEST["DeSucursal"];
        $personale = ($_REQUEST["Personal"] == "*" || $_REQUEST["Personal"] == "") ? "" : "and maqdet.usrenvext='" . $_REQUEST["Personal"] . "'";
        $cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,maqdet.usrenvext "
                . "FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio "
                . "AND ot.cliente = cli.cliente and maqdet.fenvext>='" . $_REQUEST["FechaFin"] . "' and "
                . "maqdet.fenvext <='" . $_REQUEST["FechaFin"] . "' AND maqdet.henvext >='" . $_REQUEST["HoraIni"] . "' "
                . "AND maqdet.henvext <='" . $_REQUEST["HoraFin"] . "' $SucursalDe $SucursalPara $personale "
                . "order by maqdet.orden";
        //echo $cSql;
        $Qry = mysql_query($cSql);
        ?>
        <table id="table1"align='center' width='90%' cellpadding='3' cellspacing='2'>
            <thead>
                <tr bgcolor='#5499C7'>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Suc</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Orden</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Paciente</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Estudios</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Descripcion</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Observaciones</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Para</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Usr</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rs = mysql_fetch_array($Qry)) {
                    ($nRng % 2) > 0 ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;
                    ?>
                    <tr bgcolor='<?= $Fdo ?>'>
                        <td class='letrap'><?= $rs["suc"] ?> </td>
                        <td class='letrap'><?= $rs["institucion"] . "-" . $rs["orden"] ?></td>
                        <td class='letrap' align='right'><?= $rs["nombrec"] ?></td>
                        <td class='letrap'><?= $rs["estudio"] ?></td>
                        <td class='letrap'><?= $rs["descripcion"] ?></td>
                        <td class='letrap'><?= $rs["obsenv"] ?></td>
                        <td class='letrap'><?= $rs["alias"] ?></td>
                        <td class='letrap'><?= $rs["usrenvext"] ?></td>
                    </tr>
                    <?php
                    $sumCajaMSob += $rs["ImporteCaja"] - $rs["Sobrante"];
                    $sumCaja += $rs["ImporteCaja"];
                    $sumEfectivo += $rs["CjaImporteEfectivo"];
                    $sumTarjeta += $rs["CjaImporteTarjeta"];
                    $sumTranferencia += $rs["CjaImporteTransferencia"];
                    $nRng++;
                    $Estudios++;
                }
                ?>
            </tbody>
            <tfoot>
                <tr bgcolor="#FFBBB4" class="letrap">
                    <td colspan="7"> No. de estudios: <?= $Estudios ?></td>
                </tr>
            </tfoot>
        </table>
        <br></br>
        <table class="letrap tablas2" align='center' width='98%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
                <td align='center'>Elaborado por:</td>
                <td align='center'>_________________________________</td>
                <td align='left'></td>
                <td align='center'>Mensajero:</td>	
                <td align='center'>_________________________________</td>
                <td align='center'></td>
                <td align='center'>Recibido por:</td>
                <td align='center'>_________________________________</td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <?php $var = "?FecI=" . $_REQUEST["FechaIni"] . "&FecF=" . $_REQUEST["FechaFin"] . "&HoraI=" . $_REQUEST["HoraIni"] . "&HoraF=" . $_REQUEST["HoraFin"] . "&SucursalDe=" . $_REQUEST["DeSucursal"] . "&Proveedor=" . $_REQUEST["Proveedor"] . "&personal=" . $_REQUEST["Personal"]; ?>
                <td align="center"><a href=javascript:winmed2('repenvioextpdf.php<?= $var ?>')><img src='lib/logoorthin.jpg' alt='pdf' width='80' border='0'></a></td>
                <td align="center"><a href=javascript:winmed2('repenvioextpdfeli.php<?= $var ?>')><img src='lib/logoeli.jpg' alt='pdf' width='180' border='0'></a> </td>
                <td align="center"><a class="edit" href='pidedatosventanaExt.php<?= $var ?>&generapago=Si'> ( Genera Envio para Pago )</a></td>
                <td align="center"><a href=javascript:winmed2('repenvioextpdf2.php<?= $var ?>')><img src='lib/logoorthin.jpg' alt='pdf' width='80' border='0'></a> </td>
                <td align="center"><a href=javascript:winmed2('repenvioextpdfcilab.php<?= $var ?>')><img src='lib/cilab.jpg' alt='pdf' width='80' border='0'></a></td>
            </tr>
        </table>
        <br></br>
        <table width="95%">
            <tr >
                <td>
                <a class="edit" href="pidedatosventanaextpdf.php?FechaIni=<?= $Fechai ?>&FechaFin=<?= $Fechaf ?>&HoraIni= <?=$HoraI ?>&HoraFin=<?= $HoraF ?>&DeSucursal=<?= $SucursalD ?>&Proveedor=<?= $SucursalP ?>&Personal=<?= $personal ?>"><i class="fa fa-print fa-2x" aria-hidden="true"></i> Imprimir</a>
                </td>
                <td align="right"><a href="<?= $_SERVER['PHP_SELF'] ?>" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a></td>
            </tr>
        </table>
    </body>
    <script type="text/javascript">
        $("#table1").hide();
        $(".tablas2").hide();
        if ("<?= $_REQUEST[Op] ?>" == "Busca") {
            $("#table0").hide();
            $(".tablas2").show();
            $("#table1").show();
        }
    </script>
</html>