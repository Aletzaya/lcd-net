<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

if (!isset($_REQUEST[suc])) {
    $suc = '1';
} else {
    $suc = $_REQUEST[suc];
}
if ($suc == 1) {
    $tablasuc = 'procestex';
} elseif ($suc == 2) {
    $tablasuc = 'procestep';
} elseif ($suc == 3) {
    $tablasuc = 'proceshf';
} elseif ($suc == 4) {
    $tablasuc = 'procesrys';
}

#Variables comunes;
$Titulo = "Ordenes de estudio";
$Fecha = date("Y-m-d");

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST[busca];
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Retornar = "estudios.php";
$date = date("Y-m-d H:i:s");
if ($_REQUEST[op] == "Actualizar") {
    $sql = "SELECT bloqadm FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqadm] == 'No') {
        $Msj = "Se actualiza lista de precios con exito¡";

        $sql = " UPDATE est SET lt1 = '$_REQUEST[Lt1]',lt2 = '$_REQUEST[Lt2]',lt3 = '$_REQUEST[Lt3]',lt4 = '$_REQUEST[Lt4]',lt5 = '$_REQUEST[Lt5]',
        lt6 = '$_REQUEST[Lt6]',lt7 = '$_REQUEST[Lt7]',lt8 = '$_REQUEST[Lt8]',lt9 = '$_REQUEST[Lt9]',lt10 = '$_REQUEST[Lt10]',lt11 = '$_REQUEST[Lt11]',
        lt12 = '$_REQUEST[Lt12]',lt13 = '$_REQUEST[Lt13]',lt14 = '$_REQUEST[Lt14]',lt15 = '$_REQUEST[Lt15]',lt16 = '$_REQUEST[Lt16]',lt17= '$_REQUEST[Lt17]',
        lt18 = '$_REQUEST[Lt18]',lt19 = '$_REQUEST[Lt19]',lt20 = '$_REQUEST[Lt20]',lt21 = '$_REQUEST[Lt21]',lt22 = '$_REQUEST[Lt22]',lt23 = '$_REQUEST[Lt23]' 
        WHERE estudio = '$busca'";
        if (!mysql_query($sql)) {
            echo "Error de sintaxis en SQL " . $sql;
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Administracion/Edita Lista', "est", $date, $busca, $Msj, "estudiosadmin.php");
    } else {
        $Msj = "Error se encuentra cerrado administración";
        header("Location: estudiosadmin.php?busca=$busca&Msj=$Msj&Error=SI");
    }
} elseif ($_REQUEST["op"] == "Actualiza/clasificación") {
    $sql = "SELECT bloqadm FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqadm] == 'No') {
        $cSql = "UPDATE est SET inv_cunidad='$_REQUEST[Inv_cunidad]',inv_cproducto='$_REQUEST[Inv_cproducto]' WHERE estudio='$busca' limit 1";
        $Msj = "Actualización de clasificación";
        if (!mysql_query($cSql)) {
            echo "Error en sintaxis MYSQL " . $cSql;
        }
        AgregaBitacoraEventos($Gusr, '/Estudios/Administracion/Actualiza clasificación', "est", $date, $busca, $Msj, "estudiosadmin.php");
    } else {
        $Msj = "Error se encuentra cerrado administración";
        header("Location: estudiosadmin.php?busca=$busca&Msj=$Msj&Error=SI");
    }
} elseif ($_REQUEST["Op"] == "ab") {
    $Msj = "Estudio abierto con exito";
    $sql = "UPDATE est SET bloqadm = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Administracion/Abre Administracion', "est", $date, $busca, $Msj, "estudiosadmin.php");
} elseif ($_REQUEST["Op"] == "cr") {
    $Msj = "Estudio cerrado con exito";
    $sql = "UPDATE est SET bloqadm = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Administracion/Cierra Administración', "est", $date, $busca, $Msj, "estudiosadmin.php");
}
#Intruccion a realizar si es que mandan algun proceso
$Cpql = "SELECT estudio,bloqadm,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,tecnica,inv_cunidad,inv_cproducto,
    equipo,muestras,estpropio,subdepto,contenido,comision,observaciones,proceso,clavealt,respradiologia,activo,dobleinterpreta,modify,fechmod,agrego,fechalta,depto,base,ventajas,promogral,tiempoestd,tiempoesth,entordh,enthosd,enturgd,bloqbas,bloqeqp,bloqmue,bloqcon,bloqdes,bloqadm,bloqatn,msjadmvo,consentimiento,producto_entregar,costo
    FROM est WHERE (estudio= '$busca')";
