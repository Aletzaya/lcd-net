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

$EstA      = mysql_query("SELECT estudio,descripcion,condiciones,objetivo,proceso,contenido,observaciones,entord FROM est WHERE estudio='$busca'");
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
<link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>

<?php    

echo '<body topmargin="1">';
        
//Tabla Principal    

  echo "<table border='1' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr><td bgcolor='$Gbgsubtitulo' class='letratitulo' align='center' colspan='2'>";
        echo $Est[estudio]. " " . $Est[descripcion];
    echo "</td>";
    echo "</tr>";
    echo "</table>";
        echo "<br>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='left'><td><b>Objetivo: </b></td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='justify'><td>$Est[objetivo]</td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";
    
        echo "<br>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='left'><td><b>Condiciones: </b></td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='justify'><td>$Est[condiciones]</td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";
    
        echo "<br>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='left'><td><b>Contenido: </b></td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='justify'><td>$Est[contenido]</td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";
    
        echo "<br>";
    
        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='left'><td><b>Observaciones: </b></td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='justify'><td>$Est[observaciones]</td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        if($Est[entord]==0){
          $Tiempoentrega='Mismo Dia';
        }else{
          $Tiempoentrega=$Est[entord].' Dia(s)';
        }
    
        echo "<br>";
    
        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='left'><td><b>Tiempo de Entrega: </b></td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');                
        echo "</table>";

        echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        echo "<tr class='letrap' align='justify'><td>$Tiempoentrega</td></tr>";
        //       cInput("Condiciones: ", "Text", "3", "condiciones", "left", , "3", 1, 1, '');    
            
        echo "</table>";

        echo "<table width='100%' align='center' border='0'>";
        echo "<tr>";

        echo "<br><td align='center'><a class='edit' href=javascript:wingral('estudiosobs1.php?estudio=$busca')><i class='fa fa-file-pdf-o fa-3x' style='color:red' aria-hidden='true'></i></a></td>";

        echo "</table>";

        echo "<br><br><a class='letra'  href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a>";
        
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