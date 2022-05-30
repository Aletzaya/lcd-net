<?php
#Librerias
session_start();
date_default_timezone_set("America/Mexico_City");
require("lib/lib.php");

$link = conectarse();


if (isset($_REQUEST["Institucion"])) {

    if ($_REQUEST["busca"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST["Institucion"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST["Institucion"];
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


if (isset($_REQUEST[FechaI])) {
    $_SESSION['OnToy'][5] = $_REQUEST[FechaI];
    $_SESSION['OnToy'][8] = '';
}else{
    $_SESSION['OnToy'][5] = date("Y-m-d");
}


if (isset($_REQUEST[FechaF])) {
    $_SESSION['OnToy'][6] = $_REQUEST[FechaF];
    $_SESSION['OnToy'][8] = '';
}else{
    $_SESSION['OnToy'][6] = date("Y-m-d");


}


$Institucion = $_SESSION["OnToy"][0];
$Institucion = strtolower($Institucion);
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];

#Variables comunes;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$estudio = $_REQUEST["estudio"];
$busca = $_REQUEST["busca"];


$link = conectarse();
$Orden = $_REQUEST["Orden"];
$Cliente = $_REQUEST["cliente"];
$mailpac = $_REQUEST["mailpac"];
$mailmed = $_REQUEST["mailmed"];
$mailinst = $_REQUEST["mailinst"];
$mailotro = $_REQUEST["mailotro"];
$Op = $_REQUEST["Op"];
$Msj = $_REQUEST["Msj"];
$Estudio = $_REQUEST["Estudio"];
$alterno = $_REQUEST["alterno"];
$Reg = $_REQUEST["Reg"];
$Archivo = $_REQUEST["Archivo"];
$Usr = $check['uname'];
$Fechaest = date("Y-m-d H:i:s");
$FechaI = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$FechaF = $_SESSION[OnToy][6];        //Pagina a la que regresa con parametros  


if (isset($_REQUEST["Archivo"])) {
    $cc = "INSERT INTO ingresos (cpu) VALUES ('$_SERVER[REMOTE_ADDR]');";
    mysql_query($cc);


    $img = $_REQUEST[Archivo];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($img));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($img));
    ob_clean();
    flush();
    readfile($img);
}
require ("config.php");

$Fecha = date("Y-m-d");
$Fechaa = date("Y-m-d", strtotime($Fecha . "-6 month"));
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Documento sin título</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                <style type="text/css" >
                    body {
                        background-color: #F6F6F6;
                    }
                </style>
                </head>
                <body bgcolor='#FFFFFF' onload='cFocus()'>   

                    <?php
                    if (true) {
                        ?>
                        <table >
                            <tr style="height: 85px;">
                                <td>

                                </td>
                            </tr>
                        </table>
                        <table width="100%" style='border-collapse: collapse; border: 1px solid #5D6D7E;position:relative;'>
                            <tr bgcolor="#00518C">
                                <td style="width: 280px;">
                                    <img src='images/DuranNvo.png' title='Vista preliminar' border='0' >
                                </td>
                                <td>
                                    <table align="center" border="0" width="100%">
                                        <tr>
                                            <td style="width:1000px" align="center">
                                                <h1><p style="color:#D5D8DC;font-family:courier,arial,helvética;">Entrega de resultados</p></h1>
                                            </td>
                                            <td align="right" style="width:250px">
                                                <a href="indexcli.php"><i style="color:#EC7063" class="fa fa-window-close fa-2x" aria-hidden="true"></i></a>
                                            </td>
                                            <td style="width:25px"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"  align="center">
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <?php $resultado = mysql_fetch_array(mysql_query("SELECT nombre,institucion FROM inst WHERE institucion = " . $Institucion)); ?>
                                                    <b>Institucion :</b> <?= ucwords(strtolower($resultado[institucion])) ?>     <?= ucwords(strtolower($resultado[nombre])) ?>
                                                </p>     
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table><tr style="height: 20px;"><td></td></tr></table>
                        <?php

                    ?>
                    <form name='frmfiltro' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">

                          <span  class="letrap"> Fec.inicial: </span>
                          <input type='date' name='FechaI' value='<?= $FechaI ?>' size='10' class='letrap'></input> 
                          <span  class="letrap"> Fec.final: </span>
                          <input type='date' name='FechaF' value='<?= $FechaF ?>' size='10' class='letrap'></input> 
               
                          <input type="hidden" value='<?= $Institucion?>'  name="Institucion"></input>

                          <input type='submit' name='Boton' value='filtrar' class='letrap'></input>
                         <!-- <a  class='edit' href='?Todo=*'><i class='fa fa-eye fa-2x' aria-hidden='true'></i> Ver todo </a> -->
                    </form>
                      <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                              <td height="380" valign="top">
                                  <?php


                        $Palabras  = str_word_count($busca);  //Dame el numero de palabras
                        if($Palabras > 1){
                           $P=str_word_count($busca,1);          //Metelas en un arreglo
                           for ($i = 0; $i < $Palabras; $i++) {
                                  if(!isset($BusInt)){$BusInt=" cli.nombrec like '%$P[$i]%' ";
                                }
                                  else{$BusInt=$BusInt." and cli.nombrec like '%$P[$i]%' ";}
                           }
                           //$Suc='*';
                        }else{
                           $BusInt=" cli.nombrec like '%$busca%' ";  
                          // $Suc='*';
                        }
                        if( $busca == ''){

                            $SqlB3="SELECT ot.orden
                            FROM ot
                            where ot.fecha='$FechaI' order by hora limit 1";
                        
                            $Sql3=mysql_query($SqlB3,$link);
                        
                            $S3=mysql_fetch_array($Sql3);
                        
                        
                            $SqlB4="SELECT ot.orden
                            FROM ot
                            where ot.fecha='$FechaF' order by orden desc limit 1";
                        
                            $Sql4=mysql_query($SqlB4,$link);
                        
                            $S4=mysql_fetch_array($Sql4);
                        






                            $cSql = "SELECT ot.orden,ot.cliente,ot.fecha,ot.institucion,ot.observaciones,ot.suc,ot.pagada,ot.status,
                            cli.nombrec
                            FROM ot LEFT JOIN cli ON ot.cliente=cli.cliente 
                            WHERE  ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' AND ot.institucion=" . $Institucion . " ";


                        }elseif( $busca < 'a'){                        

                            $cSql = "SELECT ot.orden,ot.cliente,ot.fecha,ot.institucion,ot.observaciones,ot.suc,ot.pagada,ot.status,
                            cli.nombrec
                            FROM ot LEFT JOIN cli ON ot.cliente=cli.cliente 
                            WHERE  ot.orden = '$busca' AND ot.institucion=" . $Institucion . " ";
                          }else{                        
                            $cSql = "SELECT ot.orden,ot.cliente,ot.fecha,ot.institucion,ot.observaciones,ot.suc,ot.pagada,ot.status,
                            cli.nombrec
                            FROM ot LEFT JOIN cli ON ot.cliente=cli.cliente 
                            WHERE  $BusInt AND ot.institucion=" . $Institucion . " ";           

                          }



                        //echo $cSql;
                        $tamPag = 15;
                        $OrdenDef = " ot.orden";
                        $res = mysql_query($cSql);
                        //echo $cSql;
                        CalculaPaginas();


                        echo "<table align='center' width='80%' border='0' cellspacing='0' cellpadding='0' style='border-collapse: collapse; border: 1px solid #fff;position:relative;'>";
                        echo "<tr height='25' bgcolor='#CCCCCC' class='letrap'>";
                        echo "<td align='center'><b>Sucursal</b></td>";
                        echo "<td align='center'><b>Orden</b></td>";
                        echo "<td align='center'><b>Nombre</b></td>";
                        echo "<td align='center'><b>Fecha</b></td>";
                        echo "<td align='center'><b>Status</b></td>";
                        echo "<td align='center'><b>Pagada</b></td>";
                        echo "<td align='center'><b>Detalle</b></td>";
                        echo "</tr>";

                        $sql = $cSql . $cWhe . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;

                        //echo $sql;
                        $rst = mysql_query($sql);
                        while ($rg = mysql_fetch_array($rst)) {
                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = $Gfdogrid;
                            }    //El resto de la division;
                            if($rg[suc]=='0'){
                                $Nsucursal='Administracion';
                            }elseif($rg[suc]=='1'){
                                $Nsucursal='Matriz';
                            }elseif($rg[suc]=='2'){
                                $Nsucursal='Unidad HF';
                            }elseif($rg[suc]=='3'){
                                $Nsucursal='Tepexpan';
                            }elseif($rg[suc]=='4'){
                                $Nsucursal='Reyes';
                            }elseif($rg[suc]=='5'){
                                $Nsucursal='Camarones';
                            }elseif($rg[suc]=='6'){
                                $Nsucursal='San Vicente';
                            }
                            echo "<tr class='letrap' height='30' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td align='center'>$rg[suc]- $Nsucursal</td>";
                            echo "<td><b> &nbsp;  $rg[orden]</b></td>";
                            echo "<td>$rg[nombrec]</td>";
                            echo "<td>$rg[fecha]</td>";
                            echo "<td>$rg[status]</td>";
                            echo "<td>$rg[pagada]</td>";
                            echo "<td><a href='entregaonlineInstituciond.php?Orden=$rg[orden]&busca=$_REQUEST[busca]&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&pagina=$pagina'><i class='fa fa-list-alt fa-2x' aria-hidden='true'></i></td>";
                            echo "</tr>";
                            $nRng++;
                        }

                        echo "</table>";
                        PonPaginacion3(false);  
                                #-------------------pon los No.de paginas-------------------   
                        
                        CuadroInferior5($busca);

                        
                        echo "<br>";
                    } else {
                        ?>
                        <table >
                            <tr style="height: 75px;">
                                <td>

                                </td>
                            </tr>
                        </table>
                        <table width="100%" style='border-collapse: collapse; border: 1px solid #5D6D7E;position:relative;'>
                            <tr bgcolor="#00518C">
                                <td style="width: 280px;">
                                    <img src='images/DuranNvo.png' title='Vista preliminar' border='0' >
                                </td>
                                <td>
                                    <table align="center" border="0" width="100%">
                                        <tr>
                                            <td style="width:1000px" align="center">
                                                <h1><p style="color:#D5D8DC;font-family:courier,arial,helvética;">Entrega de resultados</p></h1>
                                            </td>
                                            <td align="right" style="width:250px">
                                                <a href="indexcli.php"><i style="color:#EC7063" class="fa fa-window-close fa-2x" aria-hidden="true"></i></a>
                                            </td>
                                            <td style="width:25px"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table align="center">
                            <tr>
                                <td>
                                    <p style="color:#FF0000;font-family:courier,arial,helvética;">
                                        Los archivos pdf y radiografias no pueden ser visualizador al tener adeudo.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table><tr style="height: 20px;"><td></td></tr></table>
                        <?php
                        $Sql = mysql_query("SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
  otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
  otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,otd.lugar
  FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Orden'");

//$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

                        echo "<table align='center' width='68%' border='0' cellspacing='0' cellpadding='0' style='border-collapse: collapse; border: 1px solid #fff;position:relative;'>";
                        echo "<tr height='25' bgcolor='#CCCCCC' class='letrap'>";
                        echo "<td align='center'><b>Estudio</b></td>";
                        echo "<td align='center'><b>Descripcion</b></td>";
                        echo "<td align='center'><b>Descarga PDF</b></td>";
                        echo "</tr>";

                        while ($rg = mysql_fetch_array($Sql)) {

                            $clnk = strtolower($rg[estudio]);
                            $Estudio2 = strtoupper($rg[estudio]);

                            $Lugar = $rg[lugar];

                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = $Gfdogrid;
                            }    //El resto de la division;

                            echo "<tr class='letrap' height='30' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td><b> &nbsp;  $rg[estudio]</b></td>";
                            echo "<td>$rg[descripcion]</td>";
                            $Rarchivo = $Orden . "_" . $Estudio2 . ".pdf";
                            if ($rg[status] <> '' and $rg[usrvalida] <> '') {
                                if ($rg[depto] <> 2) {
                                    echo "<td align='center'><a><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></a></td>";
                                } else {
                                    echo "<td align='center'><a><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></a></td> ";
                                }
                            } else {
                                if ($rg[depto] <> 2) {
                                    echo "<td align='center'><a class='edit'><i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>En proceso</a></td>";
                                } else {
                                    if ($rg[status] == 'TERMINADA') {
                                        echo "<td align='center'><a><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></i></a></td> ";
                                    } else {
                                        echo "<td align='center'><a class='edit'><i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>En proceso</a></td>";
                                    }
                                }
                            }
                            echo "</tr>";
                            $nRng++;
                        }

                        echo "</table>";

                        
                        echo "<br>";

                        echo "<br>";
                    }

                    echo "</body>";

                    echo "</html>";

                    mysql_close();
                    ?> 