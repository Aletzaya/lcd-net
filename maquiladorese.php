<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
date_default_timezone_set("America/Mexico_City");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");

#Variables comunes;
$msj = $_REQUEST["msj"];
$Titulo = "Ordenes de estudio";
$Msj = $_REQUEST["Msj"];
$fecha = date("Y-m-d H:m:s");

if ($_REQUEST["bt"] === "Actualizar") {
    $Sql = "UPDATE mql SET nombre='$_REQUEST[Nombre]', alias='$_REQUEST[Alias]', direccion='$_REQUEST[Direccion]', "
            . "colonia='$_REQUEST[Colonia]',ciudad='$_REQUEST[Ciudad]',codigo='$_REQUEST[Codigo]',rfc='$_REQUEST[Rfc]',"
            . "telefono='$_REQUEST[Telefono]',observaciones='$_REQUEST[Observaciones]' WHERE id = $busca";
    if (mysql_query($Sql)) {
        $log = "INSERT INTO log  (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Admin/Maquiladores/Detalle','mql','$fecha',$busca)";
        if (mysql_query($log)) {
            $msj = "Registro actualizado con Exito!";
            header("Location: maquiladorese.php?busca=$busca&msj=$msj");
        }
    }
} else if ($_REQUEST["bt"] === "Agregar") {
    $Sql = "INSERT INTO mql (nombre,alias,direccion,colonia,ciudad,codigo,rfc,telefono,observaciones) "
            . "VALUES ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Direccion]', '$_REQUEST[Colonia]',"
            . "'$_REQUEST[Ciudad]','$_REQUEST[Codigo]','$_REQUEST[Rfc]','$_REQUEST[Telefono]','$_REQUEST[Observaciones]') ";
    if (mysql_query($Sql)) {
        $Id = mysql_insert_id();
        $log = "INSERT INTO log  (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Admin/Maquiladores/Detalle Creacion','mql','$fecha',$Id)";
        if (mysql_query($log)) {
            $msj = "Registro agregado con Exito!";
            header("Location: maquiladorese.php?busca=$Id&msj=$msj");
        } else {
            echo "Error SQL " . $log;
        }
    } else {
        echo "Error SQL " . $Sql;
    }
}
$Obsr = $_REQUEST["msj"];
$CpoA = mysql_query("SELECT * FROM mql WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Det. Maquiladores</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            $(document).ready(function () {
                var observaciones = "<?= $Obsr ?>";
                if (observaciones !== "") {
                    Swal.fire({
                        title: observaciones,
                        position: "top-right",
                        icon: "success",
                        timer: 1500,
                        toast: true,
                        showConfirmButton: false
                    })
                }
            });
        </script>
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Maquilador <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle de Maquiladores ::..
                                </td>
                            </tr>
                            <tr class="letrap"><td align="right"><strong>Cuenta : </strong></td><td><?= $busca ?></td></tr>
                            <tr class="letrap"><td align="right"><strong> Nombre : </strong></td><td><input type="text" value="<?= $Cpo["nombre"] ?>" name="Nombre" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Alias : </strong></td><td><input type="text" value="<?= $Cpo["alias"] ?>" name="Alias" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Dirección : </strong></td><td><input type="text" value="<?= $Cpo["direccion"] ?>" name="Direccion" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Colonia : </strong></td><td><input type="text" value="<?= $Cpo["colonia"] ?>" name="Colonia" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Cod. postal : </strong></td><td><input type="number" name="Codigo" value="<?= $Cpo["codigo"] ?>" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Ciudad : </strong></td><td><input type="text" value="<?= $Cpo["ciudad"] ?>" name="Ciudad" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>R.f.c : </strong></td><td><input type="text" value="<?= $Cpo["rfc"] ?>" name="Rfc" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Telefono : </strong></td><td><input type="number" value="<?= $Cpo["telefono"] ?>" name="Telefono" class="letrap"></input></td></tr>
                            <tr class="letrap"><td align="right"><strong>Contacto / Observaciones : </strong></td><td><input type="text" value="<?= $Cpo["observaciones"] ?>" name="Observaciones" class="letap"></input></td></tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php $_REQUEST["busca"] === "NUEVO" ? $dato = "Agregar" : $dato = "Actualizar"; ?>
                                    <input class="letrap" type="submit" value='<?= $dato ?>' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </form>
                    </table>  
                </td>
                <td valign='top' width='45%'>
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" >
                                .:: Modificaciones ::.
                            </td>
                        </tr>
                        <tr>
                            <td>
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
                                                WHERE accion like ('/Admin/Maquiladores/Detalle%') 
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
                                <table width="100%">
                                    <tr>
                                        <td align="right">
                                            <a href="maquiladores.php?busca=ini" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>      
        </table>
    </body>
</html>
<?php
mysql_close();
?>

