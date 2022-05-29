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
$msj = $_REQUEST["msj"];
$Msj = $_REQUEST["Msj"];

if ($_REQUEST["bt"] == "NUEVO") {

    $Birthday = $_REQUEST["Fechan"];

    // Obtenemos codigo postal al ingresar codigo
    $Sqlq = "SELECT * FROM estados_de_mexico WHERE d_codigo = " . $_REQUEST["Codigo"];
    $dt = mysql_fetch_array(mysql_query($Sqlq));

    $Nombrec = $_REQUEST["Apellidop"] . " " . $_REQUEST["Apellidom"] . " " . $_REQUEST["Nombre"];
    //$Nombrec = strtoupper($Nombrec);
    $sql = "INSERT INTO cli (nombrec,apellidop,apellidom,nombre,sexo,fechan,codigo,estado,municipio,refubicacion,observaciones) "
            . "VALUES ('$Nombrec','$_REQUEST[Apellidop]','$_REQUEST[Apellidom]','$_REQUEST[Nombre]',"
            . "'$_REQUEST[Sexo]','$Birthday','$_REQUEST[Codigo]','$dt[d_estado]','$dt[D_mnpio]','','');";

    if (mysql_query($sql)) {
        $Paciente = mysql_insert_id();
        $Msj = "Registro agregado con Exito id no. " . $Paciente . "&org=" . $_REQUEST["org"] . "&vta=" . $_REQUEST["vta"];
        AgregaBitacoraEventos($Gusr, '/Catalogos/Clientes/Info. Personal Agrega Nuevo registro no.' . $Paciente, "cli", $Fecha, $Paciente, $Msj, "clientese.php");
    }
    echo $sql . " .-" . mysql_error();
} elseif ($_REQUEST["bt"] === "Actualizar Inf") {
    $Nombrec = $_REQUEST[Apellidop] . " " . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];
    $Sqlq = "SELECT * FROM estados_de_mexico WHERE d_codigo = " . $_REQUEST["Codigo"];
    $dt = mysql_fetch_array(mysql_query($Sqlq));
    $Update = "UPDATE cli SET nombrec = '$Nombrec', apellidop = '$_REQUEST[Apellidop]', apellidom = '$_REQUEST[Apellidom]', "
            . "nombre = '$_REQUEST[Nombre]', sexo = '$_REQUEST[Sexo]', fechan = '$_REQUEST[Fechan]', codigo = '$_REQUEST[Codigo]', "
            . "estado = '$dt[d_estado]', municipio = '$dt[D_mnpio]' WHERE cliente = " . $busca;
    echo $Update;
    if (mysql_query($Update)) {
        $Msj = "Registro actualizado con Exito id no. " . $busca . "&org=" . $_REQUEST["org"] . "&vta=" . $_REQUEST["vta"];
        AgregaBitacoraEventos($Gusr, '/Catalogos/Clientes/Info. Personal', "cli", $Fecha, $busca, $Msj, "clientese.php");
    }
    echo mysql_error();
} elseif ($_REQUEST["bt"] == "Actualizar") {

    $Nombrec = $_REQUEST[Apellidop] . " " . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];
    //$Nombrec = strtoupper($Nombrec);
    $Location = $_REQUEST["Localidad"] === "" ? $_REQUEST["LocalidadSe"] : $_REQUEST["Localidad"];
    $sql = "UPDATE cli SET estado = '$_REQUEST[Estado]', municipio = '$_REQUEST[Municipio]', localidad = '$Location', "
            . "direccion = '$_REQUEST[Direccion]', telefono = '$_REQUEST[Telefono]', "
            . "celular = '$_REQUEST[Celular]', mail = '$_REQUEST[Mail]', refubicacion = '$_REQUEST[Refubicacion]', colonia = '$_REQUEST[Localidad]' "
            . "WHERE cliente = $busca;";
    //echo $sql;
    if (mysql_query($sql)) {
        $Msj = "Registro actualizado con Exito id no. " . $busca . "&org=" . $_REQUEST["org"] . "&vta=" . $_REQUEST["vta"];
        AgregaBitacoraEventos($Gusr, '/Catalogos/Clientes/Info. Personal Edita Contacto', "cli", $Fecha, $busca, $Msj, "clientese.php");
    }
    echo mysql_error();
}


