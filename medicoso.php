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
if ($_REQUEST[bt] == "Actualizar") {
    $sql = "UPDATE med SET diasconsulta='$_REQUEST[Diasconsulta]',hraconsulta='$_REQUEST[Hraconsulta]',hravisita='$_REQUEST[Hravisita]'"
            . ",comision='$_REQUEST[Comision]',status='$_REQUEST[Status]',clasificacion='$_REQUEST[Clasificacion]',promotorasig='$_REQUEST[Promotorasig]' "
            . "WHERE id = $busca";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Medicos/Otros/Actualiza Horarios',
            'med','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
} elseif ($_REQUEST[bt] == "Actualiza contacto") {
    $sql = "UPDATE med SET zona = '$_REQUEST[Zona]', institucion = '$_REQUEST[Institucion]', institucionp = '$_REQUEST[Institucionp]', ruta = '$_REQUEST[Ruta]'"
            . " WHERE id = '$busca'";
    if (!mysql_query($sql)) {
        $msj = "Error de sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Medicos/Otros/Actualiza la Zona del cliente',"
            . "'med','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
} elseif ($_REQUEST[bt] == "Actualiza Observaciones") {
    $sql = "UPDATE med SET refubicacion = '$_REQUEST[Refubicacion]', servicio = '$_REQUEST[Servicio]', observaciones = '$_REQUEST[Observaciones]'
            WHERE id = '$busca'";
    if (!mysql_query($sql)) {
        $msj = "Error de sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Medicos/Otros/Actualiza las Observaciones'
            ,'med','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
}

$sql = "SELECT * FROM med WHERE id = '$busca'";
$CpoA = mysql_query($sql);
$Cpo = mysql_fetch_array($CpoA);
$Estado = $_REQUEST[Estado];
$Msj = $_REQUEST[Msj];
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Clientes - Otros</title>
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
                    ..:: Informacion de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Horarios ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Dias de consulta : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Diasconsulta' value='<?= $Cpo[diasconsulta] ?>' MAXLENGTH='30'> Id : <?= $Cpo[id] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Hras de consulta : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 100px;" class='cinput'  name='Hraconsulta' value='<?= $Cpo[hraconsulta] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Horas de visita : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 100px;" class='cinput'  name='Hravisita' value='<?= $Cpo[hravisita] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    % Comision : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 45px;" class='cinput'  name='Comision' value='<?= $Cpo[comision] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Status : 
                                </td>
                                <td class="Inpt">
                                    <select class="cinput" name='Status'>
                                        <option value='Activo'>Activo</option>
                                        <option value='Inactivo'>Inactivo</option>
                                        <option value='Defuncion'>Defuncion</option>
                                        <option value='Baja'>Baja</option>
                                        <option value='Otro'>Otro</option>
                                        <option selected><?= $Cpo[status] ?></option>
                                    </select>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Clasificacion : 
                                </td>
                                <td class="Inpt">
                                    <select class="cinput" name='Clasificacion'>
                                        <option value='A'>A</option>
                                        <option value='B'>B</option>
                                        <option value='C'>C</option>
                                        <option value='D'>D</option>
                                        <option selected><?= $Cpo[clasificacion] ?></option>
                                    </select>                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Promotor Asignado : 
                                </td>
                                <td class="Inpt">
                                    <select class="cinput" name='Promotorasig'>
                                        <option value='Promotor_A'>Promotor_A</option>
                                        <option value='Promotor_B'>Promotor_B</option>
                                        <option value='Promotor_C'>Promotor_C</option> 
                                        <option value='Promotor_D'>Promotor_D</option>
                                        <option value='Promotor_E'>Promotor_E</option>
                                        <option value='Promotor_F'>Promotor_F</option>
                                        <option value='Base'>Base</option>
                                        <option selected><?= $Cpo[promotorasig] ?></option>
                                    </select>
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
                    <br></br>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Zona ::.
                                </td>
                            </tr>
                            <tr><td class='Inpt' align='right'>Zona : </td>
                                <td> 
                                    <select class="cinput" name='Zona'>
                                        <?php
                                        $ZnaA = mysql_query("SELECT zona,descripcion FROM zns order by zona");
                                        while ($Zna = mysql_fetch_array($ZnaA)) {
                                            echo "<option value='$Zna[zona]'> $Zna[zona] &nbsp; &nbsp; $Zna[descripcion]</option>";
                                        }
                                        $ZnaA = mysql_query("SELECT descripcion FROM zns WHERE zona='$Cpo[zona]'");
                                        $cSql = mysql_fetch_array($ZnaA);
                                        ?>
                                        <option value='<?= $Cpo[zona] ?>' selected><?= $Cpo[zona] . " " . $cSql[descripcion] ?></option>";
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="Inpt"align='right'>
                                    Institucion :
                                </td>
                                <td>
                                    <select class="cinput" name='Institucion'>
                                        <?php
                                        $cIns = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
                                        while ($Ins = mysql_fetch_array($cIns)) {
                                            echo "<option value='$Ins[institucion]'> $Ins[institucion] &nbsp; $Ins[alias]</option>";
                                        }
                                        $sql = mysql_query("SELECT alias FROM inst WHERE institucion='$Cpo[institucion]'");
                                        $cSql = mysql_fetch_array($sql);
                                        echo "<option value='$Cpo[institucion]' selected>$Cpo[institucion]&nbsp;$cSql[alias]</option>";
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align='right'>
                                    Institucion de pago:
                                </td>
                                <td>
                                    <select class="cinput" name="Institucionp">
                                        <?php
                                        $cIns = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
                                        while ($Ins = mysql_fetch_array($cIns)) {
                                            echo "<option value='$Ins[institucion]'> $Ins[institucion]&nbsp;$Ins[alias]</option>";
                                        }
                                        $cSql1 = "SELECT alias FROM inst WHERE institucion='$Cpo[institucionp]'";
                                        $sql = mysql_query($cSql1);
                                        $cSql = mysql_fetch_array($sql);
                                        echo "<option value='$Cpo[institucionp]' selected>$Cpo[institucionp]&nbsp;$cSql[alias]</option>";
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="Inpt" align='right'>Ruta medica:</td><td>
                                    <select class="cinput" name='Ruta'>
                                        <?php
                                        $RtaA = mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
                                        while ($Rta = mysql_fetch_array($RtaA)) {
                                            echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
                                        }
                                        $sql = mysql_query("SELECT id,descripcion FROM ruta WHERE id='$Cpo[ruta]'");
                                        $cSql = mysql_fetch_array($sql);
                                        echo "<option selected value='$Cpo[ruta]'>$cSql[descripcion]</option>";
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualiza contacto' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>  
                </td>
                <td valign='top' width='45%'>
                    <form name='form3' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Observaciones ::.
                                </td>
                            </tr>
                            <tr>
                                <td class='Inpt' align='right'>
                                    Referencia de la ubicacion : 
                                </td>
                                <td> 
                                    <textarea class="cinput" name="Refubicacion" type="text" rows="3" cols="45"><?= $Cpo[refubicacion] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class='Inpt' align='right'>
                                    Caracteristicas del Servicio : 
                                </td>
                                <td> 
                                    <textarea class="cinput" name="Servicio" type="text" rows="3" cols="45"><?= $Cpo[servicio] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class='Inpt' align='right'>
                                    Observaciones : 
                                </td>
                                <td> 
                                    <textarea class="cinput" name="Observaciones" type="text" rows="3" cols="45"><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualiza Observaciones' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
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
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Catalogos/Medicos/Otros%') 
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
                    SbmenuMed();
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

