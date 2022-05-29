<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];
if (isset($_REQUEST['FechaI']) && isset($_REQUEST['FechaF'])) {

    $FechaI = $_REQUEST['FechaI'];
    $FechaF = $_REQUEST['FechaF'];
} else {
    $fecha = new DateTime();
    $fecha->modify('first day of this month');
    $FechaIni = $fecha->format("Y-m-d");
    $fecha->modify('last day of this month');
    $FechaFin = $fecha->format('Y-m-d');
}
if ($_REQUEST["Comision"] === "Genera") {

    $Mes = substr($FechaI, 0, 7);
    $sql = "DELETE FROM cmc WHERE mes='$Mes' AND tm='A'";

    $lUp = mysql_query($sql);
    $sql = "SELECT ot.medico,ot.orden,ot.fecpago,otd.estudio,otd.descuento,otd.precio,
 	    (otd.precio*(1-(otd.descuento/100))) as importe,ot.cliente,
	    (importe*(med.comision/100)) as comisionA,med.comision,est.comision as estcomision,ot.institucion
            FROM ot,otd,med,est
	    WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.orden=otd.orden AND ot.medico=med.medico 
            AND otd.estudio=est.estudio AND med.comision > 0 AND otd.estudio<>'INF-AB' AND otd.estudio<>'TOMCOV' AND ot.medico<>'MD' AND ot.medico<>'AQ'";

    $CpoA = mysql_query($sql);
    $Medico = "XX";

    while ($Cpo = mysql_fetch_array($CpoA)) {

        if ($Cpo["medico"] <> $Medico OR $Cpo["orden"] <> $Orden) {

            if ($Medico <> 'XX') {
                $sql = "INSERT INTO cmc (medico,mes,orden,tm,fecha,concepto,importe,comision,
                        numestudios,cliente,inst) 
                        VALUES 
                        ('$Medico','$Mes','$Orden','A','$Fecha','$Estudios','$Importe','$Comision','$Num',
                        '$Cliente','$Inst')";

                $lUp = mysql_query($sql);
            }
            $Orden = $Cpo["orden"];
            $Medico = $Cpo["medico"];
            $Cliente = $Cpo["cliente"];
            $Inst = $Cpo["institucion"];
            $Estudios = "";
            $Importe = $Comision = $Num = 0;
            $Fecha = $Cpo["fecpago"];
        }

        $Num++;
        $Importe += $Cpo["importe"];
        $Estudios = $Estudios . ' ' . $Cpo["estudio"];
        if ($Cpo["estcomision"] > 0) {
            $Comision += $Cpo["importe"] * ($Cpo["estcomision"] / 100);
        } else {
            $Comision += $Cpo["importe"] * ($Cpo["comision"] / 100);
        }
    }

    header("Location: calcomisiones.php?cMes=$Mes&Msj='Calculo generado con Exito ($FechaIni - $FechaFin)!!!'");
}

#Variables comunes;
$CpoA = mysql_query("SELECT * FROM cli WHERE cliente = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Genera Comisión mensual ::..</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            $(document).ready(function () {
                var observaciones = "<?= $Msj ?>";
                if (observaciones != "") {
                    Swal.fire({
                        title: observaciones,
                        position: "top-right",
                        icon: "info",
                        toast: true,
                        timer: 3500,
                        showConfirmButton: false
                    })
                }
            });
        </script>
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>
        <br></br>
        <table width='45%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
            <tr style="background-color: #2c8e3c">
                <td class='letratitulo' align="center" colspan="2">
                    <h3>Generación de comisiónes</h3>
                </td>
            </tr>
            <tr style="height: 30px">
                <td colspan="2">
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width="100%">
                            <tr class="letrap">
                                <td width="50%"height="40px" align="right">Fecha Inicial :</td>
                                <td><input type="text" name="FechaI" class="letrap" value="<?= $FechaIni ?>"></input></td>
                            </tr>
                            <tr class="letrap">
                                <td height="40px" align="right">Fecha Final :</td>
                                <td><input type="text" name="FechaF" class="letrap" value="<?= $FechaFin ?>"></input></td>
                            </tr>
                            <tr align="center">
                                <td  height="30px" colspan="2">
                                    <input type="submit" name="Comision" value="Genera" class="letrap"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </body>
</html>
<?php
mysql_close();
?>
