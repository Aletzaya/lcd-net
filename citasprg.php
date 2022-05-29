<?php

#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");


require("lib/lib.php");

$link     = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1];
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];

$Vta       = $_SESSION[cVarVal][0];
$lBandera  = false;

$aDia   = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

if(isset($_REQUEST[busca])){
        
    if( $_REQUEST[busca] == ini ){     
        
       $Fecha   = date("Y-m-d");
      
       $_SESSION["OnToy"] = array('','','cli.nombrec','Asc',$Retornar,$Fecha,$Gcia);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)

       CitasTemp($Fecha,$Gcia);
       
    }
            
}    

if($_REQUEST[Boton] == 'Cambiar fecha y/o sucursal'){

    $_SESSION['OnToy'][5] = $_REQUEST[Fecha];
    $_SESSION['OnToy'][6] = $_REQUEST[nSucursal];

    CitasTemp($_REQUEST[Fecha],$_REQUEST[nSucursal]);

}

$nSuc                 = $_SESSION[OnToy][6];
$Fecha                = $_SESSION[OnToy][5];
$busca                = $_REQUEST[busca];

if($_REQUEST[Boton] == 'Agendar cita'){
              
    $lBandera= true;
    $Cita    = $_REQUEST[Cita];
    $Cia     = $nSuc;
    $Dia     = date('w', strtotime($Fecha));                    
    
    //$FolOt   = cZeros(IncrementaFolio('otfolio'),5);
    $FolA    = mysql_query("SELECT otfolio,alias FROM cia WHERE id='$Cia'");
    $lUp     = mysql_query("UPDATE cia SET otfolio = otfolio + 1 WHERE id='$Cia'");
    $Fol     = mysql_fetch_array($FolA);
    $FolioU  = $Fol[otfolio];
    $NomSuc  = $Fol[alias];
                 
    
    //$Fecha = date("Y-m-d");
    //$Hora1 = date("H:i");
    //$Hora2 = strtotime("-60 min",strtotime($Hora1));
    //$hora  = date("H:i",$Hora2);

    $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA = mysql_query("SELECT round(sum(precio*(1-descuento/100)),0) as importe FROM otdnvas WHERE usr='$Gusr' and venta='$Vta'");
    $Otd  = mysql_fetch_array($OtdA);
    
    $OtA  = mysql_query("SELECT * FROM otnvas WHERE usr='$Gusr' AND venta='$Vta'");
    $Ot   = mysql_fetch_array($OtA);

    $HrA  = mysql_query("SELECT horai FROM hrarios WHERE dia='$Dia' AND cita='$Cita'");
    $Hr   = mysql_fetch_array($HrA);
    
    $Abono = $Ot[abono];

    $cPag = 'No';
    if ($Abono + .5 >= $Otd[importe]) { $cPag = 'Si';}	
	
    $cCita = $Dia . "," . $Cita . "," . $Hr[horai];
    
    $cSql = "INSERT INTO ot
          (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,
          receta,importe,descuento,pagada,fecpago,medicon,status,horae,suc,folio,enviarxcorreo,entregaen,citanum)
          VALUES
          ('$Ot[cliente]','$Fecha','$hora','$Ot[medico]','$Ot[fechar]','$Ot[fechae]','$Ot[inst]',
          '$Ot[diagmedico]','$Ot[observaciones]','Cita','$Gusr','$Ot[receta]','$Otd[importe]',
          '$Ot[razon]','$cPag','$Fecha','$Ot[Medicon]','DEPTO','$Ot[horae]','$Cia','$FolOt',
          '$Ot[todoporcorreo]','$Ot[entregaen]','$cCita')";    
    
    //echo $cSql;
    
    EjecutaSql($cSql, 'otdnvas');
    
    $Id = mysql_insert_id();

    $lUp = mysql_query("UPDATE cli SET numveces=numveces+1 WHERE cliente='$Ot[cliente]' LIMIT 1");

    $lUpA = mysql_query("SELECT otdnvas.estudio as idestudio,otdnvas.precio,otdnvas.descuento,est.estudio,est.depto
            FROM est,otdnvas
            WHERE otdnvas.usr='$Gusr' AND otdnvas.venta='$Vta' AND otdnvas.estudio=est.id");    #Checo k bno halla estudios capturados

    $lBd = false;

    while ($lUp = mysql_fetch_array($lUpA)) {
        $cSql = "INSERT INTO otd (orden,estudio,precio,descuento,status,idestudio)
                 VALUES
                 ($Id,'$lUp[estudio]','$lUp[precio]','$lUp[descuento]','$Depto','$lUp[idestudio]')";
        
        EjecutaSql($cSql, 'otdnvas');
            

        if ($lUp[depto] == 2) {                  // Si es que es de radiologia se crea un archivo en base a un formato del word y lo copio
            $FilWord = strtolower("informes/" . $lUp[estudio] . ".doc");
            $FilOut = strtolower("textos/" . $lUp[estudio] . $Id . ".doc");

            if (file_exists($FilWord)) {
                copy($FilWord, $FilOut);
            }
        }
    }
   
    if ($Abono > 0) {
        //$FolCja  = cZeros(IncrementaFolio('cajafolio'),5);
        $FolA    = mysql_query("SELECT cajafolio FROM cia WHERE id='$Cia'");
        $lUp     = mysql_query("UPDATE cia SET cajafolio = cajafolio + 1 WHERE id='$Cia'");
        $Fol     = mysql_fetch_array($FolA);
        $FolioU  = $Fol[otfolio];

        if($Abono > $Otd[importe]){$Abono = $Otd[importe]; }
        
        $Tpago = $_REQUEST[Tpago];
        $cSql  = "INSERT INTO cja (orden,fecha,hora,usuario,importe,tpago,suc,folio)
                  VALUES
    		  ($Id,'$Fecha','$hora','$Gusr','$Abono','$Ot[tpago]','$Gcia','$FolCja')";

        EjecutaSql($cSql, 'otdnvas');
 
    }  
    
    //header("Location: impot.php?busca=$Id");
           
}


#Variables comunes;
$Titulo    = "Citas programadas para el $Fecha";
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];

