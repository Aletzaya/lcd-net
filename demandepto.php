<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require ("config.php");	

  require("lib/lib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];
  
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

  $Institucion=$_REQUEST[Institucion];

  $Institucion3=$_REQUEST[Institucion];

  $FecI=$_REQUEST[FechaI];

  $FecF=$_REQUEST[FechaF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Departamento=$_REQUEST[Departamento];
  $Departamento1=$_REQUEST[Departamento];

 $Servicio=$_REQUEST[Servicio];
 $Servicio1=$_REQUEST[Servicio];

  if($Servicio=="*"){
  	  $Servicio=" ";
  }else{
  	  $Servicio=" and ot.servicio='$Servicio'";
  }

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
?>
<html>
    <head>
	<meta charset="UTF-8">
        <title>Demanda Detalle </title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body>
<?php
  $InstA   = mysql_query("SELECT institucion as id, nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= " ";
	
	if($sucursalt=="1"){  
	
		$Sucursal=" ";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}	

		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}
		if($sucursal6=="1"){
			$Sucursal2= $Sucursal2 . "San Vicente - ";
			if($Sucursal==" "){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}

	if($Sucursal==" "){
		$Sucursal=" ";
	}else{
		$Sucursal= "AND (".$Sucursal.")";
	}

	if($Departamento=="*"){
		$Departamento=" ";
	}else{
		$Departamento= "AND dep.departamento=$Departamento";
	}

	if($Institucion=="*"){
		$Institucion2="*- Todos";
		$Institucion=" ";
	}else{
		$Institucion2= $NomI[id].  $NomI[nombre];
		$Institucion= "AND ot.institucion=$Institucion";

	}

    $cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, dep.departamento
		FROM ot
		LEFT JOIN otd on otd.orden=ot.orden
		LEFT JOIN est on otd.estudio=est.estudio 
		LEFT JOIN dep on est.depto=dep.departamento 
		WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' $Servicio $Departamento $Institucion $Sucursal 
		GROUP BY est.depto, est.subdepto, otd.estudio";

	$registro3=mysql_query("SELECT count(distinct ot.orden)
		FROM ot
		LEFT JOIN otd on otd.orden=ot.orden
		LEFT JOIN est on otd.estudio=est.estudio 
		LEFT JOIN dep on est.depto=dep.departamento 
		WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' $Servicio $Departamento $Institucion $Sucursal");

	$registro2=mysql_fetch_array($registro3);


	$Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion:$Institucion2";

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>
    <td width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "$Titulo"; ?>&nbsp;</div></td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php

        $FechaAux=strtotime($Fecha);
        $nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
        $FechaAnt=date("Y-m-d",$nDias);
        echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Depto</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Subdepto</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Estudio</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Descripcion</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Precio</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>#Estudios</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Sub-total</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Descuentos</th>";
        echo "<th align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'>Importe</th>";

        $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;

        while($registro=mysql_fetch_array($UpA)) {
    		if($subdep==$registro[8]){
				$departamento=" ";
				$subdepartamento=" ";
			}else{
				$departamento=$registro[7];
				$subdepartamento=$registro[8];
		        echo "<tr><td></td><td></td><td></td><td></td><td colspan='5'><hr></td></tr>";
	    		if($Noveces<>0){
			        echo "<tr>";
			        echo "<td align='center'></td>";
			        echo "<td align='center'></td>";
			        echo "<td align='center'></td>";
			        echo "<td align='center' class='letrap'><strong>T o t a l</strong></td>";
			        echo "<td align='center' class='letrap'><strong> - </strong></td>";
					echo "<td align='center' class='letrap'><strong>".number_format($Noveces2)."</strong></td>";
			        echo "<td align='right' class='letrap'><strong>".number_format($Subtotal2,'2')."</strong></td>";
			        echo "<td align='right' class='letrap'><strong>".number_format($Descuentos2,'2')."</strong></td>";
			        echo "<td align='right' class='letrap'><strong>".number_format($Total2,'2')."</strong></td>";
			        echo "</tr>";
			        echo "<tr><td colspan='9'><hr noshade></td></tr>";
        		     $Noveces3=$Noveces3+$Noveces2;
				     $Nordenes3=$Nordenes3+$Nordenes2;
		             $Descuentos3=$Descuentos3+$Descuentos2;
		             $Subtotal3=$Subtotal3+$Subtotal2;
		             $Total3=$Total3+$Total2;
		             $Noveces2=0;
		             $Nordenes2=0;
        		     $Descuentos2=0;
		             $Subtotal2=0;
        		     $Total2=0;

				}
			}

			if (($nRng % 2) > 0) {
			    $Fdo = '#FFFFFF';
			} else {
			    $Fdo = '#ABB2B9';
			}

			echo "<tr>";
			echo "<td class='letrap'><strong>".$departamento."</strong></td>";
			echo "<td class='letrap'><strong>".$subdepartamento."</strong></td>";
			echo "<td class='letrap'>".$registro[0]."</td>";
			echo "<td class='letrap'>".$registro[1]."</td>";
			echo "<td align='right' class='letrap'>".number_format($registro[2],'2')."</td>";
			echo "<td align='center' class='letrap'>".number_format($registro[3])."</td>";
			echo "<td align='right' class='letrap'>".number_format($registro[4],'2')."</td>";
			echo "<td align='right' class='letrap'>".number_format($registro[5],'2')."</td>";
			echo "<td align='right' class='letrap'>".number_format($registro[4]-$registro[5],'2')."</td>";
			echo "</tr>";

			echo "<font size='1' face='Arial, Helvetica, sans-serif'>";

             $Noveces2=$Noveces2+$registro[3];
		     $Nordenes2=$Nordenes2+$registro[6];
             $Descuentos2=$Descuentos2+$registro[5];
             $Subtotal2=$Subtotal2+$registro[4];
             $Total2=$Total2+($registro[4]-$registro[5]);

             $Noveces=$Noveces2+$registro[3];
		     $Nordenes=$Nordenes2+$registro[6];
             $Descuentos=$Descuentos2+$registro[5];
             $Subtotal=$Subtotal2+$registro[4];
             $Total=$Total2+($registro[4]-$registro[5]);
			 $Cuenta=$Cuenta+$registro[6];
			 $subdep=$registro[8];
		

        }//fin while

		$Noveces3=$Noveces3+$Noveces2;
		$Nordenes3=$Nordenes3+$Nordenes2;
		$Descuentos3=$Descuentos3+$Descuentos2;
		$Subtotal3=$Subtotal3+$Subtotal2;
		$Total3=$Total3+$Total2;

        echo "<tr><td></td><td></td><td></td><td></td><td colspan='5'><hr></td></tr>";
        echo "<tr>";
        echo "<td align='center'><strong></td>";
        echo "<td align='center'><strong></td>";
        echo "<td align='center'><strong></td>";
        echo "<td align='center' class='letrap'><strong>T o t a l</strong></td>";
        echo "<td align='center' class='letrap'><strong> - </strong></td>";
		echo "<td align='center' class='letrap'><strong>".number_format($Noveces2)."</strong></td>";
        echo "<td align='right' class='letrap'><strong>".number_format($Subtotal2,'2')."</strong></td>";
        echo "<td align='right' class='letrap'><strong>".number_format($Descuentos2,'2')."</strong></td>";
        echo "<td align='right' class='letrap'><strong>".number_format($Total2,'2')."</strong></td>";
        echo "</tr>";

        echo "<tr></tr>";
        echo "<tr></tr>";
        echo "<tr></tr>";
        echo "<tr></tr>";

        echo "<tr>";
        echo "<td align='center'></td>";
        echo "<td align='center'></td>";
        echo "<td align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong> No. Ordenes : ".number_format($registro2[0])."</td>";
        echo "<td align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong>T o t a l &nbsp;&nbsp;  G r a l .</td>";
        echo "<td align='CENTER' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong> - </td>";
        echo "<td align='center' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong>".number_format($Noveces3)."</strong></td>";
        echo "<td align='right' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong>".number_format($Subtotal3,'2')."</strong></td>";
        echo "<td align='right' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong>".number_format($Descuentos3,'2')."</strong></td>";
        echo "<td align='right' bgcolor='#808B96' class='letrap' color='#FFFFFF'><strong>".number_format($Total3,'2')."</strong></td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";
		echo "<br>";
		echo "<br>";
        echo "<table align='center' width='75%' border='0' cellspacing='1' cellpadding='0'>";

       	echo "<tr>";
       	echo "<th align='left' class='letrap'>Depto</th>";
       	echo "<th align='left' class='letrap'>Nombre</th>";
       	echo "<th align='right' class='letrap'>No. Estudios</th>";
       	echo "<th align='right' class='letrap'> - </th>";
       	echo "<th align='right' class='letrap'>Sub-total</th>";
       	echo "<th align='right' class='letrap'> &nbsp; Desctos </th>";
       	echo "<th align='right' class='letrap'>I m p o r t e</th>";
       	echo "<th align='right' class='letrap'> - </th>";
       	echo "</tr>";
        echo "<tr><td colspan='8'><hr noshade></td></tr>";

		$cSqla="SELECT dep.departamento,dep.nombre,count(otd.orden) as ordenes,sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as importe,count(distinct otd.orden)
		FROM ot
		LEFT JOIN otd on otd.orden=ot.orden
		LEFT JOIN est on otd.estudio=est.estudio 
		LEFT JOIN dep on est.depto=dep.departamento 
		WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' $Servicio $Departamento $Institucion $Sucursal 
		GROUP BY dep.departamento";

		$UpB=mysql_query($cSqla,$link);

        while($dm=mysql_fetch_array($UpB)) {

				if (($nRng % 2) > 0) {
				    $Fdo = '#E1E1E1';
				} else {
				    $Fdo = '#ABB2B9';
				}

  	      		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        		echo "<th align='left' class='letrap'>".$dm[departamento]."</th>";
        		echo "<th align='left' class='letrap'>".$dm[nombre]."</th>";
        		echo "<th align='right' class='letrap'>".number_format($dm[ordenes],'2')." &nbsp; </th>";
        		echo "<th align='right' class='letrap'>".number_format(($dm[ordenes]/$Noveces3)*100,'0')." % </th>";
        		echo "<th align='right' class='letrap'>".number_format($dm[precio],'2')." &nbsp; </th>";
        		echo "<th align='right' class='letrap'>".number_format($dm[importe],'2')." &nbsp; </th>";
        		echo "<th align='right' class='letrap'>".number_format($dm[precio]-$dm[importe],'2')." &nbsp; </th>";
        		echo "<th align='right' class='letrap'>".number_format((($dm[precio]-$dm[importe])/$Total3)*100,'0')." % </th>";
        		echo "</tr>";
        		$nRng++;
        }

echo "<tr><td colspan='8'><hr noshade></td></tr>";
echo "</table>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=3&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";        
echo "<form name='form1' method='post' action='pidedatos.php?Menu=3&fechas=1&FechaI=$FechaI&FechaF=$FechaF'><a class='edit' href=javascript:wingral('demandeptopdf.php?FechaI=$FecI&FechaF=$FecF&Institucion=$Institucion3&Departamento=$Departamento1&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6&Servicio=$Servicio1')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></i>";

 echo "</form>";
 echo "</div>";
?>
</body>
</html>
<?php
mysql_close();
?>
