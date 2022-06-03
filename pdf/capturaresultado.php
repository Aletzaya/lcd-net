<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST["busca"])) {

    if ($_REQUEST["busca"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }
        $Fecha = date("Y-m-d");
        $FechaI = $FechaF = date("Y-m-d");
        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', $Retornar, $Fecha, $Fecha);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST["busca"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST["busca"];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST["pagina"])) {
    $_SESSION['OnToy'][1] = $_REQUEST["pagina"];
}
if (isset($_REQUEST["orden"])) {
    $_SESSION['OnToy'][2] = $_REQUEST["orden"];
}
if (isset($_REQUEST["Sort"])) {
    $_SESSION['OnToy'][3] = $_REQUEST["Sort"];
}
if (isset($_REQUEST["Ret"])) {
    $_SESSION['OnToy'][4] = $_REQUEST["Ret"];
}
if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][5] = $_REQUEST["FechaI"];
    $_SESSION['OnToy'][8] = '';
    $FechaI = $_REQUEST["FechaI"];
}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["FechaF"];
    $_SESSION['OnToy'][8] = '';
    $FechaF = $_REQUEST["FechaF"];
}

$GnSuc = $_SESSION["OnToy"][7];

if ($GnSuc == "*") {
    $Sucursal = " ";
} else {
    $Sucursal = " and ot.suc='$GnSuc'";
}         




//Que sucursal estoy checando

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];



$RetSelec = $_SESSION["OnToy"][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

#Variables comunes;
$Cat='Captura';
$Titulo = "Captura de Resultados ::..";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 73;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 10) {
    $Dsp = 'Filtro activo';
}


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



#Armo el query segun los campos tomados de qrys;


if( $busca == ''){

    $cSql = "SELECT $Qry[campos],ot.suc,ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.hora,ot.horae,ot.recepcionista,cli.numveces,cli.cliente,ot.importe,ot.servicio,ot.idprocedencia,ot.responsableco "
    . "FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND $BusInt "
    . "AND ot.fecha >= '" . $_SESSION['OnToy'][5] . "' AND ot.fecha <= '" . $_SESSION['OnToy'][6] . "'  $Qry[filtro] $Sucursal ";

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos],ot.suc,ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.hora,ot.horae,ot.recepcionista,cli.numveces,cli.cliente,ot.importe,ot.servicio,ot.idprocedencia,ot.responsableco "
    . "FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND ot.orden=$busca "
    . "AND ot.fecha >= '" . $_SESSION['OnToy'][5] . "' AND ot.fecha <= '" . $_SESSION['OnToy'][6] . "' $Qry[filtro]  ";

}else{
    
    $cSql = "SELECT $Qry[campos],ot.suc,ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.hora,ot.horae,ot.recepcionista,cli.numveces,cli.cliente,ot.importe,ot.servicio,ot.idprocedencia,ot.responsableco "
    . "FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND $BusInt "
    . "AND ot.fecha >= '" . $_SESSION['OnToy'][5] . "' AND ot.fecha <= '" . $_SESSION['OnToy'][6] . "' $Qry[filtro] ";

}




//echo $cSql;
$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$FechaI = $_SESSION['OnToy'][5];
$FechaF = $_SESSION['OnToy'][6];
$aIzq = array(" ", "-", "-","Suc", "-", "-");      //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array();    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Captura Resultados</title>
        <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <form name='frmfiltro' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <table>
                <tr>
                <td class="letrap">
                        Suc: 
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


                    <td class="letrap">
                        Fec.inicial: 
                        <input type='date' style="width: 150px;" name='FechaI' value='<?= $FechaI ?>' size='10' class='letrap'></input> 
                        Fec.final:
                        <input type='date' style="width: 150px;" name='FechaF' value='<?= $FechaF ?>' size='10' class='letrap'></input> 
                        <input type='submit' name='Boton' value='enviar' class='letrap'></input>
                        <input type='hidden' name='Dpto' value='<?= $Dpto ?>' ></input>
                        <input type='hidden' name='Subdepto' value='<?= $Subdpto ?>' ></input>
                       <!-- <a  class='edit' href='?Todo=*'><i class='fa fa-eye fa-2x' aria-hidden='true'></i> Ver todo </a> -->

                    </td>
                </tr>
            </table>
        </form>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">



                    <?php
                    PonEncabezado();

                    $res = mysql_query($cSql);

                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                    $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;

                    $res = mysql_query($sql);

                    $Pos = strrpos($_SERVER[PHP_SELF], ".");
                    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';
                    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';

                    while ($rg = mysql_fetch_array($res)) {


                        if($rg[suc]=='0'){
                            $Nsucursal='Adm';
                        }elseif($rg[suc]=='1'){
                            $Nsucursal='Mtz';
                        }elseif($rg[suc]=='2'){
                            $Nsucursal='OHF';
                        }elseif($rg[suc]=='3'){
                            $Nsucursal='Tpx';
                        }elseif($rg[suc]=='4'){
                            $Nsucursal='Rys';
                        }elseif($rg[suc]=='5'){
                            $Nsucursal='Cam';
                        }elseif($rg[suc]=='6'){
                            $Nsucursal='SVC';
                        }

                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;

                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        //echo "<tr>";
                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$uLink?busca=$rg[orden]'><i class='fa fa-list fa-2x' aria-hidden='true'></i></a></td>";
                        
                        echo"<td class='letrap' >$Nsucursal</td>";
                        Display($aCps, $aDat, $rg);

                        echo "</tr>";

                        $nRng++;
                    }
                    ?>
                </td>
            </tr>
        </table>
        <?php
        PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

        CuadroInferior4($busca);
        ?>

    </body>

    <script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>
