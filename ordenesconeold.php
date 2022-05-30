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
$Titulo    = "Orden de trabajo edit";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "ordenescon.php?buscar=ini";
$Id        = 49;             //Numero de query dentro de la base de datos


#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: ordenescon.php");
    
}elseif ($_REQUEST[Boton] == 'Actualizar') {
            
    $cSql =  "UPDATE ot SET receta='$_REQUEST[Receta]',diagmedico='$_REQUEST[Diagmedico]',observaciones='$_REQUEST[Observaciones]',
              fechae='$_REQUEST[Fechae]',horae='$_REQUEST[Horae]'
              WHERE orden ='$busca'";
           
    $cProceso = "Actualizo ot orden ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'OT';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(ot)','$Usr','$cProceso');
    
    
    header("Location: ordenescon.php");
    
}

$HeA      = mysql_query("SELECT ot.cliente,ot.fecha,ot.hora,ot.receta,ot.fecharec,ot.diagmedico,ot.observaciones,ot.servicio,
             ot.pagada,cli.nombrec,ot.medico,med.nombrec as nombremedico,ot.orden,ot.fechae,ot.importe,
             ot.recepcionista,ot.suc,ot.entfec,ot.recibio,ot.horae,ot.descuento,ot.citanum
             FROM cli,ot LEFT JOIN med ON ot.medico=med.id WHERE ot.orden='$busca' AND ot.medico=med.id");
$He      = mysql_fetch_array($HeA);

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA    = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry     = mysql_fetch_array($QryA);

#Armo el query segun los campos tomados de qrys;
$cSql   = "SELECT $Qry[campos] FROM otd LEFT JOIN est ON otd.idestudio=est.id WHERE otd.orden='$busca'";

//echo $cSql;

$aCps   = SPLIT(",",$Qry[campos]);				// Es necesario para hacer el order by  desde lib;

$aIzq   = array(" ","-","-");				//Arreglo donde se meten los encabezados; Izquierdos
$aDat   = SPLIT(",",$Qry[edi]);					//Arreglo donde llena el grid de datos
$aDer   = array();				//Arreglo donde se meten los encabezados; Derechos;
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
<link rel='icon' href='favicon.ico' type='image/x-icon' />
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
        echo "Estudios que conforman la orden...";
    echo "</td></tr>";
    
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";


        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
           
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("No.de orden :", "Text", "13", "Orden", "right", $He[orden], "10", 1, 1, "");
            cInput("Fecha :", "Text", "13", "Fecha", "right", $He[fecha], "20", 1, 1, "Hra: ".$He[hora]);
            cInput("Paciente :", "Text", "30", "Cliente", "right", $He[nombrec], "35", 1, 1, ''); 

            echo "<tr height='30' class='letrap'><td align='right'>No.receta: &nbsp; </td><td>";
            cInputDat("", "","20","Receta","left",$He[receta],"20","",true);
            cInputDat(" &nbsp; Fecha/receta:", "","14","Fecharec","left",$He[fecharec],"14","",true);
            echo "</td></tr>";
            cInput("Descuento(razon):", "Text", "60", "Descuento", "right", $He[descuento], "50", 1, 0, " ");
            echo "<tr height='35' class='letrap'><td align='right' valign='bottom'>Diagnostico:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Diagmedico' cols='65' class='letrap' rows='4'>$He[diagmedico]</TEXTAREA>";
            echo "</td></tr>";  
            echo "<tr height='35' class='letrap'><td align='right' valign='bottom'>Observaciones:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Observaciones' cols='65' class='letrap' rows='4'>$He[observaciones]</TEXTAREA>";
            echo "</td></tr>";  

            echo "<tr height='30' class='letrap'><td align='right'>Institucion:&nbsp;</td><td>";
            echo '<select name="Institucion" class="content5" id="Unidades" disabled>';            
            $InsA = mysql_query("SELECT institucion as id,alias,lista,condiciones FROM inst WHERE status='ACTIVO' ORDER BY institucion");      
            while ($Ins=mysql_fetch_array($InsA)){      
                echo '<option value='.$Ins[id].'>'.$Ins[alias].'</option>';
                if($He[institucion] == $Ins[id]){
                    echo '<option selected="'.$He[institucion].'">'.$Ins[alias].'</option>';  
                }
            }          
           echo '</select> ';
           echo "</td></tr>";
           
            cInput("Medico :", "Text", "8", "Medico", "right", $He[medico], "40", 1, 1, $He[nombremedico]);            

            echo "</td></tr>";   

            //echo "<tr><td colspan='2' align='center'  bgcolor='$Gbgsubtitulo'><div class='letrasubt'>Datos personales</td></tr>";

            echo "<tr  class='letrap'><td align='right'>Servicio: &nbsp; </td><td>";
            echo '<select name="Servicio" class="content5" id="Unidades" disabled>';
            echo '<option value="Ordinario">Ordinario</option>';
            echo '<option value="Urgente">Urgente</option>';
            echo '<option value="Express">Express</option>';
            echo '<option value="Hospitalizado">Hospitlizado</option>';
            echo '<option value="Nocturno">Nocturno</option>';
            echo '<option selected="'.$He[servicio].'">'.$He[servicio].'</option>';  
            echo '</select> &nbsp; ';        
            if($He[servicio] == 'Cita'){
               echo " &nbsp; No.de cita: $He[citanum]"; 
            }
            echo "</td></tr>";
            
            echo "<tr height='30' class='letrap'><td align='right'>Fecha de entrega :&nbsp;</td><td>";
            cInputDat("", "","14","Fechae","left",$He[fechae],"14","",true);
            cInputDat(" &nbsp; Hora:", "","10","Horae","left",$He[horae],"14","",true);
            echo "</td></tr>";                        
            
            //cInput("Fecha de entrega :", "Text", "25", "", "right", $He[fechae], "30", 1, 0, '');
            //cInput("Hora :", "Text", "10", "Horae", "right", $He[horae], "10", 1, 0, '');
 
            echo "<tr height='10' bgcolor='#e1e1e1'><td></td><td></td></tr>";             
             
            cInput("Fech.d entrega real :", "Text", "20", "Entfec", "right", $He[entfec], "25", 1, 1, '');
            cInput("Quien lo entrego :", "Text", "20", "Entusr", "right", $He[entusr], "25", 1, 1, '');
            cInput("Recibo :", "Text", "60", "Recibio", "right", $He[recibio], "70", 1, 1, ''); 
 
            echo "<tr height='10' bgcolor='#e1e1e1'><td></td><td></td></tr>";             
                                     
            
            echo "<tr class='letrap'><td align='right'>Registro:</td><td> &nbsp; &nbsp; ";            
                        
            echo "Usr: $He[recepcionista]&nbsp; &nbsp; &nbsp; ";
            echo "Sucursal: $He[suc] &nbsp; &nbsp; &nbsp; ";
            echo "Ult.usuario: $He[usrmod]";
            echo "</td></tr>";

            
            
        echo "</table>";

        botones();
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";

    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $sql   = $cSql.$cWhe." LIMIT ".$limitInf.",".$tamPag;
    //echo $sql;

    $res   = mysql_query($sql);
            
    $Pos   = strrpos($_SERVER[PHP_SELF],".");
    $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #
    
    while($rg=mysql_fetch_array($res)){
        
        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";       
        //echo "<tr>";

        echo "<td class='Seleccionar' align='center'> - </td>";            
        /*
        if($RetSelec <>''){
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$RetSelec?Paciente=$rg[id]'>Seleccionar</a></td>";
        }else{
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[id]'>Editar</a></td>";
        }
        */
        Display($aCps,$aDat,$rg);
        /*
        if($Nivel >= 7 ){
            echo "<td class='Seleccionar' align='center'><a class='edit' href='historico.php?op=bc&busca=$rg[estudio]'>H.clinico</a></td>";            
        }else{
            echo "<td class='Seleccionar' align='center'> - </td>";            
        }
        
        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[estudio]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');>Eliminar</a></td>";
        */
        echo "</tr>";

        $nRng++;

    }

    echo "<tr class='letra'><td> </td><td> </td><td align='right'> Total estudios: ".number_format($nRng,"0")."</td>";
    echo "<td> </td><td> </td><td align='right'>".number_format($He[importe],"2")." &nbsp; </td></tr>";
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
