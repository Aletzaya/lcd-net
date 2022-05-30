<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

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
$Titulo    = " Edita datos de institucion";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "institu.php?buscar=ini";


#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: institu.php");
    
}elseif($_REQUEST[Boton] == 'Agregar paciente'){

    if($_REQUEST[Institucion] <> ''){  
        
        $Nombrec = $_REQUEST[Apellidop] . " " . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];
        
        $cSql =  "INSERT INTO inst (nombre,alias,direccion,localidad,municipio,referencia,codigo,
            rfc,fax,telefono,director,subdirector,lista,mail,condiciones,envio,otro,status,observaciones,servicio,
            administrativa,suplente,todo,descuento,msjadministrativo,colonia,telefonodos,correodos,especialidad,
            subespecialidad,clasificacion,paginaweb,redsocial,promotorasignado,comision,diasdeatenc,horariosdeaten,
            horariosdevisit,ruta,zona,historial,solicitudot,solicituddeserv,usralta,usuariomod,fechamod,contratoadhesion
            ifai,arco)
              VALUES
              ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]','$_REQUEST[Referencia]',
               '$_REQUEST[Codigo]','$_REQUEST[Rfc]','$_REQUEST[Fax]','$_REQUEST[Telefono]','$_REQUEST[Director]',
               '$_REQUEST[Subdirector]','$_REQUEST[Lista]','$_REQUEST[Mail]','$_REQUEST[Condiciones]','$_REQUEST[Envio]','$_REQUEST[Otro]',
               '$_REQUEST[Status]','$_REQUEST[Observaciones]','$_REQUEST[Servicio]','$_REQUEST[Administrativa]','$_REQUEST[Suplente]','$_REQUEST[Todo]',
               '$_REQUEST[Descuento]','$_REQUEST[Msjadministrativo]','$_REQUEST[Colonia]','$_REQUEST[Telefonodos]',
               '$_REQUEST[Correodos]','$_REQUEST[Especialidad]','$_REQUEST[Subespecialidad]','$_REQUEST[Clasificacion]',
               '$_REQUEST[Paginaweb]','$_REQUEST[Redsocial]','$_REQUEST[Promotorasignado]','$_REQUEST[Comision]',
               '$_REQUEST[Diasdeatenc]','$_REQUEST[Horariosdeaten]','$_REQUEST[Horariosdevisit]','$_REQUEST[Ruta]',
               '$_REQUEST[Zona]','$_REQUEST[Historial]','$_REQUEST[Solicitudot]','$_REQUEST[Solicituddeserv]','$_REQUEST[Usuralta]',
               '$_REQUEST[Usuariomod]','$_REQUEST[Fechamod]','$_REQUEST[Contratoadhesion]','$_REQUEST[Ifai]',
               '$_REQUEST[Arco]')";    

        $cId = mysql_insert_id();
    
        $cProceso = "Agrega inst $cId ";
    
        if (!mysql_query($cSql)) {
            echo "<div align='center'>$cSql</div>";
            $Archivo = 'INST';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    
        logs('Agr(inst)','$Usr','$cProceso');
        
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

    header("Location: institu.php?busca=$cId");
        
}elseif ($_REQUEST[Boton] == 'Actualizar') {
            
    $cSql =  "UPDATE inst SET nombre='$_REQUEST[Nombre]',alias='$_REQUEST[Alias]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',municipio='$_REQUEST[Municipio]',referencia='$_REQUEST[Referencia]',codigo='$_REQUEST[Codigo]',
            rfc='$_REQUEST[Rfc]',fax='$_REQUEST[Fax]',telefono='$_REQUEST[Telefono]',director='$_REQUEST[Director]',subdirector='$_REQUEST[Subdirector]',lista='$_REQUEST[Lista]',mail='$_REQUEST[Mail]',condiciones='$_REQUEST[Condiciones]',envio='$_REQUEST[Envio]',otro='$_REQUEST[Otro]',status='$_REQUEST[Status]',observaciones='$_REQUEST[Observaciones]',servicio='$_REQUEST[Servicio]',
            administrativa='$_REQUEST[Administrativa]',suplente='$_REQUEST[Suplente]',todo='$_REQUEST[Todo]',descuento='$_REQUEST[Descuento]',msjadministrativo='$_REQUEST[Msjadministrativo]',
            colonia='$_REQUEST[Colonia]',telefonodos='$_REQUEST[Telefonodos]',
            correodos='$_REQUEST[Correodos]',especialidad='$_REQUEST[Especialidad]',subespecialidad='$_REQUEST[Subespecialidad]',
            clasificacion='$_REQUEST[Clasificacion]',paginaweb='$_REQUEST[Paginaweb]',redsocial='$_REQUEST[Redsocial]',
            promotorasignado='$_REQUEST[Promotorasignado]',comision='$_REQUEST[Comision]',
            diasdeatenc='$_REQUEST[Diasdeatenc]',horariosdeaten='$_REQUEST[Horariosdeaten]',horariosdevisit='$_REQUEST[Horariosdevisit]',ruta='$_REQUEST[Ruta]',
            zona='$_REQUEST[Zona]',historial='$_REQUEST[Historial]',solicitudot='$_REQUEST[Solicitudot]',solicituddeserv='$_REQUEST[Solicituddeserv]',usuralta='$_REQUEST[Usuralta]',
            usuariomod='$_REQUEST[Usuariomod]',fechamod='$_REQUEST[Fechamod]',contratoadhesion='$_REQUEST[Contratoadhesion]',ifai='$_REQUEST[Ifai]',
            arco='$_REQUEST[Arco]'
                
WHERE institucion ='$busca'";
           
    $cProceso = "Actualizo inst institucion ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'INST';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(inst)','$Usr','$cProceso');
    
    
    header("Location: institu.php");
    
}elseif($_REQUEST[cId] <> '' ){
    
    $cSql =  "DELETE FROM inst WHERE institucion = '$_REQUEST[cId]'";    

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}


