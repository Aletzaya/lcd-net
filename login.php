<?php
header("Location: index.php");
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();

include_once ("authconfig.php"); 

#Variables comunes;
$Titulo    = "Regitro";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php 
echo"<body>";

//echo "<form name='form1' method='get' action='menu.php' onSubmit='return ValidaCampos();'>";
echo "<form name='Sample' method='post' action='$resultpage'>";
 
echo "<br><br><br><br><br><br><br><br><br><br><br>";

echo"<table width='290' border='0' align='center' cellpadding='0' cellspacing='0' style='background-image:url(images/splash1-290.jpg)'>"; 
echo  "<tr>";
echo    "<td width='290' height='290' align='center'><table width='80%' border='0' align='center' cellpadding='0' cellspacing='0'>";
echo      "<tr>";
echo        "<td width='30%' height='30' class='letrap'>Usuario:</td>";
echo        "<td>";
echo          "<label for='User'></label>";
//echo          "<input name='User' class='letrap' type='text' id='User' size='22' />";
echo            '<input type="text" class="letrap" size="22" name="username" /></label>'; 
echo        "</td>";
echo      "</tr>";
echo      "<tr>";
echo        "<td height='30'><span class='letrap'>Contraseña:</span></td>";
//echo      "<td><input name='User2' class='letrap' type='text' id='User2' size='22' /></td>";
echo        '<td><input type="password" class="letrap" size="22" name="password" /></td>'; 
echo      "</tr>";
echo    "</table>";
//echo      "<form id='form2' name='form2' method='post'>";
echo        "<br/>";
echo        "<input name='Entrar' type='submit' class='letrap' id='Entrar' value='Entrar' />";
echo    "</td>";
echo  "</tr>";
echo"</table>";
echo "</form>";
echo"</body>";
?>
<script>
 jQuery(function($){
    //Recepcion Menu
    $('#recepcion').hover(
      function () {
          // Show hidden content IF it is not already showing
          if($('#two-level-recepcion').css('display') == 'none') {
              $('#two-level-recepcion').slideDown(200);
          }
      },
      function () {
          // Do nothing when mouse leaves the link
          $.noop(); // Do Nothing
      }
    );
    // Close menu when mouse leaves Hidden Content
    $('#two-level-recepcion').mouseleave(function () {
            $('#two-level-recepcion').slideUp(50);
    });
    //Catalogos Menu
    $('#catalogos').hover(
      function () {
          // Show hidden content IF it is not already showing
          if($('#two-level-catalogos').css('display') == 'none') {
              $('#two-level-catalogos').slideDown(200);
          }
      },
      function () {
          // Do nothing when mouse leaves the link
          $.noop(); // Do Nothing
      }
    );
    // Close menu when mouse leaves Hidden Content
    $('#two-level-catalogos').mouseleave(function () {
            $('#two-level-catalogos').slideUp(50);
    });
    //Procesos Menu
    $('#procesos').hover(
      function () {
          // Show hidden content IF it is not already showing
          if($('#two-level-procesos').css('display') == 'none') {
              $('#two-level-procesos').slideDown(200);
          }
      },
      function () {
          // Do nothing when mouse leaves the link
          $.noop(); // Do Nothing
      }
    );
    // Close menu when mouse leaves Hidden Content
    $('#two-level-procesos').mouseleave(function () {
        $('#two-level-procesos').slideUp(50);
    });
    //Reportes Menu
    $('#reportes').hover(
      function () {
          // Show hidden content IF it is not already showing
          if($('#two-level-reportes').css('display') == 'none') {
              $('#two-level-reportes').slideDown(200);
          }
      },
      function () {
          // Do nothing when mouse leaves the link
          $.noop(); // Do Nothing
      }
    );

    // Close menu when mouse leaves Hidden Content
    $('#two-level-reportes').mouseleave(function () {
        $('#two-level-reportes').slideUp(50);
    });


});
 
</script>
</html>