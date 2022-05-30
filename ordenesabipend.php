<?php

session_start();


include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


require("lib/lib.php");

require ("config.php");	

$link=conectarse();
  
  date_default_timezone_set("America/Mexico_City");

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $Institucion=$_REQUEST[Institucion];

  $Departamento=$_REQUEST[Departamento];

  $FechaI=$_REQUEST[FechaI];
  $FechaF=$_REQUEST[FechaF];

  
  $Titulo=$_REQUEST[Titulo];

  $Arecepcion=$_REQUEST[Arecepcion];
  
  $Externo=$_REQUEST[Externo];
  
  $Correo=$_REQUEST[correo];
	
  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
  
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

	?>
	<html>
	
	<head>
	<meta charset="UTF-8">
	<title>Ordenes Pendientes </title>
	<link href="estilos.css" rel="stylesheet" type="text/css"/>
			<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body>
<?php

  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  


	$Sucursal= "";
	
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




	if($Correo=="S"){
		$correo2=" and (ot.entemailpac='1' or ot.entemailmed='1' or ot.entemailinst='1')";
	}else{
		$correo2=" ";
	}

	$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
	ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
	ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
	otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
	FROM ot, cli, otd, est
	WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
	ot.fecha <='$Fechaf' and ot.status<>'Entregada' and otd.status<>'CANCELADA' $correo2
	order by ot.orden";



if($Sucursal <> " "){
	if($Arecepcion=="S"){
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' and otd.status<>'CANCELADA'
					AND ($Sucursal) $correo2
					order by ot.orden";
	
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' and otd.status<>'CANCELADA' 
					AND ($Sucursal) $correo2
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and otd.status<>'CANCELADA' AND ($Sucursal)
					$correo2
					order by ot.orden";
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,
					otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00' 
					and est.estpropio='N' and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00' 
					and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' and otd.status<>'CANCELADA'
					AND ($Sucursal) $correo2
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' and otd.status<>'CANCELADA'
					AND ($Sucursal) $correo2
					order by ot.orden";
				}
			}
		}
	
	}else{
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N' and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
				}
					
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N' and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";	
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and est.estpropio='N' 
					and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.status<>'CANCELADA' AND 
					($Sucursal) $correo2
					order by ot.orden";
				}
				
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,
					otd.obsest,ot.suc,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and est.estpropio='N' and otd.status<>'CANCELADA' AND ($Sucursal)
					$correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and otd.status<>'CANCELADA' AND ($Sucursal) $correo2
					order by ot.orden";
				}
			}
		}
	}
}else{
	if($Arecepcion=="S"){
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' and otd.status<>'CANCELADA'
					$correo2
					order by ot.orden";
	
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' and otd.status<>'CANCELADA'
					$correo2
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and otd.status<>'CANCELADA'
					$correo2
					order by ot.orden";
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00' 
					and est.estpropio='N' and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00'
					and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N'
					and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' and otd.status<>'CANCELADA'
					$correo2
					order by ot.orden";
				}
			}
		}
	
	}else{
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N' and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
				}
					
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N' and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.status<>'CANCELADA' $correo2
					order by ot.orden";	
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and est.estpropio='N'
					and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.status<>'CANCELADA'
					$correo2
					order by ot.orden";
				}
				
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and est.estpropio='N' and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,
					otd.status as statusotd,ot.entemailpac,entemailmed,entemailinst,cli.mail
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$FechaI' and
					ot.fecha <='$FechaF' and ot.status<>'Entregada' and otd.status<>'CANCELADA' $correo2
					order by ot.orden";
				}
			}
		}
	}
}




