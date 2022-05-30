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
$suc = $_REQUEST[ele];

if ($suc == 1) {
    $tablasuc = 'procestex';
    $logeqp = 'logesteqp';
    $logeqp2 = 'Texcoco';
} elseif ($suc == 2) {
    $tablasuc = 'procestep';
    $logeqp = 'logesteqp2';
    $logeqp2 = 'Tepexpan';
} elseif ($suc == 3) {
    $tablasuc = 'proceshf';
    $logeqp = 'logesteqp3';
    $logeqp2 = 'Futura';
} elseif ($suc == 4) {
    $tablasuc = 'procesrys';
    $logeqp = 'logesteqp4';
    $logeqp2 = 'Reyes';
} elseif ($suc == 5) {
    $tablasuc = 'procescam';
    $logeqp = 'logesteqp5';
    $logeqp2 = 'Camarones';
} elseif ($suc == 6) {
    $tablasuc = 'processvc';
    $logeqp = 'logesteqp6';
    $logeqp2 = 'San Vicente';
}

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST[busca];
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Producto = $_REQUEST[Producto];
$idproducto = $_REQUEST[idproducto];
$Retornar = "estudios.php";
$Fecha = date("Y-m-d");
$date = date("Y-m-d H:i:s");
if ($_REQUEST["bt"] == 'Actualizar') {
    $sql = "SELECT bloqeqp FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqeqp] == 'No') {
        $Msj = "Registro actualizado con exito";
        $sql = "SELECT id FROM $tablasuc WHERE estudio='$busca'";
        $cSql = mysql_query($sql);
        $sql = mysql_fetch_array($cSql);

        if (!is_numeric($sql["id"])) {

            $sql = "INSERT INTO $tablasuc (estudio,equipo,tecnica,maquila1,maquila2,maquila3,estructura,matyeq,preparacion,posicion,tecnicaeq,"
                    . "postadq,mixtolcd,mixtotpx,mixtohf,mixtorys,mixtomaq) "
                    . "VALUES ('$busca','$_REQUEST[Equipo]','$_REQUEST[Tecnica]','$_REQUEST[Maquila1]','$_REQUEST[Maquila2]','$_REQUEST[Maquila3]'"
                    . ",'$_REQUEST[Estructura]','$_REQUEST[Matyeq]','$_REQUEST[Preparacion]','$_REQUEST[Posicion]',"
                    . "'$_REQUEST[Tecnicaeq]','$_REQUEST[Postadq]','0','0','0','0','0');";
            $Msj = "!Se agrega primer detalle con exito¡";
            if (!mysql_query($sql)) {
                $Msj = "Error" . $sql . " .-" . mysql_error() . "&Error=SI";
            }
        }

        $cSql = "UPDATE $tablasuc SET equipo = '$_REQUEST[Equipo]',tecnica = '$_REQUEST[Tecnica]',maquila1='$_REQUEST[Maquila1]',"
                . "maquila2='$_REQUEST[Maquila2]',maquila3='$_REQUEST[Maquila3]',estructura='$_REQUEST[Estructura]',matyeq='$_REQUEST[Matyeq]',"
                . "preparacion='$_REQUEST[Preparacion]',posicion='$_REQUEST[Posicion]',tecnicaeq='$_REQUEST[Tecnicaeq]',postadq='$_REQUEST[Postadq]'   "
                . "WHERE estudio='$busca'";

        $cId = mysql_insert_id();

        $cProceso = "Agrega est $cId ";
        if (!mysql_query($cSql)) {
            $Msj = "Error" . mysql_error();
        }

        AgregaBitacoraEventos($Gusr, '/Estudios/Equipos por Unidad/'.$logeqp2.'/Actualiza Equipo ', "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioseeu.php");

        $date = date("Y-m-d H:i:s");
    } else {
        $Msj = "!Error¡ Se encuentra cerrado Equipos";
        header("Location: estudioseeu.php?busca=$busca&Msj=$Msj&Error=SI&ele=$suc");
    }

} elseif ($op == 'Registrar') {

    if ($idproducto <> '' AND $_REQUEST[Cantidad] > 0) {

        $sql = "INSERT INTO estd (estudio,producto,idproducto,cantidad,suc) 
                 VALUES 
                 ('$busca','$Producto','$idproducto','$_REQUEST[Cantidad]',$suc)";

        $Msj = "Producto agregado con exito";

        if (!mysql_query($sql)) {
            echo "Error en sintaxis Mysql " . $sql;
        }

        AgregaBitacoraEventos($Gusr, '/Estudios/Equipos por Unidad/'.$logeqp2.'/Agrega Producto', "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioseeu.php");

        $Producto = "";
        $idproducto = "";
        $descripcion = " - ";
    }
}

