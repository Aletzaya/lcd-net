<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");
$link = conectarse();
require ("config.php");          //Parametros de colores;
if (isset($_REQUEST[busca])) {
    if ($_REQUEST[busca] == ini) {
        $Pos = strrpos($_REQUEST[Ret], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef
        if ($Pos > 0) {
            $Retornar = $_REQUEST[Ret] . '&';
        } else {
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST[Ret] . '?';
            }
        }
        $_SESSION["OnToy"] = array('', '', 'calendario.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST[pagina])) {
    $_SESSION['OnToy'][1] = $_REQUEST[pagina];
}
if (isset($_REQUEST[orden])) {
    $_SESSION['OnToy'][2] = $_REQUEST[orden];
}
if (isset($_REQUEST[Sort])) {
    $_SESSION['OnToy'][3] = $_REQUEST[Sort];
}
if (isset($_REQUEST[Ret])) {
    $_SESSION['OnToy'][4] = $_REQUEST[Ret];
}
if (isset($_REQUEST[FechaI])) {
    $_SESSION['OnToy'][5] = $_REQUEST[FechaI];
}else{
    $_SESSION['OnToy'][5] = date("Y-m-d");
}
if (isset($_REQUEST[FechaF])) {
    $_SESSION['OnToy'][6] = $_REQUEST[FechaF];
}
if (isset($_REQUEST[Sucursal])) {
    $_SESSION['OnToy'][7] = $_REQUEST[Sucursal];
}else{
    $_SESSION['OnToy'][7] = '0';
}


if (isset($_REQUEST["servicio"])) {
    $Servicio = $_REQUEST["servicio"];
}else{
    $Servicio = '*';
}

if($Servicio=='*'){
    $Servicioftr='';
}else{
    $Servicioftr="and display like '%$Servicio%'";
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$RetSelec = $_SESSION[OnToy][4];
$FechaI = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$Sucursal = $_SESSION[OnToy][7];
//$FechaF = $_SESSION[OnToy][6];          //Pagina a la que regresa con parametros  
$Id = 62;
$Cat='Agenda';

if($Sucursal=='0'){
    $Sucursalfiltro="";
}else{
    $Sucursalfiltro=" and sucursal='$Sucursal'";
}

if (isset($_REQUEST["status"])) {
    $Status = $_REQUEST["status"];
}else{
    $Status = "0";
}

if($Status=='0'){
    $Statusftr='';
}else{
    $Statusftr="and status=$Status";
}

$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

//Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
//echo "El valor de retornar es $RetSelec";
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$cLink = $_SERVER[PHP_SELF];
if ($_REQUEST[bt] === "Actualizar") {
    $sql = "UPDATE calendario SET titulo = '$_REQUEST[Titulo]', inicia = '$_REQUEST[Inicia]', finaliza = '$_REQUEST[Finaliza]', display='$_REQUEST[Importancia]',"
            . "servicio='$_REQUEST[Servicio]',generadora='$_REQUEST[Generadora]',receptora='$_REQUEST[Receptora]',fcita='$_REQUEST[Fcita]',frealizacion='$_REQUEST[Frealizacion]', "
            . "status='$_REQUEST[Status]' WHERE id =  $_REQUEST[op]";

    if (!mysql_query($sql)) {
        $Msj = "Error sintaxis SQL" . $sql;
    }
    $Msj = "¡Registro actualizado con exito!";
} elseif ($_REQUEST[bt] === "NUEVO") {
    $ini = date("Y-m-d H:i:m", strtotime($_REQUEST[Inicia]));
    $fin = date("Y-m-d H:i:m", strtotime($_REQUEST[Finaliza]));
    $fcit = date("Y-m-d H:i:m", strtotime($_REQUEST[Fcita]));
    $freal = date("Y-m-d H:i:m", strtotime($_REQUEST[Frealizacion]));
    $sql = "INSERT INTO calendario (titulo,inicia,finaliza,display,servicio,generadora,receptora,fcita,frealizacion,status)"
            . " VALUES ('$_REQUEST[Titulo]','$ini','$fin','$_REQUEST[Importancia]','$_REQUEST[Servicio]',"
            . "'$_REQUEST[Generadora]','$_REQUEST[Receptora]','$fcit','$freal',$_REQUEST[Status])";
    //echo $sql;
    if (!mysql_query($sql)) {
        $Msj = "Error sintaxis SQL" . $sql;
    }
    $Msj = "¡Registro capturado con exito!";
} elseif ($_REQUEST[op] === "Si") {
    $sql = "UPDATE calendario SET id=-id where id = $_REQUEST[cId]";
    if (!mysql_query($sql)) {
        $Msj = "Error sintaxis SQL" . $sql;
    }
    $Msj = "¡Registro eliminado con exito !";
}
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if ($_REQUEST[Boton] === 'Busca') {
    $Recep = $aSucursal[$_REQUEST[Receptora]];
    $cSql = "SELECT $Qry[campos],display,sucursal,status FROM $Qry[froms] WHERE date_format(inicia,'%Y-%m-%d')='$FechaI' and id >=0 $Servicioftr $Statusftr $Sucursalfiltro";
    //echo $cSql;
} else {
#Armo el query segun los campos tomados de qrys;
    $cSql = "SELECT $Qry[campos],display,sucursal,status FROM $Qry[froms] WHERE date_format(inicia,'%Y-%m-%d')='$FechaI' and id >= 0 $Servicioftr $Statusftr $Sucursalfiltro";

}

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Det", "-", "-","Sucursal", "-", "-","Servicio", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("Status", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agenda ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <?php
        if ($Msj !== null) {
            ?>
            <script type="text/javascript">
                alert("<?= $Msj ?>");
            </script>
            <?php
        }
        ?>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu,$Gusr);
        ?>
        <script src="./controladores.js"></script>
        <table><tr><td height="10px;"></td></tr></table>
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
            <a class='content1'>Sucursal :</a>
            <select id="Sucursal1" class="letrap" style="font-weight:bold" name="Sucursal">
                <?php
                if($Sucursal=='0'){
                    echo '<option selected style="font-weight:bold" value="0">- Administración * -</option>';
                }else{
                    echo '<option value="0">Administración *</option>';
                }
                
                if($Sucursal=='1'){
                    echo '<option selected style="font-weight:bold" value="1">- Matriz -</option>';
                }else{
                    echo '<option value="1">Matriz</option>';
                }
                
                if($Sucursal=='2'){
                    echo '<option selected style="font-weight:bold" value="2">- Futura -</option>';
                }else{
                    echo '<option value="2">Futura</option>';
                }
                
                if($Sucursal=='3'){
                    echo '<option selected style="font-weight:bold" value="3">- Tepexpan -</option>';
                }else{
                    echo '<option value="3">Tepexpan</option>';
                }
                
                if($Sucursal=='4'){
                    echo '<option selected style="font-weight:bold" value="4">- Los Reyes -</option>';
                }else{
                    echo '<option value="4">Los Reyes</option>';
                }
                
                if($Sucursal=='5'){
                    echo '<option selected style="font-weight:bold" value="5">- Camarones -</option>';
                }else{
                    echo '<option value="5">Camarones</option>';
                }
                
                if($Sucursal=='6'){
                    echo '<option selected style="font-weight:bold" value="6">- San Vicente -</option>';
                }else{
                    echo '<option value="6">San Vicente</option>';
                }

                ?>
            </select>
            &nbsp; &nbsp; &nbsp; 

            <a class='content1'>Servicio :</a>
            <select class="letrap" style="font-weight:bold" id="Servicio1" name="servicio">
                <?php            
                if($Servicio=='*'){
                    echo '<option selected style="font-weight:bold" value="*">- * Todos -</option>';
                }else{
                    echo '<option value="*">* Todos</option>';
                }

                if($Servicio=="#d50000"){
                    echo '<option selected style="font-weight:bold;color:#d50000;" value="#d50000">- &#9724; Eco,Holter,Prueba Esfuerzo -</option>';
                }else{
                    echo '<option style="color:#d50000;" value="#d50000">&#9724; Eco,Holter,Prueba Esfuerzo</option>';
                }

                if($Servicio=="#E67C5C"){
                    echo '<option selected style="font-weight:bold;color:#E67C5C;" value="#E67C5C">- &#9724; Servicio a Domicilio -</option>';
                }else{
                    echo '<option style="color:#E67C5C;" value="#E67C5C">&#9724; Servicio a Domicilio</option>';
                }

                if($Servicio=="#F4511E"){
                    echo '<option selected style="font-weight:bold;color:#F4511E;" value="#F4511E">- &#9724; Traslado -</option>';
                }else{
                    echo '<option style="color:#F4511E;" value="#F4511E">&#9724; Traslado</option>';
                }

                if($Servicio=="#f69724"){
                    echo '<option selected style="font-weight:bold;color:#f69724;" value="#f69724">- &#9724; Colposcopia -</option>';
                }else{
                    echo '<option style="color:#f69724;" value="#f69724">&#9724; Colposcopia</option>';
                }

                if($Servicio=="#33B679"){
                    echo '<option selected style="font-weight:bold;color:#33B679;" value="#33B679">- &#9724; Densitometria -</option>';
                }else{
                    echo '<option style="color:#33B679;" value="#33B679">&#9724; Densitometria</option>';
                }

                if($Servicio=="#0B8043"){
                    echo '<option selected style="font-weight:bold;color:#0B8043;" value="#0B8043">- &#9724; Estudio Especial -</option>';
                }else{
                    echo '<option style="color:#0B8043;" value="#0B8043">&#9724; Estudio Especial</option>';
                }

                if($Servicio=="#039BE5"){
                    echo '<option selected style="font-weight:bold;color:#039BE5;" value="#039BE5">- &#9724; Electroencefalograma -</option>';
                }else{
                    echo '<option style="color:#039BE5;" value="#039BE5">&#9724; Electroencefalograma</option>';
                }

                if($Servicio=="#3F51B5"){
                    echo '<option selected style="font-weight:bold;color:#3F51B5;" value="#3F51B5">- &#9724; Resonancia -</option>';
                }else{
                    echo '<option style="color:#3F51B5;" value="#3F51B5">&#9724; Resonancia</option>';
                }

                if($Servicio=="#7986CB"){
                    echo '<option selected style="font-weight:bold;color:#7986CB;" value="#7986CB">- &#9724; Mastografia -</option>';
                }else{
                    echo '<option style="color:#7986CB;" value="#7986CB">&#9724; Mastografia</option>';
                }

                if($Servicio=="#8E24AA"){
                    echo '<option selected style="font-weight:bold;color:#8E24AA;" value="#8E24AA">- &#9724; PCR -</option>';
                }else{
                    echo '<option style="color:#8E24AA;" value="#8E24AA">&#9724; PCR</option>';
                }

                if($Servicio=="#616161"){
                    echo '<option selected style="font-weight:bold;color:#616161;" value="#616161">- &#9724; Tomografia -</option>';
                }else{
                    echo '<option style="color:#616161;" value="#616161">&#9724; Tomografia</option>';
                }

                ?>

            </select>
            &nbsp; &nbsp; &nbsp; 

            <a class='content1'>Status :</a>
            <select class="letrap" style="font-weight:bold" id="Status1" name="status">
                <?php            
                if($Status=='0'){
                    echo '<option selected style="font-weight:bold" value="0">- * Todos -</option>';
                }else{
                    echo '<option value="0">* Todos</option>';
                }

                if($Status=='1'){
                    echo '<option selected style="font-weight:bold" value="1">- En Proceso -</option>';
                }else{
                    echo '<option value="1">En Proceso</option>';
                }

                if($Status=='2'){
                    echo '<option selected style="font-weight:bold" value="2">- Terminada -</option>';
                }else{
                    echo '<option value="2">Terminada</option>';
                }

                if($Status=='10'){
                    echo '<option selected style="font-weight:bold" value="10">- Cancelada -</option>';
                }else{
                    echo '<option value="10">Cancelada</option>';
                }
                ?>
            </select>
            &nbsp; &nbsp; &nbsp; 

            <span class='content1'> Fecha :</span>
            <input class='letrap' style="font-weight:bold" type='date' name='FechaI' value='<?= $FechaI ?>' size='10' class='content5'></input>
           <!-- <span class='content1'> Fec.final: </span>
            <input type='datetime-local' name='FechaF' value='<?= $FechaF ?>' size='10' class='content5'></input> -->
            &nbsp; &nbsp; &nbsp; 
            
            <input type="hidden" id="Sucursal" value="<?=$Sucursal?>"></input>
            <input type="hidden" id="Status" value="<?=$Status?>"></input>
            <input type="hidden" id="Servicio" value="<?=$Servicio?>"></input>
            <input type="hidden" id="FechaI" value="<?=$FechaI?>"></input>

            <input type='submit' name='Boton' value='Busca' class='letrap'></input>
        </form>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();
                    $res = mysql_query($cSql);
                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
                    if ($busca <> '') {
                        $sql = $cSql . " AND " . $orden . " LIKE ('%" . $busca . "%') ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    } else {
                        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    }
                    $res = mysql_query($sql);
                    while ($rg = mysql_fetch_array($res)) {

                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('agendae.php?busca=$rg[id]')><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";

                        $sucnombre = $aSucursal[$rg[sucursal]];

                        if($rg[display]=='#d50000'){
                            $Servicio='Eco,Holter,Prueba Esfuerzo';
                        }elseif($rg[display]=='#E67C5C'){
                            $Servicio='Servicio a Domicilio';
                        }elseif($rg[display]=='#F4511E'){
                            $Servicio='Traslado';
                        }elseif($rg[display]=='#f69724'){
                            $Servicio='Colposcopia';
                        }elseif($rg[display]=='#33B679'){
                            $Servicio='Densitometria';
                        }elseif($rg[display]=='#0B8043'){
                            $Servicio='Estudio Especial';
                        }elseif($rg[display]=='#039BE5'){
                            $Servicio='Electroencefalograma';
                        }elseif($rg[display]=='#3F51B5'){
                            $Servicio='Resonancia';
                        }elseif($rg[display]=='#7986CB'){
                            $Servicio='Mastografia';
                        }elseif($rg[display]=='#8E24AA'){
                            $Servicio='PCR';
                        }elseif($rg[display]=='#616161'){
                            $Servicio='Tomografia';
                        }

                        echo "<td align='center' class='letrap'> $sucnombre</td>";
                        
                        echo "<td align='left' class='letrap'><b><font color=$rg[display]>&#9724; $Servicio</font></b></td>";

                        Display($aCps, $aDat, $rg);

                        if ($rg[status] == 1) {
                            $sts = '<b><font color=#1E8449>En Proceso <i class="fa fa-spinner" aria-hidden="true"></i></font><b>';
                        } elseif ($rg[status] == 2) {
                            $sts = '<b><font color=#1A5276>Terminada <i class="fa fa-check-square-o" aria-hidden="true"></i></font><b>';
                        } elseif ($rg[status] == 10) {
                            $sts = '<b><font color=#C0392B>Cancelada <i class="fa fa-times" aria-hidden="true"></i></font><b>';
                        }

                        echo "<td align='center' class='letrap'>$sts</td>";

                        echo "</tr>";

                        $nRng++;
                    }
                    ?>
                </td>
            </tr>
        </table>
        <?php
        PonPaginacionventana(true);           #-------------------pon los No.de paginas-------------------    
        ?>
        <table><td><tr><a class='cMsj'> <?= $_REQUEST[msj] ?></a></tr></td></table>
        <?php CuadroInferior4($busca); ?>
    </body>
</html>
<?php
mysql_close();
?>
