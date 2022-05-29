<?php
#Librerias
session_start();
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");
$link = conectarse();
require ("config.php");   //Parametros de colores;
$Gusr = $_SESSION["Usr"][0];
$Msj = $_REQUEST["Msj"];
$busca = $_REQUEST["busca"];
$suc = $_REQUEST["suc"];
$Fecha = date("Y-m-d H:i:s");

if ($_REQUEST["boton"] == "Agregar") {

    $ini = date("Y-m-d", strtotime($_REQUEST[Inicia]));

    $sql = "INSERT INTO fechas_equipos (id_equipo,fecha,observaciones,proveedor,usr,fecha_reg,status)"
        . " VALUES ('" . $_REQUEST["Nombre2"] . "','" . $ini . "','" . $_REQUEST["Observaciones"] . "','" . $_REQUEST["Proveedor"] . "','" . $Gusr . "','" . $Fecha . "','En Proceso')";

    if (mysql_query($sql)) {
        $Msj = "¡Registro ingresado con exito!";
        $Id = mysql_insert_id();

        $sql2 = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Admin/Equipos/Registro de Mantto. Agregado','f_equipos','$Fecha','$_REQUEST[Nombre2]')";

        mysql_query($sql2);

        header("Location: calendarioeqp.php?suc=$suc&Msj=$Msj");

    } else {

        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
        header("Location: calendarioeqp.php?suc=$sucMsj=$Msj&Error=SI");

    }


} else if ($_REQUEST["boton"] == "Actualizar") {

    $ini = date("Y-m-d", strtotime($_REQUEST[Inicia]));

    $sql = "UPDATE fechas_equipos SET fecha = '$ini', status = '$_REQUEST[Status]', observaciones = '$_REQUEST[Observaciones]', proveedor = '$_REQUEST[Proveedor]' WHERE id = $busca limit 1;";

    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis " . $sql;
    } else {
        $Msj = "Registro agregado con exito";
    }

    $sqlA = "SELECT id_equipo FROM fechas_equipos WHERE id=$busca limit 1;";
    $sqlB=mysql_query($sqlA);
    $eqpos = mysql_fetch_array($sqlB);
    $eqpo=$eqpos[id_equipo];

    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Admin/Equipos/Informac. de Mantto. Actualizada','f_equipos','$Fecha','$eqpo')";

    mysql_query($sql);

    header("Location: calendarioeqp.php?suc=$suc&Msj=$Msj");

} elseif ($_REQUEST["boton"] == "Eliminar") {

    $sqlA = "SELECT id_equipo FROM fechas_equipos WHERE id=$busca limit 1;";
    $sqlB=mysql_query($sqlA);
    $eqpos = mysql_fetch_array($sqlB);
    $eqpo=$eqpos[id_equipo];

    $Sql = "DELETE FROM fechas_equipos WHERE id = $busca limit 1;";

    if (mysql_query($Sql)) {

        $Msj = "¡Registro Eliminado con exito!";

        $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Admin/Equipos/Registro Eliminado','f_equipos','$Fecha','$eqpo')";

        mysql_query($sql);

        header("Location: calendarioeqp.php?suc=$suc&Msj=$Msj");

    } else {

        $Msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();

        header("Location: calendarioeqp.php?suc=$suc&Msj=$Msj&Error=SI");
    }

}

require_once('bdd.php');

if ($suc === "* Todos") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal as sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor, "
            . "replace(fechas_equipos.observaciones, '