$NumDia    = date('w', strtotime($Fecha));                    
$cDia      = $aDia[$NumDia];

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
    
<script language="JavaScript1.2">
    
function ValidaCampos(){

    if (document.frmatendera.SeAtenderaEn.value ==""){
       alert("Favor de definir que sucursal va a entender ésta cita");
       document.frmatendera.SeAtenderaEn.focus();
       return false;
    }
    if (document.frmatendera.Cita.value ==""){
       alert("Aun no haz elegido el numero de cita");
       document.frmatendera.SeAtenderaEn.focus();
       return false;
    }
}

</script>

<?php    

echo '<body topmargin="1"><br>';

if($lBandera){
      

  echo "<table border='0' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr><td bgcolor='$Gbgsubtitulo' class='letratitulo' align='center' colspan='2'>";
        echo "Su cita ha sido registrada exitosamente";
    echo "</td>";
    echo "</tr>";
    
    //Tabla de que devide la pantalla en dos
    //Tabla Principal que devide la pantalla en dos
    echo "<tr>";
        echo "<td bgcolor='$Gbgsubtitulo'  width='20%' class='letratitulo' align='center'>";
        echo "Concepto";
    echo "</td><td bgcolor='$Gbgsubtitulo'  width='80%' class='letratitulo' align='center'>";
        echo "Descripcion...";
    echo "</td></tr>";
    
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               echo "<tr><td align='right'>Fecha: </td></tr>";                
        echo "</table>";

        //Renglo para crear un espacio...
         echo "<br>";        
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               echo "<tr><td align='right'>No.de cita:</td></tr>";                
        echo "</table>";

        //Renglo para crear un espacio...
         echo "<br>";        
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               echo "<tr><td align='right'>No.de surcursal:</td></tr>";                
        echo "</table>";
        
        echo "<br><a class='letra' href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a>";
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("", "Text", "3", "Zona", "left", $Fecha, "3", 1, 1, '');                
        echo "</table>";
    
        echo "<br>";
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("", "Text", "3", "Zona", "left", $Cita, "3", 1, 1, '');                
        echo "</table>";

        echo "<br>";
        
        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
               cInput("", "Text", "3", "Zona", "left", $NomSuc, "3", 1, 1, '');                
        echo "</table>";
        
    echo "</td></tr>";        
    echo "</table>";
    
      
}else{
    

    echo "<table width='100%' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    echo "<tr><td width='70%' align='center'>";     
        echo "<form name='frmfeccitas' method='get' action='" . $_SERVER['PHP_SELF'] . "'>";  
        echo '<span class="content1">Sucursal: </span> ';      
        echo '<select name="nSucursal" class="content5" id="Unidades">';
        $CiaSucA = mysql_query("SELECT id,nombre,alias FROM cia WHERE id > 0 ORDER BY id");      
        while ($CiaSuc=mysql_fetch_array($CiaSucA)){      
          echo "<option value='$CiaSuc[id]'>$CiaSuc[alias]</option>";
          if($CiaSuc[id] == $nSuc){
             echo "<option value='$nSuc' selected>$CiaSuc[alias]</option>";
             $NombreCia = $CiaSuc[nombre];
          }
        }   
        echo '</select> &nbsp; ';
           
        echo '<input name="Fecha" type="text" size="11" class="content5" id="Borrar2" value="'.$Fecha.'" > &nbsp; ';
        echo "<input type='submit' class='InputBoton' name='Boton' value='Cambiar fecha y/o sucursal'> &nbsp; [ <span class='content5'>$cDia</span> ]";  
        echo "<p class='content2'> &nbsp; &nbsp; &nbsp; Citas programadas de: <b>$NombreCia</b> para el: $Fecha</p>";
        echo "</form>"; 
        
    echo "</td><td align='center'>";   

    echo "<form name='frmatendera' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";

        echo " &nbsp; <input class='InputBoton' type='submit' name='Boton' value='Agendar cita'> &nbsp ";
               
    echo "</td></tr></table>";    
    /*
    if($busca == 'ini'){
       exit(); 
    }
     
    */
    echo "<table width='100%' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

    echo "<tr style='background-image: url(images/CejaAzulGrad.jpg)'>";


    echo "<td>Seleccionar</td><td>Cita</td><td>Hora</td><td>Cta</td><td>Paciente</td><td>Diag/medico</td><td>Sucursal</td><td>Usr</td></tr>";

    $CitA = mysql_query("SELECT tmcitas.cita,tmcitas.paciente,tmcitas.horai,cli.nombrec,tmcitas.usragr,
            tmcitas.sucursal,cia.alias,tmcitas.diagmedico 
            FROM cia,tmcitas 
            LEFT JOIN cli ON tmcitas.paciente=cli.cliente
            WHERE tmcitas.usr='$Gusr' AND tmcitas.sucursal=cia.id
            ORDER BY cita");
    
    while ($row = mysql_fetch_array($CitA)) {
                            
        if (($nRng % 2) > 0) {$Fdo = 'FFFFFF';} else {$Fdo = $Gfdogrid;}    //El resto de la division;

        echo "<tr class='content_txt' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
        /*
        if ($row[paciente] == 0) {
                echo "<td align='center'><input type='radio' name='Cita' value='$row[cita]'></td>";
        } else {
                echo "<td align='center'> - </td>";
        }         
        */
        echo "<td align='center'><input type='radio' name='Cita' value='$row[cita]'></td>";
        echo "<td align='right' class='content1'>$row[cita]</td>";
        echo "<td align='center' class='content2'>$row[horai]</td>";
        echo "<td class='content2'>".ucwords(strtolower($row[paciente]))."</td>";
        echo "<td class='content2'>".ucwords(strtolower($row[nombrec]))."</td>";
        echo "<td class='content2'>".ucwords(strtolower($row[diagmedico]))."</td>";
        if($row[sucursal] <> 0){
            echo "<td class='content2'>".ucwords(strtolower($row[alias]))."</td>";
        }else{
            echo "<td class='content2'>---</td>";            
        }    
        echo "<td class='content2'>".ucwords(strtolower($row[usragr]))."</td>";
        echo "</tr>";
        $nRng++;
    }

    echo "</table>";

    echo "</td></tr></table>";
  
    echo "</form>";
}   
echo '</body>';

function CitasTemp($Fecha,$cSuc){
    
global $Gusr;    
    
    $Dia = date('w', strtotime($Fecha));                    
                    
    $lUp = mysql_query("DELETE FROM tmcitas WHERE usr='$Gusr'");

    $cSql = "INSERT INTO tmcitas SELECT '$Gusr','0',cita,0,horai,'','' 
             FROM hrarios 
             WHERE dia='$Dia' AND consultorio='1' ORDER BY cita";

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'TMCITAS';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    
    $OtA = mysql_query("SELECT cliente,citanum,recepcionista,suc,diagmedico FROM ot 
           WHERE fecha='$Fecha' AND servicio='Cita' AND suc='$cSuc' ORDER BY citanum");
    
    while ($row = mysql_fetch_array($OtA)) {
     
        $aHrs   = SPLIT(",",$row[citanum]);	// Dia | Cita | Hora;
                
        if($aHrs[1] == $nCita){
           $cSql = "INSERT INTO tmcitas (usr,usragr,paciente,cita,diagmedico) 
                    VALUES 
                   ('$Gusr','$row[recepcionista]','$row[cliente]','$aHrs[1]','$row[diagmedico]')"; 
            if (!mysql_query($cSql)) {
                echo "<div align='center'>$cSql</div>";
                $Archivo = 'TMCITAS';
                die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
            }
              
        }
        
        $cSql  = "UPDATE tmcitas SET 
               tmcitas.paciente='$row[cliente]', usragr='$row[recepcionista]',diagmedico='$row[diagmedico]',
               sucursal='$row[suc]'
               WHERE tmcitas.cita='$aHrs[1]' AND tmcitas.usr='$Gusr' AND tmcitas.paciente='0' limit 1";
        if (!mysql_query($cSql)) {
                echo "<div align='center'>$cSql</div>";
                $Archivo = 'TMCITAS';
                die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
        
        $nCita = $aHrs[1];
    }
}

?>

</html>
<?php
mysql_close();
?>
