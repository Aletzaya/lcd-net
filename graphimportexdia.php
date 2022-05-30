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


$gsql = "SELECT sum(ot.importe) ImporteCaja, ot.fecha FROM ot WHERE ot.fecha AND ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' GROUP BY ot.fecha;";
//echo $gsql;
$graf = mysql_query($gsql);

while ($rs = mysql_fetch_array($graf)) {
    $dt .= "" . $rs["ImporteCaja"] . ",";
    $ds .= "'" . $rs["fecha"] . "',";
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
        <h2 style="color: #5c6773"> <i style="color:#58D68D" class="fa fa-bar-chart fa-1x" aria-hidden="true"></i> Grafica Corte de Caja X Dia <?= $FechaI ?> al dia <?= $FechaF ?></h2>
        <table width="100%" style="border-radius: 2px 2px 2px 2px;"><tr bgcolor="#CCD1D1"><td>
        <canvas id="myChart" width="1000" height="200"></canvas></td></tr></table>
        <form name='frmfiltro' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <table width="100%" class="letrap">
                <tr>
                    <td>
                        Fecha Inicial: <input name="FechaI" id="FechaI" value="<?= $FechaI ?>" class="letrap" type="text"><img src="lib/calendar.png" border="0" height="28" onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)></input>
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
                labels: [<?= $ds ?>],
                datasets: [{
                        label: '$ Importe de ingreso diario',
                        data: [<?= $dt ?>],
                        backgroundColor: [


                            'rgba(75, 192, 192, 0.4)',
                            'rgba(255, 159, 64, 0.4)',
                            'rgba(255, 206, 86, 0.4)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 3
                    }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</html>
<?php
mysql_close();
