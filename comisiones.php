<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo="Relacion de comisiones";

  require("lib/lib.php");

  $link=conectarse();

  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta página.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
  $Institucion=$_REQUEST[Institucion];
  $Medico=$_REQUEST[Medico];
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

  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  
  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);

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
			$Sucursal2= $Sucursal2 . "San Vicente- ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}

    if($Promotor <> "*"){
  	  if ($Sucursal <> "*") {
		  if($Institucion=="LCD"){
				$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
				(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,
				med.zona as zonas,zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
				from ot,otd,med,cli,zns,est
				where
				ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
				and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and ot.institucion <= '36' and ot.institucion <> '2' and ot.institucion <> '4'
				and ot.institucion <> '5' and ot.institucion <> '6' and ot.institucion <> '7' and ot.institucion <> '8' and ot.institucion <> '9'
				and ot.institucion <> '11' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
		  }else{
				if($Institucion=="*"){
					if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }
				}else{
					 if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.medico='$Medico' and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }
				}
		  }
	  }else{
		  if($Institucion=="LCD"){
				$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
				(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
				zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
				from ot,otd,med,cli,zns,est
				where
				ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
				and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and ot.institucion <= '36' and ot.institucion <> '2' and ot.institucion <> '4'
				and ot.institucion <> '5' and ot.institucion <> '6' and ot.institucion <> '7' and ot.institucion <> '8' and ot.institucion <> '9'
				and ot.institucion <> '11' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
		  }else{
				if($Institucion=="*"){
					if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }
				}else{
					 if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.medico='$Medico' and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' order by ot.medico, otd.orden ";
					 }
				}
		  }
	  }
	}else{
  	  if ($Sucursal <> "*") {
		  if($Institucion=="LCD"){
				$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
				(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
				zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
				from ot,otd,med,cli,zns,est
				where
				ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
				and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and ot.institucion <= '36' and ot.institucion <> '2' and ot.institucion <> '4'
				and ot.institucion <> '5' and ot.institucion <> '6' and ot.institucion <> '7' and ot.institucion <> '8' and ot.institucion <> '9'
				and ot.institucion <> '11' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV'  AND ($Sucursal) order by ot.medico, otd.orden ";
		  }else{
				if($Institucion=="*"){
					if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) order by ot.medico, otd.orden ";
					 }
				}else{
					 if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.medico='$Medico' and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) order by ot.medico, otd.orden ";
					 }
				}
		  }
	  }else{
		  if($Institucion=="LCD"){
				$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
				(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
				zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
				from ot,otd,med,cli,zns,est
				where
				ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
				and med.comision > '0' and med.zona=zns.zona and ot.institucion <= '36' and ot.institucion <> '2' and ot.institucion <> '4'
				and ot.institucion <> '5' and ot.institucion <> '6' and ot.institucion <> '7' and ot.institucion <> '8' and ot.institucion <> '9'
				and ot.institucion <> '11' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' order by ot.medico, otd.orden ";
		  }else{
				if($Institucion=="*"){
					if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' order by ot.medico, otd.orden ";
					 }
				}else{
					 if($Medico=="*"){
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' order by ot.medico, otd.orden ";
					 }else{
						$cSql="select ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
						(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,
						zns.descripcion as nombrezona,est.comision as estcomision,ot.suc,med.promotorasig
						from ot,otd,med,cli,zns,est
						where
						ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
						and med.comision > 0 and ot.medico='$Medico' and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' order by ot.medico, otd.orden ";
					 }
				}
		  }
	   }
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $Titulo;?></title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
		  <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body bgcolor="#FFFFFF">

<?php

  //echo $cSql;
  if(!$res=mysql_query($cSql,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{

        $registro=mysql_fetch_array($res);

        echo "<table width='100%' height='80' border='0'>";    //Encabezado
        echo "<tr><td width='26%' height='76'>";
        echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='70'></p>";
        echo "</td>";
        echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
        echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]</p>";
        echo "</td></tr></table>";
        echo "<p><strong><font size='1' face='Arial, Helvetica, sans-serif'>Medico: $registro[medico].- $registro[nommedico] &nbsp; INST:_$registro[institucion] &nbsp; Zona: &nbsp; $registro[zonas] &nbsp; $registro[nombrezona] &nbsp; $registro[promotorasig]</strong></p>";

        echo "<hr>";

        echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr>";
		echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Inst.</th>";
		echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Suc.</th>";
        echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</th>";
        echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</th>";
        echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</th>";
        echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</th>";
        echo "<th with='30%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</th>";
        echo "<th with='10%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Comision</th><tr>";

        $Institucion=$registro[institucion];
		$MedOrd=$registro[medico].$registro[orden];
        if($registro[estcomision]>=1){ 
        	$Comision3=$registro[precio]*(1-($registro[descuento]/100));	
			$Comision2=$Comision3*($registro[estcomision]/100);
			$ComisionB=$Comision2;
		}else{
			$Comision3=$registro[precio]*(1-($registro[descuento]/100));	
			$Comision2=$Comision3*($registro[comision]/100);
			$ComisionB=$Comision2;
		}
        $Estudios=$registro[estudio];
		$Contador=1;
		$ContadorEst=0;
        if($registro[descuento]>0){
           $Estudios="(DESCTO)".$registro[estudio];
           /*$Comision=0;*/
        }
        $Importe=$registro[importe];
        $Medico=$registro[medico];
        $Orden=$registro[orden];
        $Suc=$registro[suc];
		$Paciente=$registro[nombrec];
		$Fecha=$registro[fecha];

        while ($registro=mysql_fetch_array($res)){
             if($MedOrd==$registro[medico].$registro[orden]){
                $Estudios=$Estudios.", ".$registro[estudio];
                $Importe+=$registro[importe];
				$ContadorEst=$ContadorEst+1;
		        if($registro[estcomision]>=1){ 
		        	$Comision3=$registro[precio]*(1-($registro[descuento]/100));	
					$Comision2=$Comision3*($registro[estcomision]/100);
				}else{
					$Comision3=$registro[precio]*(1-($registro[descuento]/100));	
					$Comision2=$Comision3*($registro[comision]/100);
				}
				$ComisionB+=$Comision2;
             }else{
                 echo "<tr><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Institucion."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Suc."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td>";
                 echo "<td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font></td>";
                 echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
                 echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionB,'2')."</font></td></tr>";
				 $ImporteM+=$Importe;
                 $ComisionM+=$ComisionB;
				 $Contador=$Contador+1;
   			     $ComisionB=0;
                 if($registro[medico]<>$Medico){   //Total del Medico
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>T o t a l e s : &nbsp; </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ComisionM,'2')."</strong></font></td></tr>";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
                    echo "</table>";
					
					 echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
					 echo "<tr>";
					 echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Total Comision : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Letra=impletras($ComisionM,"pesos ");"</tr>";
					 echo "<tr>&nbsp;</tr>";
					 echo "<tr>&nbsp;</tr>";
					 echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fecha de entrega : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ______________________________________ </tr>"; 
					 echo "<p></p>";
					 echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Nombre de quien recibe : &nbsp;&nbsp;_____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 		 
					 echo "Firma : &nbsp;_____________________________________________ </tr>"; 
					 echo "<p></p>";
					 echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Comentarios : &nbsp;&nbsp;&nbsp;____________________________________________________________________________________________________________ </tr>"; 		 
					 echo "<tr>&nbsp;</tr>";
					 echo "</table>";

        			echo "<hr noshade style='color:3366FF;height:1px'>";
					
        			echo "<table width='100%' height='80' border='0'>";    //Encabezado
                    echo "<tr><td width='26%' height='76'>";
                    echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='70'></p>";
                    echo "</td>";
                    echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
                    echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF </p>";
                    echo "</td></tr></table>";

        			echo "<p><strong>Medico: $registro[medico].- $registro[nommedico] &nbsp; INST:_$registro[institucion] &nbsp; Zona: $registro[zonas] &nbsp; $registro[nombrezona] &nbsp; $registro[promotorasig]</strong></p>";

        			echo "<hr>";

        			echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
                    echo "<tr>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Inst.</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Suc.</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</th>";
                    echo "<th with='30%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</th>";
                    echo "<th with='10%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Comision</th><tr>";

                    $ImporteT+=$ImporteM;
                    $ComisionT+=$ComisionM;
 				    $ContadorT+=$ContadorM;

                    $ImporteM=0;
                    $ComisionM=0;
                 }
                 $Institucion=$registro[institucion];
				 $MedOrd=$registro[medico].$registro[orden];
                 $Estudios=$registro[estudio];
//				 $Comision=$registro[comision];
		         if($registro[estcomision]>=1){ 
		         	$Comision3=$registro[precio]*(1-($registro[descuento]/100));	
					$ComisionB+=$Comision3*($registro[estcomision]/100);
				 }else{
					$Comision3=$registro[precio]*(1-($registro[descuento]/100));	
					$Comision2=$Comision3*($registro[comision]/100);
					$ComisionB+=$Comision2;
				 }
                 if($registro[descuento]>0){
                    $Estudios="(DESCTO)".$registro[estudio];
                    /*$Comision=0;*/
                 }
                 $Importe=$registro[importe];
                 $Medico=$registro[medico];
                 $Suc=$registro[suc];
                 $Orden=$registro[orden];
 		 		 $Paciente=$registro[nombrec];
		 		 $Fecha=$registro[fecha];
            }
         }

                 echo "<tr><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Institucion."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Suc."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td>";
                 echo "<td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font></td>";
                 echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
                 echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionB,'2')."</font></td></tr>";
                 $ImporteM+=$Importe;
                 $ComisionM+=$ComisionB;
				 $ContadorTEst=$Contador+$ContadorEst;

         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>T o t a l e s : &nbsp; </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ComisionM,'2')."</strong></font></td></tr>";
         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";

		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr>";
		echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Total Comision : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Letra=impletras($ComisionM,"pesos ");"</tr>";
		echo "<tr>&nbsp;</tr>";
		echo "<tr>&nbsp;</tr>";
		echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fecha de entrega : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ______________________________________ </tr>"; 
		echo "<p></p>";
		echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Nombre de quien recibe : &nbsp;&nbsp;_____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 		 
		echo "Firma : &nbsp;_____________________________________________ </tr>"; 
		echo "<p></p>";
		echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Comentarios : &nbsp;&nbsp;&nbsp;____________________________________________________________________________________________________________ </tr>"; 		 
		echo "<tr>&nbsp;</tr>";
		echo "</table>";

         $ImporteT+=$ImporteM;
         $ComisionT+=$ComisionM;

         echo "<hr noshade style='color:3366FF;height:1px'>";
         echo "<table  align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
         echo "<tr><td>&nbsp;</td><td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Pacientes: &nbsp; </strong></td>
		 <td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;".number_format($Contador)."&nbsp;</strong></td>
		 <td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;E s t u d i o s : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($ContadorTEst)."</strong></font></td>
		 <td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>G R A N &nbsp; T O T A L : </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteT,'2')."</strong></font></td>
		 <td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ComisionT,'2')."</strong></font></td></tr>";
         echo "</table>";
         echo "<hr noshade style='color:3366FF;height:1px'>";

    }
	//fin while
	echo '</table>';
    ?>
<?php
  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
	
  echo "<br>";

  echo "<div align='center'>";
  echo  "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=4&fechas=1&FecI=$FecI&FecF=$FecF'>";
  echo " Regresar</a></font>";
  echo "</div>";

  echo "<div align='left'>";
  echo "<form name='form1' method='post' action='pidedatos.php?cRep=4&fechas=1&FecI=$FecI&FecF=$FecF'>";
  echo "         <input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
  echo "   </form>";
  echo "</div>";
?>
</body>
</html>
<?
mysql_close();
?>