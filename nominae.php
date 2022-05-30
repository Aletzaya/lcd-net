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

if ($_REQUEST["Boton"] === "Actualizar") {
    $Sql = "UPDATE nom SET fechap='$_REQUEST[Fechap]', fechai='$_REQUEST[Fechai]',nomina='$_REQUEST[Nomina]',
             fechaf='$_REQUEST[Fechaf]',status='$_REQUEST[Status]',numero='$_REQUEST[Numero]'
             WHERE id='$busca'";
    $Sc = mysql_query("SELECT cia FROM nom where id = $busca");
    $dt = mysql_fetch_array($Sc);
    if (mysql_query($Sql)) {
        $Msj = "!Registro Actualizado con Exito!";
        if ($_REQUEST["RecalculaNomina"] == "Si") {
            mysql_query("DELETE FROM nomf where id = " . $busca);
            Lcd($dt["cia"], $busca);
        }

        AgregaBitacoraEventos($Gusr, "/R.Humanos/Nomina/ Edita Detalle de Nomina", "nom", $Fecha, $busca, $Msj, "nominae.php");
    }
    echo mysql_error();
}

$_REQUEST[Recalcular] == 'Si' ? $Recal = true : $Recal = false;

if ($_REQUEST["Boton"] == 'Nuevo' or $Recal) {

    $cia = "SELECT numsem,numqui,sueldod,ispt,septimos,diasemana,profaltas,
                   asistencia,fallas,retardos,destajo,falta,diaquincena,septimosqui
                   FROM cia WHERE id='$Gcia'";
//echo $cia;
    $cCiaA = mysql_query($cia);
    $cCia = mysql_fetch_array($cCiaA);

    if (!$Recal) { //SI es una nomina nueva osea que no se va a recalcular;
        if ($_REQUEST["Nomina"] == 'Semana') {
            $Consec = $cCia["numsem"]; //Consecutivo de la semana;
            $lUp = mysql_query("UPDATE cia SET numsem = numsem + 1 WHERE id='$Gcia'");
        } else {
            $Consec = $cCia["numqui"]; //Consecutivo de la semana;
            $lUp = mysql_query("UPDATE cia SET numqui = numqui + 1 WHERE id='$Gcia'");
        }

#Agrego a la nomina fiscal todos los empleado que tienen imss;
        $Numero = cZeros($Consec, 2);

        $lUp = mysql_query("INSERT INTO nom (cia,fechai,fechaf,nomina,fiscal,numero,status,fechap)
                          VALUES
                          ('$Gcia','$_REQUEST[Fechai]','$_REQUEST[Fechaf]','$_REQUEST[Nomina]','Si','$Numero','Abierta','$_REQUEST[Fechap]')");
        echo mysql_error();
        $id = mysql_insert_id(); //Nomina fiscal;
    }

    Lcd($Gcia, $id);
    AgregaBitacoraEventos($Gusr, "/R.Humanos/Nomina/Crea registro", "nom", $Fecha, $id, "¡Registro agregado con exito!", "nominae.php");
}

#Variables comunes;
$CpoA = mysql_query("SELECT * FROM nom WHERE id = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8"></meta>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Empleados - Info. Personal</title>
        <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            $(document).ready(function () {
                var busca = "<?= $busca ?>";
                if (busca == "NUEVO") {
                    $("#tabla1").hide();
                    $("#tabla2").hide();
                    $("#logs").hide();
                }
                console.log("<?= $Cpo[nomina] ?>");
                $("#Nomina").val("<?= $Cpo[nomina] ?>");
                $("#Status").val("<?= $Cpo[status] ?>");
            });
        </script>
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();
                          '>
                        <table class="letrap" width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style = "background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="4">
                                    ..:: Detalle de la Nomina ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td colspan="1" width='23%' align="right">Id :</td>
                                <td>
                                    <input type='text' class='cinput' style="width:80px;" id="id" name='id' value='<?= $Cpo[id] ?>' disabled></input>
                                </td>
                                <td>Fecha del pago: </td>
                                <td><input type="date" value="<?= $Cpo[fechap] ?>" name="Fechap" id="Fechap" class="letrap"></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" >Tipo de nomina :</td>
                                <td >
                                    <select id="Nomina" name="Nomina" class="letrap">
                                        <option value="Semana">Semana</option>
                                        <option value="Quincena">Quincena</option>  
                                    </select>
                                </td>
                                <td align="right" >Numero :</td>
                                <td >
                                    <input type='number' class='cinput' name='Numero' id="Numero" value='<?= $Cpo[numero] ?>'></input>

                                </td>
                            </tr>
                            <tr style = "height: 30px">
                                <td align = "right">Fecha del :</td>
                                <td><input type="date" value="<?= $Cpo[fechai] ?>" name="Fechai" id="Fechai" class="letrap"></input></td>
                                <td  align="center">Al :</td>
                                <td><input type="date" value="<?= $Cpo[fechaf] ?>" name="Fechaf" id="Fechaf" class="letrap"></input></td>
                            </tr>
                            <tr style = "height: 30px">
                                <td align="right" >Recalcular nomina:</td>
                                <td>
                                    <select class="letrap" name="RecalculaNomina" id="RecalculaNomina" >
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                                <td align="right" >Status:</td>
                                <td>
                                    <select name='Status' id="Status" class="letrap" >
                                        <option value="Abierta">Abierta</option>
                                        <option value="Cerrada">Cerrada</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <?php is_numeric($busca) ? $Boton = "Actualizar" : $Boton = "Nuevo"; ?>
                                <td colspan="4" align="center">
                                    <input type="submit" name="Boton" value="<?= $Boton ?>" class="letrap"></input>
                                    <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                </td>
                            </tr>
                        </table>  
                    </form>
                </td>
                <td valign='top' width='45%'>
                    <table width='99%' align='center' id="logs" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="3">
                                .:: Imprime Recibos ::.
                            </td>
                        </tr>
                        <tr style="height: 50px;">
                            <td align="center"><a class="edit"><i class='fa fa-print fa-2x' aria-hidden='true'></i> Salario</a></td>
                            <td align="center"><a class="edit"><i class='fa fa-print fa-2x' aria-hidden='true'></i> Produccion</a></td>
                            <td align="center"><a class="edit"><i class='fa fa-print fa-2x' aria-hidden='true'></i> Premios</a></td>
                        </tr>
                    </table>
                    <table width='99%' align='center' id="logs" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
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
                                                WHERE accion like ('%/R.Humanos/Nomina/%') AND cliente=$busca
                                                ORDER BY id DESC LIMIT 6;";
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
                    <a href="nomina.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                </td>
            </tr>      
        </table>  
    </body>
    <script src="./controladores.js"></script>
</html>
<?php

function CalculaEdad($Fecha) {
    $Fcha = new DateTime($Fecha);
    $hoy = new DateTime();
    $edad1 = $hoy->diff($Fcha);
    $return = $edad1->y . " Años " . $edad1->m . " Meses";
    echo $return;
}

function Lcd($Gcia, $id) {

    $link = conectarse();
    $cCiaA = mysql_query("SELECT sueldod,ispt,septimos,diasemana,profaltas,asistencia,fallas,retardos,destajo,falta,
			 diaquincena,septimosqui
          FROM cia WHERE id='$Gcia'")
    ;
    $cCia = mysql_fetch_array($cCiaA);

    if ($_REQUEST["Nomina"] == 'Semana') {
        $DiasTra = $cCia["diasemana"];
        $DiasSep = $cCia["septimos"];
        $IsptVeces = 1;
    } else {
        $DiasTra = $cCia["diaquincena"];
        $DiasSep = $cCia["septimosqui"];
        $IsptVeces = 2;
    }
    $Em = "SELECT id,faltas,imss,retardos,sueldod,prestamo,ahorro,pension,"
            . "otrosing,otrosegr,cobertura,horasext,primavac,diastrab,festivos "
            . "FROM emp WHERE cia='$Gcia' AND nomina='$_REQUEST[Nomina]' AND status='Activo'";
    echo $Em;
    $EmpA = mysql_query($Em);
    while ($rg = mysql_fetch_array($EmpA)) {

        $Sueldo = $Septimo = $Ispt = 0;
        $DiasPer = $DiasTra + $DiasSep; //Dias de trabajo en el periodo;

        $Sueldo = $rg["sueldod"] * ($DiasTra - $rg["faltas"]); // (5-1) = 4;
        $Septimo = (($rg["sueldod"] * $DiasSep) / $DiasTra) * ($DiasTra - $rg["faltas"]); // ((sdo * 2) / 5) * 4 ;
        $Ispt = (($rg["ispt"] * $IsptVeces) / $DiasTra) * ($DiasTra - $rg["faltas"]); // ((ispt*1) / 5 ) * 4

        $Up = "INSERT into nomf (id,cuenta,sueldod,sueldo,septimo,ispt,faltas,retardos,impretardos,
           			 prestamo,ahorro,pension,horasext,cobertura,primavac,otrosing,otrosegr,diastrab,festivos)
                   VALUES
                   ('$id','$rg[id]','$rg[sueldod]','$Sueldo','$Septimo','$Ispt','$rg[faltas]','$rg[retardos]',
                   $rg[retardos]*$cCia[retardos],'$rg[prestamo]','$rg[ahorro]','$rg[pension]','$rg[horasext]',
                   '$rg[cobertura]','$rg[primavac]','$rg[otrosing]','$rg[otrosegr]','$rg[diastrab]','$rg[festivos]')
            		 ";
        $lUp = mysql_query($Up);
        echo $Up . mysql_error();
    }

    return $Apagar;
}

mysql_close();
?>

