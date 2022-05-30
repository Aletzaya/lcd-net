<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $capt=$_REQUEST[usuario];

  $FechaI   =   $_REQUEST[FecI];

  $FechaF   =   $_REQUEST[FecF];  

  $filtro3 = $_REQUEST[Sucursal];

  $filtro7 = $_REQUEST[Depto];

  $filtro5 = $_REQUEST[Institucion];

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

if($filtro7<>'*'){
    $filtro8="and est.depto='$filtro7'";
 }else{
    $filtro8=" ";
 }

if($capt=='SIN_REGISTRO'){
    $capt='';
}else{
    $capt=$capt;
}

if (!isset($_REQUEST[Opcion])) {
    $Opcion     = 'Detalle';
} else {
    $Opcion     = $_REQUEST[Opcion];
}

$SqlB3 = "SELECT ot.orden
FROM ot
where ot.fecha='$FechaI' order by hora limit 1";

$Sql3 = mysql_query($SqlB3);

$S3 = mysql_fetch_array($Sql3);


$SqlB4 = "SELECT ot.orden
FROM ot
where ot.fecha='$FechaF' order by orden desc limit 1";
//echo $SqlB4;
$Sql4 = mysql_query($SqlB4);

$S4 = mysql_fetch_array($Sql4);

if($Opcion=='Detalle'){

    $cSql2="SELECT otd.usrest,ot.fecha as fechaot,ot.hora as horaorden,ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,ot.cliente,cli.nombrec as paciente,inst.alias as alias,est.descripcion
    from ot
    INNER JOIN otd on otd.orden=ot.orden
    INNER JOIN est on est.estudio=otd.estudio
    INNER JOIN cli on ot.cliente=cli.cliente
    INNER JOIN inst on ot.institucion=inst.institucion
    WHERE otd.usrest='$capt' and ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $filtro4 $filtro6 $filtro8
    order by otd.orden";

}else{

    $cSql2="SELECT otd.usrest,ot.fecha as fechaot,ot.hora as horaorden,ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,ot.cliente,cli.nombrec as paciente,inst.alias as alias,est.descripcion,count(*) as cant,est.subdepto
    from ot
    INNER JOIN otd on otd.orden=ot.orden
    INNER JOIN est on est.estudio=otd.estudio
    INNER JOIN cli on ot.cliente=cli.cliente
    INNER JOIN inst on ot.institucion=inst.institucion
    WHERE otd.usrest='$capt' and ot.orden>='$S3[orden]' and ot.orden<='$S4[orden]' $filtro4 $filtro6 $filtro8
    GROUP BY otd.estudio order by est.depto,est.subdepto,otd.estudio";

}

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
          <td class='letratitulo' align='center' colspan="6">..:: Detalle de Atencion ::..</i></a></td>
      </tr>
      <tr>
          <td class='letrap'><font size='+2'>Realiza Toma o Estudio: <?= $_REQUEST[usuario] ?></font></td>
      </tr>
      <tr>
          <td class='letrap'>Relacion de ordenes atendidas del <?= $FechaI ?> al <?= $FechaF ?> </td>
      </tr>
      </table>

    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 0px solid #999;'>
      <tr>
          <td class='letrap' align='right'>
            <strong>
            <?php
            if($Opcion=='Detalle'){
            ?>
                <a href='repatencion.php?FecI=<?=$FechaI?>&FecF=<?=$FechaF?>&usuario=<?=$capt?>&Sucursal=<?=$filtro3?>&Institucion=<?=$filtro5?>&Depto=<?=$filtro7?>&Opcion=Resumen'>
                Resumen<a>
            <?php
            }else{
            ?>
                <a href='repatencion.php?FecI=<?=$FechaI?>&FecF=<?=$FechaF?>&usuario=<?=$capt?>&Sucursal=<?=$filtro3?>&Institucion=<?=$filtro5?>&Depto=<?=$filtro7?>&Opcion=Detalle'>
                Detalle<a>
            <?php
            }
            ?>
            </strong>
          </td>
      </tr>
    </table>

      <hr noshade style='color:3366FF;height:1px'>

<?php

