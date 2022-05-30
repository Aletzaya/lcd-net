<?php
 
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
 

#Saco los valores de las sessiones los cuales no cambian;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1];
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];

$Menu = $_REQUEST[Menu];

if ($Menu==1) {    //Reporte de corte de caja
    $Titulo = "Relacion de ordenes abiertas";
} elseif ($Menu==2) {
    $Titulo = "Demanda de Estudios";
} elseif ($Menu==3) {
    $Titulo = "Relacion de Ordenes abiertas";
} elseif ($Menu==4) {
    $Titulo = "Calculo de comisiones";
} elseif ($Menu==5) {
    $Titulo = "Calculo de comisiones";
} elseif ($Menu==6) {
    $Titulo = "Relacion de Comisiones";
} elseif ($Menu==7) {
    $Titulo = "Actualiza precios en ordenes de estudio";
} elseif ($Menu==8) {
    $Titulo = "Demanda de Estudios x Hora";
} elseif ($Menu==9) {
    $Titulo = "Reporte de Demanda de Ordenes x Depto Detalle del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu==10) {
    $Titulo = "Reporte de Demanda de Ordenes x Depto Resumen del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu==11) {
    $Titulo = "Reporte de Pagos de Ordenes de Trabajo del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu==12) {
    $Titulo = "Reporte de Ordenes pendientes x entregar del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu==13) {
    $Titulo = "Reporte de Ordenes Entregadas del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu== 14) {
    $Titulo = "Reporte de Ordenes - Ruta Critica del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu == 15) {
    $Titulo = "Reporte de Ordenes pendientes x entregar al Cliente del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu == 16) {
    $Titulo = "Reporte de Gastos";
} elseif ($Menu == 19) {
    $Titulo = "Reporte de Ordenes Abiertas* $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu == 20) {
    $Titulo = "Relacion Servicio Movil de Ordenes Abiertas* $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu == 21) {
    $Titulo = "Relacion de Ordenes Abiertas PEMEX* $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu == 22) {
    $Titulo = "Relacion Tiempos de entrega $_REQUEST[FechaI] al $_REQUEST[FechaF]";
} elseif ($Menu == 23) {
    $Titulo = "Relacion Estudios por Depto $_REQUEST[FechaI] al $_REQUEST[FechaF]";
}elseif($Menu == 30){
    $Titulo="Reporte de Ingresos Generales";
} elseif ($op == 'ActPre') {

    $cSql = "update otd,ot,est set otd.precio=$_REQUEST[Lista] where ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'
          and ot.orden=otd.orden and otd.estudio=est.estudio";

    $lUp = mysql_query($cSql);

    $SqlA = mysql_query("SELECT orden FROM ot WHERE ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'");
    while ($Cpo = mysql_fetch_array($SqlA)) {
        $busca = $Cpo[0];
        $ImpA = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
        $Otd = mysql_fetch_array($ImpA);
        $lUp = mysql_query("UPDATE ot SET importe='$Otd[0]' WHERE orden='$busca'");
    }


    header("Location: menu.php");
}



require("lib/lib.php");

$link     = conectarse();
    
$Titulo   = "Menu de opciones multiples";



$FechaI = date("Y-m-d");
$FechaF = date("Y-m-d");
$Fecha  =date("Y-m-d");

require ("config.php");	

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

</head>
    
<?php    

echo '<body topmargin="1">';

