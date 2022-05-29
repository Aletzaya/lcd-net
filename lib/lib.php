<?php

/**
 * Libreria principal del sistema omicrom.
 * @return type
 */
function conectarse() {

    $Coneccion = $_SESSION[Usr][6];

    if (!($link = mysql_connect("127.0.0.1", "u938386532_root", "Lcd9623299"))) {

        //setcookie ("USERNAME", "");
        //setcookie ("PASSWORD", "");	

        header("Location: index.php?Msj=true");

        //echo "Error conectando a la base de datos.";
        //exit();
    }

    if (!mysql_select_db("u938386532_lcd", $link)) {

        setcookie("USERNAME", "");
        setcookie("PASSWORD", "");

        header("Location: index.php?op=7");
    }

    mysql_set_charset("UTF8", $link);

    return $link;
}

function EjecutaSql($Sql, $Archivo) {
    if (!mysql_query($Sql)) {
        echo "<div align='center'>$Sql</div>";
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=images/IcomExportar17x15p.jpg border=0></a> para regresar</div>');
    }

    return true;
}

function encabezados() {
    global $Gusr, $Gcia, $Gnomcia;
    $Sql = "SELECT COUNT(id) registros FROM msj WHERE para = '$Gusr' AND bd = 0";
    $rs = mysql_query($Sql);
    $tt = mysql_fetch_array($rs);
    $tt["registros"] > 0 ? $Icon = "style='color:red;'" : $Icon = "";
    ?>
    <table id="EncabezadoPrincipal" width="100%" border="0" 
cellpadding="0" cellspacing="0" bgcolor="#313555">
        <tr style="height: 25px;">
            <td width="10px"></td>
            <td width="140px" id="Clic_Atencion"><a href="#" class="sbtitulo"> ATENCION A CLIENTES |</a></td>
            <td width="110px" id="Clic_Operativa"><a href="#" class="sbtitulo"> AREA OPERATIVA |</a></td>
            <td width="150px" id="Clic_Administrativa"><a href="#" class="sbtitulo"> AREA ADMINISTRATIVA |</a></td>
            <td width="120px" id="Sistemas"><a href="#" class="sbtitulo"> SISTEMAS</a></td>
            <td align="right"><a class="sbtitulo"><?= $Gcia . ".- " . $Gnomcia . " |" ?></a></td>
            <td align="right"width="150px">
                <a href="#" title="" class="sbtitulo">
                    <i class="fa fa-user-o fa-2x" aria-hidden="true"></i> Usuario: <?= $Gusr ?> 
                </a>
            </td>
            <td align="right" width="10px">
                <span class="notification-bubble " title="Notificaciones" style="background-color: rgb(245, 108, 126); display: 
                      inline;"><?= $tt["registros"] ?></span>
            </td>
            <td align="right" width="70px">
                <a href=javascript:winmed("mensajes.php"); title="" class="sbtitulo">
                    <i class="fa fa-envelope-o fa-2x" <?= $Icon ?> aria-hidden="true"></i> Correo 
                </a>
            </td>
            <td align="right" width="60px">
                <a href="index.php?op=9" title="" class="sbtitulo">
                    <i class="fa fa-times-circle-o fa-2x" aria-hidden="true"></i> Salir 
                </a>
            </td>
            <td width="10px;"></td>
        </tr>
    </table>
    <?php
}

function Filtro_Menu($Gusr, $sub_mnu) {
    $usr_menu = "SELECT submenu FROM menu_usr WHERE menu_usr.usr = (SELECT id FROM authuser WHERE authuser.uname = '$Gusr')  AND menu_usr.menu='$sub_mnu'";
    $usr_menu = mysql_query($usr_menu);
    $result = mysql_fetch_array($usr_menu);
    $mnu = "SELECT menu.submenu,lugar,url,editor FROM `menu` INNER JOIN menu_usr ON menu.menu=menu_usr.menu AND menu.menu='$sub_mnu' AND menu.lugar in ($result[submenu]) AND menu_usr.usr=(SELECT id FROM authuser WHERE authuser.uname = '$Gusr')";
    $mnu = mysql_query($mnu);
    while ($rs = mysql_fetch_array($mnu)) {
        echo "<li><a href='$rs[url]'>$rs[submenu]</a></li>";
    }
}

function End_Session($Gusr) {
    if (is_null($Gusr)) {
        echo "Sesion finalizada";
        header("Location: index.php?Msj=true");
        exit;
    }
}

function menuprueba($Mnu, $Gusr) {
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td width="1%" style="background-image:url(images/BarraVdeGrad.jpg)"></td>
            <td width="29%" style="background-image:url(images/BarraVdeGrad.jpg)"><a href="menu.php?Mnu=0"><img src="lib/DuranNvoBk.png" width="230" height="90"> </a></td>
            <td width="60%" valign="bottom" style="background-image:url(images/BarraVdeGrad.jpg)">    
                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="Tabla_1">
                    <tr><td>
                            <nav>
                                <ul class="mmenu">
                                    <li id="Recepcion_muestra"><a href="#">
                                            <div id="recepcion"></div>
                                        </a>
                                        <ul>
                                            <?php
                                            End_Session($Gusr);
                                            Filtro_Menu($Gusr, "recepcion");
                                            ?>
                                        </ul>
                                    </li>
                                    <li id="Catalogo_muestra"><a href="#"><div id="catalogo"></div></a>
                                        <ul>
                                            <?php
                                            Filtro_Menu($Gusr, "catalogos");
                                            ?>
                                        </ul>
                                    </li>
                                    <li id="Movil_muestra"><a href="#">
                                            <div id="movil"></div>
                                        </a>
                                        <ul>
                                            <?php
                                            Filtro_Menu($Gusr, "movil");
                                            ?>
                                        </ul>
                                    </li>
                                    <li id="Reporte_muestra"><a href="#">
                                            <div id="reporte"></div>
                                        </a>
                                        <ul>
                                            <?php
                                            Filtro_Menu($Gusr, "reportes");
                                            ?>
                                        </ul>
                                    </li>
                                </ul>

                            </nav>

                        </td>
                    </tr>
                </table>
                <table width="360" border="0" cellpadding="0" cellspacing="0" id="Tabla_2">
                    <tr>
                        <td>
                            <nav>
                                <ul class="mmenu">
                                    <li id="Procesos_muestra"><a href="#"><img src="images/btn_procesos-1.png"></a>
                                        <ul>
                                            <?php
                                            Filtro_Menu($Gusr, "procesos");
                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </td>
                    </tr>
                </table>      
                <table width="360" border="0" cellpadding="0" cellspacing="0" id="Tabla_3">
                    <tr>
                        <td>
                            <nav>
                                <ul class="mmenu">
                                    <li id="Admin_muestra"><a href="#"><img src="images/btn_administracion-1.png"></a>
                                        <ul>
                                            <?php
                                            Filtro_Menu($Gusr, "admin");
                                            ?>
                                        </ul>
                                    </li>
                                </ul>

                            </nav>

                        <td>
                    </tr>
                </table>      
                <table width="360" border="0" cellpadding="0" cellspacing="0" id="Tabla_4">
                    <td><tr>
                    <nav>
                        <ul class="mmenu">
                            <li id="Respaldos_muestra"><a href="#"><img src="images/btn_respaldo-1.png"></a>
                                <ul>
                                    <?php
                                    Filtro_Menu($Gusr, "respaldos");
                                    ?>
                                </ul>
                            </li>
                        </ul>

                    </nav>

        </tr><td>
    </table>      

    </td>
    <td align="right" valign="middle" style="background-image:url(images/BarraVdeGrad.jpg)">

        <table width="220" border="0" cellpadding="0" cellspacing="0">
            <tr> </tr>
            <tr>
                <!--<td height="70"><a href="#" class="btn" title=""><img src="images/BarraVdeAccesDBot1B.jpg"></a></td>
                <td><a class="btn" href="#" title=""><img src="images/BarraVdeAccesDBot2B.jpg"></a></td>
                <td><a class="btn" href="#" title=""><img src="images/BarraVdeAccesDBot3B.jpg"></a></td>
                <td><a class="btn" href="#" title=""><img src="images/BarraVdeAccesDBot4B.jpg"></a></td>-->
            </tr>
        </table>

    </td>
    </tr>
    </table>    
    <?php
}

