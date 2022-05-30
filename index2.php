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

$Cia = $_REQUEST[Cia];

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

    echo "<br><br><br>";

    if($Cia==''){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='0'){

        $formato0="style='font-weight: bold;' color=DarkRed";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='1'){

        $formato0="";
        $formato1="style='font-weight: bold;' color=DarkRed";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='2'){

        $formato0="";
        $formato1="";
        $formato2="style='font-weight: bold;' color=DarkRed";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='3'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="style='font-weight: bold;' color=DarkRed";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='4'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="style='font-weight: bold;' color=DarkRed";
        $formato5="";
        $formato6="";

    }elseif($Cia=='5'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="style='font-weight: bold;' color=DarkRed";
        $formato6="";

    }elseif($Cia=='6'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="style='font-weight: bold;' color=DarkRed";

    }


    ?>

    <table width='290' border='0' align='center' cellpadding='0' cellspacing='1' style='background-image:url(images/login2.png);border:#000 0px solid;border-color: #999; border-radius: .5em;'> 
        <tr>
            <td width='290' height='335' align='center'>
                <table width='85%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap'>
                        <tr style="height: 75px">
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td width='25%' height='50' rowspan='8'>Sucursal:</td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=0'><font size='2' <?=$formato0?>>(0) Administracion</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=1'><font size='2' <?=$formato1?>>(1) LCD Matriz</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=2'><font size='2' <?=$formato2?>>(2) LCD OHF</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=3'><font size='2' <?=$formato3?>>(3) LCD Tepexpan</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=4'><font size='2' <?=$formato4?>>(4) LCD Los Reyes</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=5'><font size='2' <?=$formato5?>>(5) LCD Camarones</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='75%' height='25' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='center'; onMouseOut=this.style.backgroundColor='#E1E1E1';this.style.textAlign='left';>
                                <a class='edit' href='index2.php?Cia=6'><font size='2' <?=$formato6?>>(6) LCD Sn Vicente Ch.</font></a>
                            </td>
                        </tr>
                    <?php
                    if($Cia==''){
                    ?>                       
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                        <tr height='30'><td colspan='2' align="center"><b>Seleccione primero una sucursal... </b></td></tr>
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                    <?php
                    }else{
                    ?>
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                        <form name='Sample' method='post' action='<?= $resultpage ?>'>
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
                            <tr height='30'>
                                <td> </td>
                                <td align='right'>
                                    <input type="submit" class="letrap" name="Boton" value="Enviar"> &nbsp; &nbsp; &nbsp;
                                </td>
                            </tr>
                            <input type="hidden" name="Cia" value="<?=$Cia?>"></input>
                        </form>
                    <?php
                    }
                    ?>
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