<?php

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
  
  $Titulo  = "Pagos del $FechaI al $FechaF";   
  $Fecha=date("Y-m-d H:i");


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
      $Sucursal2= $Sucursal2 . "Los Reyes - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=5";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=5";
      }
    }
    if($sucursal6=="1"){
      $Sucursal2= $Sucursal2 . "Los Reyes - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=6";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=6";
      }
    }
  }

  $cSql ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,dpag_ref.suc, "
          . "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,cptpago.pago,cptpagod.id_nvo,dpag_ref.orden_h "
          . "FROM dpag_ref "
          . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
          . "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
          . "LEFT JOIN cptpago ON cptpagod.id_nvo=cptpago.id "
          . "WHERE cptpagod.referencia LIKE '%$Pagos%' "
          . "AND date(dpag_ref.fechapago)>='$FechaI' "
          . "AND date(dpag_ref.fechapago)<='$FechaF' "
          . "AND dpag_ref.usr LIKE '%$Usr%' "
          . "AND dpag_ref.tipopago LIKE '%$Fpago%'"
          . "AND dpag_ref.cancelada LIKE '%$_REQUEST[Cancelado]%'"
          . "AND ($Sucursal) order by cptpagod.cuenta,cptpagod.id";

  //echo $cSql;
  $sql = mysql_query($cSql);

require ("config.php");



?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Reporte de resuemen de Gastos</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

  </head>
  <body>


<!-----
  <script type="text/javascript">
    function ocultar1(){
        document.getElementById('sub1').style.display="none";
    }

    function mostrar1(){
        document.getElementById('sub1').style.display="block";
    }

    function ocultar2(){
        document.getElementById('sub2').style.display="none";
    }

    function mostrar2(){
        document.getElementById('sub2').style.display="block";
    } 

    function ocultar3(){
        document.getElementById('sub3').style.display="none";
    }

    function mostrar3(){
        document.getElementById('sub3').style.display="block";
    }

    function ocultar4(){
        document.getElementById('sub4').style.display="none";
    }

    function mostrar4(){
        document.getElementById('sub4').style.display="block";
    } 

    function ocultar5(){
        document.getElementById('sub5').style.display="none";
    }

    function mostrar5(){
        document.getElementById('sub5').style.display="block";
    }

    function ocultar6(){
        document.getElementById('sub6').style.display="none";
    }

    function mostrar6(){
        document.getElementById('sub6').style.display="block";
    } 
    
    function ocultar7(){
        document.getElementById('sub7').style.display="none";
    }

    function mostrar7(){
        document.getElementById('sub7').style.display="block";
    }

    function ocultar8(){
        document.getElementById('sub8').style.display="none";
    }

    function mostrar8(){
        document.getElementById('sub8').style.display="block";
    }
  </script>
------>

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


                               
      //echo "<tr class='textosItalicos' bgcolor='#cccccc'>";              
      $idconcepto='';
      $nImporte=0;
      $cuenta = 0;
      $id = 0;
      $Gfont="<font size='2' face='Arial, Helvetica, sans-serif'>";
      $Gfont3="<font size='2' face='Arial, Helvetica, sans-serif'>";
      $Gfont4="<font size='2' face='Arial, Helvetica, sans-serif'>";

