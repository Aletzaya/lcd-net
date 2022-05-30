<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link         = conectarse();

  #Saco los valores de las sessiones los cuales no cambian;
  $Gusr = $_SESSION["Usr"][0];
  $Gcia = $_SESSION["Usr"][1];
  $Gnomcia = $_SESSION["Usr"][2];
  $Gnivel = $_SESSION["Usr"][3];
  $Gteam = $_SESSION["Usr"][4];
  $Gmenu = $_SESSION["Usr"][5];

  require_once './Services/MensajesServices.php';

  require ("config.php");

  if(!isset($_REQUEST[Codigo])){

    $Codigo    = '11111';

    if(!isset($_REQUEST[Orden])){

      $Orden = '12345';

    }else{

      $Orden = $_REQUEST[Orden];

    }

    $Estudio = $_REQUEST[Estudio];

  }else{ 

    $Codigo    = $_REQUEST[Codigo];

    $encuentra1= strpos($Codigo, '-');
    $encuentra2= strpos($Codigo, '=');
    $encuentra3= substr($Codigo, $encuentra2+1,$encuentra1-2); //Orden
    $Orden        = $encuentra3; 
    
    $encuentra4= strrpos($Codigo, '=');
    $encuentra5= strlen($Codigo);
    $encuentra6= substr($Codigo, $encuentra4+1, $encuentra5); //Estudio
    $Estudio      = $encuentra6;

  }

  if(!isset($_REQUEST[Op])){

    $Op = 2;

  }else{

    $Op = $_REQUEST[Op];

  }

  $Recibeencaja = $Gusr;
  $Usr       = $Gusr;
  $Fechaest  = date("Y-m-d H:i:s");
  $Msj = "- -";

  if(strlen($Orden)>4 AND strlen($Estudio)>0){

    $Fecha = date("Y-m-d");

    $Hora  = date("H:i");

    $NumA  = mysql_query("SELECT otd.estudio 
    FROM otd 
    WHERE otd.orden='$Orden' AND mid(otd.cinco,1,4)='0000'");

    $OtdA  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.cinco,otd.recibeencaja
    FROM ot,cli,otd 
    WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio'");
			   
    if($Op=='1' and $Estudio=='TODOS'){

      $OtdA2  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.cinco,otd.recibeencaja
      FROM ot,cli,otd 
      WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

		  if($Otd   = mysql_fetch_array($OtdA2)){
		  	 
        if(substr($Otd[cinco],0,4)){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
			
				  if ( $Otd[recibeencaja]=' '){
	  	
            $Up  = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Recibeencaja' 
            WHERE orden='$Orden' and recibeencaja=' '"); 

            $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");


            $Msj = "Estudio actualizado con exito!!!";

				  }
		  
        }else{

          $Up  = mysql_query("UPDATE otd SET lugar = '6', recibeencaja='$Recibeencaja' WHERE orden='$Orden'");   
          $Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
        	
        }

	  	}

    }elseif($Op=='2'){
		
		  if($Otd   = mysql_fetch_array($OtdA)){

        if(substr($Otd[cinco],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
	  	
          $Up  = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Recibeencaja' 
          WHERE orden='$Orden' AND estudio='$Estudio'"); 

          $Msj = "Estudio Registrado con exito!!!";
		  
     /*  		 }else{

           		$Up  = mysql_query("UPDATE otd SET lugar = '6', recibeencaja='$Recibeencaja' WHERE orden='$Orden' AND estudio='$Estudio'");   
           		$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion'; */
        }
			
		  }
		
      if(mysql_num_rows($NumA)==1){
        	  
        $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");

      }

    }elseif($Op=='res'){
		
  		$Up  = mysql_query("UPDATE otd SET cinco = '0000-00-00 00:00:00', lugar='5',recibeencaja='' 
  		   WHERE orden='$Orden' AND estudio='$Estudio'"); 
  		$Msj = 'Entrega de Estudio $Estudio RESTAURADA';
   	    $lUp2  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
  		('$Fechaest','$Usr','Entrega RESTAURADA Est: $Estudio Ot: $Orden')");
   	    $lUp3 = mysql_query("UPDATE ot SET encaja='No' WHERE orden='$Orden'");

      $Msj = "Estudio Restaurado con exito!!!";

    }
	
  }else{
  
    $Msj='El estudio de la Orden NO existe';	
  
  }
                
  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='2'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";
          
  echo "<html>";

?>

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Codigo.focus();

}

</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Entrega de Resultados LCD-NET ::..</title>
            <script type='text/javascript' src='ckeditor/ckeditor.js'></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1" onload='cFocus()'>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="lib/DuranNvoBk.png" width="230" height="90"></img></td>
                <td style="background-image:url(images/BarraVdeGrad.jpg)">
                    <h3 style="font-family: cursive;color: #2C3E50;" align="center" >Entrega de Resultados LCD-NET</h3>
                </td>
            </tr>
        </table>
        <br></br>



<?php

echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

echo "$Gfont2 <p align='center'><font size='3'><b>Registro de Estudios en Recepcion / E-mail</font></b></p>";

$aLug = array('Etiqueta','Etiqueta','Proceso','Captura','Impresion','Recepcion','Entregado');

$HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.observaciones,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.pagada FROM ot,cli
WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

$He   = mysql_fetch_array($HeA);  

$OtdC  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.mail
      FROM ot,cli
      WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");            

$rgp=mysql_fetch_array($OtdC);

$OtdM  = mysql_query("SELECT med.nombrec,med.mail
      FROM ot,med
      WHERE ot.orden='$Orden' AND ot.medico=med.medico");            

$rgm=mysql_fetch_array($OtdM);

$OtdI  = mysql_query("SELECT inst.nombre,inst.mail
      FROM ot,inst
      WHERE ot.orden='$Orden' AND ot.institucion=inst.institucion");            

$rgi=mysql_fetch_array($OtdI);


if($He[entemailpac]=='1' or $He[entemailmed]=='1' or $He[entemailinst]=='1'){ 

  if($He[entemailpac]=='1'){ 

    $HeC  = mysql_query("SELECT cli.mail FROM ot,cli
    WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");
     
    $Hecli   = mysql_fetch_array($HeC);

    $correop=" <b>Paciente: </b>".$Hecli[mail];

  }else{

      $correop="";

  }

  if($He[entemailmed]=='1'){

      $Hem  = mysql_query("SELECT med.mail FROM ot,med
       WHERE ot.orden='$Orden' AND ot.medico=med.medico");
               
      $Hemed   = mysql_fetch_array($Hem);

      $correom=" - <b>Medico: </b>".$Hemed[mail];

  }else{

      $correom="";

  }

  if($He[entemailinst]=='1'){

    $Hei  = mysql_query("SELECT inst.mail FROM ot,inst
     WHERE ot.orden='$Orden' AND ot.institucion=inst.institucion");
             
    $Heinst   = mysql_fetch_array($Hei);

    $correoi=" - <b>Institucion: </b>".$Heinst[mail];

  }else{

    $correoi="";

  }
                
  $correo= " <img src='lib/email.png' border='0'> Enviar por correo  -->".$correop."  ".$correom."  ".$correoi; 

}else{

    $correo= " "; 

}
     		
if($He[pagada]=='Si'){

  $StatusEco='PAGADA';

}elseif($He[pagada]=='No'){

  $StatusEco='C/ADEUDO';

}

echo "<div align='left'> &nbsp; <font size='3'><b>$He[institucion] - $Orden  &nbsp; $He[nombrec]</b></font> <font size='2'> &nbsp; Fecha de captura: $He[fecha] Hra: $He[hora] &nbsp; - &nbsp; Fecha de entrega: $He[fechae]</font> &nbsp; <font size='3' color='red'><b> - $StatusEco - </b></font></div>";
echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";
echo "<tr height='15' bgcolor='#CCCCCC'>";
echo "<td align='center'>$Gfont2 </td>";
echo "<td align='center'>$Gfont2 Paciente</td>";
echo "<td align='center'>$Gfont2 Medico</td>";
echo "<td align='center'>$Gfont2 Institucion</td>";
echo "<td align='center'>$Gfont2 Otro</td>";
echo "</tr>";
echo "<tr height='15'>";
echo "<td align='center' width='8%'>$Gfont2 <font size='2'><b> E-mail: </b></font></td>";
echo "<td align='center' width='23%'>$Gfont2 <b> Paciente </font></td>";
//echo "<form name='form2' method='post' action='entregamail2.php?Orden=$Orden&Op=actpac'><td align='center' width='45'>$Gfont2 <font size='1'><input name='mailpac' value='$rgp[mail]' type='email' size='60'></font></td></form>";
echo "<td align='center' width='23%'>$Gfont2 <b> $rgm[mail] </font></td>";
echo "<td align='center' width='23%'>$Gfont2 <b> $rgi[mail] </font></td>";
echo "<td align='center' width='23%'>$Gfont2 <b> Otro </font></td>";
echo "</tr>";          
echo "</table>";
echo "<br />";

$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est 
WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";
echo "<tr height='25' bgcolor='#CCCCCC'>";
echo "<td align='center'>$Gfont2 Estudio</td>";
echo "<td align='center'>$Gfont2 Descripcion</td>";
echo "<td align='center'>$Gfont2 Lugar</td>";   
echo "<td align='center'>$Gfont2 Recepcion</td>";   
echo "<td align='center'>$Gfont2 Fecha/hora</td>";
echo "<td align='center'>$Gfont2 Recibio</td>";  

if($Usr=='nazario' or $Usr=='MARYLIN'){

  echo "<td align='center'>&nbsp;</td>"; 

}
  
  echo "</tr>";              

  while($rg=mysql_fetch_array($Sql)){
        
    $Lugar = $rg[lugar];  

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
    echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
    echo "<td align='center'>$Gfont <font size='1'><b> $rg[estudio]</b></td>";
    echo "<td>$Gfont <font size='1'><b> $rg[descripcion]</b></td>";
    echo "<td align='center'>$Gfont &nbsp; $aLug[$Lugar]</td>";			

		if($Lugar == '6'){

       echo "<td align='center'>$Gfont &nbsp; <img src='lib/slc.png'></td>";

    }else{

       echo "<td align='center'>$Gfont <font size='1'>&nbsp; <a class='pg' href='ordenrecest.php?Op=2&Orden=$Orden&Estudio=$rg[estudio]'>Entregar</a></td>";

    }   

    echo "<td align='center'>$Gfont <font size='2'>$rg[cinco]</td>";
    echo "<td align='center'>$Gfont <font size='2'>$rg[recibeencaja]</td>";

    if($Usr=='nazario' or $Usr=='MARYLIN' or $Usr=='Andrea'){

      echo "<td align='center'>$Gfont2 <font size='1'>&nbsp; <a class='pg' href='ordenrecest.php?Op=res&Orden=$Orden&Estudio=$rg[estudio]'>***RESTAURAR</a></td>";

    }

    echo "</tr>";
    $nRng++;

  }       

  echo "</table>";
  echo "<br />";
  echo "<div align='left'> &nbsp; <b>Observaciones: $He[observaciones]</b></div>";
  echo "<br />";
  echo "<div align='left'> ".$correo."</b></div>";
    
  while($nRng<=4){  
      echo "<div align='center'>$Gfont &nbsp; </div>";
      $nRng++;
  }    

echo "&nbsp Codigo de Barras: <input type='TEXT' name='Codigo' size='30' value=''> &nbsp; &nbsp; ";
// echo "<input type='submit' name='boton' value='Enviar'>";

echo "</form>";         
  	                
echo "<br />";
echo "<br />";

echo "<div align='center'><b>Mensaje: $Msj <b></div>";
  
  
mysql_close();
  
?>
</body>
<script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>