function menu($Mnu, $Gusr) {
    ?>
    <table id='BarraPrincipal'>
        <tr class="fondo">
            <td width="30%" class="BarraPrincipal">
                <a href="menu.php"><div title="Logo Laboratorio Duran" id="logoPrincipal"></div></a>
            </td>
            <td width="61%" valign="bottom">    
                <table  width="100%" id="Tabla_1">
                    <tr>
                        <td>
                            <nav>
                                <ul class="mmenu">
                                    <li>
                                        <a href="#"><div title="Menu recepciones" id="recepcion1"></div></a>
                                        <ul class="BarraPrincipal" >
                                            <?php
                                            End_Session($Gusr);
                                            Filtro_Menu($Gusr, "recepcion");
                                            ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><div title="Menu catalogo" id="catalogo1"></div></a>
                                        <ul class="BarraPrincipal" >
                                            <?php Filtro_Menu($Gusr, "catalogos"); ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><div title="Menu movil" id="movil1"></div></a>
                                        <ul class="BarraPrincipal" >
                                            <?php Filtro_Menu($Gusr, "movil"); ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><div title="Menu reportes" id="reporte1"></div></a>
                                        <ul class="BarraPrincipal" >
                                            <?php Filtro_Menu($Gusr, "reportes"); ?>
                                        </ul>
                                    </li>
                                </ul>

                            </nav>

                        </td>
                    </tr>
                </table>
                <table width="100%" id="Tabla_2">
                    <tr>
                        <td>
                            <nav>
                                <ul class="mmenu">
                                    <li><a href="#"><div title="Menu procesos" id="procesos_muestra1"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "procesos"); ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><div title="Menu catalogo" id="catalogo1"></div></a>
                                        <ul class="BarraPrincipal" >
                                            <?php Filtro_Menu($Gusr, "catalogos"); ?>
                                        </ul>
                                    </li>
                                    <li><a href="#"><div title="Menu productividad" id="productividad"></div></a>
                                        <ul>
                                            <?php Filtro_Menu($Gusr, "productividad"); ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><div id="libre0"></div></a><ul class="BarraPrincipal"></ul>
                                    </li>
                                </ul>
                            </nav>
                        </td>
                    </tr>
                </table>      
                <table width="100%" id="Tabla_3">
                    <tr>
                        <td>
                            <nav>
                                <ul class="mmenu">
                                    <li><a href="#"><div title="Menu administracion" id="administracion1"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "admin"); ?>
                                        </ul>
                                    </li>
                                    <li><a href="#"><div title="Menu reportes" id="reporte2"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "reportes"); ?>
                                        </ul>
                                    </li>
                                    <li><a href="#"><div title="Menu promocion" id="promocion1"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "promocion"); ?>
                                        </ul>
                                    </li>
                                    <li><a href="#"><div title="Menu recursos humanos" id="recursosh1"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "recursosh"); ?>
                                        </ul>
                                    </li>
                                </ul>

                            </nav>

                        <td>
                    </tr>
                </table>      
                <table width="100%" id="Tabla_4">
                    <tr>
                        <td>
                            <nav>
                                <ul class="mmenu">
                                    <li><a href="#"><div title="Menu respaldos" id="respm"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "respaldos"); ?>
                                        </ul>
                                    </li>
                                    <li><a href="#"><div title="Menu usuarios" id="usuarios0"></div></a>
                                        <ul class="BarraPrincipal">
                                            <?php Filtro_Menu($Gusr, "usuarios"); ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><div id="libre2"></div></a><ul class="BarraPrincipal"></ul>
                                    </li>
                                    <li>
                                        <a href="#"><div id="libre3"></div></a><ul class="BarraPrincipal"></ul>
                                    </li>
                                </ul>
                            </nav>
                        <td></tr>
                </table>      

            </td>
            <td class="BarraPrincipal" align="right" valign="middle">

                <table width="220" border="0" cellpadding="0" cellspacing="0">
                    <tr> </tr>
                    <tr>
                        <td align="right">
                            <a href=javascript:winmed("ordenrecest.php"); title="Entrega de resultados" class="sbtitulo">
                                <img src="images/BarraVdeAccesDBot3B.jpg">
                            </a>
                        </td>
                        <td align="right" id="masopc">
                            <a class="btn" href="facturas.php?busca=ini" title="Facturación">
                                <img src="images/BarraVdeAccesDBot2B.jpg">
                            </a>
                        </td>
                        <td align="right" id="masopcalend">
                            <a class="btn" href=javascript:winmed("calendarioV2.php?suc=Admi"); title="Agenda">
                                <img src="images/Agenda.png">
                            </a>
                        </td>
                        <td align="right" width="5%">
                        </td>
                        <td align="right" id="masopc">
                            <a class="btn" href=javascript:winmed("checkinout.php"); title="Facturación">
                                <img src="images/accesoper.png">
                            </a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>  
    <?php
}

function menu2() {
    echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="5" height="90" td="td" style="background-image:url(images/BarraVde1.jpg)"></td>';
    echo '<td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="images/logoDuran.png" width="230" height="90" alt="Logotipo Laboratorio Clínico Durán" /></td>';
    echo '<td width="40%" valign="bottom" style="background-image:url(images/BarraVdeGrad.jpg)">';

    echo '<table width="360" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="80" height="86" class="clinico" id="clinico"><a href="#.html"  class="clinico"></a></td>';
    echo '<td width="80" class="rayosx" id="rayosx"><a href="#" title="" class="rayosx"></a></td>';
    echo '<td width="80" class="especiales" id="especiales"><a href="#" title="" class="especiales"></a></td>';
    echo '<td width="80" id="catalogos"><a href="#" title="" class="catalogos"></a></td>';
    echo '<td width="80" id="reportes"><a href="#" title="" class="reportes"></a></td></tr>';
    echo '</tr>';
    echo '</table>';

    echo '</td>';
    echo '<td align="right" valign="middle" style="background-image:url(images/BarraVdeGrad.jpg)">';

    echo '<table width="200" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr> </tr>';
    echo '<tr>';
    echo '<td height="64"><a href="#" title="" id="cotizador"></a></td>';
    echo '<td><a href="#" title="" id="factura"></a></td>';
    echo '<td><a href="#" title="" id="entrega-resultado"></a></td>';
    echo '<td><a href="#" title="" id="entrega-caja"></a></td>';
    echo '</tr>';
    echo '</table>';

    echo '</td>';
    echo '<td width="5" td="td" style="background-image:url(images/BarraVdeGrad.jpg)"></td>';
    echo '<td width="5" td="td" style="background-image:url(images/BarraVde2.jpg)"></td>';
    echo '</tr>';
    echo '</table>';
}

