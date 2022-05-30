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
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];

if ($_REQUEST["Nuevo"] === "nuevo") {
    $result = mysql_query("SELECT menu FROM lcd.menu group by menu;");
    while ($Prm = mysql_fetch_array($result)) {
        if (!mysql_query("INSERT INTO menu_usr (menu,submenu,usr,editor) VALUES ('$Prm[menu]',' ',$_REQUEST[usuario],1)")) {
            echo mysql_error();
            break;
        }
    }
    $Msj = "¡Registros agregados con exito!";
    header("location: permisosusre.php?busca=$busca&Msj=$Msj");
}

if ($_REQUEST["Boton"] === "Dar Permisos") {
    if (!mysql_query("INSERT INTO menu_usr (menu,submenu,usr,editor) VALUES ('" . $_REQUEST["Menu"] . "',' ','" . $_REQUEST["usuario"] . "',1)")) {
        echo mysql_error();
    } else {
        $Msj = "¡Registros agregados con exito!";
        header("location: permisosusre.php?busca=$busca&Msj=$Msj");
    }
}

if ($_REQUEST["Actualiza"]) {
    $sql = "UPDATE menu_usr SET submenu = '$_REQUEST[Permisos]' WHERE usr='$_REQUEST[usuario]' AND menu='$_REQUEST[Menu]'";
    $cc = mysql_query($sql);
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis mysql" . $sql;
    } else {
        $Msj = "Registro actualizado con exito $i!";
    }
    header("location: permisosusre.php?busca=$busca&Msj=$Msj");
}

if ($_REQUEST["Estado"] === "ActualizarEstado") {
    $sql = "UPDATE authuser SET status = '$_REQUEST[Activo]' WHERE id=$_REQUEST[usuario]";
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis mysql" . $sql;
    }
    $Msj = "Registro actualizado con exito!";
    header("location: permisosusre.php?busca=$busca&Msj=$Msj");
} else if ($_REQUEST["Agregar"] === "Nuevo") {

    $sql = "INSERT INTO authuser (uname,passwd,status) VALUES ('$_REQUEST[Usuario]',md5('$_REQUEST[Passwd]'),'active');";
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis mysql" . $sql;
    }
    $Msj = "Registro agregado con exito!";
    header("location: permisosusr.php?busca=$busca&Msj=$Msj");
}


if ($Cpo[sexo] === 'F') {
    $sexo = "Femenino";
} else {
    $sexo = "Masculino";
}
require ("config.php");          //Parametros de colores;
if ($_REQUEST["Estado"] <> '') {
    $Estado = $_REQUEST["Estado"];
} else {
    $Estado = $Cpo["estado"];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Permisos a Usuarios</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de: <?= $_REQUEST[busca] ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top'>
                    <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table class="letrap" width="100%">
                            <tr>
                                <td width="90%">
                                    <?php
                                    $tt = "SELECT id, uname, status  FROM authuser WHERE uname='$busca'";
                                    $result = mysql_query($tt);
                                    $Id_cli = mysql_fetch_array($result);
                                    ?>
                                    <strong>Usuaro :</strong>
                                    <input type="text" name="Usuario" value="<?= $Id_cli[uname] ?>"/>
                                    <strong>Contraseña :</strong>
                                    <input type="password" name="Passwd"/>
                                    <strong>Activo :</strong>
                                    <select name="Activo" class="letrap">
                                        <option value="active">Activo</option>
                                        <option value="inactive">Inactivo</option>
                                        <option value="<?= $Id_cli[status] ?>" selected><?= $Id_cli[status] ?></option>
                                    </select>
                                    <?php if ($_REQUEST["busca"] === "NUEVO") { ?>
                                        <input value = "Nuevo" name = "Agregar" type = "submit" class = "letrap"></input> 
                                    <?php } else { ?>
                                        <input value = "ActualizarEstado" name = "Estado" type = "submit" class = "letrap"></input>
                                    <?php } ?>
                                    <input type="hidden" name='busca' value="<?= $busca ?>"></input>
                                    <input type="hidden" name='usuario' value="<?= $Id_cli[0] ?>"></input>
                                </td>
                                <td>
                                    <a href="permisosusr.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <table width='99%' align='center' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;' id="Permisos">
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="3">
                                ..:: Permisos a Usuarios ::..
                            </td>
                        </tr>
                        <?php Detalle("form1", "recepcion", "Recepcion", $busca, "Nvo Recepcion") ?>
                        <?php Detalle("form2", "catalogos", "Catalogos", $busca, "Nvo Catalogos") ?>
                        <?php Detalle("form3", "movil", "Movil", $busca, "Nvo Movil") ?>
                        <?php Detalle("form4", "reportes", "Reportes", $busca, "Nvo Reportes") ?>
                        <?php Detalle("form5", "procesos", "Procesos", $busca, "Nvo Procesos") ?>
                        <?php Detalle("form6", "respaldos", "Respaldos", $busca, "Nvo Respaldos") ?>
                        <?php Detalle("form7", "admin", "Administracion", $busca, "Nvo Administracion") ?>
                        <?php Detalle("form8", "promocion", "Promocion", $busca, "Nvo Permisos") ?>
                        <?php Detalle("form9", "recursosh", "Recursos Humanos", $busca, "Nvo Recursos H") ?>
                        <?php Detalle("form10", "usuarios", "Usuarios", $busca, "Nvo Usuarios") ?>
                        <?php Detalle("form11", "productividad", "Productividad", $busca, "Nvo Productividad") ?>
                    </table>
                </td>
            </tr>      
        </table> 
    </body>
    <script src="./controladores.js"></script>
</html>
<?php

function Detalle($Form, $Origen, $Title, $busca, $NvoRegisto) {
    global $Id_cli, $rst;
    ?>
    <tr class="letrap">
        <td align="center" width="80%" valign="top">
            <form name='<?= $Form ?>' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <table width="100%" class="letrap">
                    <tr><td colspan="2" align="center"><h2><?= $Title ?></h2></td></tr>
                    <tr><td align="left">
                            <?php GeneraDetalle($Origen, $busca); ?>
                            <br></br><b>Permisos:</b> 
                            <TEXTAREA class="letrap" name='Permisos' cols='100' rows='1'><?= $rst[submenu] ?></TEXTAREA>
                    </td><td width="20%"><input class="letrap" type="submit" name="Actualiza" value="Actualiza"></input>
                    <input type="hidden" name='Menu' value="<?= $Origen ?>"></input>
                    <input type="hidden" name='busca' value="<?= $busca ?>"></input>
                    <input type="hidden" name='usuario' value="<?= $Id_cli[0] ?>"></input>
                            <?php
                            $rst["submenu"] == "" ? $Boton = '<br><input type="submit" name="Boton" value="Dar Permisos" class="letrap" align="center"></input>' : $Boton = "";
                            echo $Boton;
                            ?>
                    </td></tr>
                </table>
            </form>
        </td>
    </tr>
    <?php
}

function GeneraDetalle($menu, $busca) {
    global $rst;
    $tt = "SELECT submenu FROM menu_usr WHERE menu='$menu' AND usr=(SELECT id FROM authuser WHERE uname='$busca')";
    $result = mysql_query($tt);
    $rst = mysql_fetch_array($result);
    $result = mysql_query("SELECT submenu,lugar FROM menu WHERE menu='$menu'");
    $i = 1;
    while ($Prm = mysql_fetch_array($result)) {
        echo $i . ".- " . $Prm['submenu'] . " <strong>|</strong> Posicion : ".$Prm["lugar"]."<br>";

        $i++;
    }
}

mysql_close();
?>

