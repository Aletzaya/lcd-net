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
$Fecha = date("Y-m-d H:m:i");
$msj = $_REQUEST[msj];

//$Return        = "clientesf.php";

$Tabla = "clif";

$Titulo = "Detalle por cliente de Facturacion";

if ($_REQUEST[bt] == "NUEVO") {

    $sql = "INSERT INTO clif (nombre,direccion,colonia,municipio,telefono,alias,contacto,
               codigo,rfc,correo,numeroint,numeroext,enviarcorreo,cuentaban,estado,correoadm,regimenfiscal)
               VALUES
               ('$_REQUEST[Nombre]','$_REQUEST[Direccion]','$_REQUEST[Colonia]','$_REQUEST[Municipio]',
               '$_REQUEST[Telefono]','$_REQUEST[Alias]','$_REQUEST[Contacto]',
               '$_REQUEST[Codigo]','$_REQUEST[Rfc]','$_REQUEST[Correo]','$_REQUEST[Numeroint]',
               '$_REQUEST[Numeroext]','$REQUEST[Enviarcorreo]','$_REQUEST[Cuentaban]',
               '$_REQUEST[Estado]','$_REQUEST[Correoadm]','$_REQUEST[RegimenF]');";

    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis SQL : " . $sql;
    } else {
        $msj = "Exito";
    }

    $Paciente = mysql_insert_id();

    if ($_REQUEST[org] == "clifnvo" AND $msj == "Exito") {

        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Clientesf/Info. Personal/Alta Cliente Facturacion','clif', "
                . "'$Fecha',$busca)";

        header("Location: facturas.php?busca=$Paciente&Cliente=$Paciente");
    }
} elseif ($_REQUEST[bt] == "Actualizar") {

    $sql = "UPDATE clif SET nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',colonia='$_REQUEST[Colonia]',municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',alias='$_REQUEST[Alias]',contacto='$_REQUEST[Contacto]',rfc='$_REQUEST[Rfc]',codigo='$_REQUEST[Codigo]',correo='$_REQUEST[Correo]',numeroint='$_REQUEST[Numeroint]',numeroext='$_REQUEST[Numeroext]',enviarcorreo='$_REQUEST[Enviarcorreo]',cuentaban='$_REQUEST[Cuentaban]',"
            . "estado='$_REQUEST[Estado]',correoadm='$_REQUEST[Correoadm]',"
            . "regimenFiscal='$_REQUEST[RegimenF]' WHERE id='$busca' limit 1";

    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis SQL : " . $sql;
    }

    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Clientesf/Info. Personal/Edita Detalle del Cliente fac','clif', "
            . "'$Fecha',$busca)";

    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }

    $msj = "Cambio ejecutado con exito";

    header("Location: clientesf.php?busca=$busca&msj=$msj");
}


#Variables comunes;
$CpoA = mysql_query("SELECT * FROM clif WHERE id = $busca");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;

