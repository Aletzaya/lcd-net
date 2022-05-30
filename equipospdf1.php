<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:i:s");
$Fechaeven = date("Y-m-d");
$Msj = $_REQUEST["Msj"];
setcookie("BuscaEquipo", $busca);
$Return = "equipose.php";

$cSql = "select * from eqp where id='$busca'";

$CpoA = mysql_query($cSql, $link);

$Cpo = mysql_fetch_array($CpoA);

if ($_REQUEST["bt"] === "Actualizar") {

    $Prv1 = explode(" ", $_REQUEST["Proveedor1"]);
    $Prv2 = explode(" ", $_REQUEST["Proveedor2"]);
    $Prv3 = explode(" ", $_REQUEST["Proveedor3"]);

    $Sql = "UPDATE eqp SET nombre='$_REQUEST[Nombre]',alias='$_REQUEST[Alias]',marca='$_REQUEST[Marca]',"
            . "modelo='$_REQUEST[Modelo]',observaciones='$_REQUEST[Observaciones]', caracteristicas = '$_REQUEST[Caracteristicas]', serie = '$_REQUEST[Serie]', sucursal = '$_REQUEST[Sucursal]', proveedor1 = '$Prv1[0]', proveedor2 = '$Prv2[0]', proveedor3 = '$Prv3[0]', departamento = '$_REQUEST[Departamento]' "
            . "WHERE id='$busca' limit 1";
    //echo $Sql;

    if (mysql_query($Sql)) {
        $Msj = "¡Registro Actualizado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Registro Actualizado", "eqp", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }


} else if ($_REQUEST["bt"] === "Nuevo") {

    $Prv1 = explode(" ", $_REQUEST["Proveedor1"]);
    $Prv2 = explode(" ", $_REQUEST["Proveedor2"]);
    $Prv3 = explode(" ", $_REQUEST["Proveedor3"]);

    $lUp = "INSERT INTO eqp (nombre,alias,marca,modelo,observaciones,caracteristicas,serie,sucursal,proveedor1,proveedor2,proveedor3,departamento) "
            . "VALUES ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Marca]','$_REQUEST[Modelo]',"
            . "'$_REQUEST[Observaciones]','$_REQUEST[Caracteristicas]','$_REQUEST[Serie]','$_REQUEST[Sucursal]','$Prv1[0]','$Prv2[0]','$Prv3[0]','$_REQUEST[Departamento]')";

    if (mysql_query($lUp)) {
        $Msj = "¡Registro ingresado con exito!";
        $Id = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Datos Principales Creacion", "eqp", $Fecha, $Id, $Msj, $Return);
    } else {
        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
        header("Location: equipose.php?busca=NUEVO&Msj=$Msj&Error=SI");
    }


} else if ($_REQUEST["NvaFecha"] === "Nuevo") {

    $Prv = explode(" ", $_REQUEST["Proveedor"]);
    $sql = "INSERT INTO fechas_equipos (id_equipo,fecha,observaciones,proveedor) "
            . "VALUES ('" . $busca . "','" . $_REQUEST["FechaRp"] . "','" . $_REQUEST["ObservacionRp"] . "'," . $Prv[0] . ")";
    if (mysql_query($sql)) {
        $Msj = "¡Registro ingresado con exito!";
        $cId = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Nuevo Fecha de equipos", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["Historial"] === "Nuevo") {
    $Sql = "INSERT INTO historial_equipo (destino,fecha,piso,cuarto,observaciones,id_equipo) "
            . "VALUES ('" . $_REQUEST["Destino"] . "','" . $_REQUEST["Fecha"] . "','" . $_REQUEST["Piso"] . "'"
            . ",'" . $_REQUEST["Cuarto"] . "','" . $_REQUEST["Observaciones"] . "','" . $busca . "');";
    if (mysql_query($Sql)) {
        $Msj = "¡Registro ingresado con exito!";
        $cId = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Agrega Historial del equipo", "h_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "Descarga") {
    header("Content-disposition: attachment; filename=" . $_REQUEST["name"]);
    header("Content-type: MIME");
    readfile("manualespdf/" . $_REQUEST["name"]);
} else if ($_REQUEST["op"] === "Borrapdf") {
    $Sql = "DELETE FROM equipos_pdf WHERE id = " . $_REQUEST["cIdnvo"];
    if (mysql_query($Sql)) {
        $Msj = "¡Registro borrado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Elimina pdf", "equipos_pdf", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "BorraMyR") {
    $Sql = "DELETE FROM fechas_equipos WHERE id = " . $_REQUEST["cIdnvo"];
    if (mysql_query($Sql)) {
        $Msj = "¡Registro borrado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Elimina Fechas de mantenimiento y reparacion", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "BorraHE") {
    $Sql = "DELETE FROM historial_equipo WHERE id = " . $_REQUEST["cIdnvo"];
    echo $Sql;
    if (mysql_query($Sql)) {
        $Msj = "¡Registro borrado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Elimina historial de equipo", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "Guardar") {

    $Up2  = mysql_query("UPDATE eqp SET status='$_REQUEST[Statusbit]' WHERE id='$busca' limit 1;"); 

    $Sql = "INSERT INTO regeq (id_eq,fecha,observaciones,usr,status,fechaeven) VALUES ('$busca','$Fecha','" . $_REQUEST["ObsrBitacora"] . "','$Gusr','" . $_REQUEST["Statusbit"] . "','" . $_REQUEST["Fechaeven"] . "');";
    if (mysql_query($Sql)) {
        $Msj = "¡Registro agregado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Agrega registro en Bitcora", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
} else if ($_REQUEST["op"] === "Responde") {
    $Sql = "INSERT INTO resregeq (id_reg,fecha,observaciones,usr) VALUES ('" . $_REQUEST["idres"] . "','$Fecha','" . $_REQUEST["Respuesta"] . "','$Gusr');";
    if (mysql_query($Sql)) {
        $Msj = "¡Registro agregado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Agrega respuesta a registro en Bitcora", "f_equipos", $Fecha, $busca, $Msj, "equipose.php");
    } else {
        $msj = "Error en sintaxis MYSQL : " . $sql . mysql_error();
        header("Location: equipose.php?busca=$busca&Msj=$msj&Error=SI");
    }
}


$EqpA = "SELECT *,count(*) as contar from equipos_img where id_equipo='$busca'";
$EqpB = mysql_query($EqpA, $link);
$Eqp = mysql_fetch_array($EqpB);
if($Eqp[contar]==0){
    $foto='NoImagen.png';
}else{
    $foto=$Eqp[nombre_archivo];
}

#Variables comunes;
$cSql = "select * from eqp where id='$busca'";
$CpoA = mysql_query($cSql);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;

$sucnombre = $aSucursal[$Cpo[sucursal]];


if($Cpo[sucursal]=='0'){
    $Nsucursal='Administracion';
}elseif($Cpo[sucursal]=='1'){
    $Nsucursal='Matriz';
}elseif($Cpo[sucursal]=='2'){
    $Nsucursal='H.Futura';
}elseif($Cpo[sucursal]=='3'){
    $Nsucursal='Tepexpan';
}elseif($Cpo[sucursal]=='4'){
    $Nsucursal='Los Reyes ';
}elseif($Cpo[sucursal]=='5'){
    $Nsucursal='Camarones';
}elseif($Cpo[sucursal]=='6'){
    $Nsucursal='San Vicente';
}elseif($Cpo[sucursal]=='11'){
    $Nsucursal='OHF - Torre';
}elseif($Cpo[sucursal]=='12'){
    $Nsucursal='OHF - Urgencia';
}elseif($Cpo[sucursal]=='13'){
    $Nsucursal='OHF - Hospitalizacion';
}

require ("config.php");

   //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
 $Usr=$check['uname'];
 $doc_title    = "Equipos";
 $doc_subject  = "recibos unicode";
 $doc_keywords = "keywords para la busqueda en el PDF";
 
   require_once('tcpdf/config/lang/eng.php');
   require_once('tcpdf/tcpdf.php');

    // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Nsucursal,$busca,$Cpo;

    $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
    $NomI    = mysql_fetch_array($InstA);

    $Fecha   = date("Y-m-d");
    $Hora=date("H:i");

    // Logo
    $image_file2 = 'lib/DuranNvoBk.png';
    $this->Image($image_file2, 8, 5, 65, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 12);

    $this->writeHTML('<table border="0" width="600"><tr><td width="30"></td><td width="600"></td></tr><tr><td width="30"></td>
    <td align="center"   width="600">..:: BITACORA DEL EQUIPO No.= '.$busca.' ::..</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 15);

    $this->writeHTML('<table border="0" width="600"><tr><td width="30"></td><td align="center"  width="600">'. $Cpo[alias].'</td></tr></table>', false, 0);
    
    $this->SetFont('helvetica', 'B', 12);

    $this->writeHTML('<table border="0" width="600"><tr><td width="30"></td><td width="750"  align="right" >Sucursal: '.$Nsucursal.' </td></tr></table>', false, 0);



    }

    // Page footer
    function Footer() {

        // Position at 15 mm from bottom
        $this->SetY(-10);
       // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "letter");

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 35);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Resultado".$Estudio);

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

$pdf->SetFont('Helvetica', 'BI', 11, '', 'false');




if($Cpo[status]=='Optimo'){
    $dep1='<img src="../lcd-net/fotoeqp/optimo.JPG" width="30" height="30" />';
}elseif($Cpo[status]=='Observacion'){
    $dep1='<img src="../lcd-net/fotoeqp/observacion.JPG" width="30" height="30" />';
}elseif($Cpo[status]=='Fuera_de_Servicio'){
    $dep1='<img src="../lcd-net/fotoeqp/fuera.JPG" width="30" height="30" />';
}elseif($Cpo[status]=='Almacenado'){
    $dep1='<img src="../lcd-net/fotoeqp/almacenado.JPG" width="30" height="30" />';
}elseif($Cpo[status]=='Baja'){
    $dep1='<img src="../lcd-net/fotoeqp/baja.JPG" width="30" height="30" />';
}

if($Cpo[departamento]=='Laboratorio'){
    $dep='Laboratorio';
}elseif($Cpo[departamento]=='Rayos_X'){
    $dep='Rayos_X';
}elseif($Cpo[departamento]=='Hospital'){
    $dep='Hospital';
}



$html = '<table border="1">
  <tr>
  <td align="center" width="1170" style="background-color: #2c8e3c">
    <b>..:: Datos principales ::..</b>
   </td>
</tr>    
</table>';

$pdf->writeHTML($html,false,false,true,false,'C');

$pdf->SetFont('Helvetica', '', 7, '', 'false');




$html = '<table border="1" width="1170">
<tr>
<td align="center" width="250"><img src="../lcd-net/fotoeqp/'.$foto.'" border="0" width="240" height="200" />
</td>

<td width="920"> 
    <table border="1" width="800">
        <tr>
            <td align="center" width="302"><b>Nombre:</b> <br><br> '.$Cpo[nombre].'</br></br><br></br></td>
            <td align="center" width="350"><b>Alias : </b><br><br> '.$Cpo[alias].'</br></br><br></br></td>
            <td align="center" width ="350"><b>Marca : </b><br><br>'. $Cpo[marca].'</br></br><br></br><br></br><br></br></td>
        </tr>
    <tr>
            <td align="center" width="302"><b>Modelo: </b><br><br> '.$Cpo[modelo].'</br></br><br></br></td>
            <td align="center" width="350"><b>No.Serie : </b><br><br> '.$Cpo[serie].'</br></br><br></br></td>
            <td align="center" width ="350"><b>Sucursal: </b><br><br>'. $Nsucursal.'</br></br><br></br><br></br><br></br></td>
     </tr> 
    

<tr>
           <td align="center" width="460"><br><b>Status:</b> &nbsp; '.$Cpo[status].' '.$dep1.'</br><br></br></td>
           <td align="center" width="460"><br><b>Departamento:</b> &nbsp;'.$dep.' </br><br></br></td>

</tr> 
    </table>
</td>

</tr>    

</table>';

$pdf->writeHTML($html,false,false,true,false,'C');



$pdf->SetFont('Helvetica', '', 9, '', 'false');


$html = '<br><br><table border="0">
  <tr>
  <td align="center" width="1170" style="background-color: #2c8e3c">
    <b>..:: Bitacora ::..</b>
   </td>
</tr> 
<tr>
<td width="920"> 
    <table border="1" width="800">
        <tr>
            <td align="center" width="130">Fecha de Reg</td>
            <td align="center" width="619">Descripcion</td>
            <td align="center" width ="130">Fecha Evento</td>
            <td align="center" width="100">Usuario</td>
            <td align="center" width ="100">Status</td>
</tr> 
    
    </table>

</td>

</tr>    

</table>';

$pdf->writeHTML($html,false,false,true,false,'C');


$pdf->SetFont('Helvetica', '', 7, '', 'false');


$cSql2 = "SELECT * FROM regeq WHERE id_eq= '$busca' order by fecha DESC  limit 10";

$UpA = mysql_query($cSql2, $link);


while ($mql = mysql_fetch_array($UpA)) {
    
    if($mql[status]=='Optimo'){
        $dep11='<img src="../lcd-net/fotoeqp/optimo.JPG" width="30" height="30" />';
    }elseif($mql[status]=='Observacion'){
        $dep11='<img src="../lcd-net/fotoeqp/observacion.JPG" width="30" height="30" />';
    }elseif($mql[status]=='Fuera_de_Servicio'){
        $dep11='<img src="../lcd-net/fotoeqp/fuera.JPG" width="30" height="30" />';
    }elseif($mql[status]=='Almacenado'){
        $dep11='<img src="../lcd-net/fotoeqp/almacenado.JPG" width="30" height="30" />';
    }elseif($mql[status]=='Baja'){
        $dep11='<img src="../lcd-net/fotoeqp/baja.JPG" width="30" height="30" />';
    }

    $html = '<table border="1" width="800">
          <tr>
              <td align="center" width="130">'.$mql[fecha].'</td>
              <td align="center" width="619">'.$mql[observaciones].'</td>
              <td align="center" width ="130">'.$mql[fechaeven].'</td>
              <td align="center" width="100">'.$mql[usr].'</td>
              <td align="center" width ="100">'.$dep11.'<br></br></td>
  </tr></table>';
  
  $pdf->writeHTML($html,false,false,true,false,'C');

 $cSql3 = "(SELECT * FROM resregeq WHERE id_reg= '$mql[id]' order by fecha DESC limit 10)";

 $UpB = mysql_query($cSql3, $link);

 while ($mql2 = mysql_fetch_array($UpB)) {

    if($mql2[status]=='Optimo'){
        $dep12='<img src="../lcd-net/fotoeqp/optimo.JPG" width="30" height="30" />';
    }elseif($mql2[status]=='Observacion'){
        $dep12='<img src="../lcd-net/fotoeqp/observacion.JPG" width="30" height="30" />';
    }elseif($mql2[status]=='Fuera_de_Servicio'){
        $dep12='<img src="../lcd-net/fotoeqp/fuera.JPG" width="30" height="30" />';
    }elseif($mql2[status]=='Almacenado'){
        $dep12='<img src="../lcd-net/fotoeqp/almacenado.JPG" width="30" height="30" />';
    }elseif($mql2[status]=='Baja'){
        $dep12='<img src="../lcd-net/fotoeqp/baja.JPG" width="30" height="30" />';
    }

    $html = '<table border="1" width="800">
          <tr>
              <td align="center" width="130">'.$mql2[fecha].'</td>
              <td align="center" width="619">'.$mql2[observaciones].'</td>
              <td align="center" width ="130">'.$mql2[fechaeven].'</td>
              <td align="center" width="100">'.$mql2[usr].'</td>
              <td align="center" width ="100">'.$dep12.'<br></br></td>
  </tr></table>';
  $pdf->writeHTML($html,false,false,true,false,'C');

       }
}


$pdf->SetFont('Helvetica', '', 9, '', 'false');


$html = '<br><br><table border="0">
  <tr>
  <td align="center" width="1170" style="background-color: #2c8e3c">
    <b>..:: Fechas de Mantenimiento y Reparacion ::..</b>
   </td>
</tr> 
<tr>
<td width="920"> 
    <table border="1" width="800">
        <tr>
            <td align="center" width="200">Proveedor</td>
            <td align="center" width="150">Fecha</td>
            <td align="center" width="820">Observacion</td>


</tr> 
    
    </table>

</td>

</tr>    

</table>';

$pdf->writeHTML($html,false,false,true,false,'C');

$pdf->SetFont('Helvetica', '', 7, '', 'false');

$sql = "SELECT fechas_equipos.id,vm.proveedor,fechas_equipos.fecha,fechas_equipos.observaciones "
. " FROM fechas_equipos LEFT JOIN prbVentaMantenimiento vm ON fechas_equipos.proveedor = vm.id WHERE id_equipo = " . $busca;
$PgsA = mysql_query($sql);
while ($rg = mysql_fetch_array($PgsA)) {
  (($nRng % 2) > 0) ? $Fdo = $Gfdogrid : $Fdo = $Gfdogrid;

    $html = '<table border="1" width="800">
    <tr>
        <td align="left" width="200">'.$rg[proveedor].'</td>
        <td align="center" width="150">'.$rg[fecha].'</td>
        <td align="left" width="820">'.$rg[observaciones].'</td></tr></table>';
    $pdf->writeHTML($html,false,false,true,false,'C');
}

ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>