$CpoA      = mysql_query("SELECT * FROM inst WHERE institucion='$busca'");
$Cpo       = mysql_fetch_array($CpoA);

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
           
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("Institucion :", "Text", "30", "Institucion", "right", $Cpo[institucion], "35", 1, 1, '');            
            cInput("Nombre :", "Text", "45", "Nombre", "right", $Cpo[nombre], "60", 1, 0, '');
            cInput("Alias :", "Text", "25", "Alias", "right", $Cpo[alias], "40", 1, 0, '');            
            cInput("Direccion :", "Text", "60", "Direccion", "right", $Cpo[direccion], "70", 1, 0, '');            
            cInput("Colonia :", "Text", "20", "Colonia", "right", $Cpo[colonia], "30", 1, 0, '');
            cInput("Municipio :", "Text", "20", "Municipio", "right", $Cpo[municipio], "30", 1, 0, ''); 
            cInput("Codigo postal :", "Text", "10", "Codigo", "right", $Cpo[codigo], "15", 1, 0, '');                        
            echo "<tr height='25'><td class='letrap' align='right'>Envio de resultados : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Envio'>";
            echo "<option value='si'>Si</option>";
            echo "<option value='no'>No</option>";
            echo "<option selected value='$Cpo[envio]]'>$Cpo[envio]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";
            cInput("Mail: ", "Text", "45", "Mail", "right", $Cpo[mail], "60", 1, 0, '');
            cInput("Telefono :", "Text", "20", "Telefono", "right", $Cpo[telefono], "25", 1, 0, '');
            
            
            cInput("Telefono dos :", "Text", "15", "Telefonodos", "right", $Cpo[telefonodos], "20", 1, 0, '');
            cInput("Correo dos :", "Text", "40", "Correodos", "right", $Cpo[correodos], "50", 1, 0, '');
            cInput("Especialidad :", "Text", "30", "Especialidad", "right", $Cpo[especialidad], "40", 1, 0, '');
            cInput("Subespecialidad :", "Text", "30", "Subespecialidad", "right", $Cpo[subespecialidad], "35", 1, 0, '');
            cInput("Clasificacion :", "Text", "20", "Clasificacion", "right", $Cpo[clasificacion], "25", 1, 0, '');
            cInput("Pagina web :", "Text", "30", "Paginaweb", "right", $Cpo[paginaweb], "37", 1, 0, '');                      
            cInput("Red social :", "Text", "30", "Redsocial", "right", $Cpo[redsocial], "40", 1, 0, ''); 
            cInput("Promotor asignado :", "Text", "40", "Promotorasignado", "right", $Cpo[promotorasignado], "50", 1, 0, '');
            cInput("Comision :", "Text", "20", "Comision", "right", $Cpo[comision], "30", 1, 0, '');
            cInput("Dias de atencion :", "Text", "20", "Diasdeatenc", "right", $Cpo[diasdeatenc], "30", 1, 0, '');
            cInput("Horarios de atencion :", "Text", "20", "Horariosdeaten", "right", $Cpo[horariosdeaten], "30", 1, 0, '');
            cInput("Horarios de visita :", "Text", "20", "Horariosdevisit", "right", $Cpo[horariosdevisit], "25", 1, 0, '');
            cInput("Ruta :", "Text", "20", "Ruta", "right", $Cpo[ruta], "17", 1, 0, '');                      
            cInput("Zona :", "Text", "30", "Zona", "right", $Cpo[zona], "40", 1, 0, ''); 
            cInput("Historial :", "Text", "40", "Historial", "right", $Cpo[historial], "50", 1, 0, '');
            cInput("Solicitud OT :", "Text", "30", "Solicitudot", "right", $Cpo[solicitudot], "40", 1, 0, '');
            cInput("Solicitud de Servicio:", "Text", "40", "Solicituddeserv", "right", $Cpo[solicituddeserv], "50", 1, 0, '');
            cInput("Usuario Alta :", "Text", "25", "Usuralta", "right", $Cpo[usuralta], "30", 1, 0, '');
            cInput("Usuario mod :", "Text", "25", "Usuariomod", "right", $Cpo[usuariomod], "25", 1, 0, '');
            cInput("Fecha modificacion :", "Text", "20", "Fechamod", "right", $Cpo[fechamod], "27", 1, 0, '');                      
            cInput("Contrato de adhesion :", "Text", "60", "Contratoadhesion", "right", $Cpo[contratoadhesion], "70", 1, 0, ''); 
            cInput("IFAI :", "Text", "25", "Ifai", "right", $Cpo[ifai], "25", 1, 0, '');
            cInput("ARCO :", "Text", "20", "Arco", "right", $Cpo[arco], "27", 1, 0, '');
            
            
        echo "</table>";
            botones();
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='0' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

            //echo "<tr><td colspan='2' align='center'  bgcolor='$Gbgsubtitulo'><div class='letrasubt'>Datos personales</td></tr>";
            cInput("Director :", "Text", "40", "Director", "right", $Cpo[director], "50", 1, 0, '');
            cInput("Sub-director :", "Text", "40", "Subdirector", "right", $Cpo[subdirector], "50", 1, 0, '');
            cInput("Suplente :", "Text", "40", "Suplente", "right", $Cpo[suplente], "50", 1, 0, '');
            cInput("Rfc :", "Text", "25", "Rfc", "right", $Cpo[rfc], "30", 1, 0, '');
            cInput("Fax :", "Text", "20", "Fax", "right", $Cpo[fax], "25", 1, 0, '');
            cInput("Desc. institucional :", "Text", "20", "Descuento", "right", $Cpo[descuento], "17", 1, 0, '');                      
            cInput("Otro dato :", "Text", "60", "Otro", "right", $Cpo[otro], "70", 1, 0, ''); 
             
            echo "<tr height='25'><td class='letrap' align='right'>Todos los estudios : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Todo'>";
            echo "<option value='si'>Si</option>";
            echo "<option value='no'>No</option>";
            echo "<option selected value='$Cpo[todo]]'>$Cpo[todo]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";                                   
             
            echo "<tr height='25'><td class='letrap' align='right'>Lista de precios : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Lista'>";
            echo "<option value='1'>1</option>";
            echo "<option value='2'>2</option>";
            echo "<option value='3'>3</option>";
            echo "<option value='4'>4</option>";
            echo "<option value='5'>5</option>";
            echo "<option value='6'>6</option>";
            echo "<option value='7'>7</option>";
            echo "<option value='8'>8</option>";
            echo "<option value='9'>9</option>";
            echo "<option value='10'>10</option>";
            echo "<option value='11'>11</option>";
            echo "<option value='12'>12</option>";
            echo "<option value='13'>13</option>";
            echo "<option value='14'>14</option>";
            echo "<option value='15'>15</option>";
            echo "<option value='16'>16</option>";
            echo "<option value='17'>17</option>";
            echo "<option selected value='$Cpo[lista]]'>$Cpo[lista]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";                      
            echo "<tr height='25' class='letrap'><td align='right'>Status : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Status'>";
            echo "<option value=''>Activo</option>";
            echo "<option value=''>Inactivo</option>";
            echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";
            
            echo "<tr height='25' class='letrap'><td align='right'>Conds de pago : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Condiciones'>";
            echo "<option value=''>Contado</option>";
            echo "<option value=''>Credito</option>";
            echo "<option selected value='$Cpo[condiciones]'>$Cpo[condiciones]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";

            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Observaciones:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Observaciones' class='letrap' cols='65' rows='4'>$Cpo[observaciones]</TEXTAREA>";
            echo "</td></tr>";   
            
            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Caracteristicas de servicio:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Servicio' cols='65' class='letrap' rows='4'>$Cpo[servicio]</TEXTAREA>";
            echo "</td></tr>";   
            
            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Caracteristicas administrativas:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Administrativa' cols='65' class='letrap' rows='4'>$Cpo[administrativa]</TEXTAREA>";
            echo "</td></tr>";   
            
            echo "<tr height='25' class='letrap'><td align='right' valign='bottom'>Mensaje administrativo:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Msjadministrativo' cols='65' class='letrap' rows='4'>$Cpo[msjadministrativo]</TEXTAREA>";
            echo "</td></tr>";   

            echo "<tr height='10' bgcolor='#e1e1e1'><td></td><td></td></tr>";             
            
            echo "<tr class='letrap'><td align='right'>Reg.movimientos</td><td> &nbsp; &nbsp; ";            
                        
            echo "No.veces: $Cpo[numveces] &nbsp; &nbsp; &nbsp; ";
            echo "Usr: $Cpo[usr]&nbsp; &nbsp; &nbsp; ";
            echo "Sucursal: $Cpo[suc] &nbsp; &nbsp; &nbsp; ";
            echo "Ult.usuario: $Cpo[usrmod]";
            echo "</td></tr>";

            
            
            echo "</table>";
    //Cierra tabla principal la de dos cuadros        
    echo "</td></tr>";        
    echo "</table>";  
echo "</form>";  
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
