<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta p�gina.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
  $Recepcionista=$_REQUEST[Recepcionista];
 // $Medico=$_REQUEST[Medico];
  
   $filtro     =   $_REQUEST[Medico];
   $filtro3     =   $_REQUEST[Sucursal];
   $filtro5     =   $_REQUEST[Institucion];

    if($filtro3<>'*'){
        $filtro4="and ot.suc='$filtro3'";
    }else{
        $filtro4=" ";
    }

    if($filtro5<>'*'){
        $filtro6="and ot.institucion='$filtro5'";
    }else{
        $filtro6=" ";
    }


    if($filtro<>'*'){
        if($filtro<>'MR'){
            $filtro2="and ot.medico='$filtro'";
        }else{
            $filtro2="and (ot.medico<>'AQ' and ot.medico<>'MD')";
        }
    }else{
        $filtro2=" ";
    }

  $cSql2="SELECT ot.orden,ot.recepcionista,ot.importe as importeot,ot.fecha as fechaot,ot.hora as horaorden,ot.suc,ot.institucion,ot.cliente,ot.medico,ot.medicon,cli.nombrec as paciente,inst.alias as alias,med.nombrec as nombremed
  FROM ot,cli,inst,med
  WHERE ot.fecha>='$FecI' and ot.fecha<='$FecF' and ot.recepcionista='$Recepcionista' and ot.cliente=cli.cliente and ot.institucion=inst.institucion and ot.medico=med.medico $filtro4 $filtro6 $filtro2
  ORDER BY ot.orden,ot.fecha,ot.hora";

$UpA2=mysql_query($cSql2,$link);


  require ("config.php");

?>
          
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Recepcionista</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body>

    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 0px solid #999;'>
      <tr bgcolor="#2c8e3c">
          <td class='letratitulo' align='center' colspan="6">..:: Detalle de Captura ::..</i></a></td>
      </tr>
      <tr>
          <td class='letrap'><font size='+2'>Recepcionista : <?= $Recepcionista ?></font></td>
      </tr>
      <tr>
          <td class='letrap'>Relacion de ordenes del <?= $FecI ?> al <?= $FecF ?> </td>
      </tr>
      </table>
      <hr noshade style='color:3366FF;height:1px'>

<?php

echo "<table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

echo "<tr height='25'>";
echo "<td align='center' class='letrap'><b>Suc.</b></td>";
echo "<td align='center' class='letrap'><b>Inst.</b></td>";   
echo "<td align='center' class='letrap'><b>Orden</b></td>";
echo "<td align='center' class='letrap'><b>Fecha</b></td>";  
echo "<td align='center' class='letrap'><b>Hora</b></td>";  
echo "<td align='center' class='letrap'><b>Paciente</b></td>";  
echo "<td align='center' class='letrap'><b>Estudios</b></td>";  
echo "<td align='center' class='letrap'><b>Importe</b></td>";  
echo "<td align='center' class='letrap'><b>Medico</b></td>";  
echo "</tr>";  

$ContadorTEst=1;
$Contador=0;

while ($registro=mysql_fetch_array($UpA2)){

        $Institucion=$registro[institucion];
        $nombrei=$registro[alias];
        $Orden=$registro[orden];
        $Suc=$registro[suc];
        $Paciente=$registro[paciente];
        $Fecha=$registro[fechaot];
        $Hora=substr($registro[horaorden],0,5);
        $clavemed=$registro[medico];

        if($registro[medico]=='MD'){
            $nombrem=$registro[medicon];
        }else{
            $nombrem=$registro[nombremed];
        }

        if($registro[suc]=='0'){
            $Nsucursal='Adm';
        }elseif($registro[suc]=='1'){
            $Nsucursal='Matriz';
        }elseif($registro[suc]=='2'){
            $Nsucursal='OHF';
        }elseif($registro[suc]=='3'){
            $Nsucursal='Tepexpan';
        }elseif($registro[suc]=='4'){
            $Nsucursal='Reyes';
        }elseif($registro[suc]=='5'){
            $Nsucursal='Camarones';
        }elseif($registro[suc]=='6'){
            $Nsucursal='San Vicente';
        }

        if (($nRng % 2) > 0) {    $Fdo = 'FFFFFF';  } else {    $Fdo = $Gfdogrid;  }    //El resto de la division;

        echo "<tr height='25' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        echo "<td class='letrap'><font size='1'>".$Suc." - ".$Nsucursal."</font></td>";
        echo "<td class='letrap'><font size='1'>".$Institucion." - ".$nombrei."</font></td>";
        echo "<td class='letrap'><font size='1'>".$Orden."</font></td>";
        echo "<td class='letrap'><font size='1'>".$Fecha."</font></td>";
        echo "<td class='letrap'><font size='1'>".$Hora."</font></td>";
        echo "<td class='letrap'><font size='1'> &nbsp; ".$Paciente."</font></td>";

        $cSqlb=mysql_query("select otd.orden,otd.estudio,otd.precio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe from otd
        where
        otd.orden=$registro[orden] order by otd.estudio",$link);

        while ($registro2=mysql_fetch_array($cSqlb)){
            
            if($registro2[descuento]>0){
                $DESCTO="(DESCTO)";
            }else{
                $DESCTO=" ";
            }
            //$Importe=$registro2[importe];
            $Estudios=$DESCTO." ".$registro2[estudio].", ".$Estudios;
            $Importe+=$registro2[importe];
            $ContadorTEst+=$ContadorTEst;
            $nRng2++;

        }
        
        $nRng++;

        echo "<td class='letrap'><font size='1'>".$Estudios."</font></td>";
        echo "<td class='letrap' align='right'><font size='1'>".number_format($Importe,'2')." &nbsp; </font></td>";
        echo "<td class='letrap'><font size='1'> &nbsp; ".$clavemed." - ".$nombrem."</font></td>";
        echo "</tr>";

        $ImporteM+=$Importe;
        $IngresoM+=$Ingreso;
        $Contador=$Contador+1;
        $Estudios=$registro[estudio];

        $Importe=$registro2[importe];
        $Suc=$registro[suc];
        $Orden=$registro[orden];
        $Paciente=$registro[nombrec];
        $Fecha=$registro[fecha];
        
}

echo "<tr height='25'>";
echo "<td align='center' class='letrap' colspan='3'><b>No. de Ordenes. ".number_format($Contador)."</b></td>";
echo "<td align='center' class='letrap' colspan='3'><b>No. de Estudios. ".number_format($nRng2)."</b></td>";   
echo "<td align='right' class='letrap' colspan='2'><b>".number_format($ImporteM,'2')."</b></td>";  
echo "<td align='center' class='letrap'><b></b></td>";  
echo "</tr>";

 echo "</table>";
 echo "<hr noshade style='color:3366FF;height:1px'>";
    echo "<br>";
    echo "<br>";

?>
</body>
</html>
<?php
mysql_close();
?>