<?php
session_start();

date_default_timezone_set("America/Mexico_City");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

$deptos = $_REQUEST[deptos];

require ("config.php");       //Parametros de colores;

echo "<html>";

echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>$Titulo</title>";
?>

<link href="estilos.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
<link rel='icon' href='favicon.ico' type='image/x-icon' />
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>


<?php
echo "</head>";

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";
?>
<table width='99%' align='center' border='0' cellpadding='1' 
cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; 
border: 1px solid #999;'>
        <tr style="background-color: #2c8e3c"><td 
class='letratitulo'align="center">.:: Modificaciones ::.</td></tr>
        <tr>
            <td>
                <table align="center" width="95%" style="border:#000 1px 
solid;border-color: #999; border-radius: .5em;" border="0">
                    <tr class="letrap">
                        <td><b>&nbsp; Fecha</b></td>
                        <td><b>&nbsp; Usuario</b></td>
                        <td><b>&nbsp; Accion</b></td>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM logcat WHERE accion like 
('%/Cat Departamento%') AND cliente='$deptos' ORDER BY id DESC LIMIT 10;";
                    $PgsA = mysql_query($sql);
                    while ($rg = mysql_fetch_array($PgsA)) {
                        (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo ='DDE8FF';
                        ?>
			<tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                            <td align="center">&nbsp;<?= $rg[fecha] 
?></td>
                            <td><?= $rg[usr] ?></td>
                            <td><?= $rg[accion] ?></td>
                        </tr>
                        <?php
                        $nRng++;
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <?php 
echo "</body>";

echo "</html>";

mysql_close();
?>
