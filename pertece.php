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
$msj = $_REQUEST["Msj"];

if ($_REQUEST["bt"] === "Actualizar") {

    $Sql = "UPDATE pertec SET nombre='$_REQUEST[Nombre]',profesion='$_REQUEST[Profesion]',cedula='$_REQUEST[Cedula]',"
            . "status='$_REQUEST[Status]',alias='$_REQUEST[Alias]' "
            . "WHERE id='$busca' limit 1";
    echo $Sql;
    if (mysql_query($Sql)) {
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Personal Tec./Datos Principales','pertec',now(),$busca);";
        if (mysql_query($Sql)) {
            header("Location: pertec.php?busca=ini");
        }
    }
} else if ($_REQUEST["bt"] === "Nuevo") {

    $Fecha = date("Y-m-d");
    $Hora = date("H:i");
    $lUp = mysql_query("INSERT INTO pertec (nombre,profesion,cedula,status,alias) "
            . "VALUES ('$_REQUEST[Nombre]','$_REQUEST[Profesion]','$_REQUEST[Cedula]','$_REQUEST[Status]','$_REQUEST[Alias]')");
    $Id = mysql_insert_id();
    $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
            . "('$Gusr','/Admin/Salidas/Creacion','sl',now(),$Id);";
    if (mysql_query($Sql)) {
        header("Location: pertec.php?busca=$Id&Msj=Registro actualizado con exito!");
    }
}

#Variables comunes;
$cSql = "SELECT * FROM pertec WHERE id='$busca'";
$CpoA = mysql_query($cSql);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle Técnico Radiólogo</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Detalle Técnico Radiólogo no. <?= $busca ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Datos principales ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">Nombre : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombre' value='<?= $Cpo[nombre] ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Profesion : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Profesion' value='<?= $Cpo[profesion] ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Cedula : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Cedula' value='<?= $Cpo[cedula] ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Alias : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Alias' value='<?= $Cpo[alias] ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Status : </td>
                                <td>
                                    <select name='Status' class="cinput">
                                        <option value='Activo'>ACTIVO</option>
                                        <option value='Inactivo'>INACTIVO</option>
                                        <option selected value='<?= $Cpo[status] ?>'><?= $Cpo[status] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    if ($Cpo[status] == "Activo") {
                                        ?>
                                        <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                        <?php
                                    } else {
                                        ?>
                                        <input class="letrap" type="submit" value='Nuevo' name='bt'></input>
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </form>
                    </table>  
                </td>
                <td valign='top' width='45%'>         
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
                                        <td><b>&nbsp; Id</b></td>
                                        <td><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Admin/Personal Tec./Datos%') 
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
                    <a href="pertec.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                </td>
            </tr>      
        </table>  
    </body>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>

