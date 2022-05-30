<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
$link = conectarse();

if (isset($_REQUEST[Venta])) {
    $_SESSION['cVarVal'][0] = $_REQUEST[Venta];
}

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Vta = $_SESSION[cVarVal][0];
$busca = $_REQUEST[busca];
$op = $_REQUEST[op];
$MsjDatos = $_REQUEST[MsjDatos];
$Msj = $_REQUEST[Msj];
$Pdf = $_REQUEST[Pdf];
$Arch = $_REQUEST[Arch];
$c = mysql_query("SELECT * FROM ctnvas WHERE venta='$Vta' AND usr='$Gusr';");
$cc = mysql_fetch_array($c);
if ($cc == '') {
    mysql_query("INSERT INTO ctnvas (usr,venta) VALUES ('$Gusr',$Vta)");
}

if ($_REQUEST[op] == "br") {  // Borramos lo que esta en su nueva cita
    $Fecha = date("Y-m-d");
    $Horae = "15:00";

    $cSql = "DELETE FROM ctdnvas WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctdnvas');

    $cSql = "DELETE FROM ctnvas WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
    if ($Gcia == 1 OR $Gcia == 0) {
        $campo = "entmos";
    } elseif ($Gcia == 2) {
        $campo = "entmosf";
    } elseif ($Gcia == 3) {
        $campo = "entmost";
    } elseif ($Gcia == 4) {
        $campo = "entmoslr";
    } elseif ($Gcia == 5) {
        $campo = "entmoscms";
    }
    if ($Gcia > 1) {
        $cSql = "INSERT INTO ctnvas (inst,lista,usr,venta,servicio,fechae,horae,fechar,$campo,entmos)
               VALUES
               ( 0 ,'','$Gusr','$Vta','Ordinaria','$Fecha','$Horae',now(),1,0)";
    } else {
        $cSql = "INSERT INTO ctnvas (inst,lista,usr,venta,servicio,fechae,horae,fechar,$campo)
               VALUES
               ( 0 ,'','$Gusr','$Vta','Ordinaria','$Fecha','$Horae',now(),1)";
    }
    EjecutaSql($cSql, 'ctnvas');
} elseif (isset($_REQUEST[Institucion])) {
    $prd = explode(" ", $_REQUEST[Institucion]);

    $InsA = mysql_query("SELECT lista FROM inst WHERE institucion='$prd[0]'");
    $Ins = mysql_fetch_array($InsA);

    $cSql = "UPDATE ctnvas SET inst='$prd[0]',lista='$Ins[lista]' 
                WHERE usr='$Gusr' AND venta='$Vta'";
    //echo $cSql;
    EjecutaSql($cSql, 'ctnvas');
    $Precio = "est.lt" . $Ins[lista];
    $cSql = "UPDATE est,ctdnvas SET ctdnvas.precio = $Precio 
                WHERE  ctdnvas.usr='$Gusr' AND ctdnvas.venta='$Vta' AND ctdnvas.estudio=est.id";
    EjecutaSql($cSql, 'ctnvas');
} elseif (isset($_REQUEST[TodoPorCorreo])) {

    $cSql = "UPDATE ctnvas SET todoporcorreo='$_REQUEST[TodoPorCorreo]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
} elseif (isset($_REQUEST[Servicio])) {

    $cSql = "UPDATE ctnvas SET servicio='$_REQUEST[Servicio]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
} elseif ($_REQUEST[Boton] == 'Guardar') {

    $cSql = "UPDATE ctnvas SET medicon='$_REQUEST[NombreMedico]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
} elseif ($_REQUEST[Boton] == 'Guarda') {

    $cSql = "UPDATE ctnvas SET paciented='$_REQUEST[Paciented]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
} elseif (isset($_REQUEST[PacienteJ])) {
    $prd = explode(" ", $_REQUEST[PacienteJ]);
    $cSql = "UPDATE ctnvas SET cliente='$prd[0]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
    $Obr = "SELECT observaciones FROM cli where cliente='$prd[0]'";
    $s = mysql_query($Obr);
    $Dobr = mysql_fetch_array($s);
    $Obsr = $Dobr[observaciones];
} elseif (isset($_REQUEST[Paciente])) {
    $cSql = "UPDATE ctnvas SET cliente='$_REQUEST[Paciente]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
    $Obr = "SELECT observaciones FROM cli where cliente='$_REQUEST[Paciente]'";
    $s = mysql_query($Obr);
    $Dobr = mysql_fetch_array($s);
    $Obsr = $Dobr[observaciones];
} elseif ($_REQUEST[Boton] == 'Enviar') {

    $cSql = "UPDATE ctnvas SET observaciones='$_REQUEST[Observaciones]',diagmedico='$_REQUEST[Diagmedico]',
                receta='$_REQUEST[Receta]',fechar='$_REQUEST[Fechar]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
    $MsjDatos = "Registro de datos realizado c/exito";
    header("Location: cotizaorden.php?MsjDatos=$MsjDatos&cVarVal=$Vta");
} elseif ($_REQUEST[Boton] == 'Actualizar') {

    if ($_REQUEST[op] == "desc9") {
        $cSql = "UPDATE ctdnvas SET descuento='$_REQUEST[Descuento]',razon='$_REQUEST[Razon]' "
                . "WHERE usr='$Gusr' and venta='$Vta'";
    } else {
        $cSql = "UPDATE ctdnvas SET descuento='$_REQUEST[Descuento]',razon='$_REQUEST[Razon]' "
                . "WHERE usr='$Gusr' and venta='$Vta' AND estudio='$_REQUEST[IdEstudio]' LIMIT 1";
    }
    EjecutaSql($cSql, "ctdnvas");
} elseif (isset($_REQUEST[Medico])) {

    mysql_query("UPDATE ctnvas SET medicon='' WHERE usr='$Gusr' and venta='$Vta'");
    $Pos = strpos($_REQUEST[Medico], " "); //Buscon si en lo k se va a regresar trae ya un valor predef
    if ($Pos > 0) {
        $cId = substr($_REQUEST[Medico], 0, $Pos);
    } else {
        $cId = $_REQUEST[Medico];
    }//echo $cId;
    $c = mysql_query("SELECT medico FROM med WHERE id = '$cId'");
    $cc = mysql_fetch_array($c);
    $cSql = "UPDATE ctnvas SET medico='$cc[medico]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
    $Obr = "SELECT observaciones FROM med where medico='$cc[medico]'";
    $s = mysql_query($Obr);
    $Dobr = mysql_fetch_array($s);
    $Obsr = $Dobr[observaciones];
} elseif ($op == "Eli") { //Elimina     $Medico=strtoupper($Medico);
    $cSql = "DELETE FROM ctdnvas WHERE usr='$Gusr' and venta='$Vta' and estudio='$busca' limit 1";
    EjecutaSql($cSql, 'ctnvas');
} elseif ($op == "desc1") { //Elimina     $Medico=strtoupper($Medico);
    $cSql = "UPDATE ctdnvas SET descuento='$_REQUEST[Descuento]',razon='$_REQUEST[Razon]' WHERE usr='$Gusr' and venta='$Vta' AND estudio='$_REQUEST[IdEstudio]' LIMIT 1";
    EjecutaSql($cSql, 'ctdnvas');
} elseif (isset($_REQUEST[Entregaen])) {

    $cSql = "UPDATE ctnvas SET entregaen='$_REQUEST[Entregaen]' WHERE usr='$Gusr' AND venta='$Vta' LIMIT 1";

    if ($_REQUEST[Entregaen] == 'A domicilio') {

        $Msj = "Favor de verificar la direccion en el campo de <b>Observaciones</b>";

        $CliA = mysql_query("SELECT cli.direccion,cli.localidad FROM cli,ctnvas 
               WHERE ctnvas.usr='$Gusr' and ctnvas.venta='$Vta' AND  ctnvas.cliente=cli.cliente");
        $Cli = mysql_fetch_array($CliA);

        $Datos = "Entrega de resultados en Calle: " . $Cli[direccion] . " Col: " . $Cli[localidad];

        $HeA = mysql_query("SELECT observaciones FROM ctnvas WHERE usr='$Gusr' and venta='$Vta' LIMIT 1");
        $He = mysql_fetch_array($HeA);

        if ($He[observaciones] == '') {
            $cSql = "UPDATE ctnvas SET entregaen='$_REQUEST[Entregaen]',observaciones='$Datos' WHERE usr='$Gusr' AND venta='$Vta' LIMIT 1";
        }
    }

    EjecutaSql($cSql, 'ctdnvas');
} elseif ($op == "desc9") { //Elimina     $Medico=strtoupper($Medico);
    $cSql = "UPDATE ctdnvas SET descuento='$_REQUEST[Descuento]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctdnvas');
    $cSql = "UPDATE ctnvas SET razon='$_REQUEST[Razon]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctdnvas');
} elseif ($op == "borrainst") {
    $cSql = "UPDATE ctnvas SET inst = 0, lista = '' WHERE usr='$Gusr' and venta=$Vta";
    EjecutaSql($cSql, 'ctdnvas');
} elseif ($_REQUEST[Boton] == 'Registra pago') {
    $cSql = "UPDATE ctnvas SET abono='$_REQUEST[Abono]',tpago='$_REQUEST[Tpago]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctdnvas');
} elseif (isset($_REQUEST[IdEstudio]) OR $busca <> '') {
    $prd = explode(" ", $_REQUEST[IdEstudio]);
    if ($busca <> '') {
        $Pos = strpos($busca, " "); //Buscon si en lo k se va a regresar trae ya un valor predef
        if ($Pos > 0) {
            $cId = substr($busca, 0, $Pos);
        } else {
            $cId = $busca;
        }
    } else {
        $cId = $prd[0];
    }
    $sql = "SELECT id,estudio,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10,lt11,
               lt12,lt13,lt14,lt15,lt15,lt15,lt16,lt17,lt18,lt19,lt20,lt22,lt21,lt23
               FROM est WHERE id='$cId'";
    $LtaA = mysql_query($sql);

    $Lta = mysql_fetch_array($LtaA);

    if ($Lta[id] <> '') {
        $Hsql = "SELECT lista,inst FROM ctnvas WHERE ctnvas.usr='$Gusr' AND ctnvas.venta='$Vta'";
        $HeA = mysql_query($Hsql);
        $He = mysql_fetch_array($HeA);

        $Inst = "SELECT descuento FROM inst WHERE institucion = $He[inst]";
        $ins = mysql_query($Inst);
        $rlt = mysql_fetch_array($ins);

        $Precio = 'lt' . $He[lista];
        $Precio = $Lta[$Precio];

        $cSql = "INSERT INTO ctdnvas (usr,venta,estudio,precio,descuento) VALUES 
                    ('$Gusr','$Vta','$Lta[estudio]','$Precio',$rlt[descuento])";
        EjecutaSql($cSql, 'ctdnvas');
        $Msj = "Registro ingresado con exito!!!";
    } else {
        $Msj = "Estudio: $_REQUEST[Estudio] no existente";
    }
    header("Location: cotizaorden.php?Msj=$Msj&cVarVal=$Vta");
} elseif ($_REQUEST[Boton] == 'Cambia fecha/entrega') {

    $cSql = "UPDATE ctnvas SET fechae='$_REQUEST[Fechae]',horae='$_REQUEST[Horae]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctdnvas');

    $MsjFecHra = "Cambio de fecha/hora realizado c/exito";
} elseif ($_REQUEST[Boton] == 'Responsable') {

    $cSql = "UPDATE ctnvas SET responsableco='$_REQUEST[Responsableco]' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctdnvas');

    $MsjFecHra = "Cambio de fecha/hora realizado c/exito";
} elseif ($_REQUEST[Boton] == 'Genera Cotizacion') {

    $FolOt = cZeros(IncrementaFolio('otfolio'), 5);

    $FolCja = cZeros(IncrementaFolio('cajafolio'), 5);

    $Fecha = date("Y-m-d");
    $Hora1 = date("H:i");
//$Hora2 = strtotime("-60 min",strtotime($Hora1));
//$hora  = date("H:i",$Hora2);

    $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA = mysql_query("SELECT round(sum(precio*(1-descuento/100)),0) as importe FROM ctdnvas WHERE usr='$Gusr' and venta='$Vta'");
    $Otd = mysql_fetch_array($OtdA);

    $OtA = mysql_query("SELECT * FROM ctnvas WHERE usr='$Gusr' AND venta='$Vta'");
    $Ot = mysql_fetch_array($OtA);

    $Abono = $Ot[abono];

    $cPag = 'No';
    if ($Abono + .5 >= $Otd[importe]) {
        $cPag = 'Si';
    }

    $cSql = "INSERT INTO ct
          (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,
          receta,importe,descuento,medicon,horae,suc,folio)
          VALUES
          ('$Ot[cliente]','$Fecha','$hora','$Ot[medico]','$Ot[fechar]','$Ot[fechae]','$Ot[inst]',
          '$Ot[diagmedico]','$Ot[observaciones]','$Ot[servicio]','$Gusr','$Ot[receta]',$Otd[importe],
          '$Ot[razon]','$Ot[medicon]','$Ot[horae]','$Gcia','$FolOt')";

    EjecutaSql($cSql, 'ctdnvas');

    $Id = mysql_insert_id();

    $lUpA = mysql_query("SELECT ctdnvas.estudio as idestudio,ctdnvas.precio,ctdnvas.descuento,est.estudio,est.depto
            FROM est,ctdnvas
            WHERE ctdnvas.usr='$Gusr' AND ctdnvas.venta='$Vta' AND ctdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados

    $lBd = false;

    while ($lUp = mysql_fetch_array($lUpA)) {

        $cSql = "INSERT INTO ctd (id,estudio,precio,descuento,status)
                 VALUES
                 ($Id,'$lUp[estudio]','$lUp[precio]','$lUp[descuento]','$Depto')";

        EjecutaSql($cSql, 'ctdnvas');


        if ($lUp[depto] == 2) {                  // Si es que es de radiologia se crea un archivo en base a un formato del word y lo copio
            $FilWord = strtolower("informes/" . $lUp[estudio] . ".doc");
            $FilOut = strtolower("textos/" . $lUp[estudio] . $Id . ".doc");

            if (file_exists($FilWord)) {
                copy($FilWord, $FilOut);
            }
        }
    }

    if ($Abono > 0) {

        if ($Abono > $Otd[importe]) {
            $Abono = $Otd[importe];
        }

        $Tpago = $_REQUEST[Tpago];
        $cSql = "INSERT INTO cja (orden,fecha,hora,usuario,importe,tpago,suc,folio)
                  VALUES
    		  ($Id,'$Fecha','$hora','$Gusr','$Abono','$Ot[tpago]','$Gcia','$FolCja')";

//echo $cSql;
//break;
        EjecutaSql($cSql, 'ctdnvas');
    }

    header("Location: cotizaorden.php?Pdf=si&op=br&Arch=$Id");
} elseif ($_REQUEST[Envios] == 'ck') {
    $Sql = "UPDATE ctnvas SET $_REQUEST[Cmpo] = '$_REQUEST[status]' WHERE usr = '$Gusr' AND venta=$_REQUEST[Venta]";
    EjecutaSql($Sql, "ctnvas");
} elseif ($_REQUEST[Medi] == "si") {
    $cSql = "UPDATE ctnvas SET medico='' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
} elseif ($_REQUEST[Cli] == "si") {
    $cSql = "UPDATE ctnvas SET cliente='' WHERE usr='$Gusr' and venta='$Vta'";
    EjecutaSql($cSql, 'ctnvas');
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$Mnu = $_SESSION[Mnu];

$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
//echo "El valor de retornar es $RetSelec";
$Icon = "#24AAA7";

#Variables comunes;
$Titulo = "Generacion de ordenes";
$op = $_REQUEST[op];
//$Msj       = $_REQUEST[Msj];
$Id = 46;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);
$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$HeSql = "SELECT ctnvas.inst,ctnvas.lista,ctnvas.cliente,ctnvas.medico,ctnvas.todoporcorreo,ctnvas.paciented,ctnvas.entmoscms,
        ctnvas.receta,ctnvas.fechar,ctnvas.diagmedico,ctnvas.observaciones,ctnvas.horae, ctnvas.servicio,ctnvas.fechae,
        cli.nombrec,cli.mail,cli.numveces,cli.programa, med.nombrec as nombremed,med.mail as mailmed,ctnvas.medicon,ctnvas.abono
        ,ctnvas.tpago, cli.observaciones as notacliente,ctnvas.entmos, ctnvas.tentregainst,ctnvas.tentregamed,ctnvas.tentregamost
        ,ctnvas.entemailpac,ctnvas.entemailmed,ctnvas.entemailinst, ctnvas.entwhatpac,ctnvas.entwhatmed,ctnvas.entwhatinst,
        ctnvas.tentregamost,ctnvas.entmost,ctnvas.entmost,ctnvas.entmoslr, ctnvas.entmosf,ctnvas.idprocedencia,ctnvas.responsableco 
        FROM ctnvas 
        LEFT JOIN cli ON ctnvas.cliente=cli.cliente 
        LEFT JOIN med ON ctnvas.medico=med.id 
        WHERE ctnvas.usr='$Gusr' AND ctnvas.venta='$Vta'";
//echo $HeSql;
$HeA = mysql_query($HeSql);
$He = mysql_fetch_array($HeA);
$OtA = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM ctdnvas WHERE usr='$Usr' and venta='$Vta'");
$Ot = mysql_fetch_array($OtA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>.:: Cotizacion ::.</title>
        <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="jquery-ui/jquery-ui.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <script type="text/javascript">
            if ("<?= $Obsr ?>") {
                alert("<?= $Obsr ?>");
            }
            if ("<?= $Pdf ?>") {
                window.open("impot.php?busca=<?= $Arch ?>", "ventana1", "width=800,height=1000,scrollbars=NO")
            }
        </script>
        <?php
        encabezados();
        menu($Gmenu,$Gusr);
        ?>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
            <tr>
                <td height="380" valign="top">
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
                        <tr bgcolor="#D5D8DC">
                            <td width='1%' align='light'>
                            </td>
                            <td width='49%' align='light'>
                                <a class="content5">
                                    <b>
                                        Captura Cotizacion
                                    </b>
                                </a>
                            </td>
                            <td width='7%' align='center'>
                                <a class='edit' href='?op=br'>Borrar todo <i class="fa fa-trash fa-2x" style="color: #EC7063" aria-hidden="true"></i></a> 
                            </td>
                            <?php
                            if ($Vta == 1) {
                                echo '<td width="3%" align="center"><span style="color:' . $Icon . '" class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="1" style="color:#154360"></i><strong  style="color:#154360" class="fa-stack-1x" >1</strong></span>&nbsp; </td>';
                            } else {
                                echo '<td width="3%" align="center"><a style="color:' . $Icon . '" href="?Venta=1" title="Venta 1"><span class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x"  id="1" style="color:' . $Icon . '"></i><strong class="fa-stack-1x">1</strong></span></a>&nbsp; </td>';
                            }

                            if ($Vta == 2) {
                                echo '<td width="3%" align="center"><span style="color:' . $Icon . '" class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="2" style="color:#154360"></i><strong class="fa-stack-1x" style="color:#154360">2</strong></span></td>';
                            } else {
                                echo '<td width="3%" align="center"><a  href="?Venta=2" title="Venta 2" style="color:' . $Icon . '"><span class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="2" style="color:' . $Icon . '"></i><strong class="fa-stack-1x">2</strong></span></a></td>';
                            }
                            if ($Vta == 3) {
                                echo '<td width="3%" align="center"><span style="color:' . $Icon . '" class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="3" style="color:#154360"></i><strong class="fa-stack-1x" style="color:#154360">3</strong></span></td>';
                            } else {
                                echo '<td width="3%" align="center"><a style="color:' . $Icon . '" href="?Venta=3" title="Venta 3"><span class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="3" style="color:' . $Icon . '"></i><strong class="fa-stack-1x">3</strong></span></a></td>';
                            }
                            if ($Vta == 4) {
                                echo '<td width="3%" align="center"><span style="color:' . $Icon . '" class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="4" style="color:#154360"></i><strong class="fa-stack-1x" style="color:#154360">4</strong></span></td>';
                            } else {
                                echo '<td width="3%" align="center"><a style="color:' . $Icon . '" href="?Venta=4" title="Venta 4"><span class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="4" style="color:' . $Icon . '"></i><strong class="fa-stack-1x">4</strong></span></a></td>';
                            }

                            if ($Vta == 5) {
                                echo '<td width="3%" align="center"><span style="color:' . $Icon . '" class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="5" style="color:#154360"></i><strong class="fa-stack-1x" style="color:#154360">5</strong></span></td>';
                            } else {
                                echo '<td width="3%" align="center"><a style="color:' . $Icon . '" href="?Venta=5" title="Venta 5"><span class="fa-stack fa-1x"><i class="fa fa-circle-o fa-stack-2x" id="5"style="color:' . $Icon . '"></i><strong class="fa-stack-1x">5</strong></span></a></td>';
                            }
                            ?>
                            <td width='1%' align='center'>
                            </td>
                        </tr>
                    </table>
                    <table width='100%' border='0' align='center' cellpadding='3' cellspacing='0'>
                        <tr>
                            <td align="center">
                                <?php
                                echo "<i class='fa fa-hospital-o fa-2x' aria-hidden='true' style='color:#24AAA7'></i>";
                                if ($He[entmos] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entmos&Envios=ck'> Matriz <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entmos&Envios=ck'> Matriz <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entmosf] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entmosf&Envios=ck'> H.Futura <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entmosf&Envios=ck'> H.Futura <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entmost] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entmost&Envios=ck'> Tepexpan <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entmost&Envios=ck'> Tepexpan <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entmoslr] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entmoslr&Envios=ck'> Los Reyes <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entmoslr&Envios=ck'> Los Reyes <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entmoscms] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entmoscms&Envios=ck'> Camarones <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entmoscms&Envios=ck'> Camarones <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }

                                echo " &nbsp; &nbsp; <i class='fa fa-file-pdf-o fa-2x' aria-hidden='true' style='color:#24AAA7'></i>";
                                if ($He[tentregamost] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=tentregamost&Envios=ck'> Paciente <i class='fa fa-check'style='color:GREEN'  aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=tentregamost&Envios=ck'> Paciente <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[tentregamed] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=tentregamed&Envios=ck'> Medico <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=tentregamed&Envios=ck'> Medico <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[tentregainst] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=tentregainst&Envios=ck'> Institucion <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=tentregainst&Envios=ck'> Institucion <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }

                                echo " &nbsp; &nbsp; <i class='fa fa-laptop fa-2x' aria-hidden='true' style='color:#24AAA7'></i>";
                                if ($He[entemailpac] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entemailpac&Envios=ck'> Mail/Pac <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entemailpac&Envios=ck'> Mail/Pac <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entwhatpac] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entwhatpac&Envios=ck'> Whats/Pac <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entwhatpac&Envios=ck'> Whats/Pac <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entemailmed] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entemailmed&Envios=ck'> Mail/Med <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entemailmed&Envios=ck'> Mail/Med <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entwhatmed] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entwhatmed&Envios=ck'> Whats/Med <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entwhatmed&Envios=ck'> Whats/Med <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                if ($He[entemailinst] == 1) {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=entemailinst&Envios=ck'> Mail/Inst <i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=1&Cmpo=entemailinst&Envios=ck'> Mail/Inst <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                echo " &nbsp;  &nbsp; ";
                                if ($He[idprocedencia] == 'ambulancia') {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-ambulance fa-2x' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=ambulancia&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-ambulance fa-2x' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                echo " &nbsp;  &nbsp; ";
                                if ($He[idprocedencia] == 'silla') {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-wheelchair fa-2x' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=silla&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-wheelchair fa-2x' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                echo " &nbsp;  &nbsp; ";
                                if ($He[idprocedencia] == 'terceraedad') {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-blind fa-2x' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=terceraedad&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-blind fa-2x' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                echo " &nbsp;  &nbsp; ";
                                if ($He[idprocedencia] == 'problemasv') {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-deaf fa-2x' style='color:GREEN' aria-hidden='true'></i><i class='fa fa-eye-slash fa-1x' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=problemasv&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-deaf fa-2x' style='color:RED' aria-hidden='true'></i><i class='fa fa-eye-slash fa-1x' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                echo " &nbsp;  &nbsp; ";
                                if ($He[idprocedencia] == 'bebe') {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=0&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-child fa-2x' style='color:GREEN' aria-hidden='true'></i></a>";
                                } else {
                                    echo "<a class='edit' href='cotizaorden.php?Venta=$Vta&status=bebe&Cmpo=idprocedencia&Envios=ck'><i class='fa fa-child fa-2x' style='color:RED' aria-hidden='true'></i></a>";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <table width="98%" border="0" align="center" cellpadding="3" cellspacing="0">
                        <tr bgcolor="#e1e1e1">
                            <td width="5%" valign="center" class="content1">
                                <b>Institucion: </b>
                            </td>
                            <td class="content1" width="10%">
                                <?php
                                if ($He[inst] == 0) {
                                    ?>
                                    <form id="formnst" name="formnst" method="post" action="">
                                        <input style="width: 250px;" name="Institucion" type="text" id="Institucion" required></input>
                                        <input type="hidden" value="Instnva" name="op"></input>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                $(document).ready(function () {
                    $('#Institucion').autocomplete({
                        source: function (request, response) {
                            $.ajax({
                                url: "autocomplete.php",
                                datoType: "json",
                                data: {q: request.term, k: "inst"},
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
                                        <a href=javascript:winuni('dspobs.php?op=1&busca=<?= $He[inst] ?>')> <i title='Comentarios' class='fa fa-commenting fa-2x' aria-hidden='true' style='color:<?= $Icon ?>'></i></a> 
                                    </form>
                                    <?php
                                } else {
                                    $cc = "SELECT alias FROM inst WHERE institucion = $He[inst]";
                                    $cp = mysql_query($cc);
                                    $Rs = mysql_fetch_array($cp);
                                    echo $Rs[alias] . " <a href='cotizaorden.php?op=borrainst'> <i class='fa fa-times fa-lg' style='color:#EC7063' aria-hidden='true'></i>";
                                }
                                ?>
                            </td>
                            <td width='5%' align="left" class="content1">
                                Lista / precio: <?= $Lista ?>
                                Condiciones/pago: <?= $Condiciones ?>
                            </td>
                            <td width='10%' align="left" valign="center" class="content1">
                                <form id="Serv1" name="frmser" method="post" action="">
                                    <b>Servicio:</b> 
                                    <select name="Servicio"  class="letrap" onChange="frmser.submit();">
                                        <option value="Ordinario">Ordinario</option>
                                        <option value="Urgente">Urgente</option>
                                        <option value="Express">Express</option>
                                        <option value="Hospitalizado">Hospitlizado</option>
                                        <option value="Nocturno">Nocturno</option>
                                        <option selected="<?= $He[servicio] ?>"><?= $He[servicio] ?></option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <form name='frmpc' id='form99' method='post' action='<?= $_SERVER[PHP_SELF] ?>'>
                            <tr>
                                <td class="content1" width="5%">
                                    <?php
                                    $sql = "SELECT nombre ,apellidom , apellidop FROM cli WHERE cliente = $He[cliente]";
                                    $cSql = mysql_query($sql);
                                    $Sql = mysql_fetch_array($cSql);
                                    if ($He[cliente] > 0 AND $He[cliente] <> 470470) {
                                        ?>
                                        <b>Paciente :</b> 
                                    </td>
                                    <td class="content1" width="10%">
                                        <?= ucwords(strtolower($He[cliente])) . " .- " . ucwords(strtolower($Sql[nombre])) . " " . ucwords(strtolower($Sql[apellidom])) . " " . ucwords(strtolower($Sql[apellidop])) ?>
                                        <a href='cotizaorden.php?Cli=si'> 
                                            <i class='fa fa-user-times fa-2x' style='color:#EC7063' aria-hidden='true'>
                                            </i>
                                        </a>
                                        <a style="align-content: flex-end" href=javascript:winuni('dspobs.php?busca=<?= $He[cliente] ?>&op=2')>
                                            <i title='Comentarios' class='fa fa-commenting fa-2x' aria-hidden='true' style='color:<?= $Icon ?>'></i>
                                        </a>
                                        <?php
                                    } else if ($He[cliente] == 470470) {
                                        ?>
                                        <b>Paciente :</b> 
                                    </td>
                                    <td class="content1" width="15%">
                                        <?= ucwords(strtolower($He[cliente])) . " .- " . ucwords(strtolower($Sql[nombre])) . " " . ucwords(strtolower($Sql[apellidom])) . " " . ucwords(strtolower($Sql[apellidop])) . " - " . ucwords(strtolower($He[paciented])) ?>
                                        <a href='cotizaorden.php?Cli=si'> 
                                            <i class='fa fa-user-times fa-2x' style='color:#EC7063' aria-hidden='true'>
                                            </i>
                                        </a>

                                        <?php
                                    } else {
                                        ?>
                                        <label for="pacientej">
                                            <b>Paciente :</b>
                                        </label>
                                    </td>
                                    <td class="content1" width="15%">
                                        <input style="width: 250px;" name="PacienteJ" type="text" id="pacientej" placeholder="ApellidoP ApellidoM Nombre"></input>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                $('#pacientej').autocomplete({
                                                    source: function (request, response) {
                                                        $.ajax({
                                                            url: "autocomplete.php",
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
                                        <a href="catclientes1.php?busca=ini"><i class="fa fa-search fa-2x" aria-hidden="true" style="color: #24AAA7"></i></a>
                                        <a href="clientese.php?busca=NUEVO&org=odnv&vta=<?= $Vta ?>"><i class="fa fa-user-plus fa-2x" aria-hidden="true" style="color: #24AAA7"></i></a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="content2" width="15%">
                                    <?php
                                    if ($He[cliente] == 470470) {
                                        ?>
                                        <input class="letrap" type="text" size="40" value="<?= ucwords(strtolower($He[paciented])) ?>" placeholder="Nombre de Paciente" name="Paciented"></input>
                                        <input type='submit' class='content5' name='Boton' value='Guarda' class='nombre_cliente'></input>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="content1">E-mail:</span> <?php $He[mail]; ?>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="content2" width="30%">
                                    <span class="content1">Programa: </span> <?= $He[programa]; ?>
                                    &nbsp; &nbsp; &nbsp; <span class="content1">No.de veces: </span> <?= $He[numveces]; ?>
                                </td>
                            </tr>
                        </form>
                        <form name='frmmedico' id='form99' method='post' action='<?= $_SERVER[PHP_SELF] ?>'>
                            <tr bgcolor="#e1e1e1">
                                <td class="content1" width="5%" height="30">
                                    <?php
                                    if ($He[medico] === 'MD') {
                                        $sql = "SELECT nombrec FROM med WHERE medico = '$He[medico]'";
                                        $cSql = mysql_query($sql);
                                        $Sql = mysql_fetch_array($cSql);
                                        ?>                                        
                                        <b>Medico :</b>
                                    </td>
                                    <td class="content1" width="15%" height="30"> 
                                        <?= ucwords(strtolower($He[medico])) . " .- " . ucwords(strtolower($Sql[nombrec])) . " - " . ucwords(strtolower($He[medicon])) ?>
                                        <a href='cotizaorden.php?Medi=si'> 
                                            <i class='fa fa-user-times fa-2x' style='color:#EC7063' aria-hidden='true'>
                                            </i>
                                        </a>
                                        <?php
                                    } elseif ($He[medico] <> '') {
                                        $sql = "SELECT nombrec FROM med WHERE medico = '$He[medico]'";
                                        $cSql = mysql_query($sql);
                                        $Sql = mysql_fetch_array($cSql);
                                        ?>                                        
                                        <b>Medico :</b> 
                                    </td>
                                    <td class="content1" width="10%" height="30">
                                        <?= ucwords(strtolower($He[medico])) . " .- " . ucwords(strtolower($Sql[nombrec])) ?>
                                        <a href='cotizaorden.php?Medi=si'> 
                                            <i class='fa fa-user-times fa-2x' style='color:#EC7063' aria-hidden='true'>
                                            </i>
                                        </a>
                                        <a style="align-content: flex-end" href=javascript:winuni('dspobs.php?busca=<?= $He[medico] ?>&op=3')>
                                            <i title='Comentarios' class='fa fa-commenting fa-2x' aria-hidden='true' style='color:<?= $Icon ?>'></i>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <label for="medico">
                                            <b>Medico :  &nbsp;</b>
                                        </label>
                                    </td>
                                    <td class="content1" width="15%" height="30">
                                        <input style="width: 250px;" name="Medico" type="text" id="medico" placeholder="ApellidoP ApellidoM Nombre"></input>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                $('#medico').autocomplete({
                                                    source: function (request, response) {
                                                        $.ajax({
                                                            url: "autocomplete.php",
                                                            datoType: "json",
                                                            data: {q: request.term, k: "md"},
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
                                        <a href="catmedicos1.php?busca=ini"><i class="fa fa-search fa-2x" aria-hidden="true" style="color: #24AAA7"></i></a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="content2" width="15%">
                                    <?php
                                    if ($He[medico] == "MD") {
                                        echo '<input class="letrap" type="text" size="40" value="' . ucwords(strtolower($He[medicon])) . '" placeholder="Nombre del medico" name="NombreMedico">';
                                        echo "<input type='submit' class='content5' name='Boton' value='Guardar' class='nombre_cliente'>";
                                    } else {
                                        echo '<span class="content1">Nombre:</span> ' . ucwords(strtolower($He[nombremed]));
                                    }
                                    ?>
                                </td>                                    
                                <td class="content2" width="30%">
                                    <span class="content1">E-mail:</span> <?= $He[mailmed] ?>
                                </td>
                            </tr>
                        </form>
                        <form name='frmestudios' id='form90' method='post' action='<?= $_SERVER[PHP_SELF] ?>'>
                            <tr class='texto_tablas'>
                                <td class='content1' width='5%' height='30'>
                                    <label for="estudio">
                                        <b>Estudio :  &nbsp;</b>
                                    </label>
                                </td>
                                <td class='content1' width='15%' height='30'>
                                    <input style="width: 250px;" name="IdEstudio" type="text" id="estudio"></input>
                                    <script src="js/jquery-1.8.2.min.js"></script>
                                    <script src="jquery-ui/jquery-ui.min.js"></script>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#estudio').autocomplete({
                                                source: function (request, response) {
                                                    $.ajax({
                                                        url: "autocomplete.php",
                                                        datoType: "json",
                                                        data: {q: request.term, k: "est"},
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
                                    <a href="catestudios1.php?busca=ini"><i class="fa fa-search fa-2x" aria-hidden="true" style="color: #24AAA7"></i></a>
                                </td>
                                <td colspan="2" width='40%' align='right'>
                                    <span class='content1'>
                                        <font color='#990000'>
                                            <?= $Msj ?>
                                        </font>
                                    </span>
                                </td>
                            </tr>
                        </form>
                    </table>
                    <table width="97%" border="0" align="center" cellpadding="3" cellspacing="0">
                        <tr>
                            <td class="content2" width="33%" height="30">
                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                                    <tr bgcolor="#B5B5B5">
                                        <th height="24"><span class="content1">Estudio</span></th>
                                        <th><span class="content1">Descripción</span></th>
                                        <th><span class="content1">Precio</span></th>
                                        <th><a class="edit" href="menudiv.php?op=9">% Descto</a></th>
                                        <th><span class="content1">Importe</span></th>
                                        <th><span class="content1">T. Entrega</span></th>
                                        <th><span class="content1">-</span></th>
                                    </tr>
                                    <?php
                                    $Csql = "SELECT est.estudio,est.descripcion,ctdnvas.precio,ctdnvas.descuento,ctdnvas.estudio as idestudio, est.tiempoest as tiempo
                                        FROM ctdnvas
                                        LEFT JOIN est ON ctdnvas.estudio=est.estudio 
                                        WHERE ctdnvas.usr='$Gusr' AND ctdnvas.venta='$Vta';";
                                    $CpoA = mysql_query($Csql);
                                    $nRng = 0;
                                    $Fecha = date("Y-m-d");
                                    $cHoy = strtotime($Fecha);                //Convierto la fecha a Numeros    
                                    $Fec_entrega = $Fecha;
                                    while ($registro = mysql_fetch_array($CpoA)) {

                                        $Dias = $registro[tiempo] . " days";
                                        $FechaEnt = strtotime($Dias, $cHoy);   //Le quito los dias k quiero,  puede ser days month years y hasta -1 month menos un mes...
                                        $FechaEnt = date("Y-m-d", $FechaEnt);         //Convierto El resultado en Tipo Fecha

                                        if ($FechaEnt > $Fec_entrega) {
                                            $Fec_entrega = $FechaEnt;
                                        }

                                        if (($nRng % 2) > 0) {
                                            $Fdo = 'FFFFFF';
                                        } else {
                                            $Fdo = $Gfdogrid;
                                        }    //El resto de la division;
                                        ?>
                                        <tr bgcolor='<?= $Fdo ?>'>
                                            <td align='left'><a  class='edit' href=javascript:winuni('estudiosobs.php?Estudio=<?= $registro[estudio] ?>')><?= ucwords(strtolower($registro[estudio])) ?></a></td>
                                            <td><span class="content2"><?= ucwords(strtolower($registro[descripcion])) ?></span></td>
                                            <td align="right"><span class="content2"><?= number_format($registro[precio], "2") ?></span></td>
                                            <td align="center"><span class="content2"><a class="edit" href="menudiv.php?op=1&Estudio=<?= $registro[idestudio] ?>"><?= $registro[descuento] ?></a></span></td>
                                            <td align="right"><span class="content2"><?= number_format($registro[precio] * (1 - $registro[descuento] / 100), "2") ?></span></td>
                                            <td align="center"><span class="content2"><?= $FechaEnt ?></span></td>
                                            <td align="center"><span><a class="edit" href="cotizaorden.php?op=Eli&busca=<?= $registro[idestudio] ?>"><i class="fa fa-trash fa-lg" style="color:#C84239" aria-hidden="true"></i></a></span></td>
                                        </tr>
                                        <?php
                                        $nImporte += $nImp + ($registro[precio] * (1 - ($registro[descuento] / 100)));
                                        $nRng++;
                                        $x=10;
                                    }

                                    while ($nRng < 10) {

                                        echo '<tr class="content1"><td>&nbsp;</td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>';
                                        $nRng++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="6"align="right">
                                            <span class="letrap">
                                                <b> Total estudios: $<?= $nImporte ?></b>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="letrap">
                                                <b> <?php number_format($nRng, 0) ?> </b>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
                                    <tr bgcolor="#B5B5B5">
                                        <td width="40%" height="24">
                                            <form id="Serv1" name="frmdatos" method="post" action="">
                                                <span class="content1">
                                                    Observaciones:
                                                </span>
                                                <textarea name="Observaciones" cols="60"><?= $He[observaciones] ?></textarea>
                                                <br></br>
                                                <span class="content1">
                                                    Diag.medico:
                                                </span>
                                                <textarea name="Diagmedico" cols="60"><?= $He[diagmedico] ?></textarea>
                                                <br></br>
                                                <span class="content1">Receta &oacute; folio alterno:</span> 
                                                <input name="Receta" type="text" class="content1" id="Num.Paciente7" value="<?= $He[receta] ?>" size="16" />
                                                <span class="content1">Fecha/receta:</span> 
                                                <input name="Fechar" type="text" class="content1" id="Num.Paciente7" value= "<?= $He[fechar] ?>" size="16" />
                                                <input name="Boton" type="submit" class="content5" id="Borrar2" value="Enviar" />
                                                <div>
                                                    <span class='content2'>
                                                        <?= $MsjDatos ?>
                                                    </span>
                                                </div>
                                            </form>

                                            <a class='edit' href=javascript:wingral('cotizacion.php?Vta=<?= $Vta ?>')><b>Cotizacion</b></a>
                                            <br></br>
                                            <a class='edit' href=javascript:wingral('impos.php')><b>Servicio a Domicilio</b></a>
                                        </td><td width="35%" valign="top" align="center">
                                            <br></br>
                                            <form name='frmfechas' id='form90' method='post' action='<?= $_SERVER[PHP_SELF] ?>'>
                                                <span class="content1"> 
                                                    Fecha/entrega: 
                                                </span>
                                                <input name="Fechae" type="text" size="11" class="content1" id="Borrar2" value="<?= $He[fechae] ?>" />
                                                <span class="content1"> 
                                                    Hora: 
                                                </span>
                                                <input name="Horae" type="text" size="6" class="content1" id="Borrar2" value="<?= $He[horae] ?>" />
                                                <br></br>
                                                <input type='submit' class="content5" name='Boton' value='Cambia fecha/entrega'/>
                                                <p><span class='cMsj'><?= $MsjFecHra ?></span></p>
                                            </form>
                                            <form name='frmgenera' id='form90' method='post' action='<?= $_SERVER[PHP_SELF] ?>'>
                                                <?php
                                                if ($He[medico] <> "" AND $He[cliente] > 0 AND $x == 10) {
                                                    ?>
                                                    <input name="Boton" type="submit" class="content5" id="Borrar2" value="Genera Cotizacion"></input>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input name="Boton" type="submit" class="content5" id="Borrar2" value="Genera Cotizacion" disabled></input>
                                                    <?php
                                                }
                                                ?>
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    <?php
    $sql = "SELECT venta FROM ctnvas WHERE usr='$Gusr' AND inst > 0";
    $cSql = mysql_query($sql);
    while ($rs = mysql_fetch_array($cSql)) {
        ?>
        <script type="text/javascript">
            var el = document.getElementById("<?= $rs[venta] ?>");
            el.setAttribute("style", "background-color:rgba(0,0,255,0.2);border:#000; border-radius: .5em;");
        </script>
        <?php
    }
    ?>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>