$CpoA = mysql_query($Cpql);
//echo $Cpql;
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Administracion</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
//        encabezados();
//        menu($Gmenu, $Gusr);

//Tabla contenedor de brighs
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr >
                <td colspan="3" bgcolor='#2c8e3c' width='80%' class='Subt' align='center'>
                    ...::: Lista de precios de (<?= $busca ?>)  <?= $Cpo[descripcion] ?> :::...
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='43%'>
                    <form name="form1" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Listas de precios ::..
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table border="0" width="98%" align="center">
                                        <tr class="letrap">
                                            <td width="3%" align="center">
                                                #
                                            </td>
                                            <td width="10%">
                                                Precios
                                            </td>
                                            <td>
                                                Instituciones   
                                            </td>
                                        </tr>
                                        <?php
                                        $cSqlist = "SELECT estudio,descripcion,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10,lt11,
                                        lt12,lt13,lt14,lt15,lt16,lt17,lt18,lt19,lt20,lt21,lt22,lt23,formato,
                                        modify,fechmod,inv_cunidad,inv_cproducto
                                        FROM est
                                        WHERE estudio='$busca'";

                                        $CpoAlist = mysql_query($cSqlist);
                                        $Cpolist = mysql_fetch_array($CpoAlist);

                                        $ct_ps_q = mysql_query("SELECT C.nombre, CT.descripcion , CT.tipo    
                                        FROM cfdi33_c_conceptos C    
                                        JOIN cfdi33_c_categorias CT 
                                        ON (CT.clave_padre = '0' AND CT.clave = CONCAT(SUBSTR(C.clave, 1, 2), '000000')) 
                                        OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 4), '0000') 
                                        OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 6), '00')
                                        WHERE C.clave = '" . $Cpolist['inv_cproducto'] . "'
                                        ORDER BY CT.clave
                                        ");

                                        $lAg = $busca <> $Cpolist[estudio];
                                        ?>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                1
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt1" value="<?php echo $Cpolist[lt1]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=1 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                2
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt2" value="<?php echo $Cpolist[lt2]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=2 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF"  class="letrap">
                                            <td align="center">
                                                3
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt3" value="<?php echo $Cpolist[lt3]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=3 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                4
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt4" value="<?php echo $Cpolist[lt4]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=4 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                5
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt5" value="<?php echo $Cpolist[lt5]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=5 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                6
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt6" value="<?php echo $Cpolist[lt6]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=6 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                7
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt7" value="<?php echo $Cpolist[lt7]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=7 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                8
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt8" value="<?php echo $Cpolist[lt8]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=8 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                9
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt9" value="<?php echo $Cpolist[lt9]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=9 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                10
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt10" value="<?php echo $Cpolist[lt10]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=10 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                11
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt11" value="<?php echo $Cpolist[lt11]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=11 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                12
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt12" value="<?php echo $Cpolist[lt12]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=12 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                13
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt13" value="<?php echo $Cpolist[lt13]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=13 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                14
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt14" value="<?php echo $Cpolist[lt14]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=14 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                15
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt15" value="<?php echo $Cpolist[lt15]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=15 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                16
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt16" value="<?php echo $Cpolist[lt16]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=16 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                17
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt17" value="<?php echo $Cpolist[lt17]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=17 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                18
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt18" value="<?php echo $Cpolist[lt18]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=18 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                19
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt19" value="<?php echo $Cpolist[lt19]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=19 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                20
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt20" value="<?php echo $Cpolist[lt20]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=20 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                21
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt21" value="<?php echo $Cpolist[lt21]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=21 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td align="center">
                                                22
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt22" value="<?php echo $Cpolist[lt22]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=22 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="background-color:#DDE8FF" class="letrap">
                                            <td align="center">
                                                23
                                            </td>
                                            <td>
                                                <input class="letrap" style="width: 60px" name="Lt23" value="<?php echo $Cpolist[lt23]; ?>" type="text"></input>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    $result = mysql_query("select institucion,alias from inst where lista=23 and status='ACTIVO'", $link);
                                                    while ($row = mysql_fetch_array($result)) {
                                                        echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                
                                            </td>
                                            <td height="40px" align="center" class="letrap">
                                                <?php
                                                if ($Cpo[bloqadm] == 'Si') {
                                                    ?>
                                                    <a class="edit" href="estudiosadmin.php?Op=ab&busca=<?= $busca ?>">
                                                        Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                                    </a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="edit" href="estudiosadmin.php?Op=cr&busca=<?= $busca ?>">
                                                        Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td height="40px" align="center" class="letrap">
                                                <a class="letrap">
                                                    <input type="submit" value="Actualizar" name="op" class="letrap"></input>
                                                    <input type="hidden" value="<?= $_REQUEST[busca] ?>" name="busca"></input>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>                            
                            </tr>
                        </table>
                    </form>
                </td>
                <td valign='top' width='45%'>
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                ..:: Datos Factura ::..
                            </td>
                        </tr>
                        <tr style="height: 100px" valign="top">
                            <td align="right" colspan="2" class='letrap'>
                                <table width="95%" border="0" align="center">
                                    <form name="form2" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr class="letrap">
                                            <td>
                                                Unidad de medida:
                                            </td>
                                            <td>
                                                <select class='cinput' class='InputMayusculas' name='Inv_cunidad'>
                                                    <?php
                                                    $SubDepA = mysql_query("SELECT clave, nombre FROM cfdi33_c_unidades WHERE status = 1");
                                                    While ($Sub = mysql_fetch_array($SubDepA)) {
                                                        echo "<option value='$Sub[clave]'>" . ucwords(strtolower($Sub[nombre])) . " &nbsp;: " . $Sub[clave] . "</option>";
                                                        if ($Cpo[inv_cunidad] == $Sub[clave]) {
                                                            echo '<option selected="' . $Cpo[clave] . '">' . ucwords(strtolower($Sub[nombre])) . ' :' . $Sub[clave] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>     
                                            </td>
                                        </tr>
                                        <tr class="letrap">
                                            <td>
                                                Clave de Producto/Servicio:
                                            </td>
                                            <td>
                                                <select class='cinput' class='InputMayusculas' name='Inv_cproducto'>
                                                    <?php
                                                    $SubDepA = mysql_query("SELECT clave, nombre FROM cfdi33_c_conceptos WHERE status = '1'");
                                                    While ($Sub = mysql_fetch_array($SubDepA)) {
                                                        echo "<option value='$Sub[clave]'>" . ucwords(strtolower($Sub[nombre])) . " &nbsp;: " . strtoupper($Sub[clave]) . "</option>";
                                                        if ($Cpo[inv_cproducto] == $Sub[clave]) {
                                                            echo '<option selected="' . $Cpo[clave] . '">' . ucwords(strtolower($Sub[nombre])) . ' ' . strtoupper($Cpo[clave]) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" height="40px" align="center" class="letrap">
                                                <a class="letrap">
                                                    <input type="submit" value="Actualiza/clasificación" name="op" class="letrap"></input>
                                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                                </a>
                                            </td>
                                        </tr>
                                    </form>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <?php
                    TablaDeLogs("/Estudios/Administracion/", $busca);
                    ?>
                </td>
                <td valign='top' width="22%">
                    <?php
                    $sbmn = 'Admin';
                    Sbmenu();
                    ?>
                </td>
            </tr>        
        </table>    
    </body>
</html>
<?php
mysql_close();
?>
