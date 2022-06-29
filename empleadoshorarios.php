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

if ($_REQUEST["Boton"] === "Guardar") {
    if ($_REQUEST["DiaSemana"] == 7) {
        for ($i = 0; $i <= 5; $i++) {
            $Insert = "INSERT INTO horariosTrabajo (empleado,horario,entrada,salida) "
                    . "VALUES ('" . $busca . "','" . $i . "','" .
                    $_REQUEST["HoraEntrada"] . "','" . $_REQUEST["HoraSalida"] . "');";
            if (!mysql_query($Insert)) {
                $Msj = "Error en sintaxis: " . $Insert;
            }
        }
    } elseif ($_REQUEST["DiaSemana"] == 9) {
        for ($i = 0; $i <= 5; $i++) {
            $Insert = "INSERT INTO horariosTrabajo (empleado,horario,entrada,salida) "
                    . "VALUES ('" . $busca . "','" . $i . "','" .
                    $_REQUEST["HoraEntrada"] . "','" . $_REQUEST["HoraSalida"] . "');";
            if (!mysql_query($Insert)) {
                $Msj = "Error en sintaxis: " . $Insert;
            }
        }
    } else {
        $Insert = "INSERT INTO horariosTrabajo (empleado,horario,entrada,salida) "
                . "VALUES ('" . $busca . "','" . $_REQUEST["DiaSemana"] . "','" .
                $_REQUEST["HoraEntrada"] . "','" . $_REQUEST["HoraSalida"] . "');";
        if (!mysql_query($Insert)) {
            $Msj = "Error en sintaxis: " . $Insert;
        }
        $Msj = "Registro ingreado con exito!";
    }
    AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Agrega registro en horario', "horariosTrabajo", $Fecha, $busca, $Msj, "empleadoshorarios.php");
} else if ($_REQUEST["Op"] === "EliminaHorario") {
    $Delete = "DELETE FROM horariosTrabajo WHERE id = " . $_REQUEST["Delete"];
    if (!mysql_query($Delete)) {
        $Msj = "Error en sintaxis: " . $Delete;
    }
    $Msj = "Registro eliminado con exito!";
    AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Elimina registro en horario', "horariosTrabajo", $Fecha, $busca, $Msj, "empleadoshorarios.php");
} else if ($_REQUEST["Boton"] === "Edita") {
    $Update = "UPDATE horariosTrabajo SET horario = '" . $_REQUEST["DiaSemana"] . "', entrada = '" . $_REQUEST["HoraEntrada"] . "',"
            . "salida = '" . $_REQUEST["HoraSalida"] . "' WHERE id = '" . $_REQUEST["Edit"] . "';";
    if (!mysql_query($Update)) {
        $Msj = "Error en sintaxis: " . $Update . ": " . mysql_error();
    } else {
        $Msj = "Registro editado con exito!";
    }

    AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Agrega registro en horario', "horariosTrabajo", $Fecha, $busca, $Msj, "empleadoshorarios.php");
} else if ($_REQUEST["BotonPs"] === "Guardar") {
    $UpdatePS = "UPDATE emp SET ingreso = md5('" . $_REQUEST["PassEmp"] . "') WHERE id = $busca";
    if (mysql_query($UpdatePS)) {
        $Msj = "Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Cambia contrañsea', "emp", $Fecha, $busca, $Msj, "empleadoshorarios.php");
    }
} else if ($_REQUEST["Boton"] === "Agregar Codigo") {
    $InsertTarjeta = "INSERT INTO tarjetas (clave,id_empleado) VALUES ('" . $_REQUEST["Clave"] . "','" . $busca . "');";
    if (mysql_query($InsertTarjeta)) {
        $Msj = "Registro ingresado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Agrega tarjeta', "emp", $Fecha, $busca, $Msj, "empleadoshorarios.php");
    }
    echo mysql_error();
}