if($Opcion=='Detalle'){

    echo "<table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

    echo "<tr height='25'>";
    echo "<td align='center' class='letrap'><b>Suc.</b></td>";
    echo "<td align='center' class='letrap'><b>Inst.</b></td>";   
    echo "<td align='center' class='letrap'><b>Orden</b></td>";
    echo "<td align='center' class='letrap'><b>Fecha</b></td>";  
    echo "<td align='center' class='letrap'><b>Hora</b></td>";  
    echo "<td align='center' class='letrap'><b>Paciente</b></td>";  
    echo "<td align='center' class='letrap'><b>Estudios</b></td>";   
    echo "</tr>";  

    $Orden2='XXX';

    while ($registro=mysql_fetch_array($UpA2)){

            $Institucion=$registro[institucion];
            $nombrei=$registro[alias];
            $Orden=$registro[orden];
            $Suc=$registro[suc];
            $Paciente=$registro[paciente];
            $Fecha=$registro[fechaot];
            $Hora=$registro[horaorden];
            $Estudios= $registro[estudio];
            $Descripcion= $registro[descripcion];

            if($Orden2<>$Orden){
                $nRng++;
            }

            $Orden2=$registro[orden];

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

            if (($nRng2 % 2) > 0) {    $Fdo = 'FFFFFF';  } else {    $Fdo = $Gfdogrid;  }    //El resto de la division;

            echo "<tr height='25' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td class='letrap'><font size='1'>".$Suc." - ".$Nsucursal."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Institucion." - ".$nombrei."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Orden."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Fecha."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Hora."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Paciente."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Estudios." - ".$Descripcion."</font></td>";
            echo "</tr>";

            $nRng2++;
    }

    if($Orden2<>$Orden){
        $nRng++;
    }

    echo "<tr height='25'>";
    echo "<td align='center' class='letrap' colspan='3'><b>No. de Ordenes. ".number_format($nRng)."</b></td>";
    echo "<td align='center' class='letrap' colspan='3'><b>No. de Estudios. ".number_format($nRng2)."</b></td>";   
    echo "<td align='center' class='letrap'><b></b></td>";  
    echo "</tr>";

}else{

    echo "<table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

    echo "<tr height='25'>";
    echo "<td align='center' class='letrap'><b>Departamento</b></td>";
    echo "<td align='center' class='letrap'><b>Subdepartamento</b></td>";   
    echo "<td align='center' class='letrap'><b>Estudios</b></td>";
    echo "<td align='center' class='letrap'><b>Cantidad</b></td>";    
    echo "</tr>";  
            
    $depto= 'xxx';
    $subdepto= 'xxx';

    while ($registro=mysql_fetch_array($UpA2)){

            if (($nRng2 % 2) > 0) {    $Fdo = 'FFFFFF';  } else {    $Fdo = $Gfdogrid;  }    //El resto de la division;


            $Estudios= $registro[estudio];
            $Descripcion= $registro[descripcion];
            $cant= $registro[cant];


            if($depto==$registro[depto]){

                $Deptonombre='';

            }else{

                $DeptoD=mysql_query("SELECT departamento,nombre
                from dep
                WHERE dep.departamento='$registro[depto]'",$link);

                $Depto=mysql_fetch_array($DeptoD);

                $Deptonombre=$Depto[nombre];

            }

            if($subdepto==$registro[subdepto]){

                $Subdeptonombre='';

            }else{

                if($subdepto<>'xxx'){
                    echo "<tr height='25' bgcolor='#A9C1DF'>";
                    echo "<td align='right' class='letrap' colspan='2'></td>";   
                    echo "<td align='right' class='letrap'><b><font size='1'>Total por Subdepto:</font></b></td>";   
                    echo "<td align='center' class='letrap'><b><font size='1'>".$canttd."</font></b></td>";
                    echo "</tr>";
                }

                $Subdeptonombre= $registro[subdepto];
                $canttd=0;

            }

            echo "<tr height='25' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td class='letrap'><font size='1'>".$Deptonombre."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Subdeptonombre."</font></td>";
            echo "<td class='letrap'><font size='1'>".$Estudios." - ".$Descripcion."</font></td>";
            echo "<td align='center' class='letrap'><font size='1'>".$cant."</font></td>";
            echo "</tr>";

            $cantt=$cantt+$cant;
            $canttd=$canttd+$cant;
            $nRng2++;
            $depto= $registro[depto];
            $subdepto=$registro[subdepto];

    }
    if($subdepto<>'xxx'){
        echo "<tr height='25' bgcolor='#A9C1DF'>";
        echo "<td align='right' class='letrap' colspan='2'></td>";   
        echo "<td align='right' class='letrap'><b><font size='1'>Total por Subdepto:</font></b></td>";   
        echo "<td align='center' class='letrap'><b><font size='1'>".$canttd."</font></b></td>";
        echo "</tr>";
    }

    echo "<tr height='25' bgcolor='#A9C1DF'>";
    echo "<td align='right' class='letrap' colspan='3'><b>Total No. de Estudios </b></td>";
    echo "<td align='center' class='letrap'><b>".$cantt."</b></td>";
    echo "</tr>";

}

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