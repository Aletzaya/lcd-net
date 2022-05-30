<?php
/**
 * Libreria principal del sistema omicrom.
 * @return type
 */

function conectarse(){

    if(!($link=mysql_connect("127.0.0.1","Admon","det15a"))){
	setcookie ("USERNAME", "");
	setcookie ("PASSWORD", "");	
	
        header("Location: index.php?Msj=true");
        
        //echo "Error conectando a la base de datos.";
        //exit();
        
    }
    if(!mysql_select_db("lcd",$link)){

	setcookie ("USERNAME", "");
	setcookie ("PASSWORD", "");	
	
        header("Location: index.php?Msj=true");
        
	// Destroy Sessions
        
        //echo "Error seleccionando la base de datos.";
        //exit();
        
    }

    //$CiaA      = mysql_query("SELECT zonahoraria FROM cia");
    //$Cia       = mysql_fetch_array($CiaA);

    //date_default_timezone_set($Cia[zonahoraria]);  
    
    mysql_set_charset("UTF8",$link);
    
    //mysql_query("SET NAMES 'utf8'");
    
    //mysql_query("SET CHARACTER SET utf8 ");
        
    return $link;               

}


function EjecutaSql($Sql,$Archivo){
     if (!mysql_query($Sql)) {
         echo "<div align='center'>$Sql</div>";           
         die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=images/IcomExportar17x15p.jpg border=0></a> para regresar</div>');
    }
    
    return true;
}

function encabezados(){
echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
echo '<tr>';
echo '<td width="5" height="30" td style="background-image:url(images/CejaAzul1.jpg)" ></td>';
echo '<td style="background-image: url(images/CejaAzulGrad.jpg)">';
echo '<ul id="left-nested">';
echo '<li class="menu"><a href="menu.php?Mnu=1" title="" class="menu">ATENCION A CLIENTES |</a></li>';
echo '<li class="menu"><a href="menu.php?Mnu=2" title="" class="menu">AREA OPERATIVA |</a></li>';
echo '<li class="menu"><a href="menu.php?Mnu=3" title="" class="menu">AREA ADMINISTRATIVA |</a></li>';
echo '<li class="menu"><a href="menu.php?Mnu=4" title="">SISTEMAS</a></li>';
echo '</ul>';
echo '</td>';
echo '<td style="background-image: url(images/CejaAzulGrad.jpg)">';
echo '<ul id="right-nested">';
echo '<li><a href="#" title=""><i class="usuario"></i>Usuario&nbsp; </a></li>';
echo '<li><a href="#" title=""><i class="correo"></i>Correo&nbsp; </a></li>';
echo '<li><a href="#" title=""><i class="nuevo"></i>Nuevo&nbsp; </a></li>';
echo '<li><a href="#" title=""><i class="salir"></i>Salir&nbsp; </a></li>';
echo '</ul>';
echo '</td>';
echo '<td width="5" height="30" style="background-image:url(images/CejaAzul2.jpg)"></td>';
echo '</tr>';
echo '</table>';    
    
}

