<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
 // date_default_timezone_set("America/Mexico_City");

$link = conectarse();

if (isset($_REQUEST[busca])) {

    if ($_REQUEST[busca] == ini) {

        $Fecha = date("Y-m-d");

        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', 'menu.php', $Fecha, $Fecha, '*', '', 2, '','Sin filtro');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
if (isset($_REQUEST[FechaI])) {
    $_SESSION['OnToy'][5] = $_REQUEST[FechaI];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST[FechaF])) {
    $_SESSION['OnToy'][6] = $_REQUEST[FechaF];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST[FiltroCia])) {
    $_SESSION['OnToy'][7] = $_REQUEST[FiltroCia];
}
if (isset($_REQUEST[Todo])) {
    $_SESSION['OnToy'][8] = $_REQUEST[Todo];
}
if (isset($_REQUEST[EnvioCorreo])) {
    $_SESSION['OnToy'][9] = $_REQUEST[EnvioCorreo];
}
if (isset($_REQUEST[Pagado])) {
    $_SESSION['OnToy'][10] = $_REQUEST[Pagado];
}
if (isset($_REQUEST[Correo])) {
    $_SESSION['OnToy'][11] = $_REQUEST[Correo];
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$busca = strtolower($busca);
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$FechaI = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION[OnToy][6];          //Pagina a la que regresa con parametros  
$GnSuc = $_SESSION[OnToy][7];          //Que sucursal estoy checando
$GTodo = $_SESSION[OnToy][8];          //Todos
$EnvioCorreo = $_SESSION[OnToy][9];      //Filtro si se envian correos
$Pagado = $_SESSION[OnToy][10];         //Filtro si es que estan pagados o no
$GCorreo = $_SESSION[OnToy][11];         //Filtro correo

$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$Titulo = "Ordenes de estudio";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 66;             //Numero de query dentro de la base de datos
#Intruccion a realizar si es que mandan algun proceso
if ($op == 'Si') {                    //Elimina rg
    $lUp = mysql_query("DELETE FROM ot WHERE orden='$_REQUEST[cId]' limit 1");
    $Msj = "Registro eliminado";
    //$Msj = "Opcion deshabilitada";
} elseif ($op == 'rs') {
    $Up = mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
    $op = '';
}

if($GnSuc=="*"){
    $Sucursal="";
}else{
    $Sucursal=" and ot.suc='$GnSuc'";
}

if($GCorreo=="Sin filtro"){
    $Correo="";
}elseif($GCorreo=="* Correos"){
    $Correo=" and (ot.entemailpac='1' or ot.entemailmed='1' or ot.entemailinst='1')";
}elseif($GCorreo=="No Enviados"){
    $Correo=" and (ot.entemailpac='1' or ot.entemailmed='1' or ot.entemailinst='1') and ot.stenvmail='NO ENVIADO'";
}elseif($GCorreo=="Enviados"){
    $Correo=" and (ot.entemailpac='1' or ot.entemailmed='1' or ot.entemailinst='1') and ot.stenvmail='ENVIADO'";
}elseif($GCorreo=="Parcial"){
    $Correo=" and (ot.entemailpac='1' or ot.entemailmed='1' or ot.entemailinst='1') and ot.stenvmail='PARCIAL'";
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 6) {
    $Dsp = 'Filtro activo';
}

  #Deshago la busqueda por palabras(una busqueda inteligte;
  
  $Palabras  = str_word_count($busca);  //Dame el numero de palabras
  if($Palabras > 1){
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" cli.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and cli.nombrec like '%$P[$i]%' ";}
     }
     //$Suc='*';
  }else{
     $BusInt=" cli.nombrec like '%$busca%' ";  
    // $Suc='*';
  }

