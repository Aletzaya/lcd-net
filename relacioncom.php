<?php

  session_start();

  require("lib/importeletras.php");


  require("lib/lib.php");

  $link    = conectarse();

  $OrdenDef     = "";            //Orden de la tabla por default
  $tamPag       = 15;
  $nivel_acceso = 10; // Nivel de acceso para esta p�gina.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $Institucion = $_REQUEST[Institucion];
  $Medico      = $_REQUEST[Medico];
  $Status      = $_REQUEST[Status];
  $PeriodoI    = $_REQUEST[PeriodoI];
  $PeriodoF    = $_REQUEST[PeriodoF];
  $Ruta        = $_REQUEST[Ruta];
  $Importes    = $_REQUEST[Importes];
  

  $Titulo  = "Relacion de pago de comisiones del periodo $PeriodoI al $PeriodoF";
  $Titulo1  = "Fray Pedro de Gante Norte No 320";
  
  if(isset($Ruta)){
  

	  if($Medico=='*'){

   		$cSql = "SELECT cmc.inst,cmc.medico,sum(cmc.importe) as importe,sum(cmc.comision) as comision,
         med.nombrec as nommedico,med.nombrec,count(*) as ordenes,
         sum(numestudios) as estudios
   		FROM cmc,med
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''  
   	   AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4'
	      OR cmc.inst = '20' OR cmc.inst = '74' OR cmc.inst = '10' OR cmc.inst = '50' OR cmc.inst = '60') 
		   GROUP BY cmc.medico
		   ORDER BY cmc.medico, cmc.orden ";
		   
	 }else{	   
   		$cSql = "SELECT cmc.inst,cmc.medico,sum(cmc.importe) as importe,sum(cmc.comision) as comision,
         med.nombrec as nommedico,med.nombrec,count(*) as ordenes,
         sum(numestudios) as estudios
   		FROM cmc,med
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''
   		AND cmc.medico='$Medico' AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4'
	      OR cmc.inst = '20' OR cmc.inst = '74' OR cmc.inst = '50' OR cmc.inst = '60') 
		   GROUP BY cmc.medico
		   ORDER BY cmc.medico, cmc.orden ";

	 }  
	 
  }
  
  $Usr = $HTTP_SESSION_VARS['usuario_login'];   

 require ("config.php");

 ?>

 <html>
   <head>
   <meta charset="UTF-8">
       <title>Relacion de Pagos</title>
       <link href="estilos.css" rel="stylesheet" type="text/css"/>
       <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
       <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
       <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
   </head>
   <body>
 
   <?php



if(isset($Ruta)){ //Es que si enviaron la consulta;



    ?>
    <table width="150%" border="0">
      <tr> 
        <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
          </div></td>
        <td width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
            <?php echo "$Titulo1"; ?><br>
            <?php echo "$Titulo"; ?>&nbsp;</div></td>
      </tr>
    </table>
    <font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">

    <?php

     $RtaA  = mysql_query("SELECT descripcion FROM ruta WHERE id='$Ruta' ");
     $Rta  = mysql_fetch_array($RtaA);
   echo "$Gfont Ruta: $Ruta $Rta[descripcion]";

   echo "<hr noshade style='color:3366FF;height:1px'>";
         
   echo "<table align='center' bgcolor='$Gfdogrid' width='98%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr >";
     echo "<th align='center'>$Gfont # </th>";
     echo "<th align='center'>$Gfont Medico </th>";
    echo "<th align='center'>$Gfont Nombre</th>";
    echo "<th align='center'>$Gfont No.ordenes</th>";
    echo "<th align='center'>$Gfont #Estudios</th>";
    if($Importes=='Si'){
       echo "<th align='center'>$Gfont Importe</th>";
       echo "<th align='center'>$Gfont Comision</th>";
    }else{
       echo "<th align='center'>$Gfont Firma</th>";
    }   
    echo "<tr>";

                                               
   $rgA  = mysql_query($cSql);
                       
   $Inst = "";

   $nRng=1;
              
   

   $Gfont="<font size='2' face='Arial, Helvetica, sans-serif'>";


   while ($rg = mysql_fetch_array($rgA)){

     if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                                    
     echo "<tr height='30' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
      echo "<td align='center'>$Gfont $nRng </td>";
      echo "<td align='right'>$Gfont $rg[medico] &nbsp; </td>";
      echo "<td>$Gfont $rg[nommedico]</td>";
      echo "<td align='center'>$Gfont $rg[ordenes] &nbsp; </td>";
      echo "<td align='center'>$Gfont $rg[estudios] &nbsp; </td>";
       if($Importes=='Si'){         
         echo "<td align='right'>$Gfont ".number_format($rg[importe],"2")."</td>";
         echo "<td align='right'>$Gfont ".number_format($rg[comision],"2")."</td>";
      }else{   
         echo "<td align='right'>$Gfont ________________________________</td>";
      }   
      echo "</tr>";
      $nImp += $rg[importe];
      $nCom += $rg[comision];
      $nRng++;    

     }
     
}else{

    ?>
    <table width="150%" border="0">
      <tr> 
        <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
          </div></td>
        <td width="200%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
            <?php echo "$Titulo1"; ?><br>
            <?php echo "$Titulo"; ?>&nbsp;</div></td>
      </tr>
    </table>
    <font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">

    <?php
}