function menu3() {
    echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="5" height="90" td="td" style="background-image:url(images/BarraVde1.jpg)"></td>';
    echo '<td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="images/logoDuran.png" width="230" height="90" alt="Logotipo Laboratorio Clínico Durán" /></td>';
    echo '<td width="40%" valign="bottom" style="background-image:url(images/BarraVdeGrad.jpg)">';

    echo '<table width="360" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="80" height="86" class="administracion" id="administracion"><a href="#"  class="administracion"></a></td>';
    echo '<td width="80" class="recursosh" id="recursosh"><a href="#" title="" class="recursosh"></a></td>';
    echo '<td width="80" class="promocion" id="promocion"><a href="#" title="" class="promocion"></a></td>';
    echo '<td width="80" class="catalogos" id="catalogos"><a href="#" title="" class="catalogos"></a></td>';
    echo '</tr>';
    echo '</table>';

    echo '</td>';
    echo '<td align="right" valign="middle" style="background-image:url(images/BarraVdeGrad.jpg)">';

    echo '<table width="200" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr> </tr>';
    echo '<tr>';
    echo '<td height="64"><a href="#" title="" id="cotizador"></a></td>';
    echo '<td><a href="#" title="" id="factura"></a></td>';
    echo '<td><a href="#" title="" id="entrega-resultado"></a></td>';
    echo '<td><a href="#" title="" id="entrega-caja"></a></td>';
    echo '</tr>';
    echo '</table>';

    echo '</td>';
    echo '<td width="5" td="td" style="background-image:url(images/BarraVdeGrad.jpg)"></td>';
    echo '<td width="5" td="td" style="background-image:url(images/BarraVde2.jpg)"></td>';
    echo '</tr>';
    echo '</table>';

    echo '<table  border="0" bordercolor="#daeed2" width="100%"  cellpadding="0" cellspacing="0">';
    echo '<tr height="34" align="center" width="100%">';
    echo '<td bgcolor="#daeed2"height="0" valign="top" bgcolor="#FFFFFF">';
    echo '<div id="two-level-administracion">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">Captura OTs</a></li>';
    echo '<li><a href="#" title="">Clientes F.</a></li>';
    echo '<li><a href="#" title="">Facturación</a></li>';
    echo '<li><a href="#" title="">Ingresos</a></li>';
    echo '<li><a href="#" title="">Re-Imp. R.</a></li>';
    echo '<li><a href="#" title="">Corte C.</a></li>';
    echo '<li><a href="#" title="">Trazabilidad</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-recursosh">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">Personal</a></li>';
    echo '<li><a href="#" title="">Asistencia</a> </li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-promocion">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">Consulta OT s</a> </li>';
    echo '<li><a href="#" title="">Comisiones</a> </li>';
    echo '<li><a href="#" title="">Recibos</a></li>';
    echo '<li><a href="#" title="">Pagos</a></li>';
    echo '<li><a href="#" title="">Visitas</a></li>';
    echo '<li><a href="#" title="">Rutas médicas</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-catalogos">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">Estudios</a></li>';
    echo '<li><a href="#" title="">Precios</a> </li>';
    echo '<li><a href="#" title="">Médicos</a> </li>';
    echo '<li><a href="#" title="">Instituciones</a> </li>';
    echo '<li><a href="#" title="">Zonas</a> </li>';
    echo '<li><a href="#" title="">Personal</a> </li>';
    echo '</ul>';
    echo '</div>';
    echo '</td></tr>';
    echo '</table>';
}

function menu4() {
    echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="5" height="90" td="td" style="background-image:url(images/BarraVde1.jpg)"></td>';
    echo '<td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="images/logoDuran.png" width="230" height="90" alt="Logotipo Laboratorio Clínico Durán" /></td>';
    echo '<td width="40%" valign="bottom" style="background-image:url(images/BarraVdeGrad.jpg)">';

    echo '<table width="360" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="80" height="86" class="usuarios" id="usuarios"><a href="#.html"  class="usuarios"></a></td>';
    echo '<td width="80" class="niveles" id="niveles"><a href="#" title="" class="niveles"></a></td>';
    echo '<td width="80" class="bases" id="bases"><a href="#" title="" class="bases"></a></td>';
    echo '<td width="80" class="respaldo" id="respaldo"><a href="#" title="" class="respaldo"></a></td>';
    echo '<td width="80" class="unidades" id="unidades"><a href="#" title="" class="unidades"></a></td> ';
    echo '</tr>';
    echo '</table>';

    echo '</td>';
    echo '<td align="right" valign="middle" style="background-image:url(images/BarraVdeGrad.jpg)">';

    echo '<table width="200" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr> </tr>';
    echo '<tr>';
    echo '<td height="64"><a href="#" title="" id="cotizador"></a></td>';
    echo '<td><a href="#" title="" id="factura"></a></td>';
    echo '<td><a href="#" title="" id="entrega-resultado"></a></td>';
    echo '<td><a href="#" title="" id="entrega-caja"></a></td>';
    echo '</tr>';
    echo '</table>';

    echo '</td>';
    echo '<td width="5" td="td" style="background-image:url(images/BarraVdeGrad.jpg)"></td>';
    echo '<td width="5" td="td" style="background-image:url(images/BarraVde2.jpg)"></td>';
    echo '</tr>';
    echo '</table>';

    echo '<table  border="0" bordercolor="#daeed2" width="100%"  cellpadding="0" cellspacing="0">';
    echo '<tr height="34" align="center" width="100%">';
    echo '<td bgcolor="#daeed2"height="0" valign="top" bgcolor="#FFFFFF">';
    echo '<div id="two-level-recepcion999">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">...</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-catalogos">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">...</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-moviles">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">...</a> </li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-reportes">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">...</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<div id="two-level-ingresos">';
    echo '<ul id="horizontal-main-menu">';
    echo '<li><a href="#" title="">...</a> </li>';
    echo '</ul>';
    echo '</div>';
    echo '</td></tr>';
    echo '</table>';
}

function PieDePagina() {

    echo "<table width='1011' border='0' align='center' cellpadding='0' cellspacing='0' style='border-collapse: collapse; border: 1px solid #066;'>";
    echo "<tr background='libnvo/fondo_verde_down.png' height='40'>";
    echo "<td width='960' align='center' valign='center' class='Footer'>";
    echo " DETISA ::: Normal de Maestros No.10 Col. Tulantongo Texcoco Estado de Mexico Cp. 56217 Tel. 01(595) 9250401 / 1117518 www.detisa.com.mx";
    echo "</td></tr></table>";
}

function cZeros($Vlr, $nLen) {   // Function p/ rellenar de zeros
    for ($i = strlen($Vlr); $i < $nLen; $i = $i + 1) {
        $Vlr = "0" . $Vlr;
    }
    return $Vlr;
}

function PonEncabezado() {

    global $OrdenDef, $aIzq, $aDat, $aDer, $aCps, $Sort;   #P k reconoscan el valor k traen

    echo "<table align='center' width='100%' border='0' cellpadding='1' cellspacing='0' style='border-collapse: collapse; border: 1px solid #cccccc;'>";
    echo "<tr bgcolor='#5499C7'>";

    for ($i = 0; $i < sizeof($aIzq); $i = $i + 3) {
        echo "<td align='center' class='letrap' style='color:#D5D8DC;'> $aIzq[$i]</td>";
    }
    $x = 0;
    for ($i = 0; $i < sizeof($aDat); $i = $i + 3) {
        $Pso = $aCps[$x];
        if ($OrdenDef == $aCps[$x]) {   //Es el campo por el cual esta en este momento ordenado;
            if ($Sort == 'Asc') {
                $Srt = 'Desc';
                $iImg = '<i style="color:#5D6D7E;" class="fa fa-chevron-circle-up" aria-hidden="true"></i>';
            } else {
                $Srt = 'Asc';
                $iImg = '<i style="color:#5D6D7E;" class="fa fa-chevron-circle-down" aria-hidden="true"></i>';
            }
            echo "<td>&nbsp;<b><a class='letra' style='color:#D5D8DC;' href='" . $_SERVER["PHP_SELF"] . "?orden=$Pso&Sort=$Srt'>$aDat[$i]</a></b>&nbsp;$iImg</td>";
        } else {
            echo "<td style='color:#D5D8DC;'>&nbsp;<b><a class='letra' style='color:#D5D8DC;' href='" . $_SERVER["PHP_SELF"] . "?orden=$Pso&Sort=Asc'>$aDat[$i] </a></b></td>";
        }

        $x++;
    }

    for ($i = 0; $i < sizeof($aDer); $i = $i + 3) {
        echo "<td class='content2' style='color:#D5D8DC;'> $aDer[$i]</td>";
    }

    echo "</tr>";

    return true;
}

function PonEncabezadocat() {

    global $OrdenDef, $aIzq, $aDat, $aDer, $aCps, $Sort, $estudio, $suc;   #P k reconoscan el valor k traen

    echo "<table align='center' width='100%' border='0' cellpadding='1' cellspacing='0' style='border-collapse: collapse; border: 1px solid #cccccc;'>";
    echo "<tr bgcolor='#5499C7'>";

    for ($i = 0; $i < sizeof($aIzq); $i = $i + 3) {
        echo "<td align='center' class='letrap' style='color:#D5D8DC;'> $aIzq[$i]</td>";
    }
    $x = 0;
    for ($i = 0; $i < sizeof($aDat); $i = $i + 3) {
        $Pso = $aCps[$x];
        if ($OrdenDef == $aCps[$x]) {   //Es el campo por el cual esta en este momento ordenado;
            if ($Sort == 'Asc') {
                $Srt = 'Desc';
                $iImg = '<i style="color:#5D6D7E;" class="fa fa-chevron-circle-up" aria-hidden="true"></i>';
            } else {
                $Srt = 'Asc';
                $iImg = '<i style="color:#5D6D7E;" class="fa fa-chevron-circle-down" aria-hidden="true"></i>';
            }
            echo "<td>&nbsp;<b><a class='letra' style='color:#D5D8DC;' href='" . $_SERVER["PHP_SELF"] . "?orden=$Pso&Sort=$Srt&estudio=$estudio&suc=$suc'>$aDat[$i]</a></b>&nbsp;$iImg</td>";
        } else {
            echo "<td style='color:#D5D8DC;'>&nbsp;<b><a class='letra' style='color:#D5D8DC;' href='" . $_SERVER["PHP_SELF"] . "?orden=$Pso&Sort=Asc&estudio=$estudio&suc=$suc'>$aDat[$i] </a></b></td>";
        }

        $x++;
    }

    for ($i = 0; $i < sizeof($aDer); $i = $i + 3) {
        echo "<td class='content2' style='color:#D5D8DC;'> $aDer[$i]</td>";
    }

    echo "</tr>";

    return true;
}

