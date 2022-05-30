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

    $doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	//require_once('tcpdf2/tcpdf_include.php');

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

  // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $FechaI,$FechaF,$Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2;

    $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
    $NomI    = mysql_fetch_array($InstA);

    $Fecha   = date("Y-m-d");
    $Hora=date("H:i");

    // Logo
    $image_file2 = 'lib/DuranNvoBk.png';
    $this->Image($image_file2, 8, 5, 65, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 11);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr><tr><td width="30"></td><td width="800">Laboratorio Clínico Duran</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Fecha/Hora: '.$Fecha.' - '. $Hora.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="700"><tr><td width="30"></td><td width="800">Relacion de Ordenes de trabajo pendientes x entregar del '.$FechaI.' al '.$FechaF.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Sucursal: '.$Sucursal2.' Institucion: '.$Institucion.' - '. $NomI[nombre].'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 8);

    $this->writeHTML('<br><br><table align="center"  border="0" cellspacing="1" cellpadding="0">
    <tr>
    <td align="center" bgcolor="#808B96" width="90">Orden</td>
    <td align="center" bgcolor="#808B96" width="250">Paciente</td>
    <td align="center" bgcolor="#808B96" width="290">Estudios</td>
    <td align="center" bgcolor="#808B96" width="200">Toma</td>
    <td align="center" bgcolor="#808B96" width="50">Etiq</td>
    <td align="center" bgcolor="#808B96" width="50">R.Est</td>
    <td align="center" bgcolor="#808B96" width="50">Cap</td>
    <td align="center" bgcolor="#808B96" width="50">Imp</td>
    <td align="center" bgcolor="#808B96" width="50">Rece</td>
    <td align="center" bgcolor="#808B96" width="50">Email</td></tr></table><br>', false, 0);


    }

    // Page footer
    function Footer() {

        // Position at 15 mm from bottom
        $this->SetY(-10);
       // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "letter");

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 35);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Resultado".$Estudio);

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

$lBd=false;




//***** INICIA REPORTE ***//

$Orden=0;
$Ordenes=1;
$Estudios=0;
while($rg=mysql_fetch_array($UpA)) {
    if($Orden<>$rg[orden]){
        if($Orden<>0){

  $html='<table align="center"  border="0" cellspacing="1" cellpadding="0">
 <tr>
 <br>
 <td colspan="5" align="center">'.$Obs.'</td></tr></table>___________________________________________________________________________________________________________________________________________________<br><br>';
 $pdf->writeHTML($html,false,false,true,false,'');

            
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


        $pdf->SetFont('Helvetica', '', 7, '', 'false');

      $html='<table align="center" border="0" cellspacing="1" cellpadding="0">
      <tr>
      <td align="CENTER" width="90" >'.$rg[institucion].'&nbsp;-&nbsp;'.$rg[orden].'</td>
      <td align="left" width="250" >'.$rg[2].'</td>
      <td align="left" width="490" >Fecha Cap.: '.$rg[fecha].' '.$rg[hora].' '.$Rec.' '. $Urgencia.' &nbsp;Fech/hra entega: '.$rg[fechae].' '.$rg[horae].'</td>';
   
			if($rg[entemailpac]=='1' or $rg[entemailmed]=='1' or $rg[entemailinst]=='1'){ 

                $html= $html.'<td align="center" width="250">'.$rg[mail].'</td>   
				<td align="center" width="50"><img src="lib/email.png"> </a></td></tr></table>';
			}else{
                $html= $html.'<td align="center" width="200"></td>   
				<td align="center" width="50"></td></tr></table>';
            }
             $pdf->writeHTML($html,false,false,true,false,'');

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




    $html='<table align="center"  border="0" cellspacing="1" cellpadding="0">
    <tr>
    <td align="center"  width="90"></td>
    <td align="center"  width="60"></td>
    <td align="right" width="95">'.$rg[estudio].'&nbsp;-&nbsp;</td>
    <td align="left"  width="370">'.$rg[descripcion].' &nbsp;&nbsp;&nbsp; '.$pendiente.' &nbsp;&nbsp; '.$Urgencia2.'</td>
    <td align="left"  width="215">'.$fechatn.' &nbsp; &nbsp;'.$horatn.' &nbsp; &nbsp;'.$rg[usrest].' &nbsp; &nbsp;'.$rg[statustom].' &nbsp;
    '.RestarHoras($horaini,$horafin).'</td>
    <td align="center" width="50"><img src="images/'.$imagen1.'" > </a></td>
    <td align="center" width="50"><img src="images/'.$imagen2.'" > </a></td>
    <td align="center" width="50"><img src="images/'.$imagen3.'" > </a></td>
    <td align="center" width="50"><img src="images/'.$imagen4.'" > </a></td>
    <td align="center" width="50"><img src="images/'.$imagen5.'" > </a></td>
    </tr>
    </table>';

     $pdf->writeHTML($html,false,false,true,false,'');

    $Estudios++;
        $Urge=0;
 }

 $html='<table align="center"  border="0" cellspacing="1" cellpadding="0">
 <tr>
 <td colspan="5" align="center">'.$Obs.'</td></tr></table>';
 $pdf->writeHTML($html,false,false,true,false,'');

 
 $html='<table align="center" border="0" cellspacing="1" cellpadding="0">
 <tr>
 <td align="right" width="200">No. Ordenes : '.$Ordenes.'</td>
 <td align="center" width="200">No. Estudios : '.$Estudios.'</td>
 </tr>
 </table>';


$pdf->writeHTML($html,false,false,true,false,'c');


ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>

