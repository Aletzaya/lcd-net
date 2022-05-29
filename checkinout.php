<?php
#Librerias
session_start();
date_default_timezone_set("America/Mexico_City");
require("lib/lib.php");
$link = conectarse();

$busca = $_REQUEST["busca"];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];
#Variables comunes;

if ($_REQUEST["Boton"] === "Registrar") {
    $BuscaEmp = "SELECT id,nombre FROM emp WHERE id='" . $_REQUEST["Usuario"] . "' AND ingreso = md5('" . $_REQUEST["Password"] . "');";
    if ($result = mysql_query($BuscaEmp)) {
        $EmpId = mysql_fetch_array($result);
        if (is_numeric($EmpId["id"])) {
            $Insert = "INSERT INTO asistencia (empleado,fecha) VALUES ('" . $EmpId["id"] . "','" . date("Y-m-d H:i:s") . "');";
            if (!mysql_query($Insert)) {
                echo mysql_error();
            } else {
                $Msj = "Bienvenido " . $result["nombre"] . " registro con hora: " . date("Y-m-d H:i:s");
            }
        } else {
            $Msj = "Usuario no encontrado";
        }
    }
    $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "';";
    if ($RNota = mysql_query($Nota)) {
        while ($ResNt = mysql_fetch_array($RNota)) {
            echo "A " . $ResNt["horario"] . " A  Y " . (date("w") - 1) . "<br>";
            if ($ResNt["horario"] == 0 && (date("w") - 1) == 0) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 0;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                }
            } else if ($ResNt["horario"] == 1 && (date("w") - 1) == 1) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 1;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                }
            } elseif ($ResNt["horario"] == 2 && (date("w") - 1) == 2) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 2;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                    $entrada = $rst["entrada"];
                    $salida = $rst["salida"];
                    //$dif = date("H:s");
                }
            } elseif ($ResNt["horario"] == 3 && (date("w") - 1) == 3) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 3;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                    echo $rst["entrada"];
                    echo $rst["salida"];
                    echo date("H:m");
                    $HoraE = explode(":", $rst["entrada"]);
                    $HoraS = explode(":", $Insert);
                    $DifE = $HoraE - date("H");
                    $DifS = $HoraS - date("H");
                }
            } elseif ($ResNt["horario"] == 4 && (date("w") - 1) == 4) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 4;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                }
            } elseif ($ResNt["horario"] == 5 && (date("w") - 1) == 5) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 5;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                }
            } elseif ($ResNt["horario"] == 6 && (date("w") - 1) == 6) {
                $Nota = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $EmpId["id"] . "' AND horario = 6;";
                if ($result = mysql_query($Nota)) {
                    $rst = mysql_fetch_array($result);
                }
                break;
            }
        }
    }
}

if (date("w") == 1) {
    $DiaLcd = 0;
} elseif (date("w") == 2) {
    $DiaLcd = 1;
} elseif (date("w") == 3) {
    $DiaLcd = 2;
} elseif (date("w") == 4) {
    $DiaLcd = 3;
} elseif (date("w") == 5) {
    $DiaLcd = 4;
} elseif (date("w") == 6) {
    $DiaLcd = 5;
} elseif (date("w") == 0) {
    $DiaLcd = 6;
}

if ($_REQUEST["ClaveUsuario"] <> "") {
    $BuscaUsr = "SELECT * FROM tarjetas WHERE clave =  " . $_REQUEST["ClaveUsuario"] . "";
    echo $BuscaUsr;
    if ($Bsq = mysql_query($BuscaUsr)) {
        $RsBsq = mysql_fetch_array($Bsq);
        $Insert = "INSERT INTO asistencia (empleado,fecha) VALUES ('" . $RsBsq["id_empleado"] . "','" . date("Y-m-d H:i:s") . "');";
        if (!mysql_query($Insert)) {
            echo mysql_error();
        } else {
            $Id = mysql_insert_id();
            $SELECT = "SELECT * FROM emp WHERE id = " . $RsBsq["id_empleado"];
            $horarios = "SELECT * FROM horariosTrabajo WHERE empleado = " . $RsBsq["id_empleado"] . " AND horario = " . $DiaLcd;

            if ($RsHr = mysql_query($horarios)) {
                $HrsRs = mysql_fetch_array($RsHr);
                $ent = date_create($HrsRs["entrada"]);
                $Salida = date_create($HrsRs["salida"]);
                $sal = date_create(date("H:m"));
                $differenceE = date_diff($ent, $sal);
                $differenceS = date_diff($Salida, $sal);
                echo "ENTRADA" . print_r($differenceE, true);
                echo "SALIDA " . print_r($differenceS, true);

                $EntSal = $differenceE->h > $differenceS->h ? "Salida" : "Entrada";
                $Upd = "UPDATE asistencia SET entrada = '$EntSal' WHERE id = " . $Id;
                if (mysql_query($Upd)) {
                    if ($RsSel = mysql_query($SELECT)) {
                        $Rsfin = mysql_fetch_array($RsSel);
                        $Msj = "Bienvenido " . $Rsfin["nombre"] . " <br> $EntSal: " . date("Y-m-d H:i:s");
                        header("Location: checkinout.php?Msj=$Msj");
                    }
                }
            }
        }
    }
}
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
                $("#ClaveUsuario").focus();
            });
        </script>
        <table class="PrincipalTable">    
            <tr>
                <td colspan="2" class="Title">..:: Asistencia ::..</td>
            </tr>
            <tr id="Info">
                <td>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table class="SubTablas">  
                            <tr><td>..:: Identificacion ::..</td></tr>
                            <tr>
                                <td>
                                    <div style="width: 100%;    padding: 15px;">
                                        <input type="password" name="ClaveUsuario" id="ClaveUsuario" style="font-size: 20px;font-family: sans-serif;"></input>
                                    </div>
                                </td>
                            </tr>
                        </table>  
                    </form>
                    <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table class="SubTablas">  
                            <tr><td>..:: Usuario ::..</td></tr>
                            <tr>
                                <td>
                                    <div style="width: 100%;    padding: 15px;">
                                        Usuario : <input type="text" name="Usuario"></input>
                                        Clave : <input type="password" name="Password"></input>
                                        <input type="submit" name="Boton" value="Registrar"></input>
                                    </div>
                                </td>
                            </tr>
                        </table>  
                    </form>
                </td>
                <td>
                    <table class="SubTablas">  
                        <tr><td>..:: Nota ::..</td></tr>
                        <tr><td align="left" style="align-content: center" colspan="2"><div ><h2><?= $Msj ?></h2></div></td></tr>
                        <tr>
                            <td>
                                <?php
                                $BuscaHorario = "SELECT * FROM horariosTrabajo WHERE empleado = '" . $_REQUEST["Usuario"] . "'";
//                                if ($result1 = mysql_query($BuscaHorario)) {
//                                    $Horario = mysql_fetch_array($result1);
//                                    
//                                }
                                ?>
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
