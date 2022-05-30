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
#Variables comunes;
$msj = $_REQUEST["Msj"];
$Titulo = "Ordenes de estudio";
$boton = $_REQUEST["bt"];

if ($boton === "Actualizar") {
    $Sql = "UPDATE pgs SET periodo = '$_REQUEST[Periodo]',medico ='$_REQUEST[Medico]',"
            . "fecha = '$_REQUEST[Fecha]',importe ='$_REQUEST[Importe]',"
            . "nota='$_REQUEST[Nota]',promotorasig = '$_REQUEST[Promotorasig]',"
            . "otro='$_REQUEST[Otro]',movto='$_REQUEST[Movto]' WHERE idpgs=$busca";
    if (mysql_query($Sql)) {
        $Msj = "¡ Registro actualizado con Exito !";
        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) "
                . "VALUES ('$Gusr','/Catalogos/Inf de visitas/Actualiza registro','pgs','$Fecha',$busca)";
        if (!mysql_query($sql)) {
            $msj = "Error en sintaxis MYSQL : $sql";
        }
        header("location:pagose.php?Msj=$Msj&busca=$busca");
    } else {
        echo "Error en sintaxis MYSQL :  " . $Sql;
    }
} else if ($boton === "NUEVO") {

    $Sql = "INSERT INTO pgs "
            . "(periodo,medico,fecha,importe,nota,promotorasig,otro,movto) "
            . "VALUES "
            . "('$_REQUEST[Periodo]','$_REQUEST[Medico]','$_REQUEST[Fecha]','$_REQUEST[Importe]',"
            . "'$_REQUEST[Nota]','$_REQUEST[Promotorasig]','$_REQUEST[Otro]','$_REQUEST[Movto]')";

    if (mysql_query($Sql)) {
        $Msj = "¡ Registro registrado con Exito !";
        $id = mysql_insert_id();

        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) "
                . "VALUES ('$Gusr','/Catalogos/Inf de visitas/Agrega registro','cmc','$Fecha',$id)";
        if (!mysql_query($sql)) {
            $msj = "Error en sintaxis MYSQL : $sql";
        } else {
            header("location:pagose.php?Msj=$Msj&busca=$id");
        }
    } else {
        echo "Error en sintaxis MYSQL : " . $Sql;
    }
}
if ($busca == 'NUEVO') {
    $Fecha = date("Y-m-d");
    $Medico = $_REQUEST["Medico"];
    $MpoA = mysql_query("SELECT * FROM med WHERE medico='$_REQUEST[Medico]'");

    $Mpo = mysql_fetch_array($MpoA);
    $nombrem = $Mpo["nombrec"];
    $Promotorasig = $Mpo["promotorasig"];
} else {
    $Fecha = $Cpo["fecha"];
    $Medico = $Cpo["medico"];
    $nombrem = $Cpo["nombrec"];
    $Otro = $Cpo["otro"];
    $Promotorasig = $Cpo["promotorasig"];
}
$CpoA = mysql_query("SELECT * FROM pgs WHERE idpgs = '$busca'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle Visitas Medicos</title>
            <?php require("config_add.php"); ?>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
        <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        </head>
    <script type="text/javascript">
        $(document).ready(function () {
            var observaciones = "<?= $msj ?>";
            if (observaciones != "") {
                Swal.fire({
                    title: observaciones,
                    position: "top-right",
                    icon: "success",
                    timer: 1500,
                    toast: true
                })
            }
        });



    </script>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Informacion Principal ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='45%'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle de Pago ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Id : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Id' value='<?= $Cpo[idpgs] ?>' disabled></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Fecha : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fecha' value='<?= $Cpo[fecha] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Medico : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Medico' value='<?= $Cpo[medico] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Periodo : 
                                </td>
                                <td class="Inpt">
                                    <select class="letrap" name='Periodo'>
                                        <?php
                                        $PerA = mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
                                        while ($Per = mysql_fetch_array($PerA)) {
                                            echo "<option value='$Per[mes]'>$Per[mes]</option>";
                                        }
                                        echo "<option selected value='$Cpo[periodo]'>$Cpo[periodo]</option>";
                                        ?>  
                                    </select>                          
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Movto : 
                                </td>
                                <td class="Inpt">
                                    <select class="letrap" name='Movto'>
                                        <option value='Visita'>Visita</option>
                                        <option value='Pago'>Pago Comision</option>
                                        <option value='Garantia'>Garantia Servicio</option>
                                        <option value='Visitaesp'>Visita Especifica</option>
                                        <option selected value='<?= $Cpo[movto] ?>'><?= $Cpo[movto] ?></option>
                                    </select>                          
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Importe : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Importe' value='<?= $Cpo[importe] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Observaciones : 
                                </td>
                                <td class="Inpt">
                                    <textarea name='Nota' cols='50' rows='4' class="letrap"><?= $Cpo[nota] ?></textarea>                            
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Importe : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Importe' value='<?= $Cpo[importe] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Visitado por : 
                                </td>
                                <td class="Inpt">
                                    <select class="letrap" name='Promotorasig'>
                                        <option value='Promotor_A'>Promotor_A</option>
                                        <option value='Promotor_B'>Promotor_B</option>
                                        <option value='Promotor_C'>Promotor_C</option> 
                                        <option value='Promotor_D'>Promotor_D</option>
                                        <option value='Promotor_E'>Promotor_E</option>
                                        <option value='Promotor_F'>Promotor_F</option>
                                        <option value='Otro'>Otro</option>
                                        <option selected value="<?= $Cpo[promotorasig] ?>"><?= $Cpo[promotorasig] ?></option>
                                    </select>                             
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Otro : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='letrap'  name='Otro' value='<?= $Cpo[otro] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    $busca === "NUEVO" ? $TipoInput = "NUEVO" : $TipoInput = "Actualizar";
                                    ?>
                                    <input class="letrap" type="submit" value='<?= $TipoInput ?>' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                            </form>
                        </table>  
                    </td>
                    <td valign='top' width='45%'>
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
                                                WHERE accion like ('/Catalogos/Inf de visitas/%') 
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
                        <a href="pagos.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
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