function PonPaginacion($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?busca=NUEVO'>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo "<font color='#2869F3'><b> [ $pag ] </b></font>";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}

function PonPaginacionventana($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href=javascript:wingral('$cLink?busca=NUEVO')>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo "<font color='#2869F3'><b> [ $pag ] </b></font>";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}

function PonPaginacionesp($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . '.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?busca=NUEVO'>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo "<font color='#2869F3'><b> [ $pag ] </b></font>";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}


function PonPaginacionesdep($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar,$deptos;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . '.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?deptos=$deptos&busca=NUEVO'>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo "<font color='#2869F3'><b> [ $pag ] </b></font>";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}


function PonPaginacioncat($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar, $estudio, $suc;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini&estudio=$estudio&suc=$suc' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i&estudio=$estudio&suc=$suc'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1&estudio=$estudio&suc=$suc'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg&estudio=$estudio&suc=$suc'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo "<font color='#2869F3'><b> [ $pag ] </b></font>";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i&estudio=$estudio&suc=$suc'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg&estudio=$estudio&suc=$suc'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags&estudio=$estudio&suc=$suc'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}

function PonPaginacion3($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar, $Institucion, $FechaI, $FechaF, $Usr, $Tabla;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='80%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='90%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?busca=NUEVO'>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    //echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?Institucion=$Institucion' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?pagina=$i&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&usr=$Usr&tabla=$Tabla'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?pagina=1&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&usr=$Usr&tabla=$Tabla'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?pagina=$pg&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&usr=$Usr&tabla=$Tabla'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo "<font color='#2869F3'><b> [ $pag ] </b></font>";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?pagina=$i&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&usr=$Usr&tabla=$Tabla'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?pagina=$pg&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&usr=$Usr&tabla=$Tabla'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?pagina=$numPags&Institucion=$Institucion&FechaI=$FechaI&FechaF=$FechaF&usr=$Usr&tabla=$Tabla'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}

function PonPaginacionDif($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar, $regresa;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?busca=NUEVO'>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&regresa=$regresa&pagina=1'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&regresa=$regresa&pagina=$pg'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo " $pag &nbsp;";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&regresa=$regresa&pagina=$i'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg&regresa=$regresa'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&regresa=$regresa&pagina=$numPags'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}

function PonPaginacion2($Bd, $fitro, $campo, $nLink) {
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros, $Retornar, $Dpto, $Subdpto, $GnSuc, $Stat;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>";
    echo "<tr><td>";
    echo "<font color='#FF6633'>&nbsp; $Msj </font>";
    echo "</td>";
    echo "<td width='250' align='right'  > Registros:  " . number_format($numeroRegistros, "0") . " &nbsp;</td>";
    echo "</tr></table>";

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?busca=NUEVO'>Agregar <i class='fa fa-plus-circle fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <i class='fa fa-cloud-upload fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a> &nbsp; </td>";

    if ($fitro) {
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if ($campo) {
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }

    foreach ($nLink as $key => $value) {
        echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }

    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <i class='fa fa-repeat fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;&nbsp;";

    echo "</td><td align='right' class='letraex'> ";

    if ($numPags <= 10) {

        for ($i = 1; $i <= $numPags; $i++) {

            if ($i == $pagina) {

                echo " $i ";
            } else {

                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i&Dpto=$Dpto&Subdepto=$Subdpto&FiltroCia=$GnSuc&Stat=$Stat'>&nbsp;$i&nbsp;</a> ";
            }
        }
    } else {

        $ini = 1;
        if ($pagina >= 7) {

            $ini = $pagina - 5;

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1&Dpto=$Dpto&Subdepto=$Subdpto&FiltroCia=$GnSuc&Stat=$Stat'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg&Dpto=$Dpto&Subdepto=$Subdpto&FiltroCia=$GnSuc&Stat=$Stat'><i class='fa fa-angle-left fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
            }

            if ($ini + 10 > $numPags) {
                $ini = $numPags - 10;
            }
        }

        $fin = $ini + 10;

        for ($i = $ini; $i <= $fin; $i++) {

            $pag = cZeros($i, 2);

            if ($i == $pagina) {

                echo " $pag &nbsp;";
            } else {
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i&Dpto=$Dpto&Subdepto=$Subdpto&FiltroCia=$GnSuc&Stat=$Stat'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg&Dpto=$Dpto&Subdepto=$Subdpto&FiltroCia=$GnSuc&Stat=$Stat'><i class='fa fa-angle-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags&Dpto=$Dpto&Subdepto=$Subdpto&FiltroCia=$GnSuc&Stat=$Stat'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true' style='color:#2869F3;'></i></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";


    return true;
}

function CuadroInferior($busca) {
    global $Dsp, $Msj, $pagina, $busca, $Id, $orden, $aIzq, $aDat, $aDer, $aCps, $Sort, $Qry;

    echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
    echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

    echo "<script type=\"text/javascript\">";
    echo "$(document).ready(function() {";
    echo "$('#autocomplete').suggestionTool("
    . "$('#form10'),"
    . "'" . $Qry['froms'] . " " . $Qry['joins'] . "', "
    . "function() { "
    . "var orderValue = $('input[name=orden]:checked').val();"
    . "return orderValue.indexOf(' as ') >= 0 ? "
    . "orderValue.split(' as ')[0] : "
    . "orderValue; });";
    echo "});";
    echo "</script>";

    echo "<div>";

    echo "<form name='form99' id='form10' method='post' action='$_SERVER[PHP_SELF]'>";
    $num = (sizeof($aDat) / 3) + 1;
    echo "<table  align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";
    echo "<tr bgcolor='#daeed2'><td valign='center' height='70'>";

    echo "<table width='98%' border='0' cellpadding='0' cellspacing='0' align='center'>";


    echo "<tr class='texto_tablas'>";
    echo "<td width='5px' height='30' background='images/GradTope8x30A.jpg'>&nbsp;</td>";
    echo "<td width='25%' background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;&nbsp;B&uacute;squeda r&aacute;pida:&nbsp; </a>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . "size=\"20\" "
    . "class=\"texto_tablas\" "
    . "placeholder=\"Ingresar palabra(s)\" "
    . "name=\"busca\" "
    . "id=\"autocomplete\"/>&nbsp;";
    echo "</td>";
    echo "<td background='images/gradGr1x30p.jpg'><a class='letrap'>Seleccione el orden de b&uacute;squeda</a></td>";

    $x = 0;
    for ($i = 0; $i < sizeof($aDat); $i = $i + 3) {
        $Pso = $aCps[$x];
        if (strpos($Pso, $orden) === 0) {   //Es el campo por el cual esta en este momento ordenado;
            echo "<td background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;$aDat[$i]&nbsp;</a><input class='letrap' type='radio' name='orden' value='$aCps[$x]' checked></td>";
        } else {
            echo "<td background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;$aDat[$i]&nbsp;</a><input class='letrap' type='radio' name='orden' value='$aCps[$x]'></td>";
        }
        $x++;
    }
    echo "<td background='images/gradGr1x30p.jpg' width='9px' align='center'>";
    echo "<input type='submit' class='letrap' name='Boton' value='Enviar' class='nombre_cliente'>";
    echo "<input type='hidden' class='letrap'name='pagina' value='1'>";
    echo "</td>";
    //echo "<a class='textosCualli' href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=' title='Actualiza la pantalla'><img src='libnvo/refresh.png' width='25' hegiht='25'></a>";
    echo "<td width='8px' height='30' background='images/GradTope8x30B.jpg'>&nbsp;</td>";
    echo "</tr></table>";

    echo "</td></tr></table>";

    echo "</form>";

    echo "<p align='center'><font color='cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp $Msj &nbsp; &nbsp; </b></font></p>";

    echo "</div>";

    return true;
}

function CuadroInferior2($busca) {
    global $Dsp, $Msj, $pagina, $busca, $Id, $orden, $aIzq, $aDat, $aDer, $aCps, $Sort, $Qry;

    echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
    echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

    echo "<script type=\"text/javascript\">";
    echo "$(document).ready(function() {";
    echo "$('#autocomplete').suggestionTool("
    . "$('#form10'),"
    . "'" . $Qry['froms'] . " " . $Qry['joins'] . "', "
    . "function() { "
    . "var orderValue = $('input[name=orden]:checked').val();"
    . "return orderValue.indexOf(' as ') >= 0 ? "
    . "orderValue.split(' as ')[0] : "
    . "orderValue; });";
    echo "});";
    echo "</script>";

    echo "<div>";

    echo "<form name='form99' id='form10' method='post' action='$_SERVER[PHP_SELF]'>";
    $num = (sizeof($aDat) / 3) + 1;
    echo "<table  align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";
    echo "<tr bgcolor='#daeed2'><td valign='center' height='70'>";

    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='right'>";


    echo "<tr class='texto_tablas'>";
    //echo "<td width='5px' height='30' background='images/GradTope8x30A.jpg'>&nbsp;</td>";
    echo "<td width='30%' background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;&nbsp;B&uacute;squeda r&aacute;pida:&nbsp; </a>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . " autofocus "
    . "size=\"30\" "
    . "class=\"texto_tablas\" "
    . "placeholder=\"Ingresar Folio o Nombre\" "
    . "name=\"busca\" "
    . "id=\"autocomplete\"/>&nbsp;";
    echo "</td>";
    echo "<td background='images/gradGr1x30p.jpg' width='9px' align='left'>";
    echo "<input type='submit' class='letrap' name='Boton' value='Enviar' class='nombre_cliente'>";
    echo "<input type='hidden' class='letrap'name='pagina' value='1'>";
    echo "</td>";
    //echo "<a class='textosCualli' href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=' title='Actualiza la pantalla'><img src='libnvo/refresh.png' width='25' hegiht='25'></a>";
    echo "<td width='8px' height='30' background='images/gradGr1x30pcu.jpg'>&nbsp;</td>";
    echo "</tr></table>";

    echo "</td></tr></table>";

    echo "</form>";

    echo "<p align='center'><font color='cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp $Msj &nbsp; &nbsp; </b></font></p>";

    echo "</div>";

    return true;
}

function CuadroInferior3($busca, $folio) {
    global $Dsp, $Msj, $pagina, $busca, $Id, $orden, $folio, $aIzq, $aDat, $aDer, $aCps, $Sort, $Qry;

    echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
    echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

    echo "<script type=\"text/javascript\">";
    echo "$(document).ready(function() {";
    echo "$('#autocomplete').suggestionTool("
    . "$('#form10'),"
    . "'" . $Qry['froms'] . " " . $Qry['joins'] . "', "
    . "function() { "
    . "var orderValue = $('input[name=orden]:checked').val();"
    . "return orderValue.indexOf(' as ') >= 0 ? "
    . "orderValue.split(' as ')[0] : "
    . "orderValue; });";
    echo "});";
    echo "</script>";

    echo "<div>";

    echo "<form name='form99' id='form10' method='post' action='$_SERVER[PHP_SELF]'>";
    $num = (sizeof($aDat) / 3) + 1;
    echo "<table  align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";
    echo "<tr bgcolor='#daeed2'><td valign='center'>";

    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='right'>";


    echo "<tr class='texto_tablas'>";
    //echo "<td width='5px' height='30' background='images/GradTope8x30A.jpg'>&nbsp;</td>";
    echo "<td width='25%' background='images/gradGr1x30p.jpg' height='30'><a class='letrap'>&nbsp;&nbsp;Buscar :&nbsp; </a>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . " autofocus "
    . "size=\"30\" "
    . "class=\"texto_tablas\" "
    . "placeholder=\"Ingresar Folio o Nombre\" "
    . "name=\"busca\" "
    . "id=\"autocomplete\"/>&nbsp;";
    echo "</td>";
    echo "<td width='25%' background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;&nbsp;Folio :&nbsp; </a>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . "size=\"30\" "
    . "class=\"texto_tablas\" "
    . "placeholder=\"Ingresar Folio\" "
    . "name=\"folio\" "
    . "id=\"autocomplete\"/>&nbsp;";
    echo "</td>";
    echo "<td background='images/gradGr1x30p.jpg' width='9px' align='left' width='40%'>";
    echo "<input type='submit' class='letrap' name='Boton' value='Enviar' class='nombre_cliente'>";
    echo "<input type='hidden' class='letrap'name='pagina' value='1'>";
    echo "</td>";
    //echo "<a class='textosCualli' href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=' title='Actualiza la pantalla'><img src='libnvo/refresh.png' width='25' hegiht='25'></a>";
    // echo "<td width='8px' height='30' background='images/gradGr1x30pcu.jpg'>&nbsp;</td>";
    echo "</tr></table>";

    echo "</td></tr></table>";

    echo "</form>";
    echo "</div>";

    return true;
}

function CuadroInferior4($busca) {
    global $Dsp, $Msj, $pagina, $busca, $Id, $orden, $aIzq, $aDat, $aDer, $aCps, $Sort, $Qry, $Cat, $estudio, $suc;

    echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
    echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

    echo "<script type=\"text/javascript\">";
    echo "$(document).ready(function() {";
    echo "$('#autocomplete').suggestionTool("
    . "$('#form10'),"
    . "'" . $Qry['froms'] . " " . $Qry['joins'] . "', "
    . "function() { "
    . "var orderValue = $('input[name=orden]:checked').val();"
    . "return orderValue.indexOf(' as ') >= 0 ? "
    . "orderValue.split(' as ')[0] : "
    . "orderValue; });";
    echo "});";
    echo "</script>";

    echo "<div>";

    echo "<form name='form99' id='form10' method='post' action='$_SERVER[PHP_SELF]'>";
    $num = (sizeof($aDat) / 3) + 1;
    echo "<table  align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";
    echo "<tr bgcolor='#daeed2'><td valign='center' height='70'>";

    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='right'>";

    if ($Cat == 'Clientes') {

        $placeholder = 'Ingresar # de cliente o Nombre';

    } elseif ($Cat == 'Medicos') {

        $placeholder = 'Ingresar Clave del medico o Nombre';

    } elseif ($Cat == 'PersonalM') {

        $placeholder = 'Ingresar Id o Nombre';

    } elseif ($Cat == 'Institucion') {

        $placeholder = 'Ingresar # de Institucion o Nombre';

    } elseif ($Cat == 'Estudios') {

        $placeholder = 'Ingresar Clave Estudios o Nombre';

    } elseif ($Cat == 'Zonas') {

        $placeholder = 'Ingresar # Zona o Descripcion';

    } elseif ($Cat == 'Ruta') {

        $placeholder = 'Ingresar # Ruta o Descripcion';

    } elseif ($Cat == 'Depto') {

        $placeholder = 'Ingresar # Depto o Nombre';
        
    } elseif ($Cat == 'Subdepto') {

        $placeholder = 'Ingresar # Subdepto o Nombre';

    } elseif ($Cat == 'Precio') {

        $placeholder = 'Ingresar Clave Estudio o Descripcion';

    } elseif ($Cat == 'Gasto') {

        $placeholder = 'Ingresar Clave Gasto o Concepto';

    } elseif ($Cat == 'Provedor') {

        $placeholder = 'Ingresar Clave Proveedor o Nombre';

    } elseif ($Cat == 'Visitas') {

        $placeholder = 'Ingresar Clave Visita o Nombre';

    } elseif ($Cat == 'Registro') {

        $placeholder = 'Ingresar Clave Registro o Clave de Medico';

    } elseif ($Cat == 'Clientesf') {

        $placeholder = 'Ingresar Clave clientes o Nombre';

    } elseif ($Cat == 'Fac') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Inv') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Trans') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Maq') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Comp') {

        $placeholder = 'Ingresar Clave o Proveedor';

    } elseif ($Cat == 'Pedido') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Salida') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Perlab') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Equi') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Tec') {

        $placeholder = 'Ingresar Clave o Nombre';

    } elseif ($Cat == 'Captura') {

        $placeholder = 'Ingresar Orden o Nombre';

    } elseif ($Cat == 'Agenda') {

        $placeholder = 'Ingresar Id o Descripcion';
    }

    echo "<tr class='texto_tablas'>";
    //echo "<td width='5px' height='30' background='images/GradTope8x30A.jpg'>&nbsp;</td>";
    echo "<td width='30%' background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;&nbsp;B&uacute;squeda r&aacute;pida:&nbsp; </a>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . " autofocus "
    . "size=\"30\" "
    . "class=\"texto_tablas\" "
    //   . "placeholder=Ingresar Numero de cliente o Nombre "
    . "placeholder= \" $placeholder \" "
    . "name=\"busca\" "
    . "id=\"autocomplete\"/>&nbsp;";
    echo "</td>";
    echo "<td background='images/gradGr1x30p.jpg' width='9px' align='left'>";
    echo "<input type='submit' class='letrap' name='Boton' value='Enviar' class='nombre_cliente'>";
    echo "<input type='hidden' class='letrap' name='pagina' value='1'>";
    echo "<input type='hidden' class='letrap' name='estudio' value='$estudio'>";
    echo "<input type='hidden' class='letrap' name='suc' value='$suc'>";
    echo "</td>";
    //echo "<a class='textosCualli' href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=' title='Actualiza la pantalla'><img src='libnvo/refresh.png' width='25' hegiht='25'></a>";
    echo "<td width='8px' height='30' background='images/gradGr1x30p.jpg'></td>";
    echo "</tr></table>";

    echo "</td></tr></table>";

    echo "</form>";

    echo "<p align='center'><font color='cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp $Msj &nbsp; &nbsp; </b></font></p>";

    echo "</div>";

    return true;
}

function CuadroInferior5($busca) {
    global $Dsp, $Msj, $pagina, $busca, $Id, $orden, $aIzq, $aDat, $aDer, $aCps, $Sort, $Qry, $Institucion;

    echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
    echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

    echo "<script type=\"text/javascript\">";
    echo "$(document).ready(function() {";
    echo "$('#autocomplete').suggestionTool("
    . "$('#form10'),"
    . "'" . $Qry['froms'] . " " . $Qry['joins'] . "', "
    . "function() { "
    . "var orderValue = $('input[name=orden]:checked').val();"
    . "return orderValue.indexOf(' as ') >= 0 ? "
    . "orderValue.split(' as ')[0] : "
    . "orderValue; });";
    echo "});";
    echo "</script>";

    echo "<div>";

    echo "<form name='form99' id='form10' method='post' action='$_SERVER[PHP_SELF]'>";
    $num = (sizeof($aDat) / 3) + 1;
    echo "<table  align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";
    echo "<tr bgcolor='#daeed2'><td valign='center' height='70'>";

    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='right'>";


    echo "<tr class='texto_tablas'>";
    //echo "<td width='5px' height='30' background='images/GradTope8x30A.jpg'>&nbsp;</td>";
    echo "<td width='30%' background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;&nbsp;B&uacute;squeda r&aacute;pida:&nbsp; </a>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . " autofocus "
    . "size=\"30\" "
    . "class=\"texto_tablas\" "
    . "placeholder=\"Ingresar Folio o Nombre\" "
    . "name=\"busca\" "
    . "id=\"autocomplete\"/>&nbsp;";
    echo "</td>";
    echo "<td background='images/gradGr1x30p.jpg' width='9px' align='left'>";
    echo "<input type='submit' class='letrap' name='Boton' value='Enviar' class='nombre_cliente'>";
    echo "<input type='hidden' class='letrap' name='Institucion' value='$Institucion'>";
    echo "</td>";
    //echo "<a class='textosCualli' href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=' title='Actualiza la pantalla'><img src='libnvo/refresh.png' width='25' hegiht='25'></a>";
    echo "<td width='8px' height='30' background='images/gradGr1x30pcu.jpg'>&nbsp;</td>";
    echo "</tr></table>";

    echo "</td></tr></table>";

    echo "</form>";

    echo "<p align='center'><font color='cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp $Msj &nbsp; &nbsp; </b></font></p>";

    echo "</div>";

    return true;
}

function CalculaPaginas() {
    global $res, $OrdenDef, $limitInf, $pagina, $tamPag, $orden, $numPags, $numeroRegistros;

    if (!isset($orden)) {
        $orden = $OrdenDef;
    }

    //if(!isset($_REQUEST[orden])){$orden=$OrdenDef;}else{$orden=$_REQUEST[orden];}

    $numeroRegistros = mysql_num_rows($res);

    $numPags = ceil($numeroRegistros / $tamPag); //Redondea hacia arriba 3.14 -> 4;

    if (!isset($pagina) or $pagina <= 0 or $pagina > $numPags) {   // Si no trae nada vete hasta e final
        $pagina = $numPags;
    }

    //calculo del limite inferior de registros para tomarlos de la tabla;
    $limitInf = 0;
    if ($numPags > 1) {
        if ($pagina == $numPags) {
            $limitInf = $numeroRegistros - $tamPag;
        } else {
            $limitInf = ($pagina - 1) * $tamPag;
        }
    }

    return $limitInf;
}

//
function Botones() {

    global $Retornar, $busca;

    echo "<p align='center'>";

    echo "<a class='letra' href='$Retornar' ><img src='lib/regresa.png'>  Regresar </a>";

    echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";

    echo "<input type='submit' style='background:#313654; color:#ffffff;font-weight:bold;' name='Boton' value='Actualizar'>";


//echo "<input type='submit' class='nombre_cliente' name='Boton' value='Cancelar'>";
//echo "<input type='submit' class='nombre_cliente' name='Boton' value='Aplicar'>";

    echo "<input type='hidden' name='pagina' value='$pagina' >";

    echo "<input type='hidden' name='busca' value='$busca' >";

    echo "</p>";

    if ($Msj <> "") {
        echo "<br><p align='left'>&nbsp; &nbsp; &nbsp; Mensaje [ <font color='#F36'> $Msj </font> ]</font></p>";
    }
}

function cTable($Tam, $Borde) {    //Abre tabla
    echo "<table width='$Tam' border='$Borde' cellpadding='1' cellspacing='2'>";
}

function cTableCie() {      //Cierra tabla
    echo "</table>";
}

function cInputDat($Titulo, $Tipo, $Lon, $Campo, $Alin, $Valor, $MaxLon, $Mayuscula, $Ed) {
    require ('config.php');

// cInput("Codigo del acabado:","text","20",'Codigo','right',$Cpo[codigo],'20',true,true);
// Titulo, tipo, longitud del campo, Variable en la k regresa, alineacion, Valor por default,maximo de letras,Si lo convierte en mayusculas,edita el campo

    if ($Mayuscula) {
        echo "$Titulo <input type='$Tipo' class='input_mayusculas' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>";
        //echo "$Titulo <input type=$Tipo style='background-color:$InputCol; border: solid 1px #fff;height: 19px; font-size:14px; color:#fff' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>";
    } else {
        //echo "$Titulo <input class='nombre_cliente' type=$Tipo style='background-color:$InputCol;color:#ffffff;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon'>";
        echo "$Titulo <input type='$Tipo' class='texto_tablas' name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon'>";
    }
}

function cInput($Titulo, $Tipo, $Lon, $Campo, $Alin, $Valor, $MaxLon, $Mayuscula, $Ed, $Nota, $Requerimientos) {
    require ('config.php');

//cInput("Codigo del acabado:","text","20",'Codigo','right',$Cpo[codigo],'20',true,true);
// Titulo, tipo, longitud del campo, Variable en la k regresa, alineacion, Valor por default,maximo de letras,Si lo convierte en mayusculas,edita el campo
    echo "<tr height='25' class='letrap'>";
    echo "<td align='right'  bgcolor='#f1f1f1' class='nombre_cliente'>$Titulo &nbsp; </td>";
    if (strlen($Tipo) > 1) {
        if ($Ed) {    // No se puede modificar el campo solo se edita
            echo "<td>&nbsp;$Valor &nbsp; $Nota</td></tr>";
        } else {
            if ($Mayuscula) {
                echo "<td >&nbsp;<input type='$Tipo' class='cinput'  name='$Campo' size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo') $Requerimientos> $Nota</td></tr>";
                //echo "<td  class='nombre_cliente'>&nbsp;<input type=$Tipo style='background-color:$InputCol;color:#ffffff;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>$Nota</td></tr>";
            } else {
                echo "<td class='nombre_cliente'>&nbsp;<input type='$Tipo' class='cinput'  name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon' $Requerimientos> $Nota</td></tr>";
            }
        }
    }
}

function Display($aCps, $aDat, $registro) {
    for ($i = 0; $i < sizeof($aCps); $i++) {
        if ($aDat[$i * 3 + 2] == 'N') {
            echo "<td align='right'><a class='letrap'> &nbsp; " . number_format($registro[$i], '2') . " &nbsp;</a></td>";
        } elseif ($aDat[$i * 3 + 2] == 'I') {
            echo "<td align='right'><a class='letrap'> &nbsp; " . number_format($registro[$i], '0') . " &nbsp;</a></td>";
        } elseif ($aDat[$i * 3 + 2] == 'V') {
            echo "<td align='right'><a class='letrap'> &nbsp; " . number_format($registro[$i], '3') . " &nbsp;</a></td>";
        } elseif ($aDat[$i * 3 + 2] == 'M') { //EN medio;
            echo "<td align='center'><a class='letrap'> &nbsp; " . ucwords(strtolower($registro[$i])) . " &nbsp;</a></td>";
        } else {
            $Align = 'left';
            echo "<td><a class='letrap'>&nbsp;" . ucwords(strtolower($registro[$i])) . "&nbsp;</a></td>";
        }
    }
}

function Stats($cVlr) {
    require ('config.php');

    echo "<tr><td align='right'>$Gfont <b>Status: </b>&nbsp; </td><td>";

    if ($cVlr <> 'CERRADA') {
        if (isset($cVlr)) {
            echo "<select name='Status'>";
            echo "<option value='Abierta'>Abierta</option>";
            echo "<option value='Cerrada'>Cerrada</option>";
            echo "<option selected value='$cVlr'>$cVlr</option>";
        } else {
            echo "<select name='Status' disabled>";
            echo "<option selected value='Abierta'>Abierta</option>";
        }
    } else {
        echo "<select name='Status' disabled>";
        echo "<option value='Abierta'>Abierta</option>";
        echo "<option value='Cerrada'>Cerrada</option>";
        echo "<option selected value='$cVlr'>$cVlr</option>";
    }
    echo "</selected>";
    echo "</td></tr>";
}

function logs($tabla, $id, $operacion) {
    $Fecha = date("Y-m-d H:i:s");
    $Usr = $_COOKIE[USERNAME];
    $sql = "INSERT INTO logs (referencia,fecha,id,concepto,usuario)
	   VALUES ('$tabla','$Fecha','$id','$operacion','$Usr')";
    //die($sql);
    $lUp = mysql_query($sql);
}

function IncrementaFolio($Campo) {
    global $Gcia;
    $FolA = mysql_query("SELECT $Campo FROM cia WHERE id='$Gcia'");
    $lUp = mysql_query("UPDATE cia SET $Campo = $Campo + 1 WHERE id='$Gcia'");
    $Fol = mysql_fetch_array($FolA);
    $FolioU = $Fol[$Campo];

    return $FolioU;
}

function PonTitulo($Titulo) {
    global $Gcia, $Gtitle, $Titulo, $Id, $Gfecha, $Nivel, $Usr;

    $Mnu = "menu/clap" . $Nivel . ".js";

//$Usr    = $_COOKIE['USERNAME'];
//echo "SELECT count(*) as mensajes FROM msj WHERE (para='$Usr' AND !bd";
    $MsA = mysql_query("SELECT count(*) as mensajes FROM msj WHERE para='$Usr' AND !bd");
    $Ms = mysql_fetch_array($MsA);

    $nMsj = $Ms[mensajes];

//$Mnu    = "menu/lago29.js";    
    echo '<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">';
    echo '<tr bgcolor="#daeed2">';
    echo "<td width='100%' align='center'><script type='text/JavaScript' src='$Mnu'></script><a class='letra'>.:::$Titulo.:::.</a></td>";
    echo '</tr>';
    echo '</table>';
}

function Eliminar() {
    global $busca;

    if ($busca <> 'NUEVO') {
        echo "<br>";
        echo "<div align='center'>Para eliminar &eacute;ste movimiento, favor de poner el password y dar click en el boton de <b>Eliminar</b></div>";
        echo "<div align='center'>Password: ";
        echo "<input type='password' style='background-color:#bacbc2;color:#ffffff;font-weight:bold;' name='Password' size='15' maxlength='15'>";
        echo " &nbsp; <input class='nombre_cliente' type='submit' name='Boton' value='Eliminar'></div>";
    }
}

function nuevoEncabezado($Titulo) {
    //Parametros de colores;

    $CiaA = mysql_query("SELECT cia,direccion,colonia,estacion,numeroext,colonia,ciudad FROM cia");
    $Cia = mysql_fetch_array($CiaA);


    echo "<table width='97%' align='center' border='0' cellpadding='0' cellspacing='0' class='texto_bold'>";
    echo "<tr><td height='10%' width='15%'><img src='libnvo/logo.png' border='0'  height='50' width='95'></td>";
    echo "<td height='10%' width='70%' align='center'>";
    echo "<div ><b>$Cia[cia]</b></div>";
    echo "<div >" . $Cia[direccion] . " No." . $Cia[numeroext] . " " . $Cia[colonia] . " " . $Cia[ciudad] . "</div>";
    echo "<div >$Titulo</div>";

    echo "</td>";
    echo "<td height='10%' width='15%'></td>";
    echo "</tr></table><br>";
}

function scanear_string($string) {

    $string = trim($string);

    $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', '�?', 'À', 'Â', 'Ä'),
            array('&aacute;', 'a', 'a', 'a', 'a', '&Aacute;', 'A', 'A', 'A'),
            $string
    );

    $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('&eacute;', 'e', 'e', 'e', '&Eacute;', 'E', 'E', 'E'),
            $string
    );

    $string = str_replace(
            array('í', 'ì', 'ï', 'î', '�?', 'Ì', '�?', 'Î'),
            array('&iacute;', 'i', 'i', 'i', '&Iacute;', 'I', 'I', 'I'),
            $string
    );

    $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('&oacute;', 'o', 'o', 'o', '&Oacute;', 'O', 'O', 'O'),
            $string
    );

    $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('&uacute;', 'u', 'u', 'u', '&Uacute;', 'U', 'U', 'U'),
            $string
    );

    $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('&ntilde;', '&Ntilde;', 'c', 'C',),
            $string
    );


    return $string;
}

