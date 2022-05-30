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
$busca = $_REQUEST[busca];
$Traza     = $_REQUEST[Traza];
$Est    = $_REQUEST[Est];
$Det    = $_REQUEST[Det];
$op = $_REQUEST[op];
$Fechaest  = date("Y-m-d H:i:s");
$Fechareg  = date("Y-m-d");

if ($_REQUEST[bt] === "Actualizar") {
    $sql = "UPDATE ot SET receta='$_REQUEST[Receta]',fecharec='$_REQUEST[Fecharec]',descuento='$_REQUEST[Descuento]',diagmedico='$_REQUEST[Diagmedico]',"
            . "observaciones='$_REQUEST[Observaciones]',fechae='$_REQUEST[Fechae]',horae='$_REQUEST[Horae]'"
            . " WHERE ot.orden='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis mysql" . $sql;
    }
}elseif ($_REQUEST[bt] === "Cambiar") {
    header("Location: clientese.php?busca=$_REQUEST[IdExt]&rg=ord&idext=$busca");
}

if ($op == "Eli") { //Elimina     $Medico=strtoupper($Medico);
    $cSqlE = "DELETE FROM otd WHERE estudio='$Est' AND orden='$busca' limit 1";
    $SqA   = mysql_query($cSqlE);

    $OtdA  = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
    $Otd   = mysql_fetch_array($OtdA);
    $lUp   = mysql_query("UPDATE ot set importe='$Otd[0]' WHERE orden='$busca'");
    
    $lUp  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
    ('$Fechaest','$Gusr','Elima estudio $Est Ot: $busca')");
    
    $OtC     = mysql_query("select importe from ot where orden='$busca'",$link);
    $Otd      = mysql_fetch_array($OtC);
    $cSqlB   = mysql_query("select sum(importe) from cja where orden='$busca'",$link);
    $Abonado = mysql_fetch_array($cSqlB);
    
    if(($Abonado[0] + .5) >= $Otd[0] ){
       $lUp=mysql_query("update ot Set pagada='Si',fecpago='$Fechareg' where orden='$busca'",$link);
    }else{
       $lUp=mysql_query("update ot Set pagada='No' where orden='$busca'",$link);
    }
}

#Variables comunes;

$sCpo = "SELECT ot.orden,ot.fecha,ot.fecharec,ot.hora,ot.fechae,ot.cliente idex,cli.nombrec as nombrecli,ot.importe,ot.descuento,ot.receta,
    ot.ubicacion,ot.institucion,ot.medico,med.nombrec as nombremedico,ot.status,ot.recibio,ot.diagmedico,
    ot.pagada,ot.observaciones,ot.horae,cli.fechan,ot.suc,ot.recepcionista,ot.idprocedencia,ot.responsableco,ot.servicio
    FROM ot,cli,med
    WHERE ot.cliente=cli.cliente AND ot.medico=med.medico AND ot.orden='$busca'";


