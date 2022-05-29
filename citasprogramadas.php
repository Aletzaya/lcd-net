<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();

if(isset($_REQUEST[busca])){
        
    if($_REQUEST[busca] == ini ){           
      
       $Fecha = date("Y-m-d");
       $Gcia  = $_SESSION[Usr][1];   
       
       $_SESSION["OnToy"] = array('','','ot.orden','Asc','menu.php',$Fecha,$Gcia);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
 
    }
}    

//Captura los valores que trae y metelos al array
if(isset($_REQUEST[Fecha]))     { $_SESSION['OnToy'][5]   = $_REQUEST[Fecha];}
if(isset($_REQUEST[FiltroCia])) { $_SESSION['OnToy'][6]   = $_REQUEST[FiltroCia];}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca     = $_SESSION[OnToy][0];
$Fecha     = $_SESSION[OnToy][5];          //Pagina a la que regresa con parametros  
$GnSuc     = $_SESSION[OnToy][6];          //Que sucursal estoy checando

$Retornar  = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
 
#Saco los valores de las sessiones los cuales no cambian;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1];
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];

#Variables comunes;
$Titulo    = "Consulta de citas";
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];

$aIzq   = array(" ","-","-","","-","-");				//Arreglo donde se meten los encabezados; Izquierdos
$aDat   = SPLIT(",",$Qry[edi]);					//Arreglo donde llena el grid de datos
$aDer   = array("Sucursal","","","Dia","","","No.cita","","","Hora","","");				//Arreglo donde se meten los encabezados; Derechos;
$aDia   = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
$tamPag = $Qry[tampag];

require ("config.php");							   //Parametros de colores;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script> type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

</head>

<?php    

echo '<body topmargin="1">';

echo "<form name='frmfiltro' method='get' action=".$_SERVER['PHP_SELF'].">";     

   echo "<span class='content1'>Sucursal: ";
   echo "<select class='content5' name='FiltroCia' onChange='frmfiltro.submit();'>";            
   $CiaA  = mysql_query("SELECT id,alias FROM cia ORDER BY id");
   while ($Cia=mysql_fetch_array($CiaA)){      
        echo '<option value='.$Cia[id].'>'.$Cia[alias].'</option>';
        if($Cia[id] == $GnSuc){
           echo '<option selected value="'.$Cia[id].'">'.$Cia[alias].'</option>';  
        }
    }
    echo '<option value="*">* todos</option>';
    if($GnSuc == '*'){
           echo '<option selected value="*">* todos</option>';          
    }
    echo '</select>';                          

    echo " Fecha: </span>";
    echo "<input type='text' name='Fecha' value='$Fecha' size='10' class='content5'> ";  
    echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI, 'yyyy-mm-dd', this)>";

    //echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].ApartirDe,'yyyy-mm-dd',this)> ";        
    echo " &nbsp; <input type='submit' name='Boton' value='enviar' class='letrap'>";
    //echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Gfecha,'yyyy-mm-dd',this)> &nbsp; &nbsp; ";
    //echo " &nbsp; &nbsp; <a  class='edit' href='?Todo=*'><img src='images/rest.png'> Ver todo </a> ";                  

echo "</form>";      


//Tabla contenedor de brighs
echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
echo '<tr>';
echo '<td height="380" valign="top">';

