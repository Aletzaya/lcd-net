<?php
#Librerias

session_start();

//$_SESSION["Usr"] = array("", "", "", "", "", "", "Admon");
$_SESSION["Usr"] = array("", "", "", "", "", "", "u938386532_root");

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");
//$Gusr     = "Admon";
//$_SESSION['usr']='Admon';

require("lib/lib.php");

$link = conectarse();

include_once ("authconfig.php");

require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Area de Bienvenida LCD-NET</title>
        <link href="estilos.css" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    </head>

    <?php
    echo "<body>";

    echo "<br><br><br><br><br><br><br><br><br>";
    ?>

    <table width='290' border='0' align='center' cellpadding='0' cellspacing='1' style='background-image:url(images/login.png);border:#000 0px solid;border-color: #999; border-radius: .5em;'> 
        <tr>
            <td width='290' height='335' align='center'>
                <table width='80%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap'>
                    <form name='Sample' method='post' action='<?= $resultpage ?>'>
                        <tr style="height: 95px">
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td width='30%' height='30'>Sucursal:</td>
                            <td>
                                <div><input class='letrap' type='radio' name='Cia' value='0'>Administracion</div>
                                <div><input class='letrap' type='radio' name='Cia' value='1'>LCD Matriz(1)</div>
                                <div><input class='letrap' type='radio' name='Cia' value='2'>LCD OHF(2)</div>
                                <div><input class='letrap' type='radio' name='Cia' value='3'>LCD Tepexpan(3)</div>
                                <div><input class='letrap' type='radio' name='Cia' value='4'>LCD Los Reyes(4)</div>
                                <div><input class='letrap' type='radio' name='Cia' value='5'>LCD Camarones (5)</div>
                                <div><input class='letrap' type='radio' name='Cia' value='6'>LCD Sn Vicente Ch. (6)</div>
                            </td>
                        </tr>

                        <tr height='40'><td> </td><td> </td></tr>
                        <tr height='30'>
                            <td>Usuario:</td>
                            <td>
                                <input type="text" class="letrap" size="22" name="username" /></label>
                            </td>
                        </tr>
                        <tr height='30'>
                            <td>Password:</td>
                            <td>
                                <input type="password" class="letrap" size="22" name="password" />
                            </td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td align='right'>
                                <input type="submit" class="letrap" name="Boton" value="Enviar"> &nbsp; &nbsp; &nbsp;
                            </td>
                        </tr>
                    </form>
                </table>
            </td>
        </tr>
    </table>
    <table align='center'>
        <tr>
            <td class='letrap' align='center'>
                Para el correcto uso del sistema es necesario utilizar el navegador 
                <b>Google Crome </b>
                <i class='fa fa-google fa-lg' aria-hidden='true' style='color:#D63E2F'></i>
            </td>
        </tr>
    </table>

    <?php
    if ($_REQUEST[op] == 9) {
        echo "<a class='msj'>Sesión cerrada con exito</a>";
    } elseif ($_REQUEST[op] == 99) {
        echo "Datos de usuario no coinciden";
    } elseif ($_REQUEST[op] == 8) {
        echo "Sesión caduca";
    } elseif ($_REQUEST[op] == 7) {
        echo "Usuario asignado para otra Cia";
    }

    echo"</body>";
    ?>
    <script>
        jQuery(function ($) {
            //Recepcion Menu
            $('#recepcion').hover(
                    function () {
                        // Show hidden content IF it is not already showing
                        if ($('#two-level-recepcion').css('display') == 'none') {
                            $('#two-level-recepcion').slideDown(200);
                        }
                    },
                    function () {
                        // Do nothing when mouse leaves the link
                        $.noop(); // Do Nothing
                    }
            );
            // Close menu when mouse leaves Hidden Content
            $('#two-level-recepcion').mouseleave(function () {
                $('#two-level-recepcion').slideUp(50);
            });
            //Catalogos Menu
            $('#catalogos').hover(
                    function () {
                        // Show hidden content IF it is not already showing
                        if ($('#two-level-catalogos').css('display') == 'none') {
                            $('#two-level-catalogos').slideDown(200);
                        }
                    },
                    function () {
                        // Do nothing when mouse leaves the link
                        $.noop(); // Do Nothing
                    }
            );
            // Close menu when mouse leaves Hidden Content
            $('#two-level-catalogos').mouseleave(function () {
                $('#two-level-catalogos').slideUp(50);
            });
            //Procesos Menu
            $('#procesos').hover(
                    function () {
                        // Show hidden content IF it is not already showing
                        if ($('#two-level-procesos').css('display') == 'none') {
                            $('#two-level-procesos').slideDown(200);
                        }
                    },
                    function () {
                        // Do nothing when mouse leaves the link
                        $.noop(); // Do Nothing
                    }
            );
            // Close menu when mouse leaves Hidden Content
            $('#two-level-procesos').mouseleave(function () {
                $('#two-level-procesos').slideUp(50);
            });
            //Reportes Menu
            $('#reportes').hover(
                    function () {
                        // Show hidden content IF it is not already showing
                        if ($('#two-level-reportes').css('display') == 'none') {
                            $('#two-level-reportes').slideDown(200);
                        }
                    },
                    function () {
                        // Do nothing when mouse leaves the link
                        $.noop(); // Do Nothing
                    }
            );

            // Close menu when mouse leaves Hidden Content
            $('#two-level-reportes').mouseleave(function () {
                $('#two-level-reportes').slideUp(50);
            });


        });

    </script>
</html>