/*  ?>
  
    <a href="javascript:document.getElementById('sub').style.display='none';void0">Ocultar</a>&nbsp;

    <a href="javascript:document.getElementById('sub').style.display='block';void0">Mostrar</a>&nbsp;

  <?php 
*/
while($rg=mysql_fetch_array($sql)){  


            if($cuenta<>$rg[cuenta]){
              
              if($id<>0){

                
                echo "<tr>";
                echo "<td align='center' width='3%' colspan='8'>$Gfont3 &nbsp;</font></td>";
                echo "<td align='right' bgcolor='#d5dbdb' width='85%'>$Gfont4 <b> SubTotal: &nbsp; </b></font></td>";
                echo "<td align='right' bgcolor='#d5dbdb'>$Gfont4 <b>".number_format($nImportecta,2)." &nbsp; <b></font></td>"; 
                echo "</tr>"; 

                $nImportecta=0;  

              }


            if($idconcepto<>$rg[id_nvo]){

              if($nImporte<>0){

                echo "<table width='95%' align='center' border='0' cellpadding='0' cellspacing='0' class='content_txt'>";

                echo "<tr>";
                echo "<td align='center' colspan='8'>$Gfont3 </font></td>";
                echo "<td align='right' bgcolor='#a2b2de' width='85%'>$Gfont4 <b> Total: &nbsp; </b></font></td>";
                echo "<td align='right' bgcolor='#a2b2de'>$Gfont4 <b>".number_format($nImporte,2)." &nbsp; <b></font></td>"; 
                echo "</tr>"; 
                echo "<tr><td colspan='11'> &nbsp; </td></tr>"; 
                echo "</table>";

                $nImporte=0;    
           
              }

              //ENCABEZADO
              echo "<table width='95%' align='center' border='0' cellpadding='0' cellspacing='0' class='content_txt'>";

               echo "<tr id='sub".$idconcepto."'> <td width='7%'>";

                ?> 
                  <a href="javascript:document.getElementById('<?php echo "sub".$rg[id_nvo]; ?>').style.display='block';void0"><img src='lib/down_over.gif' border='0' width='15'></a>
                <?php 

              echo " &nbsp; </td></tr>";

              echo "<tr bgcolor='#a2b2de'>";
              echo "<td width='7%'>";
              echo " &nbsp; </td>";

              echo "<td align='center' colspan='10' id='sub".$idconcepto."'>";
              echo "$Gfont4 <b>$rg[id_nvo] - $rg[pago] </b></font></td>";
              echo "</tr>";
              echo "</table>";

              $idconcepto=$rg[id_nvo];        
                echo "<table width='95%' align='center' border='0' cellpadding='0' cellspacing='0' style='display:none;' id='sub".$idconcepto."'>";
             
            }

              //SUBENCABEZADO

               echo "<tr> <td width='7%'>";

                ?> 
                  <a href="javascript:document.getElementById('<?php echo "sub".$rg[id_nvo]; ?>').style.display='none';void0"><img src='lib/up.gif' border='0' width='15'></a>
                <?php 

              echo " &nbsp; </td></tr>";

                echo "<tr bgcolor='#d5dbdb'>";
                echo "<td align='left' colspan='10'>$Gfont <b> $rg[id] - $rg[cuenta] --> $rg[referencia] </b></font></td>";
                echo "</tr>";   
                echo "<tr bgcolor='#d5f5e3'>";
                echo "<td align='center' colspan='2' width='3%'>$Gfont <b>Suc - Ord</b></font></td>";  
                echo "<td align='center' width='12%'>$Gfont <b>Fecha</b></font></td>";
                echo "<td align='center' width='8%'>$Gfont <b>Folio Gasto</b></font></td>";
                echo "<td align='center' width='8%'>$Gfont <b>Tipo P.</b></font></td>";
                echo "<td align='center' width='15%'>$Gfont <b>Recibe</b></font></td>";
                echo "<td align='center' width='15%'>$Gfont <b>Autoriza</b></font></td>";
                echo "<td align='center' width='15%'>$Gfont <b>Usr</b></font></td>";
                echo "<td align='center' width='3%'>$Gfont <b>Lab</b></font></td>";
                echo "<td align='center' width='22%'>$Gfont <b>Concepto</b></font></td>";
                echo "<td align='center' width='10%'>$Gfont <b>Importe</b></font></td>";
                echo "</tr>";  

            }

              if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;


              echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
              echo "<td align='center' colspan='2' width='3%'><a style='text-decoration:none' href=javascript:winuni('pagosdet.php?Id=$rg[id]')><b>$rg[suc]&nbsp; - $rg[orden_h]&nbsp;</b>  </font></a></td>";  
              echo "<td align='center' width='12%'>$Gfont4 $rg[fechapago] &nbsp; </font></td>"; 
              echo "<td align='center' width='8%'>$Gfont4 $rg[id] &nbsp; </font></td>"; 
              echo "<td align='center' width='8%'>$Gfont4 $rg[concepto] &nbsp; </font></td>";
              echo "<td align='left' width='15%'>$Gfont4".ucwords($rg[recibe])." &nbsp; </font></td>";
              echo "<td align='left' width='15%'>$Gfont4 $rg[autoriza] &nbsp; </font></td>";
              echo "<td align='left' width='15%'>$Gfont4 $rg[usr] &nbsp; </font></td>";
              echo "<td align='center' width='3%'>$Gfont4 $rg[hospi] &nbsp; </font></td>";
              echo "<td align='left' width='22%'>$Gfont4 $rg[concept] &nbsp; </font></td>";
              echo "<td align='right' width='10%'>$Gfont4".number_format($rg[monto],2)." &nbsp; </font></td>";  
              echo "</tr>";  
 
            $nImporte += $rg[monto];              
            $nImportes += $rg[monto];  
            $nImportecta += $rg[monto];  
            $cuenta =  $rg[cuenta];         
            $id =  $rg[id];         
            $nRng ++;                      
            $idconcepto=$rg[id_nvo];        
            $concepto=$rg[pago];        
  
      }    
      

      echo "<tr>";
      echo "<td align='center' colspan='8'>$Gfont3 &nbsp;</font></td>";
      echo "<td align='right' bgcolor='#d5dbdb' width='70%'>$Gfont4 <b> SubTotal: &nbsp; </b></font></td>";
      echo "<td align='right' bgcolor='#d5dbdb' width='30%'>$Gfont4 <b>".number_format($nImportecta,2)." &nbsp; <b></font></td>"; 
      echo "</tr>"; 
      echo "</table>";

                echo "<table width='95%' align='center' border='0' cellpadding='0' cellspacing='0' class='content_txt'>";

                echo "<tr>";
                echo "<td align='center' colspan='9'>$Gfont3 </font></td>";
                echo "<td align='right' bgcolor='#a2b2de' width='85%'>$Gfont4 <b> Total: &nbsp; </b></font></td>";
                echo "<td align='right' bgcolor='#a2b2de'>$Gfont4 <b>".number_format($nImporte,2)." &nbsp; <b></font></td>"; 
                echo "</tr>"; 
                echo "<tr><td colspan='11'> &nbsp; </td></tr>"; 

      echo "<tr>";
      echo "<td align='center' colspan='8'>$Gfont3 </font></td>";
      echo "<td align='right' bgcolor='#a2b2de' colspan='2' width='85%'>$Gfont4 <b> Total General: &nbsp; </b></font></td>";
      echo "<td align='right' bgcolor='#a2b2de'>$Gfont4 <b>".number_format($nImportes,2)." &nbsp; <b></font></td>"; 
      echo "</tr>"; 

echo "</table>";



echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=17&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

?>
</font>
<div align="center">
<form name="form1" method="post" action="pidedatos.php?cRep=42&fechas=1&FecI=$FecI&FecF=$FecF">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
<?php
mysql_close();
?>