function Sbmenu() {
    global $sbmn;

    if ($sbmn == 'Basicos') {
        $colorfonb = "bgcolor='#6580A2'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#ffffff'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Equipos') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#6580A2'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#ffffff'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Muestras') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#6580A2'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#ffffff'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Contenido') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#6580A2'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#ffffff'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Descripcion') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#6580A2'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#ffffff'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Admin') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#6580A2'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#ffffff'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Atencion') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#6580A2'";
        $colorfonest = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#ffffff'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
    } elseif ($sbmn == 'Elementos') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#6580A2'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcest = "<font color='#000'>";
        $Selcele = "<font color='#ffffff'>";
    } elseif ($sbmn == 'Estadistica') {
        $colorfonb = "bgcolor='#A7B0BC'";
        $colorfone = "bgcolor='#A7B0BC'";
        $colorfonm = "bgcolor='#A7B0BC'";
        $colorfonc = "bgcolor='#A7B0BC'";
        $colorfond = "bgcolor='#A7B0BC'";
        $colorfona = "bgcolor='#A7B0BC'";
        $colorfonat = "bgcolor='#A7B0BC'";
        $colorfonele = "bgcolor='#A7B0BC'";
        $colorfonest = "bgcolor='#6580A2'";

        $Selcb = "<font color='#000'>";
        $Selce = "<font color='#000'>";
        $Selcm = "<font color='#000'>";
        $Selcc = "<font color='#000'>";
        $Selcd = "<font color='#000'>";
        $Selca = "<font color='#000'>";
        $Selcat = "<font color='#000'>";
        $Selcele = "<font color='#000'>";
        $Selcest = "<font color='#ffffff'>";
    }
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" <?= $colorfonb ?> >
                <a href="estudiose.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selcb ?>Datos Basicos</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfone ?> >
                <a href="estudioseeu.php?busca=<?= $_REQUEST[busca] ?>&ele=1" class="sbmnu"><?= $Selce ?>Equipos por unidad</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfonm ?> >
                <a href="estudiosemst.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selcm ?>Muestras</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfonc ?> >
                <a href="estudioscnt.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selcc ?>Contenido</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfond ?> >
                <a href="estudiosdg.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selcd ?>Descripcion general</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfona ?> >
                <a href="estudiosadmin.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selca ?>Administración</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfonat ?> >
                <a href="estudiosatn.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selcat ?>Atn Cli/Promo</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfonele ?> >
                <a href="estudioselem.php?busca=<?= $_REQUEST[busca] ?>&ele=1" class="sbmnu"><?= $Selcele ?>Elem. de Cap/Imp</font></a>
            </td>
        </tr>
        <tr>
            <td class="ssbm" <?= $colorfonest ?> >
                <a href="estudiosestad.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu"><?= $Selcest ?>Estadistica</font></a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="javascript:window.close()" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}

