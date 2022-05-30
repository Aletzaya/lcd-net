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

        $_SESSION["OnToy"] = array('', '', 'eqp.id', 'Asc', $Retornar, '* Todos', '* Todos', '* Todos');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)

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

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$GnSuc = $_SESSION["OnToy"][5];          //Que sucursal estoy checando
$Status = $_SESSION["OnToy"][6];          //Que sucursal estoy checando
$Departamento = $_SESSION["OnToy"][7];          //Que sucursal estoy checando

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

#Variables comunes;
$Titulo = "Medicos";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 51;            //Numero de query dentro de la base de datos
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
    $Stat=" and eqp.status='$Status'";
}

if($Departamento=="* Todos"){
    $Depto="";
}else{
    $Depto=" and eqp.departamento='$Departamento'";
}

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" eqp.nombre like '%$P[$i]%' ";}else{$BusInt=$BusInt." and eqp.nombre like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" eqp.nombre like '%$busca%' or eqp.marca like '%$busca%'";  
// $Suc='*';
}



if( $busca == ''){

    $cSql = "SELECT $Qry[campos],eqp.status,eqp.sucursal FROM $Qry[froms] WHERE id>= 0 $Sucursal $Stat $Depto"; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos],eqp.status,eqp.sucursal FROM $Qry[froms] WHERE  id='$busca'";

}else{

    $cSql = "SELECT $Qry[campos],eqp.status,eqp.sucursal FROM eqp WHERE $BusInt";
}

