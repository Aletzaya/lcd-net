<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");
$link = conectarse();

if (isset($_REQUEST[Mnu])) {
    $_SESSION[Usr][5] = $_REQUEST[Mnu];
}

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

$FechaI = $_REQUEST["FechaI"] == "" ? date("Y-m-d", strtotime(date("Y-m-d") . "-7 days")) : $_REQUEST["FechaI"];
$FechaF = $_REQUEST["FechaF"] == "" ? date("Y-m-d") : $_REQUEST["FechaF"];


$Sql = "SELECT ot.fecha,
ROUND(IFNULL(ot1.ImporteCaja1,0),2) sucursal1,
ROUND(IFNULL(ot2.ImporteCaja2,0),2) sucursal2,
ROUND(IFNULL(ot3.ImporteCaja3,0),2) sucursal3,
ROUND(IFNULL(ot4.ImporteCaja4,0),2) sucursal4, 
ROUND(IFNULL(ot5.ImporteCaja5,0),2) sucursal5,
ROUND(IFNULL(ot6.ImporteCaja6,0),2) sucursal6 
FROM ot LEFT JOIN 
(SELECT sum(ot.importe) ImporteCaja1,ot.fecha,ot.suc FROM ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND suc = 1 group by ot.fecha) ot1 ON ot1.fecha = ot.fecha LEFT JOIN
(SELECT sum(ot.importe) ImporteCaja2,ot.fecha,ot.suc FROM ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND suc = 2 group by ot.fecha) ot2 ON ot2.fecha = ot.fecha LEFT JOIN
(SELECT sum(ot.importe) ImporteCaja3,ot.fecha,ot.suc FROM ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND suc = 3 group by ot.fecha) ot3 ON ot3.fecha = ot.fecha LEFT JOIN
(SELECT sum(ot.importe) ImporteCaja4,ot.fecha,ot.suc FROM ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND suc = 4  group by ot.fecha) ot4 ON ot4.fecha = ot.fecha LEFT JOIN
(SELECT sum(ot.importe) ImporteCaja5,ot.fecha,ot.suc FROM ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND suc = 5  group by ot.fecha) ot5 ON ot5.fecha = ot.fecha LEFT JOIN
(SELECT sum(ot.importe) ImporteCaja6,ot.fecha,ot.suc FROM ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND suc = 6  group by ot.fecha) ot6 ON ot6.fecha = ot.fecha
WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' GROUP BY ot.fecha ORDER BY ot.fecha asc";

$QSql = mysql_query($Sql);
while ($rs = mysql_fetch_array($QSql)) {
    $sig .= "" . $rs["sucursal1"] . "," . $rs["sucursal2"] . "," . $rs["sucursal3"] . "," . $rs["sucursal4"] . "," . $rs["sucursal5"] . "," . $rs["sucursal6"] . ",";
    $header .= "'','','" . $rs["fecha"] . "','','', '',";
    $hedaerdob .= "'Matriz','OHT','Tepexpan','Reyes','Camarones', 'SanVic'";
}

$Titulo = "Menu de inicio";
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Grafica ::..</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
        <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?> 
        <h2 style="color: #5c6773"> <i style="color:#58D68D" class="fa fa-bar-chart fa-1x" aria-hidden="true"></i> Grafica de ingresos por Sucursales <?= $FechaI ?> al dia <?= $FechaF ?></h2>
        <table class="letrap"><tr><td>
                    <i class="fa fa-circle" style="color:rgba(236, 112, 99, .8)" aria-hidden="true"></i> Matriz
                    <i class="fa fa-circle" style="color:rgba(165, 105, 189, .8)" aria-hidden="true"></i> OHT 
                    <i class="fa fa-circle" style="color:rgba(72, 201, 176, .8)" aria-hidden="true"></i> Tepexpan
                    <i class="fa fa-circle" style="color:rgba(244, 208, 63, .8)" aria-hidden="true"></i> Reyes
                    <i class="fa fa-circle" style="color:rgba(211, 84, 0, .8)" aria-hidden="true"></i> Camarones
                    <i class="fa fa-circle" style="color:rgba(86, 101, 115, .8)" aria-hidden="true"></i> San Vicente Ch.
                </td></tr>
        </table>
        <table width="100%" style="border-radius: 2px 2px 2px 2px;"><tr bgcolor="#CCD1D1"><td>
                    <canvas id="myChart" width="1000" height="200"></canvas></td></tr></table>
        <form name='frmfiltro' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <table width="100%" class="letrap">
                <tr>
                    <td>
                        Fecha Inicial: <input name="FechaI" id="FechaI" value="<?= $FechaI ?>" class="letrap" type="text"></input>
                        Fecha Final: <input name="FechaF" id="FechaF" class="letrap" value="<?= $FechaF ?>" type="text"></input>
                        <input type="submit" name="Boton" value="Buscar" class="letrap"></input>
                    </td>
                </tr>
            </table>
        </form>
    </body>
    <script src="./controladores.js"></script>


    <script>
        $("#Departamento").val("<?= $_REQUEST["Departamento"] ?>");

        var ctx = document.getElementById('myChart');

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= $header ?>],
                datasets: [{
                        data: [<?= $sig ?>],
                        backgroundColor: [
                            'rgba(236, 112, 99, 0.2)',
                            'rgba(165, 105, 189, 0.2)',
                            'rgba(72, 201, 176, 0.2)',
                            'rgba(244, 208, 63, 0.2)',
                            'rgba(211, 84, 0, 0.2)',
                            'rgba(86, 101, 115 , 0.2)'
                        ],
                        borderColor: [
                            'rgba(236, 112, 99, 1)',
                            'rgba(165, 105, 189, 1)',
                            'rgba(72, 201, 176, 1)',
                            'rgba(244, 208, 63, 1)',
                            'rgba(211, 84, 0, 1)',
                            'rgba(86, 101, 115 , 1)'
                        ],
                        borderWidth: 1
                    }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            },
        });
        myChart.defaults.global.legend.display = false;
    </script>


</html>
<?php
mysql_close();
