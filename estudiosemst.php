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
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Fecha = date("Y-m-d");

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST[busca];
$Op = $_REQUEST[Op];
$Msj = $_REQUEST[Msj];
$Retornar = "estudios.php";
$date = date("Y-m-d H:i:s");

if ($_REQUEST["bt"] == "Actualizar") {

    $sql = "SELECT bloqmue FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqmue"] == 'No') {
        $Msj = "Registro Actualizado con exito";
        $sql = "UPDATE est SET proceso = '$_REQUEST[Proceso]', tiempoestd = '$_REQUEST[tiempoestd]', tiempoesth = '$_REQUEST[Tiempoesth]', "
                . "entord = '$_REQUEST[Entord]', entordh = '$_REQUEST[Entordh]', enthosd = '$_REQUEST[Enthosd]', enthos = '$_REQUEST[Enthos]', "
                . "enturgd = '$_REQUEST[Enturgd]', enturg='$_REQUEST[Enturg]', producto_entregar='$_REQUEST[Producto_entregar]',dobleinterpreta = '$_REQUEST[Dobleinterpreta]' "
                . "WHERE estudio = '$busca' LIMIT 1";
        if (!mysql_query($sql)) {
            echo "Error de sintaxis MYSQL " . $sql;
        }

        AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Datos Actualizados', "est", $date, $busca, $Msj, "estudiosemst.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Muestras";
        header("Location: estudiosemst.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST["bt"] == "Agregar"){

    $sql = "SELECT bloqmue FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqmue"] == 'No') {
        $Msj = "Proceso Registrado con exito";
        $sql=mysql_query("INSERT into proc_a_realizar (estudio,proceso) VALUES ('$busca','$_REQUEST[Proceso]')");
        if (!mysql_query($sql)) {
            echo "Error de sintaxis MYSQL " . $sql;
        }
        logs("est", $busca, "Edita estudio $busca");
        AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Proceso Agregado', "est", $date, $busca, $Msj, "estudiosemst.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Muestras";
        header("Location: estudiosemst.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST["bt"] == "Agregar_region"){

    $sql = "SELECT bloqmue FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqmue"] == 'No') {
        $Msj = "Region registrada con exito";
        $sql=mysql_query("INSERT into reg_a_realizar (estudio,proceso) VALUES ('$busca','$_REQUEST[region]')");
        if (!mysql_query($sql)) {
            echo "Error de sintaxis MYSQL " . $sql;
        }
        logs("est", $busca, "Edita estudio $busca");
        AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Region Agregada', "est", $date, $busca, $Msj, "estudiosemst.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Muestras";
        header("Location: estudiosemst.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST["Op"] == "Elimproceso"){

    $sql = "SELECT bloqmue FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqmue"] == 'No') {
        $Msj = "Proceso eliminado con exito";
        $sql    = mysql_query("delete FROM proc_a_realizar WHERE id='$_REQUEST[id]' and estudio='$busca'");
        if (!mysql_query($sql)) {
            echo "Error de sintaxis MYSQL " . $sql;
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Proceso Eliminado', "est", $date, $busca, $Msj, "estudiosemst.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Muestras";
        header("Location: estudiosemst.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST["Op"] == "Elimregion"){

    $sql = "SELECT bloqmue FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqmue"] == 'No') {
        $Msj = "Region eliminada con exito";
        $sql    = mysql_query("delete FROM reg_a_realizar WHERE id='$_REQUEST[id]' and estudio='$busca'");
        if (!mysql_query($sql)) {
            echo "Error de sintaxis MYSQL " . $sql;
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Region Eliminada', "est", $date, $busca, $Msj, "estudiosemst.php");
    } else {
        $Msj = "!Error¡ se encuentra cerrado Muestras";
        header("Location: estudiosemst.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST["Op"] == "ab") {

    $sql = "UPDATE est SET bloqmue = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
        $Msj = "Registro abierto con exito!";

    AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Abre Muestras', "est", $date, $busca, $Msj, "estudiosemst.php");

} elseif ($_REQUEST["Op"] == "cr") {

    $sql = "UPDATE est SET bloqmue = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $Msj = "Registro cerrado con exito!";
    AgregaBitacoraEventos($Gusr, '/Estudios/Muestras/Cierra Muestras', "est", $date, $busca, $Msj, "estudiosemst.php");

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
            <title>Estudios - Muestras de Estudio</title>
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
                    ..:: Muestras de estudio (<?= $busca ?>) <?= $Cpo[descripcion] ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' width='43%' align='center'>
                    <form name="form1" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="3">
                                    ..:: Proceso y Tiempos ::..
                                </td>
                            </tr>

                            <tr style="height: 35px" valign="bottom">
                                <td width='50%' class='letrap' align='center'>
                                    Proceso a realizar : &nbsp;
                                </td>
                                <td align='left'>
                                    <select class='cinput' class='InputMayusculas' name='Proceso'>        
                                        <option value='TOMA SANGUINEA'>TOMA SANGUINEA</option>
                                        <option value='RECOLECCION DE MUESTRA'>RECOLECCION DE MUESTRA</option>
                                        <option value='REALIZACION DE ESTUDIOS'>REALIZACION DE ESTUDIOS</option>
                                        <option value='TOMA DE MUESTRA CORPORAL'>TOMA DE MUESTRA CORPORAL</option>
                                        <option value='SERVICIO'>SERVICIO</option>
                                        <option value='MIXTO'>MIXTO</option>
                                        <option value='<?= $Cpo[proceso] ?>' SELECTED><?= $Cpo[proceso] ?></option>
                                    </select>
                                </td>
                                <td height="40px" align="left" class="letrap">
                                    <input class="letrap" type="submit" value='Agregar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='45%' colspan="3" class='letrap' align='center'>
                                    <table align="center" width="97%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td class="letrap" width="70%">
                                                <b> Proceso a realizar </b>
                                            </td>                                            
                                            <td class="letrap" width="30%" align="center">
                                                <b> Eliminar </b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' colspan="3" class='letrap' align='right'>
                                    <table align="center" width="97%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;">
                                        <?php
                                        $cSql3 = "SELECT * FROM proc_a_realizar WHERE proc_a_realizar.estudio='$busca'";
                                        $result3 = mysql_query($cSql3);
                                        while ($row3 = mysql_fetch_array($result3)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }    //El resto de la division;
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td align="left"><?= $row3[proceso] ?></td>
                                                <?php
                                                if ($Cpo[bloqmue] == 'No') {
                                                    ?> 
                                                    <td align="center">
                                                        <a href="estudiosemst.php?busca=<?= $busca ?>&id=<?= $row3[id] ?>&Op=Elimproceso">
                                                            <i class="fa fa-times fa-lg" style="color:red;" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td align='center'>-</td></tr>
                                                <?php
                                            }

                                            $nRng++;
                                        }
                                        ?>

                                </td>
                            </tr>
                        </table>
                </td>
            </tr>

            <tr style="height: 30px">
                <td width='45%' colspan="3" class='letrap' align='light'>
                    <table align="center" width="97%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;">
                        <br></br>
                        <tr style="background-color: rgba(0,0,0,.2); height: 35px;">
                            <td class="letrap" width="33%">
                                &nbsp; Realización
                            </td>
                            <td class="letrap" width="23%" align="center">
                                <select class="cinput" name="tiempoestd">
                                    <?php
                                    for ($i = 0; $i <= 30; $i++) {
                                        ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php
                                    }
                                    ?>
                                    <option selected value="<?= $Cpo[tiempoestd] ?>"><?= $Cpo[tiempoestd] ?></option>
                                </select>
                                &nbsp; dias
                            </td>
                            <td class="Inpt" width="43%"  align="center" colspan="2">
                                (hh:mm:ss) &nbsp; <input type='text' class='cinput' size='6' name='Tiempoesth' value='<?= $Cpo[tiempoesth] ?>'> &nbsp; hrs.
                            </td>
                        </tr>
                        <tr style="height: 35px;">
                            <td class="letrap" width="33%">
                                &nbsp; Ordinaria
                            </td>
                            <td class="letrap" width="23%"  align="center">
                                <select class="cinput" name="Entord">
                                    <?php
                                    for ($i = 0; $i <= 30; $i++) {
                                        ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php
                                    }
                                    ?>
                                    <option selected value="<?= $Cpo[entord] ?>"><?= $Cpo[entord] ?></option>
                                </select>
                                &nbsp; dias
                            </td>
                            <td class="Inpt" width="43%"  align="center" colspan="2">
                                (hh:mm:ss) &nbsp; <input type='text' class='cinput' size='6' name='Entordh' value='<?= $Cpo[entordh] ?>'> &nbsp; hrs.
                            </td>
                        </tr>
                        <tr style="background-color: rgba(0,0,0,.2);height: 35px;">
                            <td class="letrap" width="33%">
                                &nbsp; Hospitalizado:
                            </td>
                            <td class="letrap" width="23%"  align="center">
                                <select class="cinput" name="Enthosd">
                                    <?php
                                    for ($i = 0; $i <= 30; $i++) {
                                        ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php
                                    }
                                    ?>
                                    <option selected value="<?= $Cpo[enthosd] ?>"><?= $Cpo[enthosd] ?></option>
                                </select>
                                &nbsp; dias
                            </td>
                            <td class="Inpt" width="43%"  align="center" colspan="2">
                                (hh:mm:ss) &nbsp; <input type='text' class='cinput' size='6' name='Enthos' value='<?= $Cpo[enthos] ?>'> &nbsp; hrs.
                            </td>
                        </tr>
                        <tr style="height: 35px;">
                            <td class="letrap" width="33%">
                                &nbsp; Urgente
                            </td>
                            <td class="letrap" width="23%"  align="center">
                                <select class="cinput" name="Enturgd">
                                    <?php
                                    for ($i = 0; $i <= 30; $i++) {
                                        ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php
                                    }
                                    ?>
                                    <option selected value="<?= $Cpo[enturgd] ?>"><?= $Cpo[enturgd] ?></option>
                                </select>
                                &nbsp; dias
                            </td>
                            <td class="Inpt" width="43%"  align="center" colspan="2">
                                (hh:mm:ss) &nbsp; <input type='text' class='cinput' size='6' name='Enturg' value='<?= $Cpo[enturg] ?>'> &nbsp; hrs.
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr style="height: 35px" valign="middle">
                <td height="35px" width='50%' class='letrap' align='center'>
                    Interpretaciones : &nbsp;
                </td>
                <td>
                    <select class='cinput' class='InputMayusculas' name='Dobleinterpreta'>        
                        <option value='S'>Si</option>
                        <option value='N'>No</option>
                        <?php
                        if($Cpo[dobleinterpreta]=='S'){
                            $doblei='Si';
                        }elseif($Cpo[dobleinterpreta]=='N'){
                            $doblei='No';
                        }
                        ?>
                        <option SELECTED><?= $doblei ?></option>
                    </select>
                </td>
            </tr>
            <tr style="height: 30px">
                <td width='50%' class='letrap' align='center' valign="top">
                    Producto a entregar : &nbsp;
                </td>
                <td colspan="2">
                    <textarea NAME="Producto_entregar" class="letrap" type="text" rows="5" cols="35" ><?= $Cpo[producto_entregar] ?></textarea>
                </td>
            </tr>

            <tr>
                <td height="40px" align="center" class="letrap">
                    <?php
                    if ($Cpo[bloqmue] == 'Si') {
                        ?>
                        <a class="edit" href="estudiosemst.php?Op=ab&busca=<?= $busca ?>">
                            Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                        </a>
                        <?php
                    } else {
                        ?>
                        <a class="edit" href="estudiosemst.php?Op=cr&busca=<?= $busca ?>">
                            Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                        </a>
                        <?php
                    }
                    ?>
                </td>
                <td height="40px" align="center" class="letrap">
                    <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                </td>
            </tr>
        </table>
        </form>  
        </td>
        <td valign='top' width='45%' align='center'>
                    <form name="form1" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="3">
                                    ..:: Regiones Anatómicas ::..
                                </td>
                            </tr>

                            <tr style="height: 35px" valign="bottom">
                                <td width='50%' class='letrap' align='center'>
                                    Seleccione la región : &nbsp;
                                </td>
                                <td align="left">
                                    <?php
                                    $csuc = mysql_query("SELECT id,descripcion FROM reganatomica");
                                    echo "<SELECT name='region' class='letrap'>";
                                    while ($ceqp = mysql_fetch_array($csuc)) {
                                        echo "<option value='$ceqp[descripcion]'>$ceqp[id]: $ceqp[descripcion]</option>";
                                    }
                                    echo "</SELECT>";
                                    ?>
                                </td>
                                <td height="40px" align="left" class="letrap">
                                    <input class="letrap" type="submit" value='Agregar_region' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='45%' colspan="3" class='letrap' align='center'>
                                    <table align="center" width="97%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td class="letrap" width="70%">
                                                <b> Regiones Anatómicas </b>
                                            </td>                                            
                                            <td class="letrap" width="30%" align="center">
                                                <b> Eliminar </b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' colspan="3" class='letrap' align='right'>
                                    <table align="center" width="97%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;">
                                        <?php
                                        $cSql4 = "SELECT * FROM reg_a_realizar WHERE reg_a_realizar.estudio='$busca'";
                                        $result4 = mysql_query($cSql4);
                                        while ($row4 = mysql_fetch_array($result4)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }    //El resto de la division;
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td align="left"><?= $row4[proceso] ?></td>
                                                <?php
                                                if ($Cpo[bloqmue] == 'No') {
                                                    ?> 
                                                    <td align="center">
                                                        <a href="estudiosemst.php?busca=<?= $busca ?>&id=<?= $row4[id] ?>&Op=Elimregion">
                                                            <i class="fa fa-times fa-lg" style="color:red;" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td align='center'>-</td></tr>
                                                <?php
                                            }

                                            $nRng++;
                                        }
                                        ?>

                                </td>
                            </tr>
                        </table>
                    </form>  
        </td>
        <tr><td>&nbsp;&nbsp;</td></tr>
            <?php TablaDeLogs("/Estudios/Muestras/", $busca); ?>
        <td valign='top' width="22%">
            <?php
            $sbmn = 'Muestras';
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
