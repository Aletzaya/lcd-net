<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

$Menu = $_REQUEST[Menu];


$Titulo = "Relacion Estudios por Depto $_REQUEST[FechaI] al $_REQUEST[FechaF]";

/* $cSql = "update otd,ot,est set otd.precio=$_REQUEST[Lista] where ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'
  and ot.orden=otd.orden and otd.estudio=est.estudio";

  $lUp = mysql_query($cSql);
 */
$SqlA = mysql_query("SELECT orden FROM ot WHERE ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'");

require("lib/lib.php");

$link = conectarse();

$Titulo = "Menu de opciones multiples";

$FechaI = date("Y-m-d");
$FechaF = date("Y-m-d");
$Fecha = date("Y-m-d");

require ("config.php");
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Reporte de estudios por analito</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <table width="100%" border="0">
            <tr> 
                <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
                    </div></td>
                <td width="80%">
                    <h3 align="center">Reporte analitos</h3>
                </td>
                <td width='10%' valign='top'>
                    <a href=javascript:close();><i title="Cerrar Reporte" class="fa fa-window-close" style="color:red;" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
        <form name='form1' method='get' action='reportexestudio.php'>
            <table id="table0" width='85%'align='center' class='letrasubt' border='0' cellpadding='3' cellspacing='2' bgcolor='#7FB3D5' style='border-radius: 20px;'>
                <tr><td align='right' width='50%'>Fecha Inicial: </td><td>
                        <input type='date' class='letrap'  name='FechaIni' value ='<?= $Fecha ?>'/>
                    </td>
                </tr>
                <tr><td align='right' width='50%'>Fecha Final: </td><td>
                        <input type='date' class='letrap'  name='FechaFin' value ='<?= $Fecha ?>'/>
                    </td>
                </tr>
                <tr>
                    <td align='right'>Institución: </td><td>
                        <select name='Insti' class='letrap'>
                            <option value='TODOS'>Todos</option>
                            <option value='LCD'>* INSTITUCIONES - LCD *</option>
                            <option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>
                            <?php
                            $InsA = mysql_query("SELECT institucion,nombre FROM inst ORDER BY institucion");
                            while ($Ins = mysql_fetch_array($InsA)) {
                                echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
                            }
                            ?> 
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align='right'>Departamento:</td>
                    <td>
                        <select name="Departamento" class="letrap">
                            <option value="TODOS">Todos</option>
                            <?php
                            $SqlEst = "SELECT departamento,nombre FROM dep ";
                            $est = mysql_query($SqlEst);
                            while ($rst = mysql_fetch_array($est)) {
                                echo "<option value='$rst[departamento]'>$rst[departamento] .- $rst[nombre]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align='right'>Sub Departamento :</td>
                    <td>
                        <select name="SubDepartamento" class="letrap">
                            <option value="TODOS">Todos</option>
                            <?php
                            $SqlEst = "SELECT subdepto FROM est GROUP BY subdepto;";
                            $est = mysql_query($SqlEst);
                            while ($rst = mysql_fetch_array($est)) {
                                echo "<option value='$rst[subdepto]'>$rst[subdepto]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">Sucursal: </td>
                    <td>
                        <p><input  type="checkbox" class="botonAnimated" name="Matriz" value="1"/> Matriz</p>
                        <p><input  type="checkbox" class="botonAnimated"  name="HF" value="2"/> Hospital Futura </p>
                        <p><input type="checkbox" class="botonAnimated" name="Tepexpan" value="3"/> Tepexpan</p>
                        <p><input type="checkbox" class="botonAnimated" name="LR" value="4"/> Los Reyes</p>
                        <p><input type="checkbox" class="botonAnimated" name="Camarones" value="5"/> Camarones</p>
                        <p><input type="checkbox" class="botonAnimated" name="SV" value="6"/> San Vicente</p>
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="2" style="align-content: center;">
                        <input class="letrap" type='SUBMIT' value='Enviar'/>
                        <input type="hidden" name="Op" value="Busca">
                    </td>
                </tr>
            </table>
        </form>


        <?php
        $ConcatInst = "";
        if ($_REQUEST["Insti"] <> "TODOS") {
            $ConcatInst = " AND ot.institucion = '" . $_REQUEST["Insti"] . "' ";
        }
        $Sc = "100";
        $Sc .= is_numeric($_REQUEST["Matriz"]) ? "," . $_REQUEST["Matriz"] : "";
        $Sc .= is_numeric($_REQUEST["HF"]) ? "," . $_REQUEST["HF"] : "";
        $Sc .= is_numeric($_REQUEST["Tepexpan"]) ? "," . $_REQUEST["Tepexpan"] : "";
        $Sc .= is_numeric($_REQUEST["LR"]) ? "," . $_REQUEST["LR"] : "";
        $Sc .= is_numeric($_REQUEST["Camarones"]) ? "," . $_REQUEST["Camarones"] : "";
        $Sc .= is_numeric($_REQUEST["SV"]) ? "," . $_REQUEST["SV"] : "";

        if ($Sc !== "100") {
            $ConcatSuc = " AND ot.suc in ($Sc) ";
        }
        /*
          if ($_REQUEST["Estudio"] <> "TODOS" || $_REQUEST["Estudio"] <> "") {
          $ConcatEst = " AND dep.nombre in (SELECT otd.estudio FROM otd left join conest on "
          . "otd.estudio=conest.estudio left join ot on ot.orden=otd.orden "
          . "where ot.fecha BETWEEN '" . $_REQUEST["FechaIni"] . "' AND '" . $_REQUEST["FechaFin"] . "' "
          . "and conest.conest = '" . $_REQUEST["Estudio"] . "' group by otd.estudio) ";
          } */
        if ($_REQUEST["Departamento"] <> "TODOS") {
            $Departamento = " AND est.depto = '" . $_REQUEST["Departamento"] . "' ";
        }
        if ($_REQUEST["SubDepartamento"] <> "TODOS") {
            $SubDepto = " AND est.subdepto = '" . $_REQUEST["SubDepartamento"] . "' ";
        }


        $Sql = "SELECT  otd.estudio,count(1) cnt , est.descripcion, otd.precio, count(otd.orden), "
                . "sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto,"
                . " est.subdepto, otd.orden, dep.departamento, dep.nombre nombredep "
                . "FROM ot LEFT JOIN otd ON ot.orden = otd.orden LEFT JOIN est on otd.estudio=est.estudio LEFT JOIN dep on est.depto=dep.departamento "
                . "WHERE ot.fecha BETWEEN '" . $_REQUEST["FechaIni"] . "' AND '" . $_REQUEST["FechaFin"] . "' "
                . $ConcatInst . $ConcatSuc . $ConcatEst . $Departamento . $SubDepto . " GROUP BY otd.estudio, est.subdepto  Order by depto,subdepto,estudio asc ;";
        //echo $Sql;
        if ($_REQUEST["FechaIni"] != "") {
            $Qry = mysql_query($Sql);
        }
        ?>
        <table id="table1"align='center' width='100%' cellpadding='3' cellspacing='2'>
            <thead>
                <tr bgcolor='#5499C7'>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Depto</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Subdepto</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Estudio</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Descripcion</strong></td>
                    <td class='letrap' align='center'><strong style='color:#D5D8DC;'>Cantidad</strong></td>
                    <td class='letrap' align='center' colspan="100%"><strong style='color:#D5D8DC;'>Detalle de Estudios</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rs = mysql_fetch_array($Qry)) {
                    ($nRng % 2) > 0 ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;

                    $SqlSt = mysql_query("SELECT * FROM conest WHERE estudio = '" . $rs["estudio"] . "';");
                    ?>
                    <tr bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor = '#b7e7a7'; this.style.cursor = 'hand' onMouseOut=this.style.backgroundColor = '<?= $Fdo ?>'; >
                        <td class='letrap'><?= $rs["nombredep"] ?></td>
                        <td class='letrap'><?= $rs["subdepto"] ?></td>
                        <td class='letrap'><?= $rs["estudio"] ?> </td>
                        <td class='letrap'><?= $rs["descripcion"] ?></td>
                        <td class='letrap' align='right'><?= number_format($rs["cnt"], "0") ?></td>
                        <td class="letrap">
                            <table width="100%" class="letrap" border="1" cellpadding="0" cellspacing="0">
                                <?php
                                $e = 1;
                                $i = 1;
                                while ($rst = mysql_fetch_array($SqlSt)) {
                                    if ($i == 1 || $i == $e) {
                                        ?><tr><?php
                                        }
                                        ?>
                                        <td bgcolor="#b7e7a7" onMouseOver=this.style.backgroundColor = '<?= $Fdo ?>';this.style.cursor='hand' onMouseOut=this.style.backgroundColor = '#b7e7a7'; style="width: 10%;" align="left">
                                            <?= $rst["conest"] ?>
                                        </td>
                                        <?php
                                        if (is_integer($i / 10)) {
                                            ?></tr><?php
                                        $e += 10;
                                    }
                                    $i++;
                                }
                                ?>
                            </table></td>

                    </tr>

                    <?php
                    $nRng++;
                }
                ?>
            </tbody>
        </table>
        <br>

            <table id="table2"align='center' width='98%' cellpadding='3' cellspacing='2'>
                <thead>
                    <tr bgcolor="#85929E">
                        <td width="100%" colspan="100%" align="center" class="Subt"> Total de estudios realizados por analito</td>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $Sql = "select count(*) cnt, conest.conest from otd LEFT JOIN ot "
                            . "ON ot.orden=otd.orden LEFT JOIN conest ON otd.estudio=conest.estudio "
                            . "LEFT JOIN est ON otd.estudio=est.estudio "
                            . "WHERE ot.fecha BETWEEN '" . $_REQUEST["FechaIni"] . "' AND '" . $_REQUEST["FechaFin"] . "' "
                            . $ConcatInst . $ConcatSuc . $ConcatEst . $Departamento . $SubDepto . " group by conest.conest order by conest.conest;";

                    if ($_REQUEST["FechaIni"] != "") {
                        $Qry = mysql_query($Sql);
                    }
                    ?>
                    <?php
                    $i = 1;
                    $e = 1;
                    while ($rst = mysql_fetch_array($Qry)) {

                        if ($i == 1 || $i == $e) {
                            ?><tr><?php
                            }
                            ?>
                            <td class="letrap" bgcolor='#5499C7' style="border-radius: 50px;width: 250px;padding: 0px 5px;">
                                <?= $rst["conest"] ?> :
                            </td>
                            <td  bgcolor='#AED6F1' class="letrap" style="border-radius: 50px;width:155px" align="center"><?= $rst["cnt"] ?></td>
                            <?php
                            if (is_integer($i / 10)) {
                                ?></tr><?php
                            $e += 10;
                        }
                        ?>

                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>

            <table width="95%" align="center" id="ImpReturn">
                <tr>
                    <td align="left"><a id="Imprime" onClick="print()" class="edit"><i class="fa fa-print fa-3x" aria-hidden="true"></i></a></td>
                    <td align="right">
                        <a href="reportexestudio.php" class="content5"  id="Regresa">
                            <i class="fa fa-reply fa-2x" title="Regresar a formulario" aria-hidden="true"></i> Regresar 
                        </a>
                    </td>
                </tr>
            </table>
    </body>
    <script type="text/javascript">
        $("#table1").hide();
        $("#table2").hide();
        $("#ImpReturn").hide();
        if ("<?= $_REQUEST[Op] ?>" == "Busca") {
            $("#table0").hide();
            $("#table1").show();
            $("#table2").show();
            $("#ImpReturn").show();
        }
    </script>
</html>
<?php
mysql_close();
