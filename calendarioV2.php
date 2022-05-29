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
$Gcia = $_SESSION["Usr"][1];
$Msj = $_REQUEST["Msj"];
$busca = $_REQUEST["busca"];
$suc = $_REQUEST["suc"];

if (isset($_REQUEST["servicio"])) {
    $Servicio = $_REQUEST["servicio"];
}else{
    $Servicio = '*';
}

if (isset($_REQUEST["status"])) {
    $Status = $_REQUEST["status"];
}else{
    $Status = "0";
}

$ini = date("Y-m-d");
$Fecha = date("Y-m-d H:i:s");

if($Gcia==0){
    $generadora='Admin';
}elseif($Gcia==1){
    $generadora='Matriz';
}elseif($Gcia==2){
    $generadora='Futura';
}elseif($Gcia==3){
    $generadora='Tepexpan';
}elseif($Gcia==4){
    $generadora='Los Reyes';
}elseif($Gcia==5){
    $generadora='Camarones';
}elseif($Gcia==6){
    $generadora='San Vicente';
}

if($Servicio=='*'){
    $Servicioftr='';
}else{
    $Servicioftr="and display like '%$Servicio%'";
}

if($Status=='0'){
    $Statusftr='';
}else{
    $Statusftr="and status=$Status";
}

if ($_REQUEST["boton"] === "Agregar") {

    $ini = $_REQUEST[Inicia];
    $sql = "INSERT INTO calendario (titulo,inicia,finaliza,display,servicio,generadora,receptora,fcita,frealizacion,status,sucursal,usr,observaciones,nombre,tipopaciente,ubicacion,maps)"
            . " VALUES ('$_REQUEST[Titulo]','$ini','$fin','$_REQUEST[Importancia]','$_REQUEST[Servicio]',"
            . "'$generadora','$_REQUEST[Receptora]','$Fecha','$freal','1','$_REQUEST[Sucursal]','$Gusr','$_REQUEST[Observaciones]',"
            . "'$_REQUEST[Nombre]','$_REQUEST[TipoPaciente]','$_REQUEST[Ubicacion]','$_REQUEST[Maps]')";
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis " . $sql;
    } else {
        $Id = mysql_insert_id();
        $Msj = "Registro agregado con exito";
        AgregaAgendaEventos2($Gusr, '/Agenda/Agrega Evento ', "calendario", $Fecha, $Id, $Msj, "calendarioV2.php");
    }

} else if ($_REQUEST["boton"] === "Actualizar") {

    $ini = $_REQUEST[Inicia];

    $sql2 = mysql_query("UPDATE calendario SET maps = '' WHERE id =  $_REQUEST[busca] limit 1;");

    $Maps2 = $_REQUEST[Maps];

    $sql = "UPDATE calendario SET titulo = '" . $_REQUEST["Titulo"] . "', inicia = '" . $ini . "', finaliza = '" . $fin
            . "',display='" . $_REQUEST["Importancia"] . "', servicio = '" . $_REQUEST["Servicio"] . "',frealizacion = '" . $freal . "',"
            . "status = '" . $_REQUEST["Status"] . "',sucursal = '" . $_REQUEST["Sucursal"] . "',"
            . "observaciones = '" . $_REQUEST["Observaciones"] . "',nombre = '" . $_REQUEST["Nombre"] . "',"
            . "tipopaciente = '" . $_REQUEST["TipoPaciente"] . "',ubicacion = '" . $_REQUEST["Ubicacion"] . "',maps = '" . $Maps2 . "' WHERE id = " . $_REQUEST["busca"];

    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis " . $sql;
    } else {
        $Msj = "Registro modificado con exito";
        AgregaAgendaEventos2($Gusr, '/Agenda/Modifica Evento ', "calendario", $Fecha, "$_REQUEST[busca]", $Msj, "calendarioV2.php");

    }

}

require_once('bdd.php');

