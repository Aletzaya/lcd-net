<?php
#Librerias

session_start();

$_SESSION["Usr"] = array("", "", "", "", "", "", "u938386532_root");

require("lib/lib.php");
$link = conectarse();
include_once ("authconfig.php");
require ("config.php");

if ($_REQUEST["op"] === "Si") {
    $sql = "SELECT institucion FROM inst WHERE mail='$_REQUEST[Usuario]' AND password ='$_REQUEST[Clave]' and acceweb='Si' and status='ACTIVO' LIMIT 1;";
    echo $sql;
    $cSql = mysql_query($sql);
    if ($cc = mysql_fetch_array($cSql)) {
        $inst=$cc["institucion"];
        $cc = "INSERT INTO ingresos (cpu) VALUES (' $_SERVER[REMOTE_ADDR] USER AGENT: $_SERVER[HTTP_USER_AGENT]');";
//echo $cc;
        mysql_query($cc);
        header("Location: entregaonlineInstitucion.php?Institucion=$inst");
    }
}
$cc = mysql_query("SELECT max(id) id FROM ingresos");
$cs = mysql_fetch_array($cc);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Area de Bienvenida LCD-NET</title>
            <link href="estilos.css" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css"></link>
            <style type="text/css" >
                body {
                    background-color: #F6F6F6;
                }
            </style>
    </head>
    <table><tr><td height="150px"></td></tr></table>
    <form name='Sample' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
        <table width='260' border='0' align='center' cellpadding='0' cellspacing='1' style='height: 300px;width: 292px;background-image:url(images/login.png);border:#000 0px solid;border-color: #999; border-radius: .5em;'> 
            <tr>
                <td width='290' height='335' align='center'>
                    <table width='80%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap'>
                        <tr height='130'></tr>
                        <tr height='30'>
                            <td>Usuario: </td><td>
                                <input type="text" class="letrap" size="22" name="Usuario" autofocus/></label> 
                            </td>
                        </tr>
                        <tr height='30'>
                            <td>Password: </td>
                            <td>
                                <input type="password" class="letrap" size="22" name="Clave" /></label>
                            </td>
                        </tr>
                        <tr height='87'></tr>
                        <tr>
                            <td colspan="2" align='right'>
                                <button type="submit" class="btn btn-success btn-block text-center">
                                    Buscar Estudios
                                </button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="letrap">
            <tr>
                <td>
                    Visitas.- <?= $cs[id] ?>
                </td>
            </tr>
        </table>
        <input type="hidden" name="op" value="Si"></input>
    </form>
    <table width="100%">
        <tr align="center">
            <td width="40%">
            </td>
            <td>
                <form name='Sample' method='post' action="indexcli.php">
                    <button type="submit" class="btn btn-dark btn-block text-center">
                        Clientes
                    </button>
                </form>
                <form name='Sample' method='post' action="javascript:wingral('olvidopass.php')">
                    <button type="submit" class="btn btn-primary btn-block text-center">
                        ¿Olvidaste tu Password?
                    </button>
                </form>
            </td>
            <td width="40%">
            </td>
        </tr>
    </table>
</body>
</html>
