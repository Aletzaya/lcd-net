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
setcookie("BuscaEmp", $busca);

if ($_REQUEST["ImageEmp"] === "Actualizar") {
    $Update = "UPDATE imageEmp SET alias = '" . $_REQUEST["AliasImage"] . "' WHERE id = " . $_REQUEST["IdArchivoImage"];
    if (mysql_query($Update)) {
        $Msj = "Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Actualiza Alias A imagen', "imageEmp", $Fecha, $busca, $Msj, "empleadosimage.php");
    }
} elseif (is_numeric($_REQUEST["idDelete"])) {
    $Delete = "DELETE FROM imageEmp WHERE id = " . $_REQUEST["idDelete"];
    if (mysql_query($Delete)) {
        $Msj = "Registro eliminado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Elimina imagen', "imageEmp", $Fecha, $busca, $Msj, "empleadosimage.php");
    }
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
                        <tr><td>..:: Subir archivo ::..</td></tr>
                        <tr>
                            <td>
                                <div style="padding: 15px;">
                                    <form class="dropzone" id="mydrop" action="subirimageEmp.php">
                                        <div class="fallback">
                                            <input type="file" name="file" multiple id="file"></input>
                                        </div>
                                    </form>
                                </div>
                                <script type="text/javascript">
                                    var dropzone = new Dropzone("#file", {
                                        url: 'subirimageEmp.php'
                                    });
                                </script>
                            </td>
                        </tr>
                    </table>  
                </td>
                <td>
                    <table class="SubTablas">  
                        <tr><td>..:: Archivos ::..</td></tr>
                        <tr>
                            <td>
                                <?php
                                $SqlImage = "SELECT * FROM imageEmp WHERE id_empleado = " . $busca;
                                $i = 0;
                                if ($ImageR = mysql_query($SqlImage)) {
                                    while ($ImgRs = mysql_fetch_array($ImageR)) {
                                        ?>
                                        <form name = 'form<?= $i ?>' method = 'get' action = "<?= $_SERVER['PHP_SELF'] ?>" onSubmit = 'return ValidaCampos();'>
                                            <a href="empleadosimage.php?busca=<?= $busca ?>&idDelete=<?= $ImgRs[id] ?>">
                                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                                            </a>
                                            <?= $ImgRs["nombreArchivo"] ?> <strong>Alias: </strong><?= $ImgRs["alias"] ?>
                                            <strong>Nvo. Alias: </strong><input type="text" name="AliasImage"></input>
                                            <input name="IdArchivoImage" value="<?= $ImgRs[id] ?>" type="hidden"></input>
                                            <input name="busca" value="<?= $busca ?>" type="hidden"></input>
                                            <input type="submit" name="ImageEmp" value="Actualizar"></input>
                                        </form>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>

                            </td>
                        </tr>
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
