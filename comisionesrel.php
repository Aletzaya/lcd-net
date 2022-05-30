<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  


  $Titulo="Relacion de comisiones";
  require("lib/lib.php");

  require ("config.php");	

  $link=conectarse();


  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta p�gina.

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
  $Institucion=$_REQUEST[Institucion];
  $Medico=$_REQUEST[Medico];
  $Medico1=$Medico;
  $Status=$_REQUEST[Status];
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
  $Promotor=$_REQUEST[Promotor];
  $Promotor1=$Promotor;

  ?>
  <html>
  
  <head>
  <title>Relacion de pagos de comisiones</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
		  <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>
  
  
  
  <?php


  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }

  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}
	
		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}
		if($sucursal6=="1"){
			$Sucursal2= $Sucursal2 . "San Vicente - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}
	
  if($Promotor <> '*'){
	  if ($Sucursal <> '*') {
		  if($Institucion=="*"){
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }else{
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }
	  }else{
		  if($Institucion=="*"){
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }else{
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }
	  }

  }else{
	  if ($Sucursal <> '*') {
		  if($Institucion=="*"){
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }else{
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }
	  }else{
		  if($Institucion=="*"){
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }else{
			 if($Medico=="*"){
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
				order by med.zona, ot.medico";
			 }else{
				$cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
				ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
				and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
				order by med.zona, ot.medico";
			 }
		 }
	  }
  }
  ?>
  <table width="100%" border="0">
	<tr> 
	  <td width="18%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
		</div></td>
		<td width='64%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p><br>
		<?php echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]</p>";?><br>
	</tr>
  </table>
  <font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
  <?php




$InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
$NomI    = mysql_fetch_array($InstA);
  //echo $cSql;
  if(!$res=mysql_query($cSql,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados � hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
        echo "Recarga y/� limpia.</a></font>";
 		echo "</div>";
 	}else{

        echo "<hr noshade style='color:3366FF;height:1px'>";

        echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr>";
		echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'> Zona    </th>";
        echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'> Ins     </th>";
        echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'> Suc     </th>";
        echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'> Promotor </th>";
        echo "<th with='10%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'> Med      </th>";
        echo "<th with='30%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'> Nombre   </th>";
        echo "<th with='10%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>No.Ords  </th>";
        echo "<th with='10%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>No.Ests  </th>";
        echo "<th with='10%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Importe  </th>";
        echo "<th with='10%' align='right'><font size='2' face='Arial, Helvetica, sans-serif'>Comision </th></tr>";

        $registro=mysql_fetch_array($res);
        $Inst=$registro[institucion];
        $Promotor=$registro[promotorasig];
        $Sucursal=$registro[suc];
        $Medico=$registro[medico];
        $Nombre=$registro[nombrec];
        $Importe=$registro[importe];
        $Comision+=$registro[comisiones];
        $Estudios+=$registro[6];
        $Ordenes=1;
        $Zona=$registro[Zonas];
        $Zona1=$registro[Zonas];
		$SMedico=0;
		
        while($registro=mysql_fetch_array($res)) {
   	         $Zona1=$Zona;
             if( $registro[medico] == $Medico ){
                 $Importe+=$registro[importe];
                 /*if($registro[descuento]==0){*/$Comision+=$registro[comisiones];/*}*/
                 $Estudios+=$registro[6];
                 $Ordenes+=1;
             }else{
				 if($Medico == 'AQ' ){
					$AQComision+=$Comision;
					$AQEstudios+=$Estudios;
					$AQImporte+=$Importe;
					$AQOrdenes+=$Ordenes;
				 }elseif($Medico == 'MD' ){
					$MDComision+=$Comision;
					$MDEstudios+=$Estudios;
					$MDImporte+=$Importe;
					$MDOrdenes+=$Ordenes;
				 }else{
					$DIVComision+=$Comision;
					$DIVEstudios+=$Estudios;
					$DIVImporte+=$Importe;
					$DIVOrdenes+=$Ordenes;
				 }

                 echo "<tr><td><font size='1' face='Arial, Helvetica, sans-serif'>".$Zona."</font></td>";
                 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Inst."</font></td>";
                 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Sucursal."</font></td>";
                 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Promotor."</font></td>";
                 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Medico."</font></td>";
                 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Nombre."</font></td>";
                 echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".$Ordenes."</font></td>";
                 echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Estudios)."</font></td>";
                 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,"2")."</font></td>";
                 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Comision,"2")."</font></td></tr>";

				$TComision+=$Comision;
                $TEstudios+=$Estudios;
                $TImporte+=$Importe;
                $TOrdenes+=$Ordenes;

				$SComision+=$Comision;
				$SMedico=$SMedico+1;
                $SEstudios+=$Estudios;
                $SImporte+=$Importe;
                $SOrdenes+=$Ordenes;

		        $Inst=$registro[institucion];
		        $Sucursal=$registro[suc];
        		$Promotor=$registro[promotorasig];
        		$Medico=$registro[medico];
                $Nombre=$registro[nombrec];
                $Importe=$registro[importe];
                $Comision=0;
                /*if($registro[descuento]==0){*/$Comision=$registro[comisiones];/*}*/
                $Estudios=$registro[6];
                $Ordenes=1;
		        $Zona=$registro[Zonas];
             }
			 if ( $registro[Zonas] <> $Zona1 ){
		         echo "<tr><td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
		         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
		         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
		         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
        		 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>No. Med.:&nbsp;".$SMedico."</strong></font></td>";
		         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>&nbsp;S u b t o t a l :</strong></font></td>";
        		 echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".$SOrdenes."</strong></font></td>";
		         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".number_format($SEstudios)."</strong></font></td>";
        		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($SImporte,"2")."</strong></font></td>";
		         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($SComision,"2")."</strong></font></td></tr>";
  	             echo "<tr><td>&nbsp;</td></tr>";
				 
				$TMedico+=$SMedico;				
				$SComision=0;
 				$SMedico=0;
                $SEstudios=0;
                $SImporte=0;
                $SOrdenes=0;
             }

         }

         $TComision+=$Comision;
         $TEstudios+=$Estudios;
         $TImporte+=$Importe;
         $TOrdenes+=$Ordenes;

         $SComision+=$Comision;
		 $SMedico=$SMedico+1;
         $SEstudios+=$Estudios;
         $SImporte+=$Importe;
         $SOrdenes+=$Ordenes;
  		 $TMedico+=$SMedico;		
		 
		 if($Medico == 'AQ' ){
			$AQComision+=$Comision;
			$AQEstudios+=$Estudios;
			$AQImporte+=$Importe;
			$AQOrdenes+=$Ordenes;
		 }elseif($Medico == 'MD' ){
			$MDComision+=$Comision;
			$MDEstudios+=$Estudios;
			$MDImporte+=$Importe;
			$MDOrdenes+=$Ordenes;
		 }else{
			$DIVComision+=$Comision;
			$DIVEstudios+=$Estudios;
			$DIVImporte+=$Importe;
			$DIVOrdenes+=$Ordenes;
		 }

         echo "<tr><td><font size='1' face='Arial, Helvetica, sans-serif'>".$Zona."</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Inst."</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Sucursal."</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Promotor."</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Medico."</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Nombre."</font></td>";
         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".$Ordenes."</font></td>";
         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Estudios)."</font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,"2")."</font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Comision,"2")."</font></td></tr>";


         echo "<tr><td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
   		 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>No. Med.:&nbsp;".$SMedico."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>&nbsp;S u b t o t a l :</strong></font></td>";
   		 echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".$SOrdenes."</strong></font></td>";
         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".number_format($SEstudios)."</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($SImporte,"2")."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($SComision,"2")."</strong></font></td></tr>";
         echo "<tr><td>&nbsp;</td></tr>";


         echo "<tr bgcolor='#CCCCCC'><td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
   		 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>AQ</strong></font></td>";
         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>A QUIEN CORRESPONDA</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".$AQOrdenes."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".number_format($AQEstudios)."</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($AQImporte,"2")."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($AQComision,"2")."</strong></font></td></tr>";
		 
		 echo "<tr bgcolor='#CCCCCC'><td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
   		 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><strong>MD</strong></font></td>";
         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><strong>MEDICO DIVERSO</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><strong>".$MDOrdenes."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><strong>".number_format($MDEstudios)."</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;<strong>".number_format($MDImporte,"2")."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;<strong>".number_format($MDComision,"2")."</strong></font></td></tr>";

		 echo "<tr bgcolor='#CCCCCC'><td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
   		 echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><strong>MR</strong></font></td>";
         echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'><strong>MEDICO REFERIDO</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><strong>".$DIVOrdenes."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><strong>".number_format($DIVEstudios)."</strong></font></td>";
   		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;<strong>".number_format($DIVImporte,"2")."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;<strong>".number_format($DIVComision,"2")."</strong></font></td></tr>";

         echo "<tr><td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>&nbsp;</font></td>";
         echo "<td><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>Tot. Med.:&nbsp;".$TMedico."</strong></font></td>";
		 echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>&nbsp;T o t a l :</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".$TOrdenes."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade><strong>".number_format($TEstudios)."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($TImporte,"2")."</strong></font></td>";
         echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><hr noshade>$&nbsp;<strong>".number_format($TComision,"2")."</strong></font></td></tr>";

    }
	//fin while

	$FechaI=$_REQUEST[FecI];
	$FechaF=$_REQUEST[FecF];

	echo "</table>";

	echo "<div align='center'>";
	echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=6'>";
	echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
	echo "</div>";
	
	echo "<div align='center'>";
	echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('comisionesrelpdf.php?FecI=$FechaI&FecF=$FechaF&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6&Institucion=$Institucion&Promotor=$Promotor1&Medico=$Medico1&Status=$Status')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";
	
	
	
	 echo "</form>";
	 echo "</div>";
	
	?>
	</body>
	</html>
<?php
mysql_close();
?>