$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>
    <td width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "Relacion de Ordenes de trabajo pendientes x entregar del $FechaI al $FechaF Sucursal: $Sucursal2 Institucion:$Institucion - $NomI[nombre]"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='10'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Toma</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Etiq.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>R. Est.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Cap.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Imp.</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Rece.</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Email.</font></th>";
    echo "<tr><td colspan='10'><hr noshade></td></tr>";
    $Orden=0;
    $Ordenes=1;
	$Estudios=0;
    while($rg=mysql_fetch_array($UpA)) {
    	if($Orden<>$rg[orden]){
    		if($Orden<>0){
    			echo "<th colspan='5' align='center'><font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>$Obs</font></th>";
    			echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th>
				<th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
				$Ordenes++;
				$Urge2=0;
    		}
	    	if($rg[servicio]=="Urgente"){
				$Urgencia="* * *  U R G E N C I A  * * * ";
				$Gfont4="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
			}else{
				$Urgencia=$rg[servicio];
				$Gfont4="<font size='1' face='Arial, Helvetica, sans-serif'>";
			}
			$Rec=$rg[recepcionista];
			$Obs=$rg[observaciones];

    		echo "<tr>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
    		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2]</font></th>";
    		echo "<th colspan='2' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha Cap.: $rg[fecha] 
			&nbsp;&nbsp;&nbsp;&nbsp; $rg[hora] &nbsp;&nbsp;&nbsp;&nbsp; $Rec &nbsp;&nbsp;&nbsp;&nbsp; $Gfont4 $Urgencia 
			&nbsp;&nbsp;&nbsp;&nbsp; Fech/hra entega: $rg[fechae] &nbsp;&nbsp; $rg[horae]</font></th>";

			if($rg[entemailpac]=='1' or $rg[entemailmed]=='1' or $rg[entemailinst]=='1'){ 
				echo "<th align='center' colspan='5' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[mail]</th>";   
				echo "<th align='center' align='left'><img src='lib/email.png' border='0'></th>";   
			}else{
				echo "<th align='center'>$Gfont &nbsp;</a></th>";
			}
    		echo "</tr>";
    	    $Orden=$rg[orden];
    	}



		if($rg[etiquetas]>=1){
			$imagen1="OKShield.png";
		}else{	
			$imagen1="ErrorCircle.png";
		}
			$unoa=$rg[uno];
			$uno=substr($unoa,10);
			$esp="_";
			$uno.=$esp;
			$uno.=$unoa;

		if($rg[dos]<>'0000-00-00 00:00:00'){
			$imagen2="OKShield.png";
		}else{	
			$imagen2="ErrorCircle.png";
		}
			$dosa=$rg[dos];
			$dos=substr($dosa,10);
			$esp="_";
			$dos.=$esp;
			$dos.=$dosa;

		if($rg[tres]<>'0000-00-00 00:00:00'){
			$imagen3="OKShield.png";
		}else{	
			$imagen3="ErrorCircle.png";
		}
			$tresa=$rg[tres];
			$tres=substr($tresa,10);
			$esp="_";
			$tres.=$esp;
			$tres.=$tresa;

		if($rg[cuatro]<>'0000-00-00 00:00:00'){
			$imagen4="OKShield.png";
		}else{	
			$imagen4="ErrorCircle.png";
		}
			$cuatroa=$rg[cuatro];
			$cuatro=substr($cuatroa,10);
			$esp="_";
			$cuatro.=$esp;
			$cuatro.=$cuatroa;

		if($rg[cinco]<>'0000-00-00 00:00:00'){
			$imagen5="OKShield.png";
			$pendiente=" ";
			$Gfont3="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}else{	
			$imagen5="ErrorCircle.png";
			$pendiente="Pendiente";
			$Gfont3="<font color='#FF6600' size='1' face='Arial, Helvetica, sans-serif'>";
		}
			$cincoa=$rg[cinco];
			$cinco=substr($cincoa,10);
			$esp="_";
			$cinco.=$esp;
			$cinco.=$cincoa;

		if($rg[estudio]=="URG"){
			$Urge=1;
		}else{
			$Urge=0;
		}

    	if($Urge<>0){
			$Urgencia2="* * *  U R G E N C I A  * * * ";
			$Gfont5="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Urgencia2=" ";
			$Gfont5="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}
    	if($rg[statustom]=='PENDIENTE'){
			$Gfont6="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Gfont6="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}
		
		if($rg[fechaest]=='0000-00-00 00:00:00'){
			$Gfont7="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Gfont7="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}
		$fechatn=substr($rg[fechaest],0,10);		
		$horatn=substr($rg[fechaest],11);
		//$horatn2=$horatn-$rg[hora];
		
		$horafin=$horatn;
		$horaini=$rg[hora];
		if($horafin=='00:00:00'){
			$Hora2=date('H:i:s');
			$horafin=$Hora2;
		}

		if($rg[statusotd]=="CANCELADA"){
			$stotd='C A N C E L A D A';
			$Gfont8="<font color='#3543b2' size='1' face='Arial, Helvetica, sans-serif'>";
			$Gfont9="bgcolor='#FFFF66'";
		}else{
			$stotd=' ';
			$Gfont9="bgcolor='#FFFFFF'";
		}

		//RestarHoras($horaini,$horafin); 
		echo "<tr>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp; </font></th>";
		echo "<th align='right' >$Gfont8 $stotd &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
		<font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp;-&nbsp;</font></th>";
		echo "<th align='left' ><font size='1' face='Arial, Helvetica, sans-serif'> $rg[descripcion] &nbsp;&nbsp;&nbsp; $Gfont3 $pendiente &nbsp;&nbsp;&nbsp; $Gfont5 $Urgencia2</font></th>";
		echo "<th align='center' ><font size='1' face='Arial, Helvetica, sans-serif'>$Gfont7 $fechatn &nbsp; $horatn &nbsp;&nbsp; $rg[usrest] &nbsp;&nbsp;&nbsp; $Gfont6 $rg[statustom] &nbsp;&nbsp;&nbsp; ";
			echo RestarHoras($horaini,$horafin);	
		echo "</font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen1 alt=$uno></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen2 alt=$dos></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen3 alt=$tres></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen4 alt=$cuatro></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen5 alt=$cinco></font></th>";
   		echo "</tr>"; 
		if($rg[obsest]<>''){
		echo "<tr>";
    	echo "<th colspan='2' align='left'></th>";
    	echo "<th colspan='2' align='left'><font color='#0066FF' size='1' face='Arial, Helvetica, sans-serif'>$rg[obsest]</font></th>";
    	echo "</tr>";
		}
		$Estudios++;
			$Urge=0;
     }
     echo "<th colspan='5' align='center'><font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>$Obs</font></th>";
	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th>
	 <th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
     echo "<tr><td colspan='10'><hr noshade></td></tr>";  
     

	 
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "</tr>"; 

	
	 $FechaI=$_REQUEST[FechaI];
  	 $FechaF=$_REQUEST[FechaF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=12&fechas=1&FechaI=$FechaI&FechaF=$FechaF'>";
	 echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
	 echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('ordenesabipendpdf.php?FechaI=$FechaI&FechaF=$FechaF&Institucion=$Institucion&Departamento=$Departamento&Arecepcion=$Arecepcion&Externo=$Externo&correo=$Correo&sucursalt=$sucursalt&sucursal0=$sucursal0&sucursal1=$sucursal1&sucursal2=$sucursal2&sucursal3=$sucursal3&sucursal4=$sucursal4&sucursal5=$sucursal5&sucursal6=$sucursal6')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";


echo "</form>";
echo "</div>";
?>
</body>
</html>
<?php
mysql_close();
?>

