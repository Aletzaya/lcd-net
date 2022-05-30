<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");

$link = conectarse();
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$Titulo = "Ordenes de estudio";
$Fecha = date("Y-m-d");

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST[busca];
$Msj = $_REQUEST[Msj];
$Retornar = "estudios.php";
$date = date("Y-m-d H:i:s");
$ContenidoEstudio = $_REQUEST[Estudio];

if ($_REQUEST[Est] == 'si') {

    $sql = "SELECT * FROM est WHERE estudio='$ContenidoEstudio'";
    $cSql = mysql_query($sql);
    $sSql = mysql_fetch_array($cSql);

    $ContenidoEst = $ContenidoEstudio.' - '.$sSql[descripcion];

} elseif ($_REQUEST["bt"] == "Registra") {

    $sql = "SELECT bloqcon FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqcon] == 'No') {

        $sql = "SELECT * FROM est WHERE estudio='$ContenidoEstudio'";
        $cSql = mysql_query($sql);
        $sSql = mysql_fetch_array($cSql);
        $sql = "INSERT INTO conest (estudio,conest,descripcion) VALUES ('$busca','$ContenidoEstudio','$sSql[descripcion]');";
        if (!mysql_query($sql)) {
            echo "Error en sintaxis Mysql " . $sql;
        }
        $Msj = "Estudio agregado con exito!";
        AgregaBitacoraEventos($Gusr, '/Estudios/Contenido/Agrega contenido al estudio', "est", $date, $busca, $Msj, "estudioscnt.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Contenido";
        header("Location: estudioscnt.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST[Op] == "ab") {

    $sql = "UPDATE est SET bloqcon = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $Msj = "Registro abierto con exito!";
    AgregaBitacoraEventos($Gusr, '/Estudios/Contenido/Abre Contenido', "est", $date, $busca, $Msj, "estudioscnt.php");

} elseif ($_REQUEST[Op] == "cr") {

    $sql = "UPDATE est SET bloqcon = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $Msj = "Registro cerrado con exito!";
    AgregaBitacoraEventos($Gusr, '/Estudios/Contenido/Cierra Contenido', "est", $date, $busca, $Msj, "estudioscnt.php");

} elseif ($_REQUEST["Op"] == "Elimcon"){

    $sql = "SELECT bloqcon FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqcon"] == 'No') {
        $Msj = "Contenido eliminado con exito";
        $sql    = mysql_query("delete FROM conest WHERE id='$_REQUEST[id]' and estudio='$busca'");
        if (!mysql_query($sql)) {
            echo "Error de sintaxis MYSQL " . $sql;
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Contenido/Elimina contenido al estudio', "est", $date, $busca, $Msj, "estudioscnt.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Contenido";
        header("Location: estudioscnt.php?busca=$busca&Msj=$Msj&Error=SI");
    }

}

$CpoA = mysql_query("SELECT * FROM est WHERE (estudio= '$busca')");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Contenido de estudio</title>
            <?php require ("./config_add.php"); ?>
            <link href="estilos.css?var=1.3" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=2.1" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                </head>
                <body topmargin="1">
                    <?php
   //                 encabezados();
     //               menu($Gmenu, $Gusr);
                    ?>
                    <form name="form1" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                        <tr >
                            <td colspan="3" bgcolor='#2c8e3c' width='90%' class='Subt' align='center'>
                                ...::: Contenido del estudio (<?= $busca ?>) <?= $Cpo[descripcion] ?> :::...
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width='43%' align='center'>
                                <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                    <tr style="background-color: #2c8e3c">
                                        <td class='letratitulo'align="center" colspan="2">
                                            ..:: Agregar Contenido ::..
                                        </td>
                                    </tr>
                                    <tr style="height: 35px" valign="bottom">
                                        <td width='50%' class='letrap' align='left' colspan='2'> &nbsp; &nbsp; &nbsp; 
                                            <?php
                                            if ($Cpo[bloqcon] == 'No') {
                                            ?>
                                                <a href='seleccest.php?Dir=estudioscnt&Est=<?= $busca ?>'><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
                                            <?php
                                            }
                                            ?>
                                             &nbsp; Estudio : <font size='1' color='red'><b><?= $ContenidoEst ?></b></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="40px" align="center" class="letrap">
                                            <?php
                                            if ($Cpo[bloqcon] == 'Si') {
                                                ?>
                                                <a class="edit" href="estudioscnt.php?Op=ab&busca=<?= $busca ?>">
                                                    Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                                </a>
                                                <?php
                                            } else {
                                                ?>
                                                <a class="edit" href="estudioscnt.php?Op=cr&busca=<?= $busca ?>">
                                                    Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                                </a>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td height="40px" align="center" class="letrap">
                                            <?php
                                            if ($ContenidoEstudio <> '') {
                                            ?>
                                                <a class="letrap">
                                                    <input type="submit" value='Registra' name='bt'></input>
                                                </a>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" value="<?= $ContenidoEstudio ?>" name="Estudio"></input>
                                <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </form>  
                                <br> 
                                <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                    <tr style="background-color: #2c8e3c">
                                        <td class='letratitulo'align="center" colspan="2">
                                            ..:: Detalle ::..
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table align="center" cellpadding="3" cellspacing="2" width="97%" border='0'>
                                                <tr>
                                                    <td height="5px">
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                                    <td height="22px">
                                                        <b>Estudio</b>
                                                    </td>
                                                    <td>
                                                        <b>Agregado</b>
                                                    </td>
                                                    <td>
                                                        <b>Descripcion</b>
                                                    </td>
                                                    <td>
                                                        <b>-</b>
                                                    </td>
                                                </tr>   
                                                <?php
                                                $sql = "SELECT * FROM conest WHERE estudio='$busca'";
                                                $result3 = mysql_query($sql);
                                                while ($cSql = mysql_fetch_array($result3)) {
                                                    ?>
                                                    <tr class="letrap">
                                                        <td>
                                                            <?= $cSql[estudio] ?>
                                                        </td>
                                                        <td>
                                                            <?= $cSql[conest] ?>
                                                        </td>
                                                        <td>
                                                            <?= $cSql[descripcion] ?>
                                                        </td>
                                                        <?php
                                                        if ($Cpo[bloqcon] == 'No') {
                                                        ?> 
                                                            <td align="center">
                                                                <a href="estudioscnt.php?busca=<?= $busca ?>&id=<?= $cSql[id] ?>&Op=Elimcon">
                                                                    <i class="fa fa-times fa-lg" style="color:red;" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <td align='center'>-</td></tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table> 
                            </td>
                            <td valign='top' width='45%'>
                                <?php
                                TablaDeLogs("/Estudios/Contenido", $busca);
                                ?>
                            </td>
                            <td valign='top' width="22%">
                                <?php
                                $sbmn = 'Contenido';
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
