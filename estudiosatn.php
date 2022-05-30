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
$Titulo = "Ordenes de estudio";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Fecha = date("Y-m-d");

#Variables comunes;
$busca = $_REQUEST[busca];
$Retornar = "estudios.php";
$date = date("Y-m-d H:i:s");
if ($_REQUEST["Op"] == "Guardar Promo") {
    $sql = "SELECT bloqatn FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqatn] == 'No') {
        $Msj = "Registro actualizado con Exito";
        $sql = "UPDATE est SET ventajas='$_REQUEST[Ventajas]',promogral='$_REQUEST[Promogral]',
                        msjadmvo='$_REQUEST[Msjadmvo]',modify='$Gusr',fechmod='$date' WHERE estudio='$busca' limit 1";
        if (!mysql_query($sql)) {
            $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error() . "&Error=SI";
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Atn/ Cli Edita', "est", $date, $busca, $Msj, "estudiosatn.php");
    } else {
        $Msj = "¡Error! se encuentra cerrado Atn Cli/Promo";
        header("Location: estudiosatn.php?busca=$busca&Msj=$Msj&Error=SI");
    }
} elseif ($_REQUEST["Op"] == "ab") {
    $Msj = "Registro abierto con exito";
    $sql = "UPDATE est SET bloqatn = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error() . "&Error=SI";
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Atn/Abre Atn Cliente', "est", $date, $busca, $Msj, "estudiosatn.php");
} elseif ($_REQUEST["Op"] == "cr") {
    $Msj = "Registro cerrado con exito";
    $sql = "UPDATE est SET bloqatn = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error() . "&Error=SI";
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Atn/Cierra Atn Cliente', "est", $date, $busca, $Msj, "estudiosatn.php");
}

$sql = "SELECT estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,
    equipo,muestras,estpropio,subdepto,contenido,comision,observaciones,proceso,clavealt,respradiologia,activo,dobleinterpreta,modify,fechmod,agrego,fechalta,depto,base,ventajas,promogral,tiempoestd,tiempoesth,entordh,enthosd,enturgd,bloqbas,bloqeqp,bloqmue,bloqcon,bloqdes,bloqadm,bloqatn,msjadmvo,consentimiento,producto_entregar,costo
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
            <title>Estudios - Atencion a Clientes</title>
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
                    ...::: Detalle de estudios (<?= $busca ?>)  <?= $Cpo[descripcion] ?> :::...
                </td>
            </tr>
            <td valign='top' width='43%' align='center'>
                <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo'align="center" colspan="2">
                            ..:: Detalle ::..
                        </td>
                    </tr>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <tr style="height: 10px"><td colspan="3"></td></tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Ventajas competitivas:&nbsp;</td>
                            <td height="40px"colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Ventajas" type="text" rows="3" cols="55"><?= $Cpo[ventajas] ?></textarea>
                            </td>
                        </tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Promocion General / Est. Sugeridos:&nbsp;</td>
                            <td height="40px" colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Promogral" type="text" rows="3" cols="55"><?= $Cpo[promogral] ?></textarea>
                            </td>
                        </tr>
                        <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Mensaje Administrativo:&nbsp;</td>
                            <td height="40px"colspan="2" align="center" class="letrap">
                                <textarea class="letrap" name="Msjadmvo" type="text" rows="3" cols="55"><?= $Cpo[msjadmvo] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td height="40px" align="center" class="letrap">
                                <?php
                                if ($Cpo[bloqatn] == 'Si') {
                                    ?>
                                    <a class="edit" href="estudiosatn.php?Op=ab&busca=<?= $busca ?>">
                                        Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="edit" href="estudiosatn.php?Op=cr&busca=<?= $busca ?>">
                                        Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td height="40px" align="center" colspan="2" class="letrap">
                                <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                <input class="letrap" type="submit" name="Op" value='Guardar Promo'></input>
                            </td>
                        </tr>
                    </form>
                </table>
            </td>
            <td valign='top' width='45%'>
                <?php
                TablaDeLogs("/Estudios/Atn/", $busca);
                ?>
            </td>
            <td valign='top' width="22%">

                <?php
                $sbmn = 'Atencion';
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
