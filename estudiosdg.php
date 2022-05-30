<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Fecha = date("Y-m-d");

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST[busca];
$Retornar = "estudios.php";
$date = date("Y-m-d H:i:s");
if ($_REQUEST[Act] == "Actualizar") {
    $sql = "SELECT bloqdes FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqdes] == 'No') {
        $Msj = "Registro Actualizado con Exito!";
        $sql = "UPDATE est SET objetivo='$_REQUEST[Objetivo]',condiciones='$_REQUEST[Condiciones]',"
                . "contenido='$_REQUEST[Contenido]',observaciones='$_REQUEST[Observaciones]',respradiologia='$_REQUEST[Respradiologia]'"
                . " WHERE estudio = '$busca'";
        if (!mysql_query($sql)) {
            $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error();
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Descripcion general/Edita Detalle', "est", $date, $busca, $Msj, "estudiosdg.php");
    } else {
        $Msj = "¡Error! se encuentra cerrado descripcion";
        header("Location: estudiosdg.php?busca=$busca&Msj=$Msj&Error=SI");
    }
} elseif ($_REQUEST["Op"] == "ab") {
    $Msj = "Registro abierto con exito";
    $sql = "UPDATE est SET bloqdes = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Descripcion general/Abre Descripcion', "est", $date, $busca, $Msj, "estudiosdg.php");
} elseif ($_REQUEST["Op"] == "cr") {
    $Msj = "Registro cerrado con exito!";
    $sql = "UPDATE est SET bloqdes = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis Mysql " . $sql . " " . mysql_error();
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Descripcion general/Cierra Descripcion', "est", $date, $busca, $Msj, "estudiosdg.php");
}

$sql = "SELECT estudio,descripcion,objetivo,condiciones,contenido,observaciones,proceso,respradiologia,bloqdes
    FROM est WHERE (estudio= '$busca')";

$CpoA = mysql_query($sql);
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Descripcion General</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
//        encabezados();
//        menu($Gmenu, $Gusr);

//Tabla contenedor de brighs
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr >
                <td colspan="3" bgcolor='#2c8e3c' width='90%' class='Subt' align='center'>
                    ...::: Descripcion General (<?= $busca ?>) <?= $Cpo[descripcion] ?> :::...
                </td>
            </tr>
            <td valign='top' width='43%' align='center'>
                <form name="form1" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                ..:: Detalle ::..
                            </td>
                        </tr>
                        <tr style="height: 10px"><td colspan="3"></td></tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Objetivo:&nbsp;</td>
                            <td height="40px" colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Objetivo" type="text" rows="6" cols="55"><?= $Cpo[objetivo] ?></textarea>
                            </td>
                        </tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Condiciones:&nbsp;</td>
                            <td height="40px"colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Condiciones" type="text" rows="6" cols="55"><?= $Cpo[condiciones] ?></textarea>
                            </td>
                        </tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Contenido:&nbsp;</td>
                            <td height="40px"colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Contenido" type="text" rows="6" cols="55"><?= $Cpo[contenido] ?></textarea>
                            </td>
                        </tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Observaciones:&nbsp;</td>
                            <td height="40px"colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Observaciones" type="text" rows="6" cols="55"><?= $Cpo[observaciones] ?></textarea>
                            </td>
                        </tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Posible respuesta para radiología e imagen:&nbsp;</td>
                            <td height="40px"colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Respradiologia" type="text" rows="6" cols="55"><?= $Cpo[respradiologia] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td height="40px" align="center" class="letrap">
                                <?php
                                if ($Cpo[bloqdes] == 'Si') {
                                    ?>
                                    <a class="edit" href="estudiosdg.php?Op=ab&busca=<?= $busca ?>">
                                        Cerrar <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="edit" href="estudiosdg.php?Op=cr&busca=<?= $busca ?>">
                                        Abrir <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>                            
                            <td colspan="2" height="40px" align="center" class="letrap">
                                <a class="letrap">
                                    <input type="submit" value="Actualizar" name="Act" class="letrap"></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </a>
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
            <td valign='top' width='45%'>
                <?php
                TablaDeLogs("/Estudios/Descripcion general/", $busca);
                ?>
            </td>
            <td valign='top' width="22%">
                <?php
                $sbmn = 'Descripcion';
                Sbmenu();
                ?>
            </td>
            </tr>        
        </table>    
    </body>
</html>
<?php
mysql_close();
?>
