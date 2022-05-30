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

if ($_REQUEST["Boton"] === "Nuevo") {
    $Sql = "INSERT INTO emp (nombre,direccion,colonia,municipio,entidadf,codigop,telefono,telefonocel,correoe,"
            . "sexo,estadociv,fechanac,curp,rfc) "
            . "VALUES ('" . $_REQUEST["Nombre"] . "','" . $_REQUEST["Direccion"] . "','" . $_REQUEST["Colonia"] . "',"
            . "'" . $_REQUEST["Municipio"] . "','" . $_REQUEST["Entidadf"] . "','" . $_REQUEST["Codigop"] . "',"
            . "'" . $_REQUEST["Telefono"] . "','" . $_REQUEST["Telefonocel"] . "','" . $_REQUEST["Correoe"] . "',"
            . "'" . $_REQUEST["Sexo"] . "','" . $_REQUEST["Estadociv"] . "','" . $_REQUEST["Fechanac"] . "','" . $_REQUEST["Curp"] . "','" . $_REQUEST["Rfc"] . "');";

    if (mysql_query($Sql)) {
        $Nvo = mysql_insert_id();
        $Msj = "Registro agregado con Exito id no. " . $Nvo;
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Crea registro', "emp", $Fecha, $Nvo, $Msj, "empleadose.php");
    }

    echo "Error del SQL: " . $Sql . " Error " . mysql_error();
} else if ($_REQUEST["Boton"] === "Actualiza Detalle") {
    $Sql = "UPDATE emp SET "
            . "horario = " . $_REQUEST["Horario"] . ", "
            . "inst = " . $_REQUEST["Inst"] . ", fechai = '" . $_REQUEST["Fechai"] . "',"
            . "imss = '" . $_REQUEST["Imss"] . "',horasext = " . $_REQUEST["Horasext"] . ","
            . "asistencia = '" . $_REQUEST["Asistencia"] . "',puntualidad = '" . $_REQUEST["Puntualidad"] . "',"
            . "otrosegr = " . $_REQUEST["Otrosegr"] . ",cobertura = " . $_REQUEST["Cobertura"] . ",primavac = " . $_REQUEST["Primavac"] . ","
            . "diastrab = " . $_REQUEST["Diastrab"] . ", festivos = " . $_REQUEST["Festivos"] . ",faltas = " . $_REQUEST["Faltas"] . ","
            . "retardos = " . $_REQUEST["Retardos"] . ",status='" . $_REQUEST["Status"] . "' "
            . "WHERE id = " . $busca;
    if (mysql_query($Sql)) {
        $Msj = "!Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Principal Edita Detalle', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }

    echo "Error del SQL: " . $Sql . " Error " . mysql_error();
} else if ($_REQUEST["Boton"] === "Actualiza Pago") {
    $Sql = "UPDATE emp SET"
            . " sueldod='" . $_REQUEST["Sueldod"] . "', numseguro='" . $_REQUEST["Numseguro"] . "',"
            . "nomina='" . $_REQUEST["Nomina"] . "',altaimss ='" . $_REQUEST["Altaimss"] . "',salarioimss = '" . $_REQUEST["Salarioimss"] . "' "
            . "WHERE id = " . $busca;
    if (mysql_query($Sql)) {
        $Msj = "!Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Principal Edita Pago', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }

    echo "Error del SQL: " . $Sql . " Error " . mysql_error();
} else if ($_REQUEST["Boton"] === "Actualizar") {
    $Sql = "UPDATE emp SET nombre = '" . $_REQUEST["Nombre"] . "',rfc = '" . $_REQUEST["Rfc"] . "', direccion = '" . $_REQUEST["Direccion"] . "',"
            . " colonia = '" . $_REQUEST["Colonia"] . "', municipio = '" . $_REQUEST["Municipio"] . "', telefono = '" . $_REQUEST["Telefono"] . " "
            . "',correoe = '" . $_REQUEST["Correoe"] . "',fechanac = '" . $_REQUEST["Fechanac"] . "', telefonocel = '" . $_REQUEST["Telefonocel"] . "',"
            . "codigop='" . $_REQUEST["Codigop"] . "',entidadf='" . $_REQUEST["Entidadf"] . "',estadociv = '" . $_REQUEST["Estadociv"] . "',"
            . "sexo = '" . $_REQUEST["Sexo"] . "',curp = '" . $_REQUEST["Curp"] . "' "
            . "WHERE id = " . $busca;
    if (mysql_query($Sql)) {
        $Msj = "!Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Principal Edita Detalle Empleado', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["Boton"] === "Actualiza Datos Familiares") {
    $Sql = "UPDATE emp SET nohijos = '" . $_REQUEST["Nohijos"] . "', nohombres = '" . $_REQUEST["Nohombres"] . "', nomujeres = '" . $_REQUEST["Nomujeres"] . "',"
            . "hijo1='" . $_REQUEST["Hijo1"] . "', hijo2='" . $_REQUEST["Hijo2"] . "', hijo3='" . $_REQUEST["Hijo3"] . "',"
            . "hijo4='" . $_REQUEST["Hijo4"] . "', hijo5='" . $_REQUEST["Hijo5"] . "',conyuge = '" . $_REQUEST["Conyuge"] . "',"
            . "ocupacion1 = '" . $_REQUEST["Ocupacion1"] . "',ocupacion2 = '" . $_REQUEST["Ocupacion2"] . "',ocupacion3 = '" . $_REQUEST["Ocupacion3"] . "',"
            . "ocupacion4 = '" . $_REQUEST["Ocupacion4"] . "',ocupacion5 = '" . $_REQUEST["Ocupacion5"] . "',fechanconyuge ='" . $_REQUEST["Fechanconyuge"] . "' "
            . "WHERE id = " . $busca;
    //echo $Sql;
    if (mysql_query($Sql)) {
        $Msj = "!Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Principal Edita Detalle Datos Familiares', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
    echo $Sql . mysql_error();
} else if ($_REQUEST["Boton"] === "Actualiza Curriculum") {
    $Sql = "UPDATE emp SET "
            . "observaciones = '" . $_REQUEST["Observaciones"] . "',gradoestudios = '" . $_REQUEST["GradoEstudios"] . "',"
            . "titulo='" . $_REQUEST["Titulo"] . "',estudia = '" . $_REQUEST["Estudia"] . "',cedulap='" . $_REQUEST["Cedulap"] . "' "
            . "WHERE id = " . $busca;
    if (mysql_query($Sql)) {
        $Msj = "!Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Principal Edita Detalle Datos Curriculares', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["Boton"] === "Actualiza Laboral") {
    $Sql = "UPDATE emp SET "
            . "fechai = '" . $_REQUEST["Fechai"] . "',cia = '" . $_REQUEST["Cia"] . "',empresa = '" . $_REQUEST["Empresa"] . "',"
            . "area='" . $_REQUEST["Area"] . "',departamento='" . $_REQUEST["Departamento"] . "',cargo='" . $_REQUEST["Cargo"] . "',"
            . "diaslaborales='" . $_REQUEST["Diaslaborales"] . "',turno ='" . $_REQUEST["Turno"] . "', empresa='" . $_REQUEST["Empresa"] . "' "
            . "WHERE id = " . $busca;
    echo $Sql;
    if (mysql_query($Sql)) {
        $Msj = "!Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Informacion Principal Edita Detalle Datos Laborales', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["Boton"] === "Sancion") {
    $sql = "INSERT INTO sanciones (id_emp,fecha,observacion) VALUES ('$_REQUEST[Empleado]',now(),'$_REQUEST[Sancion]');";
    if (mysql_query($sql)) {
        $Msj = "!Registro ingresado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Sancion agrega un nuevo registro', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["FechasImportantes"] === "Agregar") {
    $sql = "INSERT INTO fechas (fecha, observaciones,empleado) "
            . "VALUES ('" . $_REQUEST[Fecha] . "','" . $_REQUEST[Detalle] . "', '" . $busca . "');";
    if (mysql_query($sql)) {
        $Msj = "!Registro ingresado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Fecha Importante agrega un nuevo registro', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["DependientesEco"] === "Agregar") {
    $sql = "INSERT INTO dep_economico (dep_economico,nombrec,id_empleado) "
            . "VALUES ('" . $_REQUEST["Parentesco"] . "', '" . $_REQUEST["Nombre"] . "',$busca);";
    if (mysql_query($sql)) {
        $Msj = "!Registro ingresado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Agrega dependiente economico', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["op"] === "EliminaFR") {
    $Sql = "DELETE FROM dep_economico WHERE id =" . $_REQUEST["cIdNvo"];
    if (mysql_query($Sql)) {
        $Msj = "!Registro eliminado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Elimina Dependiente economico', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["op"] === "EliminaFechaR") {
    $Sql = "DELETE FROM fechas WHERE id =" . $_REQUEST["cIdNvo"];
    if (mysql_query($Sql)) {
        $Msj = "!Registro eliminado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Elimina Fechas relacionadas', "emp", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["Boton"] === "UpdateSancion") {
    $Update = "UPDATE sanciones SET observacion ='" . $_REQUEST["Sancion"] . "' WHERE id_emp='" . $_REQUEST["Empleado"] . "' "
            . "AND fecha = '" . $_REQUEST["fecha"] . "'";
    if (mysql_query($Update)) {
        $Msj = "!Registro editado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Edita sancion', "sanciones", $Fecha, $busca, $Msj, "empleadose.php");
    }
} else if ($_REQUEST["op"] === "EditaDepEco" && $_REQUEST["Upd"] === "Si") {
    $Update = "UPDATE dep_economico SET dep_economico='" . $_REQUEST["Parentesco"] . "',nombrec='" . $_REQUEST["Nombre"] . "' "
            . "WHERE id = " . $_REQUEST["cIdnv"] . "";
    echo $Update;
    if (mysql_query($Update)) {
        $Msj = "!Registro editado con exito!";
        AgregaBitacoraEventos($Gusr, '/R.Humanos/Empleados/Edita DepEconomico', "dep_economico", $Fecha, $busca, $Msj, "empleadose.php");
    }
} elseif ($_REQUEST["op"] === "DescargaTitulo") {
    $Titulo = "SELECT * FROM imageEmp WHERE alias='titulo'";
    if ($TituloS = mysql_query($Titulo)) {
        $TitRst = mysql_fetch_array($TituloS);
        header("Content-disposition: attachment; filename=" . $TitRst["alias"]);
        header("Content-type: MIME");
        readfile("imageEmp/" . $TitRst["nombreArchivo"]);
    }
} elseif ($_REQUEST["op"] === "DescargaCedula") {
    $Titulo = "SELECT * FROM imageEmp WHERE alias='cedula'";
    if ($TituloS = mysql_query($Titulo)) {
        $TitRst = mysql_fetch_array($TituloS);
        header("Content-disposition: attachment; filename=" . $TitRst["alias"]);
        header("Content-type: MIME");
        readfile("imageEmp/" . $TitRst["nombreArchivo"]);
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
        <?php require ("./config_add.php");
        ?>
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            $(document).ready(function () {
                var busca = "<?= $busca ?>";
                if (busca == "NUEVO") {
                    $("#tabla1").hide();
                    $("#tabla2").hide();
                    $("#logs").hide();
                    $("#Familiar").hide();
                    $("#Curricular").hide();
                    $("#Laboral").hide();
                    $("#Sanciones").hide();
                }
                $("#Turno").val("<?= $Cpo["turno"] ?>");
                $("#Diaslaborales").val("<?= $Cpo["diaslaborales"] ?>");
                $("#Area").val("<?= $Cpo["area"] ?>");
                $("#Sexo").val("<?= $Cpo["sexo"] ?>");
                $("#Estadociv").val("<?= $Cpo["estadociv"] ?>");
                $("#Cia").val("<?= $Cpo[cia] ?>");
                $("#Cia1").val("<?= $Cpo[cia] ?>");
                $("#Empresa").val("<?= $Cpo[empresa] ?>");
                $("#Status").val("<?= $Cpo[status] ?>");
                $("#Nomina").val("<?= $Cpo[nomina] ?>");
                $("#GradoEstudios").val("<?= $Cpo[gradoestudios] ?>");
                $("#Titulo").val("<?= $Cpo[titulo] ?>");
                $("#Estudia").val("<?= $Cpo[estudia] ?>");
                $("#Departamento").val("<?= $Cpo[departamento] ?>");
                $("#Empresa1").val("<?= $Cpo["empresa"] ?>");
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
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style = "background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="4">
                                    ..:: Detalle de Empleado ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td colspan="1" width='23%' align="right" class="Inpt">Nombre Completo :</td>
                                <td colspan="3" class="Inpt"><input type='text' class='cinput' style="width:380px;" name='Nombre' value='<?= $Cpo[nombre] ?>'></input></td>
                            </tr>
                            <tr style = "height: 30px">
                                <td colspan="1" align="right" class="Inpt">Direccion :</td>
                                <td colspan="3" class="Inpt"><input type='text' class='cinput' style="width:380px;" name='Direccion' value='<?= $Cpo[direccion] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Colonia :</td>
                                <td class="Inpt"><input type='text' class='cinput' name='Colonia' value='<?= $Cpo[colonia] ?>'></input></td>
                                <td align = "right" class = "Inpt">Municipio :</td>
                                <td class = "Inpt"><input type='text' class='cinput' name='Municipio' value='<?= $Cpo[municipio] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Entidad Federativa :</td>
                                <td class="Inpt"><input type='text' class='cinput' name='Entidadf' value='<?= $Cpo[entidadf] ?>'></input></td>
                                <td align = "right" class = "Inpt">Código Postal :</td>
                                <td class = "Inpt"><input type='text' class='cinput' name='Codigop' value='<?= $Cpo[codigop] ?>'></input></td>
                            </tr>
                            <tr style = "height: 30px">
                                <td align="right" class="Inpt">Telefono :</td>
                                <td class="Inpt"><input type='text' class='cinput' name='Telefono' value='<?= $Cpo[telefono] ?>'></input></td>
                                <td align = "right" class = "Inpt">Telefono Celular :</td>
                                <td class = "Inpt"><input type='text' class='cinput' name='Telefonocel' value='<?= $Cpo[telefonocel] ?>'></input></td>
                            </tr>
                            <tr style = "height: 30px">
                                <td colspan="1" align = "right" class = "Inpt">Correo Electronico :</td>
                                <td colspan="3" class = "Inpt"><input type='text' class='cinput' style="width:380px;" name='Correoe' value='<?= $Cpo[correoe] ?>'></input></td>
                            </tr>
                            <tr style = "height: 30px">
                                <td align="right" class="Inpt">Sexo:</td>
                                <td>
                                    <select id="Sexo" name="Sexo" class="Inpt">
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </td>
                                <td align="right" class="Inpt"> Estado Civil:</td>
                                <td>
                                    <select id="Estadociv" name='Estadociv' class="Inpt">
                                        <option value="S">Soltero</option>
                                        <option value="C">Casado</option>
                                        <option value="V">Viudo</option>
                                        <option value="D/S">Divorsiado</option>
                                        <option value="U/L">Unión Libre</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style = "height: 30px">
                                <td align="right" class="Inpt">Fecha de Nacimiento :</td>
                                <td class="Inpt"><input type='text' placeholder="2021-02-05" class='cinput' name='Fechanac' value='<?= $Cpo[fechanac] ?>'></input></td>
                                <td class = "Inpt" align="center" colspan="2">Edad: <?php CalculaEdad($Cpo["fechanac"]) ?></td>
                            </tr>
                            <tr style = "height: 30px">
                                <td align="right" class="Inpt">Curp :</td>
                                <td class="Inpt"><input type='text' class='cinput' name='Curp' value='<?= $Cpo[curp] ?>'></input></td>
                                <td align = "right" class = "Inpt">RFC :</td>
                                <td class = "Inpt"><input type='text' class='cinput' name='Rfc' value='<?= $Cpo[rfc] ?>' ></input></td>
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
                    <form name='form3' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' id="tabla1" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    .:: Detalle Pago ::.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Sueldo Diario :</td>
                                <td colspan="3"><input type='text' style="width: 90px" class='cinput'  name='Sueldod' value='<?= $Cpo[sueldod] ?>'></input></td>
                            </tr>
                            <tr class="letrap" style="height: 30px">
                                <td align="center" class="Inpt">Percepción.- </td>
                                <td>Semanal: <?= $Cpo[sueldod] * 7 ?> </td>
                                <td> Quincenal: <?= ($Cpo[sueldod] * 365) / 24 ?>  </td>
                                <td> Mensual: <?= ($Cpo[sueldod] * 365) / 12 ?> aprox.</td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Nomina : </td>
                                <td>
                                    <select name="Nomina" class="letrap" id="Nomina">
                                        <option value="Mensual">Mensual</option>
                                        <option value="Quincena">Quincena</option>
                                        <option value="Semana">Semana</option>
                                    </select>
                                </td>
                                <td align="right" class="Inpt">No Seguro Social :</td>
                                <td><input type='text' class='cinput'  name='Numseguro' id="Numseguro" value='<?= $Cpo[numseguro] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Salario IMSS :</td>
                                <td><input type='datetime' class='cinput'  name='Salarioimss' id="Salarioimss" value='<?= $Cpo[salarioimss] ?>'></input></td>
                                <td align="right" class="Inpt">Alta IMSS :</td>
                                <td><input type='datetime' class='cinput'  name='Altaimss' id="Altaimss" value='<?= $Cpo[altaimss] ?>'></input></td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="4">
                                    <input class="letrap" type="submit" value='Actualiza Pago' name='Boton'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div style="padding-bottom: 15px;">
                        <table width='99%' align='center' id="logs" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Fechas Relacionadas ::.
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <table align="center" width="95%" style="margin: 7px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td><strong>&nbsp; Fecha</strong></td>
                                            <td><strong>&nbsp; Detalle</strong></td>
                                            <td><strong>Eliminar</strong></td>
                                        </tr>
                                        <?php
                                        $sql = "SELECT id,fecha,observaciones FROM `fechas` WHERE empleado = '$busca';";
                                        $PgsA = mysql_query($sql);
                                        while ($rg = mysql_fetch_array($PgsA)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td width="20%" align="left">&nbsp;<?= $rg[fecha] ?></td>
                                                <td><?= $rg[observaciones] ?></td>
                                                <td>
                                                    <a href="empleadose.php?busca=<?= $busca ?>&op=EliminaFechaR&cIdNvo=<?= $rg["id"] ?>"><i class="fa fa-trash fa-lg" style="color:#E74C3C" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $nRng++;
                                        }
                                        ?>
                                    </table>
                                    <form name='FechasImportantes' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                        <div style="padding-left: 10px;" class="letrap">
                                            Fecha : <input type="date" name="Fecha" class="letrap"></input>
                                            Detalle : <textarea cols="40" name="Detalle" class="letrap"></textarea>
                                            <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                        </div>
                                        <div style="padding-left: 40%;padding-top: 5px;">
                                            <input style="width: 20%;" type="submit" name="FechasImportantes" value="Agregar" class="letrap"></input>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="padding-bottom: 15px;">
                        <table width='99%' align='center' id="logs" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Dependientes Económicos ::.
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <table align="center" width="95%" style="margin: 7px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td align="center" style="width: 25%;"><b>&nbsp; Parentesco</b></td>
                                            <td align="center"><b>&nbsp; Nombre</b></td>
                                            <td align="center"> Eliminar</td>
                                            <td align="center"> Edit</td>
                                        </tr>
                                        <?php
                                        $sql = "SELECT id,dep_economico de,nombrec FROM `dep_economico` WHERE id_empleado = '$busca';";
                                        $PgsA = mysql_query($sql);
                                        while ($rg = mysql_fetch_array($PgsA)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td width="20%" align="left">&nbsp;<?= $rg["de"] ?></td>
                                                <td><?= $rg["nombrec"] ?></td>
                                                <td align="center">
                                                    <a href="empleadose.php?busca=<?= $busca ?>&op=EliminaFR&cIdNvo=<?= $rg["id"] ?>"><i class="fa fa-trash fa-lg" style="color:#E74C3C" aria-hidden="true"></i></a>
                                                </td>
                                                <td align="center">
                                                    <a href="empleadose.php?busca=<?= $busca ?>&op=EditaDepEco&cIdNv=<?= $rg["id"] ?>&Parentesco=<?= $rg["de"] ?>&Nombre=<?= $rg["nombrec"] ?>">
                                                        <i class="fa fa-pencil fa-lg" style="color:#E74C3C" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $nRng++;
                                        }
                                        ?>
                                    </table>
                                    <form name='Parentesco' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                        <?php
                                        if ($_REQUEST["op"] === "EditaDepEco") {
                                            ?>
                                            <div style="padding-left: 10px;" class="letrap">
                                                Parentesco : 
                                                <select name="Parentesco" class="letrap">
                                                    <option value="<?= $_REQUEST["Parentesco"] ?>" selected><?= $_REQUEST["Parentesco"] ?></option>
                                                    <option value="Papa">Papá</option>
                                                    <option value="Mama">Mamá</option>
                                                    <option value="Hijo">Hij@</option>
                                                    <option value="Ti@">Ti@</option>
                                                    <option value="Sobrino">Sorin@</option>
                                                    <option value="Esposo">Espos@</option>
                                                    <option value="Hermano">Herman@</option>
                                                    <option value="Otro">Otro</option>
                                                </select>       
                                                Nombre : 
                                                <input style="width: 250px;" type="text" class="letrap" name="Nombre" value="<?= $_REQUEST["Nombre"] ?>"></input>
                                                <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                            </div>
                                            <div style="padding-left: 40%;padding-top: 5px;">
                                                <input style="width: 20%;" type="submit" name="DependientesEco" value="Editar" class="letrap"></input>
                                                <input type="hidden" name="cIdnv" value="<?= $_REQUEST["cIdNv"] ?>"></input>
                                                <input type="hidden" name="op" value="<?= $_REQUEST["op"] ?>"></input>
                                                <input type="hidden" name="Upd" value="Si"></input>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div style="padding-left: 10px;" class="letrap">

                                                Parentesco : 
                                                <select name="Parentesco" class="letrap">
                                                    <option value="Papa">Papá</option>
                                                    <option value="Mama">Mamá</option>
                                                    <option value="Hijo">Hij@</option>
                                                    <option value="Ti@">Ti@</option>
                                                    <option value="Sobrino">Sorin@</option>
                                                    <option value="Esposo">Espos@</option>
                                                    <option value="Hermano">Herman@</option>
                                                    <option value="Otro">Otro</option>
                                                </select>       
                                                Nombre : 
                                                <input style="width: 250px;" type="text" class="letrap" name="Nombre"></input>
                                                <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                            </div>
                                            <div style="padding-left: 40%;padding-top: 5px;">
                                                <input style="width: 20%;" type="submit" name="DependientesEco" value="Agregar" class="letrap"></input>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <form name='Sanciones' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table id="Sanciones" class="letrap" width='99%' align='center' id="tabla1" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #FF1818;">
                                <td class='letratitulo'align="center" colspan="5">
                                    ..:: Eventos y/o Sucesos ::..
                                </td>
                            </tr>
                            <?php
                            $t = "SELECT * FROM sanciones WHERE id_emp = " . $Cpo["id"];
                            $sql = mysql_query($t);
                            while ($rg = mysql_fetch_array($sql)) {
                                ?>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt" colspan="1">Evento : </td>
                                    <td><?= $rg["fecha"] ?></td>
                                    <td colspan="2">
                                        <?= $rg["observacion"] ?>
                                    </td>
                                    <td>
                                        <a href="empleadose.php?busca=<?= $busca ?>&Op=UpEv&Emp=<?= $Cpo["id"] ?>&Fecha=<?= $rg["fecha"] ?>&Obs=<?= $rg["observacion"] ?>">
                                            <i class="fa fa-pencil fa-lg" style="color:#E74C3C" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            if ($_REQUEST["Op"] === "UpEv") {
                                ?>
                                <tr>
                                    <td colspan="5" style="height: 50px;">
                                        Evento : <textarea name="Sancion"  style="width: 60%" rows="2" class="letrap"><?= $_REQUEST["Obs"] ?></textarea>
                                        <input type="submit" name="Boton" value="UpdateSancion" class="letrap"></input>
                                        <input type="hidden" name="Empleado" value="<?= $Cpo["id"] ?>"></input>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                        <input type="hidden" value="<?= $_REQUEST["Fecha"] ?>" name="fecha"></input>
                                    </td>
                                </tr>
                                <?php
                            } else {
                                ?>
                                <tr style="height: 30px">
                                    <td colspan="5" style="height: 50px;">
                                        Evento: <textarea name="Sancion" style="width: 90%" rows="2" class="letrap"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="5">
                                        <input type="submit" name="Boton" value="Sancion" class="letrap"></input>
                                        <input type="hidden" name="Empleado" value="<?= $Cpo["id"] ?>"></input>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </form>
                </td>
                <td valign='top' width='45%'>
                    <form name='DetalleLaboral' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table id="Laboral" class="letrap" width='99%' align='center' id="tabla1" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    .:: Detalle Laboral ::.
                                </td>
                            </tr>
                            <tr  style="height: 30px">
                                <td align="right" class="Inpt">Fecha de ingreso :</td>
                                <td><input type='datetime' class='cinput'  name='Fechai' id="Fechai" value='<?= $Cpo[fechai] ?>'></input></td>
                                <td align="right" class="Inpt">Antiguedad :</td>
                                <td class="Inpt">
                                    <?php
                                    $fecha_nacimiento = new DateTime($Cpo[fechai]);
                                    $hoy = new DateTime();
                                    $edad = $hoy->diff($fecha_nacimiento);
                                    echo $edad->y . " Años con " . $edad->m . " Meses y " . $edad->d . " Dias";
                                    ?> 
                                </td>
                            </tr>
                            <tr style="height: 30px;">
                                <td align="right" class="Inpt">Empresa :</td>
                                <td colspan="3" class="Inpt">
                                    <select id="Empresa1" name="Empresa" class="letrap" style="width: 80%;">
                                        <option value="LCD">Laboratorio Clinico Duran</option>
                                        <option value="MOVICARE">MOVICARE</option>
                                        <option value="DCD">Diagnóstico Clinico Duran</option>
                                        <option value="OHF">Operadora Hospital Futura</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px;">
                                <td align = "right" class = "Inpt">Cia :</td>
                                <td class = "Inpt">
                                    <select id="Cia1" name="Cia" class="letrap">
                                        <?php
                                        $sqlQ = mysql_query("SELECT id,alias FROM cia;");
                                        while ($tt = mysql_fetch_array($sqlQ)) {
                                            ?>
                                            <option value="<?= $tt["id"] ?>"><?= $tt["alias"] ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </td>
                                <td align = "right" class = "Inpt">Area :</td>
                                <td class = "Inpt">
                                    <select id="Area" name="Area" class="letrap">
                                        <option value="Administracion">Administración</option>
                                        <option value="Atencion a clientes">Atencion a clientes</option>
                                        <option value="Area operativa">Area Operativa</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Departamento :</td>
                                <td>
                                    <select class="letrap" id="Departamento" name='Departamento'>
                                        <?php
                                        $DepA = mysql_query("SELECT id,nombre FROM depn ORDER BY id");
                                        while ($reg = mysql_fetch_array($DepA)) {
                                            echo "<option value='$reg[0]'>$reg[0].-$reg[1]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="right" class="Inpt">Cargo o puesto :</td>
                                <td><input type='text' class='cinput'  name='Cargo' id="Cargo" value='<?= $Cpo[cargo] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Dias Laborales:</td>
                                <td>
                                    <select id="Diaslaborales" name="Diaslaborales" class="letrap">
                                        <option value="lav">Lunes - Viernes</option>
                                        <option value="las">Lunes - Sábado</option>
                                        <option value="lad">Lunes - Domingo</option>
                                        <option value="fds">Rol fin de semana</option>
                                    </select>
                                </td>
                                <td align="right" class="Inpt">Turno:</td>
                                <td>
                                    <select id="Turno" name="Turno" class="letrap">
                                        <option value="Matutino">Matutino</option>
                                        <option value="Vespertino">Vespertino</option>
                                        <option value="Nocturno">Nocturno</option>
                                        <option value="Mixto">Mixto</option>
                                        <option value="Fin de semana">Fin de semana</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="4">
                                    <input class="letrap" type="submit" value='Actualiza Laboral' name='Boton'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <form name='Curricular' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table id="Curricular" class="letrap" width='99%' align='center' id="tabla1" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    .:: Detalle Curricular ::.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Grado estudios :</td>
                                <td>
                                    <select id="GradoEstudios" name="GradoEstudios" class="Inpt">
                                        <option value="Primaria">Primaria</option>
                                        <option value="Secundaria">Secundaria</option>
                                        <option value="Preparatoria">Prepa o Bachillerato</option>
                                        <option value="Carrera Tecnica">Carrera Tecnica</option>
                                        <option value="Licenciatura Trunca">Licencitura Trunca</option>
                                        <option value="Licenciatura Concluida">Licenciatura Concluida</option>
                                        <option value="Especialidad">Especialidad</option>
                                        <option value="Diplomado">Diplomado</option>
                                        <option value="Maestria">Maestria</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </td>
                                <td align="right" class="Inpt"><a class="edit" href="empleadose.php?busca=<?= $busca ?>&op=DescargaTitulo">Titulo</a> :</td>
                                <td>
                                    <select id="Titulo" name="Titulo" class="Inpt">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Estudia :</td>
                                <td>
                                    <select id="Estudia" name="Estudia" class="Inpt">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                                <td align="right" class="Inpt qCedula">
                                    <a class="edit" href="empleadose.php?busca=<?= $busca ?>&op=DescargaCedula">Cedula</a> :
                                </td>
                                <td class="qCedula">
                                    <select id="dCed" name="dCed" class="Inpt">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                                <td align="right" class="Inpt dCedula">Cedula:</td>
                                <td class="dCedula">
                                    <input type='text' class='cinput' style="width:120px;" id="Cedulap" name='Cedulap' value='<?= $Cpo[cedulap] ?>'></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td colspan="1" align = "right" class = "Inpt">Observaciones :</td>
                                <td colspan="3" class = "Inpt">
                                    <input type='text' class='cinput' style="width:380px;" name='Observaciones' value='<?= $Cpo[observaciones] ?>'></input>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="4">
                                    <input class="letrap" type="submit" value='Actualiza Curriculum' name='Boton'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <form name='form6' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table id="Familiar" class="letrap" width='99%' align='center' id="tabla1" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    .:: Detalle Familiar ::.
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right">Nombre de Cónyuge :</td>
                                <td colspan="3"><input type='text' class='cinput' style="width:340px;" name='Conyuge' id="Conyuge" value='<?= $Cpo[conyuge] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right">Edad Cónyuge :</td>
                                <td><input type='text' class='cinput'  name='Fechanconyuge' id="Fechanconyuge" value='<?= $Cpo[fechanconyuge] ?>'></input></td>
                                <td align="right" colspan="2"><?php CalculaEdad($Cpo["fechanconyuge"]) ?></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right">No. de Hijos :</td>
                                <td colspan="3"><input type='number' min="0" max="5" class='cinput'  name='Nohijos' id="Nohijos" value='<?= $Cpo[nohijos] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right">No. Hombres :</td>
                                <td><input type='text' class='cinput'  name='Nohombres' id="Nohombres" value='<?= $Cpo[nohombres] ?>'></input></td>
                                <td align="right">No. de Mujeres :</td>
                                <td><input type='text' class='cinput'  name='Nomujeres' id="Nomujeres" value='<?= $Cpo[nomujeres] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px" class="hijo1">
                                <td align="right">Edad Hijo 1 :</td>
                                <td><input type='text' class='cinput'  name='Hijo1' id="Hijo1" value='<?= $Cpo[hijo1] ?>'></input></td>
                                <td align="center" colspan="2"><?php CalculaEdad($Cpo["hijo1"]) ?></td>
                            </tr>
                            <tr style="height: 30px" class="hijo1">
                                <td align="right">Ocupacion 1 :</td>
                                <td colspan="3"><input style="width: 350px;" type='text' class='cinput'  name='Ocupacion1' id="Ocupacion1" value='<?= $Cpo[ocupacion1] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px" class="hijo2">
                                <td align="right">Edad Hijo 2 :</td>
                                <td><input type='text' class='cinput'  name='Hijo2' id="Hijo2" value='<?= $Cpo[hijo2] ?>'></input></td>
                                <td align="center" colspan="2"><?php CalculaEdad($Cpo["hijo2"]) ?></td>
                            </tr>
                            <tr style="height: 30px" class="hijo2">
                                <td align="right">Ocupacion 2 :</td>
                                <td colspan="3"><input style="width: 350px;" type='text' class='cinput'  name='Ocupacion2' id="Ocupacion2" value='<?= $Cpo[ocupacion2] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px" class="hijo3">
                                <td align="right">Edad Hijo 3 :</td>
                                <td><input type='text' class='cinput'  name='Hijo3' id="Hijo3" value='<?= $Cpo[hijo3] ?>'></input></td>
                                <td align="center" colspan="2"><?php CalculaEdad($Cpo["hijo3"]) ?></td>
                            </tr>
                            <tr style="height: 30px" class="hijo3">
                                <td align="right">Ocupacion 3 :</td>
                                <td colspan="3"><input style="width: 350px;" type='text' class='cinput'  name='Ocupacion3' id="Ocupacion3" value='<?= $Cpo[ocupacion3] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px" class="hijo4">
                                <td align="right">Edad Hijo 4 :</td>
                                <td><input type='text' class='cinput'  name='Hijo4' id="Hijo4" value='<?= $Cpo[hijo4] ?>'></input></td>
                                <td align="center" colspan="2"><?php CalculaEdad($Cpo["hijo4"]) ?></td>
                            </tr>
                            <tr style="height: 30px" class="hijo4">
                                <td align="right">Ocupacion 4 :</td>
                                <td colspan="3"><input style="width: 350px;" type='text' class='cinput'  name='Ocupacion4' id="Ocupacion4" value='<?= $Cpo[ocupacion4] ?>'></input></td>
                            </tr>
                            <tr style="height: 30px" class="hijo5">
                                <td align="right">Edad Hijo 5 :</td>
                                <td><input type='text' class='cinput'  name='Hijo5' id="Hijo5" value='<?= $Cpo[hijo5] ?>'></input></td>
                                <td align="center" colspan="2"><?php CalculaEdad($Cpo["hijo5"]) ?></td>
                            </tr>
                            <tr style="height: 30px" class="hijo5">
                                <td align="right">Ocupacion 5 :</td>
                                <td colspan="3"><input style="width: 350px;" type='text' class='cinput'  name='Ocupacion5' id="Ocupacion5" value='<?= $Cpo[ocupacion5] ?>'></input></td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="4">
                                    <input class="letrap" type="submit" value='Actualiza Datos Familiares' name='Boton'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <table  width='99%' align='center' id="logs" border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Modificaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table align="center" width="95%" style="margin: 8px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                    <tr class="letrap">
                                        <td><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('%/R.Humanos/Empleados/%') AND cliente=$busca
                                                ORDER BY id DESC LIMIT 15;";
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
                                            <td align="center">&nbsp;<?= $rg[fecha] ?></td>
                                            <td><?= $rg[usr] ?></td>
                                            <td><?= $rg[accion] ?></td>
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
                    <?php SbmenuEmpledos(); ?>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
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
