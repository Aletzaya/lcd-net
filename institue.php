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
$Msj = $_REQUEST["Msj"];

if ($_REQUEST[bt] === "Actualizar") {

    $sql = "UPDATE inst SET condiciones='$_REQUEST[Condiciones]', nombre = '$_REQUEST[Nombre]', alias = '$_REQUEST[Alias]', direccion = '$_REQUEST[Direccion]',  lista = '$_REQUEST[Lista]', localidad = '$_REQUEST[Localidad]'"
            . ",municipio = '$_REQUEST[Municipio]', telefono = '$_REQUEST[Telefono]', telcontacto = '$_REQUEST[Telcontacto]', codigo='$_REQUEST[Codigo]',"
            . "mail='$_REQUEST[Mail]',"
            . "status='$_REQUEST[Status]' WHERE institucion = $busca;";

    if (mysql_query($sql)) {

        $Msj = "¡Registro actualizado con exito!";

        $sql = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Instituciones/Actualiza los datos principales','inst','$Fecha','$busca')";
        mysql_query($sql);

        header("Location: institue.php?busca=$busca&Msj=$Msj");

    }else{

        $Msj = "Error en sintaxis SQL : " . $sql;
        header("Location: institue.php?busca=$busca&Msj=$Msj&Error=SI");

    }

} elseif ($_REQUEST[bt] === "NUEVO") {

    $sql = "INSERT INTO inst (nombre,alias,direccion,lista,localidad,municipio,telefono,telcontacto,codigo,mail,condiciones,status,usralta,fechalta) 
    VALUES ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Direccion]','$_REQUEST[Lista]','$_REQUEST[Localidad]','$_REQUEST[Municipio]','$_REQUEST[Telefono]','$_REQUEST[Telcontacto]','$_REQUEST[Codigo]','$_REQUEST[Mail]','$_REQUEST[Condiciones]','$_REQUEST[Status]','$Gusr','$Fecha') limit 1;";

    if (mysql_query($sql)) {

        $Msj = "¡Registro ingresado con exito!";
        $cId = mysql_insert_id();

        $sql = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Instituciones/Registro de Institucion','inst','$Fecha','$cId')";
        mysql_query($sql);

        header("Location: institue.php?busca=$cId&Msj=$Msj");

    } else {

        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();

        header("Location: institue.php?busca=NUEVO&Msj=$Msj&Error=SI");

    }

} elseif ($_REQUEST[bt] == "Actualiza contacto") {
    
    $sql = "UPDATE inst SET director = '$_REQUEST[Director]', subdirector = '$_REQUEST[Subdirector]', suplente = '$_REQUEST[Suplente]',"
            . "descuento = '$_REQUEST[Descuento]', promotorasig = '$_REQUEST[Promotorasig]', encargado = '$_REQUEST[Encargado]',"
            . "contacto = '$_REQUEST[Contacto]', mailcontacto = '$_REQUEST[Mail]', mailcontactoadm = '$_REQUEST[Mailadm]', password = '$_REQUEST[Password]', acceweb = '$_REQUEST[Acceweb]', telcontacto = '$_REQUEST[Telcontacto]' WHERE institucion = $busca;";
    
    if (mysql_query($sql)) {

        $Msj = "¡Registro Actualizado con exito!";

        $sql = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Instituciones/Actualizacion de contacto','inst', "
            . "'$Fecha','$busca')";
            
        mysql_query($sql);

        header("Location: institue.php?busca=$busca&Msj=$Msj");

    } else {

        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();

        header("Location: institue.php?busca=$busca&Msj=$Msj&Error=SI");

    }

}


