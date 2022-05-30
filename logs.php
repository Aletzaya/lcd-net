<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST[busca])) {

    if ($_REQUEST[busca] == ini) {

        $Fecha = date("Y-m-d");

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'log.id', 'Asc', $Retornar, $Fecha, $Fecha, '');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
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
if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][5] = $_REQUEST["FechaI"];
}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["FechaF"];
}

$Usr = $_REQUEST[usr];
$Tabla = $_REQUEST[tabla];

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$busca = strtolower($busca);
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$Mnu = $_SESSION[Mnu];
$FechaI = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION[OnToy][6]; 
$Id = 72;

$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

//Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
//echo "El valor de retornar es $RetSelec";
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" log.usr like '%$P[$i]%' ";}else{$BusInt=$BusInt." and log.usr like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" log.usr like '%$busca%' ";
}  
#Armo el query segun los campos tomados de qrys;

if( $busca == ''){

    $SqlB3="SELECT log.id,log.fecha
    FROM log
    where date_format(log.fecha,'%Y-%m-%d')='$FechaI' order by fecha limit 1";

    $Sql3=mysql_query($SqlB3,$link);

    $S3=mysql_fetch_array($Sql3);


    $SqlB4="SELECT log.id,log.fecha
    FROM log
    where date_format(log.fecha,'%Y-%m-%d')='$FechaF' order by id desc limit 1";

    $Sql4=mysql_query($SqlB4,$link);

    $S4=mysql_fetch_array($Sql4);


    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE log.id>='$S3[id]' and log.id<='$S4[id]' and log.usr LIKE '%$Usr%'  and log.tabla LIKE '%$Tabla%'"; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE  log.id='$busca'";

}else{

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt";

}






$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array();    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array();    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Logs Sistema</title>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>

    <?php
    echo '<body topmargin="1">';
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

        <?php
        echo "<tr height='30' class='letrap'><td align='right'><span  class='letrap'>Usuario: </span></td><td>&nbsp; ";
        $InsA   = mysql_query("SELECT uname FROM authuser ORDER BY uname");
        echo "<select class='InputMayusculas' name='usr'>";           
        while($Ins = mysql_fetch_array($InsA))
        {
            echo "<option value='$Ins[uname]'> &nbsp; $Ins[uname] </option>";}
        echo "<option  value=''> &nbsp; Todos</option>";  //se va
        echo "<option selected value='$Usr'> $Usr </option>";  //se va

        echo "</select> ";
        echo "</td></tr>";
        ?>

        <?php
        echo "<tr height='30' class='letrap'><td align='right'><span  class='letrap'>Tabla: </span></td><td>&nbsp; ";
        $InsA1   = mysql_query("SELECT tabla FROM log GROUP BY tabla HAVING COUNT(*)>1");
        echo "<select class='InputMayusculas' name='tabla'>";           
        while($Ins1 = mysql_fetch_array($InsA1))
        {
            echo "<option value='$Ins1[tabla]'> &nbsp; $Ins1[tabla] </option>";}
        echo "<option  value=''> &nbsp; Todos</option>";  //se va
        echo "<option selected value='$Tabla'> $Tabla </option>";  //se va

        echo "</select> ";
        echo "</td></tr>";
        ?>

        <input type='submit' name='Boton' value='enviar' class='letrap'></input>
    </form>
    <script src="./controladores.js"></script>

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

                    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                    Display($aCps, $aDat, $rg);
                    
                    echo "</tr>";

                    $nRng++;
                }

                ?>
            </td>
        </tr>
    </table>
    <?php
    PonPaginacion3(false);           #-------------------pon los No.de paginas-------------------    


    CuadroInferior4($busca);


    echo '</body>';
    ?>

</html>
<?php
mysql_close();
?>

