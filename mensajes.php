<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
$link = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

require_once './Services/MensajesServices.php';

$Titulo = "Menu de inicio";
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Mensajeria LCD-NET ::..</title>
            <script type='text/javascript' src='ckeditor/ckeditor.js'></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="lib/DuranNvoBk.png" width="230" height="90"></img></td>
                <td style="background-image:url(images/BarraVdeGrad.jpg)">
                    <h3 style="font-family: cursive;color: #2C3E50;" align="center" >Mensajeria LCD-NET</h3>
                </td>
            </tr>
        </table>
        <br></br>
        <table width='90%' border='0' align='center' cellpadding='1' cellspacing='2' class='letrap' style='border:#566573 1px solid;'>
            <tr>
                <?php
                $_REQUEST["op"] === "MR" ? $MrColor = "bgcolor='#AAB7B8'" : $MrColor = "";
                $_REQUEST["op"] === "ME" ? $MeColor = "bgcolor='#AAB7B8'" : $MeColor = "";
                $_REQUEST["op"] === "NM" ? $NmColor = "bgcolor='#AAB7B8'" : $NmColor = "";
                ?>
                <td <?= $MrColor ?>><a class="edit" href="mensajes.php?op=MR">Mensajes Recibidos</a></td>
                <td <?= $MeColor ?>><a class="edit" href="mensajes.php?op=ME">Mensajes Enviados</a></td>
                <td <?= $NmColor ?>><a class="edit" href="mensajes.php?op=NM">Nuevos Mensajes</a></td>
            </tr>
        </table> 
        <br></br>
        <table id="Tabla1" width='90%' border='0' align='center' cellpadding='1' cellspacing='2' class='letrap' style='border:#566573 1px solid;'>
            <tr bgcolor="#E59866">
                <td></td>
                <td>De</td>
                <td>Fecha</td>
                <td>Hora</td>
                <td>Titulo</td>
            </tr>
            <?php
            $QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=13");
            $Qry = mysql_fetch_array($QryA);

            if ($_REQUEST["op"] === "ME") {
                $cSql = "SELECT " . $Qry["campos"] . ",msj.id,msj.bd,msj.para de FROM " . $Qry["froms"] . " "
                        . "WHERE msj.de='$Usr' OR msj.de='$Tda'";
            } else {
                $cSql = "SELECT " . $Qry["campos"] . ",msj.de,msj.id,msj.bd FROM " . $Qry["froms"] . " "
                        . "WHERE (msj.para='$Gusr') order by id desc limit 15";
            }
            //echo $cSql;
            $cSqlA = mysql_query($cSql);
            while ($rg = mysql_fetch_array($cSqlA)) {
                ($nRng % 2) > 0 ? $Fdo = '#FFFFFF' : $Fdo = '#D5D5D5';

                $rg["bd"] == 0 ? $IconoMsj = '<i class="fa fa-envelope-o fa-2x" aria-hidden="true"></i>' : $IconoMsj = '<i class="fa fa-envelope-open-o fa-2x" aria-hidden="true"></i>';
                ?>
                <tr bgcolor="<?= $Fdo ?>">
                    <td><a href="mensajes.php?busca=<?= $rg[id] ?>" class="edit"><?= $IconoMsj ?></a></td>
                    <td><?= $rg["de"] ?></td>
                    <td><?= $rg["fecha"] ?></td>
                    <td><?= $rg["hora"] ?></td>
                    <td><?= $rg["titulo"] ?></td>
                </tr>
                <?php
                $nRng++;
            }
            ?>
        </table>
        <table id="Tabla2" width="100%">
            <tr>
                <td>
                    <?php
                    $CpoA = mysql_query("SELECT * FROM msj WHERE id = '" . $_REQUEST["busca"] . "'");

                    $Cpo = mysql_fetch_array($CpoA);
                    ?>
                    <TEXTAREA id='Nota' NAME='Nota' cols='100' rows='7'><?= $Cpo["nota"] ?></TEXTAREA>
		</td>
            </tr>
        </table>
        <form name='form0' method='get' action='mensajes.php'>
        <tabla border="1" class="letrap" id="Tabla3">
            <tr>
                <td colspan="3">
                    <h2 align="center">MENSAJE NUEVO</h2>
                </td>
            </tr>
            <tr>
                <td>
                    Para :
                    <select class="letrap" name='Para'>
                            <?php
                            $ParaA = mysql_query("SELECT uname FROM authuser WHERE uname<>'$Usr' ORDER BY uname");
                            while ($rg = mysql_fetch_array($ParaA)) {
                                echo "<option value='$rg[0]'>$rg[0]</option>";
                                if ($rg[0] == $Cpo["para"]) {
                                    $Disp = $rg[0];
                                }
                            }
                            ?>
                    <option value='*'> * &nbsp; Todos</option>
                    <option selected value='<?= $Cpo["para"] ?>'><?= $Disp ?></option>
                    </select>
                </td>
                <td>C.c :
                    <select class="letrap" name='Cc'>
                            <?php
                            $ParaA = mysql_query("SELECT uname FROM authuser WHERE uname<>'$Usr' ORDER BY uname");
                            while ($rg = mysql_fetch_array($ParaA)) {
                                echo "<option value='$rg[0]'>$rg[0]</option>";
                                if ($rg[0] == $Cpo["para"]) {
                                    $Disp = $rg[0];
                                }
                            }
                            ?>
                    <option selected value='<?= $Cpo["para"] ?>'><?= $Disp ?></option>"
                    </select>
                </td>
                <td>Titulo <input type="text" class="letrap" name="Titulo" style="width: 400px;"></input></td>
            </tr>
            <tr>
                <td colspan="3">
                    <TEXTAREA id='MensajeNuevo' name='MensajeNuevo' cols='90' rows='7'></TEXTAREA>
		</td>
            </tr>
            <tr><td colspan="3">
                    <input align="center" class="letrap" type="submit" name="Enviar" value="Enviar"></input>
                    <input type="hidden" name="op" value="NM"></input>
                </td>
            </tr>
        </tabla>
        </form>
    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#Tabla2").hide();
            $("#Tabla3").hide();
            var i = "<?= $_REQUEST[busca] ?>";
            var op = "<?= $_REQUEST[op] ?>";
            if (i > 0) {
                $("#Tabla1").hide();
                $("#Tabla2").show();
            } else if (op == "NM") {
                $("#Tabla1").hide();
                $("#Tabla2").hide();
                $("#Tabla3").show();
            }

            CKEDITOR.replace('Nota');
            CKEDITOR.replace('MensajeNuevo');
        });
</script>                 
                <script src="./controladores.js"></script>
                </html>
<?php
mysql_close();
?>