#Variables comunes;
$CpoA = mysql_query("SELECT * FROM emp WHERE id = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <meta charset = "UTF-8"></meta>
        <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
        <title>Empleados - Info. Personal</title>
        <?php require ("./config_add.php"); ?>
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            $(document).ready(function () {
                var busca = "<?= $busca ?>";
                $("#DiaSemana").val("<?= $_REQUEST[dia] ?>");
            });
        </script>
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <table class="PrincipalTable">    
            <tr>
                <td colspan="3" class="Title">..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombrec])) ?> ::..</td>
            </tr>
            <tr id="Info">
                <td>
                    <table class="SubTablas">  
                        <tr><td>..:: Horarios ::.. </td></tr>
                        <tr>
                            <td>
                                <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                    <div style="padding: 15px;">
                                        Dia: 
                                        <select name="DiaSemana" id="DiaSemana">
                                            <option value="0">Lunes</option>
                                            <option value="1">Martes</option>
                                            <option value="2">Miercoles</option>
                                            <option value="3">Jueves</option>
                                            <option value="4">Viernes</option>
                                            <option value="5">Sabado</option>
                                            <option value="6">Domingo</option>
                                            <option value="7">Lunes-Sabado</option>
                                            <option value="9">Lunes -Domingo</option>
                                        </select>
                                        Hora de entrada:
                                        <input type="time" name="HoraEntrada" value="<?= $_REQUEST["e"] ?>"></input>
                                        Hora de salida:
                                        <input type="time" name="HoraSalida" value="<?= $_REQUEST["s"] ?>"></input>
                                        <?php $Boton = $_REQUEST["Op"] === "EditaHorario" ? "Edita" : "Guardar"; ?>
                                        <input type="submit" name="Boton" value="<?= $Boton ?>"></input>
                                        <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                        <input type="hidden" name="Edit" value="<?= $_REQUEST["Edit"] ?>"></input>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </table>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table class="SubTablas">  
                            <tr><td>..:: Agregar Contraseña ::.. </td></tr>
                            <tr>
                                <td>
                                    Contraseña : <input type="password" name="PassEmp"></input>
                                    <input type="submit" name="BotonPs" value="Guardar"></input>
                                    <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table class="SubTablas">  
                            <tr><td>..:: Agregar Tarjeta ::.. </td></tr>
                            <tr>
                                <td>
                                    No.Tarjeta : <input type="text" name="Clave"></input>
                                    <input type="submit" name="Boton" value="Agregar Codigo"></input>
                                    <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                </td>
                            </tr>

                        </table>
                        <table class="letrap">
                            <tr><td>Tarjetas Agregadas</td></tr>
                            <?php
                            $BuscaTarjetas = "SELECT clave FROM tarjetas WHERE id_empleado = " . $busca;
                            $Tar = mysql_query($BuscaTarjetas);
                            while ($rsTj = mysql_fetch_array($Tar)) {
                                ?>
                                <tr><td style="align-content: center !important;"><?= $rsTj["clave"] ?></td></tr>
                                <?php
                            }
                            ?>
                        </table>
                    </form>
                </td>
                <td>
                    <table class="SubTablas">  
                        <tr><td colspan="5">..:: Horario ::..</td></tr>
                        <?php
                        $SqlI = "SELECT * FROM horariosTrabajo WHERE empleado = " . $busca;
                        $i = 0;
                        if ($SqlHr = mysql_query($SqlI)) {
                            ?>
                            <tr bgcolor="#58D68D">
                                <td align="center">Dia</td>
                                <td align="center">Entrada</td>
                                <td align="center">Salida</td>
                                <td align="center">Editar</td>
                                <td align="center">Eliminar</td>
                            </tr>
                            <?php
                            while ($Horarios = mysql_fetch_array($SqlHr)) {
                                switch ($Horarios["horario"]) {
                                    case 0:
                                        $Dia = "Lunes";
                                        break;
                                    case 1:
                                        $Dia = "Martes";
                                        break;
                                    case 2:
                                        $Dia = "Miercoles";
                                        break;
                                    case 3:
                                        $Dia = "Jueves";
                                        break;
                                    case 4:
                                        $Dia = "Viernes";
                                        break;
                                    case 5:
                                        $Dia = "Sabado";
                                        break;
                                    case 6:
                                        $Dia = "Domingo";
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?= $Dia ?></td>
                                    <td align="center"><?= $Horarios["entrada"] ?></td>
                                    <td align="center"><?= $Horarios["salida"] ?></td>
                                    <td align="center">
                                        <a href="empleadoshorarios.php?busca=<?= $busca ?>&Edit=<?= $Horarios["id"] ?>&Op=EditaHorario&dia=<?= $Horarios["horario"] ?>&e=<?= $Horarios["entrada"] ?>&s=<?= $Horarios["salida"] ?>">
                                            <i class="fa fa-pencil fa-lg" aria-hidden="true" style="color:#1F618D;"></i>
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a href="empleadoshorarios.php?busca=<?= $busca ?>&Delete=<?= $Horarios["id"] ?>&Op=EliminaHorario">
                                            <i class="fa fa-trash fa-lg" aria-hidden="true" style="color:#A93226;"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </table>
                </td>
                <td>
                    <?php
                    SbmenuEmpledos();
                    ?>
                </td>
            </tr>      
        </table>
    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#Nohijos").change(function () {
                let inputValue1 = document.querySelector("#Nohijos").value;
                muestraHijos(inputValue1);
            });
            var num = "<?= $Cpo[nohijos] ?>";
            muestraHijos(num);
            $(".dEstudia").hide();
            if ($("#Cedulap").val() == "------") {
                $(".dCedula").hide();
                $(".qCedula").show();
                $("#dCed").val("No")
            } else {
                $(".qCedula").hide();
            }


            $("#dCed").change(function () {
                console.log("holas" + $("#dCed").val());
                if ($("#dCed").val() == "Si") {
                    $(".dCedula").show();
                    $(".qCedula").hide();
                }
            });
        });
        function muestraHijos(variable) {
            if (variable == 0) {
                $(".hijo1").hide();
                $(".hijo2").hide();
                $(".hijo3").hide();
                $(".hijo4").hide();
                $(".hijo5").hide();
            } else if (variable == 1) {
                $(".hijo1").show();
                $(".hijo2").hide();
                $(".hijo3").hide();
                $(".hijo4").hide();
                $(".hijo5").hide();
            } else if (variable == 2) {
                $(".hijo1").show();
                $(".hijo2").show();
                $(".hijo3").hide();
                $(".hijo4").hide();
                $(".hijo5").hide();
            } else if (variable == 3) {
                console.log("ENTRA");
                $(".hijo1").show();
                $(".hijo2").show();
                $(".hijo3").show();
                $(".hijo4").hide();
                $(".hijo5").hide();
            } else if (variable == 4) {
                $(".hijo1").show();
                $(".hijo2").show();
                $(".hijo3").show();
                $(".hijo4").show();
                $(".hijo5").hide();
            } else if (variable == 5) {
                $(".hijo1").show();
                $(".hijo2").show();
                $(".hijo3").show();
                $(".hijo4").show();
                $(".hijo5").show();
            }
        }
    </script>
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

mysql_close();
?>
