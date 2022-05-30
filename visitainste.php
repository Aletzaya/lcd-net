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
$Msj = $_REQUEST["Msj"];
$Titulo = "Ordenes de estudio";
$boton = $_REQUEST["bt"];

if ($boton === "Actualizar") {
    $Sql = "UPDATE vinst SET fecha='$_REQUEST[Fecha]',nota = '$_REQUEST[Nota]',periodo='$_REQUEST[Periodo]',"
            . "movto='$_REQUEST[Movto]', promotorasig = '$_REQUEST[Promotorasig]',otro = '$_REQUEST[Otro]' WHERE id = $busca";
    if (mysql_query($Sql)) {
        $Msj = "Actualizacion realizada con exito";
        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Promocion/Registro Visitas Inst/Edita registro','vinst', "
                . "'$Fecha',$busca)";
        if (!mysql_query($sql)) {
            $msj = "Error en sintaxis MYSQL : $sql";
        } else {
            header("location:visitainste.php?Msj=$Msj&busca=$busca");
        }
    } else {
        echo "Error SQL :" . $Sql;
    }
} else if ($boton === "NUEVO") {
    $Sql = "INSERT INTO vinst (fecha,inst,nota,periodo,movto,promotorasig,otro) "
            . "VALUES ('$_REQUEST[Fecha]', '$_REQUEST[Inst]', '$_REQUEST[Nota]',"
            . "'$_REQUEST[Periodo]', '$_REQUEST[Movto]','$_REQUEST[Promotorasig]', '$_REQUEST[Otro]')";
    if (mysql_query($Sql)) {
        $id = mysql_insert_id();
        $Msj = "Actualizacion realizada con exito";
        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Promocion/Registro Visitas Inst/Agrega registro','vinst', "
                . "'$Fecha',$id)";
        if (!mysql_query($sql)) {
            $msj = "Error en sintaxis MYSQL : $sql";
        } else {
            header("location:visitainste.php?Msj=$Msj&busca=$id");
        }
    } else {
        echo "Error SQL :" . $Sql;
    }
}

$CpoA = mysql_query("SELECT vinst.id,vinst.fecha,vinst.inst,vinst.nota,vinst.periodo,inst.nombre,
  		   vinst.movto,vinst.promotorasig,vinst.otro
		   FROM vinst
  		   LEFT JOIN inst ON vinst.inst=inst.institucion	
           WHERE id='$busca'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle Visitas Institucion</title>
            <?php require("config_add.php"); ?>
    </head>

    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
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
                                    ..:: Detalle de Visitas Institucion ::..
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
                                    Fecha : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Fecha' value='<?= $Cpo[fecha] ?>' ></input>                                
                                </td>
                            </tr>
                            <?php
                            if ($busca === "NUEVO") {
                                ?>
                                <tr style="height: 30px">
                                    <td width='45%' align="right" class="Inpt">
                                        Institucion : 
                                    </td>
                                    <td class="Inpt">
                                        <input type='text' class='cinput'  name='Inst' value='<?= $_REQUEST[nvoId] ?>' ></input>    
                                        <a class="edit" href='fmtinst1.php?busca=ini&miBusca=<?= $busca ?>'>
                                            <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Nombre : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombre' value='<?= $Cpo[nombre] ?>' disabled></input>                                
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
                                    Observaciones : 
                                </td>
                                <td class="Inpt">
                                    <textarea name='Nota' cols='50' rows='4' class="letrap"><?= $Cpo[nota] ?></textarea>                            
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Visitado por : 
                                </td>
                                <td class="Inpt">
                                    <select class="letrap" id="Promotorasig" name='Promotorasig'>
                                        <option value='Promotor_A'>Promotor_A</option>
                                        <option value='Promotor_B'>Promotor_B</option>
                                        <option value='Promotor_C'>Promotor_C</option> 
                                        <option value='Promotor_D'>Promotor_D</option>
                                        <option value='Promotor_E'>Promotor_E</option>
                                        <option value='Promotor_F'>Promotor_F</option>
                                        <option value='Otro'>Otro</option>
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
                                                WHERE accion like ('/Promocion/Registro Visitas Inst/%') 
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
                        <a href="visitainst.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                        <a class="cMsj">
                            <?= $msj ?>
                        </a>
                    </td>
                </tr>      
            </table>
    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#Promotorasig").val("<?= $Cpo["promotorasig"] ?>");
        });
    </script>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
