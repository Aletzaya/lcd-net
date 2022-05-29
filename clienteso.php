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
$msj = $_REQUEST[msj];
$Fecha = date("Y-m-d H:m:s");
if ($_REQUEST[bt] == "Actualiza titular") {
    $Nombrec = $_REQUEST[Apellidop] . "" . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];
    $sql = "UPDATE cli SET titular='$_REQUEST[Titular]',credencial='$_REQUEST[Credencial]',otro='$_REQUEST[Otro]',clasificacion='$_REQUEST[Clasificacion]'"
            . " WHERE cliente = $busca;";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis SQL : " . $sql;
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Clientes/Otros/Actualizo el Titutlar $_REQUEST[Titular]','cli', "
            . "'$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    } else {
        $msj = "Cambio ejecutado con exito";
    }
    header("Location: clienteso.php?busca=$busca&msj=$msj");
} elseif ($_REQUEST[bt] == "Actualiza contacto") {
    $sql = "UPDATE cli SET zona='$_REQUEST[Zona]', institucion = '$_REQUEST[Institucion]', programa = '$_REQUEST[Programa]', "
            . "padecimiento = '$_REQUEST[Padecimiento]',observaciones='$_REQUEST[Observaciones]' "
            . "WHERE cliente = $busca;";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis SQL : " . $sql;
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Clientes/Otros/Actualizo Otros datos','cli','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    } else {
        $msj = "Cambio ejecutado con exito";
    }
    header("Location: clienteso.php?busca=$busca&msj=$msj");
}
$CpoA = mysql_query("SELECT * FROM cli WHERE cliente = $busca");
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
        <title>Clientes - Otros ::..</title>
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

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Titular del paciente ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Titular : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Titular' value='<?= $Cpo[titular] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Credencial : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Credencial' value='<?= $Cpo[credencial] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Otro dato : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Otro' value='<?= $Cpo[otro] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Clasificacion Familiar : 
                                </td>
                                <td>
                                    <select class="Inpt" name="Clasificacion">
                                        <option value="El mismo">El mismo</option>
                                        <option value="Esposa">Esposa</option>
                                        <option value="Esposo">Esposo</option>
                                        <option value="1er. hijo">1er. hijo</option>
                                        <option value="2do. hijo">2do. hijo</option>
                                        <option value="3er. hijo">3er. hijo</option>
                                        <option value="4to. hijo">4to. hijo</option>
                                        <option value="5to. hijo">5to. hijo</option>
                                        <option value="Mamá">Mamá</option>
                                        <option value="Papá">Papá</option>
                                        <option value="Concubina">Concubina</option>
                                        <option value="Otro">Otro</option>
                                        <option value="<?= $Cpo[clasificacion] ?>" selected><?= $Cpo[clasificacion] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualiza titular' name='bt'></input>
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
                                    .:: Otros ::.
                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align="right">
                                    Zona :
                                </td>
                                <td class="Inpt">
                                    <select name="Zona" class="Inpt">
                                        <?php
                                        $cSql = mysql_query("SELECT descripcion FROM zns WHERE zona = '$Cpo[zona]'");
                                        $sql = mysql_fetch_array($cSql);
                                        $ZnaA = mysql_query("SELECT zona,descripcion FROM zns ORDER BY zona");
                                        while ($Zna = mysql_fetch_array($ZnaA)) {
                                            echo "<option value='$Zna[zona]'>$Zna[zona] .- $sql[descripcion]</option>";
                                        }
                                        ?>
                                        <option value="<?= $Cpo[zona] ?>" selected><?= $Cpo[zona] ?></option>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align="right">
                                    Institucion :
                                </td>
                                <td>
                                    <select name="Institucion" class="Inpt">
                                        <?php
                                        $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
                                        while ($Ins = mysql_fetch_array($InsA)) {
                                            echo "<option value='$Ins[institucion]'>$Ins[institucion]  $Ins[alias]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align="right">
                                    Programas de salud :
                                </td>
                                <td>
                                    <select name="Programa" class="Inpt">
                                        <?php
                                        echo "<option value='1'>1.Cliente frecuente</option>";
                                        echo "<option value='2'>2.Apoyo a la salud</option>";
                                        echo "<option value='3'>3.Chequeo medico</option>";
                                        echo "<option value='4'>4.Empleado</option>";
                                        echo "<option value='5'>5.Familiar</option>";
                                        echo "<option value='6'>6.Medico</option>";
                                        echo "<option value='7'>7.Especializado</option>";
                                        echo "<option value='0'>0.Ninguno</option>";
                                        if ($Cpo[programa] == 1) {
                                            $rs = "1.Cliente frecuente";
                                        } elseif ($Cpo[programa] == 2) {
                                            $rs = "2.Apoyo a la salud";
                                        } elseif ($Cpo[programa] == 3) {
                                            $rs = "3.Chequeo medico";
                                        } elseif ($Cpo[programa] == 4) {
                                            $rs = "4.Empleado";
                                        } elseif ($Cpo[programa] == 5) {
                                            $rs = "5.Familiar";
                                        } elseif ($Cpo[programa] == 6) {
                                            $rs = "6.Medico";
                                        } elseif ($Cpo[programa] == 7) {
                                            $rs = "7.Especializado";
                                        } elseif ($Cpo[programa] == 0) {
                                            $rs = "0.Ninguno";
                                        }
                                        ?>
                                        <option value="<?= $Cpo[programa] ?>" selected><?= $rs ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align="right">
                                    Padecimientos:
                                </td>
                                <td>
                                    <textarea class="cinput" name="Padecimiento" type="text" rows="3" cols="45"><?= $Cpo[padecimiento] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align="right">
                                    Observaciones:
                                </td>
                                <td>
                                    <textarea class="cinput" name="Observaciones" type="text" rows="3" cols="45"><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualiza contacto' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                        <br></br>
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
                                        $PgsA = mysql_query("SELECT * FROM log WHERE accion like ('/Catalogos/Clientes/Otros%') AND cliente=$busca ORDER BY id DESC LIMIT 6;");
                                        while ($rg = mysql_fetch_array($PgsA)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
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

                    </form>
                </td>
                <td valign='top' width="22%">
                    <?php
                    SbmenuCli();
                    ?>
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
