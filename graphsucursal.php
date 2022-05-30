<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");
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

$FechaI = $_REQUEST["FechaI"] == "" ? date("Y-m-d", strtotime(date("Y-m-d") . "-15 days")) : $_REQUEST["FechaI"];
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
    $sig .= "" . $rs["sucursal1"] . ",";
    $sig1 .= "" . $rs["sucursal2"] . ",";
    $sig2 .= "" . $rs["sucursal3"] . ",";
    $sig3 .= "" . $rs["sucursal4"] . ",";
    $sig4 .= "" . $rs["sucursal5"] . "," ;
    $sig5 .= "" . $rs["sucursal6"] . ",";
}




$gsql2 = "SELECT sum(ot.importe) ImporteCaja, ot.fecha, ot.suc FROM ot
 WHERE  ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' GROUP BY ot.fecha ORDER BY ot.fecha ";


//echo $gsql;
$graf1 = mysql_query($gsql2);


while ($rs1 = mysql_fetch_array($graf1)) {

    $ds .= "'" . $rs1["fecha"] . "',";

}

$link = conectarse();
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
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
        <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>     

        <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?> 
        <h2 style="color: #5c6773"> <i style="color:#58D68D" class="fa fa-bar-chart fa-1x" aria-hidden="true"></i> Grafica de ingresos por Sucursales <?= $FechaI ?> al dia <?= $FechaF ?></h2>
        <table width="100%" style="border-radius: 2px 2px 2px 2px;"><tr bgcolor="#CCD1D1"><td>
        <canvas id="myChart" width="1000" height="200"></canvas></td></tr></table>
        <form name='frmfiltro' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <table width="100%" class="letrap">
                <tr>
                    <td>
                        Fecha Inicial: <input name="FechaI" value="<?= $FechaI ?>" class="letrap" type="text"><img src="lib/calendar.png" border="0" height="28" onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)></input>

                        Fecha Final: <input name="FechaF" id="FechaF" class="letrap" value="<?= $FechaF ?>" type="text"><img src="lib/calendar.png" border="0" height="28" onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></input>
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
                labels: [<?= $ds?>],
                
                datasets: [{

                        label: 'Matriz',
                        data: [<?= $sig?>],

                        backgroundColor: ['rgba(255, 99, 132, 0.4)'],
                        borderColor: ['rgba(255, 99, 132, 1)'],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'Futura',
                        data: [<?= $sig1?>],

                        backgroundColor: ['rgba(54, 162, 235, 0.4)'],
                        borderColor: ['rgba(54, 162, 235, 1)'],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'Tepexpan',
                        data: [<?= $sig2?>],

                        backgroundColor: ['rgba(255, 206, 86, 0.4)',],
                        borderColor: ['rgba(255, 206, 86, 1)',],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'Reyes',
                        data: [<?= $sig3?>],

                        backgroundColor: ['rgba(75, 192, 192, 0.4)',],
                        borderColor: ['rgba(75, 192, 192, 1)',],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'Camarones',
                        data: [<?= $sig4?>],

                        backgroundColor: ['rgba(153, 102, 255, 0.4)',],
                        borderColor: ['rgba(153, 102, 255, 1)',],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'San Vicente',
                        data: [<?= $sig5?>],

                        backgroundColor: ['rgba(255, 159, 64, 0.4)'],
                        borderColor: ['rgba(255, 159, 64, 1)'],
                        borderWidth: 4
                    }]
            },
               options: {
                scales: {
                    Y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


</html>
<?php
mysql_close();
