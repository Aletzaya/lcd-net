<?php
//ponerle a todos los campos su respectivo nombre y el busca de editar en estuio
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();

//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
 
#Saco los valores de las sessiones los cuales no cambian;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1];
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];

#Variables comunes;
$Titulo    = "Detalles por medico";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "medicos.php?buscar=ini";


#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: medicos.php");
    
}elseif($_REQUEST[Boton] == 'Agregar paciente'){

    if($_REQUEST[Institucion] <> ''){  
        
        $Apellidop = strtoupper($_REQUEST[Apellidop]);
        $Apellidom = strtoupper($_REQUEST[Apellidom]);
        $Nombre    = strtoupper($_REQUEST[Nombre]);            
        $Nombrec   = $Apellidop . " " . $Apellidom . " " . $Nombre;
        
        $cSql =  "INSERT INTO med (nombre,apellidop,apellidom,nombrec,dirconsultorio,dirparticular,
               locconsultorio,locparticular,telconsultorio,telparticular,especialidad,subespecialidad,cedula,
               municipio,codigo,mail,telcelular,zona,rfc,diasconsulta,hravisita,institucion,dependencia,status,
               comisiones,comision,fecha,fechaa,fechanac,refubicacion,telinstitucion,fecharev,participacion,
               servicio,observaciones,munconsultorio,munparticular,ruta,m01,m02,m03,m04,m05,m06,m07,m08,m09,m10,
               m11,m12,institucionp,estado,edocons,codigosis,usr,fecmod,usrmod,clasificacion,promotorasig,redsoc,
               mailalt,clave,rangopag,ordtrabajo,mensajee,histcli,novisitas,descper,descfam,otrodato,usralta,fechaalta,
               adhesion,inai,arco,numero,interior,todoporcorreo,medico)
               VALUES
              ('$Nombre','$Apellidop','$Apellidom','$Nombrec','$_REQUEST[Dirconsultorio]','$_REQUEST[Dirparticular]',
               '$_REQUEST[Locconsultorio]','$_REQUEST[Locparticular]','$_REQUEST[Telconsultorio]','$_REQUEST[Telparticular]','$_REQUEST[Especialidad]',
               '$_REQUEST[Especialidad]','$_REQUEST[Subespecialidad]','$_REQUEST[Cedula]','$_REQUEST[Municipio]','$_REQUEST[Codigo]','$_REQUEST[Mail]',
               '$_REQUEST[Telcelular]','$_REQUEST[Zona]','$_REQUEST[Rfc]','$_REQUEST[Diasconsulta]','$_REQUEST[Hravisita]','$_REQUEST[Institucion]',
               '$_REQUEST[Dependencia]','$_REQUEST[Status]','$_REQUEST[Comisiones]','$_REQUEST[Comision]','$_REQUEST[Fecha]','$_REQUEST[Fechaa]',
               '$_REQUEST[Fechanac]','$_REQUEST[Refubicacion]','$_REQUEST[Telinstitucion]','$_REQUEST[Fecharev]','$_REQUEST[Participacion]',
               '$_REQUEST[Servicio]','$_REQUEST[Observaciones]','$_REQUEST[Munconsultorio]','$_REQUEST[Munparticular]','$_REQUEST[Ruta]',
               '$_REQUEST[M01]','$_REQUEST[M02]','$_REQUEST[M03]','$_REQUEST[M04]','$_REQUEST[M05]','$_REQUEST[M06]','$_REQUEST[M07]','$_REQUEST[M08]'
               '$_REQUEST[M09]','$_REQUEST[M10]','$_REQUEST[M11]','$_REQUEST[M12]','$_REQUEST[Institucionp]','$_REQUEST[Estado]',
               '$_REQUEST[Edocons]','$_REQUEST[Codigosis]','$_REQUEST[Usr]','$_REQUEST[Fecmod]',$_REQUEST[Usrmod],'$_REQUEST[Clasificacion]',
               '$_REQUEST[Promotorasig]','$_REQUEST[Redsoc]','$_REQUEST[Mailalt]','$_REQUEST[Clave]','$_REQUEST[Rangopag]','$_REQUEST[Ordtrabajo]',
               '$_REQUEST[Mensajee]','$_REQUEST[Histcli]','$_REQUEST[Novisitas]','$_REQUEST[Descper]','$_REQUEST[Descfam]',
               '$_REQUEST[Otrodato]','$_REQUEST[Usralta]','$_REQUEST[Fechalta]','$_REQUEST[Adhesion]','$_REQUEST[Inai]','$_REQUEST[Arco]',
               '$_REQUEST[Numero]','$_REQUEST[Interior]','$_REQUEST[Todoporcorreo]','$_REQUEST[Medico]')";    
                
    
        $cProceso = "Agrega med $cId ";
    
        if (!mysql_query($cSql)) {
            echo "<div align='center'>$cSql</div>";
            $Archivo = 'MED';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    
        $cId = mysql_insert_id();
    
        $Nombrec = $cId . ' ' . $_REQUEST[Clave] . ' ' . $Nombrec;
        
        $cSql = "UPDATE med SET find = $Nombrec WHERE id=$cId";
        if (!mysql_query($cSql)) {
            echo "<div align='center'>$cSql</div>";
            $Archivo = 'MED';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
            
        logs('Agr(med)','$Usr','$cProceso');
        
    }else{
        
       $Msj = "Aun no has dado la institucion"; 
        
    }    
    /*
    $cSql = "update cliale SET id='$cId',usr='' WHERE usr='$Usr'";
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLIALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    */

    header("Location: medicos.php?busca=$cId");
        
}elseif ($_REQUEST[Boton] == 'Actualizar') {
                
    $Apellidop = strtoupper($_REQUEST[Apellidop]);
    $Apellidom = strtoupper($_REQUEST[Apellidom]);
    $Nombre    = strtoupper($_REQUEST[Nombre]);            
    $Nombrec   = $Apellidop . " " . $Apellidom . " " . $Nombre;
    $Find      = $busca . ' ' . strtoupper($_REQUEST[Medico]) . ' ' . $Nombrec;    
    $cSql =  "UPDATE med SET 
              nombre='$Nombre',apellidop='$Apellidop',apellidom='$Apellidom',
              nombrec='$Nombrec',dirconsultorio='$_REQUEST[Dirconsultorio]',dirparticular='$_REQUEST[Dirparticular]',
              medico='$_REQUEST[Medico]',    
              locconsultorio='$_REQUEST[Locconsultorio]',locparticular='$_REQUEST[Locparticular]',telconsultorio='$_REQUEST[Telconsultorio]',telparticular='$_REQUEST[Telparticular]',especialidad='$_REQUEST[Especialidad]',
              especialidad='$_REQUEST[Especialidad]',subespecialidad='$_REQUEST[Subespecialidad]',cedula='$_REQUEST[Cedula]',municipio='$_REQUEST[Municipio]',codigo='$_REQUEST[Codigo]',mail='$_REQUEST[Mail]',
              telcelular='$_REQUEST[Telcelular]',zona='$_REQUEST[Zona]',rfc='$_REQUEST[Rfc]',diasconsulta='$_REQUEST[Diasconsulta]',hravisita='$_REQUEST[Hravisita]',institucion='$_REQUEST[Institucion]',
              dependencia='$_REQUEST[Dependencia]',status='$_REQUEST[Status]',comisiones='$_REQUEST[Comisiones]',comision='$_REQUEST[Comision]',fecha='$_REQUEST[Fecha]',fechaa='$_REQUEST[Fechaa]',
              fechanac='$_REQUEST[Fechanac]',refubicacion='$_REQUEST[Refubicacion]',telinstitucion='$_REQUEST[Telinstitucion]',fecharev='$_REQUEST[Fecharev]',participacion='$_REQUEST[Participacion]',
              servicio='$_REQUEST[Servicio]',observaciones='$_REQUEST[Observaciones]',munconsultorio='$_REQUEST[Munconsultorio]',munparticular='$_REQUEST[Munparticular]',ruta='$_REQUEST[Ruta]',
              m01='$_REQUEST[M01]',m02='$_REQUEST[M02]',m03='$_REQUEST[M03]',m04='$_REQUEST[M04]',m05='$_REQUEST[M05]',m06='$_REQUEST[M06]',m07='$_REQUEST[M07]',m08='$_REQUEST[M08]',
              m09='$_REQUEST[M09]',m10='$_REQUEST[M10]',m11='$_REQUEST[M11]',m12='$_REQUEST[M12]',institucionp='$_REQUEST[Institucionp]',estado='$_REQUEST[Estado]',
              edocons='$_REQUEST[Edocons]',codigosis='$_REQUEST[Codigosis]',usr='$_REQUEST[Usr]',fecmod='$_REQUEST[Fecmod]',usrmod='$_REQUEST[Usrmod]',clasificacion='$_REQUEST[Clasificacion]',
              redsoc='$_REQUEST[Redsoc]',mailalt='$_REQUEST[Mailalt]',clave='$_REQUEST[Clave]',find='$Find',
              rangopag='$_REQUEST[Rangopag]',ordtrabajo='$_REQUEST[Ordtrabajo]',mensajee='$_REQUEST[Mensajee]',histcli='$_REQUEST[Histcli]',
              novisitas='$_REQUEST[Novisitas]',descper='$_REQUEST[Descper]',descfam='$_REQUEST[Descfam]',otrodato='$_REQUEST[Otrodato]',
              usralta='$_REQUEST[Usralta]',fechalta='$_REQUEST[Fechalta]',adhesion='$_REQUEST[Adhesion]',inai='$_REQUEST[Inai]',arco='$_REQUEST[Arco]',
              numero='$_REQUEST[Numero]',interior='$_REQUEST[Interior]',todoporcorreo='$_REQUEST[Todoporcorreo]' 
              WHERE id ='$busca'";

    
    $cProceso = "Actualizo est institucion ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'MED';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(med)','$Usr','$cProceso');
    
    
    header("Location: medicos.php");
    
}elseif($_REQUEST[cId] <> '' ){
    
    $cSql =  "DELETE FROM med WHERE medico = '$_REQUEST[cId]'";    

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}


$CpoA      = mysql_query("SELECT * FROM med WHERE id='$busca'");
$Cpo       = mysql_fetch_array($CpoA);

if($Cpo[fechanac] <> '0000-00-00'){
    $Fechanac  = $Cpo[fechanac];
    $Fecha   = date("Y-m-d");
    $array_nacimiento = explode ( "-", $Fechanac ); 
    $array_actual = explode ( "-", $Fecha ); 
    $Anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
    $Meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
    $Dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 
    if($Meses < 0 ){
       $Meses = 12 + $Meses;
       $Anos  = $Anos - 1;
    }
   
}else{
    $MsjFechan = 'dato no proporcionado';  
}  


require ("config.php");							   //Parametros de colores;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php    

echo '<body topmargin="1">';
encabezados();
PonTitulo($Titulo);
menu($Gmenu);

    //submenu();

//Tabla contenedor de brighs
echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";

        
//Tabla Principal    
    echo "<table border='0' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr>";
        echo "<td bgcolor='$Gbgsubtitulo'  width='50%' class='letratitulo' align='center'>";
        echo "Datos principales";
    echo "</td><td bgcolor='$Gbgsubtitulo'  width='50%' class='letratitulo' align='center'>";
        echo "Otros datos...";
    echo "</td></tr>";
    
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";


        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
            echo "<a class='letrap'><div align='center'>Datos personales necesarios</div></a>";
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            

            echo "<tr class='letrap'><td colspan='2'>";
            echo "&nbsp; &nbsp; &nbsp;Ap.paterno: ";
            echo "<input class='cinput' type='text' name='Apellidop' size='15' value='$Cpo[apellidop]'> &nbsp; ";
            
            echo "Ap.materno: ";
            echo "<input class='cinput' type='text' name='Apellidom' size='15' value='$Cpo[apellidom]'> &nbsp; ";

            echo "Nombre: ";
            echo "<input class='cinput' type='text' name='Nombre' size='20' value='$Cpo[nombre]'>";
        
            echo "</td></tr>";

            cInput("Clave :", "Text", "10", "Medico", "right", $Cpo[medico], "10", 1, 0, '');    
            
            echo "<tr height='25'><td class='letrap' align='right'>Clasificacion : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Clasificacion'>";
            echo "<option value='A'>A</option>";
            echo "<option value='B'>B</option>";
            echo "<option value='C'>C</option>";
            echo "<option value='D'>D</option>";
            echo "<option selected value='$Cpo[clasificacion]]'>$Cpo[clasificacion]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";

            cInput("Especialidad :", "Text", "20", "Especialidad", "right", $Cpo[especialidad], "25", 1, 0, '');    
            cInput("Cédula :", "Text", "25", "Cedula", "right", $Cpo[cedula], "37", 1, 0, '');
            cInput("Subespecialidad :", "Text", "20", "Subespecialidad", "right", $Cpo[subespecialidad], "27", 1, 0, ''); 


            echo "<tr class='letrap'><td align='right'>";
            echo "Fecha nacimiento:&nbsp;aaaa-mm-dd</td><td>";
            echo "<input type='text'class='letrap' name='Fechanac' value='$Cpo[fechanac]' size='12'> &nbsp; ";
            echo "Edad:&nbsp;";
	    echo "<input type='text' class='letrap' name='Anos' value='$Anos' size='3'> &nbsp;";
            echo "</td></tr>";
            /*
            echo "<tr class='letrap'><td align='right'>";
            
            echo " Sexo:&nbsp;</td><td>";				 
            if($Cpo[sexo] == F){$Fem = checked;$Mas='';}else{$Mas=checked;$Fem='';}
            
       	    echo "<input type='radio' name='Sexo' value='M' $Mas>Masculino ";
            echo "<input type='radio' name='Sexo' value='F' $Fem>Femenino &nbsp; ";
            echo "</td></tr>";
            */   
            //cInput("Historial :", "Text", "20", "Xxx", "right", $Cpo[xxx], "17", 1, 1, '');
            //cInput("Fecha de actualizacion :", "Text", "20", "Estudio", "right", $Cpo[estudio], "17", 1, 1, ''); 
            echo "<table>";
            
            
            
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
            //cTable('90%', '0',$Titulo);
            echo "<a class='letrap'><div align='center'>Direccion particular</div></a>";
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("Direccion:", "Text", "60", "Dirparticular", "right", $Cpo[dirparticular], "65", 1, 0, 'calle');    
            //cInput("Numero :", "Text", "10", "Numero", "right", $Cpo[numero], "15", 1, 0, '');    
            echo "<tr height='25' class='letrap'><td align='right'>Numero : &nbsp;</td><td>";
            echo "&nbsp;<input class='letrap' type='text' size='5' name='Numero' value='$Cpo[numero]'>";
            echo "&nbsp; Interior: <input class='letrap' type='text' size='5' name='Interior' value='$Cpo[interior]'>";
            echo "</td><tr>";
            
            //cInput("Interior :", "Text", "10", "Interior", "right", $Cpo[interior], "15", 1, 0, '');    
            cInput("Colonia:", "Text", "60", "Locparticular", "right", $Cpo[locparticular], "65", 1, 0, '');    
            cInput("Municipio:", "Text", "40", "Municipio", "right", $Cpo[municipio], "40", 1, 0, '');    
            cInput("Codigo postal :", "Text", "10", "Codigo", "right", $Cpo[codigo], "15", 1, 0, '');            
            //cInput("Segun sistema :", "Text", "10", "Codigosis", "right", $Cpo[codigosis], "15", 1, 0, '');
            /*
            echo "<tr height='25'><td class='letrap' align='right'>Estado : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Estado'>";
            echo "<option value='1'>Si</option>";
            echo "<option value='2'>No</option>";
            echo "<option selected value='$Cpo[estado]]'>$Cpo[estado]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";
            
            echo "<tr height='25'><td class='letrap' align='right'>Municipio : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Municipio'>";
            echo "<option value='1'>Si</option>";
            echo "<option value='2'>No</option>";
            echo "<option selected value='$Cpo[municipio]]'>$Cpo[municipio]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";              
            ;
            */
            cInput("Telefono :", "Text", "10", "Telparticular", "right", $Cpo[telparticular], "15", 1, 0, '');    
            cInput("Telefono del consultorio :", "Text", "20", "Telconsultorio", "right", $Cpo[telconsultorio], "20", 1, 0, '');
            cInput("Correo electronico :", "Text", "35", "Mail", "right", $Cpo[mail], "40", 1, 0, '');    
            //cInput("Correo alternativo :", "Text", "35", "Mailalt", "right", $Cpo[mailalt], "40", 1, 0, '');   
            //cInput("Red Social :", "Text", "30", "Redsoc", "right", $Cpo[redsoc], "40", 1, 0, '');    
            //cInput("Zona :", "Text", "30", "Zona", "right", $Cpo[zona], "40", 1, 0, '');                
            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Referencia de ubicacion:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Refubicacion' class='letrap' cols='49' rows='4'>$Cpo[refubicacion]</TEXTAREA>";
            echo "</td></tr>";
            
            echo "<tr height='20'><td class='letrap' align='right'>Enviar resultados por correo : &nbsp;</td><td>";
            if($Cpo[todoporcorreo]=='Si'){$cSi=checked;}elseif($Cpo[todoporcorreo]=='No'){$cNo=checked;}
            echo "<span class='content2'>";
            echo " &nbsp; &nbsp; <input type='radio' name='Todoporcorreo' value='Si' $cSi> Si ";
            echo " &nbsp; &nbsp; <input type='radio' name='Todoporcorreo' value='No' $cNo> No";
            echo " &nbsp; &nbsp; forzosamente</span>"; 
            echo "</td><tr>";
            
            //echo "<table>";
            echo "</table>";
            
        Botones();
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";
            echo "<a class='letrap'><div align='center'>Datos de promocion</div></a>";
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='0' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

            //echo "<tr><td colspan='2' align='center'  bgcolor='$Gbgsubtitulo'><div class='letrasubt'>Datos personales</td></tr>";

            
            cInput("Dias de consulta :", "Text", "30", "Diasconsulta", "right", $Cpo[diasconsulta], "30", 1, 0, '');
            cInput("Horarios de consulta :", "Text", "20", "Hraconsulta", "right", $Cpo[hraconsulta], "25", 1, 0, '');
            cInput("Promotor asignado :", "Text", "10", "Promotorasig", "right", $Cpo[promotorasig], "15", 1, 0, '');
            cInput("% de comision :", "Text", "10", "Comision", "right", $Cpo[comision], "15", 1, 0, '');
            cInput("Zona :", "Text", "10", "Zona", "right", $Cpo[zona], "15", 1, 0, '');
            cInput("Rango de pago :", "Text", "10", "Rangopag", "right", $Cpo[rangopag], "15", 1, 0, '');
            cInput("Institucion :", "Text", "10", "Institucion", "right", $Cpo[institucion], "15", 1, 0, '');
            cInput("Persona autorizada para recibir comision :", "Text", "30", "Promotorasig", "right", $Cpo[promotorasig], "30", 1, 0, ''); 
            cInput("No. de visitas :", "Text", "10", "Novisitas", "right", $Cpo[novisitas], "15", 1, 0, '');           
            cInput("Solicitud de ordenes de trabajo :", "Text", "20", "Ordtrabajo", "right", $Cpo[Ordtrabajo], "20", 1, 0, '');
            cInput("Mensajes emergentes :", "Text", "60", "Mensajee", "right", $Cpo[mensajee], "80", 1, 0, '');

            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Historial de demanda de clientes:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Servicio' class='Histcli' cols='49' rows='4'>$Cpo[histcli]</TEXTAREA>";
            echo "</td></tr>";
            
            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Solicitud de servicio:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Servicio' class='letrap' cols='65' rows='4'>$Cpo[servicio]</TEXTAREA>";
            echo "</td></tr>";
            
            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Observaciones:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Observaciones' class='letrap' cols='65' rows='4'>$Cpo[observaciones]</TEXTAREA>";
            echo "</td></tr>";   

                        

            echo "<tr height='10' bgcolor='#e1e1e1'><td></td><td></td></tr>";             
            
            echo "<tr height='25' class='letrap'><td align='right'>Reg.movimientos</td><td> &nbsp; &nbsp; ";            
            echo "<b>Dias de entrega</b>";                        
            echo "No.veces: $Cpo[numveces] &nbsp; &nbsp; &nbsp; ";
            echo "Usr: $Cpo[usr]&nbsp; &nbsp; &nbsp; ";
            echo "Sucursal: $Cpo[suc] &nbsp; &nbsp; &nbsp; ";
            echo "Ult.usuario: $Cpo[usrmod]";
            echo "</td></tr>";

            
            
            echo "</table>";
            
echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
            echo "<a class='letrap'><div align='center'>Descuentos y beneficios</div></a>";
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("% Descuento personal :", "Text", "20", "Descper", "right", $Cpo[descper], "20", 1, 0, '');    
            cInput("% Descuento familiar :", "Text", "20", "Descfam", "right", $Cpo[descfam], "20", 1, 0, '');
            cInput("Otro dato  :", "Text", "50", "Otrodato", "right", $Cpo[otrodato], "60", 1, 0, '');    
echo "</table>";

echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
            echo "<a class='letrap'><div align='center'>Datos de registro</div></a>";
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("Usuario Alta :", "Text", "40", "Usralta", "right", $Cpo[usralta], "50", 1, 0, '');    
            cInput("Fecha Alta :", "Text", "20", "Fechalta", "right", $Cpo[fechalta], "20", 1, 0, '');
            cInput("Usuario modificación  :", "Text", "20", "Usrmod", "right", $Cpo[usrmod], "20", 1, 0, '');
            cInput("Fecha modificación :", "Text", "20", "Fecmod", "right", $Cpo[fecmod], "20", 1, 0, '');    
echo "</table>";
echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
            echo "<a class='letrap'><div align='center'>Nivel de datos</div></a>";
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("Contrato de Adhesión :", "Text", "30", "Adhesion", "right", $Cpo[adhesion], "30", 1, 0, '');    
            cInput("Aviso INAI :", "Text", "30", "Inai", "right", $Cpo[inai], "30", 1, 0, '');
            cInput("Derechos ARCO  :", "Text", "30", "Arco", "right", $Cpo[arco], "30", 1, 0, '');
echo "</table>";
    //Cierra tabla principal la de dos cuadros        
    echo "</td></tr>";        
    echo "</table>"; 
echo '</form>';
echo '</body>';

?>
<script type="application/javascript">
(function() {
    function ocultar(one, two, three, four, five) {
        $.each([one, two, three, four, five], function(index, elm) {
            if($(elm).css('display')) {
                $(elm).slideUp('fast');
            }
        });
    }
    jQuery(function($) {

        //-----Recepcion Menu
        $('#recepcion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-recepcion').css('display') == 'none') {
                $('#two-level-recepcion').slideDown('fast');
                ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-recepcion').mouseleave(function () {
            if($('#two-level-recepcion').css('display')) {
                $('#two-level-recepcion').slideUp('fast');
            }
        });
        //-----Facturacion Menu
        $('#facturacion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-facturacion').css('display') == 'none') {
                $('#two-level-facturacion').slideDown('fast');
                ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-recepcion', '#two-level-moviles');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-facturacion').mouseleave(function () {
            if($('#two-level-facturacion').css('display')) {
                $('#two-level-facturacion').slideUp('fast');
            }
        });		

        //--------Catalogos Menu
        $('#catalogos').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-catalogos').css('display') == 'none') {
                $('#two-level-catalogos').slideDown('fast');
                ocultar('#two-level-recepcion', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-catalogos').mouseleave(function () {
            if($('#two-level-catalogos').css('display')) {
                $('#two-level-catalogos').slideUp('fast');
            }
        });

        //-----Ingresos Menu
        $('#ingresos').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-ingresos').css('display') == 'none') {
                  $('#two-level-ingresos').slideDown('fast');
                  ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-ingresos').mouseleave(function () {
            if($('#two-level-ingresos').css('display')) {
                  $('#two-level-ingresos').slideUp('fast');
              }

        });

        //------Reportes Menu
        $('#reportes').mouseover(function () {
              // Show hidden content IF it is not already showing
            if($('#two-level-reportes').css('display') == 'none') {
                $('#two-level-reportes').slideDown('fast');
                ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-moviles', '#two-level-facturacion','#two-level-ingresos', '#two-level-procesos');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-reportes').mouseleave(function () {
            if($('#two-level-reportes').css('display')) {
                $('#two-level-reportes').slideUp('fast');
            }
        });
		        //-----Moviles Menu
        $('#moviles').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-moviles').css('display') == 'none') {
                  $('#two-level-moviles').slideDown('fast');
                  ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-ingresos');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-moviles').mouseleave(function () {
            if($('#two-level-moviles').css('display')) {
                  $('#two-level-moviles').slideUp('fast');
              }

        });


    });
})();
</script>
</html>