if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
    $Cliente = $_REQUEST[Nombre];
    $Alias = $_REQUEST[Alias];
    $Rfc = $_REQUEST[Rfc];
    $busca = "NUEVO";
} else {
    $Estado = $Cpo[estado];
    $Cliente = $Cpo[nombre];
    $Alias = $Cpo[alias];
    $Rfc = $Cpo[rfc];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Clientes Facturacion - Info. Personal</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                </head>
                <body topmargin="1">
                    <?php
                    encabezados();
                    menu($Gmenu, $Gusr);
                    ?>
                    <script>
                        $(document).ready(function (){
                            $("#RegimenF").val("<?=$Cpo["regimenFiscal"]?>");
                        });
                    </script>
                    <script src="./controladores.js"></script>
                    <?php
                    ?>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                            <tr>
                                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) ?> ::..
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' align='center' height='440' width='55%'>
                                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                        <tr style="background-color: #2c8e3c">
                                            <td class='letratitulo'align="center" colspan="2">
                                                ..:: Detalle de cliente Facturacion ::..
                                            </td>
                                        </tr>
                                        <tr style="height: 30px">
                                            <td width='45%' align="right" class="Inpt" style="width: 300px">
                                                Cliente : 
                                            </td>
                                            <td class="Inpt">
                                                <input type='text' class='cinput' style="width: 300px" name='Nombre' value='<?= $Cliente ?>' MAXLENGTH='120' required> Id : <?= $Cpo[id] ?>
                                            </td>
                                        </tr>
                                        <tr style="height: 30px">
                                            <td align="right" class="Inpt">
                                                Alias : 
                                            </td>
                                            <td class="Inpt">
                                                <input type='text' class='cinput'  name='Alias' value='<?= $Alias ?>' MAXLENGTH='30'></input>
                                            </td>
                                        </tr>
                                        <tr style="height: 30px">
                                            <td align="right" class="Inpt">
                                                RFC : 
                                            </td>
                                            <td class="Inpt">
                                                <input type='text' class='cinput'  name='Rfc' value='<?= $Rfc ?>' MAXLENGTH='30' required></input>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="Inpt">Regimen Fiscal:</td>
                                            <td>
                                                <select name="RegimenF" id="RegimenF" class="cinput">
                                                    <?php
                                                    $rsRf = mysql_query("SELECT * FROM cfdi33_c_regimenes;");
                                                    while ($rsF = mysql_fetch_array($rsRf)) {
                                                        ?>
                                                        <option value="<?= $rsF["clave"] ?>"><?= $rsF["clave"] ?> .- <?= $rsF["descripcion"] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr style="height: 30px">
                                            <td align="right" class="Inpt">
                                                Estado : 
                                            </td>
                                            <td class="Inpt">
                                                <SELECT class='cinput' name='Estado'>
                                                    <option value='Aguascalientes'>Aguascalientes</option>
                                                    <option value='Baja California'>Baja California</option>
                                                    <option value='Baja California Sur'>Baja California Sur</option>
                                                    <option value='Campeche'>Campeche</option>
                                                    <option value='Chiapas'>Chiapas</option>
                                                    <option value='Chihuahua'>Chihuahua</option>
                                                    <option value='Coahuila'>Coahuila</option>
                                                    <option value='Colima'>Colima</option>
                                                    <option value='Distrito Federal'>Distrito Federal</option>
                                                    <option value='Durango'>Durango</option>
                                                    <option value='Estado de Mexico'>Estado de Mexico</option>
                                                    <option value='Guanajuato'>Guanajuato</option>
                                                    <option value='Guerrero'>Guerrero</option>
                                                    <option value='Hidalgo'>Hidalgo</option>
                                                    <option value='Jalisco'>Jalisco</option>
                                                    <option value='Michoacan'>Michoacan</option>
                                                    <option value='Morelos'>Morelos</option>
                                                    <option value='Nayarit'>Nayarit</option>
                                                    <option value='Nuevo Leon'>Nuevo Leon</option>
                                                    <option value='Oaxaca'>Oaxaca</option>
                                                    <option value='Puebla'>Puebla</option>
                                                    <option value='Queretaro'>Queretaro</option>
                                                    <option value='Quintana Roo'>Quintana Roo</option>
                                                    <option value='San Luis Potosi'>San Luis Potosi</option>
                                                    <option value='Sinaloa'>Sinaloa</option>
                                                    <option value='Sonora'>Sonora</option>
                                                    <option value='Tabasco'>Tabasco</option>
                                                    <option value='Tamaulipas'>Tamaulipas</option>
                                                    <option value='Tlaxcala'>Tlaxcala</option>
                                                    <option value='Veracruz'>Veracruz</option>
                                                    <option value='Yucatán'>Yucatán</option>
                                                    <option value='Zacatecas'>Zacatecas</option>
                                                    <option selected value='<?= $Estado ?>'><?= $Estado ?></option>
                                                    &nbsp; <input class='Inpt' type='submit' name='est' value='Enviar Estado'></input>
                                                </select>
                                            </td>
                                            <?php
                                            if ($_REQUEST[Municipio] <> '') {
                                                $Municipio = $_REQUEST[Municipio];
                                            } else {
                                                $Municipio = $Cpo[municipio];
                                            }

                                            $MpioA = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
                                            ?>
                                            <tr>
                                                <td align="right" class="Inpt">
                                                    Municipio : 
                                                </td>
                                                <td class="Inpt">
                                                    <SELECT class='cinput' name='Municipio'>
                                                        <?php
                                                        while ($Mpio = mysql_fetch_array($MpioA)) {
                                                            echo "<option class='cinput' value='$Mpio[municipio]'>$Mpio[municipio]</option>";
                                                        }
                                                        echo "<option class='cinput' selected value='$Municipio'>$Municipio</option>";
                                                        ?>
                                                        &nbsp; <input class='Inpt' type='submit' name='Mun' value='Enviar Municipio'></input>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Colonia : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' style="width: 110px" class='cinput'  name='Colonia' value='<?= $Cpo[colonia] ?>'>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Direccion : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput' style="width: 300px" name='Direccion' value='<?= $Cpo[direccion] ?>'>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Numero Exterior : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput'  name='Numeroext' value='<?= $Cpo[numeroext] ?>' MAXLENGTH='10'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Numero Interior : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput'  name='Numeroint' value='<?= $Cpo[numeroint] ?>' MAXLENGTH='10'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Telefono : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput'  name='Telefono' value='<?= $Cpo[telefono] ?>' MAXLENGTH='20'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Codigo Postal : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput'  name='Codigo' value='<?= $Cpo[codigo] ?>' MAXLENGTH='7'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Envio por correo : 
                                                </td>
                                                <td class="Inpt">
                                                    <select name='Enviarcorreo'>
                                                        <option value='Si'>Si</option>
                                                        <option value='No'>No</option>
                                                        <option selected value='<?= $Cpo[enviarcorreo] ?>'><?= $Cpo[enviarcorreo] ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Correo Electronico : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput' style="width: 300px" name='Correo' value='<?= $Cpo[correo] ?>' MAXLENGTH='60'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    4 Ultimos Digitos de la Cta : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput'  name='Cuentaban' value='<?= $Cpo[cuentaban] ?>' MAXLENGTH='4'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Contacto : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput'  name='Contacto' value='<?= $Cpo[contacto] ?>' MAXLENGTH='40'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td align="right" class="Inpt">
                                                    Correo Electronico Admvo: 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' class='cinput' style='width: 300px' name='Correoadm' value='<?= $Cpo[correoadm] ?>' MAXLENGTH='60'></input>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="35px" align="center" colspan="2">
                                                    <?php
                                                    if ($busca == "NUEVO") {
                                                        ?>
                                                        <input class="letrap" type="submit" value='NUEVO' name='bt'></input>
                                                        <input type="hidden" value="<?= $_REQUEST[busca] ?>" name="busca"></input>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                    </table>
                                    </form>
                                    <td valign='top'  width='45%'>
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
                                                        $sql = "SELECT * FROM log 
                                                WHERE accion like ('%/Catalogos/Clientesf/Info. Personal%') AND cliente=$busca
                                                ORDER BY id DESC LIMIT 6;";
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
                                        <br>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <a href="clientesf.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                                    </td>
                                                </tr>
                                            </table>
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