if ($suc === "Admi") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 $Servicioftr $Statusftr ORDER BY id DESC ";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Administración";

} elseif ($suc === "Matriz") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 AND sucursal='1' $Servicioftr $Statusftr ORDER BY id DESC";
    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Matriz";

} elseif ($suc === "Ohf") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 AND sucursal='2' $Servicioftr $Statusftr ORDER BY id DESC";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Futura";

} elseif ($suc === "Tpx") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 AND sucursal='3' $Servicioftr $Statusftr ORDER BY id DESC";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Tepexpan";

} elseif ($suc === "Reyes") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 AND sucursal='4' $Servicioftr $Statusftr ORDER BY id DESC";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Los Reyes";

} elseif ($suc === "Camarones") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 AND sucursal='5' $Servicioftr $Statusftr ORDER BY id DESC";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "Camarones";

} elseif ($suc === "Snvi") {
    $sql = "SELECT id,nombre,tipopaciente,ubicacion,titulo,inicia,finaliza,display,constrain,sucursal,status,"
            . "replace(servicio, '
', ' ') servicio,replace(observaciones, '
', ' ') observaciones,replace(ubicacion, '
', ' ') ubicacion,generadora,receptora,fcita,frealizacion,usr,maps"
            . " FROM calendario WHERE id >= 0 AND sucursal='6' $Servicioftr $Statusftr ORDER BY id DESC";

    $req = $bdd->prepare($sql);
    $req->execute();

    $Titulo = "San Vicente";

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
            <title>.:: Agenda de Estudios ::.</title>
            <?php require ("./config_add.php"); ?>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="js/jquery.js"></script>
            <link href='fullcalendar-5.4.0/lib/main.css' rel='stylesheet' />
            <script src='fullcalendar-5.4.0/lib/main.js'></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"></link>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <style>
                html,body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial,Helvetica,sans-serif;
                    font-size: 12px;
                }
            </style>

            </style>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>
            <script>

                $(document).on('click', '#Ver', function() {
                    $('#ModalVer').modal('show');
                    $("#Tituloeve").val("nazario");
                });

                $(document).on('click', '#agregar_nombres', function() {
                    $('#ModalAgregarNombre').modal('show');
                });

                $(document).ready(function () {
                    $("#Sucursal1").val("<?= $suc ?>");
                    $("#Servicio1").val("<?= $Servicio ?>");
                    $("#Status1").val("<?= $Status ?>");
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
                        initialDate: "<?=$ini?>",
                        eventMaxStack: 5,
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
                        dayMaxEventRows: true, // for all non-TimeGrid views
                        views: {
                            timeGrid: {
                              dayMaxEventRows: 5, // adjust to 6 only for timeGridWeek/timeGridDay
                              dayMaxEvents: 5, 
                            }
                        },
                        select: function (info) {
                            const hoy = new Date();
                            var Fecha = moment(hoy, "YYYY-MM-DD HH:mm").format('YYYY-MM-DDTHH:mm');
                            $("#id").html("Nuevo registro ::..");
                            $("#Titulo").val("");
                            $("#Sucursal").val("");
                            $("#Importancia").val("");
                            $("#Status").val(1).prop('disabled', true);
                            $("#Inicia").val(moment(info.start).format('YYYY-MM-DDTHH:mm'));  
                            $("#Observaciones").val("");
                            $("#Ubicacion").val("");
                            $("#Maps").val("");
                            $("#Agregar").show().prop('disabled', false);
                            $("#Actualizar").hide().prop('disabled', true);
                            $("#Ver").hide().prop('disabled', true);
                            $('#exampleModal').modal('toggle');
                        },
                        eventDrop: function (info) { // Cambiamos de fecha via JS
                            Swal.fire({
                                title: "¿ Seguro de cambiar 1" + info.event.title + " al dia " + moment(info.event.start.toISOString()).format("YYYY-MM-DD HH:mm") + " ?",
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
                                title: "Seguro de cambiar 2" + info.event.title + " al dia " + moment(info.event.start.toISOString()).format("YYYY-MM-DD HH:mm"),
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
                                    var Fecha = moment(hoy, "YYYY-MM-DD HH:mm").format('YYYY-MM-DDTHH:mm');
                                    $("#id").html("Agregar nuevo registro ::..");
                                    $("#Titulo").val("");
                                    $("#Sucursal").val("");
                                    $("#Importancia").val("");
                                    $("#Status").val(1).prop('disabled', true);
                                    $("#Inicia").val(Fecha);
                                    $("#Observaciones").val("");
                                    $("#Ubicacion").val("");
                                    $("#Maps").val("");
                                    $("#Agregar").show().prop('disabled', false);
                                    $("#Actualizar").hide().prop('disabled', true);
                                    $("#Ver").hide().prop('disabled', true);
                                    $('#exampleModal').modal('toggle');
                                }
                            }
                        },
                        eventClick: function (info) {
                            var title = "Registro No. " + info.event.id;
                            var usuario = "Usuario: " + info.event.extendedProps.usr;
                            var generadora = "Generó: " + info.event.extendedProps.generadora;
                            var fechacap = "Captura: " + info.event.extendedProps.fechacita;
                            $("#id").html(title);
                            $("#Usr").html(usuario);
                            $("#busca").val(info.event.id);
                            $("#Titulo").val(info.event.title);
                            $("#Sucursal").val(info.event.extendedProps.sucursal);
                            $("#Importancia").val(info.event.extendedProps.color);
                            $("#Status").val(info.event.extendedProps.status).prop('disabled', false);
                            var FechaInicia = moment(info.event.start, "YYYY-MM-DD HH:mm").format('YYYY-MM-DDTHH:mm');
                            $("#Inicia").val(FechaInicia);
                            $("#Observaciones").val(info.event.extendedProps.observaciones);
                            $("#Ubicacion").val(info.event.extendedProps.ubicacion);
                            $("#Maps").val(info.event.extendedProps.maps);
                            $("#Generadora").html(generadora);
                            $("#Fechacap").html(fechacap);
                            $("#Agregar").hide().prop('disabled', true);
                            $("#Actualizar").show().prop('disabled', false);
                            $("#Ver").show().prop('disabled', false);
                            console.log(info.event.extendedProps.servicio);
                            $("#exampleModal").modal();
                        },
                        eventSources: [{
                                events: [
                                    <?php
                                    foreach ($events as $event):
                                        ?>
                                        {
                                            <?php
                                                if($event[status]=='1'){
                                                    $proc="..:: En Proceso ::..";
                                                }elseif($event[status]=='2'){
                                                    $proc='..:: Terminada ::..';
                                                }elseif($event[status]=='10'){
                                                    $proc='..:: Cancelada ::..';
                                                }
                                            ?>
                                            id: "<?= $event[id] ?>",
                                            title: "<?= $event[titulo] ?>",
                                            start: "<?= $event[inicia] ?>",
                                            color: "<?= $event[display] ?>",
                                            usr: "<?= $event[usr] ?>",
                                            generadora: "<?= $event[generadora] ?>",
                                            fechacita: "<?= $event[fcita] ?>",
                                            extendedProps: {
                                                id: "<?= $event[id] ?>",
                                                sucursal: "<?= $event[sucursal] ?>",
                                                color: "<?= $event[display] ?>",
                                                status: "<?= $event[status] ?>",
                                                observaciones: "<?= $event[observaciones] ?>",
                                                ubicacion: "<?= $event[ubicacion] ?>",
                                                generadora: "<?= $event[generadora] ?>",
                                                fechacita: "<?= $event[fcita] ?>",
                                                usr: "<?= $event[usr] ?>",
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
                        id = info.event.id;
                        Event = [];
                        Event[0] = id;
                        Event[1] = moment(start).format("YYYY-MM-DD HH:mm");
                        $.ajax({
                            url: 'ServiciosCalendario.php',
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
                    /*overflow: hidden;  don't do scrollbars */
                    margin: 0px 5px;
                    padding: 0;
                    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                    font-size: 12px;
                    background-color: #FBFBFB;
                }

              #calendar-container {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                color: #0A5689;
                background-color: #DEE5EA;
              }

              .fc-header-toolbar {
                /*
                the calendar will be butting up against the edges,
                but let's scoot in the header's buttons
                */
                padding-top: 1em;
                padding-left: 1em;
                padding-right: 1em;
              }

                #calendar {
                    max-width: 100%;
                    max-height: 100%;
                    margin: 0 auto;
                    color: #0A5689;
                    background-color: #DEE5EA;
                }

            </style>
            <script language="Javascript">
            function imprimirSeleccion(nombre) {
            var ficha = document.getElementById(nombre);
            var ventimp = window.open(' ', 'popimpr');
            ventimp.document.write( ficha.innerHTML );
            ventimp.document.close();
            ventimp.print( );
            ventimp.close();
            }
            </script>
    </head>
    <body>
        <div align="center">
            <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                <tr style = "background-color: #255B83" class = 'letratitulo'>
                    <td align="center">
                            <h2 align="center">..:: Agenda de Estudios - <?= $Titulo ?> ::..</h2>
                    </td>
                </tr>
            </table>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 0px solid #999;'>  
                    <tr>
                        <td align="left" width="30%" class="letrap">
                            <b>Sucursal:</b>
                            <select id="Sucursal1" class="letrap" style="font-weight:bold" name="suc">
                                <option value="Admi">
                                    Administración *
                                </option>
                                <option value="Matriz">
                                    Matriz
                                </option>
                                <option value="Ohf">
                                    Futura
                                </option>
                                <option value="Tpx">
                                    Tepexpan
                                </option>
                                <option value="Reyes">
                                    Los Reyes
                                </option>
                                <option value="Camarones">
                                    Camarones
                                </option>
                                <option value="Snvi">
                                    San Vicente
                                </option>
                            </select>
                            &nbsp; &nbsp; &nbsp; 
                            <b>Servicio:</b>
                            <select class="letrap" style="font-weight:bold" id="Servicio1" name="servicio">
                                <option style="color:#d50000;" value="*">
                                    * Todos
                                </option>
                                <option style="color:#d50000;" value="d50000">
                                    &#9724; Eco,Holter,Prueba Esfuerzo 
                                </option>
                                <option style="color:#E67C5C;" value="E67C5C">
                                    &#9724; Servicio a Domicilio 
                                </option>    
                                <option style="color:#F4511E;" value="F4511E">
                                    &#9724; Traslado
                                </option> 
                                <option style="color:#f69724;" value="f69724">
                                    &#9724; Colposcopia
                                </option>
                                <option style="color:#33B679;" value="33B679">
                                    &#9724; Densitometria
                                </option>
                                <option style="color:#0B8043;" value="0B8043">
                                    &#9724; Estudio Especial
                                </option>
                                <option style="color:#039BE5;" value="039BE5">
                                    &#9724; Electroencefalograma
                                </option>
                                <option style="color:#3F51B5;" value="3F51B5">
                                    &#9724; Resonancia
                                </option>
                                <option style="color:#7986CB;" value="7986CB">
                                    &#9724; Mastografia
                                </option>
                                <option style="color:#8E24AA;" value="8E24AA">
                                    &#9724; PCR
                                </option>
                                <option style="color:#616161;" value="616161">
                                    &#9724; Tomografia
                                </option>
                            </select>
                            &nbsp; &nbsp; &nbsp; 

                            <b>Status:</b>
                            <select class="letrap" style="font-weight:bold" id="Status1" name="status">
                                <option value='0'>* Todos</option>
                                <option value='1'>En Proceso</option>
                                <option value='2'>Terminada</option>
                                <option value='10'>Cancelada</option>
                            </select>

                            &nbsp; &nbsp; &nbsp; 
                            <input type="submit" class="letrap" name="bt" value="Enviar"></input>
                        </td>
                        <td width="20%" align="right" class="letrap">
                            <b>Salir</b> <a class='letrap' href='javascript:window.close()'><b><i class="fa fa-window-close-o fa-2x" aria-hidden="true" style="color:#D98880;"></i></b></a>
                        </td>
                    </tr>

                </table>
            </form>
        </div>
<!-- Modal Editar -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="color: #FFFFFF; background-color: #F6F6F6;">
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <div class="modal-header" style = "color: #FFFFFF; background-color: #255B83">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <a class="letratitulo" style="align-content: center;"><div id="id"></div></a>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <table border='0' style="width: 100%;">
                                <tr>
                                    <td valign="top">
                                        <table border='0' style="width: 95%;">
                                            <tr style="height: 30px;" align="right" class="letrap">
                                                <td  width="20%" class="ssbm">
                                                    <a class="input">* Titulo :</a> 
                                                </td>
                                                
                                                <td class="ssbm" width="80%">
                                                    <input class="form-control" id="Titulo" type="text" name="Titulo" required></input>
                                                </td>
                                            </tr>
                                            
                                            <tr style="height: 30px" class="letrap">
                                                <td width='20%' align="right" class="ssbm">
                                                    <a class="input">* Sucursal : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select id="Sucursal"  style="width: 50%" class="form-control" name="Sucursal" required>
                                                        <option value="1">LCD-Matriz</option> 
                                                        <option value="2">LCD-OHF</option>  
                                                        <option value="3">LCD-Tepexpan</option>
                                                        <option value="4">LCD-Los Reyes</option>
                                                        <option value="5">LCD-Camarones</option>  
                                                        <option value="6">LCD-Sn Vicente</option>  
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td  style="height: 30px" align="right" class="ssbm">
                                                    <a class="input">* Servicio : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select class="form-control"  style="width: 50%" id="Importancia" name="Importancia" required>
                                                        <option style="color:#d50000;" value="#d50000">
                                                            &#9724; Eco,Holter,Prueba Esfuerzo 
                                                        </option>
                                                        <option style="color:#E67C5C;" value="#E67C5C">
                                                            &#9724; Servicio a Domicilio 
                                                        </option>    
                                                        <option style="color:#F4511E;" value="#F4511E">
                                                            &#9724; Traslado
                                                        </option> 
                                                        <option style="color:#f69724;" value="#f69724">
                                                            &#9724; Colposcopia
                                                        </option>
                                                        <option style="color:#33B679;" value="#33B679">
                                                            &#9724; Densitometria
                                                        </option>
                                                        <option style="color:#0B8043;" value="#0B8043">
                                                            &#9724; Estudio Especial
                                                        </option>
                                                        <option style="color:#039BE5;" value="#039BE5">
                                                            &#9724; Electroencefalograma
                                                        </option>
                                                        <option style="color:#3F51B5;" value="#3F51B5">
                                                            &#9724; Resonancia
                                                        </option>
                                                        <option style="color:#7986CB;" value="#7986CB">
                                                            &#9724; Mastografia
                                                        </option>
                                                        <option style="color:#8E24AA;" value="#8E24AA">
                                                            &#9724; PCR
                                                        </option>
                                                        <option style="color:#616161;" value="#616161">
                                                            &#9724; Tomografia
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px;" class="letrap">
                                                <td  style="height: 30px" align="right" class="ssbm">
                                                    <a class="alt">Status : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select class="form-control" id="Status"  style="width: 50%" name="Status" required>
                                                        <option value='1'>En Proceso</option>
                                                        <option value='2'>Terminada</option>
                                                        <option value='10'>Cancelada</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px" class="letrap">
                                                <td width='20%' align="right" class="ssbm">
                                                    <a class="alt">Fecha/Hora : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <input style="width: 50%" type='datetime-local' class='form-control' id="Inicia" name='Inicia' required></input>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td valign="top" width='20%' align="right" class="ssbm">
                                                    <a class="alt">Observaciones : </a>
                                                </td>
                                                <td  class="ssbm">
                                                    <textarea  class="form-control" name="Observaciones" id="Observaciones" type="text" rows="3" cols="45"><?= $Cpo[observaciones] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td valign="top" width='20%' align="right" class="ssbm">
                                                    <a class="alt">Ubicacion : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <textarea class="form-control" id="Ubicacion" name="Ubicacion" type="text" rows="3" cols="45"><?= $Cpo[ubicacion] ?></textarea>
                                                </td>
                                            </tr>
                                                                                    
                                            <tr style="height: 30px;" align="right" class="letrap">
                                                <td  width="20%" class="ssbm">
                                                    <a class="alt">Maps :</a> 
                                                </td>
                                                <td class="ssbm" width="80%">
                                                    <textarea class="form-control" id="Maps" name="Maps" type="text" rows="2" cols="45" disabled><?= $Cpo[maps] ?></textarea>
                                                </td>
                                            </tr>

                                            <tr style="height: 30px;" align="right" class="letrap">
                                                <td  width="100%" align="right" colspan="2">
                                                    <input class="form-control" style="color: #FFFFFF; background-color: #C0392B; width: 80px;" type="button" value="Ver" id="Ver" name='boton'></input>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </div>

                        <div class="modal-footer">

<!--
                            <h5 class="letrap" id="Maps1">
                                <a class="letrap" style="align-content: center;"><div id="Maps1"></div></a>
                            </h5>
-->
                            <input class="form-control" style="color: #FFFFFF; background-color: #27AE60" type="submit" value='Agregar' id="Agregar" name='boton'></input>

                            <input class="form-control" style="color: #FFFFFF; background-color: #27AE60" type="submit" value="Actualizar" id="Actualizar" name='boton'></input>

                            &nbsp; &nbsp; &nbsp; &nbsp;

                            <h5 class="letrap" id="Generadora">
                                <a class="letrap" style="align-content: center;"><div id="Generadora"></div></a>
                            </h5>

                            &nbsp; &nbsp; &nbsp; &nbsp;

                            <h5 class="letrap" id="Usr">
                                <a class="letrap" style="align-content: center;"><div id="Usr"></div></a>
                            </h5>

                            &nbsp; &nbsp; &nbsp; &nbsp;

                            <h5 class="letrap" id="Fechacap">
                                <a class="letrap" style="align-content: center;"><div id="Fechacap"></div></a>
                            </h5>

                            &nbsp; &nbsp; &nbsp; &nbsp;

                        <!--    <div id="div_print">División que se imprimirá cuando se pulse el enlace.</div>-->
                            <a href="javascript:imprimirSeleccion('div_print')" ><i class="fa fa-print fa-lg" aria-hidden="true"></i></a>

                            <input type="hidden" name="bt" ></input>
                            <input type="hidden" name="busca" id="busca"></input>
                            <input type="hidden" id="suc" name='suc' value="<?=$suc?>"></input>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!-- Modal Ver -->
        <div class="modal fade" id="ModalVer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="color: #FFFFFF; background-color: #F6F6F6;">
                        <div class="modal-header" style = "color: #FFFFFF; background-color: #255B83">
                            <h5 class="modal-title" id="ModalVer">
                                <a class="letratitulo" style="align-content: center;"><div id="id"></div></a>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <table border='0' style="width: 100%;">
                                <tr>
                                    <td valign="top">
                                        <table border='0' style="width: 95%;">
                                            <tr style="height: 30px;" align="right" class="letrap">
                                                <td  width="100%" class="ssbm">
                                                    <h5 class="letrap" id="Titulo">
                                                        <a class="letrap" style="align-content: center;"><div id="Titulo"></div></a>
                                                    </h5>
                                            </tr>
                                            <tr style="height: 30px;" align="right" class="letrap">
                                                <td  width="100%" class="ssbm">
                                                    <h5 class="letrap" id="Usr">
                                                        <a class="letrap" style="align-content: center;"><div id="Usr"></div></a>
                                                    </h5>
                                            </tr>
                                            <!--
                                            <tr style="height: 30px" class="letrap">
                                                <td width='20%' align="right" class="ssbm">
                                                    <a class="input">Sucursal : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select id="Sucursal"  style="width: 50%" class="form-control" name="Sucursal">
                                                        <option value="1">LCD-Matriz</option> 
                                                        <option value="2">LCD-OHF</option>  
                                                        <option value="3">LCD-Tepexpan</option>
                                                        <option value="4">LCD-Los Reyes</option>
                                                        <option value="5">LCD-Camarones</option>  
                                                        <option value="6">LCD-Sn Vicente</option>  
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td  style="height: 30px" align="right" class="ssbm">
                                                    <a class="input">Servicio : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select class="form-control"  style="width: 50%" id="Importancia" name="Importancia" >
                                                        <option style="color:#d50000;" value="#d50000">
                                                            &#9724; Eco,Holter,Prueba Esfuerzo 
                                                        </option>
                                                        <option style="color:#E67C5C;" value="#E67C5C">
                                                            &#9724; Servicio a Domicilio 
                                                        </option>    
                                                        <option style="color:#F4511E;" value="#F4511E">
                                                            &#9724; Traslado
                                                        </option> 
                                                        <option style="color:#f69724;" value="#f69724">
                                                            &#9724; Colposcopia
                                                        </option>
                                                        <option style="color:#33B679;" value="#33B679">
                                                            &#9724; Densitometria
                                                        </option>
                                                        <option style="color:#0B8043;" value="#0B8043">
                                                            &#9724; Estudio Especial
                                                        </option>
                                                        <option style="color:#039BE5;" value="#039BE5">
                                                            &#9724; Electroencefalograma
                                                        </option>
                                                        <option style="color:#3F51B5;" value="#3F51B5">
                                                            &#9724; Resonancia
                                                        </option>
                                                        <option style="color:#7986CB;" value="#7986CB">
                                                            &#9724; Mastografia
                                                        </option>
                                                        <option style="color:#8E24AA;" value="#8E24AA">
                                                            &#9724; PCR
                                                        </option>
                                                        <option style="color:#616161;" value="#616161">
                                                            &#9724; Tomografia
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px;" class="letrap">
                                                <td  style="height: 30px" align="right" class="ssbm">
                                                    <a class="alt">Status : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <select class="form-control" id="Status"  style="width: 50%" name="Status" required>
                                                        <option value='1'>En Proceso</option>
                                                        <option value='2'>Terminada</option>
                                                        <option value='10'>Cancelada</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px" class="letrap">
                                                <td width='20%' align="right" class="ssbm">
                                                    <a class="alt">Fecha/Hora : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <input style="width: 50%" type='datetime-local' class='form-control' id="Inicia" name='Inicia' required></input>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td valign="top" width='20%' align="right" class="ssbm">
                                                    <a class="alt">Observaciones : </a>
                                                </td>
                                                <td  class="ssbm">
                                                    <textarea  class="form-control" name="Observaciones" id="Observaciones" type="text" rows="3" cols="45"><?= $event[observaciones] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr class="letrap">
                                                <td valign="top" width='20%' align="right" class="ssbm">
                                                    <a class="alt">Ubicacion : </a>
                                                </td>
                                                <td class="ssbm">
                                                    <textarea class="form-control" id="Ubicacion" name="Ubicacion" type="text" rows="3" cols="45"><?= $event[ubicacion] ?></textarea>
                                                </td>
                                            </tr>
                                        -->
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--
                        <div class="modal-footer">
                            <input class="form-control" style="color: #FFFFFF; background-color: #27AE60" type="submit" value='Agregar' id="Agregar" name='boton'></input>

                            <input class="form-control" style="color: #FFFFFF; background-color: #27AE60" type="submit" value="Actualizar" id="Actualizar" name='boton'></input>

                            <input class="form-control" style="color: #FFFFFF; background-color: #C0392B; width: 80px;" type="submit" value="Editar" id="Editar" name='boton'></input>

                            <input type="hidden" name="bt" ></input>
                            <input type="hidden" id="busca" name='busca'></input>
                            </a>
                        </div>
                    -->
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

        <div id="div_print"></div>
    </body>
</html>
<?php
mysql_close();
?>
