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
$busca = $_REQUEST[busca];
if ($_REQUEST[bt] === "Actualizar") {
    $sql = "UPDATE ct SET receta='$_REQUEST[Receta]',fecharec='$_REQUEST[Fecharec]',descuento='$_REQUEST[Descuento]',diagmedico='$_REQUEST[Diagmedico]',"
            . "observaciones='$_REQUEST[Observaciones]',fechae='$_REQUEST[Fechae]',horae='$_REQUEST[Horae]'"
            . " WHERE ct.orden='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis mysql" . $sql;
    }
}elseif ($_REQUEST[bt] === "Cambiar") {
    header("Location: clientese.php?busca=$_REQUEST[IdExt]&rg=ord&idext=$busca");
}
$sCpo = "SELECT ct.id,ct.fecha,ct.fecharec,ct.hora,ct.fechae,ct.cliente idex,cli.nombrec as nombrecli,ct.importe,ct.descuento,ct.receta,
    ct.institucion,ct.medico,med.nombrec as nombremedico,ct.recibio,ct.institucion,ct.diagmedico,
    ct.observaciones,cli.fechan,ct.suc,ct.recepcionista,ct.servicio
    FROM ct,cli,med
    WHERE ct.cliente=cli.cliente AND ct.medico=med.medico AND ct.id='$busca'";

//echo $sCpo;
$CpoA = mysql_query($sCpo);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
} else {
    $Estado = $Cpo[estado];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Recepción - Consulta OT's</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu,$Gusr);
        ?>
        <script src="./controladores.js"></script>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de: <?= $Cpo[orden] . ' ' . ucwords(strtolower($Cpo[nombrecli])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Datos Principales ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    No. de Orden : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Orden' value='<?= $Cpo[id] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Fecha : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fecha' value='<?= $Cpo[fecha] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Hora : 
                                </td>
                                <td class="Inpt">
                                    <input type='time' class='cinput'  name='Hora' value='<?= $Cpo[hora] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Paciente : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Nombrec" type="text" rows="1" cols="30" disabled><?= $Cpo[nombrecli] ?></textarea>
                                    <input type="hidden" value='<?= $Cpo[idex] ?>' name='IdExt'></input>
                                    <input class="letrap" type="submit" value='Cambiar' name='bt'></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    No. de receta : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Receta' value='<?= $Cpo[receta] ?>' MAXLENGTH='30' disabled></input>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" class="Inpt">
                                    Fecha de receta : 
                                </td>
                                <td>
                                    <input type='date' class='cinput'  name='Fecharec' value='<?= $Cpo[fecharec] ?>' MAXLENGTH='30' disabled></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Diagnostico : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Diagmedico" type="text" rows="3" cols="45" disabled><?= $Cpo[diagmedico] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Observaciones : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Observaciones" type="text" rows="3" cols="45" disabled><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                            <tr height='30' class='letrap'>
                                <td align='right'>
                                    Institucion:
                                </td>
                                <td>
                                    <select name="Institucion" disabled>  
                                        <?php
                                        $InsA = mysql_query("SELECT institucion as id,alias,lista,condiciones FROM inst WHERE status='ACTIVO' ORDER BY institucion");
                                        while ($Ins = mysql_fetch_array($InsA)) {
                                            ?>
                                            <option value='<?= $Ins[id] ?>'><?= $Ins[alias] ?></option>
                                            <?php
                                            if ($He[institucion] == $Ins[id]) {
                                                ?>
                                                <option selected="<?= $He[institucion] ?>"><?= $Ins[alias] ?></option>  
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Medico : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Medico' value='<?= $Cpo[nombremedico] ?>' size='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr  class='letrap'><td align='right'>Servicio: &nbsp; </td><td>
                                    <select name="Servicio" disabled>
                                        <option value="Ordinario">Ordinario</option>
                                        <option value="Urgente">Urgente</option>
                                        <option value="Express">Express</option>
                                        <option value="Hospitalizado">Hospitlizado</option>
                                        <option value="Nocturno">Nocturno</option>
                                        <option selected="<?= $Cpo[servicio] ?>"><?= $Cpo[servicio] ?></option>  
                                    </select> &nbsp;         
                                    <?php
                                    if ($He[servicio] == 'Cita') {
                                        ?>
                                        &nbsp; No.de cita: <?= $He[citanum] ?> 
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td valign='top' width='45%'>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle ::..
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table align="center" cellpadding="3" cellspacing="2" width="97%" border='0'>
                                        <tr>
                                            <td height="5px" colspan="4">
                                            </td>
                                        </tr>
                                        <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                            <td height="22px">
                                                <b>Estudio</b>
                                            </td>
                                            <td>
                                                <b>Precio</b>
                                            </td>
                                            <td>
                                                <b>Descto %</b>
                                            </td>
                                            <td>
                                                <b>Importe</b>
                                            </td>
                                        </tr>   
                                        <?php
                                        $sql = "SELECT est.descripcion,ctd.estudio,ctd.precio,ctd.status,ctd.descuento,ctd.precio-(ctd.precio*ctd.descuento)/100 as importe FROM ctd INNER JOIN est ON ctd.estudio = est.estudio  WHERE ctd.id='$busca'";
                                        //echo $sql;
                                        $result3 = mysql_query($sql);
                                        $num = 0;
                                        while ($cSql = mysql_fetch_array($result3)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = '#FFFFFF';
                                            } else {
                                                $Fdo = '#D5D8DC';
                                            }
                                            ?>
                                            <tr class="letrap" bgcolor="<?= $Fdo ?>">
                                                <td>
                                                    <?= $cSql[descripcion] ?>
                                                </td>
                                                <td align="right">
                                                    <?= number_format($cSql[precio], 2) ?>
                                                </td>
                                                <td align="center">
                                                    <?= $cSql[descuento] ?>
                                                </td>
                                                <td  align="right">
                                                    <?= number_format($cSql[importe], 2) ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $sumP = $sumP + $cSql[precio];
                                            $sumPi = $sumPi + $cSql[importe];
                                            $nRng++;
                                        }
                                        ?>
                                        <tr class="letrap">
                                            <td align="right"><b>Totales: ---> </
                                                    b></td>
                                            <td align="right"><b>
                                                    $  <?= number_format($sumP, 2) ?>
                                                </b></td>
                                            <td></td>
                                            <td align="right"><b>
                                                    $  <?= number_format($sumPi, 2) ?>
                                                </b></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <table>
                        <tr>
                            <td>
                                <a href="cotizaciones.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>      
        </table> 
    </body>
</html>
<?php
mysql_close();
?>
