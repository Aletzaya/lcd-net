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

        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', $Retornar, $Fecha, $Fecha, '*', '', 2, '');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
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
if (isset($_REQUEST["FiltroCia"])) {
    $_SESSION['OnToy'][7] = $_REQUEST["FiltroCia"];
}
if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][5] = $_REQUEST["FechaI"];
    $_SESSION['OnToy'][8] = '';
}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["FechaF"];
    $_SESSION['OnToy'][8] = '';
}

$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$busca = strtolower($busca);
$pagina = $_SESSION["OnToy"][1];
$orden = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$RetSelec = $_SESSION["OnToy"][4];
$GnSuc = $_SESSION["OnToy"][7];          //Que sucursal estoy checando
$FechaI = $_SESSION["OnToy"][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION["OnToy"][6];          //Pagina a la que regresa con parametros  

$Estudio = $_REQUEST["Estudio"];

$Estudio2 = $_REQUEST["Estudio2"];

$Subdepto = $_REQUEST["Subdepto"];

$sucorigen = $_REQUEST["sucorigen"];

$Suc = $_REQUEST["Suc"];

$Stat = $_REQUEST["Stat"];

$Ord = $_REQUEST["Ord"];

$Orden2 = $_REQUEST["Orden2"];

$indmuestras = $_REQUEST["indmuestras"];

$nmuestras = $_REQUEST["nmuestras"];

if (!isset($Stat)) {
    $Stat == '*';
} else {
    $Stat = $_REQUEST["Stat"];
}

$Op = $_REQUEST["Op"];

$Folio = $_REQUEST["folio"];

$Msj = $_REQUEST["Msj"];

$Fecha = date("Y-m-d");
$Fechas = date("Y-m-d H:i:s");

$Hora = date("H:i:s");

$SqlC = "SELECT * FROM maqdet WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2'";

$resC = mysql_query($SqlC);

$registro3 = mysql_fetch_array($resC);

if ($Op == 1 || $Op == 2 || $Op == 3 || $Op == 4 || $Op == 5 || $Op == 6) {
    if (empty($registro3)) {

        $Msj = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
		VALUES
		('$Orden2','$Estudio2','$Op','$Fecha','$Hora','$Gusr')") == true ? "Registro ingresado con exito" : "Error en sql";
    } else {
        $Msj = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mint='$Op',fenv='$Fecha',"
                        . "henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1") == true ? "Registro actualizado con exito" : "Error en sql";
    }
    header("Location: proceso.php?Msj=$Msj");
} elseif ($Op === 'Ext') {

    $SqlC = "SELECT * FROM invldet WHERE invldet.orden='$Orden2' AND invldet.estudio='$Estudio2'";
    //echo $SqlC;
    $resC = mysql_query($SqlC);

    $registro4 = mysql_fetch_array($resC);

    if (empty($registro4)) {

        $cSql4 = "SELECT team FROM authuser WHERE authuser.uname='$Gusr'";
        $result4 = mysql_query($cSql4);
        $row4 = mysql_fetch_array($result4);
        $row4s = $row4["team"];

        if ($row4s == 1 or $row4s == 0) {

            $Almacen = 'invl.invmatriz';
        } elseif ($row4s == 2) {

            $Almacen = 'invl.invhf';
        } elseif ($row4s == 3) {

            $Almacen = 'invl.invtepex';
        } elseif ($row4s == 4) {

            $Almacen = 'invl.invreyes';
        }

        $cSql2 = "SELECT estd.estudio,estd.producto,estd.idproducto,estd.cantidad,invl.clave FROM estd,invl WHERE estd.estudio='$Estudio2' and estd.idproducto=invl.id and estd.suc='$row4s'";

        $result2 = mysql_query($cSql2);

        while ($row2 = mysql_fetch_array($result2)) {

            $id = $row2["idproducto"];

            if ($indmuestras == 'Varias') {

                $lUp = mysql_query("UPDATE invl SET $Almacen = $Almacen - ( $row2[cantidad] * $nmuestras ), invl.existencia=invl.existencia - ($row2[cantidad]*$nmuestras) WHERE invl.id='$row2[idproducto]' limit 1");

                $Fecha2 = date("Y-m-d H:i");

                $cantidadmuestras = $row2["cantidad"] * $nmuestras;

                $lUp2 = mysql_query("INSERT INTO invldet (fecha,idproducto,producto,estudio,cantidad,usr,suc,orden,sucorigen)
      VALUES
      ('$Fecha2','$id','$row2[producto]','$Estudio2','$cantidadmuestras','$Gusr','$row4s','$Orden2','$sucorigen')");
            } else {

                $lUp = mysql_query("UPDATE invl SET $Almacen = $Almacen - $row2[cantidad], invl.existencia=invl.existencia - $row2[cantidad] WHERE invl.id='$row2[idproducto]' limit 1");

                $Fecha2 = date("Y-m-d H:i");

                $lUp2 = mysql_query("INSERT INTO invldet (fecha,idproducto,producto,estudio,cantidad,usr,suc,orden,sucorigen)
      VALUES
      ('$Fecha2','$id','$row2[producto]','$Estudio2','$row2[cantidad]','$Gusr','$row4s','$Orden2','$sucorigen')");
            }
        }
        //**************************** inventario
    }

    if (empty($registro3)) {
        //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
        $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,mext,fenvext,henvext,usrenvext)
      VALUES
      ('$Orden2','$Estudio2','$_REQUEST[alias]','$Fecha','$Hora','$Gusr')");
    } else {
        $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mext='$_REQUEST[alias]',fenvext='$Fecha',henvext='$Hora',usrenvext='$Gusr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
    }

    //$busca=$_REQUEST[Orden2];
} elseif ($Op == 'Recint') {
    if (empty($registro3)) {
        //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
        $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,frecint,hrecint,usrrecint)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Gusr')");
    } else {
        $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frecint='$Fecha',hrecint='$Hora',usrrecint='$Gusr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
    }
    //$busca=$_REQUEST[Orden2];
} elseif ($Op == 'Recext') {
    if (empty($registro3)) {
        $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,frecext,hrecext,usrrecext)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Gusr')");
    } else {
        $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frecext='$Fecha',hrecext='$Hora',usrrecext='$Gusr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
    }
    //$busca=$_REQUEST[Orden2];
} elseif ($Op == 'Rec') {
    if (empty($registro3)) {
        $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,frec,hrec,usrrec)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Usr')");
    } else {
        $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frec='$Fecha',hrec='$Hora',usrrec='$Gusr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
    }
    //$busca=$_REQUEST[Orden2];
}

