<?php
#Librerias
session_start();
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

#Variables comunes;
$Titulo = " Edita datos del paciente";
$busca = $_REQUEST[busca];
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];

if (isset($_REQUEST[op])) {
    $_SESSION['OnToy'][1] = $_REQUEST[op];
}

$Retornar = $_SESSION['OnToy'][4];

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Intruccion a realizar si es que mandan algun proceso
if ($_REQUEST[Boton] == Cancelar) {

    header("Location: clientes.php");
} elseif ($_REQUEST[Boton] == 'Actualizar') {

    $Apellidop = strtoupper($_REQUEST[Apellidop]);
    $Apellidom = strtoupper($_REQUEST[Apellidom]);
    $Nombre = strtoupper($_REQUEST[Nombre]);
    $Nombrec = $Apellidop . " " . $Apellidom . " " . $Nombre;

    if ($busca == 'NUEVO') {

        if ($_REQUEST[Fechan] == '0000-00-00' AND $_REQUEST[Anos] <> '') {

            $Anos = $_REQUEST[Anos];
            $cFecha = strtotime(date("Y-m-d"));                //Convierto la fecha a Numeros
            $Fechan = strtotime("- $Anos years", $cFecha);   //Le quito los dias k quiero,  puede ser days month years y hasta -1 month menos un mes...
            $Fechan = date("Y-m-d", $Fechan);         //Convierto El resultado en Tipo Fecha
            $Fechan_real = 0;                         //NO es fecha real es un estimado
        } elseif ($_REQUEST[Fechan] <> '0000-00-00') {

            $Fechan = $_REQUEST[Fechan];
            $Fechan_real = 1;                       //Si es fecha real
        } else {

            $Fechan_real = 0;                         //NO es fecha real es un estimado
            $Fechan = '0000-00-00';
        }

        $CpoA = mysql_query("SELECT * FROM estados WHERE find like '%$_REQUEST[find]%'");
        $Cpo = mysql_fetch_array($CpoA);

        $cSql = "INSERT INTO cli (apellidop,apellidom,nombre,nombrec,fecha,fechan,localidad,municipio,
                  codigo,estado,institucion,fechan_real,clave)
                 VALUES
                 ('$Apellidop','$Apellidom','$Nombre',
                 '$Nombrec','$Fecha','$Fechan','$Cpo[colonia]','$Cpo[municipio]','$Cpo[codigo]',
                 '$Cpo[estado]','$_REQUEST[Institucion]','$Fechan_real','$_REQUEST[Clave]')";
    } else {

        $cSql = "UPDATE cli SET apellidop = '$Apellidop', apellidom='$Apellidom',
              nombre='$Nombre',nombrec='$Nombrec',fechan='$_REQUEST[Fechan]',
              telefono='$_REQUEST[Telefono]',programa='$_REQUEST[Programa]',
              status='$_REQUEST[Status]',localidad='$_REQUEST[Localidad]',sexo='$_REQUEST[Sexo]',titular='$_REQUEST[Titular]',
              estado='$_REQUEST[Estado]', 
              direccion='$_REQUEST[Direccion]',mail='$_REQUEST[Mail]',credencial='$_REQUEST[Credencial]',
              colonia='$_REQUEST[Colonia]',curp='$_REQUEST[Curp]',padecimiento='$_REQUEST[Padecimiento]',
              institucion='$_REQUEST[Institucion]',afiliacion='$_REQUEST[Afiliacion]', 
              expira='$_REQUEST[Expira]',refubicacion='$_REQUEST[Refubicacion]',municipio='$_REQUEST[Municipio]',rfc='$_REQUEST[Rfc]',zona='$_REQUEST[Zona]',
              fecha='$_REQUEST[Fecha]',usr='$_REQUEST[Usr]',usrmod='$_REQUEST[Usrmod]',suc='$_REQUEST[Suc]',
              codigo='$_REQUEST[Codigo]',observaciones='$_REQUEST[Observaciones]'
              WHERE cliente='$busca'";
    }


    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLI';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    if ($busca == 'NUEVO') {
        $cId = mysql_insert_id();
        header("Location: catclientes1e.php?busca=$cId&Msj=Registro Agregado con Exito");
    } else {
        header("Location: $Retornar?Paciente=$busca&Msj=Registro Actualizado con Exito");
    }
} elseif ($_REQUEST[cId] <> '') {

    $cSql = "DELETE FROM cliale WHERE idnvo = '$_REQUEST[cId]'";

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
}

