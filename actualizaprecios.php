<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];
$op = $_REQUEST["op"];
if ($op == 'ActPre') {

    $cSql = "UPDATE otd,ot,est SET otd.precio = $_REQUEST[Lista] WHERE ot.fecha>='$_REQUEST[FechaI]'AND ot.institucion='$_REQUEST[Institucion]'
          AND ot.orden=otd.orden AND otd.estudio=est.estudio";
    $lUp = mysql_query($cSql);
    
    $Sql = "SELECT orden FROM ot WHERE ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'";
    
    $SqlA = mysql_query($Sql);
    
    while ($Cpo = mysql_fetch_array($SqlA)) {
        $busca = $Cpo["orden"];
        $Sql = "SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'";
        echo $Sql;
        $ImpA = mysql_query($Sql);

        $Otd = mysql_fetch_array($ImpA);
        $Sql = "UPDATE ot SET importe='$Otd[0]' WHERE orden='$busca'";

        $lUp = mysql_query($Sql);
    }
    $Msj = "Actualizacion ejecutada con exito!";
    header("Location: menu.php?Msj=$Msj");
}
$fecha = new DateTime();
$fecha->modify('first day of this month');
$Fecha = $fecha->format("Y-m-d");

#Variables comunes;
$CpoA = mysql_query("SELECT * FROM cli WHERE cliente = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Actualiza Precios ::..</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            $(document).ready(function () {
                var observaciones = "<?= $Msj ?>";
                if (observaciones != "") {
                    Swal.fire({
                        title: observaciones,
                        position: "top-right",
                        icon: "info",
                        toast: true,
                        timer: 3500,
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
        <br></br>
        <table width='45%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
            <tr style="background-color: #2c8e3c">
                <td class='letratitulo' align="center" colspan="2">
                    <h3>Actualiza precio en ordenes de estudio</h3>
                </td>
            </tr>
            <tr style="height: 30px">
                <td colspan="2">
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width="100%">
                            <tr align="center" class="letrap">
                                <td height="30px">Apartit de la fecha <input type="text" name="FechaI" class="letrap" style="width: 90px;" value="<?= $Fecha ?>"></input></td>
                            </tr>
                            <tr align="center" class="letrap">
                                <td  height="30px">
                                    Institucion :
                                    <select class="letrap" name='Institucion'>
                                        <?php
                                        $InsA = mysql_query("select institucion,nombre,alias from inst order by institucion");
                                        while ($Ins = mysql_fetch_array($InsA)) {
                                            echo "<option value=$Ins[institucion]>$Ins[institucion] - $Ins[alias]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr align="center" class="letrap">
                                <td height="30px">Actualiza con la lista de precios : 
                                    <select name='Lista'  class="letrap">
                                        <option value='est.lt1'>Lista 1</option>
                                        <option value='est.lt2'>Lista 2</option>
                                        <option value='est.lt3'>Lista 3</option>
                                        <option value='est.lt4'>Lista 4</option>
                                        <option value='est.lt5'>Lista 5</option>
                                        <option value='est.lt6'>Lista 6</option>
                                        <option value='est.lt7'>Lista 7</option>
                                        <option value='est.lt8'>Lista 8</option>
                                        <option value='est.lt9'>Lista 9</option>
                                        <option value='est.lt10'>Lista 10</option>
                                        <option value='est.lt11'>Lista 11</option>
                                        <option value='est.lt12'>Lista 12</option>
                                        <option value='est.lt13'>Lista 13</option>
                                        <option value='est.lt14'>Lista 14</option>
                                        <option value='est.lt15'>Lista 15</option>
                                        <option value='est.lt16'>Lista 16</option>
                                        <option value='est.lt17'>Lista 17</option>
                                        <option value='est.lt18'>Lista 18</option>
                                        <option value='est.lt19'>Lista 19</option>
                                        <option value='est.lt20'>Lista 20</option>
                                        <option value='est.lt21'>Lista 21</option>
                                        <option value='est.lt22'>Lista 22</option>
                                        <option value='est.lt23'>Lista 23</option>
                                    </select></p>
                                </td>
                            </tr>
                            <tr align="center">
                                <td  height="30px">
                                    <input type="submit" class="letrap" name="Boton" value="Enviar"></input>
                                    <input type='hidden' name='op' value='ActPre'></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>

    </body>
</html>
<?php
mysql_close();
?>