$Id = 63;
$FechaT = date("Y-m-d h:i:s");
$Fecha = date("Y-m-d");
$FechaH = date("h:i:s");
$Orden2 = $_REQUEST["Orden2"];
$Estudio = $_REQUEST["Estudio"];
$Folio = $_REQUEST["folio"];

if ($GnSuc == "*") {
    $Sucursal = "";
} else {
    $Sucursal = " and ot.suc='$GnSuc'";
}

if ($_REQUEST[Dpto] == '' OR $_REQUEST[Dpto] == '*') {
    $dpto = " ";
} else {
    $Dpto = $_REQUEST[Dpto];
    $dpto = "AND est.depto = " . $_REQUEST[Dpto];
}

if ($_REQUEST[Subdepto] == '' OR $_REQUEST[Subdepto] == '*') {
    $subdpto = " ";
} else {
    $Subdpto = $_REQUEST[Subdepto];
    $subdpto = "AND est.subdepto = '" . $_REQUEST[Subdepto] . "'";
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

#Armo el query segun los campos tomados de qrys;


$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " cli.nombrec like '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " and cli.nombrec like '%$P[$i]%' ";
        }
    }
} else {
    $BusInt = " cli.nombrec like '%$busca%' ";
}

if ($busca == '') {
    $SqlB3 = "SELECT ot.orden
            FROM ot
            where ot.fecha='$FechaI' order by hora limit 1";

    $Sql3 = mysql_query($SqlB3);

    $S3 = mysql_fetch_array($Sql3);


    $SqlB4 = "SELECT ot.orden
            FROM ot
            where ot.fecha='$FechaF' order by orden desc limit 1";
    //echo $SqlB4;
    $Sql4 = mysql_query($SqlB4);

    $S4 = mysql_fetch_array($Sql4);
/*
    $cSql = "SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso
        ,maqdet.mint, maqdet.mext, maqdet.obsenv, maqdet.obsrec,maqdet.usrrecint,maqdet.usrrecext,ot.suc,maqdet.usrrec,indmuestras
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN maqdet ON ot.orden = maqdet.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE ot.cliente=cli.cliente AND maqdet.estudio = est.estudio and ot.orden>='$S3[orden]' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' and ot.orden<='$S4[orden]' $dpto $subdpto $Sucursal";
*/

    $cSql="SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,otd.status,otd.usrest,otd.statustom,otd.estudio,otd.capturo,otd.cuatro,otd.recibeencaja,otd.fr,otd.usrvalida,est.depto,cli.nombrec,ot.suc,otd.obsest,ot.observaciones,otd.fechaest,otd.proceso,est.subdepto,maqdet.mint,maqdet.mext,maqdet.obsenv,maqdet.obsrec,maqdet.usrrecint,maqdet.usrrecext,maqdet.usrrec
        FROM ot
        INNER JOIN otd on ot.orden=otd.orden
        INNER JOIN est on otd.estudio=est.estudio
        INNER JOIN cli on ot.cliente=cli.cliente
        inner join maqdet on ot.orden=maqdet.orden
        WHERE ot.cliente=cli.cliente AND maqdet.estudio = est.estudio and ot.orden>='$S3[orden]' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' and ot.orden<='$S4[orden]' $dpto $subdpto $Sucursal";


} elseif ($busca < 'a') {
/*
    $cSql = "SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso
        ,maqdet.mint, maqdet.mext, maqdet.obsenv, maqdet.obsrec,maqdet.usrrecint,maqdet.usrrecext,ot.suc,maqdet.usrrec,indmuestras
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN maqdet ON ot.orden = maqdet.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE ot.cliente=cli.cliente and est.depto <> 7 and est.depto <> 8 and est.depto <> 9 and est.depto <> 10 and otd.statustom<>'' and otd.statustom<>'PENDIENTE'  and otd.orden='$busca'";

*/
    $cSql="SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,otd.status,otd.usrest,otd.statustom,otd.estudio,otd.capturo,otd.cuatro,otd.recibeencaja,otd.fr,otd.usrvalida,est.depto,cli.nombrec,ot.suc,otd.obsest,ot.observaciones,otd.fechaest,otd.proceso,est.subdepto,maqdet.mint,maqdet.mext,maqdet.obsenv,maqdet.obsrec,maqdet.usrrecint,maqdet.usrrecext,maqdet.usrrec
        FROM ot
        INNER JOIN otd on ot.orden=otd.orden
        INNER JOIN est on otd.estudio=est.estudio
        INNER JOIN cli on ot.cliente=cli.cliente
        inner join maqdet on ot.orden=maqdet.orden
        WHERE ot.cliente=cli.cliente and est.depto <> 7 and est.depto <> 8 and est.depto <> 10 and otd.statustom<>'' AND otd.estudio=maqdet.estudio and otd.statustom<>'PENDIENTE' AND otd.orden='$busca'";

} else {
/*
    $cSql = "SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso
        ,maqdet.mint, maqdet.mext, maqdet.obsenv, maqdet.obsrec,maqdet.usrrecint,maqdet.usrrecext,ot.suc,maqdet.usrrec,indmuestras 
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN maqdet ON ot.orden = maqdet.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE $BusInt and est.depto <> 7 and est.depto <> 8 and est.depto <> 9 and est.depto <> 10 and otd.statustom<>'' and otd.statustom<>'PENDIENTE'";
*/
        
    $cSql="SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,otd.status,otd.usrest,otd.statustom,otd.estudio,otd.capturo,otd.cuatro,otd.recibeencaja,otd.fr,otd.usrvalida,est.depto,cli.nombrec,ot.suc,otd.obsest,ot.observaciones,otd.fechaest,otd.proceso,est.subdepto,maqdet.mint,maqdet.mext,maqdet.obsenv,maqdet.obsrec,maqdet.usrrecint,maqdet.usrrecext,maqdet.usrrec
        FROM ot
        INNER JOIN otd on ot.orden=otd.orden
        INNER JOIN est on otd.estudio=est.estudio
        INNER JOIN cli on ot.cliente=cli.cliente
        inner join maqdet on ot.orden=maqdet.orden
        WHERE $BusInt and est.depto <> 7 and est.depto <> 8  and est.depto <> 10 and otd.statustom<>'' and otd.statustom<>'PENDIENTE'";


}
//echo $cSql;
$aCps = SPLIT(",", $Qry[campos]);
$aIzq = array();
$aDat = SPLIT(",", $Qry[edi]);
$aDer = array("MTRZ", "-", "-", "OHT", "-", "-", "TPX", "-", "-", "RYS", "-", "-", "CAM", "-", "-", "SVC", "-", "-", "Proveedor", "-", "-", "Det", "-", "-", "Int", "-", "-", "Ext", "-", "-", "Est", "-", "-");
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Estudios por departamento ::..</title>
        <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();

        menu($Gmenu, $Gusr);

        if ($Dpto == '*') {
            $colorT = 'bgcolor="#519145"';
        } elseif ($Dpto == '1') {
            $color1 = 'bgcolor="#519145"';
        } elseif ($Dpto == '2') {
            $color2 = 'bgcolor="#519145"';
        } elseif ($Dpto == '3') {
            $color3 = 'bgcolor="#519145"';
        } elseif ($Dpto == '4') {
            $color4 = 'bgcolor="#519145"';
        } elseif ($Dpto == '6') {
            $color6 = 'bgcolor="#519145"';
        } elseif ($Dpto == '9') {
            $color9 = 'bgcolor="#519145"';
        } elseif ($Dpto == '10') {
            $color10 = 'bgcolor="#519145"';
        } else {
            $colorT = 'bgcolor="#519145"';
        }
        ?>
            <script src="./controladores.js"></script>

        <table border="0" width="100%">
            <tr style="height: 24px;" bgcolor="#84B2D1">
                <td class="sbmenu" align="center" style="width: 100px" <?= $colorT; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=*">
                        Todos *
                    </a>
                </td>
                <td class="sbmenu" align="center" style="width: 100px" <?= $color1; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=1&Subdpt=*">
                        Laboratorio
                    </a>
                </td>
                <td class="sbmenu" align="center" style="width: 100px" <?= $color2; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=2">
                        Rayos X y USG
                    </a>
                </td>
                <td class="sbmenu" align="center" style="width: 100px" <?= $color3; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=3">
                        Especiales
                    </a>
                </td>
                <td class="sbmenu" align="center" style="width: 100px" <?= $color4; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=4">
                        Servicios
                    </a>
                </td>
                <td class="sbmenu" align="center" style="width: 100px" <?= $color6; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=6">
                        Externos
                    </a>
                </td>
            </tr>
        </table>
        <?php
        if ($dpto <> " ") {
            ?>
            <table border="0" style="width: 100%;">
                <tr style="height: 24px;" bgcolor="#7AD169">
                    <?php
                    $sql = "SELECT departamento,subdepto FROM depd where departamento=$Dpto";
                    $SubA = mysql_query($sql);
                    $Subdpto == $SubD[subdepto] ? $colorsub = 'bgcolor="#84B2D1"' : $colorsub = 'bgcolor="#519145"';
                    ?>
                    <td class="sbmenu" align="center" <?= $colorsub; ?> >
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=<?= $Dpto ?>&Subdepto=*">
                            Todos *
                        </a>
                    </td>
                    <?php
                    while ($SubD = mysql_fetch_array($SubA)) {

                        $Subdpto == $SubD[subdepto] ? $colorsub = 'bgcolor="#84B2D1"' : $colorsub = 'bgcolor="#519145"';
                        ?>
                        <td class="sbmenu" align="center" <?= $colorsub; ?> >
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=<?= $Dpto ?>&Subdepto=<?= $SubD[subdepto] ?>">
                                <?= $SubD[subdepto] ?>
                            </a>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </table>
            <?php
        }
        ?>
        <table border="0"><tr class='letrap'><td height="10px;"></td></tr></table>
        <form name='frmfiltro' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <table width="100%">
                <tr>
                    <td class="letrap">
                        Buscar: 
                        <input class='letrap' type='text' size='30' class='texto_tablas' placeholder='Ingresar Folio o Nombre' name='busca' autofocus/>
                        Suc: 
                        <select class='letrap' name='FiltroCia' onChange='frmfiltro.submit();'>
                            <?php
                            $CiaA = mysql_query("SELECT id,alias FROM cia ORDER BY id");
                            while ($Cia = mysql_fetch_array($CiaA)) {
                                if ($Cia[id] == $GnSuc) {
                                    echo '<option selected value="' . $Cia[id] . '">' . $Cia[alias] . '</option>';
                                } else {
                                    echo '<option value=' . $Cia[id] . '>' . $Cia[alias] . '</option>';
                                }
                            }
                            echo "<option value='*'>* Todos</option>";
                            if ($GnSuc == '*') {
                                echo "<option selected value='*'>* todos</select>";
                            }
                            ?>
                        </select>
                        Fec.inicial: 
                        <input type='date' style="width: 150px;" name='FechaI' value='<?= $FechaI ?>' size='10' class='letrap'></input> 
                        Fec.final:
                        <input type='date' style="width: 150px;" name='FechaF' value='<?= $FechaF ?>' size='10' class='letrap'></input> 
                        <input type='submit' name='Boton' value='enviar' class='letrap'></input>
                        <input type='hidden' name='Dpto' value='<?= $Dpto ?>' ></input>
                        <input type='hidden' name='Subdepto' value='<?= $Subdpto ?>' ></input>
                       <!-- <a  class='edit' href='?Todo=*'><i class='fa fa-eye fa-2x' aria-hidden='true'></i> Ver todo </a> -->

                    </td>
                    <td><a class="NvaFact" href=javascript:wingral("pidedatosventana.php");>Envio Interno</a></td>
                    <td><a class="NvaFact" href=javascript:wingral("pidedatosventanaExt.php");>Envio Externo</a></td>
                    <td><a class="NvaFact" href=javascript:wingral("pidedatosventanaPrd.php");>Productividad</a></td>
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

                    $sql = $cSql . $cWhe . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    //echo $sql;
                    $res = mysql_query($sql);

                    while ($rg = mysql_fetch_array($res)) {
                        $clnk = strtolower($rg[estudio]);
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                        Display($aCps, $aDat, $rg);

                        if ($rg["mint"] == '1') {
                            echo "<td align='center' height='25px'><i class='fa fa-check-circle fa-lg' style='color:green' aria-hidden='true'></i></td>";
                        } else {
                            echo "<td height='25px' align='center'><a href='" . $_SERVER["PHP_SELF"] . "?Op=1&Estudio2=" . $rg["estudio"] . "&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }
                        if ($rg["mint"] == '2') {
                            echo "<td align='center'><i class='fa fa-check-circle fa-lg'  style='color:green' aria-hidden='true'></i></td>";
                        } else {
                            echo "<td align='center'><a href='$_SERVER[PHP_SELF]?Op=2&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }
                        if ($rg["mint"] == '3') {
                            echo "<td align='center'><i class='fa fa-check-circle fa-lg' style='color:green' aria-hidden='true'></i></td>";
                        } else {
                            echo "<td align='center'><a href='$_SERVER[PHP_SELF]?Op=3&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }
                        if ($rg["mint"] == '4') {
                            echo "<td align='center'><i class='fa fa-check-circle fa-lg'  style='color:green' aria-hidden='true'></i></td>";
                        } else {
                            echo "<td align='center'><a href='$_SERVER[PHP_SELF]?Op=4&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }
                        if ($rg["mint"] == '5') {
                            echo "<td align='center'><i class='fa fa-check-circle fa-lg'  style='color:green' aria-hidden='true'></i></td>";
                        } else {
                            echo "<td align='center'><a href='$_SERVER[PHP_SELF]?Op=5&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }
                        if ($rg["mint"] == '6') {
                            echo "<td align='center'><i class='fa fa-check-circle fa-lg'  style='color:green' aria-hidden='true'></i></td>";
                        } else {
                            echo "<td align='center'><a href='$_SERVER[PHP_SELF]?Op=6&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }
                        echo "<td align='center'>";
                        echo "<form name='form' method='post' action='proceso.php?Op=Ext&Estudio2=$rg[estudio]&Orden2=$rg[orden]&indmuestras=$rg[indmuestras]''>";
                        $MqlA = mysql_query("select * from mql");
                        echo "<select size='1' name='alias' class='letrap' onchange=this.form.submit()>";
                        echo "<option value=' '>Quitar Registro</option>";
                        while ($Mql = mysql_fetch_array($MqlA)) {
                            echo "<option value='$Mql[id]'>$Mql[alias]</option>";
                        }
                        $MqlA2 = mysql_query("select * from mql where mql.id=$rg[mext]");
                        $Mql2 = mysql_fetch_array($MqlA2);
                        echo "<option selected value='$rg[mext]'>$Mql2[alias]</option>";
                        echo "</select>";
                        echo "</form>";
                        echo "</td>";

                        echo "<td align='center'><a  href=javascript:winuni('obsmql.php?Estudio2=" . $rg["estudio"] . "&Orden2=" . $rg["orden"] . "')><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";


                        if ($rg["usrrecint"] <> '') {
                            echo "<td align='center' class='letrap'> $rg[usrrecint]</td>";
                        } elseif ($rg["mint"] <> $rg["suc"]) {
                            echo "<td align='center'><a href='$_SERVER[PHP_SELF]?Op=Recint&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-user-o fa-lg' aria-hidden='true'></i></a></td>";
                        } else {
                            echo "<td align='center' class='letrap'> - </td>";
                        }


                        if ($rg["usrrecext"] <> '') {
                            echo "<td align='center' class='letrap'>$rg[usrrecext]</td>";
                        } elseif ($rg["usrenvext"] <> '') {
                            echo "<td align='center'><a  href='$_SERVER[PHP_SELF]?Op=Recext&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        } else {
                            echo "<td align='center' class='letrap'> - </td>";
                        }

                        if ($rg["usrrec"] <> '') {
                            echo "<td align='center' class='letrap'>$rg[usrrec]</td>";
                        } else {
                            echo "<td align='center'><a  href='$_SERVER[PHP_SELF]?Op=Rec&Estudio2=$rg[estudio]&Orden2=$rg[orden]'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }

                        echo "</tr>";

                        $nRng++;
                    }
                    ?>
                </td>
            </tr>
        </table>
        <table><td><tr><a class='cMsj'><?php echo $_REQUEST["msj"] ?></a></tr></td></table>
                    <?php PonPaginacion2(false); ?> 
    </body>

</html>
<?php
mysql_close();