function menu($Mnu){
    echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="5" height="90" td="td" style="background-image:url(images/BarraVde1.jpg)"></td>';
    echo '<td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><a href="menu.php?Mnu=0"><img src="images/logoDuran.png" width="230" height="90" <a/></td>';
    echo '<td width="40%" valign="bottom" style="background-image:url(images/BarraVdeGrad.jpg)">';

    if($Mnu==1){        
        echo '<table width="360" border="0" cellpadding="0" cellspacing="0">';
        echo '<tr>';
        echo '<td width="80" id="recepcion" height="86"><a href="#"  class="recepcion" ></a></td>';
        echo '<td width="80" id="catalogos"><a href="#" title="" class="catalogos" ></a></td>';
        echo '<td width="80" id="moviles"><a href="#" title="" class="moviles" ></a></td>';
        echo '<td width="80" id="reportes"><a href="#" title="" class="reportes" ></a></td>';
        echo '<td width="80" id="ingresos"><a href="#" title="" class="ingresos" ></a></td>';
        echo '<td width="80" id="facturacion"><a href="#" title="" class="facturacion" ></a></td>';
        echo '</tr>';
        echo '</table>';
        
    }elseif($Mnu==2){
        echo '<table width="360" border="0" cellpadding="0" cellspacing="0">';
        echo '<tr>';
            echo '<td width="80" height="86" class="clinico" id="clinico"><a href="#"  class="clinico"></a></td>';
            echo '<td width="80" class="rayosx" id="rayosx"><a href="#" title="" class="rayosx"></a></td>';
            echo '<td width="80" class="especiales" id="especiales"><a href="#" title="" class="especiales"></a></td>';
            echo '<td width="80" id="catalogos"><a href="#" title="" class="catalogos"></a></td>';
            echo '<td width="80" id="reportes"><a href="#" title="" class="reportes"></a></td></tr>';
        echo '</tr>';
        echo '</table>';
        
        
    }
    
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
    
    if($Mnu==1){

            echo '<table  border="0" bordercolor="#daeed2" width="100%"  cellpadding="0" cellspacing="0">';
            echo '<tr height="34" align="center" width="100%">';
            echo    '<td bgcolor="#daeed2"height="0" valign="top" bgcolor="#FFFFFF">';
            echo        '<div id="two-level-recepcion">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="ordenesnvas.php?Venta=1" title="">Captura OTs</a></li>';
            echo                    '<li><a href="ordenescon.php?busca=ini" title="">Consulta OTs</a></li>';
            echo                    '<li><a href="#" title="">Atencion OTs</a></li>';
            echo                    '<li><a href="#" title="">Agenda</a></li>';                       
            echo            '</ul>';
            echo        '</div>'; 
            echo        '<div id="two-level-catalogos">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="clientes.php?busca=ini" title="">Clientes</a></li>';
            echo                    '<li><a href="medicos.php?busca=ini" title="">Medicos</a></li>';
            echo                    '<li><a href="institu.php?busca=ini" title="">Instituciones</a></li>';
            echo                    '<li><a href="estudios.php?busca=ini" title="">Estudios</a></li>';
            echo            '</ul>';
            echo        '</div>';          
            echo        '<div id="two-level-moviles">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">Traslados</a> </li>';
            echo                    '<li><a href="#" title="">Resultados</a> </li>';
            echo                    '<li><a href="#" title="">Unidades</a></li>';
            echo                    '<li><a href="#" title="">Rut d maquinas</a></li>';
            echo                    '<li><a href="#" title="">otras</a></li>';  
            echo            '</ul>';
            echo        '</div>';
            echo        '<div id="two-level-reportes">';
            echo            '<ul id="horizontal-main-menu">';
            echo                     '<li><a href="#" title="">OTs</a></li>';
            echo                     '<li><a href="#" title="">Demanda OT</a> </li>';
            echo            '</ul>';
            echo         '</div>' ;
            echo         '<div id="two-level-ingresos">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">Ingresos</a> </li>';
            echo                    '<li><a href="#" title="">Corte de caja</a> </li>';
            echo            '</ul>';
            echo          '</div>';
            echo         '<div id="two-level-facturacion">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">-----</a> </li>';
            echo                    '<li><a href="#" title="">-----</a> </li>';
            echo            '</ul>';
            echo          '</div>';
            echo      '</td></tr>';
            echo    '</table>';
    }elseif ($Mnu==2) {
            echo '<table  border="0" bordercolor="#daeed2" width="100%"  cellpadding="0" cellspacing="0">';
            echo '<tr height="34" align="center" width="100%">';
            echo    '<td bgcolor="#daeed2"height="0" valign="top" bgcolor="#FFFFFF">';
            echo        '<div id="two-level-clinico">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">Captura OTs</a></li>';
            echo                    '<li><a href="#" title="">Captura R.</a></li>';                       
            echo            '</ul>';
            echo        '</div>'; 
            echo        '<div id="two-level-rayosx">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">Consulta OT s</a></li>';
            echo                    '<li><a href="#" title="">Captura R.</a> </li>';
            echo                    '<li><a href="#" title="">Imprimir R.</a> </li>';
            echo            '</ul>';
            echo        '</div>';          
            echo        '<div id="two-level-especiales">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">Consulta OT s</a> </li>';
            echo                    '<li><a href="#" title="">Captura R.</a> </li>';
            echo                    '<li><a href="#" title="">Imp.R.</a></li>';
            echo            '</ul>';
            echo        '</div>';
            echo        '<div id="two-level-catalogos">';
            echo            '<ul id="horizontal-main-menu">';
            echo                     '<li><a href="#" title="">Estudios</a></li>';
            echo                     '<li><a href="#" title="">Elementos</a> </li>';
            echo            '</ul>';
            echo         '</div>' ;
            echo         '<div id="two-level-reportes">';
            echo            '<ul id="horizontal-main-menu">';
            echo                    '<li><a href="#" title="">OT s</a> </li>';
            echo                    '<li><a href="#" title="">Demanda OT</a> </li>';
            echo                    '<li><a href="#" title="">Entrega R.</a> </li>';
            echo            '</ul>';
            echo          '</div>';
            echo      '</td></tr>';
            echo    '</table>';
        
    }
}


