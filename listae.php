<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");

if ($_REQUEST[bt] == "Actualizar") {

    $sql = "UPDATE est SET descripcion='$_REQUEST[Descripcion]',estudio='$_REQUEST[Estudio]',objetivo='$_REQUEST[Objetivo]',"
            . "condiciones='$_REQUEST[Condiciones]',tubocantidad='$_REQUEST[Tubocantidad]',tiempoest='$_REQUEST[Tiempoest]',"
            . "equipo='$_REQUEST[Equipo]',tecnica='$_REQUEST[Tecnica]' WHERE id=$busca";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Precios/Info. General/Edita el Detalle del Producto',"
            . "'est','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $msj = "Cambio ejecutado con exito";
    header("Location: listae.php?busca=$busca&msj=$msj");
} elseif ($_REQUEST[bt] == "Actualiza") {
    $sql = "UPDATE est SET lt1='$_REQUEST[Lt1]', lt2='$_REQUEST[Lt2]', lt3='$_REQUEST[Lt3]', lt4='$_REQUEST[Lt4]', lt5='$_REQUEST[Lt5]', "
            . "lt6='$_REQUEST[Lt6]', lt7='$_REQUEST[Lt7]', lt8='$_REQUEST[Lt8]', lt9='$_REQUEST[Lt9]', lt10='$_REQUEST[Lt10]', lt11='$_REQUEST[Lt11]', "
            . "lt12='$_REQUEST[Lt12]', lt13='$_REQUEST[Lt13]', lt14='$_REQUEST[Lt14]',lt15='$_REQUEST[Lt15]', lt16='$_REQUEST[Lt16]', "
            . "lt17='$_REQUEST[Lt17]', lt18='$_REQUEST[Lt18]', lt19='$_REQUEST[Lt19]', lt20='$_REQUEST[Lt20]', lt21='$_REQUEST[Lt21]', "
            . "lt22='$_REQUEST[Lt22]', lt23='$_REQUEST[Lt23]' WHERE id = $busca";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Precios/Info. General/Edita precios de las inst'"
            . ",'est','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $msj = "Cambio ejecutado con exito";
    header("Location: listae.php?busca=$busca&msj=$msj");
} elseif ($_REQUEST[bt] == "Actualizar Fac") {
    $sql = "UPDATE est SET inv_cunidad='$_REQUEST[cumedida]',inv_cproducto='$_REQUEST[common_claveps]' WHERE id='$busca' limit 1";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Precios/Info. General/Actualiza la clasificacion de su facturación'"
            . ",'est','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $msj = "Cambio ejecutado con exito";
    header("Location: listae.php?busca=$busca&msj=$msj");
}
$msj = $_REQUEST[msj];
$sql = "SELECT * FROM est WHERE id = '$busca'";

$CpoA = mysql_query($sql);
$Cpo = mysql_fetch_array($CpoA);
if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
} else {
    $Estado = $Cpo[estado];
}
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Precios - Info. General</title>
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
        <script src="./controladores.js"></script>
    <?php
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle de Producto ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Id : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Id' value='<?= $Cpo[id] ?>' style="width: 60px" disabled>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Descripción : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Descripcion" type="text" rows="2" cols="40"><?= $Cpo[descripcion] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Estudio :
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput' style="width: 60px;" name='Estudio' value='<?= $Cpo[estudio] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Objetivo : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Objetivo" type="text" rows="4" cols="40"><?= $Cpo[objetivo] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Condiciones : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Condiciones" type="text" rows="3" cols="40"><?= $Cpo[condiciones] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Tubo Cantidad : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Tubocantidad' value='<?= $Cpo[tubocantidad] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Tiempo Estimado : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 50px;" class='cinput'  name='Tiempoest' value='<?= $Cpo[tiempoest] ?>'> Hrs.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Equipo : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput' style="width: 200px;" name='Equipo' value='<?= $Cpo[equipo] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Tecnica : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput' style="width: 200px;" name='Tecnica' value='<?= $Cpo[tecnica] ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>

                        </table>
                    </form>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Clasificación para su Facturación del producto ::..
                                </td>
                            </tr>
                            <br></br>
                            <tr>
                                <td align="right" class="Inpt">
                                    Unidad de medida : 
                                </td>
                                <td class="Inpt">
                                    <SELECT class='cinput' name='Inv_cunidad'>
                                        <?php
                                        $MpioA = mysql_query("SELECT clave, descripcion FROM cfdi33_c_fpago WHERE status = 1");
                                        while ($Mpio = mysql_fetch_array($MpioA)) {
                                            echo "<option class='cinput' value='$Mpio[clave]'>$Mpio[clave] .- $Mpio[descripcion]</option>";
                                        }
                                        ?>
                                        <option class="cinput" value='<?= $Cpo[inv_cunidad] ?>' selected></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" class="Inpt">
                                    Clave de Producto/Servicio : 
                                </td>
                                <td class="Inpt">
                                    <SELECT class='cinput' name='Inv_cproducto'>
                                        <?php
                                        $MpioA = mysql_query("SELECT clave, nombre FROM cfdi33_c_conceptos WHERE status = '1'");
                                        while ($Mpio = mysql_fetch_array($MpioA)) {
                                            echo "<option class='cinput' value='$Mpio[clave]'>$Mpio[nombre]</option>";
                                        }
                                        ?>
                                        <option class="cinput" value='<?= $Cpo[inv_cproducto] ?>' selected></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualizar Fac' name='bt'></input>
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
                                                WHERE accion like ('/Catalogos/Precios/%') 
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
                <td valign='top' width='45%'>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Precios ::.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.1 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt1' value='<?= $Cpo[lt1] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.2 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt2' value='<?= $Cpo[lt2] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.3 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt3' value='<?= $Cpo[lt3] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.4 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt4' value='<?= $Cpo[lt4] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.5 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt5' value='<?= $Cpo[lt5] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.6 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt6' value='<?= $Cpo[lt6] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.7 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt7' value='<?= $Cpo[lt7] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.8 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt8' value='<?= $Cpo[lt8] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.9 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt9' value='<?= $Cpo[lt9] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.10 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt10' value='<?= $Cpo[lt10] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.11 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt11' value='<?= $Cpo[lt11] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.12 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt12' value='<?= $Cpo[lt12] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.13 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt13' value='<?= $Cpo[lt13] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.14 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt14' value='<?= $Cpo[lt14] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.15 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt15' value='<?= $Cpo[lt15] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.16 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt16' value='<?= $Cpo[lt16] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.17 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt17' value='<?= $Cpo[lt17] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.18 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt18' value='<?= $Cpo[lt18] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.19 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt19' value='<?= $Cpo[lt19] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.20 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt20' value='<?= $Cpo[lt20] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.21 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt21' value='<?= $Cpo[lt21] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.22 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt22' value='<?= $Cpo[lt22] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Precio no.23 : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px" class='cinput'  name='Lt23' value='<?= $Cpo[lt23] ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualiza' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>                    </td>
                <td valign='top' width="22%">
                    <?php
                    SbmenuList();
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
