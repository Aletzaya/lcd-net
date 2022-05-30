<?php

#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();

if(isset($_REQUEST[busca])){
        
    if($_REQUEST[busca] == ini ){           

      $Pos   = strrpos($_REQUEST[Ret],"?"); //Buscon si en lo k se va a regresar trae ya un valor predef
      
      if($Pos > 0){
         $Retornar  = $_REQUEST[Ret].'&';     
      }else{
         if($_REQUEST[Ret] <> ''){ 
            $Retornar  = $_REQUEST[Ret].'?';               
         }   
      }      
      
      $_SESSION["OnToy"] = array('','','cli.nombrec','Asc',$Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
      
    }elseif($_REQUEST[busca] <> ''){  
      $_SESSION['OnToy'][0]    =  $_REQUEST[busca];    
    }
}    

//Captura los valores que trae y metelos al array
if(isset($_REQUEST[pagina]))  { $_SESSION['OnToy'][1]   = $_REQUEST[pagina]; }
if(isset($_REQUEST[orden]))   { $_SESSION['OnToy'][2]   = $_REQUEST[orden];  } 
if(isset($_REQUEST[Sort]))    { $_SESSION['OnToy'][3]   = $_REQUEST[Sort];   }
if(isset($_REQUEST[Ret]))     { $_SESSION['OnToy'][4]   = $_REQUEST[Ret];    }


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca    = $_SESSION[OnToy][0];
$pagina   = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort     = $_SESSION[OnToy][3];        

$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
 
//echo "El valor de retornar es $RetSelec";

$Usr      = $_COOKIE[USERNAME];
$Nivel    = $_COOKIE[LEVEL];

#Variables comunes;
$Titulo    = "Catalogo de pacientes";
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Id        = 5;             //Numero de query dentro de la base de datos
//

//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";

#Intruccion a realizar si es que mandan algun proceso
if($op=='Si'){                    //Elimina rg

 /*   
 $ExiA = mysql_query("SELECT id FROM fc WHERE cliente='$_REQUEST[cId]' limit 1");
 $Exi  = mysql_fetch_array($ExiA);

 if ($Exi[id] <> ""){
                    $Msj = "No es posible eliminar el cliente, existen facturas registradas";
 }else{
     //$lUp  = mysql_query("DELETE FROM cli WHERE id='$_REQUEST[cId]' limit 1");
     $Msj  = "Registro eliminado";
 }

 */
 
 $lUp  = mysql_query("DELETE FROM cli WHERE id='$_REQUEST[cId]' limit 1");
 $Msj  = "Registro eliminado";
 //$Msj = "Opcion deshabilitada";

}elseif($op=='rs'){
  $Up  = mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
  $op  = '';
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA    = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry     = mysql_fetch_array($QryA);

if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
   $P = str_word_count($busca,1);          //Metelas en un arreglo
   for ($i = 0; $i < $Palabras; $i++) {
     if(!isset($BusInt)){$BusInt=" $OrdenDef LIKE  '%$P[$i]%' ";}else{$BusInt=$BusInt." AND $OrdenDef like '%$P[$i]%' ";}
   }
}else{
   $BusInt = $OrdenDef." LIKE '%".$busca."%'";
}


#Armo el query segun los campos tomados de qrys;
//$cSql   = "SELECT $Qry[campos]  FROM $Qry[froms] LEFT JOIN inst ON cli.institucion=inst.institucion
//           WHERE $BusInt $Qry[filtro]";
#Armo el query segun los campos tomados de qrys;
$cSql   = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt $Qry[filtro]";

//echo $cSql;

$aCps   = SPLIT(",",$Qry[campos]);				// Es necesario para hacer el order by  desde lib;

$aIzq   = array(" ","-","-");				//Arreglo donde se meten los encabezados; Izquierdos
$aDat   = SPLIT(",",$Qry[edi]);					//Arreglo donde llena el grid de datos
$aDer   = array("","","","","","");				//Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");							   //Parametros de colores;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php    

echo '<body topmargin="1">';
echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
echo '<tr>';
echo '<td width="5" height="30" td style="background-image:url(images/CejaAzul1.jpg)" ></td>';
echo '<td style="background-image: url(images/CejaAzulGrad.jpg)">';
echo '<ul id="left-nested">';
echo '<li class="menu"><a href="#" title="" class="menu">CLIENTES |</a></li>';
echo '<li class="menu"><a href="#" title="" class="menu">OPERATIVA |</a></li>';
echo '<li class="menu"><a href="#" title="" class="menu">ADMIN |</a></li>';
echo '<li class="menu"><a href="#" title="" class="menu">OTRO |</a></li>';
echo '<li class="menu"><a href="#" title="">SISTEMAS</a></li>';
echo '</ul>';
echo '</td>';
echo '<td style="background-image: url(images/CejaAzulGrad.jpg)">';
echo '<ul id="right-nested">';
echo '<li><i class="usuario"></i> <a href="#" title="">Usuario</a></li>';
echo '<li><i class="correo"></i><a href="#" title="">Correo</a></li>';
echo '<li><i class="nuevo"></i><a href="#" title="">Nuevo</a></li>';
echo '<li><i class="salir"></i><a href="#" title="">Salir</a>    </li>';
echo '</ul>';
echo '</td>';
echo '<td width="5" height="30" style="background-image:url(images/CejaAzul2.jpg)"></td>';
echo '</tr>';
echo '</table>';
//Se cierra 1er menu
menuu();

echo '</td>';
echo '</tr>';
echo '<tr><td>';
/*Aqui esta el submenu*/
submenu();
echo  '</td></tr>';
echo'</table>';
/*aqui termina el submenu*/

    //Menu 
    echo '<table border="0" align="center" cellpadding="0" cellspacing="0" id="two-level-reportes">';
    echo '<tr>';
    echo '<td width="90" height="30" class="btn-two-level"><a href="#" title="">CLIENTES</a></td>';
    echo '<td width="90" height="30" class="btn-two-level"><a href="#" title="">ESTUDIOS</a></td>';
    echo '<td width="90" height="30" class="btn-two-level"><a href="#" title="">MÉDICOS</a></td>';
    echo '<td width="90" height="30" class="btn-two-level"><a href="#" title="">INSTITUCIONES</a></td>';          
    echo '</tr>';
    echo '</table>';
    


echo '</td></tr></table>';
echo '</table>';
//Se cierran todas las tablas


//Tabla contenedor de brighs
echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
echo '<tr>';
echo '<td height="505" valign="top">';

    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $sql   = $cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
    //echo $sql;

    $res   = mysql_query($sql);
            
    $Pos   = strrpos($_SERVER[PHP_SELF],".");
    $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #
    
    while($rg=mysql_fetch_array($res)){
        
        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";       
        //echo "<tr>";

        if($RetSelec <>''){
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$RetSelec?Paciente=$rg[id]'>Seleccionar</a></td>";
        }else{
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[id]'>Editar</a></td>";
        }

        Display($aCps,$aDat,$rg);

        if($Nivel >= 7 ){
            echo "<td class='Seleccionar' align='center'><a class='edit' href='historico.php?op=bc&busca=$rg[id]'>H.clinico</a></td>";            
        }else{
            echo "<td class='Seleccionar' align='center'> - </td>";            
        }
        
        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');>Eliminar</a></td>";

        echo "</tr>";

        $nRng++;

    }

    echo "</table>";
    
    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

    CuadroInferior($busca);
    
echo '</td>';
echo '</tr>';
echo '</table>';

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
                ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-procesos', '#two-level-facturacion', '#two-level-moviles');
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
<?php
mysql_close();
?>