if($EnvioCorreo == 2){
    $CorreoEnv = " ";
}else if($EnvioCorreo == 1 OR $EnvioCorreo == 0){
    $CorreoEnv = " AND entemailpac = $EnvioCorreo OR entemailmed = $EnvioCorreo OR entemailinst = $EnvioCorreo ";
    if($EnvioCorreo == 1){
        $EnvioCorreoNombre = "Si";
    }else{
        $EnvioCorreoNombre = "No";
    }
}
if($Pagado !== ""){
    $StatusPago = " AND ot.pagada = '$Pagado' ";
}

  if( $busca == ''){

    $SqlB3="SELECT ot.orden
    FROM ot
    where ot.fecha='$FechaI' order by hora limit 1";

    $Sql3=mysql_query($SqlB3,$link);

    $S3=mysql_fetch_array($Sql3);


    $SqlB4="SELECT ot.orden
    FROM ot
    where ot.fecha='$FechaF' order by orden desc limit 1";

    $Sql4=mysql_query($SqlB4,$link);

    $S4=mysql_fetch_array($Sql4);

    $cSql="SELECT $Qry[campos],cli.numveces,ot.suc,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,IF (ot.pagada = 'No','F5B7B1','') statuspago,cli.cliente,ot.stenvmail
    FROM ot
    INNER JOIN cli on cli.cliente=ot.cliente
    WHERE  ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $Sucursal $Correo";

  }elseif( $busca < 'a'){

    $cSql="SELECT $Qry[campos],cli.numveces,ot.suc,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,IF (ot.pagada = 'No','F5B7B1','') statuspago,cli.cliente,ot.stenvmail
    FROM ot
    INNER JOIN cli on cli.cliente=ot.cliente
    WHERE ot.orden >= '$busca'";

  }else{

    $cSql="SELECT $Qry[campos],cli.numveces,ot.suc,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,IF (ot.pagada = 'No','F5B7B1','') statuspago,cli.cliente,ot.stenvmail
    FROM ot
    INNER JOIN cli on cli.cliente=ot.cliente
    WHERE $BusInt";

  }