if($Menu ==  1){
    	 
    echo "<br><div align='center'>Relacion de ordenes generadas</div><br>";
    
    echo "<form name='form1' method='get' action='ordenesabi.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
	echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    
	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";
    
	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";             
        
	echo "Tipo de servicio: ";
    echo "</td><td>";    
    echo "<select name='Servicio' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    echo "<option value='Ordinaria'> Ordinaria </option>";
    echo "<option value='Urgente'> Urgente </option>";
    echo "<option value='Nocturno'> Nocturno </option>";
    echo "<option value='Express'> Express </option>";
    echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";             
    
	echo "Solo Ordenes C/Descto [Si/No] : ";
    echo "</td><td>";    
	echo "<select name='Descto' class='content1'>";
	echo "<option value='N'>No</option>";
	echo "<option value='S'>Si</option>";
	echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";             		

	echo "Recepcionista, * todos : ";
    echo "</td><td>";            
    echo "<input type='text' class='content1'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;";
    echo "</td></tr><tr><td align='right'>";             		

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
	echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>";             		
    echo "</td><td align='center'>";    
        
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    
    echo "</form>";
    
    
}elseif($Menu ==  2){
    	 
    echo "<br><div align='center'>Demanda de ordenes generadas</div><br>";
    
    echo "<form name='form1' method='get' action='demandepto2.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
	echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    
	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";
    
	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";  
	



    echo "Departamento : ";
    echo "</td><td>";    
	$Depto=mysql_query("select departamento,nombre from dep",$link);
	echo "<select name='Departamento' class='content1'>";
	echo "<option value=''> *  T o d o s </option>";
	while ($Depto1=mysql_fetch_array($Depto)){
		   echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";  
	           
    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	//echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
	echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>";             		
	//echo "Sucursal : ";
    echo "</td><td align='center'>";  
	  
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    
    echo "</form>";
    
}elseif($Menu ==  3){
    	 
    echo "<br><div align='center'>Demanda de ordenes generadas</div><br>";
    
    echo "<form name='form1' method='get' action='demandepto.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
	echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    
	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";
    


	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>"; 
	


    echo "Departamento : ";
    echo "</td><td>";    
	$Depto=mysql_query("select departamento,nombre from dep",$link);
	echo "<select name='Departamento' class='content1'>";
	echo "<option value=''> *  T o d o s </option>";
	while ($Depto1=mysql_fetch_array($Depto)){
		   echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";  
	           
    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Tipo de Servicio : ";
    echo "</td><td class='content1'>";    
    echo "<select name='Servicio' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    echo "<option value='Ordinaria'> Ordinaria </option>";
    echo "<option value='Urgente'> Urgente </option>";
    echo "<option value='Nocturno'> Nocturno </option>";
    echo "<option value='Express'> Express </option>";
    echo "</select>";
    echo "</td></tr>"; 
    echo "</td><td align='center'>";             
    echo "</td><td align='center'>";  
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    
    echo "</form>";
    
}elseif($Menu == 4){

    echo "<br><div align='center'>CORTE DE CAJA </div><br>";

        echo "<form name='form1' method='get' action='impcorte.php'>";
        echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
           
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='Fecha' size='9' value ='$Fecha'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].Fecha,'yyyy-mm-dd',this)>";

    echo "</td></tr><tr><td align='right'>";    
     
    
    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>";    
        
    echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>"; 
	

    echo "Recepcionista, * todos : ";
    echo "</td><td>";            
    echo "<input type='text' class='content1'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;";
    echo "</td></tr><tr><td align='right'>";   

    echo "<tr><td align='right' width='50%'>";    
    echo " Hra.Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' value='07:00' name='HoraI' size='6' >&nbsp;&nbsp;&nbsp;";
    echo "</td>"; 


    echo "<tr><td align='right' width='50%'>";    
    echo " Hra.Final: ";
    echo "</td><td>";    
    echo "<input type='text' value='14:00'  name='HoraF' size='6' >&nbsp;&nbsp;&nbsp;";
    echo "<input type='SUBMIT' value='Enviar'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<input type='SUBMIT' name='Completo' value='Dia_Completo'>";
    echo "</td>"; 
    echo "</form>";


}elseif($Menu ==  5){
    	 
    echo "<br><div align='center'>Relacion de ordenes generadas</div><br>";
    
    echo "<form name='form1' method='get' action='repadeudos.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
	echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";
    
	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value=''> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";             
	echo "Solo Adeudos [Si/No] : ";
    echo "</td><td>";    
	echo "<select name='cAdeudo' class='content1'>";
	echo "<option value='N'>No</option>";
	echo "<option value='S'>Si</option>";
	echo "</select>";           		
    echo "</td></tr><tr height='30'><td align='right'>";             		
    echo "</td><td align='center'>";    
        
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    echo "</form>";

}elseif($Menu ==  6){


    $Fechai=date("Y-m-d");

    echo "<form name='form1' method='get' action='comisionesrel.php'>";


    echo "<br><div align='center'>RELACION DE COMISIONES</div><br>";

    echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";  

        echo "Fecha Inicial: ";
        echo "</td><td>";
        echo "<input type='text'  name='FecI' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)>";
        echo "</td></tr><tr><td align='right'>"; 

        echo "<p>Fecha Final: ";
        echo "</td><td>";
        echo "<input type='text'  name='FecF' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)></p>";
        echo "</td></tr><tr><td align='right'>"; 

    
        echo "Sucursal : ";
        echo "</td><td class='content1'>";    
        echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
        echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
        echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
        echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
        echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
        echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
        echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
        echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
        echo "</td></tr><tr height='30'><td align='right'>";    
                      

        echo "Institucion : ";
        echo "</td><td>";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option></p>";
        echo "</select>";
        echo "</td></tr><tr height='30'><td align='right'>";    

        echo "Promotor Asig. : ";
        echo "</td><td>";
        echo "<select name='Promotor'>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>"; 
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Base'>Base</option>";
          echo "<option selected value='*'> * T o d o s </option></p>";
          echo "</select>";
          echo "</td></tr><tr height='30'><td align='right'>";    

        echo "Medico, * todos : ";
        echo "</td><td>";
        echo "<input type='text'  name='Medico' size='10' value ='*'> &nbsp; &nbsp; </p>";
        echo "</td></tr><tr height='30'><td align='right'>";    



        echo "Medicos con Status[Activo/Inactivo] : ";
        echo "</td><td>";
        echo "<select name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</select>";
        echo "</td></tr><tr height='30'><td align='right'>";             		
        echo "</td><td align='center'>";    
            
        echo "<input class='content1' type='submit' value='Enviar'>";
        echo "</td></tr>";            
        echo "</table>";
        
        echo "</form>";


}elseif($Menu == 12){


    echo "<br><div align='center'>PENDIENTES POR ENTREGAR</div><br>";
    echo "<form name='form1' method='get' action='ordenesabipend.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
    echo "<tr><td align='right' width='30%'>"; 

    
    echo "Fecha Inicial [aaaa-mm-dd]: ";
    echo "</td><td align='center'>";    
    echo "<input type='text'  name='FechaI' size='9' value ='$FechaI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td><td align='right'>";    
    echo "Fecha Final : [aaaa-mm-dd]: ";
    echo "</td><td align='center'>";    
    echo "<input type='text'  name='FechaF' size='9' value ='$FechaF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
   
    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 


    echo "Institucion : ";
    echo "</td><td colspan='3'> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
    echo "<select name='Institucion' class='content1'>";
    echo "<option value=''> *  T o d o s </option>";
    echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
    echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
    while ($Ins=mysql_fetch_array($InsA)){
           echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
    }
    echo "<option selected value=''> * T o d o s </option>";
    echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>"; 


    echo "Departamento : ";
    echo "</td><td colspan='3'> ";    
    $Depto=mysql_query("select departamento,nombre from dep",$link);
    echo "<select name='Departamento'>";
    echo "<option value=''> *  T o d o s </option>";
    while ($Depto1=mysql_fetch_array($Depto)){
           echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
    }
    echo "<option selected value=''> * T o d o s </option>";
    echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Solo Estudios Pendientes [Si/No] : ";
    echo "</td><td colspan='3'>";    
    echo "<select name='Arecepcion'>";
    echo "<option value='N'>No</option>";
    echo "<option value='S'>Si</option>";
    echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Solo Estudios Externos [Si/No] : ";
    echo "</td><td colspan='3'>";    
    echo "<select name='Externo'>";
    echo "<option value='N'>No</option>";
    echo "<option value='S'>Si</option>";
    echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "<img src='lib/email.png' border='0'>&nbsp; Solo Entega por correo [Si/No] : ";
    echo "</td><td colspan='2'>";    
    echo "<select name='correo'>";
    echo "<option value='N'>No</option>";
    echo "<option value='S'>Si</option>";
    echo "</select>";
    echo "</td><td align='center'>";    
        
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";


}elseif($Menu ==  16){
    	 
    echo "<br><div align='center'>Reporte de Gastos</div><br>";
    
    echo "<form name='form1' method='get' action='detpagos.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
	echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT id,referencia FROM cptpagod ORDER BY referencia");
    echo "<select class='InputMayusculas' name='Pagos'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[referencia]'> &nbsp; $Ins[referencia] </option>";
    }
    echo "<option selected value=''> &nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Forma de pago: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT * FROM cpagos ORDER BY id");
    echo "<select class='InputMayusculas' name='Fpago'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[id]'> &nbsp;$Ins[id].- $Ins[concepto] </option>";
    }   
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";

    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Usuario: </td><td>&nbsp; ";
    $InsA   = mysql_query("SELECT uname FROM authuser ORDER BY uname");
    echo "<select class='InputMayusculas' name='Usr'>";           
    while($Ins = mysql_fetch_array($InsA))
    {echo "<option value='$Ins[uname]'> &nbsp; $Ins[uname] </option>";}
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";
          
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos cancelados: </td><td>&nbsp; ";
    echo "<select class='InputMayusculas' name='Cancelado'>";
    echo "<option value='1'>&nbsp; Si </option>";
    echo "<option value='0'>&nbsp; No</option>";
    echo "<option selected value=''>&nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    echo "</td></tr><tr height='30'><td align='right'>";             		
    echo "</td><td align='center'>";    
        
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";

}elseif($Menu ==  17){
    	 
    echo "<br><div align='center'>Resumen de Gastos</div><br>";
    
    echo "<form name='form1' method='get' action='detpagosr.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
	echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT id,referencia FROM cptpagod ORDER BY referencia");
    echo "<select class='InputMayusculas' name='Pagos'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[referencia]'> &nbsp; $Ins[referencia] </option>";
    }
    echo "<option selected value=''> &nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Forma de pago: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT * FROM cpagos ORDER BY id");
    echo "<select class='InputMayusculas' name='Fpago'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[id]'> &nbsp;$Ins[id].- $Ins[concepto] </option>";
    }   
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";

    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Usuario: </td><td>&nbsp; ";
    $InsA   = mysql_query("SELECT uname FROM authuser ORDER BY uname");
    echo "<select class='InputMayusculas' name='Usr'>";           
    while($Ins = mysql_fetch_array($InsA))
    {echo "<option value='$Ins[uname]'> &nbsp; $Ins[uname] </option>";}
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";
          
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos cancelados: </td><td>&nbsp; ";
    echo "<select class='InputMayusculas' name='Cancelado'>";
    echo "<option value='1'>&nbsp; Si </option>";
    echo "<option value='0'>&nbsp; No</option>";
    echo "<option selected value=''>&nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    echo "</td></tr><tr height='30'><td align='right'>";             		
    echo "</td><td align='center'>";    
        
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";

}elseif($Menu == 20){
  	 
    echo "<br><div align='center'>Relacion Servicio Movil de Ordenes Abiertas</div><br>";
    
    echo "<form name='form1' method='get' action='ordeneserv.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
	echo "<input type='text' class='content1'  name='FecI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FecF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td> ";     
    echo "<select name='Sucursal'>";
    $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
    echo "<option value='*'> * T o d o s </option>";
    while ($Cia=mysql_fetch_array($CiaA)){
           echo "<option value='$Cia[0]'>$Cia[0]  $Cia[1]</option>";
    }
    //echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";                       
    echo "</td></tr><tr height='30'><td align='right'>";             
 
       
       

	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";             
        


    echo "Tipo de servicio : ";
    echo "</td><td>";    
    echo "<select name='Servicio'>";
    echo "<option value='1'>* todos</option>";
    echo "<option value='2'>Urgentes</option>";
    echo "<option value='3'>Express</option>";
    echo "</select></p>";
    echo "</td></tr><tr height='30'><td align='right'>";             

           


    echo "Servicio Movil :";
    echo "</td><td>";    
    echo "<select name='Subdepto'>";
    echo "<option value='*'>* Todos</option>";
    echo "<option value='TOMA'>Tomas a Domicilio</option>";
    echo "<option value='RECOLECCION'>Recoleccion de Muestra</option>";
    echo "<option value='TRASLADO'>Traslados</option>";
    echo "<option value='PORTATIL'>Portatiles</option>";
    echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";             		
    echo "</td><td align='center'>";    
        
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    
    echo "</form>";





}elseif($Menu == 30){

    echo "<br><div align='center'>Reporte de Pagos / Adeudos</div><br>";

    echo "<form name='form1' method='get' action='impcorte2.php'>";

    echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

    echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
    echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
    echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
    echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
    echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
    echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Institucion : ";
    echo "</td><td colspan='3'> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
    echo "<select name='Institucion' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Ins=mysql_fetch_array($InsA)){
           echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";    
        
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";

}elseif($Menu == 31){

    echo "<br><div align='center'>Reporte de Pagos / Adeudos 2</div><br>";

    echo "<form name='form1' method='get' action='impcorte3.php'>";

    echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

    echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
    echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
    echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
    echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
    echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
    echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Institucion : ";
    echo "</td><td colspan='3'> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
    echo "<select name='Institucion' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Ins=mysql_fetch_array($InsA)){
           echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";    
        
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";


}elseif($Menu == 32){

    echo "<br><div align='center'>Reporte General Recepcion</div><br>";

    echo "<form name='form1' method='get' action='impcorterecep.php'>";

    echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

    echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
    echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
    echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
    echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
    echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
    echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Institucion : ";
    echo "</td><td colspan='3'> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
    echo "<select name='Institucion' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Ins=mysql_fetch_array($InsA)){
           echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";    
        
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";

}elseif($Menu == 33){

    echo "<br><div align='center'>Resumen de Corte de Caja X Dia</div><br>";

    echo "<form name='form1' method='get' action='impcortexdia.php'>";

    echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
    echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    

    echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";

    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
    echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
    echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
    echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
    echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
    echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Institucion : ";
    echo "</td><td colspan='3'> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
    echo "<select name='Institucion' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Ins=mysql_fetch_array($InsA)){
           echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";    
        
    
    echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    echo "</form>";

}elseif($Menu == 34){
    
    if($Fechas == 1){
        $FecI=$_REQUEST[FecI];
      $FecF=$_REQUEST[FecF];
 }else{
     $FecI=date("Y-m-d");
     $FecF=date("Y-m-d");
 }	 

    echo "<form name='form1' method='get' action='comisiones.php'>";


    echo "<br><div align='center'>CALCULO DE COMISIONES</div><br>";

    echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
	
    echo "<tr><td align='right' width='50%'>";  

        echo "Fecha Inicial: ";
        echo "</td><td>";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)>";
        echo "</td></tr><tr><td align='right'>"; 

        echo "<p>Fecha Final: ";
        echo "</td><td>";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)></p>";
        echo "</td></tr><tr><td align='right'>"; 

    
        echo "Sucursal : ";
        echo "</td><td class='content1'>";    
        echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
        echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
        echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
        echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
        echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
        echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
        echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
        echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
        echo "</td></tr><tr height='30'><td align='right'>";    
                      

        echo "Institucion : ";
        echo "</td><td>";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option></p>";
        echo "</select>";
        echo "</td></tr><tr height='30'><td align='right'>";    

        echo "Promotor Asig. : ";
        echo "</td><td>";
        echo "<select name='Promotor'>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>"; 
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Base'>Base</option>";
          echo "<option selected value='*'> * T o d o s </option></p>";
          echo "</select>";
          echo "</td></tr><tr height='30'><td align='right'>";    

        echo "Medico, * todos : ";
        echo "</td><td>";
        echo "<input type='text'  name='Medico' size='10' value ='*'> &nbsp; &nbsp; </p>";
        echo "</td></tr><tr height='30'><td align='right'>";    



        echo "Medicos con Status[Activo/Inactivo] : ";
        echo "</td><td>";
        echo "<select name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</select>";
        echo "</td></tr><tr height='30'><td align='right'>";             		
        echo "</td><td align='center'>";    
            
        echo "<input class='content1' type='submit' value='Enviar'>";
        echo "</td></tr>";            
        echo "</table>";
        
        echo "</form>";
}elseif ($Menu ==  35){
    	 
        echo "<br><div align='center'>Relacion de ordenes generadas</div><br>";
        
        echo "<form name='form1' method='get' action='ordenesabiope.php'>";
        echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        
        echo "<tr><td align='right' width='50%'>";    
        echo "Fecha Inicial: ";
        echo "</td><td>";    
        echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
        echo "</td></tr><tr><td align='right'>";    
        
        echo "Fecha Final: ";
        echo "</td><td> ";     
        echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
        echo "</td></tr><tr height='30'><td align='right'>";
        
        echo "Institucion : ";
        echo "</td><td> ";    
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion' class='content1'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
        echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</select>";                          
        echo "</td></tr><tr height='30'><td align='right'>";             
            
        echo "Tipo de servicio: ";
        echo "</td><td>";    
        echo "<select name='Servicio' class='content1'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='Ordinaria'> Ordinaria </option>";
        echo "<option value='Urgente'> Urgente </option>";
        echo "<option value='Nocturno'> Nocturno </option>";
        echo "<option value='Express'> Express </option>";
        echo "</select>";
        echo "</td></tr><tr height='30'><td align='right'>";             
        
        echo "Solo Ordenes C/Descto [Si/No] : ";
        echo "</td><td>";    
        echo "<select name='Descto' class='content1'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";
        echo "</td></tr><tr height='30'><td align='right'>";             		
    
        echo "Recepcionista, * todos : ";
        echo "</td><td>";            
        echo "<input type='text' class='content1'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;";
        echo "</td></tr><tr><td align='right'>";             		
    
        echo "Sucursal : ";
        echo "</td><td class='content1'>";    
        echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
        echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
        echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
        echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
        echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br>  ";
        echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
        echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
        echo "</td></tr><tr height='30'><td align='right'>";             		
        echo "</td><td align='center'>";    
            
        echo "<input class='content1' type='submit' value='Enviar'>";
        echo "</td></tr>";            
        echo "</table>";
        
        
        echo "</form>";
        
}elseif($Menu ==  36){
    	 
    echo "<br><div align='center'>Demanda de ordenes generadas</div><br>";
    
    echo "<form name='form1' method='get' action='demandepto2ope.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
	echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    
	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";
    
	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>";  
	



    echo "Departamento : ";
    echo "</td><td>";    
	$Depto=mysql_query("select departamento,nombre from dep",$link);
	echo "<select name='Departamento' class='content1'>";
	echo "<option value=''> *  T o d o s </option>";
	while ($Depto1=mysql_fetch_array($Depto)){
		   echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";  
	           
    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	//echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
	echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>";             		
	//echo "Sucursal : ";
    echo "</td><td align='center'>";  
	  
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    
    echo "</form>";
    
}elseif($Menu ==  37){
    	 
    echo "<br><div align='center'>Demanda de ordenes generadas</div><br>";
    
    echo "<form name='form1' method='get' action='demandeptoope.php'>";
	echo "<table width='85%'align='center' class='letrasubt' border='1' cellpadding='3' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    
	echo "<tr><td align='right' width='50%'>";    
    echo "Fecha Inicial: ";
    echo "</td><td>";    
    echo "<input type='text' class='content1'  name='FechaI' size='9' value ='$FechaI'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr><td align='right'>";    
    
	echo "Fecha Final: ";
    echo "</td><td> ";     
    echo "<input type='text' class='content1'  name='FechaF' size='9' value ='$FechaF'>&nbsp;<img src='lib/calendar.png' border='0' height='28' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
    echo "</td></tr><tr height='30'><td align='right'>";
    


	echo "Institucion : ";
    echo "</td><td> ";    
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
	echo "<select name='Institucion' class='content1'>";
	echo "<option value='*'> *  T o d o s </option>";
	echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
	echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
	while ($Ins=mysql_fetch_array($InsA)){
		   echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";                          
    echo "</td></tr><tr height='30'><td align='right'>"; 
	


    echo "Departamento : ";
    echo "</td><td>";    
	$Depto=mysql_query("select departamento,nombre from dep",$link);
	echo "<select name='Departamento' class='content1'>";
	echo "<option value=''> *  T o d o s </option>";
	while ($Depto1=mysql_fetch_array($Depto)){
		   echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
	}
	echo "<option selected value='*'> * T o d o s </option>";
	echo "</select>";
    echo "</td></tr><tr height='30'><td align='right'>";  
	           
    echo "Sucursal : ";
    echo "</td><td class='content1'>";    
	echo "<input type='checkbox' value='1' name='sucursalt' checked>* Todos <br>";
	echo "<input type='checkbox' value='1' name='sucursal0'>0 Administracion <br> ";
	echo "<input type='checkbox' value='1' name='sucursal1'>1 Matriz <br> ";
	echo "<input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura <br> ";
	echo "<input type='checkbox' value='1' name='sucursal3'>3 Tepexpan <br> ";
    echo "<input type='checkbox' value='1' name='sucursal4'>4 Los Reyes <br> ";
    echo "<input type='checkbox' value='1' name='sucursal5'>5 Camarones <br>  ";
    echo "<input type='checkbox' value='1' name='sucursal6'>6 San Vicente ";
    echo "</td></tr><tr height='30'><td align='right'>"; 

    echo "Tipo de Servicio : ";
    echo "</td><td class='content1'>";    
    echo "<select name='Servicio' class='content1'>";
    echo "<option value='*'> *  T o d o s </option>";
    echo "<option value='Ordinaria'> Ordinaria </option>";
    echo "<option value='Urgente'> Urgente </option>";
    echo "<option value='Nocturno'> Nocturno </option>";
    echo "<option value='Express'> Express </option>";
    echo "</select>";
    echo "</td></tr>"; 
    echo "</td><td align='center'>";             
    echo "</td><td align='center'>";  
	echo "<input class='content1' type='submit' value='Enviar'>";
    echo "</td></tr>";            
    echo "</table>";
    
    
    echo "</form>";
}


?>

</html>