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
$Fecha = date("Y-m-d H:i:s");
$Fechaeven = date("Y-m-d");
$Msj = $_REQUEST["Msj"];
$idres = $_REQUEST["idres"];
setcookie("BuscaEquipo", $busca);
$Return = "equipose.php";

$cSql = "select * from eqp where id='$busca'";

$CpoA = mysql_query($cSql, $link);

$Cpo = mysql_fetch_array($CpoA);

if ($_REQUEST["bt"] === "Actualizar") {

    $Prv1 = explode(" ", $_REQUEST["Proveedor1"]);
    $Prv2 = explode(" ", $_REQUEST["Proveedor2"]);
    $Prv3 = explode(" ", $_REQUEST["Proveedor3"]);

    $Sql = "UPDATE eqp SET nombre='$_REQUEST[Nombre]',alias='$_REQUEST[Alias]',marca='$_REQUEST[Marca]',"
            . "modelo='$_REQUEST[Modelo]',observaciones='$_REQUEST[Observaciones]', caracteristicas = '$_REQUEST[Caracteristicas]', serie = '$_REQUEST[Serie]', sucursal = '$_REQUEST[Sucursal]', proveedor1 = '$Prv1[0]', proveedor2 = '$Prv2[0]', proveedor3 = '$Prv3[0]', departamento = '$_REQUEST[Departamento]' "
            . "WHERE id='$busca' limit 1";
    //echo $Sql;

    if (mysql_query($Sql)) {
        $Msj = "¡Registro Actualizado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Registro Actualizado", "eqp", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }


} else if ($_REQUEST["bt"] === "Nuevo") {

    $Prv1 = explode(" ", $_REQUEST["Proveedor1"]);
    $Prv2 = explode(" ", $_REQUEST["Proveedor2"]);
    $Prv3 = explode(" ", $_REQUEST["Proveedor3"]);

    $lUp = "INSERT INTO eqp (nombre,alias,marca,modelo,observaciones,caracteristicas,serie,sucursal,proveedor1,proveedor2,proveedor3,departamento) "
            . "VALUES ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Marca]','$_REQUEST[Modelo]',"
            . "'$_REQUEST[Observaciones]','$_REQUEST[Caracteristicas]','$_REQUEST[Serie]','$_REQUEST[Sucursal]','$Prv1[0]','$Prv2[0]','$Prv3[0]','$_REQUEST[Departamento]')";

    if (mysql_query($lUp)) {
        $Msj = "¡Registro ingresado con exito!";
        $Id = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Datos Principales Creacion", "eqp", $Fecha, $Id, $Msj, $Return);
    } else {
        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
        header("Location: equipose.php?busca=NUEVO&Msj=$Msj&Error=SI");
    }


} else if ($_REQUEST["NvaFecha"] === "Nuevo") {

    $Prv = explode(" ", $_REQUEST["Proveedor"]);
    $sql = "INSERT INTO fechas_equipos (id_equipo,fecha,observaciones,proveedor,usr,fecha_reg,status) "
            . "VALUES ('" . $busca . "','" . $_REQUEST["FechaRp"] . "','" . $_REQUEST["ObservacionRp"] . "'," . $Prv[0] . ",'" . $Gusr . "','" . $Fecha . "','En Proceso')";
    if (mysql_query($sql)) {
        $Msj = "¡Registro ingresado con exito!";
        $cId = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Nuevo Fecha de equipos", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["Historial"] === "Nuevo") {
    $Sql = "INSERT INTO historial_equipo (destino,fecha,piso,cuarto,observaciones,id_equipo) "
            . "VALUES ('" . $_REQUEST["Destino"] . "','" . $_REQUEST["Fecha"] . "','" . $_REQUEST["Piso"] . "'"
            . ",'" . $_REQUEST["Cuarto"] . "','" . $_REQUEST["Observaciones"] . "','" . $busca . "');";
    if (mysql_query($Sql)) {
        $Msj = "¡Registro ingresado con exito!";
        $cId = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Agrega Historial del equipo", "h_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "Descarga") {
    header("Content-disposition: attachment; filename=" . $_REQUEST["name"]);
    header("Content-type: MIME");
    readfile("manualespdf/" . $_REQUEST["name"]);
} else if ($_REQUEST["op"] === "Borrapdf") {
    $Sql = "DELETE FROM equipos_pdf WHERE id = " . $_REQUEST["cIdnvo"];
    if (mysql_query($Sql)) {
        $Msj = "¡Registro borrado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Elimina pdf", "equipos_pdf", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "BorraMyR") {
    $Sql = "DELETE FROM fechas_equipos WHERE id = " . $_REQUEST["cIdnvo"];
    if (mysql_query($Sql)) {
        $Msj = "¡Registro borrado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Elimina Fechas de mantenimiento y reparacion", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "BorraHE") {
    $Sql = "DELETE FROM historial_equipo WHERE id = " . $_REQUEST["cIdnvo"];
    echo $Sql;
    if (mysql_query($Sql)) {
        $Msj = "¡Registro borrado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Elimina historial de equipo", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "Guardar") {

    $Up2  = mysql_query("UPDATE eqp SET status='$_REQUEST[Statusbit]' WHERE id='$busca' limit 1;"); 

    $Sql = "INSERT INTO regeq (id_eq,fecha,observaciones,usr,status,fechaeven) VALUES ('$busca','$Fecha','" . $_REQUEST["ObsrBitacora"] . "','$Gusr','" . $_REQUEST["Statusbit"] . "','" . $_REQUEST["Fechaeven"] . "');";
    if (mysql_query($Sql)) {
        $Msj = "¡Registro agregado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Agrega registro en Bitcora", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "Responde") {
    $Sql = "INSERT INTO resregeq (id_reg,fecha,observaciones,usr) VALUES ('" . $_REQUEST["idres"] . "','$Fecha','" . $_REQUEST["Respuesta"] . "','$Gusr');";
    if (mysql_query($Sql)) {
        $Msj = "¡Registro agregado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Agrega respuesta a registro en Bitcora", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
}

#Variables comunes;
$cSql = "select * from eqp where id='$busca'";
$CpoA = mysql_query($cSql);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;

$sucnombre = $aSucursal[$Cpo[sucursal]];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle de equipo</title>
            <?php require ("./config_add.php"); ?>
            <link rel="stylesheet" type="text/css" href="css/dropzone.css">
            <script type="text/javascript" src="js/dropzone.js"></script>
    </head>
    <body topmargin="1">
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="2" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Detalle del equipo No. <?= $busca ?> - <?= $Cpo[alias] ?>::..
                </td>
            </tr>
            <tr>
                <td colspan="2" valign='top' align='center' height='240' width='100%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name = 'form1' method = 'get' action = "<?= $_SERVER['PHP_SELF'] ?>">
                            <tr style = "background-color: #2c8e3c">
                                <td class = 'letratitulo' align = "center" colspan = "4">
                                    ..:: Datos principales ::..
                                </td>
                            </tr>
                            <tr style = "height: 45px" class="letrap">
                                <?php
                                    $EqpA = "SELECT *,count(*) as contar from equipos_img where id_equipo='$busca'";
                                    $EqpB = mysql_query($EqpA, $link);
                                    $Eqp = mysql_fetch_array($EqpB);
                                    if($Eqp[contar]==0){
                                        $foto='NoImagen.png';
                                    }else{
                                        $foto=$Eqp[nombre_archivo];
                                    }
                                    
                                ?>  
                                <td width = '20%' class="ssbm" rowspan="6" align="center">
                                    <div class="fallback" style="padding: 15px;">
                                        <input type="file" name="file" id="file" accept="image/png,image/jpeg"><IMG SRC="../lcd-net/fotoeqp/<?=$foto?>" border='0' width='250' height='200'></input>
                                    </div>
                                    <script type="text/javascript">
                                        var dropzone = new Dropzone("#file", {
                                            url: 'subirimg.php'
                                        });
                                    </script>

                                </td>

                                <td width = '30%' class="ssbm">
                                    <strong>
                                     Nombre : 
                                    </strong><br>
                                    <input type = 'text' class = 'cinput' style="width: 350px;" name = 'Nombre' value = '<?= $Cpo[nombre] ?>'/> Id: <?= $busca ?>
                                </td>
                                <td  width = '20%' class="ssbm">
                                    <strong>
                                     Alias : 
                                    </strong><br>
                                    <input type = 'text' class = 'cinput' style="width: 200px;" name = 'Alias' value = '<?= $Cpo[alias] ?>'/>
                                </td>
                                <td width = '20%' class="ssbm">
                                    <strong>
                                     Marca : 
                                    </strong><br>
                                    <input type = 'text' class = 'cinput' style="width: 200px;" name = 'Marca' value = '<?= $Cpo[marca] ?>'/>
                                </td>
                            </tr>

                            <tr style = "height: 45px" class="letrap">

                                <td width = '30%' class="ssbm">
                                    <strong>
                                     Modelo : 
                                    </strong><br>
                                    <input type = 'text' class = 'cinput' style="width: 200px;" name = 'Modelo' value = '<?= $Cpo[modelo] ?>'/>
                                </td>
                                <td  width = '20%' class="ssbm">
                                    <strong>
                                     No. Serie : 
                                    </strong><br>
                                    <input type = 'text' class = 'cinput' style="width: 200px;" name = 'Serie' value = '<?= $Cpo[serie] ?>'/>
                                </td>
                                <td width = '20%' class="ssbm">
                                    <strong>
                                     Sucursal : 
                                    </strong><br>
                                    <select class='letrap' name='Sucursal'>
                                        <?php
                                        $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' and id<>'100' ORDER BY id");
                                        while ($Cia = mysql_fetch_array($CiaA)) {
                                            if($Cia[id]==$Cpo[sucursal]){
                                                echo '<option selected style="font-weight:bold" value="' . $Cia[id] . '">- ' . $aSucursal[$Cia[id]] . ' -</option>';
                                            }else{
                                                echo '<option value="' . $Cia[id] . '">' . $aSucursal[$Cia[id]] .'</option>';
                                            }
                                        }

                                        if($Cpo[sucursal]==11){
                                            echo '<option selected style="font-weight:bold" value="11">- OHF - Torre -</option>';
                                            echo '<option value="12">OHF - Urgencia</option>';
                                            echo '<option value="13">OHF - Hospitalizacion</option>';
                                        }elseif($Cpo[sucursal]==12){
                                            echo '<option value="11">OHF - Torre</option>';
                                            echo '<option selected style="font-weight:bold" value="12">- OHF - Urgencia -</option>';
                                            echo '<option value="13">OHF - Hospitalizacion</option>';
                                        }elseif($Cpo[sucursal]==13){
                                            echo '<option value="11">OHF - Torre</option>';
                                            echo '<option value="12">OHF - Urgencia</option>';
                                            echo '<option selected style="font-weight:bold" value="13">- OHF - Hospitalizacion -</option>';
                                        }else{
                                            echo '<option value="11">OHF - Torre</option>';
                                            echo '<option value="12">OHF - Urgencia</option>';
                                            echo '<option value="13">OHF - Hospitalizacion</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr style = "height: 45px" class="letrap">
                                <td width = '30%' class="ssbm" colspan="3">
                                    <strong>
                                     Proveedor 1 : 
                                    </strong><br>
                                    <?php
                                        $ProvA1 = "SELECT * from prbVentaMantenimiento where id='$Cpo[proveedor1]'";
                                        $Prov1a = mysql_query($ProvA1, $link);
                                        $Prov1 = mysql_fetch_array($Prov1a);
                                    ?>
                                    <input type='text' style="width: 200px;" class='cinput'  name='Proveedor1' id="Proveedor1" value="<?= $Prov1[id] ?> - <?= $Prov1[alias] ?>"/>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#Proveedor1').autocomplete({
                                                source: function (request, response) {
                                                    $.ajax({
                                                        url: "equiposeajax.php",
                                                        datoType: "json",
                                                        data: {q: request.term, k: "pst"},
                                                        success: function (data) {
                                                            response(JSON.parse(data));
                                                            console.log(JSON.parse(data));
                                                        }
                                                    });
                                                },
                                                minLength: 1
                                            });
                                        });
                                        </script>
                                    &nbsp; &nbsp; 
                                    <?= $Prov1[proveedor] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov1[empresa] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov1[telefono] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov1[email] ?>
                                </td>
                            </tr>

                            <tr style = "height: 45px" class="letrap">
                                <td width = '30%' class="ssbm" colspan="3">
                                    <strong>
                                     Proveedor 2 : 
                                    </strong><br>
                                    <?php
                                        $ProvA2 = "SELECT * from prbVentaMantenimiento where id='$Cpo[proveedor2]'";
                                        $Prov2a = mysql_query($ProvA2, $link);
                                        $Prov2 = mysql_fetch_array($Prov2a);
                                    ?>
                                    <input type='text' style="width: 200px;" class='cinput'  name='Proveedor2' id="Proveedor2" value="<?= $Prov2[id] ?> - <?= $Prov2[alias] ?>"/>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#Proveedor2').autocomplete({
                                                source: function (request, response) {
                                                    $.ajax({
                                                        url: "equiposeajax.php",
                                                        datoType: "json",
                                                        data: {q: request.term, k: "pst"},
                                                        success: function (data) {
                                                            response(JSON.parse(data));
                                                            console.log(JSON.parse(data));
                                                        }
                                                    });
                                                },
                                                minLength: 1
                                            });
                                        });
                                        </script>
                                    &nbsp; &nbsp; 
                                    <?= $Prov2[proveedor] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov2[empresa] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov2[telefono] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov2[email] ?>
                                </td>
                            </tr>

                            <tr style = "height: 45px" class="letrap">
                                <td width = '30%' class="ssbm" colspan="3">
                                    <strong>
                                     Proveedor 3 : 
                                    </strong><br>
                                    <?php
                                        $ProvA3 = "SELECT * from prbVentaMantenimiento where id='$Cpo[proveedor3]'";
                                        $Prov3a = mysql_query($ProvA3, $link);
                                        $Prov3 = mysql_fetch_array($Prov3a);
                                    ?>
                                    <input type='text' style="width: 200px;" class='cinput'  name='Proveedor3' id="Proveedor3" value="<?= $Prov3[id] ?> - <?= $Prov3[alias] ?>"/>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#Proveedor3').autocomplete({
                                                source: function (request, response) {
                                                    $.ajax({
                                                        url: "equiposeajax.php",
                                                        datoType: "json",
                                                        data: {q: request.term, k: "pst"},
                                                        success: function (data) {
                                                            response(JSON.parse(data));
                                                            console.log(JSON.parse(data));
                                                        }
                                                    });
                                                },
                                                minLength: 1
                                            });
                                        });
                                        </script>
                                    &nbsp; &nbsp; 
                                    <?= $Prov3[proveedor] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov3[empresa] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov3[telefono] ?>
                                    &nbsp; - &nbsp;
                                    <?= $Prov3[email] ?>
                                </td>
                            </tr>

                            <tr style = "height: 95px" class="letrap">
                                <td width = '30%' class="ssbm" colspan="2">
                                    <strong>
                                     Caracteristicas : 
                                    </strong><br>
                                    <textarea name = "Caracteristicas" class = "letrap" rows = "5" cols = "90" ><?= $Cpo[caracteristicas] ?></textarea>
                                </td>
                                <td class="ssbm" valign="top">
                                    <strong>
                                     Departamento : 
                                    </strong><br>
                                    <select class='letrap' name='Departamento' style="width: 170px;">
                                        <?php
                                        if($Cpo[departamento]=='Laboratorio'){
                                            echo '<option selected style="font-weight:bold" value="Laboratorio">- Laboratorio -</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital</option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Rayos_X'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option selected style="font-weight:bold" value="Rayos_X">- Rayos X -</option>';
                                            echo '<option value="Hospital">Hospital</option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Hospital'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option selected style="font-weight:bold" value="Hospital">- Hospital -</option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_1'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_1">- Consultorio_1 -</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_2'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_2">- Consultorio_2 -</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_3'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_3">- Consultorio_3 -</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_4'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_4">- Consultorio_4 -</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_5'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_5">- Consultorio_5 -</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_6'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_6">- Consultorio_6 -</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }elseif($Cpo[departamento]=='Consultorio_7'){
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital/option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option selected style="font-weight:bold" value="Consultorio_7">- Consultorio_7 -</option>';
                                        }else{
                                            echo '<option selected style="font-weight:bold" value="">- Elije un departamento -</option>';
                                            echo '<option value="Laboratorio">Laboratorio</option>';
                                            echo '<option value="Rayos_X">Rayos X</option>';
                                            echo '<option value="Hospital">Hospital</option>';
                                            echo '<option value="Consultorio_1">Consultorio_1</option>';
                                            echo '<option value="Consultorio_2">Consultorio_2</option>';
                                            echo '<option value="Consultorio_3">Consultorio_3</option>';
                                            echo '<option value="Consultorio_4">Consultorio_4</option>';
                                            echo '<option value="Consultorio_5">Consultorio_5</option>';
                                            echo '<option value="Consultorio_6">Consultorio_6</option>';
                                            echo '<option value="Consultorio_7">Consultorio_7</option>';
                                        }
                                        ?>
                                    </select>
                            </td>
                            </tr>

                            <tr style = "height: 95px" class="letrap">
                                <td width = '20%' class="ssbm" align="center">
                                    <strong>
                                     Status : 
                                    </strong>
                                    <?= $Cpo[status] ?>
                                    <?php
                                    switch($Cpo[status]) {
                                        case Optimo:
                                    ?>
                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#239B56;" aria-hidden="true"></i>
                                    <?php
                                        break;
                                        case Observacion:
                                    ?>
                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#F39C12;" aria-hidden="true"></i>
                                    <?php
                                        break;
                                        case Fuera_de_Servicio:
                                    ?>
                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#E74C3C;" aria-hidden="true"></i>
                                    <?php
                                        break;
                                        case Almacenado:
                                    ?>
                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#85929E;" aria-hidden="true"></i>
                                    <?php
                                        break;
                                        case Baja:
                                    ?>
                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#000000;" aria-hidden="true"></i>
                                    <?php
                                        break;
                                    }
                                    ?>  
                                    <br>
                                    <br>
                                    <div align="center">
                                        <?php
                                        if ($busca > 0) {
                                            ?>
                                            <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                            <?php
                                        } else {
                                            ?>
                                            <input class="letrap" type="submit" value='Nuevo' name='bt'></input>
                                            <?php
                                        }
                                        ?>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                    </div>
                                    <br>
                                </td>
                                <td width = '30%' class="ssbm" colspan="3">
                                    <strong>
                                     Observaciones : 
                                    </strong><br>
                                    <textarea name="Observaciones" class="letrap" rows="5" cols="90" ><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                        </form>
                    </table> 
                </td>
                </tr>

                <tr>
                <td valign='top' align='center' height='440' width='40%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='margin-top: 1px;border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    ..:: Guias del equipo ::..
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <table width="98%" style="margin-top: 10px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td><b>&nbsp; Nombre PDF</b></td>
                                            <td><b>&nbsp; Descargar</b></td>
                                            <td><b>&nbsp; Eliminar</b></td>
                                        </tr>

                                        <?php
                                        $sql = "SELECT * FROM equipos_pdf WHERE origen='PDF_equipos' and id_equipo = " . $busca ;
                                        $PgsA = mysql_query($sql);
                                        while ($rg = mysql_fetch_array($PgsA)) {
                                            (($nRng % 2) > 0) ? $Fdo = $Gfdogrid : $Fdo = $Gfdogrid;
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td><?= $rg["nombre_archivo"] ?></td>
                                                <td align="center">
                                                    <a href="equipose.php?busca=<?= $busca ?>&op=Descarga&name=<?= $rg["nombre_archivo"] ?>">
                                                        <i class="fa fa-cloud-download fa-lg" style="color: #85929E;" aria-hidden="true"></i> 
                                                    </a>
                                                </td>
                                                <td align="center">
                                                    <a href="equipose.php?busca=<?= $busca ?>&op=Borrapdf&cIdnvo=<?= $rg["id"] ?>">
                                                        <i class="fa fa-trash fa-lg" aria-hidden="true" style="color: #E74C3C;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </form>
                    </table> 

                    <div style="padding: 8px;">
                        <form class="dropzone" id="mydrop" action="subirpdf.php?idres=0&origen=PDF_equipos">
                            <div class="fallback">
                                <input type="file" name="file" multiple id="file"></input>
                            </div>
                        </form>
                    </div>
                    <script type="text/javascript">
                        var dropzone = new Dropzone("#file", {
                            url: 'subirpdf.php?idres=0&origen=PDF_equipos'
                        });
                    </script>

                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Modificaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <table width="98%" style="margin-top: 10px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                    <tr class="letrap" align="center">
                                        <td><b>&nbsp; Id</b></td>
                                        <td><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Admin/Equipos/%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 10;";
                                     
                                    $PgsA = mysql_query($sql);
                                    while ($rg = mysql_fetch_array($PgsA)) {
                                        if (($nRng % 2) > 0) {
                                            $Fdo = 'FFFFFF';
                                        } else {
                                            $Fdo = $Gfdogrid;
                                        }
                                        ?>
                                        <tr class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#CBE3E9';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                                            <td>
                                                <b>&nbsp;<?= $rg[id] ?></b>
                                            </td>
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
                                </table><br/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign='top' width='60%'>         
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr>
                            <td>      
                                <?php
                            if ($_REQUEST["op"] == "Docto") {
                                ?>

                                <div style="padding: 8px;">
                                    <form class="dropzone" id="mydrop" action="subirpdf.php?idres=<?=$idres?>&origen=Docto">
                                        <div class="fallback">
                                            <input type="file" name="file" multiple id="file"></input>
                                        </div>
                                    </form>
                                </div>
                                <script type="text/javascript">
                                    var dropzone = new Dropzone("#file", {
                                        url: 'subirpdf.php?idres=<?=$idres?>&origen=Docto'
                                    });
                                </script>

                                    <br />
                                    <div align="center">
                                        <a class="edit" href=equipose.php?busca=<?= $busca ?>&op=Cancelar&idres=<?= $mql[id] ?>><b>Cerrar</b></a>
                                    </div>
                                    <br />
                                <?php
                            } 
                                ?>
   
                                <table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                    <form name='Bitacora' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
                                        <tr style="background-color: #2c8e3c">
                                            <td class='letratitulo'align="center" colspan="2">
                                                .:: Bitacora::.
                                            </td>
                                        </tr>
                                        </tr>
                                        <table width='98%' border='0' align='center'>
                                            <?php
                                            if ($_REQUEST["Boton"] == "Agrega") {
                                                ?>
                                                <tr>
                                                    <td align="center" class="letrap">
                                                        <textarea rows="2" cols="70" class="letrap" name="ObsrBitacora" class="letrap"></textarea>
                                                        <br /><br />
                                                        <strong>
                                                         Fecha Evento : 
                                                        </strong>
                                                        <input type='date' class='letrap' name='Fechaeven' value ='<?= $Fechaeven ?>' required/>
                                                         &nbsp;
                                                        <strong>
                                                         Status : 
                                                        </strong>
                                                        <select name='Statusbit' class="letrap" required>
                                                            <option value="">- Elige Status -</option>
                                                            <option value="Optimo">Optimo</option>
                                                            <option value="Observacion">Observacion</option>
                                                            <option value="Fuera_de_Servicio">Fuera_de_Servicio</option>
                                                            <option value="Almacenado">Almacenado</option>
                                                            <option value="Baja">Baja</option>
                                                        </select>
                                                         <br /><br />
                                                        <input type="submit" name="op" class="letrap" value='Guardar'/> &nbsp;
                                                        &nbsp; <input type="submit" name="op" class="letrap" value='Cancela'/>
                                                    </td>
                                                </tr>
                                                </table>
                                                <?php
                                            } else {
                                                if ($_REQUEST["op"] == "Responder") {
                                                    ?>
                                                    <tr>
                                                        <td align='center'>
                                                            <textarea rows="2" cols="50" name="Respuesta" class="letrap"></textarea>
                                                            <input type="submit" class="letrap" name="op" value="Responde"></input>
                                                            &nbsp; <input type="submit" name="op" class="letrap" value='Cancela'/>
                                                            <input type="hidden" name="idres" value="<?= $_REQUEST["idres"] ?>"></input>
                                                        </td>
                                                    </tr>
                                                    </table>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td align='center'>
                                                            <input class="letrap" type="submit" name="Boton" value="Agrega"/>
                                                        </td>
                                                    </tr>
                                                    </table>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                            }
                                            ?>

                                        <table width='98%' border='0' align='center'>
                                            <tr>
                                                <td colspan="2">
                                                    <table width="100%" style="margin-top: 10px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                                        <tr class="letrap" align="center">
                                                            <td><b>Doctos</b></td>
                                                            <td><b>Fech_Reg.</b></td>
                                                            <td><b>Descripción</b></td>
                                                            <td><b>Fech_Event</b></td>
                                                            <td><b>Usuario</b></td>
                                                            <td><b>Responder</b></td>
                                                            <td><b>Sts</b></td>
                                                        </tr>

                                                        <?php

                                                        $cSql2 = "SELECT * FROM regeq WHERE id_eq= '$busca' order by fecha DESC  limit 10";

                                                        $UpA = mysql_query($cSql2, $link);


                                                        while ($mql = mysql_fetch_array($UpA)) {
                                                            ?>
                                                            <tr class="letrap" align="center" bgcolor='<?=$Gfdogrid?>' onMouseOver=this.style.backgroundColor='#CBE3E9';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Gfdogrid?>';>

                                                                <td align="center">
                                                                    <a class="edit" href=equipose.php?busca=<?= $busca ?>&op=Docto&idres=<?= $mql[id] ?>><i class="fa fa-cloud-upload fa-lg" style="color: #85929E;"></i></a>
                                                                </td>                               
                                                                <td align="center">
                                                                    <b>&nbsp;<?= $mql[fecha] ?></b>
                                                                </td>
                                                                <td align="left">
                                                                    &nbsp;<?= $mql[observaciones] ?>
                                                                </td>
                                                                <td align="center">
                                                                    <b>&nbsp;<?= $mql[fechaeven] ?></b>
                                                                </td>
                                                                <td>
                                                                    <?= $mql[usr] ?>
                                                                </td>
                                                                <td><a class="edit" href=equipose.php?busca=<?= $busca ?>&op=<?= Responder ?>&idres=<?= $mql[id] ?>>Responder </a></td>
                                                                <td align="center">
                                                                    <?php
                                                                    switch($mql[status]) {
                                                                        case Optimo:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#239B56;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Observacion:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#F39C12;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Fuera_de_Servicio:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#E74C3C;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Almacenado:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#85929E;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Baja:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#000000;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                    }
                                                                    ?>  
                                                                </td>


                                                                    <?php
                                                                    $sql2 = "SELECT * FROM equipos_pdf WHERE id_respuesta = ".$mql[id];
                                                                    $PgsA2 = mysql_query($sql2);
                                                                    while ($rg2 = mysql_fetch_array($PgsA2)) {
                                                                        (($nRng % 2) > 0) ? $Fdo = $Gfdogrid : $Fdo = $Gfdogrid;
                                                                        ?>
                                                                            <tr class="letrap" bgcolor='#FCF3CF' onMouseOver=this.style.backgroundColor='#CBE3E9';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#FCF3CF';>
                                                                            <td></td>
                                                                            <td><?= $rg2["fecha"] ?></td>
                                                                            <td><?= $rg2["nombre_archivo"] ?></td>
                                                                            <td align="center">
                                                                                <a href="equipose.php?busca=<?= $busca ?>&op=Descarga&name=<?= $rg2["nombre_archivo"] ?>">
                                                                                    <i class="fa fa-cloud-download fa-lg" style="color: #85929E;" aria-hidden="true"></i> 
                                                                                </a>
                                                                            </td>
                                                                            <td align="center"><?= $rg2["usr"] ?></td>
                                                                            <td align="center">
                                                                                <a href="equipose.php?busca=<?= $busca ?>&op=Borrapdf&cIdnvo=<?= $rg2["id"] ?>">
                                                                                    <i class="fa fa-trash fa-lg" aria-hidden="true" style="color: #E74C3C;"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                            </tr>
                                                            <?php
                                                            $cSql3 = "(SELECT * FROM resregeq WHERE id_reg= '$mql[id]' order by fecha DESC limit 10)";

                                                            $UpB = mysql_query($cSql3, $link);

                                                            while ($mql2 = mysql_fetch_array($UpB)) {
                                                                ?>
                                                                <tr class="letrap" bgcolor='#FCF3CF' onMouseOver=this.style.backgroundColor='#CBE3E9';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#FCF3CF';>

                                                                    <td align="center">
                                                                    </td>
                                                                    <td align="center">
                                                                        <b><?= $mql2[fecha] ?></b>
                                                                    </td>
                                                                    <td>
                                                                        &nbsp;<?= $mql2[observaciones] ?>
                                                                    </td>
                                                                    <td>
                                                                        
                                                                    </td>                             
                                                                    <td align="center">
                                                                        <?= $mql2[usr] ?>
                                                                    </td>
                                                                    <td  aling="right"><b>Respuesta</b></td>
                                                                    <td>
                                                                    <?php
                                                                    switch($mql2[status]) {
                                                                        case Optimo:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#239B56;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Observacion:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#F39C12;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Fuera_de_Servicio:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#E74C3C;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Almacenado:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#85929E;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                        case Baja:
                                                                    ?>
                                                                        &nbsp; &nbsp; <i class="fa fa-square fa-2x" style="color:#000000;" aria-hidden="true"></i>
                                                                    <?php
                                                                        break;
                                                                    }
                                                                    ?>  
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $nRng2++;
                                                            }

                                                            $nRng++;
                                                        }
                                                        ?>
                                                    </table>
                                                </td>

                                            </tr>
                                        </table>
                                        <input type="hidden" name="busca" value="<?= $busca ?>"></input>

                                        </td>
                                        </tr>      
                                    </table><br/>
                                </form>

                                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                        <tr style="background-color: #2c8e3c">
                                            <td class='letratitulo'align="center" colspan="4">
                                                ..:: Fechas de Mantenimiento y Reparacion ::..
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <table align="center" width="98%" style="margin: 2%;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                                    <tr class="letrap">
                                                        <td><b>&nbsp; Proveedor</b></td>
                                                        <td><b>&nbsp; Fecha</b></td>
                                                        <td><b>&nbsp; Observacion</b></td>
                                                        <td><b>&nbsp; Eliminar</b></td>
                                                    </tr>

                                                    <?php
                                                    $sql = "SELECT fechas_equipos.id,vm.proveedor,fechas_equipos.fecha,fechas_equipos.observaciones "
                                                            . " FROM fechas_equipos LEFT JOIN prbVentaMantenimiento vm ON fechas_equipos.proveedor = vm.id WHERE id_equipo = " . $busca;
                                                    $PgsA = mysql_query($sql);
                                                    while ($rg = mysql_fetch_array($PgsA)) {
                                                        (($nRng % 2) > 0) ? $Fdo = $Gfdogrid : $Fdo = $Gfdogrid;
                                                        ?>
                                                        <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                            <td><?= $rg["proveedor"] ?></td>
                                                            <td><?= $rg["fecha"] ?></td>
                                                            <td><?= $rg["observaciones"] ?></td>
                                                            <td align="center">
                                                                <a href="equipose.php?busca=<?= $busca ?>&op=BorraMyR&cIdnvo=<?= $rg["id"] ?>">
                                                                    <i class="fa fa-trash fa-lg" aria-hidden="true" style="color: #E74C3C;"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr style="height: 30px" class="Inpt">
                                            <td align="right">Proveedor : </td>
                                            <td ><input type='text' class='cinput'  name='Proveedor' id="Proveedor" required/></td>
                                            <script src="js/jquery-1.8.2.min.js"></script>
                                            <script src="jquery-ui/jquery-ui.min.js"></script>
                                            <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#Proveedor').autocomplete({
                                                source: function (request, response) {
                                                    $.ajax({
                                                        url: "equiposeajax.php",
                                                        datoType: "json",
                                                        data: {q: request.term, k: "pst"},
                                                        success: function (data) {
                                                            response(JSON.parse(data));
                                                            console.log(JSON.parse(data));
                                                        }
                                                    });
                                                },
                                                minLength: 1
                                            });
                                        });
                                            </script>
                                            <td>Fecha : </td>
                                            <td><input type="date" name="FechaRp" class="cinput" required></input></td>
                                        </tr>
                                        <tr style="height: 30px" class="Inpt">
                                            <td align="right">Observacion :</td>
                                            <td colspan="3"><textarea name="ObservacionRp" cols="60" rows="2" class="cinput"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td height="35px" align="center" colspan="4">
                                                <input class="letrap" type="submit" value='Nuevo' name='NvaFecha'></input>
                                                <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                            </td>
                                        </tr>
                                    </form>
                                </table><br/>

                                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                    <form name='HistorialEquipo' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                        <tr style="background-color: #2c8e3c">
                                            <td class='letratitulo'align="center" colspan="4">
                                                ..:: Historial del equipo ::..
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <table align="center" width="96%" style="margin: 2%;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                                    <tr class="letrap">
                                                        <td><b>&nbsp; Destino</b></td>
                                                        <td><b>&nbsp; Fecha</b></td>
                                                        <td><b>&nbsp; Piso</b></td>
                                                        <td><b>&nbsp; Cuarto</b></td>
                                                        <td><b>&nbsp; Observaciones</b></td>
                                                        <td><b>&nbsp; Eliminar</b></td>
                                                    </tr>
                                                    <?php
                                                    $sql = "SELECT *,historial_equipo.id idhe FROM historial_equipo LEFT JOIN cia ON destino=cia.id  WHERE id_equipo = " . $busca;
                                                    $PgsA = mysql_query($sql);
                                                    while ($rg = mysql_fetch_array($PgsA)) {
                                                        (($nRng % 2) > 0) ? $Fdo = $Gfdogrid : $Fdo = $Gfdogrid;
                                                        ?>
                                                        <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                            <td><?= $rg["alias"] ?></td>
                                                            <td><?= $rg["fecha"] ?></td>
                                                            <td><?= $rg["piso"] ?></td>
                                                            <td><?= $rg["cuarto"] ?></td>
                                                            <td><?= $rg["observaciones"] ?></td>
                                                            <td align="center">
                                                                <a href="equipose.php?busca=<?= $busca ?>&op=BorraHE&cIdnvo=<?= $rg["idhe"] ?>">
                                                                    <i class="fa fa-trash fa-lg" aria-hidden="true" style="color: #E74C3C;"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr style="height: 30px" class="Inpt">
                                            <td align="right">Destino : </td>
                                            <td>
                                                <select name="Destino" class="letrap">
                                                    <?php
                                                    $Sql = "SELECT id,alias FROM cia WHERE id<100 ORDER BY id ASC;";
                                                    $cSQl = mysql_query($Sql);
                                                    while ($rg = mysql_fetch_array($cSQl)) {
                                                        ?>
                                                        <option value="<?= $rg["id"] ?>"><?= $rg["alias"] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>Fecha : </td>
                                            <td><input type="date" name="Fecha" class="cinput" required></input></td>
                                        </tr>
                                        <tr style="height: 30px" class="Inpt">
                                            <td align="right">Piso : </td>
                                            <td><input type="number" name="Piso" class="cinput" min="0" max="20"></input></td>
                                            <td>No. Cuarto</td>
                                            <td><input type="number" name="Cuarto" class="cinput" min="0" max="20"></input></td>
                                        </tr>
                                        <tr style="height: 30px" class="Inpt">
                                            <td align="right">Observacion :</td>
                                            <td colspan="3"><textarea name="Observaciones" cols="70" rows="2" class="cinput"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td height="35px" align="center" colspan="4">
                                                <input class="letrap" type="submit" value='Nuevo' name='Historial'></input>
                                                <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                            </td>
                                        </tr>
                                    </form>
                                </table> 
                </td>
            </tr>
        </table>
    <br>
    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2'> 
        <tr align="letf"> 
            <td valign='top' width="22%">
                <a class='elim' href='javascript:window.close()'><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table> <br><br>
</body>

</html>

<?php
mysql_close();
?>
