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
 

$Usr      = $_COOKIE[USERNAME];
$Nivel    = $_COOKIE[LEVEL];

#Variables comunes;
$Titulo    = "Detalle por producto";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "invlab.php";


#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: invlab.php");
    
}elseif($_REQUEST[Boton] == 'Agregar paciente'){

    if($_REQUEST[Institucion] <> ''){  
                
        $cSql =  "INSERT INTO invl (clave,descripcion,marca,presentacion,pzasmedida,invgral,invmatriz,invtepex,invhf,
            invreyes,existencia,costo,costoant,costopromedio,iva,costopza,depto,subdepto,uso,min,max,status)
              VALUES
              ('$_REQUEST[Clave]','$_REQUEST[Descripcion]','$_REQUEST[Marca]','$_REQUEST[Presentacion]','$_REQUEST[Pzasmedida]','$_REQUEST[Invgral]',
               '$_REQUEST[Invmatriz]','$_REQUEST[Invtepex]','$_REQUEST[Invhf]','$_REQUEST[Invreyes]','$_REQUEST[Existencia]','$_REQUEST[Costo]',
               '$_REQUEST[Costoant]','$_REQUEST[Costopromedio]','$_REQUEST[Iva]','$_REQUEST[Costopza]','$_REQUEST[Depto]',
               '$_REQUEST[Subdepto]','$_REQUEST[Uso]','$_REQUEST[Min]','$_REQUEST[Max]','$_REQUEST[Status]')";    

        $cId = mysql_insert_id();
    
        $cProceso = "Agrega invl $cId ";
    
        if (!mysql_query($cSql)) {
            echo "<div align='center'>$cSql</div>";
            $Archivo = 'INVL';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    
        logs('Agr(invl)','$Usr','$cProceso');
        
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

    header("Location: invlab.php?busca=$cId");
        
}elseif ($_REQUEST[Boton] == 'Actualizar') {
            
    $cSql =  "UPDATE invl SET 
               clave='$_REQUEST[Clave]',descripcion='$_REQUEST[Descripcion]',marca='$_REQUEST[Marca]',
               presentacion='$_REQUEST[Presentacion]',pzasmedida='$_REQUEST[Pzasmedida]',invgral='$_REQUEST[Invgral]',invmatriz='$_REQUEST[Invmatriz]',
               invtepex='$_REQUEST[Invtepex]',invhf='$_REQUEST[Invhf]',invreyes='$_REQUEST[Invreyes]',existencia='$_REQUEST[Existencia]',
               costo='$_REQUEST[Costo]',costoant='$_REQUEST[Costoant]',costopromedio='$_REQUEST[Costopromedio]',
               iva='$_REQUEST[Iva]',costopza='$_REQUEST[Costopza]',depto='$_REQUEST[Depto]',subdepto='$_REQUEST[Subdepto]',
               uso='$_REQUEST[Uso]',min='$_REQUEST[Min]',max='$_REQUEST[Max]',status='$_REQUEST[Status]'
            WHERE id ='$busca'";
           
    $cProceso = "Actualizo prv institucion ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'INVL';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(invl)','$Usr','$cProceso');
    
    
    header("Location: invlab.php");
    
}elseif($_REQUEST[cId] <> '' ){
    
    $cSql =  "DELETE FROM invl WHERE id = '$_REQUEST[cId]'";    

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}


$CpoA      = mysql_query("SELECT * FROM invl WHERE id='$busca'");
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php    

echo '<body topmargin="1">';
encabezados();
PonTitulo($Titulo);
menu1();

    //submenu();

//Tabla contenedor de brighs

        
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
                    echo "<tr class='letrap'><td  align='center' colspan='2'>";
            echo "Clave: ";
            echo "<input class='cinput' type='text' name='Clave' size='15' value='$Cpo[clave]'> &nbsp; ";
            
            echo "Descripcion : ";
            echo "<input class='cinput' type='text' name='Descripcion' size='15' value='$Cpo[descripcion]'> &nbsp; ";
        
            echo "</td></tr><tr><td></td></tr>";
        
            cInput("Marca :", "Text", "10", "Marca", "right", $Cpo[marca], "15", 1, 0, '');
            echo "<tr><td class='letrap' align='right'>Presentacion individual: &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Presentacion'>";
            echo "<option value='1'>Piezas</option>";
            echo "<option value='2'>Cajas</option>";
            echo "<option value='1'>Paquetes</option>";
            echo "<option value='2'>Bolsas</option>";
            echo "<option value='1'>Kilos</option>";
            echo "<option value='2'>Litros</option>";
            echo "<option selected value='$Cpo[presentacion]]'>$Cpo[presentacion]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";  
            cInput("Pzas.x presentacion :", "Text", "10", "Pzasmedida", "right", $Cpo[pzasmedida], "20", 1, 0, '');    
            cInput("% del iva :", "Text", "10", "Iva", "right", $Cpo[iva], "15", 1, 0, '');            
            cInput("Existencia :", "Text", "9", "Existencia", "right", $Cpo[existencia], "9", 1, 0, '');            
            cInput("Costo :", "Text", "10", "Costo", "right", $Cpo[costo], "10", 1, 0, '');
            cInput("Costo x pieza :", "Text", "20", "Costopza", "right", $Cpo[costopza], "20", 1, 0, '');
            cInput("Departamento :", "Text", "20", "Depto", "right", $Cpo[depto], "20", 1, 1, ''); 
            echo "<tr><td class='letrap' align='right'>Sub-departamento :xx &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Subdepto'>";
            echo "<option value='1'>Si</option>";
            echo "<option value='2'>No</option>";
            echo "<option selected value='$Cpo[subdepto]]'>$Cpo[subdepto]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";  
            cInput("Utilizacion :xx", "Text", "6", "Dias", "right", $Cpo[dias], "7", 1, 0, '');    
            cInput("Minimo :", "Text", "20", "Min", "right", $Cpo[min], "20", 1, 0, '');
            cInput("Maximo :", "Text", "20", "Max", "right", $Cpo[max], "20", 1, 1, ''); 
            echo "<tr><td class='letrap' align='right'>Status : &nbsp;</td><td>";
            echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Status'>";
            echo "<option value='1'>Activo</option>";
            echo "<option value='2'>Inactivo</option>";
            echo "<option selected value='$Cpo[status]]'>$Cpo[status]</option>";  //se va
            echo "</select> ";
            echo "</td><tr>";  
        echo "</table>";
            
        Botones();
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='0' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

            //echo "<tr><td colspan='2' align='center'  bgcolor='$Gbgsubtitulo'><div class='letrasubt'>Datos personales</td></tr>";
            echo "<tr class='letrap'><td align='right' valign='bottom'>Observaciones:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Observaciones' cols='65' rows='4'>$Cpo[observaciones]</TEXTAREA>";
            echo "</td></tr>";   

                        

            echo "<tr height='10' bgcolor='#e1e1e1'><td></td><td></td></tr>";             
            
            echo "<tr class='letrap'><td align='right'>Reg.movimientos</td><td> &nbsp; &nbsp; ";            
            echo "<b>Dias de entrega</b>";                        
            echo "No.veces: $Cpo[numveces] &nbsp; &nbsp; &nbsp; ";
            echo "Usr: $Cpo[usr]&nbsp; &nbsp; &nbsp; ";
            echo "Sucursal: $Cpo[suc] &nbsp; &nbsp; &nbsp; ";
            echo "Ult.usuario: $Cpo[usrmod]";
            echo "</td></tr>";

            
            
            echo "</table>";
    //Cierra tabla principal la de dos cuadros        
    echo "</td></tr>";        
    echo "</table>";    
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
<?php
mysql_close();
?>