', ' ') observaciones"
            . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "* Todos";

}elseif ($suc === "0") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='0' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Administración";
} elseif ($suc === "1") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='1' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Lcd - Matriz";
} elseif ($suc === "2") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='2' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Lcd - Futura";
} elseif ($suc === "3") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='3' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Lcd - Tepexpan";
} elseif ($suc === "4") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='4' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Lcd - Los Reyes";
} elseif ($suc === "5") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='5' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Lcd - Camarones";
} elseif ($suc === "6") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='6' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Lcd - San Vicente";
} elseif ($suc === "11") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='11' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Ohf - Torre Médica";
} elseif ($suc === "12") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='12' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Ohf - Urgencias";
} elseif ($suc === "13") {
    $sql = "SELECT fechas_equipos.id,fechas_equipos.fecha as inicia,fechas_equipos.status,eqp.nombre as nombre, eqp.sucursal,eqp.id as eqpid,eqp.departamento,fechas_equipos.proveedor as proveedor"
        . " FROM fechas_equipos,eqp WHERE fechas_equipos.id > 0 and fechas_equipos.id_equipo=eqp.id and eqp.sucursal='13' ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();
    $Titulo = "Ohf - Hospitalización";
}
$Obsr = $_REQUEST["Msj"];
$events = $req->fetchAll();
$Stg = substr($Stg, 0, -1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>.:: Calendario Equipos ::.</title>
            <?php require ("./config_add.php"); ?>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="js/jquery.js"></script>
            <link href='fullcalendar-5.4.0/lib/main.css' rel='stylesheet' />
            <script src='fullcalendar-5.4.0/lib/main.js'></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"></link>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <style>
                html,body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial,Helvetica,sans-serif;
                    font-size: 12px;
                }
            </style>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>
            <script>
                $(document).ready(function () {
                    $("#Sucursal1").val("<?= $suc ?>");
                });

                document.addEventListener('DOMContentLoaded', function () {
                    var vista = "";

                    if (localStorage.getItem('Vista') != null) {
                        vista = localStorage.getItem('Vista');
                    } else {
                        vista = localStorage.setItem("Vista", "dayGridMonth");
                    }
                    console.log(vista);
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: vista,
                        editable: true,
                        headerToolbar: {
                            left: 'prev,next today Agregar',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                        },
                        selectable: true,
                        selectHelper: true,
                        weekNumbers: true,
                        expandRows: true,
                        themeSystem: 'standard',
                        select: function (info) {
                            const hoy = new Date();
                            var Fecha = moment(hoy, "YYYY-MM-DD").format('YYYY-MM-DD');
                            $("#id").html("Nuevo registro ::..");
                            $("#busca").val("");
                            $("#eqpid").val("");
                            $("#Titulo").val("").prop('disabled', true);
                            $("#Nombre").val("").prop('disabled', false);;
                            $("#Nombre2").val("").prop('disabled', false);;
                            $("#Proveedor").val("").prop('disabled', false);;
                            $("#Sucursal").val("");
                            $("#Sucursal2").val("").prop('disabled', true);
                            $("#Status").val("En Proceso").prop('disabled', true);
                            $("#Inicia").val(moment(info.start).format('YYYY-MM-DD'));  
                            $("#Observaciones").val("");
                            $("#Agregar").show().prop('disabled', false);
                            $("#Actualizar").hide().prop('disabled', true);
                            $("#Eliminar").hide().prop('disabled', true);
                            $('#exampleModal').modal('toggle');
                        },
                        eventDrop: function (info) { // Cambiamos de fecha via JS
                            Swal.fire({
                                title: "¿ Seguro de cambiar: " + info.event.title + " al dia " + moment(info.event.start.toISOString()).format("YYYY-MM-DD") + " ?",
                                position: "center",
                                icon: "info",
                                showConfirmButton: true,
                                confirmButtonText: "Aceptar",
                                showCancelButton: true,
                                cancelButtonColor: '#d33'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    edit(info);
                                } else {
                                    info.revert();
                                }
                            })
                        },
                        eventResize: function (info) { // si changement de longueur
                            Swal.fire({
                                title: "Seguro de cambiar " + info.event.title + " al dia " + moment(info.event.start.toISOString()).format("YYYY-MM-DD"),
                                position: "center",
                                icon: "info",
                                showConfirmButton: true,
                                confirmButtonText: "Aceptar",
                                showCancelButton: true,
                                cancelButtonColor: '#d33'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    edit(info);
                                } else {
                                    info.revert();
                                }
                            })
                        },
                        customButtons: {
                            Agregar: {
                                text: "Agregar",
                                click: function () {
                                    const hoy = new Date();
                                    var Fecha = moment(hoy, "YYYY-MM-DD").format('YYYY-MM-DD');
                                    $("#id").html("Agregar nuevo registro ::..");
                                    $("#busca").val("");
                                    $("#eqpid").val("");
                                    $("#Titulo").val("").prop('disabled', true);
                                    $("#Nombre").val("");
                                    $("#Nombre2").val("");
                                    $("#Proveedor").val("").prop('disabled', false);;
                                    $("#Sucursal").val("");
                                    $("#Sucursal2").val("").prop('disabled', true);
                                    $("#Status").val("En Proceso").prop('disabled', true);
                                    var FechaInicia = moment(Fecha, "YYYY-MM-DD").format('YYYY-MM-DD');
                                    $("#Inicia").val(FechaInicia);  
                                    $("#Observaciones").val("");
                                    $("#Agregar").show().prop('disabled', false);
                                    $("#Actualizar").hide().prop('disabled', true);
                                    $("#Eliminar").hide().prop('disabled', true);
                                    $('#exampleModal').modal('toggle');
                                }
                            }
                        },
                        eventClick: function (info) {
                            var title = "Registro No. " + info.event.id;
                            $("#id").html(title);
                            $("#busca").val(info.event.id);
                            $("#eqpid").val(info.event.eqpid);
                            $("#Titulo").val(info.event.title).prop('disabled', true);
                            $("#Nombre").val(info.event.extendedProps.nombre).prop('disabled', true);
                            $("#Nombre2").val(info.event.extendedProps.nombre2).prop('disabled', true);
                            $("#Proveedor").val(info.event.extendedProps.proveedor).prop('disabled', false);;
                            $("#Sucursal").val(info.event.extendedProps.sucursal).prop('disabled', true);
                            $("#Sucursal2").val(info.event.extendedProps.sucursal2).prop('disabled', true);
                            $("#Status").val(info.event.extendedProps.status);
                            var FechaInicia = moment(info.event.start, "YYYY-MM-DD").format('YYYY-MM-DD');
                            $("#Inicia").val(FechaInicia);                           
                            $("#Observaciones").val(info.event.extendedProps.observaciones);
                            $("#Agregar").hide().prop('disabled', true);
                            $("#Actualizar").show().prop('disabled', false);
                            $("#Eliminar").show().prop('disabled', false);
                            console.log(info.event.extendedProps.servicio);
                            $("#exampleModal").modal();
                        },
                        eventSources: [{
                                events: [
                                    <?php
                                    foreach ($events as $event):

                                        if($event[departamento]=='Laboratorio'){
                                            $display="#2980B9";
                                        }elseif($event[departamento]=='Rayos_X'){
                                            $display="#6c4675";
                                        }else{
                                            $display="#CD6155";
                                        }
                                        
                                        if($event[sucursal]=='1'){
                                            $sucursal="Mtz";
                                        }elseif($event[sucursal]=='2'){
                                            $sucursal="Ohf";
                                        }elseif($event[sucursal]=='3'){
                                            $sucursal="Tpx";
                                        }elseif($event[sucursal]=='4'){
                                            $sucursal="Rys";
                                        }elseif($event[sucursal]=='5'){
                                            $sucursal="Cam";
                                        }elseif($event[sucursal]=='6'){
                                            $sucursal="Svc";
                                        }elseif($event[sucursal]=='11'){
                                            $sucursal="Tm";
                                        }elseif($event[sucursal]=='12'){
                                            $sucursal="Urg";
                                        }elseif($event[sucursal]=='13'){
                                            $sucursal="Hp";
                                        }else{
                                            $sucursal="SD";
                                        }
                                        $Titulos=ucwords(strtolower($event[nombre]));
                                        ?>
                                        {

                                            id: "<?= $event[id] ?>",
                                            eqpid: "<?= $event[eqpid] ?>",
                                            nombre: "<?= $event[nombre] ?>",
                                            nombre2: "<?= $event[eqpid] ?>",
                                            proveedor: "<?= $event[proveedor] ?>",
                                            title: "<?= $sucursal ?> - <?= $Titulos ?>",
                                            start: "<?= $event[inicia] ?>",
                                            color: "<?= $display ?>",
                                            usr: "<?= $event[usr] ?>",
                                            status: "<?= $event[status] ?>",
                                            extendedProps: {
                                                id: "<?= $event[id] ?>",
                                                eqpid: "<?= $event[eqpid] ?>",
                                                nombre: "<?= $event[nombre] ?>",
                                                nombre2: "<?= $event[eqpid] ?>",
                                                proveedor: "<?= $event[proveedor] ?>",
                                                sucursal: "<?= $sucursal ?>",
                                                sucursal2: "<?= $event[sucursal] ?>",
                                                color: "<?= $display ?>",
                                                status: "<?= $event[status] ?>",
                                                usr: "<?= $event[usr] ?>",
                                                observaciones: "<?= $event[observaciones] ?>"
                                            }
                                        },
                                        <?php
                                    endforeach;
                                    ?>
                                ]
                            }]
                    });
                    calendar.setOption('locale', 'Es');
                    calendar.render();


                    $(".fc-dayGridMonth-button").click(function () {
                        localStorage.setItem("Vista", "dayGridMonth");
                    });
                    $(".fc-timeGridWeek-button").click(function () {
                        localStorage.setItem("Vista", "dayGridWeek");
                    });
                    $(".fc-timeGridDay-button").click(function () {
                        localStorage.setItem("Vista", "timeGridDay");
                    });
                    $(".fc-listMonth-button").click(function () {
                        localStorage.setItem("Vista", "listWeek");
                    });

                    function edit(info) {
                        start = info.event.start;
                        if (info.event.end) {
                            end = info.event.end;
                        } else {
                            end = info.event.start;
                        }

                        id = info.event.id;
                        Event = [];
                        Event[0] = id;
                        Event[1] = moment(start).format("YYYY-MM-DD");
                        Event[2] = moment(end).format("YYYY-MM-DD");
                        $.ajax({
                            url: 'ServiciosCalendarioeqp.php',
                            type: "POST",
                            data: {Event: Event},
                            success: function (rep) {
                                if (rep == 'OK') {
                                    Swal.fire({
                                        title: "¡Registro actualizado con exito!",
                                        position: "center",
                                        icon: "success",
                                        timer: 1200,
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        }
                        );
                    }
                });
            </script>
            <script type="text/javascript">
                $(document).ready(function () {
                    var observaciones = "<?= $Obsr ?>";
                    console.log(observaciones);
                    if (observaciones !== "") {
                        Swal.fire({
                            title: observaciones,
                            position: "center",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            </script>
            <style>

                body {
                    margin: 0px 5px;
                    padding: 0;
                    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                    font-size: 12px;
                    background-color: #FBFBFB;
                }

                #calendar {
                    max-width: 1300px;
                    max-height: 560px;
                    margin: 0 auto;
                    color: #0A5689;
                    background-color: #DEE5EA;
                }

            </style>
    </head>
    <body>
        <div align="center">
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                    <tr style = "background-color: #255B83" class = 'letratitulo'>
                        <td align="center" width="20%" class="ssbm">
                            Sucursal:
                            <select id="Sucursal1" class="letrap" name="suc" onchange="this.form.submit()">
                                <?php
                                $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' and id<>'100' ORDER BY id");
                                while ($Cia = mysql_fetch_array($CiaA)) {
                                    if($Cia[id]==$suc){
                                        echo '<option selected style="font-weight:bold" value="' . $Cia[id] . '">- ' . $aSucursal[$Cia[id]] . ' -</option>';
                                    }else{
                                        echo '<option value="' . $Cia[id] . '">' . $aSucursal[$Cia[id]] .'</option>';
                                    }
                                }

                                if($suc==11){
                                    echo '<option selected style="font-weight:bold" value="11">- OHF - Torre -</option>';
                                    echo '<option value="12">OHF - Urgencia</option>';
                                    echo '<option value="13">OHF - Hospitalizacion</option>';
                                }elseif($suc==12){
                                    echo '<option value="11">OHF - Torre</option>';
                                    echo '<option selected style="font-weight:bold" value="12">- OHF - Urgencia -</option>';
                                    echo '<option value="13">OHF - Hospitalizacion</option>';
                                }elseif($suc==13){
                                    echo '<option value="11">OHF - Torre</option>';
                                    echo '<option value="12">OHF - Urgencia</option>';
                                    echo '<option selected style="font-weight:bold" value="13">- OHF - Hospitalizacion -</option>';
                                }else{
                                    echo '<option value="11">OHF - Torre</option>';
                                    echo '<option value="12">OHF - Urgencia</option>';
                                    echo '<option value="13">OHF - Hospitalizacion</option>';
                                }
                                if ($suc == '* Todos') {
                                    echo "<option selected style='font-weight:bold' value='* Todos'>- * Todos -</select>";
                                }else{
                                    echo "<option value='* Todos'>* Todos</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td align="center">
                                <h2 align="center">..:: Calendario de Mantto. a Equipos - <?= $Titulo ?> ::..</h2>
                        </td>
                        <td width="20%" align="center" class="ssbm">
                            <a class='letrap' style="color:#FADBD8;" href='javascript:window.close()'><b>Salir &nbsp; &nbsp;<i class="fa fa-window-close-o fa-2x" aria-hidden="true" style="color:#D98880;"></i></b></a>
                        </td>
                    </tr>
                </table>
            </form>

        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="color: #FFFFFF; background-color: #F6F6F6;">
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <div class="modal-header" style = "color: #FFFFFF; background-color: #255B83">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <a class="letratitulo" style="align-content: center;"><div id="id"></div></a>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table>
                                <tr>
                                    <td valign="top">
                                        <table>
                                            <tr style="height: 30px;" align="right" class="letrap">
                                                <td  width="30%" class="ssbm">
                                                    <a class="input">Titulo :</a> 
                                                </td>
                                                <td class="ssbm">
                                                    <input class="form-control" id="Titulo" type="text" name="Titulo"></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px" align="right" class="letrap">
                                                <td class="ssbm"><a class="input">Equipo :</a></td>
                                                <td class="ssbm">
                                                <select class='form-control' name='Nombre2' id="Nombre2" required>
                                                    <?php
                                                    $EqpA = mysql_query("SELECT id,nombre,sucursal FROM eqp ORDER BY id");
                                                    while ($Eqps = mysql_fetch_array($EqpA)) {
                                                        if($Eqps[sucursal]=='1'){
                                                            $suceqp="(Mtz)";
                                                        }elseif($Eqps[sucursal]=='2'){
                                                            $suceqp="(Ohf)";
                                                        }elseif($Eqps[sucursal]=='3'){
                                                            $suceqp="(Tpx)";
                                                        }elseif($Eqps[sucursal]=='4'){
                                                            $suceqp="(Rys)";
                                                        }elseif($Eqps[sucursal]=='5'){
                                                            $suceqp="(Cam)";
                                                        }elseif($Eqps[sucursal]=='6'){
                                                            $suceqp="(Svc)";
                                                        }elseif($Eqps[sucursal]=='11'){
                                                            $suceqp="(Tm)";
                                                        }elseif($Eqps[sucursal]=='12'){
                                                            $suceqp="(Urg)";
                                                        }elseif($Eqps[sucursal]=='13'){
                                                            $suceqp="(Hp)";
                                                        }else{
                                                            $suceqp="(SD)";
                                                        }
                                                        echo '<option value="' . $Eqps[id] . '">'.$suceqp.' '.$Eqps[id].' - ' .   $Eqps[nombre] .'</option>';
                                                    }

                                                    ?>
                                                </select>
                                            </tr>
                                            <tr style="height: 30px" class="letrap">
                                                <td width='30%' align="right" class="ssbm">
                                                    <a class="alt">Sucursal : </a>
                                                </td>
                                                <td class="ssbm">
                                                <select class='form-control' id="Sucursal2" name="Sucursal2">
                                                    <?php
                                                    $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' and id<>'100' ORDER BY id");
                                                    while ($Cia = mysql_fetch_array($CiaA)) {
                                                        echo '<option value="' . $Cia[id] . '">' . $aSucursal[$Cia[id]] .'</option>';
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
                                            <tr style="height: 30px" class="letrap">
                                                <td width='30%' align="right" class="ssbm">
                                                    <a class="alt">Proveedor : </a>
                                                </td>
                                                <td class="ssbm">
                                                <select class='form-control' id="Proveedor" name="Proveedor" required>
                                                    <?php
                                                    $ProvA = mysql_query("SELECT id,proveedor from prbVentaMantenimiento ORDER BY id");
                                                    while ($Prov = mysql_fetch_array($ProvA)) {
                                                        echo '<option value="' . $Prov[id] . '">' . $Prov[id] .' - ' . $Prov[proveedor] .'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px;" class="letrap">
                                                <td  style="height: 30px" align="right" class="ssbm">
                                                    <a class="alt">Status : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select class="form-control" id="Status" style="width: 50%" name="Status" required>
                                                        <option style="color:#0071c5;" value='En Proceso'>&#9724; En Proceso</option>
                                                        <option style="color:#27AE60;" value='Terminado'>&#9724; Terminado</option>
                                                        <option style="color:#FE301C;" value='Cancelado'>&#9724; Cancelado</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px" class="letrap">
                                                <td width='30%' align="right" class="ssbm">
                                                    <a class="alt">Inicial : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <input type='date' style="width: 200px;" class='form-control' id="Inicia"  name='Inicia' required></input>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td valign="top" width='30%' align="right" class="ssbm">
                                                    <a class="alt">Observaciones : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <textarea  class="form-control" name="Observaciones" id="Observaciones" type="text" rows="4" cols="85"><?= $Cpo[observaciones] ?></textarea>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <input class="form-control" style="color: #FFFFFF; background-color: #27AE60" type="submit" value='Agregar' id="Agregar" name='boton'></input>

                            <input class="form-control" style="color: #FFFFFF; background-color: #27AE60" type="submit" value="Actualizar" id="Actualizar" name='boton'></input>

                            <input class="form-control" style="color: #FFFFFF; background-color: #C0392B; width: 80px;" type="submit" value="Eliminar" id="Eliminar" name='boton'></input>
                                                        
                            <input type="hidden" name="bt" ></input>
                            <input type="hidden" name="busca" value="<?= $busca ?>" id="busca"></input>
                            <input type="hidden" name="suc" value="<?= $suc ?>"></input>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="MuestraModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <div class="modal-header">
                            <h5 class="modal-title" id="ShowTitle"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="ShowExt0"></div>
                            <div id="ShowExt1"></div>
                            <div id="ShowBody"></div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id='calendar'></div>
    </body>
</html>
<?php
mysql_close();
?>