$CpoA = mysql_query("SELECT * FROM cli WHERE cliente='$busca'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Documento sin título</title>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
            <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
            <script> type = "text/javascript" src = "lib/dhtmlgoodies_calendar.js?random=90090518" ></script>
            <link href="estilos.css" rel="stylesheet" type="text/css"/>
    </head>

    <script language="JavaScript1.2">

                function ValidaCampos() {

                    if (document.form1.Apellidom.value == "" || document.form1.Apellidop.value == "" || document.form1.Nombre.value == "" || document.form1.find.value == "") {
                        alert("Aun hay campos importantes en blanco");
                        document.form1.Apellidom.focus();
                        return false;
                    }
                    return true;
                }

                function load() {
                    document.form1.Apellidop.focus();
                }

    </script>
<?php
echo '<body topmargin="1">';

encabezados();

PonTitulo($Titulo);

menu($Gmenu, $Gusr);

//Variables por default cuando es nuevo
if ($busca == NUEVO) {
    $Alta = $Fecha = date("Y-m-d");
    $cClasificacion = "El mismo";
    $Fechan = date("Y-m-d");
    $cZona = 1;
    $cInst = 1;
    $cPrg = 1;
} else {

    $Alta = $Cpo[alta];
    $Fechan = $Cpo[fechan];
    $cClasificacion = $Cpo[clasificacion];

    if ($Cpo[fechan] <> '0000-00-00') {
        $Fechanac = $Cpo[fechan];
        $Fecha = date("Y-m-d");
        $array_nacimiento = explode("-", $Fechanac);
        $array_actual = explode("-", $Fecha);
        $Anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años 
        $Meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
        $Dias = $array_actual[2] - $array_nacimiento[2]; // calculamos días 
        if ($Meses < 0) {
            $Meses = 12 + $Meses;
            $Anos = $Anos - 1;
        }
        if ($Cpo[fechan_real] == 0) {
            $MsjFechan = 'fecha calculada';
        }
    } else {
        $MsjFechan = 'dato no proporcionado';
    }
}

//Tabla Principal que devide la pantalla en dos
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

if ($busca == 'NUEVO') {

    echo "<form name='form1' method='get' id='form90' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";

    echo "<a class='letrap' >Datos necesarios</a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    //cTable('90%', '0',$Titulo);
    //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";     

    echo "<tr class='letrap'><td colspan='2'>";
    echo "&nbsp;Ap.paterno: ";
    echo "<input class='cinput' type='text' name='Apellidop' size='15' value='$Cpo[apellidop]'> &nbsp; ";

    echo "Ap.materno: ";
    echo "<input class='cinput' type='text' name='Apellidom' size='15' value='$Cpo[apellidom]'> &nbsp; ";

    echo "Nombre: ";
    echo "<input class='cinput' type='text' name='Nombre' size='20' value='$Cpo[nombre]'>";

    echo "</td></tr>";
    //cInput("Nombre completo :", "Text", "20", "Nombrec", "right", $Cpo[nombrec], "17", 1, 1, '');


    echo "<tr height='25' class='letrap'><td align='right'>Institucion: </td><td>";
    $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
    echo "<select class='letrap' name='Institucion'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[0]'>$Ins[0]  " . ucwords(strtolower($Ins[1])) . "</option>";
        if ($Ins[institucion] == $Gcia) {
            $DispInst = ucwords(strtolower($Ins[alias]));
        }
    }
    echo "<option selected value='$Gcia'>$DispInst</option>";
    echo "</select>";
    echo "</td></tr>";


    cInput("No.clave d control :", "Text", "20", "Clave", "right", $Cpo[clave], "17", 1, 0, '');

    echo "<tr class='letrap'><td align='right'>";

    echo " Sexo:&nbsp;</td><td>";
    if ($Cpo[sexo] == F) {
        $Fem = checked;
        $Mas = '';
    } else {
        $Mas = checked;
        $Fem = '';
    }

    echo "<input type='radio' name='Sexo' value='M' $Mas>Masculino ";
    echo "<input type='radio' name='Sexo' value='F' $Fem>Femenino &nbsp; ";
    echo "</td></tr>";

    echo "<tr class='letrap'><td align='right'>";
    echo "Fecha nacimiento(aaaa-mm-dd):&nbsp;</td><td>";
    echo "<input type='text'class='letrap' name='Fechan' value='0000-00-00' size='14'> &nbsp; ";
    echo "Edad:&nbsp;";
    echo "<input type='text' class='letrap' name='Anos' value='$Anos' size='2'> &nbsp;";

    echo "</td></tr>";

    echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.2.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.mockjax.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/jquery.autocomplete.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"lib/predictive_search.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"util.js\"></script>";
    echo "<link rel=\"stylesheet\" href=\"lib/predictive_styles.css\">";

    echo "<script type=\"text/javascript\">";
    echo "$(document).ready(function() {";
    echo "$('#autocomplete').suggestionTool("
    . "$('#form90'),"
    . "' estados ', "
    . "function() { "
    . "var orderValue = 'find';"
    . "return orderValue.indexOf(' as ') >= 0 ? "
    . "orderValue.split(' as ')[0] : "
    . "orderValue; });";
    echo "});";
    echo "</script>";

    //echo "<form name='frmestudios' id='form90' method='get' action='$_SERVER[PHP_SELF]'>";

    echo "<tr class='texto_tablas'>";
    echo "<td align='right'><a class='letrap'>&nbsp;&nbsp;Colonia:&nbsp; </a></td><td>";
    echo "&nbsp;<input class='letrap'"
    . "type=\"text\" "
    . "size=\"30\" "
    . "class=\"texto_tablas\" "
    . "placeholder=\"Ingresar palabra(s) o id de estudio\" "
    . "name=\"find\" "
    . "id=\"autocomplete\"/>&nbsp;";

    //echo "<input type='submit' class='letrap' name='Boton' value='Agregar estudio' class='nombre_cliente'>";        
    //echo "</td>";        

    echo "<input type='hidden' name='Boton' value='Actualizar'>";

    echo "</td></tr></table>";

    Botones();

    echo "</form>";
} else {

    echo "<form name='form2' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";

    echo "<a class='letrap' >Datos necesarios</a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    //cTable('90%', '0',$Titulo);
    //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";     

    echo "<tr class='letrap'><td colspan='2'>";
    echo "&nbsp;Ap.paterno: ";
    echo "<input class='cinput' type='text' name='Apellidop' size='15' value='$Cpo[apellidop]'> &nbsp; ";

    echo "Ap.materno: ";
    echo "<input class='cinput' type='text' name='Apellidom' size='15' value='$Cpo[apellidom]'> &nbsp; ";

    echo "Nombre: ";
    echo "<input class='cinput' type='text' name='Nombre' size='20' value='$Cpo[nombre]'>";

    echo "</td></tr>";
    cInput("Nombre completo :", "Text", "20", "Nombrec", "right", $Cpo[nombrec], "17", 1, 1, '');

    cInput("No.clave d control :", "Text", "20", "Clave", "right", $Cpo[clave], "17", 1, 0, '');

    echo "<tr class='letrap'><td align='right'>";

    echo " Sexo:&nbsp;</td><td>";
    if ($Cpo[sexo] == F) {
        $Fem = checked;
        $Mas = '';
    } else {
        $Mas = checked;
        $Fem = '';
    }

    echo "<input type='radio' name='Sexo' value='M' $Mas>Masculino ";
    echo "<input type='radio' name='Sexo' value='F' $Fem>Femenino &nbsp; ";
    echo "</td></tr>";

    echo "<tr class='letrap'><td align='right'>";
    echo "Fecha nacimiento:&nbsp;</td><td>";
    echo "<input type='text'class='letrap' name='Fechan' value='$Cpo[fechan]' size='10'> &nbsp; ";
    echo "Años:&nbsp;";
    echo "<input type='text' class='letrap' name='Anos' value='$Anos' size='2'> &nbsp;";
    echo "Meses:&nbsp;$Meses &nbsp; Dias:&nbsp; $Dias &nbsp; &nbsp;";

    echo "<a class='letrap'><b>$MsjFechan</b></a>";

    cInput("Fuente : ", "Text", "10", "Fuente", "right", $Cpo[fuente], "15", 1, 0, '');

    echo "<tr class='letrap'><td align='right' valign='bottom'>Historial :&nbsp;</td><td>";
    echo "<TEXTAREA class='letrap' NAME='Historial' cols='40' rows='3'>$Cpo[historial]</TEXTAREA>";
    echo "</td></tr>";

    echo "</table><br>";

    //echo "<a class='letrap'>Direccion</a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'  class='letrap'>";
    cInput("Estado : ", "Text", "30", "Estado", "right", $Cpo[estado], "40", 1, 0, '');
    cInput("Delegacion/Mun.: ", "Text", "30", "Municipio", "right", $Cpo[municipio], "50", 1, 0, '');
    cInput("Colonia : ", "Text", "30", "Localidad", "right", $Cpo[localidad], "50", 1, 0, '');
    cInput("Direccion(calle) : ", "Text", "60", "Direccion", "right", $Cpo[direccion], "70", 1, 0, '');

    echo "<tr class='letrap'><td align='right'>Número ext: &nbsp; </td><td> ";
    echo "<input class='cinput' type='text' name='Numero' size='10' value='$Cpo[numero]'> &nbsp; ";
    echo "Interior: <input class='cinput' type='text' name='Interior' size='10' value='$Cpo[interior]'> &nbsp; ";

    echo "Cod. postal: ";
    echo "<input class='cinput' type='text' name='Codigo' size='10' value='$Cpo[codigo]'> &nbsp; ";

    echo "</td></tr>";

    /*
      cInput("Número : ", "Text", "10", "Numero", "right", $Cpo[numero], "15", 1, 0, '');
      cInput("Interior : ", "Text", "10", "Interior", "right", $Cpo[interior], "15", 1, 0, '');
      cInput("Cod. postal : ", "Text", "10", "Codigo", "right", $Cpo[codigo], "15", 1, 0, '');

      cInput("Seg. sistema C.P.: ", "Text", "10", "Sistemacp", "right", $Cpo[sistemacp], "15", 1, 0, '');

      echo "<tr class='letrap'><td align='right'>Número ext: &nbsp; </td><td> ";
      echo "<input class='cinput' type='text' name='Numero' size='10' value='$Cpo[numero]'> &nbsp; ";
     */
    echo "<tr class='letrap' height='25'><td align='right'>";
    echo "Telf.domicilio:&nbsp; </td><td> ";
    echo "<input class='cinput' type='text' name='Telefono' size='20' value='$Cpo[telefono]'> &nbsp; ";

    echo "Celular: ";
    echo "<input class='cinput' type='text' name='Celular' size='20' value='$Cpo[celular]'> &nbsp; ";

    echo "</td></tr>";

    //cInput("Telefono : ", "Text", "10", "Telefono", "right", $Cpo[telefono], "15", 1, 0, '');
    //cInput("Celular : ", "Text", "10", "Celular", "right", $Cpo[celular], "15", 1, 0, '');
    echo "<tr class='letrap' height='25'><td align='right'>";
    echo "Correo:&nbsp; </td><td> ";
    echo "<input class='cinput' type='text' name='Mail' size='35' value='$Cpo[mail]'> &nbsp; ";
    //echo " alterno: <input class='cinput' type='text' name='Mailalt' size='35' value='$Cpo[mailalt]'> &nbsp; ";
    echo "</td></tr>";
    //cInput("Correo electronico : ", "Text", "40", "Mail", "right", $Cpo[mail], "50", 1, 0, '');
    //cInput("Correo alternativo  : ", "Text", "40", "Mailalt", "right", $Cpo[mailalt], "50", 1, 0, '');
    cInput("Red social : ", "Text", "30", "Redsoc", "right", $Cpo[redsoc], "40", 1, 0, '');
    cInput("Zona : ", "Text", "20", "Zona", "right", $Cpo[zona], "30", 1, 0, '');
    echo "<tr ><td class='letrap' align='right' valign='bottom'>Ref. de ubicacion :&nbsp;</td><td>";
    echo "<TEXTAREA class='letrap' NAME='Refubicacion' cols='40' rows='3'>$Cpo[refubicacion]</TEXTAREA>";
    echo "</td></tr>";


    echo "</table>";

    Botones();
}


