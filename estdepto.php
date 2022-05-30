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
        //$_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
        $Fecha = date("Y-m-d");

        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', $Retornar, $Fecha, $Fecha, '*', '', 2, '','*');   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST["busca"];
    }
}else{
    $_SESSION['OnToy'][0] = $_REQUEST["busca"];
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
$FechaI = $_SESSION["OnToy"][5]; 
$FechaF = $_SESSION["OnToy"][6];  

$Id = 63;
$FechaT = date("Y-m-d H:i");
$Fecha = date("Y-m-d");
$FechaH = date("H:i");
$Orden2 = $_REQUEST["Orden2"];
$Estudio = $_REQUEST["Estudio"];
$Estudio2 = $_REQUEST["Estudio2"];
$Folio = $_REQUEST["folio"];
$sucorigen = $_REQUEST[sucorigen]; 
$Stat=$_REQUEST[Stat];

if ($GnSuc == "*") {
    $Sucursal = "";
} else {
    $Sucursal = " and ot.suc='$GnSuc'";
}

if (!isset($Stat)){
    $Stat='* Todos';
}else{
    $Stat=$_REQUEST[Stat];
}

if ($_REQUEST[Op] == 'Rec') {

    $SqlC = "SELECT * FROM maqdet WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio'";

    $resC = mysql_query($SqlC);

    $registro3 = mysql_fetch_array($resC);


    if (empty($registro3)) {

        $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,frec,hrec,usrrec)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$FechaH','$Gusr')");
    } else {

        $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frec='$Fecha',hrec='$Hora',usrrec='$Gusr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio' limit 1");
    }

    $Up = mysql_query("UPDATE otd SET proceso='$Gusr' WHERE orden='$Orden2' AND estudio='$Estudio2'");

    $NumA  = mysql_query("SELECT otd.estudio 
    FROM otd 
    WHERE otd.orden='$Orden2' AND otd.proceso=''");

    if(mysql_num_rows($NumA)==0){
      $lUp = mysql_query("UPDATE ot SET status='En Captura' WHERE orden='$Orden2'");
    }else{
      $lUp = mysql_query("UPDATE ot SET status='En Captura Parc' WHERE orden='$Orden2'");
    } 



                //**************************** inventario
    //  if($subdepto<>'VIROLOGIA'){

            $cSql4="SELECT team FROM authuser WHERE authuser.uname='$Gusr'";
            $result4=mysql_query($cSql4);
            $row4=mysql_fetch_array($result4);
            $row4s=$row4[team];

            if($row4s==1 or $row4s==0){

                $Almacen='invl.invmatriz';

            }elseif($row4s==2){

                $Almacen='invl.invhf';

            }elseif($row4s==3){

                $Almacen='invl.invtepex';

            }elseif($row4s==4){

                $Almacen='invl.invreyes';

            }elseif($row4s==5){

                $Almacen='invl.invcam';

            }elseif($row4s==6){

                $Almacen='invl.invsnv';

            }

            $cSql2="SELECT estd.estudio,estd.idproducto,estd.producto,estd.cantidad,invl.clave FROM estd,invl WHERE estd.estudio='$Estudio2' and estd.idproducto=invl.id and estd.suc='$row4s'";

            $result2=mysql_query($cSql2);

            while ($row2=mysql_fetch_array($result2)){

                $cant=$row2[cantidad];

                $id=$row2[idproducto];

              $lUp = mysql_query("UPDATE invl SET $Almacen = $Almacen - $cant, invl.existencia=invl.existencia - $cant WHERE invl.id='$row2[idproducto]' limit 1");

              $Fecha2  = date("Y-m-d H:i");

              $lUp2 = mysql_query("INSERT INTO invldet (fecha,idproducto,producto,estudio,cantidad,usr,suc,orden,sucorigen) 
                               VALUES
                               ('$Fecha2','$id','$row2[producto]','$Estudio2','$row2[cantidad]','$Gusr','$row4s','$Orden2','$sucorigen')");
            }

//      }

            //**************************** inventario
   $busca = $Orden2;

}elseif ($_REQUEST[Op] == 'Rec2') {

    $Up = mysql_query("UPDATE otd SET proceso='' WHERE orden='$Orden2' AND estudio='$Estudio2'");

    $busca = $Orden2;

}

if ($_REQUEST[Op] == '1') {
    if ($_REQUEST[Regis] == '1') {
        $Up = mysql_query("UPDATE otd SET fechaest='$FechaT', usrest='$Gusr', statustom='TOMA/REALIZ', status='TOMA/REALIZ'
					  WHERE orden='$Orden2' AND estudio='$Estudio'");

        $OtdA = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$Orden2' AND estudio='$Estudio'");

        while ($Otd = mysql_fetch_array($OtdA)) {
            $Est = $Otd[estudio];
            if (substr($Otd[dos], 0, 4) == '0000') {
                if ($Otd[lugar] <= '3') {
                    $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$FechaT' 
							 WHERE orden='$Orden2' and estudio='$Estudio' limit 1");
                } else {
                    $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$FechaT' 
							 WHERE orden='$Orden2' AND estudio='$Estudio' limit 1");
                }
            }

            $SqlC = "SELECT * FROM maqdet WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Est'";

            $resC = mysql_query($SqlC, $link);

            $registro4 = mysql_fetch_array($resC);

            if (empty($registro4)) {

                $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
						VALUES
						('$Orden2','$Est','$_REQUEST[Suc]','$Fecha','$Hora','$Usr')");
            } else {
                $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Est',mint='$_REQUEST[Suc]',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Est' limit 1");
            }
        }

        $NumA  = mysql_query("SELECT otd.estudio 
        FROM otd 
        WHERE otd.orden='$Orden2' AND mid(otd.fechaest,1,4)='0000'");

        if(mysql_num_rows($NumA)==0){
          $lUp = mysql_query("UPDATE ot SET status='En Proceso' WHERE orden='$Orden2'");
        }else{
          $lUp = mysql_query("UPDATE ot SET status='En Proceso Parc' WHERE orden='$Orden2'");
        } 

    } else {
        $Up = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$statustom', status='$statustom'
	          WHERE orden='$Orden2' AND estudio='$Estudio'");
    }

    $NumA1 = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$Orden2' AND otd.statustom='PENDIENTE'");

    $NumA2 = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$Orden2' AND otd.statustom=' '");

    if (mysql_num_rows($NumA1) >= 1) {
        $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$Orden2'");
    } else {
        if (mysql_num_rows($NumA2) == 0) {
            $lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$Orden2'");
        } else {
            $lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$Orden2'");
        }
    }
    $busca = $Orden2;
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

if ($Stat=='* Todos'){
  $status=" ";
}elseif($Stat=='TERMINADA'){
  $status="and otd.status='TERMINADA'";
}elseif($Stat=='CANCELADA'){
  $status="and otd.status='CANCELADA'";
}elseif($Stat=='PENDIENTE S/R'){
  $status="and otd.statustom= ";
}else{
  $status="and otd.status<>'TERMINADA' and otd.status<>'CANCELADA'";
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

    $cSql = "SELECT ot.institucion,ot.orden,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso,otd.status,otd.recibeencaja,ot.suc,ot.entemailpac,ot.observaciones,est.subdepto,cli.numveces,otd.fr
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE ot.cliente=cli.cliente and ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $status $dpto $subdpto $Sucursal";
} elseif ($busca < 'a') {

    $cSql = "SELECT ot.institucion,ot.orden,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso,otd.status,otd.recibeencaja,ot.suc,ot.entemailpac,ot.observaciones,est.subdepto,cli.numveces,otd.fr
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE ot.cliente=cli.cliente and otd.orden='$busca'";
} else {

    $cSql = "SELECT ot.institucion,ot.orden,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso,otd.status,otd.recibeencaja,ot.suc,ot.entemailpac,ot.observaciones,est.subdepto,cli.numveces,otd.fr
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE $BusInt";
}


//echo $cSql;
$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Toma", "-", "-", "Cap", "-", "-", "Etiq", "-", "-", "Imp", "-", "-", "Img", "-", "-", "Proc", "-", "-", "Suc", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
//$aDer = array("Msj", "-", "-", "Tom/Real", "-", "-", "Proc", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$aDer = array("Status", "-", "-","ObsG", "-", "-","Correo", "-", "-", "Tom/Real", "-", "-", "Ent.Recep", "-", "-", "Vcs", "-", "-", "Logo", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Estudios por departamento ::..</title>
        <link href="estilos.css?v=1.3" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
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
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=*&busca=ini">
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
                <td class="sbmenu" align="center" style="width: 125px" <?= $color9; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=9">
                        Laboratorio Biologia Molecular
                    </a>
                </td>
                <td class="sbmenu" align="center" style="width: 125px" <?= $color10; ?> >
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=10">
                        Estudios de Gabinete
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
                    if ($Subdpto == $SubD[subdepto]) {
                        $colorsub = 'bgcolor="#84B2D1"';
                    } else {
                        $colorsub = 'bgcolor="#519145"';
                    }
                    ?>
                    <td class="sbmenu" align="center" <?= $colorsub; ?> >
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?Dpto=<?= $Dpto ?>&Subdepto=*">
                            Todos *
                        </a>
                    </td>
                    <?php
                    while ($SubD = mysql_fetch_array($SubA)) {

                        if ($Subdpto == $SubD[subdepto]) {
                            $colorsub = 'bgcolor="#84B2D1"';
                        } else {
                            $colorsub = 'bgcolor="#519145"';
                        }
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

        //submenu();
//Tabla contenedor de brighs
//    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
//    echo '<tr>';
        //   echo '<td valign="top">';
        //CuadroInferior3($busca,$folio);
        ?>
        <table border="0"><tr class='letrap'><td height="10px;"></td></tr></table>
        <form name='frmfiltro' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <table>
                <tr>
                    <td class="letrap">

                        Buscar: 
                        <input class='letrap' type='text' size='30' class='texto_tablas' placeholder='Ingresar Folio o Nombre' name='busca'/>

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

                        Status: 
                        <select class='letrap' name='Stat' onChange='frmfiltro.submit();'>
                            <option value='* Todos'>* Todos</option>
                            <option value='TERMINADA'>Terminada</option>
                            <option value='PENDIENTE'>Pendiente</option>
                            <option value='PENDIENTE S/R'>Pendiente S/R</option>
                            <option value='CANCELADA'>Cancelada</option>
                            <option value='SINCAPTURA'>SINCAPTURA</option>
                            <option selected value='* Todos'><?= $Stat ?></option></p>
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
                </tr>
            </table>
        </form>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();

                    $res = mysql_query($cSql, $link);

                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
                    
                    $sql = $cSql . $cWhe . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    
                    $res = mysql_query($sql);

                    while ($rg = mysql_fetch_array($res)) {
                        $clnk = strtolower($rg[estudio]);
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        if($rg[fr]<>0){
                            $Fdo = 'E6B0AA';
                        }else{
                            $Fdo = $Fdo;
                        }
                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        /*
                          $SqlB = "SELECT *
                          FROM maqdet
                          WHERE maqdet.orden='$rg[orden]' AND maqdet.estudio='$rg[estudio]'";

                          $resB = mysql_query($SqlB);

                          $registro2 = mysql_fetch_array($resB);
                         */

                        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('capturaresultadod.php?busca=$rg[orden]')><i class='fa fa-list-ul fa-lg' aria-hidden='true' style='color:GREEN'></i></a></td>";

                        if ($rg[depto] <> 2) {

                            $capA = mysql_query("SELECT count(orden) as contar FROM resul WHERE orden='$rg[orden]' and estudio='$rg[estudio]'");

                            $cap=mysql_fetch_array($capA);

                            if($cap[contar]==0){
                                $sipdf=0;
                                echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('estdeptocvalcppdf.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')><i class='fa fa-spinner fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";

                            }else{
                                $sipdf=1;
                                echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('estdeptocvalcppdf.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";

                            }

                        } else {
                            if($rg[subdepto] == 'RX_DENTAL'){

                                echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('estdeptodental.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true' style='color:#E74C3C'></i></a></td>";


                            }else{

                                echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('estdeptoc.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true' style='color:#E74C3C'></i></a></td>";

                            }
                        }

                        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('impeti.php?op=1&busca=$rg[orden]&Est=$rg[estudio]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";


                        if ($rg[capturo] <> '') {

                            if ($rg[depto] <> 2) {

                                if ($rg[status] == 'TERMINADA' and $rg[usrvalida] <> '') {
                                                                    
                                    echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td>";

                                }else{

                                    if($sipdf==1){

                                        echo "<td align='center'><font size='1'><b><a class='edit' href=javascript:wingral('estdeptovalida.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')>S/Liberar</a></b></font></td>";

                                    }else{

                                        echo "<td align='center'>-</td>";

                                    }

                                }

                            }else{

                                if($rg[subdepto] == 'RX_DENTAL'){

                                    echo "<td align='center'><a href=javascript:wingral('pdfradiologiadental.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td>";

                                }else{

                                    echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td>";

                                }


                            }
                                
                        } else {

                                echo "<td align='center'>-</td>";

                        }

                        $ImgA = mysql_query("SELECT archivo FROM estudiospdf WHERE id='$rg[orden]' and usrelim=''");

                        $Img = mysql_fetch_array($ImgA);
                        if ($Img[archivo] <> '') {
                            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('displayestudioslcdimg.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-search fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                        } else {
                            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('displayestudioslcdimg.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-upload fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                        }

                        if ($rg[proceso] <> '') {
                           // echo "<td align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[proceso])) . "</b></font></td>";
                            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Estudio2=$rg[estudio]&sucorigen=$rg[suc]&Op=Rec2&Dpto=$Dpto&Subdepto=$Subdpto&FechaI=$FechaI&FechaF=$FechaF&pagina=$pagina&filtro=$filtro'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[proceso])) . "</b></font></a></td>";

                        } else {
                            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Estudio2=$rg[estudio]&sucorigen=$rg[suc]&Op=Rec&Dpto=$Dpto&Subdepto=$Subdpto&FechaI=$FechaI&FechaF=$FechaF&pagina=$pagina&filtro=$filtro'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }

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

                        echo "<td align='center'>$Gfont <font size='1'><b>$Nsucursal</b></font></td>";

                        Display($aCps, $aDat, $rg);

                        if($sipdf==0 and $rg[depto] <> 2){

                            echo "<td align='center' class='letrap'><font size='1'><b>SINCAPTURA</b></font></td>";

                        }else{

                            echo "<td align='center' class='letrap'><font size='1'>$rg[status]</font></td>";

                        }

                        if ($rg[observaciones] <> ''){
                            echo "<td align='center'><a class='pg' href=javascript:winmed('ordenesdxest.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-commenting' aria-hidden='true' style='color:#229954'></i></a></td>";
                        } else {
                            echo "<td align='center'></td>";
                        }

                        if ($rg[entemailpac] == '1' or $rg[entemailmed] == '1' or $rg[entemailinst] == '1') {
                            echo "<td align='center'><a class='pg' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-envelope' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                        } else {
                            echo "<td align='center'><a class='edit' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')>No</a></td>";
                        }

                        if ($rg[statustom] == 'TOMA/REALIZ') {
                            echo "<td align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[usrest])) . "</b></font></td>";
                        } elseif ($rg[statustom] == 'PENDIENTE') {
                            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Op=1&Estudio=$rg[estudio]&Regis=1&Dpto=$Dpto&Subdepto=$Subdpto&FechaI=$FechaI&FechaF=$FechaF&pagina=$pagina&filtro=$filtro'><i class='fa fa-exclamation-triangle fa-lg' aria-hidden='true' style='color:#F4D03F'></i></a></td>";
                        } else {
                            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Op=1&Estudio=$rg[estudio]&Regis=1&FechaI=$FechaI&FechaF=$FechaF&pagina=$pagina'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                        }

                        echo "<td class='Seleccionar' align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[recibeencaja])) . "</b></font></td>";

                        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('repots.php?busca=$rg[cliente]')><b>$rg[numveces]</b></a></td>";

//*****************Con logo *************//

                        if ($rg[capturo] <> '') {

                            if ($rg[depto] <> 2) {

                                if ($rg[status] == 'TERMINADA' and $rg[usrvalida] <> '') {
                                                                    
                                    echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('resultapdf3.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td>";

                                }else{

                                    echo "<td align='center'>-</td>";

                                }

                            }else{

                                if($rg[subdepto] == 'RX_DENTAL'){

                                    echo "<td align='center'><a href=javascript:wingral('pdfradiologiadental.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td>";

                                }else{

                                    echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td>";

                                }


                            }
                                
                        } else {

                                echo "<td align='center'>-</td>";

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
?>
