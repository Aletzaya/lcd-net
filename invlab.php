<?php
#Librerias
session_start();

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST["busca"])) {

    if ($_REQUEST["busca"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST[Ret] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'invl.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST["busca"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
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


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$Mnu = $_SESSION["Mnu"];
$Cat='Inv';
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
$Titulo = "Catalogo de Productos";
$op = $_REQUEST["op"];
$estudio = $_REQUEST["estudio"];
$suc = $_REQUEST["suc"];
$Msj = $_REQUEST["Msj"];
$Id = 68;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" invl.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and invl.descripcion like '%$P[$i]%' ";}
 }
}else{
        $BusInt=" invl.descripcion like '%$busca%' or invl.marca like '%$busca%' or invl.clave like '%$busca%'";  
}

$Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";

if( $busca == ''){
    $cSql = "SELECT $Qry[campos],invl.invgral, invl.invmatriz, invl.invtepex, invl.invhf, "
    . "invl.invgralreyes, invl.invreyes, invl.existencia, invl.invcam, invl.invsnv, "
    . "IF(invl.existencia < invl.min,'red, Faltante',IF(invl.existencia > invl.max ,'blue, Extendido','black, Ok')) result1 "
    . "FROM invl LEFT JOIN dep ON $Qry[filtro]  WHERE id>= 0 and invl.status='Activo'";

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos],invl.invgral, invl.invmatriz, invl.invtepex, invl.invhf, "
        . "invl.invgralreyes, invl.invreyes, invl.existencia, invl.invcam, invl.invsnv, "
        . "IF(invl.existencia < invl.min,'red, Faltante',IF(invl.existencia > invl.max ,'blue, Extendido','black, Ok')) result1 "
        . "FROM invl LEFT JOIN dep ON $Qry[filtro]  WHERE  id='$busca' and invl.status='Activo'";

}else{

        $cSql = "SELECT $Qry[campos],invl.invgral, invl.invmatriz, invl.invtepex, invl.invhf, "
            . "invl.invgralreyes, invl.invreyes, invl.existencia, invl.invcam, invl.invsnv, "
            . "IF(invl.existencia < invl.min,'red, Faltante',IF(invl.existencia > invl.max ,'blue, Extendido','black, Ok')) result1 "
            . "FROM invl LEFT JOIN dep ON $Qry[filtro]  WHERE invl.status='Activo' and $BusInt ";

}

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Det", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array(" ", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Inventario ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>
    <body topmargin="1">
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="97%">
            <tr><td align='center' colspan='6'><FONT class='content_txt' SIZE='3'><b><?= $Titulo ?></b></font></td></tr>
            <tr><td align='center' colspan='6'>&nbsp;</td></tr>
            <tr>
                <tr>
                    <td height="380" valign="top">
                        <?php
                        PonEncabezadocat();

                        $res = mysql_query($cSql);

                        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;

                        $resN = mysql_query($sql);

                        $Pos = strrpos($_SERVER["PHP_SELF"], ".");
                        $cLink = substr($_SERVER["PHP_SELF"], 0, $Pos) . 'e.php';     #
                        $uLink = substr($_SERVER["PHP_SELF"], 0, $Pos) . 'd.php';     #

                        while ($rg = mysql_fetch_array($resN)) {
                            $Result = explode(",", $rg[result1]);
                            $Color = $Result[0];
                            $Text = $Result[1];
                            
                            ($nRng % 2) > 0 ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;
                            
                            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td class='Seleccionar' align='center'><a class='edit' href='estudioseeu.php?Producto=$rg[clave]&idproducto=$rg[id]&busca=$estudio&ele=$suc'>Seleccionar</a></td>";
                            
                            Display($aCps, $aDat, $rg);

                            echo "</tr>";

                            $nRng++;
                        }
                        ?>
                    </td>
                </tr>
        </table>
        <?php
        PonPaginacioncat(true);           #-------------------pon los No.de paginas-------------------    

        CuadroInferior4($busca);

        echo "<div align='center'>";
        echo "<a href='estudioseeu.php?busca=$estudio&ele=$suc'><i class='fa fa-reply fa-2x' aria-hidden='true'></i>$Gfon Regresar </a><b></font>";
        echo "</div>";
        ?>
    </body>


</html>
<?php
mysql_close();
?>

