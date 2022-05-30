<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo  = "Recibos de comisiones";

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
  

  ?>

  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html>
  <head>
  <meta charset="UTF-8">
  <title>.::Recibos::..</title>

  </head>
  
  <body bgcolor="#FFFFFF">
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
   <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

  <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
  
  <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
  
  <?php

  if(isset($Ruta)){
  
	  if($Medico=='*'){

   		$cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
         med.nombrec as nommedico,cli.nombrec,med.zona as zonas,cmc.concepto
   		FROM cmc,med,cli
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''  
   	   AND cmc.cliente=cli.cliente AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4'
	      OR cmc.inst = '20' OR cmc.inst = '74' OR cmc.inst = '10' OR cmc.inst = '50' OR cmc.inst = '60') 
	      GROUP BY cmc.orden
		   ORDER BY cmc.medico, cmc.orden ";
         
	 }else{	   

   		$cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
         med.nombrec as nommedico,cli.nombrec,med.zona as zonas,cmc.concepto
   		FROM cmc,med,cli
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''
   		AND cmc.medico='$Medico' AND cmc.cliente=cli.cliente 
   	   AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4'
	      OR cmc.inst = '20' OR cmc.inst = '74' OR cmc.inst = '10' OR cmc.inst = '50' OR cmc.inst = '60') 
	      GROUP BY cmc.orden
		   ORDER BY cmc.medico, cmc.orden ";
    }
    
  }

  $Usr = $HTTP_SESSION_VARS['usuario_login'];   

 require ("config.php");


  if(isset($Ruta)){
      		  		       		  		 
      $rgA      = mysql_query($cSql);
      	  
      $Medico   = "";
      
      $nRenglon = 0;
      		  		  
      while ($rg = mysql_fetch_array($rgA)){
				
			if($Medico<>$rg[medico]){

			   if($Medico<>''){

					$Letra = impletras($nCom," pesos ");

    				echo "<tr height='20'>";
					echo "<th with='10%' align='center'>$Gfont </th>";
   				echo "<th with='10%' align='center'>$Gfont </th>";
   				echo "<th with='10%' align='center'>$Gfont </th>";
   				echo "<th with='10%' align='center'>$Gfont $Letra </th>";
   				echo "<th with='10%' align='right'>$Gfont Total --------> </th>";
   				echo "<th with='30%' align='right'>$Gfont ".number_format($nImp,"2")."</th>";
   				echo "<th with='30%' align='right'>$Gfont ".number_format($nCom,"2")."</th>";
   				echo "<tr>";
        			echo "</table>";	        			
  					echo "<p align'center'>$Gfont Fecha/entrega: ________________ &nbsp; ";
  					echo " &nbsp; &nbsp; Quien recibe: __________________________________________";
  					echo " &nbsp; Firma: ______________________ </p>"; 
  					echo "<p>$Gfont Comentarios : __________________________________________________________________________________________________________</p>";
  					
   		    	echo "<p>&nbsp;</p>";
   		    	echo "<p>&nbsp;</p>";
   		    	echo "<p>&nbsp;</p>";
   		    	echo "<p>&nbsp;</p>";
   		    	echo "<p>&nbsp;</p>";
  					
			   }
			   
   			echo "<table width='100%' border='0'>";    //Encabezado
   			echo "<tr><td width='200' align='left'>";
   			echo "<img src='lib/DuranNvoBk.png' width='187' height='60'>";
   			echo "</td>";
   			echo "<td width='74%' align='center'>$Gfont <strong>Laboratorio Clinico Duran</strong><br>";
   			echo "$Gdir<br>";
   			echo "Recibo de pago de comisiones del periodo $Periodo<br>";
   			echo "</td></tr></table>";
   						   
            echo "<p>$Gfont <strong>Medico: $rg[medico].- $rg[nommedico] &nbsp; INST:_$rg[institucion] &nbsp; Zona: &nbsp; $rg[zonas] &nbsp; $rg[nombrezona]</strong></p>";

            //echo "<hr noshade style='color:3366FF;height:1px'>";
            echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
   			echo "<tr height='20'>";
				echo "<th with='10%' align='center'>$Gfont Inst. </th>";
   			echo "<th with='10%' align='center'>$Gfont Orden</th>";
   			echo "<th with='10%' align='center'>$Gfont Fecha</th>";
   			echo "<th with='10%' align='center'>$Gfont Paciente</th>";
   			echo "<th with='10%' align='center'>$Gfont Estudios</th>";
   			echo "<th with='30%' align='center'>$Gfont Importe</th>";
   			echo "<th with='10%' align='center'>$Gfont Comision</th>";
   			echo "<tr>";

				$Medico   = $rg[medico];
							
				$nImp     = $nCom = 0;
				
				$nRenglon = 5;

			}			
			
         echo "<tr height='20'><td align='right'>$Gfont $rg[inst] &nbsp; </td>";
         echo "<td>$Gfont $rg[orden]</td>";
         echo "<td>$Gfont $rg[fecha]</td>";
         echo "<td>$Gfont $rg[nombrec]</td>";
         echo "<td>$Gfont $rg[concepto]</td>";
         echo "<td align='right'>$Gfont ".number_format($rg[importe],"2")."</td>";
         echo "<td align='right'>$Gfont ".number_format($rg[comision],"2")."</td></tr>";

         $nImp += $rg[importe];
         $nCom += $rg[comision];
         
         $TnImp += $rg[importe];
         $TnCom += $rg[comision];

         $nRenglon++;
		}
		
   }else{		//Mando esto solo para no dejarlo en blanco;
   
     	echo "<table width='100%' height='80' border='0'>";    //Encabezado
   	echo "<tr><td width='26%' height='76'>";
   	echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='61'></p>";
   	echo "</td>";
   	echo "<td width='74%' align='center'>$Gfont <strong>Laboratorio Clinico Duran</strong><br>";
   	echo "$Gdir<br>";
   	echo "Recibo de pago de comisiones del periodo $Periodo<br>";
   	echo "</td></tr></table>";			   
      echo "<p>$Gfont <strong>Medico: $rg[medico].- $rg[nommedico] &nbsp; INST:_$rg[institucion] &nbsp; Zona: &nbsp; $rg[zonas] &nbsp; $rg[nombrezona]</strong></p>";
      //echo "<hr noshade style='color:3366FF;height:1px'>";
      echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
   	echo "<tr>";
		echo "<th with='10%' align='center'>$Gfont Inst. </th>";
   	echo "<th with='10%' align='center'>$Gfont Orden</th>";
   	echo "<th with='10%' align='center'>$Gfont Fecha</th>";
   	echo "<th with='10%' align='center'>$Gfont Paciente</th>";
   	echo "<th with='10%' align='center'>$Gfont Estudios</th>";
   	echo "<th with='30%' align='center'>$Gfont Importe</th>";
   	echo "<th with='10%' align='center'>$Gfont Comision</th>";
   	echo "<tr>";

   }

   $Letra = impletras($nCom," pesos ");

   echo "<tr>";
	echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont $Letra </th>";
   echo "<th with='10%' align='right'>$Gfont Total --------> </th>";
   echo "<th with='30%' align='right'>$Gfont ".number_format($nImp,"2")."</th>";
   echo "<th with='30%' align='right'>$Gfont ".number_format($nCom,"2")."</th>";
   echo "<tr>";
   echo "</table>";	
  	echo "<div align'center'>$Gfont Fecha entrega: ___________________ &nbsp; &nbsp; Quien recibe: _________________________________________ &nbsp; Firma: _______________________ </div>"; 
   echo "<p>$Gfont Comentarios : __________________________________________________________________________________________________________</p>";


	echo "<p>&nbsp;</p>";
   echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
   echo "<tr>";
	echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='right'>$Gfont GRAN TOTAL --------></th>";
   echo "<th with='10%' align='right'>$Gfont </th>";
   echo "<th with='30%' align='right'>$Gfont ".number_format($TnImp,"2")."</th>";
   echo "<th with='30%' align='right'>$Gfont ".number_format($TnCom,"2")."</th>";
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
		  
		  /*	
        echo " &nbsp; ";
        $InsA=mysql_query("SELECT institucion,nombre FROM inst");
        echo "<SELECT name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</SELECT>";
        */
                
        echo " &nbsp; Medico, * todos: ";
        echo "<INPUT TYPE='TEXT'  name='Medico' size='4' value ='*'> &nbsp; ";
        /*
        echo "Medicos[Activo/Inactivo] : ";
        echo "<SELECT name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</SELECT>";
		*/
        echo "&nbsp; &nbsp; <INPUT TYPE='SUBMIT' value='Enviar'>";

    echo "</form>";
   
   
	
   echo "<br>";


   echo "<div align='center'>";
   echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
   echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
   echo "</div>";

   echo "<div align='left'>";
   //echo "<form name='form1' method='post' action='pidedatos.php?cRep=4&fechas=1&FecI=$FecI&FecF=$FecF'>";
   echo "<form name='form1' method='post' action='menu.php'>";
   echo "         <input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
   echo "   </form>";
   echo "</div>";

echo "</body>";

echo "</html>";

mysql_close();




?>