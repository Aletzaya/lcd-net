<?php

  session_start();


  session_start();


  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  
  require("lib/lib.php");
  
  require ("config.php");	

  $link=conectarse();
  
  $FechaI    = $_REQUEST[FechaI];
  $FechaF    = $_REQUEST[FechaF];
  $Pagos     = $_REQUEST[Pagos];
  $Usr       = $_REQUEST[Usr];
  $Fpago     = $_REQUEST[Fpago];
  $Cancelado = $_REQUEST[Cancelado];
  
  $Titulo  = "Pagos del $FechaI al $FechaF";   
  $Fecha=date("Y-m-d H:i");
  ?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Reporte de Gastos</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>
  
  
  
  <?php

  $Sucursal     =   $_REQUEST[Sucursal];
  //$Sucursal     =   $Sucursal[0];
  $sucursalt = $_REQUEST[sucursalt];
  $sucursal0 = $_REQUEST[sucursal0];
  $sucursal1 = $_REQUEST[sucursal1];
  $sucursal2 = $_REQUEST[sucursal2];
  $sucursal3 = $_REQUEST[sucursal3];
  $sucursal4 = $_REQUEST[sucursal4];
  $sucursal5 = $_REQUEST[sucursal5];
  $sucursal6 = $_REQUEST[sucursal6];

    $Sucursal= "";
  
  if($sucursalt=="1"){  
  
    $Sucursal="dpag_ref.suc<>9";
    $Sucursal2= " * - Todas ";
  }else{
  
    if($sucursal0=="1"){  
      $Sucursal= " dpag_ref.suc=0";
      $Sucursal2= "Administracion - ";
    }
    
    if($sucursal1=="1"){ 
      $Sucursal2= $Sucursal2 . "Matriz - "; 
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=1";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=1";
      }
    }
    
    if($sucursal2=="1"){
      $Sucursal2= $Sucursal2 . "Hospital Futura - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=2";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=2";
      }
    }
    
    if($sucursal3=="1"){
      $Sucursal2= $Sucursal2 . "Tepexpan - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=3";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=3";
      }
    }
    
    if($sucursal4=="1"){
      $Sucursal2= $Sucursal2 . "Los Reyes - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=4";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=4";
      }
    }    

    if($sucursal5=="1"){
      $Sucursal2= $Sucursal2 . "Camrones - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=5";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=5";
      }
    }
    if($sucursal6=="1"){
      $Sucursal2= $Sucursal2 . "San Vicente - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=6";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=6";
      }
    }
  }

  $cSql ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.cerrada,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
          . "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
          . "FROM dpag_ref "
          . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
          . "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
          . "WHERE cptpagod.referencia LIKE '%$Pagos%' "
          . "AND date(dpag_ref.fechapago)>='$FechaI' "
          . "AND date(dpag_ref.fechapago)<='$FechaF' "
          . "AND dpag_ref.usr LIKE '%$Usr%' "
          . "AND dpag_ref.tipopago LIKE '%$Fpago%'"
          . "AND dpag_ref.cancelada LIKE '%$_REQUEST[Cancelado]%'"
          . "AND dpag_ref.cerrada='1'"

          . "AND ($Sucursal)";

  //echo $cSql;
  $sql = mysql_query($cSql);

  ?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>
    <td width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha"; ?><br>
        <?php echo "$Titulo"; ?>&nbsp;</div></td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php





echo "<table width='95%' align='center' border='0' cellpadding='1' cellspacing='2' class='content_txt'>";                                
      //echo "<tr class='textosItalicos' bgcolor='#cccccc'>";              
      echo "<tr height='21' background='lib/bartit.gif'>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Suc</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Id</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Referencia</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Cuenta</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Tipo P.</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Recibe</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Autoriza</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Usr</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Hospi</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Concepto</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
      echo "<th align='center'><font size='2' face='Arial, Helvetica, sans-serif'>cerrada</font></th>";

      echo "</tr>"; 	

while($rg=mysql_fetch_array($sql)){          

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;
            
       
          
            if($rg[cerrada]=='0'){
              $cerrada='Abierta';
          }elseif($rg[cerrada]=='1'){
              $cerrada='cerrada';
        
          }
          
            echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'> $rg[suc] </font></td>"; 
            echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'> $rg[id] </font></td>"; 
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'> $rg[referencia] </font></td>";	
            echo "<td align='right'><font size='2' face='Arial, Helvetica, sans-serif'> $rg[cuenta] </font></td>";
            echo "<td align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[fechapago] &nbsp; </font></td>";	
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[concepto] &nbsp; </font></td>";
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'>".ucwords($rg[recibe])." &nbsp; </font></td>";
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[autoriza] &nbsp; </font></td>";
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[usr] &nbsp; </font></td>";
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[hospi] &nbsp; </font></td>";
            echo "<td align='left'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[concept] &nbsp; </font></td>";
            echo "<td align='right'><font size='2' face='Arial, Helvetica, sans-serif'>".number_format($rg[monto],2)." &nbsp; </font></td>";	
            echo "<td align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$cerrada &nbsp;</font></td>";	

            echo "</tr>";            
            $nImporte        += $rg[monto];              
            $nRng ++;                        
      }      
      echo "<tr class='content_txt' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
      echo "<td align='left'>  </td>";  
      echo "<td align='left'>  </td>";  
      echo "<td align='left'>  </td>";
      echo "<td align='left'>  </td>";
      echo "<td align='left'>  </td>";
      echo "<td align='left'>  </td>";
      echo "<td align='left'>  </td>";
      echo "<td align='left'>  </td>";
      echo "<td align='left'>  </td>";	
      echo "<td align='left'>  </td>";
      echo "<td align='right' bgcolor='$Fdo'><b><font size='2' face='Arial, Helvetica, sans-serif'> Total</b>&nbsp; </font></td>";	
      echo "<td align='right' bgcolor='$Fdo'><b><font size='2' face='Arial, Helvetica, sans-serif'>".number_format($nImporte,2)." </font></b>&nbsp; </td>";	
      echo "</tr>";
echo "</table>";
echo "</table>";

echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=16&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('detpagospdf.php?FechaI=$FechaI&FechaF=$FechaF&Pagos=$Pagos&Fpago=$Fpago&Usr=$Usr&Cancelado=$Cancelado&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";



 echo "</form>";
 echo "</div>";

?>
</body>
</html>
<?php
mysql_close();
?>
