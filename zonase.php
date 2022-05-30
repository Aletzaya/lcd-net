<?php

#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();

//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
 

$Usr      = $_COOKIE[USERNAME];
$Nivel    = $_COOKIE[LEVEL];

#Variables comunes;
$Titulo    = " Edita zonas";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "zonas.php";


#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: zonas.php$busca=ini");
    
}elseif($_REQUEST[Boton] == 'Agregar paciente'){

    if($_REQUEST[Institucion] <> ''){    
        $cSql =  "INSERT INTO zns (descripcion,poblacion)
              VALUES
              ('$_REQUEST[Descripcion]','$_REQUEST[Poblacion]')";    

        $cId = mysql_insert_id();
    
        $cProceso = "Agrega zns $cId ";
    
        if (!mysql_query($cSql)) {
            echo "<div align='center'>$cSql</div>";
            $Archivo = 'ZNS';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    
        logs('Agr(zns)','$Usr','$cProceso');
        
    }else{
        
       $Msj = "Aun no has dado la institucion"; 
        
    }    
    /*
    $cSql = "update cliale SET id='$cId',usr='' WHERE usr='$Usr'";
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLIALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    */

    header("Location: zonas.php?busca=$cId");
        
}elseif ($_REQUEST[Boton] == 'Actualizar') {
            
    $cSql =  "UPDATE zns SET descripcion='$_REQUEST[Descripcion]',
              poblacion='$_REQUEST[Poblacion]'
              WHERE zona='$busca'";
    
    $cProceso = "Actualizo zns id ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'ZNS';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(zns)','$Usr','$cProceso');
    
    
    header("Location: zonas.php");
    
}elseif($_REQUEST[cId] <> '' ){
    
    $cSql =  "DELETE FROM zns WHERE idnvo = '$_REQUEST[cId]'";    

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}


$CpoA      = mysql_query("SELECT * FROM zns WHERE zona='$busca'");
$Cpo       = mysql_fetch_array($CpoA);

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
encabezados();
PonTitulo($Titulo);
menu1();

    //submenu();

//Tabla contenedor de brighs

        
//Tabla Principal    

echo "</td></tr><tr><td valign='top' align='center' height='440'>";

    echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";

        //Tabla de que devide la pantalla en dos
    //Tabla Principal que devide la pantalla en dos
    echo "<table border='0' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr>";
        echo "<td bgcolor='$Gbgsubtitulo'  width='50%' class='letratitulo' align='center'>";
        echo "Datos principales";
    echo "</td><td bgcolor='$Gbgsubtitulo'  width='50%' class='letratitulo' align='center'>";
        echo "Otros datos...";
    echo "</td></tr>";
    
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";


        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
           
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";     
                   cInput("Zona :", "Text", "3", "Zona", "left", $Cpo[zona], "3", 1, 1, '');
                   cInput("Descripcion :", "Text", "20", "Descripcion", "left", $Cpo[descripcion], "50", 1, 0, '');
                   cInput("Poblacion :", "Text", "7", "Poblacion", "left", $Cpo[poblacion], "15", 1, 0, '');
                
        echo "</table>";
            
        Botones();
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='0' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

            echo "<tr class='letrap'><td align='right' valign='bottom'>Observaciones:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Observaciones' cols='65' rows='4'>$Cpo[observaciones]</TEXTAREA>";
            echo "</td></tr>";   


            
            
            echo "</table>";
    //Cierra tabla principal la de dos cuadros        
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
<?php
mysql_close();
?>