#Variables comunes;
$CpoA = mysql_query("SELECT * FROM inst WHERE institucion = $busca");
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
        <title>Instituciones - General</title>
        <?php require ("./config_add.php"); ?>
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
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) ?> ::..
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
                                <td width='45%' align="right" class="Inpt">
                                    Institucion : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 60px"class='cinput'  name='Institucion' value='<?= $Cpo[institucion] ?>' MAXLENGTH='7' disabled> Id : <?= $Cpo[id] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Nombre : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 220px" class='cinput'  name='Nombre' value='<?= $Cpo[nombre] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Alias : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 120px" class='cinput'  name='Alias' value='<?= $Cpo[alias] ?>' MAXLENGTH='20'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Dirección : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 300px" class='cinput'  name='Direccion' value='<?= $Cpo[direccion] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Colonia : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Localidad' value='<?= $Cpo[localidad] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Municipio : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Municipio' value='<?= $Cpo[municipio] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Telefono : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 85px" class='cinput'  name='Telefono' value='<?= $Cpo[telefono] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Telefono de contacto : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 85px" class='cinput'  name='Telcontacto' value='<?= $Cpo[telcontacto] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Codigo postal : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 50px" class='cinput'  name='Codigo' value='<?= $Cpo[codigo] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Correo Electronico : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 170px" class='cinput'  name='Mail' value='<?= $Cpo[mail] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                            <td align="right" class="Inpt">
                                 Lista de Precio: : 
                            </td>

                            <td>
                                <select class="cinput" name="Lista">
                                     <option value="0">0</option>
                                     <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>

                                    <option value="<?= $Cpo[lista] ?>" selected><?= $Cpo[lista] ?>
                                    </option>
                                </select>
                            </td>
                            </tr>
                            <tr style="height: 30px">
                            <td align="right" class="Inpt">
                                 Conds. de pago: : 
                            </td>

                            <td>
                                <select class="cinput" name="Condiciones">
                                    <option value="CONTADO">CONTADO</option>
                                    <option value="CREDITO">CREDITO</option>
                                    <option value="<?= $Cpo[condiciones] ?>" selected><?= $Cpo[condiciones] ?>
                                    </option>
                                </select>
                            </td>
                            </tr>
                            <td align="right" class="Inpt">
                                Status : 
                            </td>
                            <td>
                                <select class="cinput" name="Status">
                                    <option value="INACTIVO">Inactivo</option>
                                    <option value="ACTIVO">Activo</option>
                                    <option value="<?= $Cpo[status] ?>" selected><?= $Cpo[status] ?></option>
                                </select>
                            </td>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    if ($busca == "NUEVO") {
                                        $inactivo="disabled";
                                    ?>
                                        <input class="letrap" type="submit" value='NUEVO' name='bt'></input>
                                        
                                    <?php
                                    } else {
                                        $inactivo="";
                                        
                                    ?>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                        <input type="submit" value='Actualizar' name='bt'></input>
                                        
                                    <?php
                                    }
                                    ?>          
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
                                    .:: Contactos ::.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Director : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput' style="width: 300px" name='Director' value='<?= $Cpo[director] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Subdirector : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 90px" class='cinput'  name='Subdirector' value='<?= $Cpo[subdirector] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Suplente : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 90px" class='cinput'  name='Suplente' value='<?= $Cpo[suplente] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Descuento Institucional : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 40px" class='cinput'  name='Descuento' value='<?= $Cpo[descuento] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Promotor Asignado : 
                                </td>
                                <td class="Inpt">
                                    <SELECT class='cinput' name='Promotorasig' <?= $inactivo ?>>
                                        <option value='Promotor_A'>Promotor_A</option>
                                        <option value='Promotor_B'>Promotor_B</option>
                                        <option value='Promotor_C'>Promotor_C</option>
                                        <option value='Promotor_D'>Promotor_D</option>
                                        <option value='Promotor_E'>Promotor_E</option>
                                        <option value='Promotor_F'>Promotor_F</option>
                                        <option value='Base'>Base</option>

                                        <option selected value='<?= $Cpo[promotorasig] ?>'><?= $Cpo[promotorasig] ?></option>
                                    </select>
                                </td>
                            </tr>                                
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Encargado Institucional : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Encargado' value='<?= $Cpo[encargado] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Contacto : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Contacto' value='<?= $Cpo[contacto] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Telefono Contacto : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Telcontacto' value='<?= $Cpo[telcontacto] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Mail Contacto : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Mail' value='<?= $Cpo[mailcontacto] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Mail Contacto Administrativo : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Mailadm' value='<?= $Cpo[mailcontactoadm] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Password Pagina Web: 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Password' value='<?= $Cpo[password] ?>' <?= $inactivo ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                            <td align="right" class="Inpt">
                                 Acceso Web: : 
                            </td>

                            <td>
                                <select class="cinput" name="Acceweb" <?= $inactivo ?>>
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                    <option value="<?= $Cpo[acceweb] ?>" selected><?= $Cpo[acceweb] ?>
                                    </option>
                                </select>
                            </td>
                            </tr>



                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualiza contacto' name='bt'  <?= $inactivo ?>></input>
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
                                    $sql = "SELECT * FROM logcat 
                                                WHERE accion like ('/Catalogos/Instituciones/%') 
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
                    if ($busca == "NUEVO") {
                    ?>
                        <br></br>
                        <table>
                            <tr>
                                <td>
                                    <a href="institu.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                </td>
                            </tr>
                        </table>
                    <?php
                    }else{
                        SbmenuInst();
                    }
                    ?>
                </td>
            </tr>      
        </table>  
    </body>
</html>
<?php
mysql_close();
?>
