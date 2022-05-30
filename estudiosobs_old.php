<?php

#Librerias
session_start();

require("lib/lib.php");

$link     = conectarse();
 
#Variables comunes;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1];
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];

$busca     = $_REQUEST[Estudio];

$EstA      = mysql_query("SELECT estudio,descripcion,condiciones,objetivo,proceso FROM est WHERE estudio='$busca'");
$Est       = mysql_fetch_array($EstA);

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
</head>

<?php    

echo '<body topmargin="1">';
        
//Tabla Principal    

  echo "<table border='1' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr><td bgcolor='$Gbgsubtitulo' class='letratitulo' align='center' colspan='2'>";
        echo $Est[estudio]. " " . $Est[descripcion];
    echo "</td>";
    echo "</tr><br>";
    /* 
    //Tabla de que devide la pantalla en dos
    //Tabla Principal que devide la pantalla en dos
    echo "<tr>";
        echo "<td bgcolor='$Gbgsubtitulo'  width='20%' class='letratitulo' align='center'>";
        echo "Concepto";
    echo "</td><td bgcolor='$Gbgsubtitulo'  width='80%' class='letratitulo' align='center'>";
        echo "Descripcion...";
    echo "</td></tr>";
   
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("Condiciones :", "Text", "3", "Zona", "left", "", "3", 1, 1, '');                
        echo "</table>";

        //Renglo para crear un espacio...
         echo "<br>";        
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("Objetivo :", "Text", "3", "Zona", "left", $Cpo[zona], "3", 1, 1, '');                
        echo "</table>";

        //Renglo para crear un espacio...
         echo "<br>";        
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("Procedimiento :", "Text", "3", "Zona", "left", $Cpo[zona], "3", 1, 1, '');                
        echo "</table>";
        
        echo "<br><a class='letra' href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a>";
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";
*/
        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='left'><td><b>Condiciones: </b></td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='justify'><td>$Est[condiciones]</td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";
    
        echo "<br>";
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("Objetivo", "Text", "3", "Objetivo", "left", $Est[objetivo], "3", 1, 1, '');                
        echo "</table>";

        echo "<br>";
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("", "Text", "3", "Zona", "left", $Est[proceso], "3", 1, 1, '');                
        echo "</table>";
        
    echo "</td></tr>";        
    echo "</table>";
    
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