//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-","Carat ", "-", "-","Bitac");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("Sucursal", "-", "-","Sts/Est", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Equipos ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu,$Gusr);
    ?>
    <script src="./controladores.js"></script>

    <table><tr><td height="10px;"></td></tr></table>
        <div align="right">
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
            <span  class="letrap"> Sucursal: </span>

            <select class='letrap' name='FiltroCia' onChange='frmfiltro.submit();'>
                <?php
                $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' and id<>'100' ORDER BY id");
                while ($Cia = mysql_fetch_array($CiaA)) {
                    if($Cia[id]==$GnSuc){
                        echo '<option selected style="font-weight:bold" value="' . $Cia[id] . '">- ' . $Cia[alias] . ' -</option>';
                    }else{
                        echo '<option value="' . $Cia[id] . '">' . $Cia[alias] . '</option>';
                    }
                }

                if($GnSuc==11){
                    echo '<option selected style="font-weight:bold" value="11">- OHF - Torre -</option>';
                    echo '<option value="12">OHF - Urgencia</option>';
                    echo '<option value="13">OHF - Hospitalizacion</option>';
                    echo "<option value='* Todos'>* Todos</option>";
                }elseif($GnSuc==12){
                    echo '<option value="11">OHF - Torre</option>';
                    echo '<option selected style="font-weight:bold" value="12">- OHF - Urgencia -</option>';
                    echo '<option value="13">OHF - Hospitalizacion</option>';
                    echo "<option value='* Todos'>* Todos</option>";
                }elseif($GnSuc==13){
                    echo '<option value="11">OHF - Torre</option>';
                    echo '<option value="12">OHF - Urgencia</option>';
                    echo '<option selected style="font-weight:bold" value="13">- OHF - Hospitalizacion -</option>';
                    echo "<option value='* Todos'>* Todos</option>";
                }elseif($GnSuc == '* Todos') {
                    echo '<option value="11">OHF - Torre</option>';
                    echo '<option value="12">OHF - Urgencia</option>';
                    echo '<option value="13">OHF - Hospitalizacion</option>';
                    echo "<option selected style='font-weight:bold' value='* Todos'>- * Todos -</select>";
                }else{
                    echo '<option value="11">OHF - Torre</option>';
                    echo '<option value="12">OHF - Urgencia</option>';
                    echo '<option value="13">OHF - Hospitalizacion</option>';
                    echo "<option value='* Todos'>* Todos</option>";
                }
                ?>
            </select>
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
            &nbsp; &nbsp; 
            <span  class="letrap"> Departamento: </span>
            <select class='letrap' name='Departamento' onChange='frmfiltro.submit();'>
                <?php
                    $Laboratorio='';
                    $Rayos='';
                    $Hospital='';
                    $Todos='';
                    
                    if($Departamento=="Laboratorio"){
                        $Laboratorio='selected style="font-weight:bold"';
                    }elseif($Departamento=="Rayos_X"){
                        $Rayos='selected style="font-weight:bold"';
                    }elseif($Departamento=="Hospital"){
                        $Hospital='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_1"){
                        $Consultorio_1='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_2"){
                        $Consultorio_2='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_3"){
                        $Consultorio_3='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_4"){
                        $Consultorio_4='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_5"){
                        $Consultorio_5='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_6"){
                        $Consultorio_6='selected style="font-weight:bold"';
                    }elseif($Departamento=="Consultorio_7"){
                        $Consultorio_7='selected style="font-weight:bold"';
                    }elseif($Departamento=="* Todos"){
                        $Todos='selected style="font-weight:bold"';
                    }
                    echo "<option ".$Laboratorio." value='Laboratorio'> Laboratorio </option>";
                    echo "<option ".$Rayos." value='Rayos_X'> Rayos X </option>";
                    echo "<option ".$Hospital." value='Hospital'> Hospital </option>";
                    echo "<option ".$Consultorio_1." value='Consultorio_1'> Consultorio_1 </option>";
                    echo "<option ".$Consultorio_2." value='Consultorio_2'> Consultorio_2 </option>";
                    echo "<option ".$Consultorio_3." value='Consultorio_3'> Consultorio_3 </option>";
                    echo "<option ".$Consultorio_4." value='Consultorio_4'> Consultorio_4 </option>";
                    echo "<option ".$Consultorio_5." value='Consultorio_5'> Consultorio_5 </option>";
                    echo "<option ".$Consultorio_6." value='Consultorio_6'> Consultorio_6 </option>";
                    echo "<option ".$Consultorio_7." value='Consultorio_1'> Consultorio_7 </option>";
                    echo "<option ".$Todos." value='* Todos'> * Todos </option>";
                    echo "</select>";
                ?>
                &nbsp; &nbsp; <a class='edit' href=javascript:wingral2('calendarioeqp.php?suc=*+Todos')><i class='fa fa-calendar fa-2x' aria-hidden='true'></i></a> &nbsp; &nbsp;
                &nbsp; <a class='edit' href=javascript:wingral2('graficoeqp.php')><i class='fa fa-pie-chart fa-2x' aria-hidden='true'></i></a> &nbsp; &nbsp; 
                &nbsp; <a class='edit' href=javascript:wingral2('repequipo.php?busca=ini')><i class='fa fa-file-text fa-2x' aria-hidden='true'></i></a> &nbsp; &nbsp; 
                &nbsp; <a class='edit' href=javascript:wingral2('repequipomant.php?busca=ini')><i class='fa fa-cog fa-2x' aria-hidden='true'></i></a> &nbsp; &nbsp; 


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

    $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;


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

        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('$cLink?busca=$rg[id]')><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('equipospdf.php?busca=$rg[id]')><i class='fa fa-print fa-2x' aria-hidden='true'></i></a></td>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('equipospdf1.php?busca=$rg[id]')><i class='fa fa-print fa-2x' style='color:red' aria-hidden='true'></i></a></td>";

        Display($aCps, $aDat, $rg);

        if($rg[sucursal]==11){
            $sucnombre = "OHF - Torre";
        }elseif($rg[sucursal]==12){
            $sucnombre = "OHF - Urgencia";
        }elseif($rg[sucursal]==13){
            $sucnombre = "OHF - Hospitalizacion";
        }else{
            $sucnombre = $aSucursal[$rg[sucursal]];
        }

        echo "<td class='letrap' align='center'>$sucnombre</td>";

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

        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral2('equiposestad.php?busca=$rg[id]')>".$Sts."</a></td>";

        echo "</tr>";

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
