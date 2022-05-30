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


$Titulo = "Asistencia por fechas  $_REQUEST[FechaI] al $_REQUEST[FechaF]";

/* $cSql = "update otd,ot,est set otd.precio=$_REQUEST[Lista] where ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'
  and ot.orden=otd.orden and otd.estudio=est.estudio";

  $lUp = mysql_query($cSql);
 */
$SL = "select emp.nombre,ent.fechae,sl.fechas from "
        . "( SELECT id,empleado,MIN(fecha) fechae,entrada,date_format(fecha,'%Y-%m-%d') fecha_entrada "
        . "FROM asistencia WHERE entrada = 'Entrada' GROUP BY empleado,date_format(fecha,'%Y-%m-%d')) ent "
        . "LEFT JOIN (SELECT id,empleado,MAX(fecha) fechas,entrada,date_format(fecha,'%Y-%m-%d') fecha_salida "
        . "FROM asistencia WHERE entrada = 'Salida' GROUP BY empleado,date_format(fecha,'%Y-%m-%d')) sl "
        . "ON sl.empleado=ent.empleado left join emp on ent.empleado=emp.id WHERE sl.fecha_salida=ent.fecha_entrada "
        . "AND ent.fecha_entrada >= '" . $_REQUEST["FechaIni"] . "' AND ent.fecha_entrada <= '" . $_REQUEST["FechaFin"] . "';";
$SqlA = mysql_query($SL);
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
            <title>Reporte de asistencia</title>
            <link href="css/reportes_estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <?php require ("./config_add.php"); ?>
            
    </head>
    <body topmargin="1">
        <table width="100%" border="0" id="Rep_menu">
            <tr class="fondo"> 
                <td><div id="logo"></div></td>
                <td>
                    <h3>Reporte analitos</h3>
                </td>
            </tr>
        </table>
        <form name='form1' method='get' action='reporteAsistencia.php'>
            <table id="table0" class="BusquedaReportes">
                <tr><td>Fecha Inicial: </td><td>
                        <input type='date' class='letrap'  name='FechaIni' value ='<?= $Fecha ?>'/>
                    </td>
                </tr>
                <tr><td>Fecha Final: </td><td>
                        <input type='date' class='letrap'  name='FechaFin' value ='<?= $Fecha ?>'/>
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="2" style="align-content: center;">
                        <input class="letrap" type='SUBMIT' value='Enviar'/>
                        <input type="hidden" name="Op" value="Busca">
                    </td>
                </tr>
            </table>
        </form>
        <table id="table1"align='center' width='100%' cellpadding='3' cellspacing='2'>
            <thead>
                <tr bgcolor='#5499C7'>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Nombre</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Fecha Entrada</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Fecha Salida</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rs = mysql_fetch_array($SqlA)) {
                    ($nRng % 2) > 0 ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;
                    ?>
                    <tr bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor = '#b7e7a7'; this.style.cursor = 'hand' onMouseOut=this.style.backgroundColor = '<?= $Fdo ?>'; >
                        <td class='letrap'><?= $rs["nombre"] ?></td>
                        <td class='letrap'><?= $rs["fechae"] ?></td>
                        <td class='letrap'><?= $rs["fechas"] ?> </td>
                    </tr>

                    <?php
                    $nRng++;
                }
                ?>
            </tbody>
        </table>
        <table width="95%" align="center" id="ImpReturn">
            <tr>
                <td align="left"><a id="Imprime" onClick="print()" class="edit"><i class="fa fa-print fa-3x" aria-hidden="true"></i></a></td>
                <td align="right">
                    <a href="reporteAsistencia.php" class="content5"  id="Regresa">
                        <i class="fa fa-reply fa-2x" title="Regresar a formulario" aria-hidden="true"></i> Regresar 
                    </a>
                </td>
            </tr>
        </table>
    </body>
    <script type="text/javascript">
        $("#table1").hide();
        $("#table2").hide();
        $("#ImpReturn").hide();
        if ("<?= $_REQUEST[Op] ?>" == "Busca") {
            $("#table0").hide();
            $("#table1").show();
            $("#table2").show();
            $("#ImpReturn").show();
        }
    </script>
</html>
<?php
mysql_close();
