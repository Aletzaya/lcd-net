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
            <title>Concentrado Caja</title>
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

    <body topmargin="1">
        <h2 align="center">Reporte de caja por rango de fechas </h2>
        <a href=javascript:close();></a><i class="fa fa-window-close" style="color:red;" aria-hidden="true"></i>
        <form name='form1' method='get' action='concentradoCaja.php'>
            <table id="table0" width='85%'align='center' class='letrasubt' border='0' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                <tr><td align='right' width='50%'>Fecha Inicial: </td><td>
                        <input type='date' class='letrap'  name='FechaIni' value ='<?= $Fecha ?>'/>
                    </td>
                </tr>
                <tr><td align='right' width='50%'>Fecha Final: </td><td>
                        <input type='date' class='letrap'  name='FechaFin' value ='<?= $Fecha ?>'/>
                    </td>
                </tr>
                <tr><td align='right'>Sucursal : </td>
                    <td class='letrap'>
                        <input type='checkbox' value='1' name='sucursalt' checked/>* Todos <br/>
                        <input type='checkbox' value='1' name='sucursal0'/>0 Administracion <br/>
                        <input type='checkbox' value='1' name='sucursal1'/>1 Matriz <br/>
                        <input type='checkbox' value='1' name='sucursal2'/>2 Hospital Futura <br/>
                        <input type='checkbox' value='1' name='sucursal3'/>3 Tepexpan <br/>
                        <input type='checkbox' value='1' name='sucursal4'/>4 Los Reyes <br/>
                        <input type='checkbox' value='1' name='sucursal5'/>5 Camarones <br/>
                        <input type='checkbox' value='1' name='sucursal6'/>6 San Vicente <br/>
                    </td>
                </tr>
                <tr height='30'>
                    <td align='right'>
                        Institucion : 
                    </td><td> 
                        <?php $InsA = mysql_query("select institucion,nombre from inst"); ?>
                        <select name='Institucion' class='letrap'>
                            <option value='*'> *  T o d o s </option>
                            <option value='LCD'>* INSTITUCIONES - LCD *</option>
                            <option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>
                            <?php
                            while ($Ins = mysql_fetch_array($InsA)) {
                                echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
                            }
                            ?>
                            <option selected value='*'> * T o d o s </option>
                        </select>
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="2" style="align-content: center;">
                        <input class="letrap" type='SUBMIT' value='Enviar'/> &nbsp;&nbsp;&nbsp;
                        <input class="letrap" type='SUBMIT' name='Completo' value='Dia_Completo'/>
                        <input type="hidden" name="Op" value="Busca">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        $Sql = "
            SELECT sum(cja.importe) ImporteCaja,sum(ot.importe)ImporteOT,cja.usuario ,tpago2.CjaImporteEfectivo, 
            tpago2.OtImporteEfectivo ,tpago3.CjaImporteTarjeta, tpago3.OtImporteTarjeta,tpago4.CjaImporteTransferencia, 
            tpago4.OtImporteTransferencia,tpago5.Sobrante  
                FROM cja 
                LEFT JOIN 
                ot ON ot.orden=cja.orden 
                LEFT JOIN 
                        (SELECT sum(cjaimp.importe) CjaImporteEfectivo,cjaimp.tpago,sum(otimp.importe) OtImporteEfectivo,cjaimp.usuario
                                FROM cja cjaimp
                                LEFT JOIN ot otimp ON otimp.orden=cjaimp.orden 
                                WHERE cjaimp.tpago='Efectivo' AND cjaimp.fecha >= '$_REQUEST[FechaIni]' 
                        AND cjaimp.fecha <= '$_REQUEST[FechaFin]' GROUP BY cjaimp.usuario,cjaimp.tpago
                        ) tpago2 
                ON tpago2.usuario = cja.usuario 
                LEFT JOIN 
                        (SELECT sum(cjatar.importe) CjaImporteTarjeta,cjatar.tpago,sum(ottar.importe) OtImporteTarjeta,cjatar.usuario
                                FROM cja cjatar
                                LEFT JOIN ot ottar ON ottar.orden=cjatar.orden 
                                WHERE cjatar.tpago='Tarjeta' AND cjatar.fecha >= '$_REQUEST[FechaIni]' 
                        AND cjatar.fecha <= '$_REQUEST[FechaFin]' GROUP BY cjatar.usuario,cjatar.tpago
                        ) tpago3 
                ON tpago3.usuario = cja.usuario 
                LEFT JOIN 
                        (SELECT sum(cjatrans.importe) CjaImporteTransferencia,cjatrans.tpago,sum(otttrans.importe) OtImporteTransferencia,cjatrans.usuario
                                FROM cja cjatrans
                                LEFT JOIN ot otttrans ON otttrans.orden=cjatrans.orden 
                                WHERE cjatrans.tpago='Transferencia' AND cjatrans.fecha >= '$_REQUEST[FechaIni]' 
                        AND cjatrans.fecha <= '$_REQUEST[FechaFin]' GROUP BY cjatrans.usuario,cjatrans.tpago
                        ) tpago4 
                ON tpago4.usuario = cja.usuario 
                LEFT JOIN 
                        (SELECT sum(cjasob.importe) Sobrante,cjasob.usuario FROM cja cjasob LEFT JOIN ot otsob ON cjasob.orden = otsob.orden 
                        WHERE cjasob.fecha ='$_REQUEST[FechaFin]' 
                        AND otsob.fecha <> '$_REQUEST[FechaFin]' GROUP BY cjasob.usuario
                        ) tpago5 
                ON tpago5.usuario = cja.usuario 
                WHERE cja.fecha AND cja.fecha >= '$_REQUEST[FechaIni]' AND cja.fecha <= '$_REQUEST[FechaFin]' GROUP BY cja.usuario;";
        $Qry = mysql_query($Sql);
        ?>
        <table id="table1"align='center' width='90%' cellpadding='3' cellspacing='2'>
            <thead>
                <tr bgcolor='#5499C7'>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Usuario</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Recuperacion</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Ingreso del dia</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Ingreso del total</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Efectivo</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Tarjeta</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Transferencia</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rs = mysql_fetch_array($Qry)) {
                    ($nRng % 2) > 0 ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;
                    ?>
                    <tr bgcolor='<?= $Fdo ?>'>
                        <td class='letrap'><?= $rs["usuario"] ?> </td>
                        <td class='letrap' align='right'><?= number_format($rs["Sobrante"], "2") ?></td>
                        <td class='letrap' align='right'><?= number_format($rs["ImporteCaja"] - $rs["Sobrante"], "2") ?></td>
                        <td class='letrap' align='right'><?= number_format($rs["ImporteCaja"], "2") ?></td>
                        <td class='letrap' align='right'><?= number_format($rs["CjaImporteEfectivo"], "2") ?></td>
                        <td class='letrap' align='right'><?= number_format($rs["CjaImporteTarjeta"], "2") ?></td>
                        <td class='letrap' align='right'><?= number_format($rs["CjaImporteTransferencia"], "2") ?></td>
                    </tr>
                    <?php
                    $sumCajaMSob += $rs["ImporteCaja"] - $rs["Sobrante"];
                    $sumCaja += $rs["ImporteCaja"];
                    $sumEfectivo += $rs["CjaImporteEfectivo"];
                    $sumTarjeta += $rs["CjaImporteTarjeta"];
                    $sumTranferencia += $rs["CjaImporteTransferencia"];
                    $nRng++;
                }
                ?>
            </tbody>
            <tfoot>
                <tr bgcolor="#FFBBB4" class="letrap">
                    <td></td>
                    <td align="right"><strong>Total -></strong></td>
                    <td align='right'><?= number_format($sumCajaMSob, "2") ?></td>
                    <td align='right'><?= number_format($sumCaja, "2") ?></td>
                    <td align='right'><?= number_format($sumEfectivo, "2") ?></td>
                    <td align='right'><?= number_format($sumTarjeta, "2") ?></td>
                    <td align='right'><?= number_format($sumTranferencia, "2") ?></td>
                </tr>
            </tfoot>
        </table>
        <table width="95%"><tr align="right"><td><a href="concentradoCaja.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a></td></tr></table>
    </body>
    <script type="text/javascript">
        $("#table1").hide();
        if ("<?= $_REQUEST[Op] ?>" == "Busca") {
            $("#table0").hide();
            $("#table1").show();
        }
    </script>
</html>
<?php
mysql_close();
?>