$CpoA = mysql_query($sCpo);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
} else {
    $Estado = $Cpo[estado];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Recepción - Consulta OT's</title>
        <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />

        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="jquery-ui/jquery-ui.min.js"></script>        

        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>

    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu,$Gusr);
        ?>
        <script src="./controladores.js"></script>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de la Orden: <?= $Cpo[orden] . ' - ' . ucwords(strtolower($Cpo[nombrecli])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='98%' align='center' border='0' cellpadding='0' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    ..:: Datos Principales ::..
                                </td>
                            </tr>
                            <tr style="height: 35px">
                                <td align='right' class="Inpt" width='30%'>
                                    Sucursal :
                                </td>
                                <td  width='20%'>
                                    <select class="Inpt" name="Sucursal"  style="font-weight:bold" disabled>  
                                        <?php
                                        $SucA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9'ORDER BY id");
                                        while ($Suc = mysql_fetch_array($SucA)) {
                                            ?>
                                            <option value='<?= $Suc[id] ?>'><?= $Suc[id] .' - '.$Suc[alias] ?></option>
                                            <?php
                                            if ($Cpo[suc] == $Suc[id]) {
                                                ?>
                                                <option selected value="<?= $Suc[id]?>"><?= $Suc[id] ." - ". $Suc[alias] ?></selected>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </td>
                                <td width='50%' align="right" class="Inpt">
                                    Servicio : 
                                </td>
                                <td class="Inpt">
                                    <?php
                                    if($Cpo[servicio]=="Urgente"){
                                        $Letraserv='red';
                                    }else{
                                        $Letraserv='blue';
                                    }
                                    ?>
                                    <select name="Servicio" style="font-weight:bold;color:<?=$Letraserv?>;" disabled>
                                        <option value="Ordinario">Ordinario</option>
                                        <option value="Urgente">Urgente</option>
                                        <option value="Express">Express</option>
                                        <option value="Hospitalizado">Hospitlizado</option>
                                        <option value="Nocturno">Nocturno</option>
                                        <option selected="<?= $Cpo[servicio] ?>"><?= $Cpo[servicio] ?></option>  
                                    </select>        
                                </td>
                            </tr>
                            <tr style="height: 35px" bgcolor="#D5D8DC">
                                <td align='right' class="Inpt" width='30%'>
                                    Institucion :
                                </td>
                                <td  width='20%'>
                                    <select class="Inpt" name="Institucion"  style="font-weight:bold" disabled>  
                                        <?php
                                        $InsA = mysql_query("SELECT institucion as id,alias,lista,condiciones FROM inst WHERE status='ACTIVO' ORDER BY institucion");
                                        while ($Ins = mysql_fetch_array($InsA)) {
                                            ?>
                                            <option value='<?= $Ins[id] ?>'><?= $Ins[id] .' - '.$Ins[alias] ?></option>
                                            <?php
                                            if ($Cpo[institucion] == $Ins[id]) {
                                                ?>
                                                <option selected value="<?= $Ins[id]?>"><?= $Ins[id] ." - ". $Ins[alias] ?></selected>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </td>
                                <td width='50%' align="right" class="Inpt">
                                    No. de Orden : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Orden' value='<?= $Cpo[orden] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 35px">
                                <td align="right" class="Inpt">
                                    Fecha Cap : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fecha' value='<?= $Cpo[fecha] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                                <td align="right" class="Inpt">
                                    Hora Cap : 
                                </td>
                                <td class="Inpt">
                                    <input type='time' class='cinput'  name='Hora' value='<?= $Cpo[hora] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 35px" bgcolor="#D5D8DC">
                                <td align="right" class="Inpt">
                                    Paciente : 
                                </td>
                                <td class="Inpt" colspan="3">
                                    <textarea class="cinput" name="Nombrec" type="text" rows="1" cols="60" disabled><?= $Cpo[idex] .' - '. $Cpo[nombrecli] ?></textarea>
                                    <input type="hidden" value='<?= $Cpo[idex] ?>' name='IdExt'></input> 
                                    <input class="letrap" type="submit" value='Cambiar' name='bt' disabled></input>
                                </td>
                            </tr>
                            <tr style="height: 35px">
                                <td align="right" class="Inpt">
                                    Medico : 
                                </td>
                                <td class="Inpt" colspan="3">
                                    <input type='text' class='cinput'  name='Medico' value='<?= $Cpo[medico] .' - '. $Cpo[nombremedico] ?>' size='60' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 35px" bgcolor="#D5D8DC">
                                <td align="right" class="Inpt">
                                    No. de receta : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Receta' value='<?= $Cpo[receta] ?>' MAXLENGTH='30'></input>
                                </td>
                                <td align="right" class="Inpt">
                                    Fecha de receta : 
                                </td>
                                <td>
                                    <input type='date' class='cinput'  name='Fecharec' value='<?= $Cpo[fecharec] ?>' MAXLENGTH='30'></input>
                                </td>
                            </tr>
                            <tr style="height: 35px">
                                <td align="right" class="Inpt">
                                    Descuento (razón) : 
                                </td>
                                <td class="Inpt" colspan="3">
                                    <textarea class="cinput" name="Descuento" type="text" rows="1" cols="70"><?= $Cpo[descuento] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px" bgcolor="#D5D8DC">
                                <td align="right" class="Inpt">
                                    Diagnostico : 
                                </td>
                                <td class="Inpt" colspan="3">
                                    <textarea class="cinput" name="Diagmedico" type="text" rows="2" cols="70"><?= $Cpo[diagmedico] ?></textarea>
                                </td>
                            </tr>
                            <tr style="height: 35px">
                                <td align="right" class="Inpt">
                                    Observaciones : 
                                </td>
                                <td class="Inpt" colspan="3">
                                    <textarea class="cinput" name="Observaciones" type="text" rows="2" cols="70"><?= $Cpo[observaciones] ?></textarea>
                                </td>
                            </tr>
                                    <?php
                                    if ($He[servicio] == 'Cita') {
                                        ?>
                                        &nbsp; No.de cita: <?= $He[citanum] ?> 
                                        <?php
                                    }
                                    ?>

                            <tr style="height: 35px" bgcolor="#D5D8DC">
                                <td align="right" class="Inpt">
                                    Fecha de entrega : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fechae' value='<?= $Cpo[fechae] ?>'>
                                    </input>
                                </td>
                                <td align="right" class="Inpt">
                                    Hora de entrega : 
                                </td>
                                <td class="Inpt">
                                    <input type='time' class='cinput'  name='Horae' value='<?= $Cpo[horae] ?>'>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 35px">
                                <td align="right" class="Inpt">
                                    Feche entrega real: 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Entfec' value='<?= $Cpo[entfec] ?>' disabled>
                                    </input>
                                </td>
                                <td align="right" class="Inpt">
                                    Quien lo entrego : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Entusr' value='<?= $Cpo[entusr] ?>' MAXLENGTH='30' size='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr style="height: 35px" bgcolor="#D5D8DC">
                                <td align="right" class="Inpt">
                                    Recibo: 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Recibio' value='<?= $Cpo[recibio] ?>' MAXLENGTH='30' disabled>
                                    </input>
                                </td>
                                <td align="right" class="Inpt">
                                    Capturó : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Capturo' value='<?= $Cpo[recepcionista] ?>' MAXLENGTH='30' size='30' disabled>
                                    </input>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
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
                                    ..:: Detalle ::..
                                </td>
                            </tr>
                            <tr>
                                <td class="content1">
                                    <?php
                                        if($Gnivel=='99'){
                                    ?>
                                        <form name='frmestudios' id='form90' method='post' action='<?= $_SERVER[PHP_SELF] ?>'>
                                            <div class="texto_tablas" align="center">
                                                <span> Agregar Estudio : </span>
                                                <input style="width: 250px;" name="IdEstudio" type="text" id="estudio" class="content1"></input>
                                                <script src="js/jquery-1.8.2.min.js"></script>
                                                <script src="jquery-ui/jquery-ui.min.js"></script>
                                                <script type="text/javascript" class="texto_tablas">
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
                                            </div>
                                            <input name="busca" type="hidden" value="<?=$busca?>">
                                        </form>
                                    <?php
                                        }else{
                                    ?>
                                            <br>
                                    <?php
                                        }
                                    ?>
                                    <table align="center" cellpadding="3" cellspacing="2" width="97%" border="0">
                                        <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                            <td height="12px">
                                                <b>Etq</b>
                                            </td>
                                            <td height="22px">
                                                <b>Estudio</b>
                                            </td>

                                            <?php
                                            if ($Traza=='Si') {
                                            ?>
                                                <td>
                                                    <b>Etiq</b>
                                                </td>
                                                <td>
                                                    <b>Toma</b>
                                                </td>
                                                <td>
                                                    <b>Capt</b>
                                                </td>
                                                <td>
                                                    <b>Impr</b>
                                                </td>
                                                <td>
                                                    <b>Recep</b>
                                                </td>
                                            <?php
                                            } else {
                                            ?>
                                                <td>
                                                    <b>Precio</b>
                                                </td>
                                                <td>
                                                    <b>Descto %</b>
                                                </td>
                                                <td>
                                                    <b>Importe</b>
                                                </td>  
                                                <?php
                                                    if($Gnivel=='99'){
                                                ?>
                                                        <td>
                                                            <b>Elim</b>
                                                        </td> 
                                                <?php
                                                    }else{
                                                ?>
                                                        <td>
                                                            <b>-</b>
                                                        </td> 
                                                <?php
                                                    }
                                                ?>
                                            <?php
                                            }
                                            ?>
                                        </tr>   
                                        <?php
                                        $sql = "SELECT est.descripcion,otd.estudio,otd.precio,otd.status,otd.descuento,otd.precio-(otd.precio*otd.descuento)/100 as importe,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,otd.etiquetas,otd.fechaest,otd.usrest FROM otd INNER JOIN est ON otd.estudio = est.estudio  WHERE orden='$busca'";             

                                        $result3 = mysql_query($sql);
                                        $num = 0;
                                        while ($cSql = mysql_fetch_array($result3)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = '#FFFFFF';
                                            } else {
                                                $Fdo = '#D5D8DC';
                                            }

                                            if($cSql[etiquetas]>=1){
                                                $imagen1="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                            }else{  
                                                $imagen1="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                            }

                                            if($cSql[dos]<>'0000-00-00 00:00:00'){
                                                $imagen2="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                            }else{  
                                                $imagen2="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                            }

                                            if($cSql[tres]<>'0000-00-00 00:00:00'){
                                                $imagen3="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                            }else{  
                                                $imagen3="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                            }

                                            if($cSql[cuatro]<>'0000-00-00 00:00:00'){
                                                $imagen4="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                            }else{  
                                                $imagen4="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                            }

                                            if($cSql[cinco]<>'0000-00-00 00:00:00'){
                                                $imagen5="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                            }else{  
                                                $imagen5="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                            }

                                            ?>
                                            <tr class="letrap" bgcolor="<?= $Fdo ?>">
                                                <td>
                                                    <a class='edit' href="javascript:winuni('impeti.php?op=1&busca=<?= $Cpo[orden] ?>&Est=<?= $cSql[estudio] ?>')"><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a>
                                                </td>
                                                <td>
                                                    <?= $cSql[estudio] .' - '. $cSql[descripcion] ?>
                                                </td>
                                            <?php
                                            if ($Traza=='Si') {
                                            ?>
                                                <td align="center">
                                                    <b><a href="ordenescone.php?busca=<?=$busca?>&Traza=Si&Det=Eti&Est=<?=$cSql[estudio]?>"><?= $imagen1 ?></b></a>
                                                </td>
                                                <td align="center">
                                                    <b><a href="ordenescone.php?busca=<?=$busca?>&Traza=Si&Det=Toma&Est=<?=$cSql[estudio]?>"><?= $imagen2 ?></b></a>
                                                </td>
                                                <td align="center">
                                                    <b><a href="ordenescone.php?busca=<?=$busca?>&Traza=Si&Det=Capt&Est=<?=$cSql[estudio]?>"><?= $imagen3 ?></b></a>
                                                </td>
                                                <td align="center">
                                                    <b><a href="ordenescone.php?busca=<?=$busca?>&Traza=Si&Det=Impr&Est=<?=$cSql[estudio]?>"><?= $imagen4 ?></b></a>
                                                </td>
                                                <td align="center">
                                                    <b><a href="ordenescone.php?busca=<?=$busca?>&Traza=Si&Det=Recep&Est=<?=$cSql[estudio]?>"><?= $imagen5 ?></b></a>
                                                </td>
                                            <?php
                                            } else {
                                            ?>
                                                <td align="right">
                                                    <?= number_format($cSql[precio], 2) ?>
                                                </td>
                                                <td align="center">
                                                    <?= $cSql[descuento] ?>
                                                </td>
                                                <td  align="right">
                                                    <?= number_format($cSql[importe], 2) ?>
                                                </td>
                                                <?php
                                                    if($Gnivel=='99'){
                                                ?>
                                                        <td>
                                                            <b><span><a class="edit" href="ordenescone.php?op=Eli&Est=<?= $cSql[estudio] ?>&busca=<?= $Cpo[orden] ?>"><i class="fa fa-trash fa-lg" style="color:#C84239" aria-hidden="true"></i></a></span></b>
                                                        </td> 
                                                <?php
                                                    }else{
                                                ?>
                                                        <td>
                                                            <b>-</b>
                                                        </td> 
                                                <?php
                                                    }
                                                ?>
                                            <?php
                                            }
                                            ?>
                                            </tr>
                                            <?php
                                            $sumP = $sumP + $cSql[precio];
                                            $sumPi = $sumPi + $cSql[importe];
                                            $nRng++;
                                        }
                                        ?>
                                        <?php
                                            if ($Traza=='Si') {

                                                $sql4 = "SELECT otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,otd.etiquetas,otd.fechaest,otd.usrest,otd.impeti,otd.capturo,otd.impest,otd.recibeencaja FROM otd WHERE otd.orden='$busca' and otd.estudio='$Est' limit 1";          
                                                $result4 = mysql_query($sql4);

                                                $cSql4 = mysql_fetch_array($result4);

                                                if($Det=='Eti'){

                                                    $Detalle= '<b>'. $Est .' - '.' Imp. Etiqueta (Fecha/Hora): </b>'.$cSql4[uno] .' <b> Usuario: </b> '. $cSql4[impeti];

                                                }elseif($Det=='Toma'){

                                                    $Detalle= '<b>'. $Est .' - '.' Toma/Recolecc (Fecha/Hora): </b>'.$cSql4[fechaest] .' <b> Usuario: </b> '. $cSql4[usrest];

                                                }elseif($Det=='Capt'){

                                                    $Detalle= '<b>'. $Est .' - '.' Captura (Fecha/Hora): </b>'.$cSql4[tres] .' <b> Usuario: </b> '. $cSql4[capturo];

                                                }elseif($Det=='Impr'){

                                                    $Detalle= '<b>'. $Est .' - '.' Impresion (Fecha/Hora): </b>'.$cSql4[cuatro] .' <b> Usuario: </b> '. $cSql4[impest];

                                                }elseif($Det=='Recep'){

                                                    $Detalle= '<b>'. $Est .' - '.' Recib.Recep (Fecha/Hora): </b>'.$cSql4[cinco] .' <b> Usuario: </b> '. $cSql4[recibeencaja];

                                                }else{

                                                    $Detalle='';

                                                }
                                        ?>
                                            <tr class="letrap">
                                                <td>
                                                </td>
                                                <td align="left" colspan="6"><?= $Detalle ?></td>
                                            </tr>
                                        <?php
                                            } else {
                                        ?>
                                            <tr class="letrap">
                                                <td>
                                                    <a class='edit' href="javascript:winuni('impeti2.php?op=3&busca=<?= $Cpo[orden] ?>')"><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#a93226'></i></a>
                                                </td>
                                                <td align="right"><b>Totales: ---> </
                                                        b></td>
                                                <td align="right"><b>
                                                        $  <?= number_format($sumP, 2) ?>
                                                    </b></td>
                                                <td></td>
                                                <td align="right"><b>
                                                        $  <?= number_format($sumPi, 2) ?>
                                                    </b></td>
                                            </tr>
                                        <?php
                                            }
                                        ?>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <table border="0" align="center">
                        <tr>
                            <td>
                                <a href="ordenescon.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                            </td>
                            <td width="50%">
                            </td>
                            <td align="right">
                                <?php
                                if ($Traza=='Si') {
                                ?>    
                                    <a href="ordenescone.php?busca=<?=$busca?>&Traza=No" class="content5" ><font size="2"> Precios </font></a>
                                <?php
                                } else {
                                ?>
                                    <a href="ordenescone.php?busca=<?=$busca?>&Traza=Si" class="content5" ><font size="2"> Trazabilidad </font></a>
                                <?php
                                }
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
?>