if ($_REQUEST["Op"] == "ab") {

    $sql = "UPDATE est SET bloqeqp = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }

    $Msj = "Registro abierto con exito!";

    AgregaBitacoraEventos($Gusr, '/Estudios/Equipos por Unidad/'.$logeqp2.'/Abre Equipos', "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioseeu.php");

} elseif ($_REQUEST["Op"] == "cr") {

    $sql = "UPDATE est SET bloqeqp = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }

    $Msj = "Registro cerrado con exito!";

    AgregaBitacoraEventos($Gusr, '/Estudios/Equipos por Unidad/'.$logeqp2.'/Cierra Equipos', "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioseeu.php");

} elseif ($_REQUEST["Op"] == "Elim") {

        $Sql = "DELETE FROM estd WHERE estudio='$busca' AND producto='$_REQUEST[producto]' and suc='$suc' and idproducto='$_REQUEST[idproducto]'";
        $pSql = mysql_query($Sql);
        $Msj = "Producto Eliminado con exito";
        if (!mysql_query($sql)) {
            echo "Error en sintaxis Mysql " . $sql;
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Equipos por Unidad/'.$logeqp2.'/Elimina Producto', "estd", $date, $busca, $Msj . "&ele=" . $suc, "estudioseeu.php");

}

$Cpql = "SELECT * FROM $tablasuc WHERE estudio='$busca' limit 1";
$CpoA = mysql_query($Cpql);
$Cpo = mysql_fetch_array($CpoA);


$CpoB = mysql_query("SELECT descripcion FROM est WHERE (estudio= '$busca')");
$Cpob = mysql_fetch_array($CpoB);

$CpoC = mysql_query("SELECT descripcion FROM invl WHERE id=$idproducto");
$Cpoc = mysql_fetch_array($CpoC);
$descripcion=$Cpoc[descripcion];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Equipos x Unidad</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
//        encabezados();
//        menu($Gmenu, $Gusr);

//Tabla contenedor de brighs
        echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr >
                <td colspan="3" bgcolor='#2c8e3c' width='90%' class='Subt' align='center'>
                    ...::: Equipos por Unidad (<?= $busca ?>) <?= $Cpob[descripcion] ?> :::...
                </td>
            </tr>
            <tr>
                <td valign='top' width='43%' align='center'>
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>

                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                ..:: Equipo ::..
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>
                                    <table width="100%" align="center" cellpadding="0" cellspacing="0" style='border-collapse: collapse; border: 1px solid #c0c0c0;'>
                                        <tr class="letrap">
                                            <?php
                                            if ($suc == 1) {
                                                ?>
                                                <td class="ssbm" bgcolor="#6580A2" height="30px" align="center">
                                                    <a href="estudioseeu.php?ele=1&busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><font color='#FFF'><b>LCD-TX</b></font></a>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td class="ssbm" height="30px" align="center">
                                                    <a href="estudioseeu.php?ele=1&busca=<?= $_REQUEST[busca] ?>" class="sbmnu">LCD-TX</a>
                                                </td>
                                                <?php
                                            }
                                            if ($suc == 2) {
                                                ?>
                                                <td class="ssbm" bgcolor="#6580A2" align="center">
                                                    <a href="estudioseeu.php?ele=2&busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><font color='#FFF'><b>LCD-TPX</b></font></a>
                                                </td>
                                            <?php } else {
                                                ?>
                                                <td class="ssbm" align="center">
                                                    <a href="estudioseeu.php?ele=2&busca=<?= $_REQUEST[busca] ?>" class="sbmnu">LCD-TPX</a>
                                                </td>
                                                <?php
                                            }
                                            if ($suc == 3) {
                                                ?>
                                                <td class="ssbm" bgcolor="#6580A2" align="center">
                                                    <a href="estudioseeu.php?ele=3&busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><font color='#FFF'><b>LCD-HF</b></font></a>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td class="ssbm" align="center">
                                                    <a href="estudioseeu.php?ele=3&busca=<?= $_REQUEST[busca] ?>" class="sbmnu">LCD-HF</a>
                                                </td>
                                                <?php
                                            }
                                            if ($suc == 4) {
                                                ?>
                                                <td class="ssbm" bgcolor="#6580A2" align="center">
                                                    <a href="estudioseeu.php?ele=4&busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><font color='#FFF'><b>LCD-RYS</b></font></a>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td class="ssbm" align="center">
                                                    <a href="estudioseeu.php?ele=4&busca=<?= $_REQUEST[busca] ?>" class="sbmnu">LCD-RYS</a>
                                                </td>
                                                <?php
                                            }
                                            if ($suc == 5) {
                                                ?>
                                                <td class="ssbm" bgcolor="#6580A2" align="center">
                                                    <a href="estudioseeu.php?ele=5&busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><font color='#FFF'><b>LCD-CAM</b></font></a>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td class="ssbm" align="center">
                                                    <a href="estudioseeu.php?ele=5&busca=<?= $_REQUEST[busca] ?>" class="sbmnu">LCD-CAM</a>
                                                </td>
                                                <?php
                                            }
                                            if ($suc == 6) {
                                                ?>
                                                <td class="ssbm" bgcolor="#6580A2" align="center">
                                                    <a href="estudioseeu.php?ele=6&busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><font color='#FFF'><b>LCD-SVC</b></font></a>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td class="ssbm" align="center">
                                                    <a href="estudioseeu.php?ele=6&busca=<?= $_REQUEST[busca] ?>" class="sbmnu">LCD-SVC</a>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                        <from name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="height: 30px">
                                <td class='letrap' align='right'>
                                    Equipo : &nbsp;
                                </td>
                                <td colspan="2">
                                    <?php
                                    $csuc = mysql_query("SELECT * FROM eqp");
                                    echo "<SELECT name='Equipo' class='letrap'>";
                                    while ($ceqp = mysql_fetch_array($csuc)) {
                                        echo "<option value='$ceqp[2]'>$ceqp[0]: $ceqp[2]</option>";
                                    }
                                    echo "<option SELECTED value='$Cpo[equipo]'>$Cpo[equipo]</option>";
                                    echo "</SELECT>";
                                    ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' class='letrap' align='right'>Tecnica : &nbsp;
                                </td>
                                <td colspan="2">
                                    <SELECT style='width:270px' class='letrap' name='Tecnica'>
                                        <?php
                                        $ctec = mysql_query("SELECT * FROM tec");
                                        echo "<option value=''>*** Ninguno ***</option>";
                                        while ($tec = mysql_fetch_array($ctec)) {
                                            echo "<option value='$tec[tecnica]'>$tec[id]: $tec[tecnica]</option>";
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[tecnica] ?>"><?= $Cpo[tecnica] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px"><td width='45%' class='letrap' align='right'>Externo 1 : &nbsp;</td>
                                <td colspan="2">
                                    <select style="width:270px" class='letrap' class='InputMayusculas' name='Maquila1'>        

                                        <?php
                                        $sql = "SELECT * FROM mql";

                                        $SqlC = mysql_query($sql);
                                        echo "<option value=''>*** Ninguno ***</option>";
                                        WHILE ($mql = mysql_fetch_array($SqlC)) {
                                            echo "<option value='$mql[1]'>$mql[0]: $mql[1]</option>";
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[maquila1] ?>"><?= $Cpo[maquila1] ?></option>
                                    </select> 
                                </td>
                            </tr>
                            <tr style="height: 30px"><td width='45%' class='letrap' align='right'>Externo 2 : &nbsp;</td>
                                <td colspan="2">
                                    <select style="width:270px" class='letrap' class='InputMayusculas' name='Maquila2'>        

                                        <?php
                                        $sql = "SELECT * FROM mql";
                                        $SqlC = mysql_query($sql);
                                        echo "<option value=''>*** Ninguno ***</option>";
                                        WHILE ($Sub = mysql_fetch_array($SqlC)) {
                                            echo "<option value='$Sub[nombre]'>" . $Sub[id] . " &nbsp; " . $Sub[nombre] . "</option>";
                                        }
                                        echo '<option selected="' . $Cpo[maquila2] . '"> ' . $Cpo[maquila2] . '</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px"><td width='45%' class='letrap' align='right'>Externo 3 : &nbsp;</td>
                                <td colspan="2">
                                    <select style="width:270px" class='letrap' class='InputMayusculas' name='Maquila3'>
                                        <?php
                                        $sql = "SELECT * FROM mql";
                                        $SqlC = mysql_query($sql);
                                        echo "<option value=''>*** Ninguno ***</option>";
                                        WHILE ($Sub = mysql_fetch_array($SqlC)) {
                                            echo "<option value='$Sub[nombre]'>" . $Sub[id] . " &nbsp; " . $Sub[nombre] . "</option>";
                                        }
                                        echo '<option selected="' . $Cpo[maquila3] . '"> ' . $Cpo[maquila3] . '</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Estructura a Valorar:&nbsp;</td>
                                <td height="40px"colspan="2" align="center" class="letrap">
                                    <textarea name="Estructura" class="letrap" type="text" rows="3" cols="55"><?= $Cpo[estructura] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Material necesario:&nbsp;</td>
                                <td height="40px" colspan="2" align="center" class="letrap">
                                    <textarea name="Matyeq" class="letrap" type="text" rows="3" cols="55"><?= $Cpo[matyeq] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Preparac. Pac.:&nbsp;</td>
                                <td height="40px"colspan="2" align="center" class="letrap">
                                    <textarea name="Preparacion" class="letrap" type="text" rows="3" cols="55"><?= $Cpo[preparacion] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Posicionam. Pac.:&nbsp;</td>
                                <td height="40px"colspan="2" align="center" class="letrap">
                                    <textarea name="Posicion" class="letrap" type="text" rows="3" cols="55"><?= $Cpo[posicion] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Tecnica Sugerida:&nbsp;</td>
                                <td height="40px"colspan="2" align="center" class="letrap">
                                    <textarea name="Tecnicaeq" class="letrap" type="text" rows="3" cols="55"><?= $Cpo[tecnicaeq] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px" valign="top"><td width='20%' class='letrap' align='right'>Post adquision Est.:&nbsp;</td>
                                <td height="40px"colspan="2" align="center" class="letrap">
                                    <textarea name="Postadq" class="letrap" type="text" rows="3" cols="55"><?= $Cpo[postadq] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td height="40px" align="center" class="letrap">
                                    <?php
                                    $sql = "SELECT bloqeqp FROM est WHERE estudio= '$busca'";
                                    $CpoA = mysql_query($sql);
                                    $Cpo1 = mysql_fetch_array($CpoA);
                                    if ($Cpo1[bloqeqp] == 'Si') {
                                        ?>
                                        <a class="edit" href="estudioseeu.php?Op=ab&busca=<?= $busca ?>&ele=<?= $suc ?>">
                                            Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a class="edit" href="estudioseeu.php?Op=cr&busca=<?= $busca ?>&ele=<?= $suc ?>">
                                            Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                    <input type="hidden" value="<?= $suc ?>" name="ele"></input>
                                    <input type="hidden" value="<?= $Producto ?>" name="Producto"></input>
                                </td>
                            </tr>
                        </from>
                    </table>
                </td>
                <td valign='top' width='45%'>
                    <from name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="3">
                                    ..:: Detalle ::..
                                </td>
                            </tr>
                            <tr style="height: 20px" valign="top">
                                <td align="left" class='letrap'><br>
                                        <?php
                                        if ($Cpo1[bloqeqp] == 'No') {
                                            ?>
                                            <a href="invlab.php?orden=invl.descripcion&estudio=<?= $busca ?>&suc=<?= $suc ?>" class="edit"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
                                            <?php                                            
                                        }
                                        ?>
                                        Producto : <input class='letrap' style="width: 50px" type="text" value='<?= $idproducto ?>' name='idproducto'></input><br><?php echo "<font size='1' color='red'> &nbsp; &nbsp; <b> $descripcion </b></font>"; ?>
                                </td>
                                <td align="left" class='letrap'><br>
                                        <i class="fa fa-hashtag fa-2x" aria-hidden="true"></i> Cantidad : <input class='letrap' type="text" style="width: 50px" value='<?= $Cpo[A] ?>' name='Cantidad'></input>
                                </td>
                                <?php
                                if ($Cpo1[bloqeqp] == 'No') {
                                    ?>
                                    <td align='center'><br>
                                        <input type="submit" value='Registrar' name='op'></input>
                                    </td>
                                    <?php
                                } else {
                                    echo "<td></td>";
                                }
                                ?>
                            </tr>
                        </table>
                    </from>

                    <?php
                    $cSql2 = "SELECT estd.estudio,estd.producto,estd.idproducto,estd.cantidad,invl.descripcion,invl.clave FROM estd,invl WHERE estd.estudio='$busca' and estd.idproducto=invl.id and estd.suc='$suc'";

                    $result2 = mysql_query($cSql2);
                    ?>
                    <br>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <?php
                            while ($row2 = mysql_fetch_array($result2)) {

                                if (($nRng % 2) > 0) {
                                    $Fdo = 'FFFFFF';
                                } else {
                                    $Fdo = $Gfdogrid;
                                }    //El resto de la division;

                                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                                echo "<td align='left' class='letrap'> $Gfont $row2[idproducto] &nbsp; &nbsp; </td><td align='left' class='letrap'> $Gfont $row2[producto] &nbsp; &nbsp; </td><td align='left' class='letrap'>$Gfont $row2[descripcion]</td><td align='center' class='letrap'>$Gfont $row2[cantidad]</td>";

                                if ($Cpo1[bloqeqp] == 'No') {
                                    echo "<td align='center'><a name='dt' value='dtsnm' href='estudioseeu.php?busca=$busca&producto=$row2[producto]&idproducto=$row2[idproducto]&Op=Elim&ele=$suc'><i class='fa fa-trash-o fa-1x' aria-hidden='true'></i></a></tr>";

                                } else {
                                    echo "<td align='center'>-</td></tr>";
                                }

                                $nRng++;
                            }
                            ?>
                        </table>

                        <table>
                            <tr>
                                <td height="20px">

                                </td>
                            </tr>
                        </table> 
                        <?php
                        TablaDeLogs("/Estudios/Equipos por Unidad/".$logeqp2."/", $busca);
                        ?>
                </td>
                <td valign='top' width="22%">
                    <?php
                    $sbmn = 'Equipos';
                    Sbmenu();
                    ?>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                </td>
            </tr>        
        </table>    
    </body>
</html>
<?php
mysql_close();
?>
