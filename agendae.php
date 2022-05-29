<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];

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

if ($_REQUEST[bt] === "Actualizar") {

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
        AgregaAgendaEventos2($Gusr, '/Agenda/Modifica Evento ', "calendario", $Fecha, "$_REQUEST[busca]", $Msj, "agendae.php");

    }

} elseif ($_REQUEST[bt] === "Enviar") {
    
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
        AgregaAgendaEventos($Gusr, '/Agenda/Agrega Evento ', "calendario", $Fecha, $Id, $Msj, "agendae.php");
    }

}
#Variables comunes;
$CpoA = mysql_query("SELECT * FROM calendario WHERE id = $busca");
$Cpo = mysql_fetch_array($CpoA);


if($busca==='NUEVO'){

    $inicia = date("Y-m-d\TH:m:s");

}else{

    $inicia = date("Y-m-d\TH:i", strtotime($Cpo[inicia]));
    
}

require ("config.php");          //Parametros de colores;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Actualizar Informacion Agenda</title>
        <?php require ("./config_add.php"); ?>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    </head>
    <body topmargin="1">
        <script src="./controladores.js"></script>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: <?= $Cpo[titulo] ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Datos principales ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='25%' align="right" class="Inpt">
                                    Id : 
                                </td>
                                <td class="Inpt" width='85%'>
                                    <input type='number' style="width: 60px" class='cinput'  id="id" name='Id' value='<?= $Cpo[id] ?>' disabled></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Titulo : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 410px" maxlength="100" class='cinput'  name='Titulo' value='<?= $Cpo[titulo] ?>' required></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Sucursal : 
                                </td>
                                <td>
                                    <select id="Sucursal"  style="width: 50%" class="cinput" name="Sucursal" required>
                                        <option value="1">LCD-Matriz</option> 
                                        <option value="2">LCD-OHF</option>  
                                        <option value="3">LCD-Tepexpan</option>
                                        <option value="4">LCD-Los Reyes</option>
                                        <option value="5">LCD-Camarones</option>  
                                        <option value="6">LCD-Sn Vicente</option>  
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td  style="height: 30px" align="right" class="Inpt">
                                    Servicio : 
                                </td>
                                <td>
                                    <select class="cinput"  style="width: 50%" id="Importancia" name="Importancia">
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
                            <tr style="height: 30px;">
                                <td  style="height: 30px" align="right" class="Inpt">
                                    Status : 
                                </td>
                                <td>
                                    <select class="cinput" id="Status"  style="width: 50%" name="Status" required>
                                        <option value='1'>En Proceso</option>
                                        <option value='2'>Terminada</option>
                                        <option value='10'>Cancelada</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Fecha/Hora : 
                                </td>
                                <td class="Inpt">
                                    <input type='datetime-local' class='cinput'  name='Inicia' value='<?= $inicia ?>'></input>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="right" class="Inpt">
                                    Observaciones : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Observaciones" type="text" rows="4" cols="65"><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="right" class="Inpt">
                                    Ubicacion : 
                                </td>
                                <td class="Inpt">
                                    <textarea class="cinput" name="Ubicacion" type="text" rows="4" cols="65"><?= $Cpo[ubicacion] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" id="Boton" name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </form>
                    </table>  
                </td>
                <td valign='top' width='45%'>
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
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
                                            <b>&nbsp; Acción</b>
                                        </td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM logagenda
                                                WHERE accion like ('/Agenda/%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 6;";
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
                    <?php
                    //SbmenuInst();
                    ?>
                    <table>
                        <tr>
                            <td>
                                <a class='elim' href='javascript:window.close()'><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>      
        </table>  
    </body>
    <script type="text/javascript">
        $("#Sucursal").val("<?= $Cpo[sucursal] ?>");
        $("#Importancia").val("<?= $Cpo[display] ?>");
        $("#Status").val("<?= $Cpo[status] ?>");
        $("#Generadora").val("<?= $Cpo[generadora] ?>");
        $("#Receptora").val("<?= $Cpo[receptora] ?>");

        if (Number.isNaN(<?= $busca ?>)) {
            $("#Boton").val("NUEVO");
        } else {
            $("#Boton").val("Actualizar");
        }
    </script>
    <?php
    //echo $Cpo[generadora] . $Cpo[receptora];
    ?>
</html>
<?php
mysql_close();
?>
