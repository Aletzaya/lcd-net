<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();
$busca     = $_REQUEST[busca];
 
#Variables comunes;
$Titulo    = " Detalle de estudios";

require ("config.php");							   //Parametros de colores;

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>$Titulo</title>";

//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8_spanish_ci" />';    //Con esto salen las ñ y acentos;
echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";

echo '<meta content="text/html; charset=utf-8" http-equiv="content-type">';    

echo "<link href='lib/estilos_clap.css' rel='stylesheet' type='text/css'>";

echo "<meta http-equiv='refresh' content='800;url=http:/lcd/menu.php' />";

echo "</head>";

echo "<body bgcolor='#EFEFEF'>";    
        
   echo "<br><table width='97%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
           
   echo "<tr><td align='left'  bgcolor='#3300CC' class='titulo'><font color='#FFFFCC'><b> &nbsp; Estudios capturados </b></td></tr>";     
            
            
        $ImgA = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$busca' and usrelim=''");
        while ($row = mysql_fetch_array($ImgA)) {   
             $Pos   = strrpos($row[archivo],".");
             $cExt  = strtoupper(substr($row[archivo],$Pos+1,3));                             
             $foto  = $row[archivo];        
             if($cExt == 'PDF' ){                
                echo "<tr><td align='center'><a href=javascript:winuni('enviafile.php?busca=$row[archivo]')><img src='lib/Pdf.gif' title='Estudios' border='0'></a></td></tr>";                                     
             }else{
                echo "<tr><td align='center'><IMG SRC='estudios/$foto' border='0'></td></tr>";   
             }   
             echo "<tr bgcolor='#e1e1e1' class='content_txt'><td align='left' colspan='2'>".ucfirst(strtolower($row[archivo]))."</td><td align='right'><a class='Seleccionar' href='?'>Quitar de la lista</a></td></tr>";
             echo "<tr><td align='center'> &nbsp; </td></tr>";
        } 
       
                        
    echo "</table><br>";

            
    echo "</td></tr>";

echo "</table>";                                               
                                           
echo "</body>";
    
echo "</html>";  
  
mysql_close();
?>
