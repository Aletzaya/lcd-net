<?php
session_start();

require("lib/lib.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

$link = conectarse();
$busca = $_REQUEST["busca"];

$Titulo = "Detalle por producto";

if ($_REQUEST["Boton"] == "Actualiza" && $_REQUEST["dt"] == "Envio") {
    $sql = "UPDATE maqdet set obsenv = '" . $_REQUEST["Observaciones"] . "'"
            . " WHERE orden = '" . $_REQUEST["Orden2"] . "' and estudio = '" . $_REQUEST["Estudio2"] . "'";

    $lUp2 = mysql_query($sql);
} else if ($_REQUEST["Boton"] == "Actualiza" && $_REQUEST["dt"] == "Recepcion") {
    $sql = "UPDATE maqdet set obsrec='" . $_REQUEST["Observaciones"] . "' "
            . "WHERE orden='" . $_REQUEST["Orden2"] . "' and estudio='" . $_REQUEST["Estudio2"] . "'";
    //echo $sql;
    $lUp2 = mysql_query($sql);
}

$Fecha = date("Y-m-d");

require ("config.php");
?>
<html>

    <head>

        <title><?php echo $Titulo; ?></title>
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

    <body onLoad="cFocus1()">

        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

        <SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
        <table width="100%" border="0">
            <tr>
                <td align="center" colspan="2">
                    <table width='99%' bgcolor="#2c8e3c" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr><td width="15%" align="center">
                                <img src="lib/DuranNvoBk.png" width="230" height="90"></img>
                            </td><td valign="bottom" align="center"><h2 style="color: #ffffff;">Orden # <?= $_REQUEST["Orden2"] ?> </h2><h2 style="color: #ffffff;">Estudio : <?= $_REQUEST["Estudio2"] ?></h2></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <!--<tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo'>Detalle</td></tr>-->
                        <?php
                        $cSqlH = "SELECT ot.orden,ot.fecha,ot.hora,ot.fechae,ot.cliente,cli.nombrec,ot.institucion FROM ot,cli
                                        WHERE ot.cliente=cli.cliente and ot.orden='" . $_REQUEST["Orden2"] . "'";
                        $Sql = mysql_query($cSqlH);
                        $He = mysql_fetch_array($Sql);
                        ?>
                        <tr height='45px' bgcolor='#2c8e3c'>
                            <td align='center' colspan="2" class='letratitulo'>
                                Cliente : <?= $He["cliente"] ?> .- <?= $He["nombrec"] ?> 
                                <br/>
                                Institucion : <?= $He["institucion"] ?>
                                Fecha : <?= $He["fecha"] ?> - <?= $He["hora"] ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr  height='100px'>
                <td  colspan="2">
                    <table align="center" cellpadding="3" cellspacing="2" width="97%" border='0'>
                        <tr>
                            <td height="5px" colspan="4">
                            </td>
                        </tr>
                        <tr class="letrap" bgcolor="#A7C2FC">
                            <td rowspan="2"><b>Descripcion</b></td>
                            <td colspan="3" align="center"><strong>Envio</strong></td>
                            <td colspan="3" align="center"><strong>Entrega</strong></td>
                        </tr>
                        <tr bgcolor="#A7C2FC" class="letrap" align="center">
                            <td><b>Fecha</b></td>
                            <td><b>Hora</b></td>
                            <td><b>Usuario</b></td>
                            <td><b>Fecha</b></td>
                            <td><b>Hora</b></td>
                            <td><b>Usuario</b></td>
                        </tr>  
                        <?php
                        $cSqlD = "SELECT maqdet.orden,maqdet.estudio,maqdet.mint,maqdet.mext,maqdet.fenv,maqdet.henv,maqdet.usrenv,maqdet.frec,
                                    maqdet.hrec,maqdet.usrrec,maqdet.obsenv,maqdet.obsrec,est.descripcion FROM maqdet,est
                                    WHERE maqdet.estudio=est.estudio AND maqdet.orden='" . $_REQUEST["Orden2"] . "' and maqdet.estudio='" . $_REQUEST["Estudio2"] . "'";
                        $Sql = mysql_query($cSqlD);
                        $He = mysql_fetch_array($Sql);
                        ?>
                        <tr class="letrap" bgcolor="<?= $Fdo ?>">
                            <td><?= $He["descripcion"] ?></td>
                            <td><?= $He["fenv"] ?></td>
                            <td><?= $He["henv"] ?></td>
                            <td><?= $He["usrenv"] ?></td>
                            <td><?= $He["frec"] ?></td>
                            <td><?= $He["hrec"] ?></td>
                            <td><?= $He["usrrec"] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width='50%' valign="top">
                    <?php
                    $cSql = "SELECT obsenv,obsrec FROM maqdet WHERE orden = '" . $_REQUEST["Orden2"] . "' AND estudio = '" . $_REQUEST["Estudio2"] . "'";
                    //echo $cSql;
                    $CpoA = mysql_query($cSql);
                    $Cpo = mysql_fetch_array($CpoA);
                    ?>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo'>Observaciones de Recepcion</td></tr>
                            <tr height="130px">
                                <td align="center">
                                    Observaciones : <textarea name="Observaciones" rows="5" cols="30"><?= $Cpo["obsrec"] ?></textarea>
                                </td>
                            </tr>
                            <tr height="30px">
                                <td colspan="2" align="center">
                                    <input type="submit" name="Boton" value="Actualiza" class="letrap"> 
                                    <input type="hidden" name='busca' value="<?= $busca ?>">
                                    <input type="hidden" name='Estudio2' value="<?= $_REQUEST[Estudio2] ?>">
                                    <input type="hidden" name='Orden2' value="<?= $_REQUEST[Orden2] ?>">
                                    <input type="hidden" name='dt' value="Recepcion">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td align='center' valign="top">
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo'>Observaciones de Envio</td></tr>
                            <tr height="130px">
                                <td align="center">
                                    Observaciones : <textarea name="Observaciones" rows="5" cols="30"><?= $Cpo["obsenv"] ?></textarea>
                                </td>
                            </tr>
                            <tr height="30px">
                                <td colspan="2" align="center">
                                    <input type="submit" name="Boton" value="Actualiza" class="letrap"> 
                                    <input type="hidden" name='Estudio2' value="<?= $_REQUEST[Estudio2] ?>">
                                    <input type="hidden" name='Orden2' value="<?= $_REQUEST[Orden2] ?>">
                                    <input type="hidden" name='dt' value="Envio">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
        <?php
        mysql_close();
        