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

        $_SESSION["OnToy"] = array('', '', 'el.id', 'Asc', $Retornar,$Fecha, $Fecha);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST["busca"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST["busca"];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST["pagina"])) {
    $_SESSION['OnToy'][1] = $_REQUEST["pagina"];
}
if (isset($_REQUEST[orden])) {
    $_SESSION['OnToy'][2] = $_REQUEST["orden"];
}
if (isset($_REQUEST[Sort])) {
    $_SESSION['OnToy'][3] = $_REQUEST["Sort"];
}
if (isset($_REQUEST[Ret])) {
    $_SESSION['OnToy'][4] = $_REQUEST["Ret"];
}
if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][5] = $_REQUEST["FechaI"];
}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["FechaF"];
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$FechaI = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION[OnToy][6]; 
$Cat='Comp';
$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION["OnToy"][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

#Variables comunes;
$Titulo = "Compras para laboratorio";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 15;             //Numero de query dentro de la base de datos
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);
$Palabras = str_word_count($busca);  //Dame el numero de palabras




$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" prv.nombre like '%$P[$i]%' ";}else{$BusInt=$BusInt." prv.nombre  like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" prv.nombre  like '%$busca%' ";
// $Suc='*';
}



#Armo el query segun los campos tomados de qrys;


if( $busca == ''){





    $cSql = "SELECT " . $Qry["campos"] . ",el.proveedor,prv.nombre,el.almacen "
    . "FROM " . $Qry["froms"] . " LEFT JOIN prv ON el.proveedor= prv.id WHERE el.fecha>='$FechaI' and el.fecha<='$FechaF'";  

}elseif( $busca < 'a'){
    $cSql = "SELECT " . $Qry["campos"] . ",el.proveedor,prv.nombre,el.almacen "
    . "FROM " . $Qry["froms"] . " LEFT JOIN prv ON el.proveedor = prv.id  WHERE el.id='$busca'";

}else{
    $cSql = "SELECT " . $Qry["campos"] . ",el.proveedor,prv.nombre,el.almacen "
        . "FROM " . $Qry["froms"] . " LEFT JOIN prv ON el.proveedor = prv.id WHERE " . $BusInt . " " . $Qry["filtro"];
}










$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "", "", " ", "", "", " ", "", "");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Compras para laboratorio ::..</title>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <table>
            <tr>
            <td height="10px;"></td></tr></table>
        <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
            <span  class="letrap"> Fec.inicial: </span>
            <input type='date' name='FechaI' value='<?= $FechaI ?>' size='10' class='letrap'></input> 
            <span  class="letrap"> Fec.final: </span>
            <input type='date' name='FechaF' value='<?= $FechaF ?>' size='10' class='letrap'></input> 
    
            <input type='submit' name='Boton' value='enviar' class='letrap'></input>
           <!-- <a  class='edit' href='?Todo=*'><i class='fa fa-eye fa-2x' aria-hidden='true'></i> Ver todo </a> -->
        </form>
        <script src="./controladores.js"></script>
    <?php

        echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
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
            echo "<td height='21px' class='letrap' align='center'><a class='edit' href='$cLink?busca=$rg[id]'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i></a></td>";
            echo "<td class='letrap' align='center'><a class='edit' href=javascript:winuni('impentlabpdf.php?busca=$rg[id]') ><i class='fa fa-print fa-lg' aria-hidden='true'></i></a></td>";
            echo "<td class='letrap' align='center'><a class='edit' href='$uLink?busca=$rg[id]'><i class='fa fa-list fa-lg' aria-hidden='true'></i></a></td>";

            Display($aCps, $aDat, $rg);
            $rg["status"] == 'Abierta' or $rg["status"] == 'ABIERTA' ? $Status = "fa-unlock" : $Status = "fa-unlock-alt";
            if ($rg[almacen] == 'invgral') {
                $Almacenes = 'General';
            } elseif ($rg[almacen] == 'invmatriz') {
                $Almacenes = 'Matriz';
            } elseif ($rg[almacen] == 'invtepex') {
                $Almacenes = 'Tepexpan';
            } elseif ($rg[almacen] == 'invhf') {
                $Almacenes = 'HospitalF';
            } elseif ($rg[almacen] == 'invgralreyes') {
                $Almacenes = 'Gral.Reyes';
            } elseif ($rg[almacen] == 'invreyes') {
                $Almacenes = 'Reyes';
            }
            echo "<td class='letrap' align='center'>" . $rg["proveedor"] . "- " . ucwords ($rg["nombre"]) . "</td>";
            echo "<td align='center' class='letrap'> " . ucwords ($Almacenes) . "</td>";
            echo "<td align='center'><a href=javascript:winuni('pagosel.php?busca=$rg[id]') class='edit'><i class='fa $Status fa-lg' aria-hidden='true'></i></a></td>";

            echo "</tr>";

            $nRng++;
        }

        echo '</td>';
        echo '</tr>';
        echo '</table>';

        PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

        CuadroInferior4($busca);
        mysql_close();
        ?>
    </body>


</html>
<?php
mysql_close();
?>
