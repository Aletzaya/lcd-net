<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
setcookie("NOOT", $busca);

$Usr = $check['uname'];
$cId = $_REQUEST[busca];

$estudio = $_REQUEST[estudio];
$archivo = $_REQUEST[archivo];
$archivo=base64_decode($archivo);

$lBd = false;

#Variables comunes;
$Titulo = " Detalle de documentos - Proveedor";

require ("config.php");          //Parametros de colores;

$HeA = mysql_query("SELECT *
  FROM prv
  WHERE prv.id=$busca");

$He = mysql_fetch_array($HeA);
?>

<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<meta charset="UTF-8">
    <head>
        <title></title>

        <link rel="stylesheet" type="text/css" href="css/dropzone.css">

        <script type="text/javascript" src="js/dropzone.js"></script>
    </head>
    <body>

        <table width='100%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
            <tr style='background-color: #ABB2B9 ;border-collapse: collapse; border: 1px solid #bbb;height: 7px;'>
                <td align='left' class='titulo' colspan='3'>
                    <font color='#FFFFCC'><b> &nbsp; Documentos capturados </b>
                </td>
            </tr>
            <?php
            $ImgA = mysql_query("SELECT archivo,idnvo,usr,fechasub FROM doctosprv WHERE id='$busca' and usrelim='' and archivo='$archivo'");
            while ($row = mysql_fetch_array($ImgA)) {
                $Pos = strrpos($row[archivo], ".");
                $cExt = strtoupper(substr($row[archivo], $Pos + 1, 4));
                $foto = $row[archivo];
                if ($cExt == 'PDF') {
                    echo "<tr><td align='center'><embed src='http://lcd-system.com/lcd-net/doctosprv/$foto' type='application/pdf' width='100%' height='1300px' /></td></tr>";
                } elseif ($cExt == 'DOCX') {

                    echo "<tr><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=http://lcd-system.com/lcd-net/doctosprv/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>";
                } elseif ($cExt == 'XLSX') {

                    echo "<tr><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=http://lcd-system.com/lcd-net/doctosprv/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>";
                } elseif ($cExt == 'DCM') {

                    echo "<a href='http://lcd-system.com/lcd-net/doctosprv/$foto' style='width:100%; height:50%; border: none;min-height:500px;')><img src='lib/Pdf.gif' title=$Pdf border='0'></a> &nbsp; &nbsp; ";
                } else {

                    echo "<tr><td align='center'><IMG SRC='doctosprv/$foto' border='0' width='1200'></td></tr>";
                }

            }
            ?>
        </table>
        <br>
    </body>
</html>
<?php
mysql_close();
?>