if ($_REQUEST[Idext]) {
    header("Location: ordenescone.php?busca = $_REQUEST[Idext]");
}


#Variables comunes;
$cd = "SELECT * FROM cli WHERE cliente = $busca";
$CpoA = mysql_query($cd);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;

if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
    $Apellidop = $_REQUEST[Apellidop];
    $Apellidom = $_REQUEST[Apellidom];
    $Nombre = $_REQUEST[Nombre];
    $Sexo = $_REQUEST[Sexo];
    $Fechan = $_REQUEST[Fechan];
    $Localidad = $_REQUEST[Localidad];
    $Direccion = $_REQUEST[Direccion];
    $Codigo = $_REQUEST[Codigo];
    $Telefono = $_REQUEST[Telefono];
    $Celular = $_REQUEST[Celular];
    $Mail = $_REQUEST[Mail];
    $Refubicacion = $_REQUEST[Refubicacion];
    if ($busca == "NUEVO") {
        $busca = "NUEVO";
    } else {
        $busca = $busca;
    }
} else {
    $Estado = $Cpo[estado];
    $Apellidop = $Cpo[apellidop];
    $Apellidom = $Cpo[apellidom];
    $Nombre = $Cpo[nombre];
    $Sexo = $Cpo[sexo];
    if ($Sexo == 'M') {
        $Sexo = 'Masculino';
    } elseif ($Sexo == 'F') {
        $Sexo = 'Femenino';
    }
    $Fechan = $Cpo[fechan];
    $Localidad = $Cpo[localidad];
    $Direccion = $Cpo[direccion];
    $Codigo = $Cpo[codigo];
    $Telefono = $Cpo[telefono];
    $Celular = $Cpo[celular];
    $Mail = $Cpo[mail];
    $Refubicacion = $Cpo[refubicacion];
}



$Fechanac = $Cpo[fechan];
$Fecha = date("Y-m-d");
$array_nacimiento = explode("-", $Fechanac);
$array_actual = explode("-", $Fecha);
$anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias = $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0) {
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
    switch ($array_actual[1]) {
        case 1: $dias_mes_anterior = 31;
            break;
        case 2: $dias_mes_anterior = 31;
            break;
        case 3: $dias_mes_anterior = 28;
            break;
//                      if (bisiesto($array_actual[0])) 
//                      { 
//                          $dias_mes_anterior=29; break; 
//                      } else { 
//                          $dias_mes_anterior=28; break; 
//                      } 
        case 4: $dias_mes_anterior = 31;
            break;
        case 5: $dias_mes_anterior = 30;
            break;
        case 6: $dias_mes_anterior = 31;
            break;
        case 7: $dias_mes_anterior = 30;
            break;
        case 8: $dias_mes_anterior = 31;
            break;
        case 9: $dias_mes_anterior = 31;
            break;
        case 10: $dias_mes_anterior = 30;
            break;
        case 11: $dias_mes_anterior = 31;
            break;
        case 12: $dias_mes_anterior = 30;
            break;
    }

    $dias = $dias + $dias_mes_anterior;
}
//
//ajuste de posible negativo en $meses 
if ($meses < 0) {
    --$anos;
    $meses = $meses + 12;
}

if ($anos >= '110') {
    $Edad = 'Verificar Fecha de Nacimiento';
} elseif ($anos == '0' and $meses == '0') {
    $Edad = $dias . ' Dias';
} elseif ($anos == '0' and $meses >= '1') {
    $Edad = $meses . ' Meses';
} elseif ($anos >= '1') {
    $Edad = $anos . ' A&ntilde;os ';
}

