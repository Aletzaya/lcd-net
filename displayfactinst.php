<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();
$busca     = $_REQUEST[busca];
setcookie ("NOOT",$busca);

$Usr      =   $check['uname'];
$cId       = $_REQUEST[busca]; 

//$estudio  =   $_REQUEST[estudio];
$archivo = $_REQUEST[archivo];  
$lBd       = false;

#Variables comunes;
$Titulo    = " Detalle de facturas";

require ("config.php");							   //Parametros de colores;


if($archivo<>''){
    $id   = $_REQUEST[id];
    unlink("factinst/$archivo");
    $Usrelim    = $_COOKIE['USERNAME'];
    $Fechaelim  = date("Y-m-d H:i:s");
    $lUp = mysql_query("UPDATE factinst set usrelim='$Usrelim',fechaelim='$Fechaelim' where archivo='$archivo' and id='$id'");
}

  $HeA  = mysql_query("SELECT instfact.id,instfact.documento,instfact.status,instfact.tpago,instfact.importe,instfact.iva,instfact.total,instfact.usr,instfact.fecha,instfact.inst,inst.alias
  FROM instfact,inst
  WHERE instfact.id='$busca' AND instfact.inst=inst.institucion");
     
  $He   = mysql_fetch_array($HeA);           

?>

<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<meta charset="UTF-8">
<title></title>

<link rel="stylesheet" type="text/css" href="css/dropzone.css">

<script type="text/javascript" src="js/dropzone.js"></script>

</head>

<body>

<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#f1f1f1" style="border-collapse: collapse; border: 1px solid #999;">
       
<tr><td align="center" bgcolor="#156687" class="titulo" colspan="3"><font color="#FFFFFF"><b> Carga de Facturas </b></td></tr></table>

<form class="dropzone" id="mydrop" action="subirfactinst.php">

<div class="fallback">

<input type="file" name="file" multiple id="archivos">

</div>

</form>

<script type="text/javascript">

    var dropzone = new Dropzone("#archivos", {
        url: 'subirfactinst.php'
    });

</script>

<?php

echo "<div align='center'> &nbsp; <font size='+2'>$He[inst] - $He[alias]</font></div>";
echo "<div align='center'> <b>No.Id: $He[id] - Facturas/Remision: $He[documento] - $He[usr]</b></div>";

echo "<br><table width='100%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
       
echo "<tr><td align='left'  bgcolor='#156687' class='titulo' colspan='3'><font color='#FFFFCC'><b> &nbsp; Facturas Digitalizadas </b></td></tr>";     
       
        
$ImgA = mysql_query("SELECT archivo,idnvo FROM factinst WHERE id='$busca' and usrelim=''");
while ($row = mysql_fetch_array($ImgA)) {   
     $Pos   = strrpos($row[archivo],".");
     $cExt  = strtoupper(substr($row[archivo],$Pos+1,4));                             
     $foto  = $row[archivo];        
     if($cExt == 'PDF' ){                
      //  echo "<tr><td align='center'><a href=javascript:winuni('enviafile2.php?busca=$row[archivo]')><img src='lib/Pdf.gif' title='Estudios' border='0'></a></td></tr>";     
        echo "<tr><td align='center'><embed src='factinst/$foto' type='application/pdf' width='100%' height='1300px' /></td></tr>";   

     }elseif($cExt == 'DOCX' ){                                 
              
        echo "<tr><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=http://lcd-net.dyndns.org/lcd/factinst/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>";   

    }elseif($cExt == 'XLSX' ){                                 
              
        echo "<tr><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=http://lcd-net.dyndns.org/lcd/factinst/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>"; 

    }elseif($cExt == 'DCM' ){  

        echo "<a href='http://lcd-net.dyndns.org/lcd/factinst/$foto' style='width:100%; height:50%; border: none;min-height:500px;')><img src='lib/Pdf.gif' title=$Pdf border='0'></a> &nbsp; &nbsp; ";                

       // echo "<tr><td align='center'><iframe src='http://lcd-net.dyndns.org/lcd/estudios/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>";          

     }else{

        echo "<tr><td align='center'><IMG SRC='../lcd/factinst/$foto' border='0' width='1200'></td></tr>";   
     } 
  
     echo "<tr class='content_txt'><td bgcolor='#2980b9' align='left' colspan='2'><font color='#FFFFCC'>".ucfirst(strtolower($row[archivo]))."</font></td><td align='center'><a class='Seleccionar' href='displayfactinst.php?archivo=$foto&id=$busca&busca=$busca&estudio=$estudio' onclick='return confirm(\"Desea eliminar el archivo?\")'><font color='blue'><img src='lib/dele.png' title=Elimina_$Pdf border='0'> - Eliminar</font></a></td></tr>";
    //echo "<tr><td align='center'> &nbsp; </td></tr>";
}    
                    
echo "</table><br>";

        
echo "</td></tr>";

echo "</table>";                                               
                                           
echo "</body>";
    
echo "</html>";  

?>
                           
</body>
    
</html>

<?php
mysql_close();
?>
