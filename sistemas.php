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
$Titulo    = "Administrador";
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

require ("config.php");	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php    
echo '<body topmargin="1">';

encabezados();
PonTitulo($Titulo);
menu4();

echo '<table border="0" width="100%"><tr align="center"><td>';
echo '<img src="images/contFake890.jpg" width="890" height="472" /></td>';
echo '</td></tr></table>';
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

        //-----Administracion Menu
        $('#administracion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-administracion').css('display') == 'none') {
                $('#two-level-administracion').slideDown('fast');
                ocultar('#two-level-recursosh', '#two-level-promocion', '#two-level-catalogos');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-administracion').mouseleave(function () {
            if($('#two-level-administracion').css('display')) {
                $('#two-level-administracion').slideUp('fast');
            }
        });
        //-----Recursos H Menu
        $('#recursosh').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-recursosh').css('display') == 'none') {
                $('#two-level-recursosh').slideDown('fast');
                ocultar('#two-level-administracion', '#two-level-promocion', '#two-level-catalogos');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-recursosh').mouseleave(function () {
            if($('#two-level-recursosh').css('display')) {
                $('#two-level-recursosh').slideUp('fast');
            }
        });		

        //--------Promocion Menu
        $('#promocion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-promocion').css('display') == 'none') {
                $('#two-level-promocion').slideDown('fast');
                ocultar('#two-level-administracion', '#two-level-catalogos', '#two-level-recursosh');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-promocion').mouseleave(function () {
            if($('#two-level-promocion').css('display')) {
                $('#two-level-promocion').slideUp('fast');
            }
        });

        //-----Catalogos Menu
        $('#catalogos').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-catalogos').css('display') == 'none') {
                  $('#two-level-catalogos').slideDown('fast');
                  ocultar('#two-level-administracion', '#two-level-recursosh', '#two-level-promocion');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-catalogos').mouseleave(function () {
            if($('#two-level-catalogos').css('display')) {
                  $('#two-level-catalogos').slideUp('fast');
              }

        });



    });
})();
</script>
</html>
<?php
mysql_close();
?>

