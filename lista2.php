<?php

  #Librerias
  session_start();
 
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");
  
  $link=conectarse();

  $Cia=$_SESSION['Cia'];
  $busca=$_REQUEST[busca];
    
  require ("config.php");							//Parametros de colores;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

 //headymenu($Titulo,0);

$cCiaA = mysql_query("SELECT razon,destajo,diasemana,diaquincena FROM cia WHERE id='$Cia' ",$link);
$cCia  = mysql_fetch_array($cCiaA);

$NomA  = mysql_query("SELECT * FROM nom WHERE id='$busca' ",$link);
$Nom   = mysql_fetch_array($NomA);

//$Tipo    = $_REQUEST[Tipo];         //1.-fiscal, 2.-no fiscal;

$Tfaltas = 0;
$Fecha=strtotime($Nom[fecha]);
if($Nom[nomina]=='Semana'){ $FechaI=strtotime("-6 days",$Fecha);}else{$FechaI=strtotime("-14 days",$Fecha);}    

      $RegA = mysql_query("  
      SELECT nomf.cuenta,emp.nombre,nomf.faltas,nomf.sueldo,nomf.septimo,nomf.ispt,
      nomf.sueldo + nomf.septimo - nomf.ispt,nomf.sueldod,depn.nombre,emp.infonavit,
      emp.departamento,emp.credencial,emp.fechai,emp.rfc,emp.imss,nomf.retardos,nomf.impretardos,nomf.otrosegr,
      nomf.ahorro,nomf.prestamo,nomf.pension, nomf.otrosing+nomf.festivos as otrosing, nomf.cobertura, nomf.horasext, nomf.primavac 
      FROM nomf,emp,depn 
      WHERE nomf.cuenta=emp.id and emp.departamento=depn.id and nomf.id='$busca' 
      ORDER BY emp.departamento, emp.nombre");

      $Treg=0;
      $Totsue=0;
      $Totsep=0;
      $Totisp=0;      
   
   if($Nom[nomina]=='Semana'){$DiasT=$cCia[diasemana];}else{$DiasT=$cCia[diaquincena];}    

   echo "$Gfont <br>";
   echo " &nbsp; &nbsp; $cCia[razon]";
   echo "<br> &nbsp; &nbsp; Salario minimo ".number_format($i,"0");
   echo "<br> &nbsp; &nbsp; Del ".$Nom[fechai] ." al ". $Nom[fechaf]." Semana: $Nom[numero]";
   echo "<table align='center' width='99%' border='0' cellpadding=0 cellspacing=0>";   
   echo "<tr>";
   echo "<td>$Gfont Clv </font></td>";
   echo "<td>$Gfont Nombre </font></td>";
   echo "<td>$Gfont D.Trab</font></td>";
   echo "<td>$Gfont Fltas</font></td>";
   echo "<td align='right'>$Gfont Sdo.diario</font></td>";
   echo "<td align='right'>$Gfont S.Periodo</font></td>";
   echo "<td align='right'>$Gfont 7o.Dia</font></td>";
   echo "<td align='right'>$Gfont Hrs.Ext</font></td>";
   echo "<td align='right'>$Gfont Coberts</font></td>";
   echo "<td align='right'>$Gfont PrimaVac</font></td>";
   echo "<td align='right'>$Gfont Otrs Ing</font></td>";
   echo "<td align='right'>$Gfont T.Percep</font></td>";
   echo "<td align='right'>$Gfont Ispt</font></td>";
   echo "<td align='right'>$Gfont Ahorro</font></td>";
   echo "<td align='right'>$Gfont Prestamo</font></td>";
   echo "<td align='right'>$Gfont Pension</font></td>";
   echo "<td align='right'>$Gfont O.Egrs</font></td>";
   echo "<td align='right'>$Gfont T.Deducc</font></td>";
   echo "<td align='right'>$Gfont A pagar</font></td>";
   echo "</tr>";

   $Depto='xxx';      
   while($rg=mysql_fetch_array($RegA)){
         if($Depto<>$rg[departamento]){
            if($Depto<>'xxx'){            //Mando los totales por departamento;
                echo "<tr>";
                echo "<td>&nbsp;</td><td align='right'>$Gfont Totales - - - - - - > no.empleados ".number_format($Reg,"0")."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
                echo "<td align='right'>$Gfont ".number_format($Tsueldo,"2")." &nbsp;</td>";
                echo "<td align='right'>$Gfont ".number_format($Tseptimo,"2")." &nbsp; </td>";
                echo "<td align='right'>$Gfont ".number_format($Thorasext,"2")." &nbsp; </td>";
                echo "<td align='right'>$Gfont ".number_format($Tcoberturas,"2")." &nbsp; </td>";
                echo "<td align='right'>$Gfont ".number_format($Tprimavac,"2")." &nbsp; </td>";
                echo "<td align='right'>$Gfont ".number_format($Totrosing,"2")." &nbsp; </td>";
                echo "<td align='right'>$Gfont ".number_format($Tsueldo+$Tseptimo+$Thorasext+$Tcoberturas+$Tprimavac+$Totrosing,"2")." &nbsp; </td>";
                
                echo "<td align='right'>$Gfont ".number_format($Tispt,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tahorro,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tprestamo,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tpension,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Totrosegr,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tispt+$Tahorro+$Tprestamo+$Tpension+$Totrosegr,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tsueldo+$Tseptimo-($Tispt+$Tahorro+$Tprestamo+$Tpension+$Totrosegr),"2")." &nbsp; </td>"; 
                echo "</tr>";
            }
            $Reg=$Tispt=$Totrosegr=$Tsueldo=$Tseptimo=$Tfaltas=$Tahorro=$Tprestamo=$Tpension=0;
            $Depto=$rg[departamento];
            
            echo "<tr>";
            echo "<td>&nbsp;</td><td>$Gfont *** <b> $rg[departamento] $rg[8] </b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
            echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
            echo "</tr>";            
                     
         }
         
         # ** Si premio  no alcanza para pagar ratardos y fallas lo quito de sueldo;
         $Ingresos  = $rg[sueldo]+$rg[septimo]+$rg[horasext]+$rg[cobertura]+$rg[primavac]+$rg[otrosing];
         $Egresos   = $rg[ispt]+$rg[ahorro]+$rg[prestamo]+$rg[pension]+$rg[otrosegr]+$rg[infonavit];
         
         
			 echo "<tr>";
          echo "<td align='right'>$Gfont  $rg[credencial] &nbsp;</font></a></td>";
          echo "<td>$Gfont ".substr($rg[1],0,25)."&nbsp;</font></a></td>";
          echo "<td>$Gfont &nbsp; ".number_format($DiasT-$rg[faltas],"0")." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[faltas],'0')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[sueldod],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($Sueldo,'2')." &nbsp;</font></a></td>";          
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[septimo],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[horasext],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[cobertura],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[primavac],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[otrosing],'2')." &nbsp;</font></a></td>";

          echo "<td align='right'>$Gfont &nbsp; ".number_format($Ingresos,'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[ispt],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[ahorro],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[prestamo],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[pension],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($rg[otrosegr],'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($Egresos,'2')." &nbsp;</font></a></td>";
          echo "<td align='right'>$Gfont &nbsp; ".number_format($Ingresos-$Egresos,'2')." &nbsp;</font></a></td>";
          echo "</tr>";
          
          $Tsueldo   += $Sueldo;
          $Tseptimo  += $rg[septimo];
          $Tispt     += $rg[ispt];

          $Thorasext += $rg[horasext];
          $Tcobertura+= $rg[cobertura];
          $Tprimavac += $rg[primavac];
          $Totrosing += $rg[otrosing];
          
          $Totrosegr += $rg[otrosegr];
          $Tfaltas   += $rg[faltas];
          
          $Tahorro   += $rg[ahorro];
          $Tpension  += $rg[pension];
          $Tprestamo += $rg[prestamo];
                    
          $Tperc     += $Sueldo + $rg[septimo];
          $Reg++;
          
          $Treg++;
          $Totsue      += $Sueldo;
          $Totsep      += $rg[septimo];
          $Totisp      += $rg[ispt];
          $Tototrosegr += $rg[otrosegr];
          $Gfaltas     += $rg[faltas];
          
          $Totaho      += $rg[ahorro];
          $Totpre      += $rg[prestamo];
          $Totpen      += $rg[pension];
   }

                echo "<tr>";
                echo "<td>&nbsp;</td><td align='right'>$Gfont Totales - - - - - - > no.empleados ".number_format($Reg,"0")."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
                echo "<td align='right'>$Gfont ".number_format($Tsueldo,"2")." &nbsp;</td>";
                echo "<td align='right'>$Gfont ".number_format($Tseptimo,"2")." &nbsp; </td>";
                echo "<td align='right'>$Gfont ".number_format($Tsueldo+$Tseptimo,"2")." &nbsp; </td>";
                
                echo "<td align='right'>$Gfont ".number_format($Tispt,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tahorro,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tprestamo,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tpension,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Totrosegr,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tispt+$Tahorro+$Tprestamo+$Tpension+$Totrosegr,"2")." &nbsp;</td>";                
                echo "<td align='right'>$Gfont ".number_format($Tsueldo+$Tseptimo-($Tispt+$Tahorro+$Tprestamo+$Tpension+$Totrosegr),"2")." &nbsp; </td>"; 
                echo "</tr>";

			echo "<tr><td cols='100'>&nbsp;</td></tr>";

         echo "<tr>";
         echo "<td>&nbsp;</td><td align='right'>$Gfont  <b>GRAN TOTAL - - ></b> &nbsp; ".number_format($Treg,"0")."</td><td>&nbsp;</td>";
         echo "<td align='right'>$Gfont ".number_format($Gfaltas,"0")." &nbsp;</td><td>&nbsp;</td>";
         echo "<td align='right'>$Gfont ".number_format($Totsue,"2")." &nbsp;</td>";
         echo "<td align='right'>$Gfont ".number_format($Totsep,"2")." &nbsp;</td>";
         echo "<td align='right'>$Gfont ".number_format($Totsue+$Totsep,"2")." &nbsp;</td>";
         echo "<td align='right'>$Gfont ".number_format($Totisp,"2")." &nbsp;</td>";  
         echo "<td align='right'>$Gfont ".number_format($Totaho,"2")." &nbsp;</td>";                
         echo "<td align='right'>$Gfont ".number_format($Totpre,"2")." &nbsp;</td>";                
         echo "<td align='right'>$Gfont ".number_format($Totpen,"2")." &nbsp;</td>";                                       
         echo "<td align='right'>$Gfont ".number_format($Tototrosegr,"2")." &nbsp;</td>";                
         echo "<td align='right'>$Gfont ".number_format($Totisp+$Tototrosegr+$Totaho+$Totpre+$Totpen,"2")." &nbsp;</td>";                
         echo "<td align='right'>$Gfont ".number_format($Totsue+$Totsep-$Totisp-$Tototrosegr-$Totaho-$Totpre-$Totpen,"2")." &nbsp;</td>";
         echo "<td>&nbsp;</td>";                
         echo "</tr>";

   echo "</tr></table>";    
   
   echo "<p>&nbsp;</p>";  
   

echo "<table align='center' width='98%' border='0' cellpadding=0 cellspacing=0>";   

echo "<tr><td>";

echo "<a href='nom.php'><img src='lib/regresar.gif' border='0'></a>";

echo "</td><td>";

echo "<form name='form1' method='post' action='nom.php'>";

     echo "<input type='submit' name='Imprimir' value='Imprime' onCLick='print()'>";

echo "</form>";

echo "</td><td>";

       echo "<form name='form1' method='get' action='lista.php'>";
           echo "<select name='Tipo'>";
          echo "<option value='1'>1</option>";
          echo "<option value='2'>2</option>";
          echo "<option selected value=''><-Elige tipo-></option>";
          echo "</select> &nbsp; ";
          echo "<INPUT TYPE='SUBMIT' value='Ok'></font></div>";
          echo "<input type='hidden' name='busca' value=$busca>";

      echo "</form>";

echo "</td></tr></table>";

echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?>