if ($_REQUEST["Boton"] === "Verifica Correo") {
    require_once 'nusoap.php';
    require_once 'class.phpmailer.php';
    $SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 2 ORDER BY id");

    $Smtp = mysql_fetch_array($SmtpA);

    try {
        //*******************Envio de correo;
        //		Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        //Tell PHPMailer to use SMTP
        $mail->IsSMTP();
        //Enable SMTP debugging
        //0 = off (for production use)
        //1 = client messages
        //2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'error_log';

        //Set the hostname of the mail server
        $mail->Host = $Smtp[smtpname];

        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = $Smtp[smtpport];

        //Whether to use SMTP authentication
        //$mail->SMTPSecure = 'ssl';

        $mail->SMTPAuth = true;

        error_log("********************************************\n" . $Smtp[smtpuser] . "::" . $Smtp[smtploginpass] . "\n");
        //Username to use for SMTP authentication
        $mail->Username = $Smtp[smtpuser];

        //Password to use for SMTP authentication
        $mail->Password = $Smtp[smtploginpass];

        //Set the subject line
        $mail->Subject = "Verificar correo";


        $mail->Body = "Hola $Cpo[nombrec]<br>"
                . "Usted registró una cuenta en Diagnóstico Clínico Duran. <br>"
                . "Le estamos enviando por este medio la verificación y al mismo tiempo nos reiteramos"
                . " a sus ordenes para cualquier aclaracion al respecto, gracias por su preferencia.<br> "
                . "Saludos cordiales, Laboratorio Clinico Duran.";

        //Replace the plain text body with one created manually
        $mail->AltBody = 'Envio de verificación';

        //Set who the message is to be sent from
        //$mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');
        $mail->SetFrom('atencionaclientes@dulab.com.mx', 'Laboratorio Clinico Duran, factura electronica');

        //Set who the message is to be sent to
        $mail->AddAddress($Mail, 'Servicios');

        if ($Correoadm <> '') {
            $mail->AddAddress($Correoadm, 'Administracion');
        }


        /*         * *************************************
         */

        //Set the subject line
        $mail->Subject = "Envío de verificación de correo electronico";

        //Send the message, check for errors
        if (!$mail->Send()) {

            $Msj = "Mailer Error: " . $mail->ErrorInfo;
        } else {

            $Msj = "..::Sus mensaje sido enviados con exito::..";
            $Status = 1;
        }
    } catch (phpmailerException $e) {
        error_log($e->errorMessage());
        $Msj = $e->errorMessage();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $Msj = $e->getMessage();
    }
    header("Location: clientese.php?busca=$busca&Msj=$Msj");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <meta charset = "UTF-8"/>
        <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
        <title>Clientes - Info. Personal</title>
        <?php require ("./config_add.php");
        ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombrec])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c"><td class='letratitulo'align="center" colspan="2">..:: Detalle de paciente ::..</td></tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">Paciente : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombrec' value='<?= $Cpo[nombrec] ?>' size='35' disabled></input> Id : <?= $Cpo[cliente] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Apellido paterno : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Apellidop' value='<?= $Cpo["apellidop"] ?>' required></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Apellido materno : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Apellidom' value='<?= $Cpo["apellidom"] ?>' required></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Nombre : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombre' value='<?= $Cpo["nombre"] ?>' MAXLENGTH='30' required></input>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" class="Inpt">Genero : </td>
                                <td>
                                    <?php
                                    if (is_string($Cpo["sexo"])) {
                                        $Sexo = $Cpo["sexo"] === "M" ? "Masculino" : "Femenino";
                                    } else {
                                        $Sexo = "";
                                    }
                                    ?>
                                    <select name='Sexo' class="cinput">
                                        <option value="F">Femenino</option>
                                        <option value="M">Masculino</option>
                                        <option value='<?= $Cpo["sexo"] ?>' selected><?= $Sexo ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Fecha de Nacimiento : </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fechan' id="Fechan" value='<?= $Fechan ?>' required> </input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Edad : </td>
                                <td class="Inpt">
                                    <input type='number' style="width: 50px;" class='cinput'  name='Edad' id="Edad"></input>
                                    <input type="button" name="Calcular" id="Calcular" value="Calcula" class="letrap"></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Edad : </td>
                                <td class="Inpt">
                                    <?= $Edad ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Codigo Postal : </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 90px" class='cinput'  name='Codigo' value='<?= $Cpo["codigo"] ?>'></input>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <?php $Val = $busca === "NUEVO" ? "NUEVO" : "Actualizar Inf"; ?>
                                    <input class="letrap" type="submit" value='<?= $Val ?>' name='bt'></input>
                                    <input class="letrap" type="hidden" value='<?= $busca ?>' name='busca'></input>
                                    <input type="hidden" name="org" value="<?= $_REQUEST[org] ?>"></input>
                                    <input type="hidden" name="vta" value="<?= $_REQUEST[vta] ?>"></input>
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
                                    .:: Contacto ::.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Estado : </td>
                                <td class="Inpt">
                                    <select class='cinput' name='Estado'>
                                        <option value='Aguascalientes'>Aguascalientes</option>
                                        <option value='Baja California'>Baja California</option>
                                        <option value='Baja California Sur'>Baja California Sur</option>
                                        <option value='Campeche'>Campeche</option>
                                        <option value='Chiapas'>Chiapas</option>
                                        <option value='Chihuahua'>Chihuahua</option>
                                        <option value='Coahuila'>Coahuila</option>
                                        <option value='Colima'>Colima</option>
                                        <option value='Distrito Federal'>Distrito Federal</option>
                                        <option value='Durango'>Durango</option>
                                        <option value='Estado de Mexico'>Estado de Mexico</option>
                                        <option value='Guanajuato'>Guanajuato</option>
                                        <option value='Guerrero'>Guerrero</option>
                                        <option value='Hidalgo'>Hidalgo</option>
                                        <option value='Jalisco'>Jalisco</option>
                                        <option value='Michoacan'>Michoacan</option>
                                        <option value='Morelos'>Morelos</option>
                                        <option value='Nayarit'>Nayarit</option>
                                        <option value='Nuevo Leon'>Nuevo Leon</option>
                                        <option value='Oaxaca'>Oaxaca</option>
                                        <option value='Puebla'>Puebla</option>
                                        <option value='Queretaro'>Queretaro</option>
                                        <option value='Quintana Roo'>Quintana Roo</option>
                                        <option value='San Luis Potosi'>San Luis Potosi</option>
                                        <option value='Sinaloa'>Sinaloa</option>
                                        <option value='Sonora'>Sonora</option>
                                        <option value='Tabasco'>Tabasco</option>
                                        <option value='Tamaulipas'>Tamaulipas</option>
                                        <option value='Tlaxcala'>Tlaxcala</option>
                                        <option value='Veracruz'>Veracruz</option>
                                        <option value='Yucatán'>Yucatán</option>
                                        <option value='Zacatecas'>Zacatecas</option>
                                        <option selected value='<?= $Estado ?>'><?= $Estado ?></option>
                                    </select>
                                    <input class='Inpt' type='submit' name='est' id="EnviarEstado" value='Enviar Estado'></input>
                                    <?= $Cpo[estado] ?>
                                </td>
                            </tr>
                            <?php
                            if ($_REQUEST[Municipio] <> '') {
                                $Municipio = $_REQUEST[Municipio];
                            } else {
                                $Municipio = $Cpo[municipio];
                            }

                            $MpioA = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
                            ?>
                            <tr>
                                <td align="right" class="Inpt">Municipio : </td>
                                <td class="Inpt">
                                    <select class='cinput' name='Municipio'>
                                        <?php
                                        while ($Mpio = mysql_fetch_array($MpioA)) {
                                            echo "<option class='cinput' value='$Mpio[municipio]'>$Mpio[municipio]</option>";
                                        }
                                        echo "<option class='cinput' selected value='$Municipio'>$Municipio</option>";
                                        ?>
                                    </select>
                                    <input class='Inpt' type='submit' name='Mun' id="EnviarMunicipio" value='Enviar Municipio'></input>
                                    <?= $Cpo[municipio] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Colonia : </td>
                                <td class="Inpt">
                                    <select class='cinput locs'  name='LocalidadSe' id="Localidad">
                                        <?php
                                        $SColonia = "SELECT d_asenta FROM estados_de_mexico WHERE d_codigo = " . $Cpo["codigo"];
                                        $st = mysql_query($SColonia);
                                        while ($d = mysql_fetch_array($st)) {
                                            echo "<option value='$d[d_asenta]'>$d[d_asenta]</option>";
                                        }
                                        ?>
                                    </select>
                                    <input class="loct letrap" name='Localidad' id="LocalidadTexto" type="text"></input>
                                    <input class="letrap" type="button" id="Text" name="Text" value="Text"></input>
                                    <?= $Cpo[localidad] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Direccion : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput' style="width: 300px" name='Direccion' value='<?= $Direccion ?>'></input>
                                </td>
                            </tr>
                            <script src="js/jquery-1.8.2.min.js"></script>
                            <script src="jquery-ui/jquery-ui.min.js"></script>
                            <script type="text/javascript">
                        $(document).ready(function () {
                            $('#estado').autocomplete({
                                source: function (request, response) {
                                    $.ajax({
                                        url: "sqlaut.php",
                                        datoType: "json",
                                        data: {q: request.term},
                                        success: function (data) {
                                            response(data);
                                        }
                                    });
                                },
                                minLength: 2,
                                select: function (event, ui) {
                                    alert("Selecciono: " + ui.item.label);
                                }
                            });
                        });
                            </script>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Tel. Casa : </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 90px" class='cinput'  name='Telefono' value='<?= $Telefono ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Celular : </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 90px" class='cinput'  name='Celular' value='<?= $Celular ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Correo Electronico : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Mail' id='CorreoE' value='<?= $Mail ?>'></input>
                                    <?php
                                    if ($Mail !== "") {
                                        ?>
                                        <a href="clientese.php?busca=<?= $busca ?>&Boton=Verifica Correo&org=<?= $_REQUEST[org] ?>&vta=<?= $_REQUEST[vta] ?>"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></a> 
                                        Verificar de correo
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Referencia de Ubicacion : </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Refubicacion" type="text" rows="3" cols="45"><?= $Refubicacion ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    if ($busca == "NUEVO") {
                                        ?>

                                        <?php
                                    } else {
                                        ?>
                                        <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                        <input type="hidden" value="<?= $_REQUEST[org] ?>" name="org"></input>
                                        <input type="hidden" name="vta" value="<?= $_REQUEST[vta] ?>"></input>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                    TablaDeLogs("/Catalogos/Clientes/Info. Personal", $busca);
                    ?>

                </td>
                <td class="letrap" valign='top' width="22%">
                    <table id="Original">
                        <tr>
                            <td>
                                <?php
                                SbmenuCli();
                                ?>            
                            </td>
                        </tr>
                    </table>

                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                    <table id="ReturnOrden">
                        <tr>
                            <td>
                                <?php
                                if ($_REQUEST["org"] === "odnv") {
                                    ?>
                                    <a href="ordenesnvas.php?Venta=<?= $_REQUEST[vta] ?>&PacienteJ=<?= $busca ?>" class="content5" ><i class="fa fa-random fa-2x" aria-hidden="true"></i><br></br> Transferir a Ot's</a>
                                    <?php
                                }
                                ?>    
                            </td>
                        </tr>
                    </table>
                    <table><tr><td style="height: 100px;"></td></tr></table>
                    <table id="ReturnOrdenA">
                        <tr>
                            <td>
                                <a href="ordenesnvas.php?Venta=<?= $_REQUEST[vta] ?>" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i><br></br> Regresar a Ot's</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>      
        </table>  
    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            var busca = "<?= $busca ?>";
            $("#Localidad").val("<?= $Cpo[colonia] ?>");
            $("#CorreoE").change(function () {
                var a = document.querySelector("#CorreoE").value;
                console.log(a);
                var emailRegex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (emailRegex.test(a)) {
                    $(this).css('background-color', '');
                    $("#Sts").html('<i style="color:green" class="fa fa-check" aria-hidden="true"></i>');
                } else {
                    $(this).css('background-color', '#FF8B7F');
                    $("#Sts").html('<i style="color:red" class="fa fa-times" aria-hidden="true"></i>');
                }
            });
            $("#ReturnOrdenA").hide();
            if (busca === "NUEVO") {
                $("#EnviarEstado").hide();
                $("#EnviarMunicipio").hide();
                $("#ReturnOrden").hide();
            }
            if ("<?= $_REQUEST[org] ?>" == "odnv") {
                $("#Original").hide();
                $("#ReturnOrdenA").show();
            }
            $(".loct").hide();
            $("#LocalidadTexto").hide();
            $("#Text").click(function () {
                $("#LocalidadTexto").show();
                $(".locs").hide();
                $(".loct").show();
            });

            $("#Calcular").click(function () {
                const st = new Date();
                var a = st.getFullYear();
                var c = st.getMonth();
                var b = $("#Edad").val();
                if (c < 10 && c !== 0) {
                    c = 0 + "" + c;
                } else if (c == 0) {
                    c = 0 + "" + 1;
                }
                var year = a - b;
                var fechan = year + "-" + c + "-01";

                $("#Fechan").val(fechan);

            });
        });
    </script>
</html>
<?php
mysql_close();
?>
