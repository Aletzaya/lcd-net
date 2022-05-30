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

$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];

if($op==1){
    $CpoA      = mysql_query("SELECT alias,observaciones,servicio,administrativa FROM inst WHERE institucion='$busca'");
    $Cpo       = mysql_fetch_array($CpoA);
}elseif($op==2){
    $CpoA      = mysql_query("SELECT nombrec,observaciones,descuentos FROM cli WHERE cliente='$busca'");
    $Cpo       = mysql_fetch_array($CpoA);      
}elseif($op==3){
    $CpoA      = mysql_query("SELECT nombrec,refubicacion,servicio,observaciones FROM med WHERE id='$busca'");
    $Cpo       = mysql_fetch_array($CpoA);      
}

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

  echo "<table border='0' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr><td bgcolor='$Gbgsubtitulo' class='letratitulo' align='center' colspan='2'>";
      if($op == 1 ){
         echo $busca. " " . $Cpo[alias];
      }elseif($op == 2){
         echo $busca. " " . $Cpo[nombrec];          
      }elseif($op == 3){
         echo $busca. " " . $Cpo[nombrec];          
      }  
    echo "</td>";
    echo "</tr>";
    
    //Tabla de que devide la pantalla en dos
    //Tabla Principal que devide la pantalla en dos
    echo "<tr>";
        echo "<td bgcolor='$Gbgsubtitulo'  width='30%' class='letratitulo' align='center'>";
        echo "Concepto";
    echo "</td><td bgcolor='$Gbgsubtitulo'  width='70%' class='letratitulo' align='center'>";
        echo "Descripcion...";
    echo "</td></tr>";
    
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";
        if($op == 1){
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Observaciones: </span></td></tr>";                
            echo "</table>";

            //Renglo para crear un espacio...
             echo "<br>";        
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Descuento: </span></td></tr>";                
            echo "</table>";

            //Renglo para crear un espacio...
             echo "<br>";        
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               echo "<tr height='21'><td alig='right'><span class='content1'>Administrativo: </span></td></tr>";                
            echo "</table>";
        }elseif($op == 2){    
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Observaciones: </span></td></tr>";                
            echo "</table>";

            //Renglo para crear un espacio...
             echo "<br>";        
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Descuento: </span></td></tr>";                
            echo "</table>";

            //Renglo para crear un espacio...
             echo "<br>";        
        
        }elseif($op == 3){    
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Servicio: </span></td></tr>";                
            echo "</table>";

            //Renglo para crear un espacio...
             echo "<br>";        
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Observaciones: </span></td></tr>";                
            echo "</table>";
            echo "<br>";
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   echo "<tr height='21'><td alig='right'><span class='content1'>Ubicacion: </span></td></tr>";                
            echo "</table>";

            //Renglo para crear un espacio...
             echo "<br>";        
        
        }     
        echo "<br><a class='letra' href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a>";
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";
        if($op == 1){
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[observaciones], "3", 1, 1, '');                
            echo "</table>";
    
            echo "<br>";
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[servicio], "3", 1, 1, '');                
            echo "</table>";

            echo "<br>";
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[administrativa], "3", 1, 1, '');                
            echo "</table>";
        }elseif($op==2){
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[observaciones], "3", 1, 1, '');                
            echo "</table>";
    
            echo "<br>";
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[descuentos], "3", 1, 1, '');                
            echo "</table>";

            echo "<br>";
        
            
        }elseif($op==3){
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[servicio], "3", 1, 1, '');                
            echo "</table>";
    
            echo "<br>";
        
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[observaciones], "3", 1, 1, '');                
            echo "</table>";
            echo "<br>";
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
                   cInput("", "Text", "3", "", "left", $Cpo[refubicacion], "3", 1, 1, '');                
            echo "</table>";

            echo "<br>";
        
            
        }      
        
    echo "</td></tr>";        
    echo "</table>";
    
echo '</body>';

?>
</html>
<?php
mysql_close();
?>
