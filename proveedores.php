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
      
      $_SESSION["OnToy"] = array('','','prv.id','Asc',$Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
      
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
$busca = strtolower($busca);
$pagina   = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort     = $_SESSION[OnToy][3];        
$Mnu = $_SESSION[Mnu];

$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
 
//echo "El valor de retornar es $RetSelec";

$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Cat='Provedor';

#Variables comunes;
$Titulo    = "Proveedores";
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Id        = 22;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 2) {
    $Dsp = 'Filtro activo';
}


$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" prv.nombre like '%$P[$i]%' ";}else{$BusInt=$BusInt." and prv.nombre like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" prv.nombre like '%$busca%' ";  
// $Suc='*';
}



#Armo el query segun los campos tomados de qrys;


if( $busca == ''){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE id>= 0"; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE id='$busca'";

}else{
    
    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt";

}



$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " $OrdenDef LIKE  '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " AND $OrdenDef like '%$P[$i]%' ";
        }
    }
} else {
    $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}





//echo $cSql;

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-"," ", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>						   //Parametros de colores;

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Proveedores ::..</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>

<?php    
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu,$Gusr);
    ?>
    <script src="./controladores.js"></script>
<?php
    //submenu();
//Tabla contenedor de brighs
    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr>';
    echo '<tr>';
    echo '<td height="380" valign="top">';
    
    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
    //echo $sql;

    $res   = mysql_query($sql);
            
    $Pos   = strrpos($_SERVER[PHP_SELF],".");
    $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #
    
    while($rg=mysql_fetch_array($res)){
        
        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";       
        //echo "<tr>";

        if($RetSelec <>''){
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$RetSelec?Paciente=$rg[id]'>Seleccionar</a></td>";
        }else{
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[id]'>Editar</a></td>";
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('proveedorpdf.php?busca=$rg[id]')><i class='fa fa-print fa-2x' aria-hidden='true'></i></a></td>";

        }

        Display($aCps,$aDat,$rg);
        
        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');>Eliminar</a></td>";

        echo "</tr>";

        $nRng++;

    }

    echo "</table>";
echo '</td>';
echo '</tr>';
echo '</table>';
    
    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

    CuadroInferior4($busca);


    echo '</body>';
    ?>

</html>
<?php
mysql_close();
?>
