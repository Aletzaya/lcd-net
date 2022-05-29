<?php
#Librerias
session_start();
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");
$link = conectarse();
require ("config.php");   //Parametros de colores;
$Gusr = $_SESSION[Usr][0];
$Msj = $_REQUEST[Msj];
$busca = $_REQUEST[busca];
$Pb = "SELECT eqp.nombre,vm.proveedor,fe.fecha,fe.observaciones "
        . "FROM fechas_equipos fe LEFT JOIN prbVentaMantenimiento vm "
        . "ON fe.proveedor = vm.id LEFT JOIN eqp ON fe.id_equipo=eqp.id;";

$cCpo = mysql_query($Pb);
While ($Cpo = mysql_fetch_array($cCpo)) {
    $Fecha = explode("-", $Cpo["fecha"]);
    $Fecha = date("Y") . "-" . $Fecha[1] . "-" . $Fecha[2];
    if ($Cpo["destino"] === 0) {
        $Color = "#D98880";
    } else if ($Cpo["destino"] == 1) {
        $Color = "#7FB3D5";
    } else if ($Cpo["id_cia"] == 2) {
        $Color = "#76D7C4";
    } else if ($Cpo["destino"] == 3) {
        $Color = "#F7DC6F";
    } else if ($Cpo["destino"] == 4) {
        $Color = "#E59866";
    } else if ($Cpo["destino"] == 5) {
        $Color = "#99A3A4";
    }
    $Gen = $Cpo["nombre"] . " - " . $Cpo["proveedor"] . " - " . $Cpo["observaciones"];
    $Stg = $Stg . " { title : \" $Gen \", start : \"$Cpo[fecha]\", end : \"$Cpo[fecha]\",color : \"$Color\"},";
}
$Titulo = "Administración";
$Stg = substr($Stg, 0, -1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Catalogo de Zonas ::..</title>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
            <link href='fullcalendar-5.4.0/lib/main.css' rel='stylesheet' />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"></link>
            <script src='fullcalendar-5.4.0/lib/main.js'></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>        
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <style>
                html,body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial,Helvetica,sans-serif;
                    font-size: 14px;
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        editable: false,
                        headerToolbar: {
                            left: 'prev,next today Agregar',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                        },
                        selectable: true,
                        selectHelper: true,
                        weekNumbers: true,
                        customButtons: {
                            Agregar: {
                                text: "Agregar",
                                click: function () {
                                    $('#exampleModal').modal('toggle');
                                }
                            }
                        },
                        dateClick: function (info) {
                            ('#exampleModal').modal();
                        },
                        eventClick: function (info) {
                            $("#ShowTitle").html(info.event.title);
                            $("#ShowExt0").html("Hora inicio: " + info.event.start);
                            //$("#ShowExt1").html("Hora final : " + info.event.end);
                            var json = JSON.stringify(info.event.extendedProps);
                            $("#ShowBody").html();
                            $("#MuestraModal").modal();
                        },
                        eventSources: [{
                                events: [
<?= $Stg ?>
                                ]
                            }]
                    });

                    calendar.setOption('locale', 'Es');
                    calendar.render();
                });

            </script>
            <style>

                body {
                    margin: 0px 5px;
                    padding: 0;
                    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                    font-size: 14px;
                    background-color: #E9F7EF;
                }

                #calendar {
                    max-width: 2000px;
                    max-height: 2000px;
                    margin: 0 auto;
                    color: #0099FF;
                    background-color: #EFFFFF;
                }

            </style>
    </head>
    <body>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div  class="modal-content">
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <a class="alt" style="align-content: center;">..:: Nueva Cita ::..</a>
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
                                            <tr style="height: 40px;" align="right">
                                                <td  width="45%">
                                                    <a class="alt">Titulo :</a> 
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" name="Titulo" required></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 40px" align="right">
                                                <td><a class="alt">Nombre :</a></td>
                                                <td><input name="Nombre" type="text" class="form-control" required></input></td>
                                            </tr>
                                            <tr style="height: 40px" align="right">
                                                <td width='45%' align="right">
                                                    <a class="alt">Tipo de paciente : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <input type='text' maxlength="20" class='form-control'  name='TipoPaciente' value='<?= $Cpo[tipopaciente] ?>'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td width='45%' align="right">
                                                    <a class="alt">Sucursal : </a>
                                                </td>
                                                <td>
                                                    <select id="Sucursal"  style="width: 50%" class="form-control" name="Sucursal" required>
                                                        <option value="0">Texcoco</option>    
                                                        <option value="1">Matriz</option> 
                                                        <option value="2">LCD-OHF</option>  
                                                        <option value="3">LCD-Tepexpan</option>
                                                        <option value="4">LCD-Los Reyes</option>
                                                        <option value="5">LCD-Camarones</option>  
                                                        <option value="6">LCD-Sn Vicente</option>  
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px;">
                                                <td  style="height: 30px" align="right" class="alt">
                                                    <a class="alt">Unidad Generadora : </a>
                                                </td>
                                                <td>
                                                    <select class="form-control" id="Generadora"  style="width: 50%" name="Generadora" required>
                                                        <option value='Texcoco'>Texcoco</option>
                                                        <option value='Futura'>Futura</option>
                                                        <option value='Tepexpan'>Tepexpan</option>
                                                        <option value='Los Reyes'>Los Reyes</option>
                                                        <option value='Camarones'>Camarones</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px;">
                                                <td  style="height: 30px" align="right" class="alt">
                                                    <a class="alt">Unidad Receptora : </a>
                                                </td>
                                                <td>
                                                    <select class="form-control" id="Receptora"  style="width: 50%" name="Receptora" required>
                                                        <option value='Texcoco'>Texcoco</option>
                                                        <option value='Futura'>Futura</option>
                                                        <option value='Tepexpan'>Tepexpan</option>
                                                        <option value='Los Reyes'>Los Reyes</option>
                                                        <option value='Camarones'>Camarones</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  style="height: 30px" align="right" class="alt">
                                                    <a class="alt">Importancia : </a>
                                                </td>
                                                <td>
                                                    <select class="form-control"  style="width: 50%" id="Importancia" name="Importancia" >
                                                        <option value="#F1948A">
                                                            Importante
                                                        </option>    
                                                        <option value="#F0B27A">
                                                            Medio
                                                        </option> 
                                                        <option value="#F4D03F">
                                                            Normal
                                                        </option>  
                                                        <option value="#82E0AA">
                                                            Informativo
                                                        </option>  
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px;">
                                                <td  style="height: 30px" align="right" class="alt">
                                                    <a class="alt">Status : </a>
                                                </td>
                                                <td>
                                                    <select class="form-control" id="Status"  style="width: 50%" name="Status" required>
                                                        <option value='0'>Cita</option>
                                                        <option value='1'>En Proceso</option>
                                                        <option value='2'>Terminada</option>
                                                        <option value='10'>Cancelada</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td width='45%' align="right">
                                                    <a class="alt">Inicial : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <input type='datetime-local' class='form-control'  name='Inicia' value='<?= $inicia ?>' required></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td width='45%' align="right">
                                                    <a class="alt">Final : 
                                                </td>
                                                <td class="Inpt">
                                                    <input type='datetime-local' class='form-control'  name='Finaliza' value='<?= $finaliza ?>' required></input>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td valign="top">
                                        <table>

                                            <tr style="height: 30px">
                                                <td width='45%' align="right">
                                                    <a class="alt">Fecha de cita : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <input type='datetime-local' class='form-control'  name='Fcita' value='<?= $fcita ?>'></input>
                                                </td>
                                            </tr>
                                            <tr style="height: 30px">
                                                <td width='45%' align="right">
                                                    <a class="alt">Fecha de realizacion : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <input type='datetime-local' class='form-control'  name='Frealizacion' value='<?= $frealizacion ?>'></input>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" width='45%' align="right">
                                                    <a class="alt">Tipo de Servicio : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <textarea class="form-control" name="Servicio" type="text" rows="4" cols="45"><?= $Cpo[servicio] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" width='45%' align="right">
                                                    <a class="alt">Observaciones : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <textarea  class="form-control" name="Observaciones" type="text" rows="4" cols="45"><?= $Cpo[observaciones] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" width='45%' align="right">
                                                    <a class="alt">Ubicacion : </a>
                                                </td>
                                                <td class="Inpt">
                                                    <textarea class="form-control" name="Ubicacion" type="text" rows="4" cols="45"><?= $Cpo[ubicacion] ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <input class="form-control" type="submit" value='Agregar' name='bt'></input>
                            <input type="hidden" value='<?= $_REQUEST[busca] ?>' name='busca'></input>
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