function menu2(){
    echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<td width="5" height="90" td="td" style="background-image:url(images/BarraVde1.jpg)"></td>';
    echo '<td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="images/logoDuran.png" width="230" height="90" alt="Logotipo Laboratorio Clínico Durán" /></td>';
    echo '<td width="40%" valign="bottom" style="background-image:url(images/BarraVdeGrad.jpg)">';
    
        echo '<table width="360" border="0" cellpadding="0" cellspacing="0">';
        echo '<tr>';
            echo '<td width="80" height="86" class="clinico" id="clinico"><a href="#"  class="clinico"></a></td>';
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

function menu3(){
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
echo    '<td bgcolor="#daeed2"height="0" valign="top" bgcolor="#FFFFFF">';
echo        '<div id="two-level-administracion">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">Captura OTs</a></li>';
echo                    '<li><a href="#" title="">Clientes F.</a></li>';
echo                    '<li><a href="#" title="">Facturación</a></li>';
echo                    '<li><a href="#" title="">Ingresos</a></li>';                       
echo                    '<li><a href="#" title="">Re-Imp. R.</a></li>';   
echo                    '<li><a href="#" title="">Corte C.</a></li>';                       
echo                    '<li><a href="#" title="">Trazabilidad</a></li>';
echo            '</ul>';
echo        '</div>'; 
echo        '<div id="two-level-recursosh">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">Personal</a></li>';
echo                    '<li><a href="#" title="">Asistencia</a> </li>';
echo            '</ul>';
echo        '</div>';          
echo        '<div id="two-level-promocion">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">Consulta OT s</a> </li>';
echo                    '<li><a href="#" title="">Comisiones</a> </li>';
echo                    '<li><a href="#" title="">Recibos</a></li>';
echo                    '<li><a href="#" title="">Pagos</a></li>';
echo                    '<li><a href="#" title="">Visitas</a></li>';  
echo                    '<li><a href="#" title="">Rutas médicas</a></li>';
echo            '</ul>';
echo        '</div>';
echo        '<div id="two-level-catalogos">';
echo            '<ul id="horizontal-main-menu">';
echo                     '<li><a href="#" title="">Estudios</a></li>';
echo                     '<li><a href="#" title="">Precios</a> </li>';
echo                     '<li><a href="#" title="">Médicos</a> </li>';
echo                     '<li><a href="#" title="">Instituciones</a> </li>';
echo                     '<li><a href="#" title="">Zonas</a> </li>';
echo                     '<li><a href="#" title="">Personal</a> </li>';
echo            '</ul>';
echo         '</div>' ;
echo      '</td></tr>';
echo    '</table>';
}

function menu4(){
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
echo    '<td bgcolor="#daeed2"height="0" valign="top" bgcolor="#FFFFFF">';
echo        '<div id="two-level-recepcion999">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">...</a></li>';                      
echo            '</ul>';
echo        '</div>'; 
echo        '<div id="two-level-catalogos">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">...</a></li>';
echo            '</ul>';
echo        '</div>';          
echo        '<div id="two-level-moviles">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">...</a> </li>';
echo            '</ul>';
echo        '</div>';
echo        '<div id="two-level-reportes">';
echo            '<ul id="horizontal-main-menu">';
echo                     '<li><a href="#" title="">...</a></li>';
echo            '</ul>';
echo         '</div>' ;
echo         '<div id="two-level-ingresos">';
echo            '<ul id="horizontal-main-menu">';
echo                    '<li><a href="#" title="">...</a> </li>';
echo            '</ul>';
echo          '</div>';
echo      '</td></tr>';
echo    '</table>';
}


function PieDePagina(){
    
    echo "<table width='1011' border='0' align='center' cellpadding='0' cellspacing='0' style='border-collapse: collapse; border: 1px solid #066;'>";
    echo "<tr background='libnvo/fondo_verde_down.png' height='40'>";
    echo "<td width='960' align='center' valign='center' class='Footer'>";
    echo " DETISA ::: Normal de Maestros No.10 Col. Tulantongo Texcoco Estado de Mexico Cp. 56217 Tel. 01(595) 9250401 / 1117518 www.detisa.com.mx";
    echo "</td></tr></table>";
    
}


function cZeros($Vlr,$nLen){   // Function p/ rellenar de zeros
  for($i = strlen($Vlr); $i < $nLen; $i=$i+1){
      $Vlr = "0".$Vlr;
   }
  return $Vlr;
}

function PonEncabezado(){

global $OrdenDef, $aIzq, $aDat, $aDer, $aCps, $Sort;   #P k reconoscan el valor k traen

    echo "<table align='center' width='100%' border='0' cellpadding='1' cellspacing='0' style='border-collapse: collapse; border: 1px solid #cccccc;'>";
    echo "<tr style='background-image: url(images/CejaAzulGrad.jpg)'>";

    for ($i = 0; $i < sizeof($aIzq); $i = $i + 3) {
        echo "<td align='center'> $aIzq[$i]</td>";
    } 
    $x = 0;
    for ($i = 0; $i < sizeof($aDat); $i = $i + 3) {
        $Pso = $aCps[$x];        
        if ($OrdenDef == $aCps[$x]) {   //Es el campo por el cual esta en este momento ordenado;
            if ($Sort == 'Asc') {
                $Srt = 'Desc';
                $iImg = 'asc.png';
            } else {
                $Srt = 'Asc';
                $iImg = 'des.png';
            }
            echo "<td>&nbsp;<a class='letra' href='" . $_SERVER["PHP_SELF"] . "?orden=$Pso&Sort=$Srt'>$aDat[$i]</a>&nbsp;<img src='lib/$iImg' border='0'></td>";
        }else {
            echo "<td>&nbsp;<a class='letra' href='" . $_SERVER["PHP_SELF"] . "?orden=$Pso&Sort=Asc'>$aDat[$i] </a></td>";
        }
        
        $x++;
    }

    for ($i = 0; $i < sizeof($aDer); $i = $i + 3) {
        echo "<td> $aDer[$i] </td>";
    }

    echo "</tr>";

    return true;
}



function PonPaginacion($Bd,$fitro,$campo,$nLink){
    global $inicio, $Msj, $cSql, $pagina, $tamPag, $orden, $numPags, $final, $busca, $nRng, $Sort, $Id, $numeroRegistros;


    if (sizeof($Bd) > 1) {
        $Comodin = $Bd[1];
        $Bd = $Bd[0];
    }

    //Mensajes y No de registros;
    ?>

    <table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class='letrap'>
    <tr><td><font color='#FF6633'>&nbsp; <?=$Msj?> </td>
        <td width='250' align='right'  > Registros:  <?=number_format($numeroRegistros, "0")?> &nbsp;</td></tr>
    </table>


    <?php
    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $cSql = str_replace("'", "!", $cSql);                      //Remplazo la comita p'k mande todo el string
    $Sql = str_replace("+", "~", $cSql);                      //Remplazo la comita p'k mande todo el string

    echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr height='20'><td valign='top' width='6%' >&nbsp;&nbsp;";

    if ($Bd) {
        echo "<a class='letraex' href='$cLink?busca=NUEVO'>Agregar <img src='images/agregar.png' border='0'></a> &nbsp; </td>";        
    }

    echo "<td width='6%'><a class='letraex' href='bajarep.php?cSql=$Sql'>Exportar <img src='images/exportar.jpg' border='0'></a> &nbsp; </td>";

    if($fitro){
        echo "<a class='letra' href=javascript:winuni('filcampos.php?Id=$Id')>Filtrar</a>&nbsp;&nbsp;";
    }
    if($campo){
        echo "<a class='letra' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a>&nbsp;&nbsp;";
    }
    foreach ($nLink as $key => $value) {
         echo "<a class='seleccionar' href=$value><span id=blink>$key</span></a>&nbsp;&nbsp;";
    }
    
    echo "<td width='12%'><a class='letraex' href='$_SERVER[PHP_SELF]?busca=ini' title='Actualiza la pantalla'>Restablecer pantalla <img src='images/rest.png' border='0'></a>&nbsp;&nbsp;";

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

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=1'><img src='images/sultimo.png' border='0'></a>&nbsp;";

            if ($pagina - 11 >= 1) {

                if ($pagina >= $numPags - 3) {
                    $pg = $ini - 9;
                } else {
                    $pg = $pagina - 11;
                }
                echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><img src='images/santerior.png' border='0'></a>&nbsp; ";
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
                echo "<a class='letraex' href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$i'> $pag </a>&nbsp;";
            }
        }

        if ($pagina + 11 <= $numPags) {

            $pg = $pagina + 11;
            echo " <a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$pg'><img src='images/sig.png' border='0'></a>&nbsp; ";
        }

        if ($pagina < ($numPags - 5)) {

            echo "<a href='" . $_SERVER["PHP_SELF"] . "?busca=$busca&pagina=$numPags'><img src='images/sigsig.png' border='0'></a>&nbsp;";
        }
    }

    echo "&nbsp;</td></tr></table>";
    
    
    return true;

}