#Armo el query segun los campos tomados de qrys;
    
    $Dia = date('w', strtotime($Fecha));                    
                    
    $lUp = mysql_query("DELETE FROM tmcitas WHERE usr='$Gusr'");

    $cSql = "INSERT INTO tmcitas SELECT '$Gusr','0',cita,0,horai,'','' 
             FROM hrarios 
             WHERE dia='$Dia' AND consultorio='1' ORDER BY cita";
                    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'TMCITAS';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    
    if($GnSuc <> '*'){
       $OtA = mysql_query("SELECT cliente,citanum,recepcionista,suc,diagmedico FROM ot 
           WHERE fecha='$Fecha' AND servicio='Cita' AND suc='$GnSuc' ORDER BY citanum");
    }else{
       $OtA = mysql_query("SELECT cliente,citanum,recepcionista,suc,diagmedico FROM ot 
           WHERE fecha='$Fecha' AND servicio='Cita' ORDER BY citanum");        
    }    
    while ($row = mysql_fetch_array($OtA)) {
     
        $aHrs   = SPLIT(",",$row[citanum]);	// Dia | Cita | Hora;
                
        if($aHrs[1] == $nCita){
           $cSql = "INSERT INTO tmcitas (usr,usragr,paciente,cita,diagmedico,sucursal) 
                    VALUES 
                   ('$Gusr','$row[recepcionista]','$row[cliente]','$aHrs[1]','$row[diagmedico]','$GnSuc')"; 
            if (!mysql_query($cSql)) {
                echo "<div align='center'>$cSql</div>";
                $Archivo = 'TMCITAS';
                die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
            }
              
        }
        
        $cSql  = "UPDATE tmcitas SET 
               tmcitas.paciente='$row[cliente]', usragr='$row[recepcionista]',diagmedico='$row[diagmedico]',
               sucursal='$row[suc]'
               WHERE tmcitas.cita='$aHrs[1]' AND tmcitas.usr='$Gusr' AND tmcitas.paciente='0' limit 1";
        if (!mysql_query($cSql)) {
                echo "<div align='center'>$cSql</div>";
                $Archivo = 'TMCITAS';
                die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
        
        $nCita = $aHrs[1];
    }

    echo "<table width='100%' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

    echo "<tr style='background-image: url(images/CejaAzulGrad.jpg)'>";


    echo "<td>Cita</td><td>Hora</td><td>Cta</td><td>Paciente</td><td>Diag/medico</td><td>Sucursal</td><td>Usr</td></tr>";

    $CitA = mysql_query("SELECT tmcitas.cita,tmcitas.paciente,tmcitas.horai,cli.nombrec,tmcitas.usragr,
            tmcitas.sucursal,cia.alias,tmcitas.diagmedico 
            FROM cia,tmcitas 
            LEFT JOIN cli ON tmcitas.paciente=cli.cliente
            WHERE tmcitas.usr='$Gusr' AND tmcitas.sucursal=cia.id
            ORDER BY cita");
    
    while ($row = mysql_fetch_array($CitA)) {
                            
        if (($nRng % 2) > 0) {$Fdo = 'FFFFFF';} else {$Fdo = $Gfdogrid;}    //El resto de la division;

        echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        /*
        if ($row[paciente] == 0) {
                echo "<td align='center'><input type='radio' name='Cita' value='$row[cita]'></td>";
        } else {
                echo "<td align='center'> - </td>";
        }         
        */
        //echo "<td align='center'><input type='radio' name='Cita' value='$row[cita]'></td>";
        echo "<td align='right' class='content1'> &nbsp; $row[cita]</td>";
        echo "<td align='center' class='content2'> $row[horai]</td>";
        echo "<td class='content2'> &nbsp; ".ucwords(strtolower($row[paciente]))."</td>";
        echo "<td class='content2'> &nbsp; ".ucwords(strtolower($row[nombrec]))."</td>";
        echo "<td class='content2'> &nbsp; ".ucwords(strtolower($row[diagmedico]))."</td>";
        if($row[sucursal] <> 0){
            echo "<td class='content2'>".ucwords(strtolower($row[alias]))."</td>";
        }else{
            echo "<td class='content2'>---</td>";            
        }    
        echo "<td class='content2'>".ucwords(strtolower($row[usragr]))."</td>";
        echo "</tr>";
        $nRng++;
    }

    echo "</table>";
    
    echo "<br><a class='letra' href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a>";
 
    //echo "</td></tr></table>";
  
    echo "</form>";
    
echo '</td>';
echo '</tr>';
echo '</table>';
    
    //PonPaginacion(false);           #-------------------pon los No.de paginas-------------------    

    //CuadroInferior($busca);
    

echo '</body>';

?>
<script type="application/javascript">
(function() {
    function ocultar(one, two, three, four, five) {
        $.each([one, two, three, four, five], function(index, elm) {
            if($(elm).css('display')) {
                $(elm).slideUp('fast');
            }
        });
    }
    jQuery(function($) {

        //-----Recepcion Menu
        $('#recepcion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-recepcion').css('display') == 'none') {
                $('#two-level-recepcion').slideDown('fast');
                ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-recepcion').mouseleave(function () {
            if($('#two-level-recepcion').css('display')) {
                $('#two-level-recepcion').slideUp('fast');
            }
        });
        //-----Facturacion Menu
        $('#facturacion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-facturacion').css('display') == 'none') {
                $('#two-level-facturacion').slideDown('fast');
                ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-recepcion', '#two-level-moviles');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-facturacion').mouseleave(function () {
            if($('#two-level-facturacion').css('display')) {
                $('#two-level-facturacion').slideUp('fast');
            }
        });		

        //--------Catalogos Menu
        $('#catalogos').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-catalogos').css('display') == 'none') {
                $('#two-level-catalogos').slideDown('fast');
                ocultar('#two-level-recepcion', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-catalogos').mouseleave(function () {
            if($('#two-level-catalogos').css('display')) {
                $('#two-level-catalogos').slideUp('fast');
            }
        });

        //-----Ingresos Menu
        $('#ingresos').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-ingresos').css('display') == 'none') {
                  $('#two-level-ingresos').slideDown('fast');
                  ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-ingresos').mouseleave(function () {
            if($('#two-level-ingresos').css('display')) {
                  $('#two-level-ingresos').slideUp('fast');
              }

        });

        //------Reportes Menu
        $('#reportes').mouseover(function () {
              // Show hidden content IF it is not already showing
            if($('#two-level-reportes').css('display') == 'none') {
                $('#two-level-reportes').slideDown('fast');
                ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-moviles', '#two-level-facturacion','#two-level-ingresos', '#two-level-procesos');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-reportes').mouseleave(function () {
            if($('#two-level-reportes').css('display')) {
                $('#two-level-reportes').slideUp('fast');
            }
        });
		        //-----Moviles Menu
        $('#moviles').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-moviles').css('display') == 'none') {
                  $('#two-level-moviles').slideDown('fast');
                  ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-ingresos');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-moviles').mouseleave(function () {
            if($('#two-level-moviles').css('display')) {
                  $('#two-level-moviles').slideUp('fast');
              }

        });


    });
})();
</script>
</html>
<?php
mysql_close();
?>
