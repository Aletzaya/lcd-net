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
        $Fecha = date("Y-m-d");

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'fechas_equipos.id', 'Asc', $Retornar, '* Todos', '* Todos', '* Todos', $Fecha, $Fecha);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)

    } elseif ($_REQUEST["busca"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
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
if (isset($_REQUEST["FiltroCia"])) {
    $_SESSION['OnToy'][5] = $_REQUEST["FiltroCia"];
}
if (isset($_REQUEST["Status"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["Status"];
}
if (isset($_REQUEST["Departamento"])) {
    $_SESSION['OnToy'][7] = $_REQUEST["Departamento"];
}

if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][8] = $_REQUEST["FechaI"];
    $_SESSION['OnToy'][10] = '';

}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][9] = $_REQUEST["FechaF"];
    $_SESSION['OnToy'][10] = '';

}
#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$GnSuc = $_SESSION["OnToy"][5];          //Que sucursal estoy checando
$Status = $_SESSION["OnToy"][6];          //Que sucursal estoy checando
$Departamento = $_SESSION["OnToy"][7];          //Que sucursal estoy checando
$GTodo = $_SESSION[OnToy][10];  
$FechaI = $_SESSION[OnToy][8];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION[OnToy][9]; 

$Cat='Equi';

$Mnu = $_SESSION["Mnu"];

$RetSelec = $_SESSION["OnToy"][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION["OnToy"][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];



$FechaI = $_REQUEST["FechaI"] == "" ? date("Y-m-d ", strtotime(date("Y-m-d") . "-15 days")) : $_REQUEST["FechaI"];
$FechaF = $_REQUEST["FechaF"] == "" ? date("Y-m-d") : $_REQUEST["FechaF"];


#Variables comunes;
$Titulo = "Medicos";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 81;            //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry["filtro"]) > 2) {
    $Dsp = 'Filtro activo';
}


if($GnSuc=="* Todos"){
    $Sucursal="";
}else{
    $Sucursal=" and eqp.sucursal='$GnSuc'";
}



if($Status=="* Todos"){
    $Stat="";
}else{
    $Stat=" and fechas_equipos.status='$Status'";
}

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" fechas_equipos.observaciones like '%$P[$i]%' ";}else{$BusInt=$BusInt." and fechas_equipos.observaciones like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" fechas_equipos.observaciones like '%$busca%' ";  
// $Suc='*';
}



if( $busca == ''){



    $cSql = "SELECT $Qry[campos],fechas_equipos.status, fechas_equipos.id_equipo, fechas_equipos.proveedor, fechas_equipos.id, fechas_equipos.fecha FROM $Qry[froms] WHERE  fechas_equipos.fecha>='$FechaI' and fechas_equipos.fecha<='$FechaF' $Stat order by fecha desc" ; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos],fechas_equipos.status, fechas_equipos.id_equipo, fechas_equipos.proveedor, fechas_equipos.id, fechas_equipos.fecha FROM $Qry[froms] WHERE  id='$busca'";

}else{

    $cSql = "SELECT $Qry[campos],fechas_equipos.status, fechas_equipos.id_equipo, fechas_equipos.proveedor, fechas_equipos.id, fechas_equipos.fecha FROM fechas_equipos WHERE $BusInt";
}

//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Id ", "-","-","Fecha ", "-","-","Equipo","-","-","Proveedor", "-", "-",);    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array(" Sucursal", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Reporte Equipos::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>

    <?php
    echo '<body topmargin="1">';
    ?>
    <table width="100%" border="0">
      <tr> 
        <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
          </div></td>
    
        <td width="74%"><p align="left"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Reporte de  Mantenimiento y Reparacion </strong></font></p>
          </td>
      </tr>
    </table>
    <script src="./controladores.js"></script>

    <table><tr><td height="10px;"></td></tr></table>
        <div align="right">
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
        
        <span  class="letrap"> Fec.inicial: </span>
        <input type='date' name='FechaI' value='<?= $FechaI ?>' size='10' class='letrap'></input> 
        <span  class="letrap"> Fec.final: </span>
        <input type='date' name='FechaF' value='<?= $FechaF ?>' size='10' class='letrap'></input> 


                
                        <input type='submit' name='Boton' value='enviar' class='letrap'></input>

        </form>
        </div>
<?php
    //submenu();
//Tabla contenedor de brighs
echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
echo '<tr>';
echo '<tr>';
echo '<td height="380" valign="top">';

    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $sql = $cSql . " LIMIT " . $limitInf . "," . $tamPag;


    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        //echo "<tr>";

        echo "<td class='letrap' align='center'>$rg[id]</td>";
        echo "<td class='letrap' align='center' width='100' >$rg[fecha]</td>";

        $cSql2 = "SELECT * FROM eqp WHERE id=$rg[id_equipo] "; 
        $res2 = mysql_query($cSql2);
        $rg2 = mysql_fetch_array($res2);

        if($rg2[sucursal]==11){
            $sucnombre = "OHF - Torre";
        }elseif($rg2[sucursal]==12){
            $sucnombre = "OHF - Urgencia";
        }elseif($rg2[sucursal]==13){
            $sucnombre = "OHF - Hospitalizacion";
        }else{
            $sucnombre = $aSucursal[$rg2[sucursal]];
        }

        echo "<td class='letrap' align='left' width='250'>$rg2[id]-$rg2[nombre]</td>";


        $cSql3 = "SELECT * FROM prbVentaMantenimiento WHERE id=$rg[proveedor] "; 
        $res3 = mysql_query($cSql3);
        $rg3 = mysql_fetch_array($res3);

        echo "<td class='letrap' align='left' width='250' >$rg3[id]-$rg3[proveedor]</td>";

        Display($aCps, $aDat, $rg);

        echo "<td class='letrap' align='center'>$sucnombre</td>";

        $nRng++;

     
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    PonPaginacionventana(true);           #-------------------pon los No.de paginas-------------------    

    CuadroInferior4($busca);


    echo '</body>';
    ?>

</html>
<?php
mysql_close();
?>
