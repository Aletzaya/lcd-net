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
$Titulo    = " Edita datos del paciente";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "depto.php";


#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: depto.php");
    
}elseif($_REQUEST[Boton] == 'Agregar paciente'){

    if($_REQUEST[Institucion] <> ''){
        $Nombrec = $_REQUEST[Apellidop] . " " . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];
    
        $cSql =  "INSERT INTO dep (nombre)
              VALUES
              ('$_REQUEST[Nombre]')";    

        $cId = mysql_insert_id();
    
        $cProceso = "Agrega dep $cId ";
    
        if (!mysql_query($cSql)) {
            echo "<div align='center'>$cSql</div>";
            $Archivo = 'DEP';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    
        logs('Agr(dep)','$Usr','$cProceso');
        
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

    header("Location: depto.php?busca=$cId");
        
}elseif ($_REQUEST[Boton] == 'Actualizar') {
            
    $cSql =  "UPDATE dep SET nombre='$_REQUEST[Nombre]'
              WHERE departamento ='$busca'";
    
           
    $cProceso = "Actualizo dep departamento ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'DEP';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(dep)','$Usr','$cProceso');
    
    
    header("Location: depto.php");
    
}elseif($_REQUEST[Boton] == 'Agregar alergia' ){
    
    if($busca == NUEVO ){
        $cSql =  "INSERT INTO dep (usr,nombre,descripcion)
              VALUES
              ('$Usr','$_REQUEST[NombreAle]','$_REQUEST[Descripcion]')";    
    }else{
        $cSql =  "INSERT INTO dep (id,nombre,descripcion)
              VALUES
              ('$busca','$_REQUEST[NombreAle]','$_REQUEST[Descripcion]')";            
    }    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}elseif($_REQUEST[cId] <> '' ){
    
    $cSql =  "DELETE FROM cliale WHERE idnvo = '$_REQUEST[cId]'";    

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}


$CpoA      = mysql_query("SELECT * FROM dep WHERE departamento='$busca'");
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
                   cInput("Departamento :", "Text", "18", "Departamento", "left", $Cpo[departamento], "40", 1, 1, '');
                   cInput("Nombre :", "Text", "18", "Nombre", "left", $Cpo[nombre], "40", 1, 0, '');
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