//echo $cSql;
$aCps = SPLIT(",", $Qry[campos]);
$aIzq = array(" ", "-", "-", "", "-", "-");    //Arreglo Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("Recep", "", "", "Entrega", "", "", "Pagado", "", "", "Correos", "", "", "Sucursal", "", "", "Visitas", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ordenes de estudio</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu,$Gusr);
        ?>
        <table><tr><td height="10px;"></td></tr></table>
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
            <span  class="letrap"> Sucursal: </span>

            <select class='letrap' name='FiltroCia' onChange='frmfiltro.submit();'>
                <?php
                $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' ORDER BY id");
                while ($Cia = mysql_fetch_array($CiaA)) {
                    if($Cia[id]==$GnSuc){
                        echo '<option selected style="font-weight:bold" value="' . $Cia[id] . '">- ' . $Cia[alias] . ' -</option>';
                    }else{
                        echo '<option value="' . $Cia[id] . '">' . $Cia[alias] . '</option>';
                    }
                }
                if ($GnSuc == '*') {
                    echo "<option selected style='font-weight:bold' value='*'>- * Todos -</select>";
                }else{
                    echo "<option value='*'>* Todos</option>";
                }
                ?>
            </select>

            <span  class="letrap"> Correo: </span>
            <select class='letrap' name='Correo' onChange='frmfiltro.submit();'>
                <?php
                    $NEnviados='';
                    $Parcial='';
                    $Enviados='';
                    $Corre='';
                    $SFiltro='';
                    
                    if($GCorreo=="No Enviados"){
                        $NEnviados='selected style="font-weight:bold"';
                    }elseif($GCorreo=="Parcial"){
                        $Parcial='selected style="font-weight:bold"';
                    }elseif($GCorreo=="Enviados"){
                        $Enviados='selected style="font-weight:bold"';
                    }elseif($GCorreo=="* Correos"){
                        $Corre='selected style="font-weight:bold"';
                    }elseif($GCorreo=="Sin filtro"){
                        $SFiltro='selected style="font-weight:bold"';
                    }

                    echo "<option ".$NEnviados." value='No Enviados'> No Enviados </option>";
                    echo "<option ".$Parcial." value='Parcial'> Parcial </option>";
                    echo "<option ".$Enviados." value='Enviados'> Enviados </option>";
                    echo "<option ".$Corre." value='* Correos'> * Correos </option>";
                    echo "<option ".$SFiltro." value='Sin filtro'> Sin filtro </option>";
                    echo "</select>";
                ?>
            <span  class="letrap"> Fec.inicial: </span>
            <input type='date' name='FechaI' value='<?= $FechaI ?>' size='10' class='letrap'></input> 
            <span  class="letrap"> Fec.final: </span>
            <input type='date' name='FechaF' value='<?= $FechaF ?>' size='10' class='letrap'></input> 

            <input type='submit' name='Boton' value='enviar' class='letrap'></input>
           <!-- <a  class='edit' href='?Todo=*'><i class='fa fa-eye fa-2x' aria-hidden='true'></i> Ver todo </a> -->
        </form>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();

                    $res = mysql_query($cSql);

                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                    $sql = $cSql . $cWhe . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    //echo $sql;

                    $res = mysql_query($sql);

                    $Pos = strrpos($_SERVER[PHP_SELF], ".");
                    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
                    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

                    while ($rg = mysql_fetch_array($res)) {

                        $nSuc = $rg[suc];
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        if ($rg[statuspago]) {
                            $Fdo = $rg[statuspago];
                        }
                        $Sucursal = $aSucursal[$nSuc];
                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        //echo "<tr>";

                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[orden]'>Editar</a></td>";

                        echo "<td align='left' class='letrap'><a class='letra' href=javascript:wingral('impotpdf-ant.php?busca=$rg[orden]')><i class='fa fa-print fa-lg' style='color:#3374FF' aria-hidden='true'></i></a></td>";

                        echo "<td class='Seleccionar' align='right'><a class='edit' href=javascript:wingral('resultapdf_ip3.php?clnk=bh&Orden=$rg[orden]&Estudio=BH&Depto=TERMINADA&op=im&alterno=4')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td>";


                        Display($aCps, $aDat, $rg);

                        if ($rg[encaja] == 'Si' OR $rg[status] == 'Entregada') {
                            echo "<td align='center'><a href=javascript:winuni('ordenrec.php?Recibeencaja=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></a></td>";
                        } else {
                            echo "<td align='center'><a href=javascript:winuni('ordenrec.php?Recibeencaja=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-times' style='color:red;' aria-hidden='true'></i></a></td>";
                        }

                        if ($rg[status] <> 'Entregada') {
                            echo "<td align='center'><a href=javascript:winmed('ordenpac.php?Entregapac=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-times' style='color:red;' aria-hidden='true'></i></a></td>";
                        } else {
                            echo "<td align='center'><a href=javascript:winmed('ordenpac.php?Entregapac=$Gusr&Orden=$rg[orden]&boton=Enviar')><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></a></td>";
                        }
                        if ($rg[pagada] == 'Si') {
                            echo "<td><a class='edit' style='color:green;' aria-hidden='true'href=javascript:winuni('ingreso2.php?busca=$rg[orden]&boton=Enviar')>$rg[pagada]</a></td>";
                        } else {
                            echo "<td><a class='edit'  style='color:red;' aria-hidden='true' href=javascript:winuni('ingreso2.php?busca=$rg[orden]&boton=Enviar')><b>$rg[pagada]</b></td>";
                        }
                        if ($rg[entemailpac] == '1' or $rg[entemailmed] == '1' or $rg[entemailinst] == '1') {
                            
                            if($rg[stenvmail]=='NO ENVIADO'){
                                echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-envelope-o' style='color:red;' aria-hidden='true'></i></a></td>";
                            }elseif($rg[stenvmail]=='PARCIAL'){
                                echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-envelope-open-o' style='color:blue;' aria-hidden='true'></i></a></td>";
                            }elseif($rg[stenvmail]=='ENVIADO'){
                                echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-paper-plane-o' style='color:black;' aria-hidden='true'></i></a></td>";
                            }

                        } else {
                            echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')>$Gfont No</a></td>";
                        }
                        echo "<td class='content1'>$Sucursal</td>";
                        echo "<td class='letrap'><a class='content1' href=javascript:winuni('repots.php?busca=$rg[cliente]')><b>$rg[numveces]</b></a></td>";
                        
                        echo "</tr>";

                        $nRng++;
                    }
                    ?>

                </td>
            </tr>
        </table>
        <?php
        PonPaginacion(false);           #-------------------pon los No.de paginas-------------------    
        CuadroInferior2($busca);
        ?>
    </body>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>