function CuadroInferior($busca){
global $Dsp, $Msj, $pagina, $busca, $Id,$orden, $aIzq, $aDat, $aDer, $aCps, $Sort, $Qry;

echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

echo "<script type=\"text/javascript\">";
echo "$(document).ready(function() {";
echo        "$('#autocomplete').suggestionTool("
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
        echo    "<td width='25%' background='images/gradGr1x30p.jpg'><a class='letrap'>&nbsp;&nbsp;B&uacute;squeda r&aacute;pida:&nbsp; </a>";
        echo            "&nbsp;<input class='letrap'"
                            . "type=\"text\" "
                            . "size=\"20\" "
                            . "class=\"texto_tablas\" "
                            . "placeholder=\"Ingresar palabra(s)\" "
                            . "name=\"busca\" "
                            . "id=\"autocomplete\"/>&nbsp; <img src=images/lupa.png>";
        echo        "</td>";
        echo    "<td background='images/gradGr1x30p.jpg'><a class='letrap'>Seleccione el orden de b&uacute;squeda</a></td>"; 
        
        $x = 0;
        for ($i = 0; $i < sizeof($aDat); $i = $i + 3) {
            $Pso = $aCps[$x];
            if (strpos($Pso, $orden)===0) {   //Es el campo por el cual esta en este momento ordenado;

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


function CalculaPaginas(){
global $res,$OrdenDef,$limitInf,$pagina,$tamPag,$orden,$numPags,$numeroRegistros;

       if(!isset($orden)){$orden=$OrdenDef;}

       //if(!isset($_REQUEST[orden])){$orden=$OrdenDef;}else{$orden=$_REQUEST[orden];}

       $numeroRegistros = mysql_num_rows($res);

       $numPags         = ceil($numeroRegistros/$tamPag);	//Redondea hacia arriba 3.14 -> 4;

       if(!isset($pagina) or $pagina <= 0 or $pagina >$numPags){   // Si no trae nada vete hasta e final
          $pagina=$numPags;
       }

       //calculo del limite inferior de registros para tomarlos de la tabla;
       $limitInf=0;
       if($numPags>1){
          if($pagina==$numPags){
          	 $limitInf = $numeroRegistros-$tamPag;
          }else{
             $limitInf = ($pagina-1)*$tamPag;
          }
		 }

return $limitInf;

}

//
function Botones(){

global $Retornar,$busca;

echo "<p align='center'>";

echo "<a class='letra' href='$Retornar' ><img src=lib/regresa.png>  Regresar</a>";

echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<input type='submit' style='background:#313654; color:#ffffff;font-weight:bold;' name='Boton' value='Actualizar'>";


//echo "<input type='submit' class='nombre_cliente' name='Boton' value='Cancelar'>";


//echo "<input type='submit' class='nombre_cliente' name='Boton' value='Aplicar'>";

echo "<input type='hidden' name='pagina' value=$pagina >";

echo "<input type='hidden' name='busca' value=$busca >";

echo "</p>";

if($Msj <> ""){
  echo "<br><p align='left'>&nbsp; &nbsp; &nbsp; Mensaje [ <font color='#F36'> $Msj </font> ]</font></p>";
}

}



function cTable($Tam,$Borde){    //Abre tabla
   echo "<table width='$Tam' border='$Borde' cellpadding='1' cellspacing='2'>";
}

function cTableCie(){						//Cierra tabla
	echo "</table>";
}

function cInputDat($Titulo,$Tipo,$Lon,$Campo,$Alin,$Valor,$MaxLon,$Mayuscula,$Ed){
require ('config.php');

// cInput("Codigo del acabado:","text","20",'Codigo','right',$Cpo[codigo],'20',true,true);
// Titulo, tipo, longitud del campo, Variable en la k regresa, alineacion, Valor por default,maximo de letras,Si lo convierte en mayusculas,edita el campo

if($Mayuscula){
   echo "$Titulo <input type='$Tipo' class='input_mayusculas' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>";
   //echo "$Titulo <input type=$Tipo style='background-color:$InputCol; border: solid 1px #fff;height: 19px; font-size:14px; color:#fff' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>";
}else{
   //echo "$Titulo <input class='nombre_cliente' type=$Tipo style='background-color:$InputCol;color:#ffffff;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon'>";
   echo "$Titulo <input type='$Tipo' class='texto_tablas' name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon'>";
}

}


function cInput($Titulo,$Tipo,$Lon,$Campo,$Alin,$Valor,$MaxLon,$Mayuscula,$Ed,$Nota,$Requerimientos){
require ('config.php');

//cInput("Codigo del acabado:","text","20",'Codigo','right',$Cpo[codigo],'20',true,true);
// Titulo, tipo, longitud del campo, Variable en la k regresa, alineacion, Valor por default,maximo de letras,Si lo convierte en mayusculas,edita el campo
  echo "<tr height='25' class='letrap'>";
  echo "<td align='right'  bgcolor='#f1f1f1' class='nombre_cliente'>$Titulo &nbsp; </td>";
  if(strlen($Tipo)>1){
      if($Ed){				// No se puede modificar el campo solo se edita
        echo "<td>&nbsp;$Valor &nbsp; $Nota</td></tr>";
      }else{
          if($Mayuscula){
              echo "<td >&nbsp;<input type='$Tipo' class='cinput'  name='$Campo' size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo') $Requerimientos> $Nota</td></tr>";
              //echo "<td  class='nombre_cliente'>&nbsp;<input type=$Tipo style='background-color:$InputCol;color:#ffffff;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>$Nota</td></tr>";
          }else{
              echo "<td class='nombre_cliente'>&nbsp;<input type='$Tipo' class='cinput'  name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon' $Requerimientos> $Nota</td></tr>";
          }
     }
   }
}

function Display($aCps,$aDat,$registro){
    for ($i = 0; $i < sizeof($aCps); $i++) {
	     if($aDat[$i*3+2]=='N'){
           echo "<td align='right'><a class='letrap'> &nbsp; ".number_format($registro[$i],'2')." &nbsp;</a></td>";
	     }elseif($aDat[$i*3+2]=='I'){
           echo "<td align='right'><a class='letrap'> &nbsp; ".number_format($registro[$i],'0')." &nbsp;</a></td>";
	     }elseif($aDat[$i*3+2]=='V'){
           echo "<td align='right'><a class='letrap'> &nbsp; ".number_format($registro[$i],'3')." &nbsp;</a></td>";
	     }elseif($aDat[$i*3+2]=='M'){	//EN medio;
           echo "<td align='center'><a class='letrap'> &nbsp; ".ucwords(strtolower($registro[$i]))." &nbsp;</a></td>";
	     }else{$Align='left';
           echo "<td><a class='letrap'>&nbsp;".ucwords(strtolower($registro[$i]))."&nbsp;</a></td>";
        }
    }
}

function Stats($cVlr){
require ('config.php');

echo "<tr><td align='right'>$Gfont <b>Status: </b>&nbsp; </td><td>";

if($cVlr<>'CERRADA'){
   if(isset($cVlr)){
      echo "<select name='Status'>";
      echo "<option value='Abierta'>Abierta</option>";
      echo "<option value='Cerrada'>Cerrada</option>";
      echo "<option selected value='$cVlr'>$cVlr</option>";
   }else{
     echo "<select name='Status' disabled>";
     echo "<option selected value='Abierta'>Abierta</option>";
   }
}else{
   echo "<select name='Status' disabled>";
   echo "<option value='Abierta'>Abierta</option>";
   echo "<option value='Cerrada'>Cerrada</option>";
   echo "<option selected value='$cVlr'>$cVlr</option>";
}
echo "</selected>";
echo "</td></tr>";
}

function logs($tabla,$id,$operacion){
    $Fecha  = date("Y-m-d H:i:s");
    $Usr    = $_COOKIE[USERNAME];
    $sql = "INSERT INTO logs (referencia,fecha,id,concepto,usuario)
	   VALUES ('$tabla','$Fecha','$id','$operacion','$Usr')";
    //die($sql);
    $lUp    = mysql_query($sql);
}


function IncrementaFolio($Campo){

	  $FolA    = mysql_query("SELECT $Campo FROM cia");
	  $lUp     = mysql_query("UPDATE cia SET $Campo = $Campo + 1");
	  $Fol     = mysql_fetch_array($FolA);
	  $FolioU  = $Fol[folioenvios];

	 return $FolioU;

}

function PonTitulo($Titulo){
global $Gcia,$Gtitle,$Titulo,$Id,$Gfecha,$Nivel,$Usr;

$Mnu    = "menu/clap".$Nivel.".js";

//$Usr    = $_COOKIE['USERNAME'];

//echo "SELECT count(*) as mensajes FROM msj WHERE (para='$Usr' AND !bd";
$MsA    = mysql_query("SELECT count(*) as mensajes FROM msj WHERE para='$Usr' AND !bd");
$Ms     = mysql_fetch_array($MsA);

$nMsj   = $Ms[mensajes];

//$Mnu    = "menu/lago29.js";    
    echo '<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">';
      echo '<tr bgcolor="#daeed2">';
      echo "<td width='100%' align='center'><script type='text/JavaScript' src='$Mnu'></script><a class='letra'>.:::$Titulo.:::.</a></td>";
      echo '</tr>';
    echo '</table>';
}
function Eliminar(){
global $busca;

if($busca<>'NUEVO'){
   echo "<br>";
   echo "<div align='center'>Para eliminar &eacute;ste movimiento, favor de poner el password y dar click en el boton de <b>Eliminar</b></div>";
   echo "<div align='center'>Password: ";                    
   echo "<input type='password' style='background-color:#bacbc2;color:#ffffff;font-weight:bold;' name='Password' size='15' maxlength='15'>";
   echo " &nbsp; <input class='nombre_cliente' type='submit' name='Boton' value='Eliminar'></div>";		
}
}



function nuevoEncabezado($Titulo){
						   //Parametros de colores;

$CiaA    = mysql_query("SELECT cia,direccion,colonia,estacion,numeroext,colonia,ciudad FROM cia");
$Cia     = mysql_fetch_array($CiaA);


   echo "<table width='97%' align='center' border='0' cellpadding='0' cellspacing='0' class='texto_bold'>";
   echo "<tr><td height='10%' width='15%'><img src='libnvo/logo.png' border='0'  height='50' width='95'></td>";
   echo "<td height='10%' width='70%' align='center'>";
   echo "<div ><b>$Cia[cia]</b></div>";
   echo "<div >".$Cia[direccion]." No." .$Cia[numeroext] . " ". $Cia[colonia] . " ".$Cia[ciudad]. "</div>";
   echo "<div >$Titulo</div>";
   
   echo "</td>";
   echo "<td height='10%' width='15%'></td>";
   echo "</tr></table><br>"; 
  
}
function scanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('&aacute;', 'a', 'a', 'a', 'a', '&Aacute;', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('&eacute;', 'e', 'e', 'e', '&Eacute;', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
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

?>