"<tr>";
 echo "<th align='center'>$Gfont </th>";
echo "<th align='center'>$Gfont </th>";
echo "<th align='center'>$Gfont </th>";
echo "<th align='center'>$Gfont $Letra </th>";
echo "<th align='right'>$Gfont Total --------> </th>";
  if($Importes=='Si'){   
   echo "<th align='right'>$Gfont ".number_format($nImp,"2")."</th>";
   echo "<th align='right'>$Gfont ".number_format($nCom,"2")."</th>";
}else{
   echo "<th align='right'>$Gfont </th>";   
}      
echo "<tr>";
echo "</table>";	

 echo "<p>&nbsp;</p>";
      
echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

     echo "$Gfont Ruta: ";
         $RtaA=mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
     echo "<select name='Ruta'>";
     while ($Rta=mysql_fetch_array($RtaA)){
            echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
            if($Rta[id]==$Ruta){$Des1=$Rta[descripcion];}
     }
     echo "<option selected value='$Ruta'>$Des1</option>";
     echo "</select> &nbsp; ";
     
     echo "Inicial: ";
     $CmcA = mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
     echo "<SELECT name='PeriodoI'>";
     while($Cmc=mysql_fetch_array($CmcA)){
          echo "<option value='$Cmc[0]'>$Cmc[0]</option>";
          if($Periodo=='$Cmc[mes]'){$Dsp1 = $Cmc[mes];}
     }
     echo "<option selected value='$Periodo'>$Periodo</option>";
     echo "</SELECT> &nbsp; ";

     echo "Final: ";
     $CmcA = mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
     echo "<SELECT name='PeriodoF'>";
     while($Cmc=mysql_fetch_array($CmcA)){
          echo "<option value='$Cmc[0]'>$Cmc[0]</option>";
          if($Periodo=='$Cmc[mes]'){$Dsp1 = $Cmc[mes];}
     }
     echo "<option selected value='$Periodo'>$Periodo</option>";
     echo "</SELECT>";

     echo " &nbsp; ";

     /*
     $InsA=mysql_query("SELECT institucion,nombre FROM inst");
     echo "<SELECT name='Institucion'>";
     echo "<option value='*'> *  T o d o s </option>";
     echo "<option value='LCD'> INSTITUCIONES LCD</option>";
     while ($Ins=mysql_fetch_array($InsA)){
            echo "<option value='$Ins[0]'>$Ins[0] $Ins[1]</option>";
            if($Ins[institucion]==$Institucion){$Dsp2 = $Ins[nombre];}
     }
     if($Institucion==''){
        echo "<option selected value='*'> * T o d o s </option>";
     }else{
        echo "<option selected value='$Institucion'>$Dsp2</option>";			        
     }   
     echo "</SELECT>";
     */
             
     echo " &nbsp; Medico, * todos: ";
     echo "<INPUT TYPE='TEXT'  name='Medico' size='3' value ='*'> &nbsp; ";
     
     
     echo "C/Importes : ";
     echo "<SELECT name='Importes'>";
     echo "<option value='Si'>Si</option>";
     echo "<option value='No'>No</option>";
     echo "</SELECT>";
           
     echo "<INPUT TYPE='SUBMIT' value='Enviar'>";

 echo "</form>";

 echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";


echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('relacioncompdf.php?Ruta=$Ruta&PeriodoI=$PeriodoI&PeriodoF=$PeriodoF&Medico=$Medico&Importes=$Importes')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

 //echo "<form name='form1' method='post' action='pidedatos.php?cRep=4&fechas=1&FecI=$FecI&FecF=$FecF'>";
 echo "</form>";

echo "</body>";

echo "</html>";

mysql_close();



?>

