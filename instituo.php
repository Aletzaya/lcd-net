<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];

if ($_REQUEST[bt] == "Actualizar") {

    $sql = "UPDATE inst SET responsable='$_REQUEST[Responsable]', observaciones='$_REQUEST[Observaciones]', servicio='$_REQUEST[Servicio]',"
            . "administrativa='$_REQUEST[Administrativa]',msjadministrativo='$_REQUEST[Msjadministrativo]' WHERE institucion = $busca;";

    if (mysql_query($sql)) {

        $Msj = "¡Registro actualizado con exito!";

        $sql = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Instituciones/Actualiza los Otros datos','inst','$Fecha','$busca')";
        mysql_query($sql);

        header("Location: instituo.php?busca=$busca&Msj=$Msj");

    }else{

        $Msj = "Error en sintaxis SQL : " . $sql;
        header("Location: instituo.php?busca=$busca&Msj=$Msj&Error=SI");

    }
}

$CpoA = mysql_query("SELECT * FROM inst WHERE institucion = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
} else {
    $Estado = $Cpo[estado];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Instituciones - General</title>
        <?php require ("./config_add.php"); ?>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu,$Gusr);
        ?>
        <script src="./controladores.js"></script>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) ?> ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='45%'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Datos ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Responsable de cobranza : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Responsable" type="text" rows="3" cols="45"><?= $Cpo[responsable] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Observaciones : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Observaciones" type="text" rows="3" cols="45"><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Caracteristicas de servicio : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Servicio" type="text" rows="3" cols="45"><?= $Cpo[servicio] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Caracteristicas Administrativas : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Administrativa" type="text" rows="3" cols="45"><?= $Cpo[administrativa] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Mensaje Administrativo : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Msjadministrativo" type="text" rows="3" cols="45"><?= $Cpo[msjadministrativo] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                            </form>
                        </table>  
                    </td>
                    <td valign='top' width='45%'>
                        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                <tr style="background-color: #2c8e3c">
                                    <td class='letratitulo'align="center" colspan="2">
                                        .:: Ots ::.
                                    </td>
                                </tr>

                            </table>
                        </form>            
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Modificaciones ::.
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <table align="center" width="95%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td>
                                                <b>&nbsp; Id</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Fecha</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Usuario</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Accion</b>
                                            </td>
                                        </tr>
                                        <?php
                                            $sql = "SELECT * FROM logcat 
                                            WHERE accion like ('/Catalogos/Instituciones/%') 
                                            AND cliente=$busca ORDER BY id DESC LIMIT 6;";
                                        //echo $sql;
                                        $PgsA = mysql_query($sql);
                                        while ($rg = mysql_fetch_array($PgsA)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td>
                                                    <b>&nbsp;<?= $rg[id] ?></b>
                                                </td>
                                                <td align="center">
                                                    &nbsp;<?= $rg[fecha] ?>
                                                </td>
                                                <td>
                                                    <?= $rg[usr] ?>
                                                </td>
                                                <td>
                                                    <?= $rg[accion] ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $nRng++;
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign='top' width="22%">
                        <?php
                        SbmenuInst();
                        ?>
                    </td>
                </tr>      
            </table>  

    </body>
</html>
<?php
mysql_close();
?>
