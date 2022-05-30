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

        $_SESSION["OnToy"] = array('', '', 'regeq.id', 'Asc', $Retornar, '* Todos', '* Todos', '* Todos', $Fecha, $Fecha);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)

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
$Id = 80;            //Numero de query dentro de la base de datos
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
    $Stat=" and regeq.status='$Status'";
}

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" regeq.observaciones like '%$P[$i]%' ";}else{$BusInt=$BusInt." and regeq.observaciones like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" regeq.observaciones like '%$busca%' ";  
// $Suc='*';
}



if( $busca == ''){



    $cSql = "SELECT $Qry[campos],regeq.status, regeq.id_eq, regeq.id, regeq.usr FROM $Qry[froms] WHERE  regeq.fechaeven>='$FechaI' and regeq.fechaeven<='$FechaF'  $Stat order by fechaeven desc" ; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos],regeq.status, regeq.id_eq, regeq.id, regeq.usr FROM $Qry[froms] WHERE  id='$busca'";

}else{

    $cSql = "SELECT $Qry[campos],regeq.status,regeq.id_eq, regeq.id, regeq.usr FROM regeq WHERE $BusInt";
}

//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Id ", "-","-","Equipo","-","-",);    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("Sts/Est", "-", "-","Usuario", "-", "-","Sucursal", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
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
    
        <td width="74%"><p align="left"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Reporte de Bitacora</strong></font></p>
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

        &nbsp; &nbsp; 
            <span  class="letrap"> Status: </span>
            <select class='letrap' name='Status' onChange='frmfiltro.submit();'>
                <?php
                    $Optimo='';
                    $Observacion='';
                    $Fuera_de_Servicio='';
                    $Almacenado='';
                    $Baja='';
                    $Sin_Registro='';
                    
                    if($Status=="Optimo"){
                        $Optimo='selected style="font-weight:bold"';
                    }elseif($Status=="Observacion"){
                        $Observacion='selected style="font-weight:bold"';
                    }elseif($Status=="Fuera_de_Servicio"){
                        $Fuera_de_Servicio='selected style="font-weight:bold"';
                    }elseif($Status=="Almacenado"){
                        $Almacenado='selected style="font-weight:bold"';
                    }elseif($Status=="Baja"){
                        $Baja='selected style="font-weight:bold"';
                    }elseif($Status==""){
                        $Sin_Registro='selected style="font-weight:bold"';
                    }elseif($Status=="* Todos"){
                        $Todos='selected style="font-weight:bold"';
                    }

                    echo "<option ".$Optimo." value='Optimo'> Optimo </option>";
                    echo "<option ".$Observacion." value='Observacion'> Observacion </option>";
                    echo "<option ".$Fuera_de_Servicio." value='Fuera_de_Servicio'> Fuera_de_Servicio </option>";
                    echo "<option ".$Almacenado." value='Almacenado'> Almacenado </option>";
                    echo "<option ".$Baja." value='Baja'> Baja </option>";
                    echo "<option ".$Sin_Registro." value=''> Sin_Registro </option>";
                    echo "<option ".$Todos." value='* Todos'> * Todos </option>";
                    echo "</select>";
                ?>

                
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

        echo "<td class='letrap' align='center' width='50'>$rg[id]</td>";

        $cSql2 = "SELECT * FROM eqp WHERE id=$rg[id_eq] "; 
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

        echo "<td class='letrap' align='left' width='250' >$rg2[id]-$rg2[nombre]</td>";

        Display($aCps, $aDat, $rg);


        switch($rg[status]) {
        case Optimo:
            $Sts="<i class='fa fa-square fa-lg' style='color:#239B56;' aria-hidden='true'></i>";
            break;
        case Observacion:
            $Sts="<i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i>";
            break;
        case Fuera_de_Servicio:
            $Sts="<i class='fa fa-square fa-lg' style='color:#E74C3C;' aria-hidden='true'></i>";
            break;
        case Almacenado:
            $Sts="<i class='fa fa-square fa-lg' style='color:#85929E;' aria-hidden='true'></i>";
            break;
        case Baja:
            $Sts="<i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i>";
            break;
        default:
            $Sts="<i class='fa fa-exclamation-circle fa-lg' style='color:#E74C3C;' aria-hidden='true'></i>";
            break;
        }


        echo "<td class='Seleccionar' align='center'>".$Sts."</a></td>";
        echo "<td class='letrap' align='center'  width='100'>$rg[usr]</a></td>";
        echo "<td class='letrap' align='center' width='100'>$sucnombre</td>";


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
