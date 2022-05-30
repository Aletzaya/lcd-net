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
$Titulo = "Ordenes de estudio";

$Msj = $_REQUEST[Msj];
$CpoA = mysql_query("SELECT * FROM med WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Medicos - Info. Personal</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu);
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Movimientos al Medico <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="3">
                                    .:: Actividad ::.
                                </td>
                            </tr>
                            <tr style="height: 30px" class="cinput">
                                <td align="right" class="Inpt" colspan="3">
                                    Usr : 
                                    <select class='cinput' name='Usr'>
                                        <?php
                                        $sql = "SELECT uname FROM authuser;";
                                        $cSql = mysql_query($sql);
                                        while ($sql = mysql_fetch_array($cSql)) {
                                            echo "<option class='cinput' value='$sql[uname]'>$sql[uname]</option>";
                                        }
                                        if ($_REQUEST[Usr] <> '') {
                                            echo "<option class='cinput' selected value='$_REQUEST[Usr]'>$_REQUEST[Usr]</option>";
                                        } else {
                                            echo "<option class='cinput' selected value='*'>* Todos</option>";
                                        }
                                        ?>
                                    </select>
                                    Fecha Ini:
                                    <input type='date' class='cinput'  name='fechai'></input>
                                    Fecha Fin:
                                    <input type='date' class='cinput'  name='fechaf'></input>   
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="3">
                                    <input class="letrap" type="submit" value='Buscar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </form>
                        <tr align="center" class="Inpt">
                            <td>
                                <b>Usr</b>
                            </td>
                            <td>
                                <b>Fecha</b>
                            </td>
                            <td>
                                <b>Accion</b>
                            </td>
                        </tr>
                        <?php
                        $usr = strtoupper($_REQUEST[Usr]);
                        if ($_REQUEST[Usr] = '*') {
                            $sql = "SELECT * FROM log WHERE fecha >= '$_REQUEST[fechai] 00:00:00' AND fecha <= '$_REQUEST[fechaf] 23:59:59' AND accion like ('%/Medicos/%') ORDER BY id DESC  LIMIT 30;";
                        } else {
                            $sql = "SELECT * FROM log WHERE usr='$usr' AND fecha >= '$_REQUEST[fechai] 00:00:00' AND fecha <= '$_REQUEST[fechaf] 23:59:59' AND accion like ('%/Medicos/%') ORDER BY id DESC  LIMIT 30;";
                        }
                        $cSql = mysql_query($sql);
                        while ($sql = mysql_fetch_array($cSql)) {
                            ?>
                            <tr class="Inpt">
                                <td align="center">
                                    <?= $sql[usr] ?>
                                </td>
                                <td align="center">
                                    <?= $sql[fecha] ?>
                                </td>
                                <td>
                                    <?= $sql[accion] ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>  
                </td>
                <td valign='top' width='45%'>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>

                        </table>
                    </form>                    </td>
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
    <script type="application/javascript">
        (function() {
        function ocultar(one, two, three, four, five) {
        $.each([one, two, three, four, five], function(index, elm) {
        if($(elm).css('display')) {
        $(elm).slideUp('fast');
        }
        });
        }
        jQuery(function($) {

        //-----Recepcion Menu
        $('#recepcion').mouseover(function () {
        // Show hidden content IF it is not already showing
        if($('#two-level-recepcion').css('display') == 'none') {
        $('#two-level-recepcion').slideDown('fast');
        ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
        }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-recepcion').mouseleave(function () {
        if($('#two-level-recepcion').css('display')) {
        $('#two-level-recepcion').slideUp('fast');
        }
        });
        //-----Facturacion Menu
        $('#facturacion').mouseover(function () {
        // Show hidden content IF it is not already showing
        if($('#two-level-facturacion').css('display') == 'none') {
        $('#two-level-facturacion').slideDown('fast');
        ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-recepcion', '#two-level-moviles');
        }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-facturacion').mouseleave(function () {
        if($('#two-level-facturacion').css('display')) {
        $('#two-level-facturacion').slideUp('fast');
        }
        });		

        //--------Catalogos Menu
        $('#catalogos').mouseover(function () {
        // Show hidden content IF it is not already showing
        if($('#two-level-catalogos').css('display') == 'none') {
        $('#two-level-catalogos').slideDown('fast');
        ocultar('#two-level-recepcion', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
        }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-catalogos').mouseleave(function () {
        if($('#two-level-catalogos').css('display')) {
        $('#two-level-catalogos').slideUp('fast');
        }
        });

        //-----Ingresos Menu
        $('#ingresos').mouseover(function () {
        // Show hidden content IF it is not already showing
        if($('#two-level-ingresos').css('display') == 'none') {
        $('#two-level-ingresos').slideDown('fast');
        ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
        }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-ingresos').mouseleave(function () {
        if($('#two-level-ingresos').css('display')) {
        $('#two-level-ingresos').slideUp('fast');
        }

        });

        //------Reportes Menu
        $('#reportes').mouseover(function () {
        // Show hidden content IF it is not already showing
        if($('#two-level-reportes').css('display') == 'none') {
        $('#two-level-reportes').slideDown('fast');
        ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-moviles', '#two-level-facturacion','#two-level-ingresos', '#two-level-procesos');
        }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-reportes').mouseleave(function () {
        if($('#two-level-reportes').css('display')) {
        $('#two-level-reportes').slideUp('fast');
        }
        });
        //-----Moviles Menu
        $('#moviles').mouseover(function () {
        // Show hidden content IF it is not already showing
        if($('#two-level-moviles').css('display') == 'none') {
        $('#two-level-moviles').slideDown('fast');
        ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-ingresos');
        }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-moviles').mouseleave(function () {
        if($('#two-level-moviles').css('display')) {
        $('#two-level-moviles').slideUp('fast');
        }

        });


        });
        })();
    </script>
</html>
<?php
mysql_close();
?>

