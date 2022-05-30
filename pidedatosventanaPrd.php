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

/* $cSql = "update otd,ot,est set otd.precio=$_REQUEST[Lista] where ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'
  and ot.orden=otd.orden and otd.estudio=est.estudio";

  $lUp = mysql_query($cSql);
 */
$SqlA = mysql_query("SELECT orden FROM ot WHERE ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'");

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
        });
    </script>

    <body topmargin="1">
        <h2 align="center">Reporte de  Productividad </h2>
        <a href=javascript:close();><i class="fa fa-window-close" style="color:red;" aria-hidden="true"></i></a>
        <form name='form1' method='get' action='<?= $_SERVER['PHP_SELF'] ?>'>
            <table id="table0" width='85%'align='center' class='letrasubt' border='0' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                <tr>
                    <td align='right'>Fecha Inicial: </td>
                    <td><input type='date' class='letrap'  name='FechaIni' value ='<?= $Fecha ?>'/></td>
                    <td align='right'>Fecha Final: </td>
                    <td><input type='date' class='letrap'  name='FechaFin' value ='<?= $Fecha ?>'/></td>
                </tr>
                <tr><td align='right'>De sucursal : </td>
                    <td class='letrap' colspan="3">
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
        $SucursalDe = $_REQUEST["DeSucursal"] == "*" ? "" : "and ot.suc=" . $_REQUEST["DeSucursal"];
        $Personal2 = ($_REQUEST["Personal"] == "") ? "" : "and maqdet.usrrec='" . $_REQUEST["Personal"] . "'";
        $cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mint,maqdet.fenv,maqdet.henv,maqdet.usrrec,maqdet.obsrec,ot.orden as ord,"
                . "ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion "
                . "FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente "
                . "and maqdet.frec>='" . $_REQUEST["FechaIni"] . "' and maqdet.frec <='" . $_REQUEST["FechaFin"] . "' "
                . "$SucursalDe $Personal2 order by maqdet.orden";
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
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Personal</strong></td>
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
                        <td class='letrap'><?= $rs["obsrec"] ?></td>
                        <td class='letrap'><?= $rs["usrrec"] ?></td>
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
        <table width="95%">
            <tr >
                <td>
                    <a class="edit" href="javascript:window.print()"><i class="fa fa-print fa-2x" aria-hidden="true"></i> Imprimir</a>
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
<?php
mysql_close();
?>