//Cuadro derecho del cuadro principal     
echo "</td><td valign='top'>";
/*
if ($busca <> 'NUEVO') {

    echo "<a class='letrap'><div align='center'>Datos institucionales<div></a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    //cTable('90%', '0',$Titulo);
    //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";     

    cInput("Afiliacion:", "Text", "20", "Afiliacion", "right", $Cpo[afiliacion], "30", 1, 0, '');
    cInput("Tipo de cliente :", "Text", "20", "Cliente", "right", $Cpo[cliente], "25", 1, 0, '');
    cInput("Credencial :", "Text", "20", "Credencial", "right", $Cpo[credencial], "25", 1, 0, '');

    echo "<tr class='letrap'><td align='right'>";

    echo " Expira:&nbsp;</td><td>";

    if ($Cpo[expira] == S) {
        $SiExp = checked;
    } else {
        $NoExp = checked;
    }

    echo "<input type='radio' name='Expira' value='S' $SiExp>Si ";
    echo "<input type='radio' name='Expira' value='N' $NoExp>No &nbsp;&nbsp; ";

    cInputDat("Fec.expiracion:", "Text", "10", "Expiracion", "left", $Cpo[expiracion], "15", 1, 0, '');

    echo "</td></tr>";

    cInput("Codigo(clave?) :", "Text", "20", "Xxx", "right", $Cpo[xxx], "25", 1, 0, '');
    cInput("Centro de trabajo :", "Text", "20", "Centrotr", "right", $Cpo[centrotr], "25", 1, 0, '');
    cInput("Organismo :", "Text", "30", "Organismo", "right", $Cpo[organismo], "35", 1, 0, '');
    cInput("Departamento :", "Text", "30", "Departamento", "right", $Cpo[departamento], "35", 1, 0, '');
    echo "<tr height='25' class='letrap'><td align='right'>Status : &nbsp;</td><td>";
    echo "&nbsp;<select class='cinput' class='InputMayusculas' name='Status'>";
    echo "<option value=''>Activo</option>";
    echo "<option value=''>Inactivo</option>";
    echo "<option value=''>Defuncion</option>";
    echo "<option value=''>Baja</option>";
    echo "<option value=''>Otro</option>";
    echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";  //se va
    echo "</select> ";
    echo "</td><tr>";

    echo "<tr class='letrap'><td align='right' valign='bottom'>Nota :&nbsp;</td><td>";
    echo "<TEXTAREA class='letrap' NAME='Nota' cols='40' rows='3'>$Cpo[nota]</TEXTAREA>";
    echo "</td></tr>";


    echo "</table><br>";
    echo "<a class='letrap' align='center'><div align='center'>Caracteristicas del servicio</div></a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    cInput("Beneficios y descuentos :", "Text", "30", "Descuentos", "right", $Cpo[descuentos], "35", 1, 0, '');
    cInput("Restricciones :", "Text", "30", "Restricciones", "right", $Cpo[restricciones], "35", 1, 0, '');
    echo "<tr class='letrap'><td align='right' valign='bottom'>Observaciones :&nbsp;</td><td>";
    echo "<TEXTAREA class='letrap' NAME='Observaciones' cols='65' rows='3'>$Cpo[observaciones]</TEXTAREA>";
    echo "</td></tr>";

    echo "</table><br>";


    echo "<a class='letrap' align='center'><div align='center'>Datos fiscales</div></a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    cInput("Clientes a facturar :", "Text", "40", "Facturar", "right", $Cpo[facturar], "45", 1, 0, '');
    cInput("RFC :", "Text", "25", "Rfc", "right", $Cpo[rfc], "30", 1, 0, '');
    cInput("Alias :", "Text", "20", "Alias", "right", $Cpo[alias], "25", 1, 0, '');
    cInput("CURP :", "Text", "30", "Curp", "right", $Cpo[curp], "35", 1, 0, '');
    cInput("Otro dato :", "Text", "60", "Otrodato", "right", $Cpo[otrodato], "65", 1, 0, '');

    echo "</table><br>";

    echo "<a class='letrap' align='center'><div align='center'>Datos de registro</div></a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    cInput("Usuario Alta  :", "Text", "40", "Usr", "right", $Cpo[usr], "45", 1, 0, '');
    cInput("Fecha Alta :", "Text", "20", "Alta", "right", $Cpo[alta], "25", 1, 0, '');
    cInput("Usuario Modificación :", "Text", "20", "Usrmod", "right", $Cpo[usrmod], "25", 1, 0, '');
    cInput("Fecha Modificación :", "Text", "20", "Fecmod", "right", $Cpo[fecmod], "25", 1, 0, '');

    echo "</table><br>";
    echo "<a class='letrap' align='center'><div align='center'>Nivel de datos</div></a>";
    echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
    cInput("Contrato de Adhesión  :", "Text", "30", "Adhesion", "right", $Cpo[adhesion], "35", 1, 0, '');
    cInput("Aviso de INAI :", "Text", "30", "Inai", "right", $Cpo[inai], "35", 1, 0, '');
    cInput("Derechos ARCO :", "Text", "30", "Arco", "right", $Cpo[arco], "35", 1, 0, '');
    echo "</table><br>";
}
//Cierra tabla principal la de dos cuadros  
 * */    
echo "</td></tr>";
echo "</table>";

echo "</form>";

echo '</body>';
?>
</html>
<script src="./controladores.js"></script>
<?php
mysql_close();
?>
