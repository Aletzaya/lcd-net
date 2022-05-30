<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
$archivo = $_REQUEST[archivo];
$Usr = $check['uname'];

require ("config.php");          //Parametros de colores;

if ($archivo <> '') {

    $id = $_REQUEST[id];
    unlink("estudios/$archivo");
    $Usrelim = $_COOKIE['USERNAME'];
    $Fechaelim = date("Y-m-d H:i:s");
    $lUp = mysql_query("UPDATE estudiospdf set usrelim='$Usrelim',fechaelim='$Fechaelim' where archivo='$archivo' and id='$id'");

    $Msj='Eliminacion de Imagenes con Exito';

    AgregaBitacoraEventos2($Usrelim, '/Imagenes/Eliminacion de Imagenes', "estudiospdf", $Fechaelim, $busca, $Msj, "listadoimagenes.php");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
        <title>Listado de Imagenes</title>
        <?php require ("./config_add.php"); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
    <body>
    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
        <tr>
            <td colspan="2" align="center">
                <table width="98%" style="margin-top: 10px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                    <tr class="letrap" align="center">
                        <td><b>Archivo</b></td>
                        <td><b>Desc</b></td>
                        <td><b>Elim</b></td>
                    </tr>
                    <?php
                    $ImgA = mysql_query("SELECT archivo,idnvo,usr,fechasub FROM estudiospdf WHERE id='$busca' and usrelim=''");
                    while ($row = mysql_fetch_array($ImgA)) {
                        
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;

                        $Pos = strrpos($row[archivo], ".");
                        $cExt = strtoupper(substr($row[archivo], $Pos + 1, 4));
                        $foto = $row[archivo];

                        echo "<tr class='letrap' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                        echo "<td align='left'>" . ucfirst(strtolower($row[archivo])) . "</td>";
                        echo "<td align='center'> - </td>";
                        echo "<td align='center'><a class='edit' href='listadoimagenes.php?archivo=$foto&id=$busca&busca=$busca' onclick='return confirm(\"Desea eliminar el archivo?\")'><font color='#FFFFCC'><i class='fa fa-trash fa-lg' aria-hidden='true' style='color: red;'> </i></font></a>";

                        echo "</tr>";

                        $nRng++;
                    }
                    ?>
                    </tr>
                </table>
                <br/>
            </td>
        </tr>
    </table>
    <br>
    </body>
</html>
<?php
mysql_close();
?>