function SbmenuMed() {
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='height: 100px;border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" >
                <a href="medicose.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Info. Personal</a>
            </td>
        </tr>
        <tr>
            <td class="ssbm">
                <a href="medicoso.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Otros</a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="medicos.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}

function SbmenuMed1() {
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='height: 100px;border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" >
                <a href="medicose1.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Info. Personal</a>
            </td>
        </tr>
        <tr>
            <td class="ssbm">
                <a href="medicoso1.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Otros</a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="javascript:window.close()" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}

function SbmenuList() {
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='height: 45;border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" >
                <a href="listae.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Info. General</a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="lista.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}

function SbmenuCli() {
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='height: 90px;border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" >
                <a href="clientese.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Info. Personal</a>
            </td>
        </tr>
        <tr>
            <td class="ssbm">
                <a href="clienteso.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Otros</a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="clientes.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}

function SbmenuInst() {
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='height: 90px;border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" >
                <a href="institue.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Info. de Instituto</a>
            </td>
        </tr>
        <tr>
            <td class="ssbm">
                <a href="instituo.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Otros</a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="institu.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}

function qry($usr, $concepto) {
    $fecha = date("Y-m-d HH:mm:ss");
    $Sql = "INSERT INTO logs (usr,concepto,fecha) VALUES ('$usr','$concepto','$fecha');";
    if (!mysql_query($Sql)) {
        echo "ERROR $Sql";
    }
}

function RestarHoras($horaini, $horafin) {
    $horai = substr($horaini, 0, 2);
    $mini = substr($horaini, 3, 2);
    $segi = substr($horaini, 6, 2);

    $horaf = substr($horafin, 0, 2);
    $minf = substr($horafin, 3, 2);
    $segf = substr($horafin, 6, 2);

    $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
    $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

    $dif = $fin - $ini;

    $difh = floor($dif / 3600);
    $difm = floor(($dif - ($difh * 3600)) / 60);
    $difs = $dif - ($difm * 60) - ($difh * 3600);
    return date("H:i:s", mktime($difh, $difm, $difs));
}

function AgregaBitacoraEventos($Gusr, $Origen, $Tabla, $Fecha, $busca, $Msj, $Return) {
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','$Origen','$Tabla', "
            . "'$Fecha','$busca')";
    if (mysql_query($sql)) {
        header("Location: $Return?busca=$busca&Msj=$Msj");
    } else {
        $Msj = "Error datos ingresados " . mysql_error();
        header("Location: $Return?busca=$busca&Msj=$Msj&Error=SI");
    }
}

function AgregaBitacoraEventos2($Gusr, $Origen, $Tabla, $Fecha, $busca, $Msj, $Return) {
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','$Origen','$Tabla', "
            . "'$Fecha','$busca')";
    mysql_query($sql);
}

function AgregaAgendaEventos($Gusr, $Origen, $Tabla, $Fecha, $busca, $Msj, $Return) {
    $sql = "INSERT INTO logagenda (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','$Origen','$Tabla', "
            . "'$Fecha','$busca')";
    if (mysql_query($sql)) {
        header("Location: $Return?busca=$busca&Msj=$Msj");
    } else {
        $Msj = "Error datos ingresados " . mysql_error();
        header("Location: $Return?busca=$busca&Msj=$Msj&Error=SI");
    }
}

function AgregaAgendaEventos2($Gusr, $Origen, $Tabla, $Fecha, $busca, $Msj, $Return) {
    $sql = "INSERT INTO logagenda (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','$Origen','$Tabla', "
            . "'$Fecha','$busca')";
    mysql_query($sql);
}

function AdjuntaMensajeWhats($noTelefono, $Texto, $Tamaño = 'fa-2x') {
    ?>
    <a target="_blanck" href="https://api.whatsapp.com/send?phone=<?= $noTelefono ?>&text=<?= $Texto ?>"><i style='color:green;' class="fa fa-whatsapp <?= $Tamaño ?>" aria-hidden="true"></i></a>
    <?php
}

function TablaDeLogs($Origen, $busca) {
    ?>
    <table width='99%' align='center' border='0' cellpadding='1' 
cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; 
border: 1px solid #999;'>
        <tr style="background-color: #2c8e3c"><td 
class='letratitulo'align="center">.:: Modificaciones ::.</td></tr>
        <tr>
            <td>
                <table align="center" width="95%" style="border:#000 1px 
solid;border-color: #999; border-radius: .5em;" border="0">
                    <tr class="letrap">
                        <td><b>&nbsp; Fecha</b></td>
                        <td><b>&nbsp; Usuario</b></td>
                        <td><b>&nbsp; Accion</b></td>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM log WHERE accion like 
('%$Origen%') AND cliente='$busca' ORDER BY id DESC LIMIT 10;";
                    $PgsA = mysql_query($sql);
                    while ($rg = mysql_fetch_array($PgsA)) {
                        (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo ='DDE8FF';
                        ?>
			<tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                            <td align="center">&nbsp;<?= $rg[fecha] 
?></td>
                            <td><?= $rg[usr] ?></td>
                            <td><?= $rg[accion] ?></td>
                        </tr>
                        <?php
                        $nRng++;
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <?php
}

function footer() {
    ?>
    <footer>
        <table class="footerlcd">
            <tr>
                <td>
                    <a target="_blank" 
href="https://www.facebook.com/hospitalfuturaoficial"><i class="fa 
fa-facebook-square fa-lg" style="color:blue;" 
aria-hidden="true"></i></a>
                    LCD-NET Laboratorio Clínico Durán Futura | C. Fray 
Pedro de Gante 320, Centro, 56100 Texcoco, Méx. | 
                    Tel. (595) 9542917 | 
http://www.hospitalfutura.com.mx/  
                </td>
                <td>
                    Versión 2.0.0
                </td>
            </tr>
        </table>
    </footer>
    <?php
}


function SbmenuEmpledos() {
    ?>
    <table border='0' class='Orilla' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' 
style='height: 90px;border-collapse: collapse; border: 1px solid #fff;position:relative;'>
        <tr>
            <td class="ssbm" >
                <a href="empleadose.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Info. Personal</a>
            </td>
        </tr>
        <tr>
            <td class="ssbm">
                <a href="empleadosimage.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Imagenes</a>
            </td>
        </tr>
	<tr>
            <td class="ssbm">
                <a href="empleadoshorarios.php?busca=<?= $_REQUEST[busca] ?>" class="sbmnu">Horarios</a>
            </td>
        </tr>
    </table>
    <br></br>
    <table>
        <tr>
            <td>
                <a href="empleados.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table>
    <?php
}
