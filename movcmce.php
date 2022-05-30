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
    $Sql = "UPDATE cmc SET medico = '$_REQUEST[Medico]',cliente = '$_REQUEST[Cliente]',inst = '$_REQUEST[Institucion]',mes='$_REQUEST[Mes]',"
            . "orden = '$_REQUEST[Orden]',concepto = '$_REQUEST[Concepto]', importe='$_REQUEST[Importe]', comision = '$_REQUEST[Comision]',"
            . "numestudios = '$_REQUEST[Numestudios]' WHERE id = '$busca' LIMIT 1;";
    if (mysql_query($Sql)) {
        $Msj = "¡ Registro actualizado con Exito !";
        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) "
                . "VALUES ('$Gusr','/Catalogos/Registro/Movto/Actualiza registro','cmc','$Fecha',$busca)";
        if (!mysql_query($sql)) {
            $msj = "Error en sintaxis MYSQL : $sql";
        }
        header("location:movcmce.php?Msj=$Msj&busca=$busca");
    } else {
        echo "Error en sintaxis MYSQL :  " . $Sql;
    }
} else if ($boton === "NUEVO") {
    $Sql = "INSERT INTO cmc (medico, inst, cliente, mes, orden, fecha, "
            . "concepto, importe, comision, numestudios) "
            . "VALUES "
            . "('$_REQUEST[Medico]','$_REQUEST[Institucion]','$_REQUEST[Cliente]','$_REQUEST[Mes]',"
            . "'$_REQUEST[Orden]','" . date("Y-m-d") . "','$_REQUEST[Concepto]','$_REQUEST[Importe]','$_REQUEST[Comision]','$_REQUEST[Numestudios]')";
    if (mysql_query($Sql)) {
        $Msj = "¡ Registro registrado con Exito !";
        $id = mysql_insert_id();

        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) "
                . "VALUES ('$Gusr','/Catalogos/Registro/Movto/Agrega registro','cmc','$Fecha',$busca)";
        if (!mysql_query($sql)) {
            $msj = "Error en sintaxis MYSQL : $sql";
        }
        header("location:movcmce.php?Msj=$Msj&busca=$id");
    } else {
        echo "Error en sintaxis MYSQL : " . $Sql;
    }
}
$CpoA = mysql_query("SELECT * FROM cmc WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Medicos - Info. Personal</title>
            <?php require("config_add.php"); ?>
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
                                    ..:: Detalle de Medico ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Id : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Id' value='<?= $Cpo[id] ?>' disabled></input>                                
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
                                    Cliente : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Cliente' value='<?= $Cpo[cliente] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Institucion : 
                                </td>
                                <td class="Inpt">
                                    <select class="letrap" name='Institucion'>
                                        <?php
                                        $cIns = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
                                        while ($Ins = mysql_fetch_array($cIns)) {
                                            echo "<option value='$Ins[institucion]'> $Ins[institucion]&nbsp;$Ins[alias]</option>";
                                            if ($Ins[institucion] == $Cpo[inst]) {
                                                $DesIns = $Ins[alias];
                                            }
                                        }
                                        echo "<option selected>$Cpo[inst]&nbsp;$DesIns</option>";
                                        ?>  
                                    </select>                          
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Periodo : 
                                </td>
                                <td class="Inpt">
                                    <select class="letrap" name='Mes'>
                                        <?php
                                        $PerA = mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
                                        while ($Per = mysql_fetch_array($PerA)) {
                                            echo "<option value='$Per[mes]'>$Per[mes]</option>";
                                        }
                                        echo "<option selected value='$Cpo[mes]'>$Cpo[mes]</option>";
                                        ?>  
                                    </select>                          
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Orden : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Orden' value='<?= $Cpo[orden] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Concepto : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Concepto' value='<?= $Cpo[concepto] ?>' ></input>                                
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
                                    Comision : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Comision' value='<?= $Cpo[comision] ?>' ></input>                                
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Numero de estudios : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Numestudios' value='<?= $Cpo[numestudios] ?>' ></input>                                
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
                                                WHERE accion like ('/Catalogos/Registro/Movto%') 
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
                        <a href="movcmc.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
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

