<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

date_default_timezone_set("America/Mexico_City");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d");
$Msj = $_REQUEST["Msj"];
$Fechaest  = date("Y-m-d H:i:s");
$Hora      = date("H:i:s");
$Estudio   = $_REQUEST[Estudio];
$Estudio2   = $_REQUEST[Estudio];
$status    = $_REQUEST[status];
$muestras    = $_REQUEST[muestras];
$nmuestras    = $_REQUEST[nmuestras];
$Recoleccion = $_REQUEST[Recoleccion];
$Op = $_REQUEST[Op];
$cambio = $_REQUEST[cambio];
$cambiopag = $_REQUEST[cambiopag];
$fechaot = $_REQUEST[fecha];
$fechan = $_REQUEST[fechan];
$sexocli = $_REQUEST[Sexo];
$serviciot = $_REQUEST[Servicio];
$Apellidop = $_REQUEST[Apellidop];
$Apellidom = $_REQUEST[Apellidom];
$Nombre = $_REQUEST[Nombre];
$Est    = $_REQUEST[Est];
$Det    = $_REQUEST[Det];
$mailpac = $_REQUEST[mailpac];
$diagmedico = $_REQUEST[diagmedico];
$razondescuento = $_REQUEST[razondescuento];
$responsableco = $_REQUEST[responsableco];
$id = $_REQUEST[id];
$modificaest = $_REQUEST[modificaest];

if (!isset($_REQUEST[Traza])) {
    $Traza     = 'No';
} else {
    $Traza     = $_REQUEST[Traza];
}

require './Services/CapturaResultadoService.php';

require ("config.php");          //Parametros de colores;

$Suc = $He["suc"];

$veces = $Cpo["numveces"];

$sucnombre = $aSucursal[$Suc];

$Sexo='';

if ($Cpo["sexo"]=='M') {
    $Sexo = "Masculino";
} elseif ($Cpo["sexo"]=='F') {
    $Sexo = "Femenino";
} else {
    $Sexo = "";
}

//****************** Cambios ***************//

if ($_REQUEST["Boton"] === "Cambiar") {

    if($cambio=='fecha'){
        $fechacambio=substr($fechaot, 0,10);
        $horacambio=substr($fechaot, 11,5);
        if($Cpo["fecha"]<=$fechacambio){
            if($Cpo["fecha"]==$fechacambio and $Cpo["hora"]>$horacambio){
                $Msj='Hora no puede ser anterior a la Hora de la captura';
                $Error='SI';
            }else{

                $Up2  = mysql_query("UPDATE ot SET fechae='$fechacambio', horae='$horacambio'
                WHERE orden='$busca' limit 1;"); 

                $Msj='Cambio de Fecha y Hora Correcta '.$fechacambio.' - '.$horacambio;
                $cambio = '';

                AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Fecha-Hora Entrega '.$fechacambio.' - '.$horacambio, "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

            }

        }else{
            $Msj='Fecha no puede ser anterior a la fecha de la captura';
            $Error='SI';
        }
    }elseif($cambio=='sexo'){

        $Up2  = mysql_query("UPDATE cli SET sexo='$sexocli'
        WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Genero del cliente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Genero '.$sexocli, "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='servicio'){

        $Up2  = mysql_query("UPDATE ot SET servicio='$serviciot'
        WHERE orden='$busca' limit 1;"); 

        $Msj='Cambio de Servicio con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Servicio '.$serviciot, "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='nombre'){

        $Nombrec= $Apellidop.' '.$Apellidom.' '.$Nombre;

        $Up2  = mysql_query("UPDATE cli SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',nombrec='$Nombrec'
        WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Nombre paciente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Nombre paciente ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='fechanac' or $cambio=='edad'){

        $Up2  = mysql_query("UPDATE cli SET fechan='$fechan'
        WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Fecha de Nac del cliente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Fecha de Nac ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='mailpac'){

        $Up2  = mysql_query("UPDATE cli SET cli.mail='$mailpac' WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Correo de paciente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Correo de paciente ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='diagnostico'){

        $Up2  = mysql_query("UPDATE ot SET ot.diagmedico='$diagmedico' WHERE orden='$busca' limit 1;"); 

        $Msj='Cambio de Diagnostico con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Diagnostico ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='descuento'){

        $Up2  = mysql_query("UPDATE ot SET ot.descuento='$razondescuento' WHERE orden='$busca' limit 1;"); 

        $Msj='Cambio de Razón Descuento con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Razon de Descuento ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='responsable'){

        $Up2  = mysql_query("UPDATE ot SET ot.responsableco='$responsableco' WHERE orden='$busca' limit 1;"); 
        $Msj='Cambio de Responsable Económico con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Responsable Económico ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='medico'){

        $Pos = strpos($_REQUEST[Medico], " "); 

        if ($Pos > 0) {
            $cId = substr($_REQUEST[Medico], 0, $Pos);
        } else {
            $cId = $_REQUEST[Medico];
        }

        $c = mysql_query("SELECT medico, status FROM med WHERE status='Activo' and ( id = '$cId' or medico = '$cId')");

        $cc = mysql_fetch_array($c);

        if ($cc[medico] <> '') {

            $Up2  = mysql_query("UPDATE ot SET medico='$cc[medico]' WHERE orden='$busca' limit 1;"); 

            if ($cc[medico] == 'MD') {

                $Up3  = mysql_query("UPDATE ot SET medicon='MEDICO DIVERSO' WHERE orden='$busca' limit 1;"); 

                $Msj='Favor de Agregar Nombre de Médico Diverso';
                $cambio = 'MD';

                header("Location: ordenescone2.php?busca=$busca&Msj=$Msj&cambio=$cambio");

            }else{

                $Msj='Cambio de Médico con Exito';
                $cambio = '';

                AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Médico '.$cc[medico], "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");
   
            }

        }else{

            $Msj = "Médico No Existe Favor de Verificarlo";
            $cambio = '';

        }

    }elseif($cambio=='MD'){

        $Up2  = mysql_query("UPDATE ot SET medicon='$_REQUEST[NombreMedico]' WHERE orden='$busca' limit 1;"); 
        $Msj='Cambio de Médico Diverso con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Médico Diverso ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='sucursal'){

        $Up2  = mysql_query("UPDATE ot SET suc='$_REQUEST[Sucursal]' WHERE orden='$busca' limit 1;"); 
        $Msj='Cambio de Sucursal con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Sucursal ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='captura'){

        $Up2  = mysql_query("UPDATE ot SET recepcionista='$_REQUEST[Captura]' WHERE orden='$busca' limit 1;"); 
        $Msj='Cambio de Recepcionista con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Recepcionista ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }elseif($cambio=='nip'){

        $Pos = strpos($_REQUEST[PacienteJ], " "); 

        if ($Pos > 0) {
            $cId = substr($_REQUEST[PacienteJ], 0, $Pos);
        } else {
            $cId = $_REQUEST[PacienteJ];
        }

        $c = mysql_query("SELECT cliente, status FROM cli WHERE status='Activo' and cliente = '$cId' limit 1;");

        $cc = mysql_fetch_array($c);

        if ($cc[cliente] <> '') {

            $Up2  = mysql_query("UPDATE ot SET cliente='$cc[cliente]' WHERE orden='$busca' limit 1;"); 

            $Msj='Cambio de NIP Paciente con Exito';
            $cambio = '';

            AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Paciente de NIP '.$Cpo[cliente].' a '.$cc[cliente], "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

        }else{

            $Msj = "NIP Paciente No Existe Favor de Verificarlo";
            $cambio = '';

        }

    }elseif($cambio=='institucion'){

        if($Cpo[institucion]<>$_REQUEST[Institucion]){

            $Up2  = mysql_query("UPDATE ot SET institucion='$_REQUEST[Institucion]' WHERE orden='$busca' limit 1;");

            $LtA  = mysql_query("SELECT institucion,lista,descuento FROM inst WHERE institucion='$_REQUEST[Institucion]'");

            $Lt   = mysql_fetch_array($LtA);

            $Lista="lt".$Lt[lista];

            $Descuento=$Lt[descuento];

            $OtdA  = mysql_query("SELECT estudio,precio,descuento FROM otd WHERE orden='$busca'");

            while ($Otd   = mysql_fetch_array($OtdA)) {

                $EstudA  = mysql_query("SELECT estudio,$Lista as pre FROM est WHERE estudio='$Otd[estudio]'");

                $Estud  = mysql_fetch_array($EstudA);

                $CambioestA  = mysql_query("UPDATE otd SET precio='$Estud[pre]',descuento='$Descuento' WHERE orden='$busca' and estudio='$Otd[estudio]' limit 1;");

            }

            $OtC     = mysql_query("SELECT importe from ot where orden='$busca' limit 1;");
            $Otd      = mysql_fetch_array($OtC);
            $cSqlB   = mysql_query("SELECT sum(importe) as importecja from cja where orden='$busca'");
            $Abonado = mysql_fetch_array($cSqlB);
            
            if(($Abonado[importecja] + .5) >= $Otd[importe] ){
               $lUp=mysql_query("UPDATE ot Set pagada='Si' where orden='$busca' limit 1;");
            }else{
               $lUp=mysql_query("UPDATE ot Set pagada='No' where orden='$busca' limit 1;");
            }

            $Msj='Cambio de Institucion con Exito';
            $cambio = '';

            AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Inst de '.$Cpo[institucion].' a '.$_REQUEST[Institucion], "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");
        }
    }

}elseif ($_REQUEST["Boton"] === "OK") {

    if($Gnivel=='99'){

        $lUp    = mysql_query("UPDATE cja set tpago = '$_REQUEST[tpago]',suc = '$_REQUEST[tpagosuc]',importe = '$_REQUEST[tpagoimp]',usuario = '$_REQUEST[tpagocap]' WHERE orden='$busca' and id='$id' limit 1;");

        $Msj='Cambio de Registro de Pago con Exito';
        $cambio = '';

        $OtC     = mysql_query("SELECT importe from ot where orden='$busca' limit 1;");
        $Otd      = mysql_fetch_array($OtC);
        $cSqlB   = mysql_query("SELECT sum(importe) as importecja from cja where orden='$busca'");
        $Abonado = mysql_fetch_array($cSqlB);
        
        if(($Abonado[importecja] + .5) >= $Otd[importe] ){
           $lUp=mysql_query("UPDATE ot Set pagada='Si' where orden='$busca' limit 1;");
        }else{
           $lUp=mysql_query("UPDATE ot Set pagada='No' where orden='$busca' limit 1;");
        }

        AgregaBitacoraEventos2($Gusr, '/Consulta ordenes/Cambio de Registro de pago '.$id, "cja", $Fechaest, $busca, $Msj, "ordenescone2.php");

        header("Location: ordenescone2.php?Msj=$Msj&Traza=Pagos&busca=".$busca);

    }else{

        $lUp    = mysql_query("UPDATE cja set tpago = '$_REQUEST[tpago]' WHERE orden='$busca' and id='$id' limit 1;");

        $Msj='Cambio de Tipo de Pago con Exito';
        $cambio = '';

        AgregaBitacoraEventos2($Gusr, '/Consulta ordenes/Cambio de Tipo de pago '.$_REQUEST[tpago], "cja", $Fechaest, $busca, $Msj, "ordenescone2.php");

    }

}elseif ($_REQUEST["Boton"] === "Agrega") {

    if (isset($_REQUEST[IdEstudio])) {

        $Pos = strpos($_REQUEST[IdEstudio], " "); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $cId = substr($_REQUEST[IdEstudio], 0, $Pos);
        } else {
            $cId = $_REQUEST[IdEstudio];
        }

        $sql = "SELECT id,estudio,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10,lt11,
                   lt12,lt13,lt14,lt15,lt15,lt15,lt16,lt17,lt18,lt19,lt20,lt22,lt21,lt23,descripcion
                   FROM est WHERE activo='Si' and (id='$cId' or estudio='$cId')";

        $LtaA = mysql_query($sql);

        $Lta = mysql_fetch_array($LtaA);

        if ($Lta[id] <> '') {

            $LtA = mysql_query("SELECT lista,institucion,descuento FROM inst WHERE institucion='$Cpo[institucion]'");
            $Lt=mysql_fetch_array($LtA);
            $Lista="lt".ltrim($Lt[lista]);
            $Desctoinst=$Lt[descuento];

            $lUp=mysql_query("SELECT estudio,$Lista FROM est WHERE estudio='$Lta[estudio]'");

            $cCpo=mysql_fetch_array($lUp);

            if($cCpo[$Lista]<>0){

               $Estudio=strtoupper($Lta[estudio]);

               $lUp=mysql_query("INSERT into otd (orden,estudio,precio,status,descuento) VALUES ('$busca','$Estudio','$cCpo[$Lista]','DEPTO','$Desctoinst')");

               $OtdA=mysql_query("SELECT sum(precio*(1-descuento/100)) as impotd FROM otd WHERE orden='$busca'");
               $Otd=mysql_fetch_array($OtdA);
               $lUp=mysql_query("UPDATE ot set importe='$Otd[impotd]' WHERE orden='$busca' limit 1;");

                $OtC     = mysql_query("SELECT importe from ot where orden='$busca' limit 1;");
                $Otd      = mysql_fetch_array($OtC);
                $cSqlB   = mysql_query("SELECT sum(importe) as importecja from cja where orden='$busca'");
                $Abonado = mysql_fetch_array($cSqlB);
                
                if(($Abonado[importecja] + .5) >= $Otd[importe] ){
                   $lUp=mysql_query("UPDATE ot Set pagada='Si' where orden='$busca' limit 1;");
                }else{
                   $lUp=mysql_query("UPDATE ot Set pagada='No' where orden='$busca' limit 1;");
                }
                    
                $Msj='Agrega estudio con exito';

                AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Agrega estudio '.$Estudio, "otd", $Fechaest, $busca, $Msj, "ordenescone2.php");

            }else{

                $Msj="El Estudio [".$Lta[estudio]."] no tiene precio, favor de verificar";

            }

        }else{

            $Msj="El Estudio no existe, favor de verificar";

        }

    } else {

        $Msj="El Estudio no existe, favor de verificar";

    }

    $modificaest = '';

}elseif ($_REQUEST["Boton"] === "Cancelar") {

    $cambio = '';
    $modificaest = '';

}

  //*************************//


if($_REQUEST[op]=='3'){

    if ($He["idprocedencia"] == $_REQUEST[opc]) {

        $opc     = '';

    }else{

        $opc     = $_REQUEST[opc];

    }

    $lUp=mysql_query("UPDATE ot set idprocedencia = '$opc' WHERE orden='$busca' limit 1");

    $Msj='Cambio de Tipo Paciente con exito';

    AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Regist Tipo Paciente ', "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");

}elseif($_REQUEST[op]=='Eli'){
    
    $cSqlE = "DELETE FROM otd WHERE estudio='$Est' AND orden='$busca' limit 1;";
    $SqA   = mysql_query($cSqlE);
    $OtdA  = mysql_query("SELECT sum(precio*(1-descuento/100)) as importet FROM otd WHERE orden='$busca'");
    $Otd   = mysql_fetch_array($OtdA);
    $lUp   = mysql_query("UPDATE ot set importe='$Otd[importet]' WHERE orden='$busca' limit 1;");
    
    $OtC     = mysql_query("SELECT importe from ot where orden='$busca' limit 1;");
    $Otd      = mysql_fetch_array($OtC);
    $cSqlB   = mysql_query("SELECT sum(importe) as importecja from cja where orden='$busca'");
    $Abonado = mysql_fetch_array($cSqlB);
    
    if(($Abonado[importecja] + .5) >= $Otd[importe] ){
       $lUp=mysql_query("UPDATE ot Set pagada='Si' where orden='$busca' limit 1;");
    }else{
       $lUp=mysql_query("UPDATE ot Set pagada='No' where orden='$busca' limit 1;");
    }
        
    $Msj='Eliminacion de estudio con exito';

    AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Eliminacion de estudio '.$Est, "otd", $Fechaest, $busca, $Msj, "ordenescone2.php");
}   

if ($_REQUEST[Envios] == 'ck') {

    $Sql = mysql_query("UPDATE ot SET $_REQUEST[Cmpo] = '$_REQUEST[status]' WHERE orden='$busca' limit 1");
    
    $Msj='Cambio de Entrega con exito';

    AgregaBitacoraEventos($Gusr, '/Consulta ordenes/Cambio de Entrega '.$_REQUEST[Cmpo], "ot", $Fechaest, $busca, $Msj, "ordenescone2.php");
}

if ($modificaest=='desctoestok') {
    
    $cSql = mysql_query("UPDATE otd SET descuento='$_REQUEST[desctoest]' WHERE orden='$busca' AND estudio='$Est' limit 1;");

    $OtdA  = mysql_query("SELECT sum(precio*(1-descuento/100)) as importet FROM otd WHERE orden='$busca'");
    $Otd   = mysql_fetch_array($OtdA);
    $lUp   = mysql_query("UPDATE ot set importe='$Otd[importet]' WHERE orden='$busca' limit 1;");
    
    $OtC     = mysql_query("SELECT importe from ot where orden='$busca' limit 1;");
    $Otd      = mysql_fetch_array($OtC);
    $cSqlB   = mysql_query("SELECT sum(importe) as importecja from cja where orden='$busca'");
    $Abonado = mysql_fetch_array($cSqlB);
    
    if(($Abonado[importecja] + .5) >= $Otd[importe] ){
       $lUp=mysql_query("UPDATE ot Set pagada='Si' where orden='$busca' limit 1;");
    }else{
       $lUp=mysql_query("UPDATE ot Set pagada='No' where orden='$busca' limit 1;");
    }
    
    $Msj='Cambio de Descuento con exito';

    AgregaBitacoraEventos2($Gusr, '/Consulta ordenes/Cambio de Descuento '.$Est, "otd", $Fechaest, $busca, $Msj, "ordenescone2.php");

    $modificaest = '';

}elseif ($modificaest=='desctoestokt') {
    
    $cSql = mysql_query("UPDATE otd SET descuento='$_REQUEST[desctoestodos]' WHERE orden='$busca' limit 20");

    $OtdA  = mysql_query("SELECT sum(precio*(1-descuento/100)) as importet FROM otd WHERE orden='$busca'");
    $Otd   = mysql_fetch_array($OtdA);
    $lUp   = mysql_query("UPDATE ot set importe='$Otd[importet]' WHERE orden='$busca' limit 1;");
    
    $OtC     = mysql_query("SELECT importe from ot where orden='$busca' limit 1;");
    $Otd      = mysql_fetch_array($OtC);
    $cSqlB   = mysql_query("SELECT sum(importe) as importecja from cja where orden='$busca'");
    $Abonado = mysql_fetch_array($cSqlB);
    
    if(($Abonado[importecja] + .5) >= $Otd[importe] ){
       $lUp=mysql_query("UPDATE ot Set pagada='Si' where orden='$busca' limit 1;");
    }else{
       $lUp=mysql_query("UPDATE ot Set pagada='No' where orden='$busca' limit 1;");
    }
    
    $Msj='Cambio de Descto a todos los estudios con exito';

    AgregaBitacoraEventos2($Gusr, '/Consulta ordenes/Cambio de Descto a todos los estudios ', "otd", $Fechaest, $busca, $Msj, "ordenescone2.php");

    $modificaest = '';
}

  //*************************//

if ($Cpo["idprocedencia"] == 'ambulancia') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}elseif ($Cpo["idprocedencia"] == 'silla') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}elseif ($Cpo["idprocedencia"] == 'terceraedad') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}elseif ($Cpo["idprocedencia"] == 'problemasv') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:RED" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
} elseif ($Cpo["idprocedencia"] == 'bebe') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:RED" aria-hidden="true"></i>';
}else{
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}
  
  //*************************//

require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Consulta de Orden de Trabajo</title>
    <?php require ("./config_add.php"); ?>
</head>
<body topmargin="1">
    <?php
    $nomcliente = ucwords(strtolower(substr($Cpo[nombrecli],0,50)));
    ?>
    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
        <tr>
            <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Consulta de Orden: <?= $Cpo["institucion"] ?> - <?= $busca ?> - <?= $nomcliente ?> ::..
            </td>
        </tr>
        <tr>
            <td valign='top' align='center' height='130' width='100%'>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 1px solid #999;'>  
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo' align="center" colspan="5">
                            ..:: Datos principales ::..
                        </td>
                    </tr>

        <! –– Primer reglon Intitucion ––>

                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm">
                        <?php
                        if($Gnivel=='99'){
                        ?>
                            <?php
                            if ($cambio=='institucion') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Institucion : 
                                </strong><br>
                                <select class='letrap' name='Institucion'>
                                    <?php
                                    $InstA = mysql_query("SELECT institucion,alias FROM inst WHERE status='ACTIVO' ORDER BY institucion");
                                    while ($Inst = mysql_fetch_array($InstA)) {
                                        if($Inst[institucion]==$Cpo[institucion]){
                                            echo '<option selected style="font-weight:bold" value="' . $Inst[institucion] . '"> - ' . $Inst[institucion] . ' - ' . $Inst[alias] . ' -</option>';
                                        }else{
                                            echo '<option value="' . $Inst[institucion] . '">' . $Inst[institucion] . ' - ' . $Inst[alias] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=institucion'>
                                <i class="fa fa-square fa-lg" style="color:#5499C7;" aria-hidden="true">
                                </i><a>
                                Institucion : 
                                </strong><br>
                                <div align="center"><?= $Cpo["institucion"] . " - " . $Cpo["nombrei"] ?></div>
                            <?php
                            }
                            ?>
                        <?php
                        }else{
                        ?>
                            <strong>Institucion : </strong><br>
                            <div align="center"><?= $Cpo["institucion"] . " - " . $Cpo["nombrei"] ?></div>
                        <?php
                        }
                        ?>
                        </td>
                        <?php
                        $urgente1='';
                        $urgente2='';
                        if($Cpo["servicio"]=='Urgente'){
                            $colorserv='red';
                            $urgente1='<strong>';
                            $urgente2='</strong>';
                        }
                        ?>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='servicio') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Servicio : 
                                </strong><br>
                                <select name='Servicio' class="cinput">
                                    <option value="Ordinario">Ordinario</option>
                                    <option value="Urgente">Urgente</option>
                                    <option value="Express">Express</option>
                                    <option value="Hospitalizado">Hospitalizado</option>
                                    <option value="Nocturno">Nocturno</option>
                                    <option value='<?= $Cpo["servicio"] ?>' selected><?= $Cpo["servicio"] ?></option>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=servicio'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                 Servicio : 
                                </strong><br>
                                <div align="center"><?= $urgente1 ?><font color='<?= $colorserv ?>'>
                                <?= $Cpo["servicio"] ?> </font> <?= $urgente2 ?></div>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                        <?php
                        if($Gnivel=='99'){
                        ?>
                            <?php
                            if ($cambio=='sucursal') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Sucursal : 
                                </strong><br>
                                <select class='letrap' name='Sucursal'>
                                    <?php
                                    $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' and id<>'100' ORDER BY id");
                                    while ($Cia = mysql_fetch_array($CiaA)) {
                                        if($Cia[id]==$Suc){
                                            echo '<option selected style="font-weight:bold" value="' . $Cia[id] . '">- ' . $Cia[alias] . ' -</option>';
                                        }else{
                                            echo '<option value="' . $Cia[id] . '">' . $Cia[alias] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=sucursal'>
                                <i class="fa fa-square fa-lg" style="color:#5499C7;" aria-hidden="true">
                                </i><a>
                                Sucursal : 
                                </strong><br>
                                <div align="center"><?= $Suc ?> - <?= $sucnombre ?></div>
                            <?php
                            }
                            ?>
                        <?php
                        }else{
                        ?>
                            <strong>Sucursal : </strong><br>
                            <div align="center"><?= $Suc ?> - <?= $sucnombre ?></div>
                        <?php
                        }
                        ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                        <?php
                        if($Gnivel=='99'){
                        ?>
                            <?php
                            if ($cambio=='captura') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Capturó : 
                                </strong><br>
                                <select class='letrap' name='Captura'>
                                    <?php
                                    $PerA = mysql_query("SELECT id,uname FROM authuser WHERE id<>'2' and id<>'140' and id<>'155' ORDER BY uname");
                                    while ($Per = mysql_fetch_array($PerA)) {
                                        $UsrCap=strtoupper($Per[uname]);
                                        if($UsrCap==$Cpo["recepcionista"]){
                                            echo '<option selected style="font-weight:bold" value="' . $UsrCap . '">- ' . $UsrCap . ' -</option>';
                                        }else{
                                            echo '<option value="' . $UsrCap . '">' . $UsrCap . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=captura'>
                                <i class="fa fa-square fa-lg" style="color:#5499C7;" aria-hidden="true">
                                </i><a>
                                Capturó : 
                                </strong><br>
                                <div align="center"><?= $Cpo["recepcionista"] ?></div>
                            <?php
                            }
                            ?>
                        <?php
                        }else{
                        ?>
                            <strong>Capturó : </strong><br>
                            <div align="center"><?= $Cpo["recepcionista"] ?></div>
                        <?php
                        }
                        ?> 
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Fech. Orden : </strong><br>
                            <div align="center"><?= $Cpo["fecha"] . " " . $Cpo["hora"] ?></div>
                        </td>
                    </tr>

        <! –– Segundo reglon Paciente ––>

                    <tr style="height: 30px" class="letrap">
                        <td width='28%' class="ssbm" align="lefth">
                        <?php
                        if($Gnivel=='99'){
                        ?>
                            <?php
                            if ($cambio=='nip') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Capturó : 
                                </strong><br>
                                <input style="width: 250px;" name="PacienteJ" type="text" id="pacientej" placeholder="Nombre del paciente"></input>
                                <script src="js/jquery-1.8.2.min.js"></script>
                                <script src="jquery-ui/jquery-ui.min.js"></script>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#pacientej').autocomplete({
                                            source: function (request, response) {
                                                $.ajax({
                                                    url: "autocomplete.php",
                                                    datoType: "json",
                                                    data: {q: request.term, k: "pst"},
                                                    success: function (data) {
                                                        response(JSON.parse(data));
                                                        console.log(JSON.parse(data));
                                                    }
                                                });
                                            },
                                            minLength: 1
                                        });
                                    });
                                </script>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=nip'>
                                <i class="fa fa-square fa-lg" style="color:#5499C7;" aria-hidden="true">
                                </i><a>
                                 Paciente : 
                                </strong><br>
                                <div align="center"><strong><?= $Cpo["cliente"] . " - " . $nomcliente ?></strong></div>
                            <?php
                            }
                            ?>
                        <?php
                        }else{
                        ?>
                            <?php
                            if ($cambio=='nombre') {

                                $clinombreD = "SELECT cli.apellidop,cli.apellidom,cli.nombre,cli.nombrec
                                    FROM cli
                                    WHERE cli.cliente=$Cpo[cliente] limit 1;";
                                    $clinombre = mysql_query($clinombreD);

                                $nombrecli = mysql_fetch_array($clinombre);
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Paciente : 
                                </strong><br>
                                <table align="center">
                                    <tr class="content2">
                                        <td>
                                            <input type='text' class='cinput' name='Apellidop' value='<?= $nombrecli["apellidop"] ?>'></input>
                                        </td>
                                        <td>
                                            <input type='text' class='cinput' name='Apellidom' value='<?= $nombrecli["apellidom"] ?>'></input>
                                        </td>
                                        <td>
                                            <input type='text' class='cinput' name='Nombre' value='<?= $nombrecli["nombre"] ?>'></input>
                                        </td>                                    
                                    </tr>
                                </table>
                            <?php
                            }else{
                            ?>
                                <?php
                                if ($veces<='2') {
                                ?> 
                                    <strong>
                                    <a href='ordenescone2.php?busca=<?=$busca?>&cambio=nombre'>
                                    <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                    </i><a>
                                     Paciente : 
                                    </strong><br>
                                    <div align="center"><strong><?= $Cpo["cliente"] . " - " . $nomcliente ?></strong></div>
                                <?php
                                }else{
                                ?>
                                    <strong>
                                     Paciente : 
                                    </strong><br>
                                    <div align="center"><strong><?= $Cpo["cliente"] . " - " . $nomcliente ?></strong></div>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='sexo') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Genero : 
                                </strong><br>
                                <select name='Sexo' class="cinput">
                                    <?php
                                    if($Cpo["sexo"]=='M'){
                                    ?>
                                        <option value="F">Femenino</option>
                                    <?php
                                    }elseif($Cpo["sexo"]=='F'){
                                    ?>
                                        <option value="M">Masculino</option>
                                    <?php
                                    }else{
                                    ?>
                                        <option value="F">Femenino</option>
                                        <option value="M">Masculino</option>
                                    <?php
                                    }
                                    ?>
                                    <option value='<?= $Cpo["sexo"] ?>' selected><?= $Sexo ?></option>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=sexo'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                 Genero : 
                                </strong><br>
                                <div align="center"><?= $Sexo ?></div>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='fechanac' or $cambio=='edad') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Fech. Nacim. : 
                                </strong><br>
                                <input type='date' class='letrap' name='fechan' id="Fechan" value ='<?= $Cpo["fechan"] ?>'/>
                            <?php
                            }else{
                            ?>
                                <?php
                                if ($veces<='2') {
                                ?> 
                                    <strong>
                                    <a href='ordenescone2.php?busca=<?=$busca?>&cambio=fechanac'>
                                    <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                    </i><a>
                                     Fech. Nacim. : 
                                    </strong><br>
                                    <div align="center"><?= $Cpo["fechan"] ?></div>
                                <?php
                                }else{
                                ?>
                                    <strong>
                                     Fech. Nacim. : 
                                    </strong><br>
                                    <div align="center"><?= $Cpo["fechan"] ?></div>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($veces<='2') {
                            ?> 
                                <?php
                                if ($cambio=='edad') {
                                ?> 
                                    <strong>
                                    <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                    </i><a>
                                     Edad : 
                                    </strong><br>
                                    <input type='number' style="width: 50px;" class='cinput'  name='Edad' id="Edad"></input>
                                    <input type="button" name="Calcular" id="Calcular" value="Calcula" class="letrap"></input>
                                <?php
                                }else{
                                ?>
                                    <strong>
                                    <a href='ordenescone2.php?busca=<?=$busca?>&cambio=edad'>
                                    <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                    </i><a>
                                     Edad : 
                                    </strong><br>
                                    <div align="center"><?= $anos . " Años " . $meses . " Meses" ?></div>
                                <?php
                                }
                                ?>
                            <?php
                            }else{
                            ?>
                                    <strong>
                                     Edad : 
                                    </strong><br>
                                    <div align="center"><?= $anos . " Años " . $meses . " Meses" ?></div>
                            <?php
                            }
                            ?>

                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='fecha') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Fech. Entrega : 
                                </strong><br>
                                <input type='datetime-local' class='letrap' name='fecha' value ='<?= $Cpo["fechae"] . "T" . $Cpo["horae"] ?>'/>
                            <?php
                            }else{
                            ?>
                                <strong>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=fecha'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                 Fech. Entrega : 
                                </strong><br>
                                <div align="center"><?= $Cpo["fechae"] . " " . $Cpo["horae"] ?></div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>

        <! –– Tercer reglon Entregas ––>

                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td class="ssbm" align="lefth" colspan="2">
                            <strong>Entrega Sucursal : </strong><br>
                            <div align="center">
                                <?php
                                if ($Cpo[entmos] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entmos&Envios=ck'><font color='red'>Tex. </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entmos&Envios=ck'>Tex. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entmosf] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entmosf&Envios=ck'><font color='red'>Fut. </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entmosf&Envios=ck'>Fut. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entmost] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entmost&Envios=ck'><font color='red'>Tepex. </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entmost&Envios=ck'>Tepex. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entmoslr] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entmoslr&Envios=ck'><font color='red'>Reyes </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entmoslr&Envios=ck'>Reyes <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entmoscms] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entmoscms&Envios=ck'><font color='red'>Cam. </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entmoscms&Envios=ck'>Cam. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>


                                <?php
                                if ($Cpo[entmosch] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entmosch&Envios=ck'><font color='red'>Sn.Vct </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entmosch&Envios=ck'>Sn.Vct <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>
                            </div>
                        </td>
                        <td class="ssbm" align="lefth" colspan="1">
                            <strong>Entrega Fisica : </strong><br>
                            <div align="center">
                                <?php
                                if ($Cpo[tentregamost] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=tentregamost&Envios=ck'><font color='red'>Pac. </font><i class='fa fa-check'style='color:GREEN'  aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=tentregamost&Envios=ck'>Pac. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[tentregamed] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=tentregamed&Envios=ck'><font color='red'>Méd. </font><i class='fa fa-check'style='color:GREEN'  aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=tentregamed&Envios=ck'>Méd. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>
                                
                                <?php
                                if ($Cpo[tentregainst] == '1') {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=tentregainst&Envios=ck'><font color='red'>Inst. </font><i class='fa fa-check'style='color:GREEN'  aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=tentregainst&Envios=ck'>Inst. <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>
                            </div>
                        </td>
                        <td class="ssbm" align="lefth" colspan="2">
                            <strong>Entrega Digital : </strong><br>
                            <div align="center">
                                <?php
                                if ($Cpo[entemailpac] == 1) {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entemailpac&Envios=ck'><font color='red'> Mail/Pac </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entemailpac&Envios=ck'> Mail/Pac <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entwhatpac] == 1) {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entwhatpac&Envios=ck'><font color='red'> Whats/Pac </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entwhatpac&Envios=ck'> Whats/Pac <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entemailmed] == 1) {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entemailmed&Envios=ck'><font color='red'> Mail/Med </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entemailmed&Envios=ck'> Mail/Med <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entwhatmed] == 1) {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entwhatmed&Envios=ck'><font color='red'> Whats/Med </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entwhatmed&Envios=ck'> Whats/Med <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($Cpo[entemailinst] == 1) {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=0&Cmpo=entemailinst&Envios=ck'><font color='red'> Mail/Inst </font><i class='fa fa-check' style='color:GREEN' aria-hidden='true'></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class='edit' href='ordenescone2.php?busca=<?=$busca?>&status=1&Cmpo=entemailinst&Envios=ck'> Mail/Inst <i class='fa fa-times' style='color:RED' aria-hidden='true'></i></a>
                                <?php
                                }
                                ?>
                            </div>
                        </td>
                    </tr>

        <! –– Cuarto reglon Mail ––>

                    <tr style="height: 30px" class="letrap">
                        <td class="ssbm" align="lefth" colspan="2">
                            <?php
                            if ($cambio=='mailpac') {
                            ?>  
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i>
                                <strong>Mail Paciente : </strong><br>
                                <div align="center"><input name='mailpac' value='<?= $Cpo["climail"] ?>' type='email' size='60'></div>
                            <?php
                            } else {
                            ?>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=mailpac'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                <strong>Mail Paciente : </strong><br>
                                <div align="center"><?= $Cpo["climail"] ?></div>
                            <?php
                            }
                            ?>      
                        </td>
                        <td class="ssbm" align="lefth">
                            <strong>Cel. Paciente : </strong><br>
                            <div align="center"><?= $Cpo["celular"] ?></div>
                        </td>
                        <td class="ssbm" align="lefth" colspan="2">
                            <strong>Mail Médico : </strong><br>
                            <div align="center"><?= $Cpo["medmail"] ?></div>
                        </td>
                    </tr>

        <! –– Quito reglon Descuento ––>

                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm" colspan="3">
                            <?php
                            if ($cambio=='descuento') {
                            ?>  
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i>
                                <strong>Descuento (Razón) : </strong><br>
                                <div align="center"><input name='razondescuento' value='<?= $Cpo["descuento"] ?>' type='text' size='60'></div>
                            <?php
                            } else {
                            ?>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=descuento'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                <strong>Descuento (Razón) : </strong><br>
                                <div align="center"><?= $Cpo["descuento"] ?></div>
                            <?php
                            }
                            ?>  
                        </td>
                        <td width='18%' align="lefth" class="ssbm">
                            <strong>No. Receta : </strong><br>
                            <div align="center"><?= $Cpo["receta"] ?></div>
                        </td>
                        <td width='18%' align="lefth" class="ssbm">
                            <strong>Fech. Receta : </strong><br>
                            <div align="center"><?= $Cpo["fecharec"] ?></div>
                        </td>
                    </tr>

        <! –– Sexto reglon Diagnostico ––>

                    <tr style="height: 30px" class="letrap">
                        <td width='28%' align="lefth" class="ssbm" colspan="3">
                            <?php
                            if ($cambio=='diagnostico') {
                            ?>  
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i>
                                <strong>Diagnóstico : </strong><br>
                                <div align="center"><input name='diagmedico' value='<?= $Cpo["diagmedico"] ?>' type='text' size='60'></div>
                            <?php
                            } else {
                            ?>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=diagnostico'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                <strong>Diagnóstico : </strong><br>
                                <div align="center"><?= $Cpo["diagmedico"] ?></div>
                            <?php
                            }
                            ?>   
                        </td>
                        <td width='18%' align="lefth" class="ssbm">
                            <?php
                            if ($cambio=='responsable') {
                            ?>  
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i>
                                <strong>Resp. Econom. : </strong><br>
                                <div align="center"><input name='responsableco' value='<?= $Cpo["responsableco"] ?>' type='text' size='30'></div>
                            <?php
                            } else {
                            ?>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=responsable'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                <strong>Resp. Econom. : </strong><br>
                                <div align="center"><?= $Cpo["responsableco"] ?></div>
                            <?php
                            }
                            ?>   
                        </td>
                        <td width='18%' align="lefth" class="ssbm">
                            <strong>Num. Veces : </strong><br>
                            <div align="center"><a class='content1' href=javascript:winuni('repots.php?busca=<?= $Cpo[cliente] ?>')><?= $Cpo[numveces]; ?></a></div>
                        </td>
                    </tr>

        <! –– Septimo reglon Médico ––>

                    <?php
                    if($Cpo["medico"]=='MD'){
                        $nommedico = ucwords(strtolower(substr($Cpo[medicon],0,50)));
                    }else{
                        $nommedico = ucwords(strtolower(substr($Cpo[nombremed],0,50)));
                    }
                    ?>
                    <tr style="height: 30px" class="letrap"  bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm">
                            <?php
                            if ($cambio=='medico') {
                            ?>  
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i>
                                <strong>Médico : </strong><br>
                                <div align="center">
                                    <input style="width: 250px;" name="Medico" type="text" id="medico" placeholder="Nombre del médico"></input>
                                    <script src="js/jquery-1.8.2.min.js"></script>
                                    <script src="jquery-ui/jquery-ui.min.js"></script>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#medico').autocomplete({
                                                source: function (request, response) {
                                                    $.ajax({
                                                        url: "autocomplete.php",
                                                        datoType: "json",
                                                        data: {q: request.term, k: "md"},
                                                        success: function (data) {
                                                            response(JSON.parse(data));
                                                            console.log(JSON.parse(data));
                                                        }
                                                    });
                                                },
                                                minLength: 1
                                            });
                                        });
                                    </script>
                                </div>
                            <?php   
                            }elseif ($cambio=='MD') {
                            ?>    
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i>
                                <strong>Médico : </strong><br>
                                <div align="center">
                                <input class="letrap" type="text" size="40" value="<?= $nommedico ?>" placeholder="Nombre del medico" name="NombreMedico"></input>
                                </div>

                            <?php
                            } else {
                            ?>
                                <a href='ordenescone2.php?busca=<?=$busca?>&cambio=medico'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
                                <strong>Médico : </strong><br>
                                <div align="center"><?= $Cpo["medico"] . " - " . $nommedico ?></div>
                            <?php
                            }
                            ?>  
                        </td>
                        <td width='5%' valign='top' align="center" class="ssbm2" colspan="3"><a href="ordenescone2.php?busca=<?=$busca?>&op=3&opc=ambulancia"><?= $idprocedencia ?></a> &nbsp; &nbsp; <a href="ordenescone2.php?busca=<?=$busca?>&op=3&opc=silla"> <?= $idprocedencia2 ?></a> &nbsp; &nbsp;<a href="ordenescone2.php?busca=<?=$busca?>&op=3&opc=terceraedad"><?= $idprocedencia3 ?></a> &nbsp; &nbsp; <a href="ordenescone2.php?busca=<?=$busca?>&op=3&opc=problemasv"><?= $idprocedencia4 ?></a> &nbsp; &nbsp; <a href="ordenescone2.php?busca=<?=$busca?>&op=3&opc=bebe"><?= $idprocedencia5 ?></a> &nbsp; &nbsp;  &nbsp; &nbsp; </td>
                        <td width='28%'align="center" class="ssbm2">
                            <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                            <input type="hidden" name="cambio" value="<?= $cambio ?>"></input>
                            <?php
                            if($cambio<>''){
                            ?>
                                <input class="letrap" type="submit" name="Boton" value="Cambiar"></input>
                                &nbsp;
                                <input class="letrap" type="submit" name="Boton" value="Cancelar"></input>
                            <?php                               
                            }
                            ?>
                        </td>
                    </tr>
                    </form>
                </table> 
            </td>
        </tr>      
    </table>  

<table width='95%' border="0" align="center">
    <tr>
        <td align="left" width='60%'>
            <?php
            if ($Traza=='Si') {
                $Encabezadodet='Trazabilidad';
            ?>    

                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=No" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Estudios </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true"></i> Trazabilidad </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Pagos" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Pagos </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Historial" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Historial Facturado </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Linea" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Ingresos en linea </font></a>

            <?php
            } elseif ($Traza=='No') {
                $Encabezadodet='Estudios';
            ?>
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=No" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true"></i> Estudios </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Trazabilidad </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Pagos" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Pagos </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Historial" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Historial Facturado </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Linea" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Ingresos en linea </font></a>
            <?php
            } elseif ($Traza=='Pagos') {
                $Encabezadodet='Pagos';
            ?>
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=No" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Estudios </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Trazabilidad </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Pagos" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true"></i> Pagos </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Historial" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Historial Facturado </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Linea" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Ingresos en linea </font></a>

            <?php
            } elseif ($Traza=='Historial') {
                $Encabezadodet='Historial Facturado';
            ?>

                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=No" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Estudios </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Trazabilidad </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Pagos" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Pagos </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Historial" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true"></i> Historial Facturado </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Linea" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Ingresos en linea </font></a>

            <?php
            } elseif ($Traza=='Linea') {
                $Encabezadodet='Ingresos en linea';
            ?>

                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=No" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Estudios </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Trazabilidad </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Pagos" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Pagos </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Historial" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i> Historial Facturado </font></a>
                &nbsp; &nbsp;
                <a href="ordenescone2.php?busca=<?=$busca?>&Traza=Linea" class="content5" ><font size="2"><i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true"></i> Ingresos en linea </font></a>

            <?php
            }
            ?>
        </td>

        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
        <td align="right" width='40%' class="letrap" valign="bottom">
            <?php
            if($Gnivel=='99'){
                if ($Traza=='No') {
                    if ($modificaest=='agrestudio') {
            ?>
                            <b>Estudio: </b>
                            <input style="width: 250px;" name="IdEstudio" type="text" id="estudio"  autofocus='autofocus'></input>
                            <script src="js/jquery-1.8.2.min.js"></script>
                            <script src="jquery-ui/jquery-ui.min.js"></script>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#estudio').autocomplete({
                                        source: function (request, response) {
                                            $.ajax({
                                                url: "autocomplete.php?Inst=<?=$Cpo[institucion]?>",
                                                datoType: "json",
                                                data: {q: request.term, k: "estudio"},
                                                success: function (data) {
                                                    response(JSON.parse(data));
                                                    console.log(JSON.parse(data));
                                                }
                                            });
                                        },
                                        minLength: 1
                                    });
                                });
                            </script>

                            <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                            <input type="hidden" name="modificaest" value="<?= $modificaest ?>"></input>
                            <?php
                            if($modificaest<>''){
                            ?>
                                <input class="letrap" type="submit" name="Boton" value="Agrega"></input>
                                <input class="letrap" type="submit" name="Boton" value="Cancelar"></input>
                            <?php                               
                            }
                            ?>
                    <?php
                        }else{
                    ?>
                        <a href="ordenescone2.php?busca=<?=$busca?>&modificaest=agrestudio" class="content5" ><i class="fa fa-plus" style="color:#2E86C1;" aria-hidden="true"></i></a>
            <?php
                    }
                }
            }
            ?>
        </td>
        </form>
    </tr>
</table> 
    <table width='99%' align='center' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
        <tr style="background-color: #2c8e3c">
            <td class='letratitulo'align="center" colspan="2">
                ..:: <?= $Encabezadodet ?> ::..
            </td>
        </tr>
        <tr>
            <td class="content1">
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
                <table align="center" cellpadding="3" cellspacing="2" width="97%" border="0">
                    <tr bgcolor="#A7C2FC" class="letrap" align="center">
                        <?php
                        $sql = "SELECT est.descripcion,otd.estudio,otd.precio,otd.status,otd.descuento,otd.precio-(otd.precio*otd.descuento)/100 as importe,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,otd.etiquetas,otd.fechaest,otd.usrest,otd.usrvalida FROM otd INNER JOIN est ON otd.estudio = est.estudio  WHERE orden='$busca'";             

                        $result3 = mysql_query($sql);
                        $num = 0;

                        if ($Traza=='Si') {
                        ?>
                            <td height="12px">
                                <b>Etq</b>
                            </td>
                            <td height="22px">
                                <b>Estudio</b>
                            </td>
                            <td>
                                <b>Etiq</b>
                            </td>
                            <td>
                                <b>Toma</b>
                            </td>
                            <td>
                                <b>Capt</b>
                            </td>
                            <td>
                                <b>Impr</b>
                            </td>
                            <td>
                                <b>Recep</b>
                            </td>
                            <td>
                                <b>Result</b>
                            </td>
                            <?php
                            while ($cSql = mysql_fetch_array($result3)) {
                                if (($nRng % 2) > 0) {
                                    $Fdo = '#FFFFFF';
                                } else {
                                    $Fdo = '#D5D8DC';
                                }

                                if($cSql[etiquetas]>=1){
                                    $imagen1="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                }else{  
                                    $imagen1="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                }

                                if($cSql[dos]<>'0000-00-00 00:00:00'){
                                    $imagen2="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                }else{  
                                    $imagen2="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                }

                                if($cSql[tres]<>'0000-00-00 00:00:00'){
                                    $imagen3="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                }else{  
                                    $imagen3="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                }

                                if($cSql[cuatro]<>'0000-00-00 00:00:00'){
                                    $imagen4="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                }else{  
                                    $imagen4="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                }

                                if($cSql[cinco]<>'0000-00-00 00:00:00'){
                                    $imagen5="<i class='fa fa-check' style='color:green;' aria-hidden='true'></i>";
                                }else{  
                                    $imagen5="<i class='fa fa-times' style='color:red;' aria-hidden='true'></i>";
                                }

                                if($cSql[status] == 'TERMINADA' and $cSql[usrvalida] <> ''){
                                    $imagen6="<i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></i>";
                                }else{  
                                    $imagen6="<i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>";
                                }

                            ?>
                                <tr  class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#A9DFBF';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>

                                <td align="center">
                                    <a class='edit' href="javascript:winuni('impeti.php?op=1&busca=<?= $Cpo[orden] ?>&Est=<?= $cSql[estudio] ?>')"><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a>
                                </td>
                                <td>
                                    <?= $cSql[estudio] .' - '. $cSql[descripcion] ?>
                                </td>
                                <td align="center">
                                    <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si&Det=Eti&Est=<?=$cSql[estudio]?>"><?= $imagen1 ?></b></a>
                                </td>
                                <td align="center">
                                    <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si&Det=Toma&Est=<?=$cSql[estudio]?>"><?= $imagen2 ?></b></a>
                                </td>
                                <td align="center">
                                    <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si&Det=Capt&Est=<?=$cSql[estudio]?>"><?= $imagen3 ?></b></a>
                                </td>
                                <td align="center">
                                    <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si&Det=Impr&Est=<?=$cSql[estudio]?>"><?= $imagen4 ?></b></a>
                                </td>
                                <td align="center">
                                    <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=Si&Det=Recep&Est=<?=$cSql[estudio]?>"><?= $imagen5 ?></b></a>
                                </td>
                                <td align="center">
                                    <b><?= $imagen6 ?></b>
                                </td>
                                </tr>
                            <?php
                                $nRng++;
                            }
                                $sql4 = "SELECT otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,otd.etiquetas,otd.fechaest,otd.usrest,otd.impeti,otd.capturo,otd.impest,otd.recibeencaja FROM otd WHERE otd.orden='$busca' and otd.estudio='$Est' limit 1";          
                                $result4 = mysql_query($sql4);

                                $cSql4 = mysql_fetch_array($result4);

                                if($Det=='Eti'){

                                    $Detalle= '<b>'. $Est .' - '.' Imp. Etiqueta (Fecha/Hora): </b>'.$cSql4[uno] .' <b> Usuario: </b> '. $cSql4[impeti];

                                }elseif($Det=='Toma'){

                                    $Detalle= '<b>'. $Est .' - '.' Toma/Recolecc (Fecha/Hora): </b>'.$cSql4[fechaest] .' <b> Usuario: </b> '. $cSql4[usrest];

                                }elseif($Det=='Capt'){

                                    $Detalle= '<b>'. $Est .' - '.' Captura (Fecha/Hora): </b>'.$cSql4[tres] .' <b> Usuario: </b> '. $cSql4[capturo];

                                }elseif($Det=='Impr'){

                                    $Detalle= '<b>'. $Est .' - '.' Impresion (Fecha/Hora): </b>'.$cSql4[cuatro] .' <b> Usuario: </b> '. $cSql4[impest];

                                }elseif($Det=='Recep'){

                                    $Detalle= '<b>'. $Est .' - '.' Recib.Recep (Fecha/Hora): </b>'.$cSql4[cinco] .' <b> Usuario: </b> '. $cSql4[recibeencaja];

                                }else{

                                    $Detalle='';

                                }
                            ?>
                                <tr class="letrap">
                                    <td>
                                    </td>
                                    <td align="left" colspan="6"><?= $Detalle ?></td>
                                </tr>

                        <?php
                        } elseif ($Traza=='No') {
                        ?>
                            <td height="12px">
                                <b>Etq</b>
                            </td>
                            <td height="22px">
                                <b>Estudio</b>
                            </td>
                            <td>
                                <b>Precio</b>
                            </td>
                            <td>
                                <?php
                                    if($Gnivel=='99'){
                                        if ($modificaest=='desctoestodos') {
                                ?>
                                            <div align="center">
                                            <input class="letrap" style="width: 55px;text-align: center;" type="text" size="6" align="right" name="desctoestodos"></input>
                                            <input type="hidden" name="modificaest" value="desctoestokt"></input>
                                            </div>
                                    <?php
                                        }else{
                                    ?>
                                            <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=No&modificaest=desctoestodos">Movto.</b></a>
                                <?php
                                        }
                                    }else{
                                ?>
                                        <b>Movto.</b>
                                <?php
                                    }
                                ?>
                            </td>
                            <td>
                                <b>Importe</b>
                            </td>  
                            <td>
                            <?php
                                if($Gnivel=='99'){
                            ?>
                                <b>Elim</b>
                            <?php
                                }else{
                            ?>
                                    <b>-</b>
                            <?php
                                }
                            ?>
                            </td> 
                            <?php
                            while ($cSql = mysql_fetch_array($result3)) {
                                if (($nRng % 2) > 0) {
                                    $Fdo = '#FFFFFF';
                                } else {
                                    $Fdo = '#D5D8DC';
                                }
                            ?>
                                <tr  class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#A9DFBF';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                                <td align="center">
                                    <a class='edit' href="javascript:winuni('impeti.php?op=1&busca=<?= $Cpo[orden] ?>&Est=<?= $cSql[estudio] ?>')"><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a>
                                </td>
                                <td>
                                    <?= $cSql[estudio] .' - '. $cSql[descripcion] ?>
                                </td>
                                <?php
                                if($cSql[precio]==0){
                                    $Rojo='RED';
                                }else{
                                    $Rojo='';
                                }
                                ?>
                                <td align="right" bgcolor="<?= $Rojo ?>">
                                    <?= number_format($cSql[precio], 2) ?>
                                </td>
                                <td align="center" class="letrap">
                                <?php
                                    if($Gnivel=='99'){
                                        if ($modificaest=='desctoest' and $cSql[estudio]==$Est) {
                                ?>
                                            <div align="center">
                                            <input class="letrap" style="width: 55px;text-align: center;" type="text" size="6" align="right" name="desctoest" value="<?= $cSql[descuento] ?>"></input>
                                            <input type="hidden" name="Est" value="<?= $Est ?>"></input>
                                            <input type="hidden" name="modificaest" value="desctoestok"></input>
                                            </div>
                                    <?php
                                        }else{
                                    ?>
                                            <b><a href="ordenescone2.php?busca=<?=$busca?>&Traza=No&Est=<?=$cSql[estudio]?>&modificaest=desctoest"><?= $cSql[descuento] ?></b></a>
                                <?php
                                        }
                                    }else{
                                ?>
                                        <?= $cSql[descuento] ?>
                                <?php
                                    }
                                ?>
                                </td>

                                <td  align="right">
                                    <?= number_format($cSql[importe], 2) ?>
                                </td>
                                <?php
                                    if($Gnivel=='99'){
                                ?>
                                        <td align="center">
                                            <b><span><a class="edit" href="ordenescone2.php?op=Eli&Est=<?= $cSql[estudio] ?>&busca=<?= $Cpo[orden] ?>"><i class="fa fa-trash fa-lg" style="color:#C84239" aria-hidden="true"></i></a></span></b>
                                        </td> 
                                <?php
                                    }else{
                                ?>
                                        <td align="center">
                                            <b>-</b>
                                        </td> 
                                <?php
                                    }
                                ?>
                                </tr>
                                <?php
                                $sumP = $sumP + $cSql[precio];
                                $sumPi = $sumPi + $cSql[importe];
                                $nRng++;
                            }                        
                            ?>
                            <tr class="letrap">
                                <td align="center">
                                    <a class='edit' href="javascript:winuni('impeti2.php?op=3&busca=<?= $Cpo[orden] ?>')"><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#a93226'></i></a>
                                </td>
                                <td align="right"><b>Totales: ---> </
                                        b></td>
                                <td align="right"><b>
                                        $  <?= number_format($sumP, 2) ?>
                                    </b></td>
                                <td></td>
                                <td align="right"><b>
                                        $  <?= number_format($sumPi, 2) ?>
                                    </b></td>
                            </tr>
                        <?php
                        } elseif ($Traza=='Pagos') {
                        ?>
                            <td align="center"><b>Id</b></td>
                            <td align="center"><b>Sucursal</b></td>
                            <td align="center"><b>Fecha</b></td>
                            <td align="center"><b>Hora</b></td>
                            <td align="center"><b>Importe</b></td>
                            <td align="center"><b>Tipo de pago</b></td>
                            <td align="center"><b>Usuario</b></td>
                            <td align="center"><b>-</b></td>

                            <?php
                                $PagosDet = "SELECT * FROM cja WHERE orden='$busca'";
                                $PagosD = mysql_query($PagosDet);
                                while ($Pagos = mysql_fetch_array($PagosD)) {
                                    $sucnombrepag = $aSucursal[$Pagos[suc]];
                                    if (($nRng % 2) > 0) { $Fdo = 'FFFFFF';  } else { $Fdo = $DDE8FF; }
                            ?>
                                    <tr  class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#A9DFBF';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                                        <td align="center"><?= $Pagos[id] ?></td>
                                        <td align="center">
                                        <?php
                                        if ($id==$Pagos[id] and $cambiopag=='tpago' and $Gnivel=='99') {
                                        ?> 
                                        <select class='letrap' name='tpagosuc'>
                                            <?php
                                            $CiaA = mysql_query("SELECT id,alias FROM cia WHERE id<>'9' and id<>'100' ORDER BY id");
                                            while ($Cia = mysql_fetch_array($CiaA)) {
                                                if($Cia[id]==$Pagos[suc]){
                                                    echo '<option selected style="font-weight:bold" value="' . $Cia[id] . '">- ' . $Cia[alias] . ' -</option>';
                                                }else{
                                                    echo '<option value="' . $Cia[id] . '">' . $Cia[alias] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        }else{
                                        ?> 
                                            <?= $sucnombrepag ?>
                                        <?php
                                        }
                                        ?> 
                                        </td>
                                        <td align="center"><?= $Pagos[fecha] ?></td>
                                        <td align="center"><?= $Pagos[hora] ?></td>
                                        <td align="right">
                                        <?php
                                        if ($id==$Pagos[id] and $cambiopag=='tpago' and $Gnivel=='99') {
                                        ?> 
                                            <input class="letrap" style="width: 80px;text-align: center;" type="text" size="9" align="right" name="tpagoimp" value="<?= $Pagos[importe] ?>"></input>
                                        <?php
                                        }else{
                                        ?> 
                                            <?= number_format($Pagos[importe],'2') ?>
                                        <?php
                                        }
                                        ?> 
                                        </td>
                                        <?php
                                        if ($id==$Pagos[id] and $cambiopag=='tpago') {
                                        ?>  
                                            <td align="center">
                                                <select name='tpago' class="cinput">
                                                    <option value="Efectivo">Efectivo</option>
                                                    <option value="Tarjeta">Tarjeta</option>
                                                    <option value="Transferencia">Transferencia</option>
                                                    <option value="Cheque">Cheque</option>
                                                    <option value="Credito">Credito</option>
                                                    <option value="Nomina">Nomina</option>
                                                    <option value='<?= $Pagos["tpago"] ?>' selected><?= $Pagos["tpago"] ?></option>
                                                </select>
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td align="center">
                                                <?= $Pagos[tpago] ?>
                                            </td>
                                        <?php
                                        }
                                        ?>  
                                        <td align="center">
                                        <?php
                                        if ($id==$Pagos[id] and $cambiopag=='tpago' and $Gnivel=='99') {
                                        ?> 
                                        <select class='letrap' name='tpagocap'>
                                            <?php
                                            $PerA = mysql_query("SELECT id,uname FROM authuser WHERE id<>'2' and id<>'140' and id<>'155' ORDER BY uname");
                                            while ($Per = mysql_fetch_array($PerA)) {
                                                $UsrCap=strtoupper($Per[uname]);
                                                if($UsrCap==$Pagos[usuario]){
                                                    echo '<option selected style="font-weight:bold" value="' . $UsrCap . '">- ' . $UsrCap . ' -</option>';
                                                }else{
                                                    echo '<option value="' . $UsrCap . '">' . $UsrCap . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        } else {
                                        ?>
                                            <?= $Pagos[usuario] ?>
                                        <?php
                                        }
                                        ?>
                                        </td>
                                        <?php
                                        if ($id==$Pagos[id] and $cambiopag=='tpago') {
                                        ?>  
                                            <td align="center">
                                                <input class="letrap" type="submit" name="Boton" value="OK"></input>
                                                <input class="letrap" type="submit" name="Boton" value="X"></input>
                                            </td>
                                        <?php
                                        } else {
                                        ?>

                                            <?php
                                                if($Gnivel=='99'){
                                            ?>
                                                    <td align="center">
                                                        <a href='ordenescone2.php?busca=<?=$busca?>&Traza=Pagos&cambiopag=tpago&id=<?=$Pagos[id]?>'>
                                                        <i class="fa fa-square fa-lg" style="color:#2E86C1;" aria-hidden="true">
                                                        </i><a>
                                                    </td>
                                            <?php
                                                }else{
                                            ?>
                                                    <td align="center">
                                                        <a href='ordenescone2.php?busca=<?=$busca?>&Traza=Pagos&cambiopag=tpago&id=<?=$Pagos[id]?>'>
                                                        <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                                        </i><a>
                                                    </td>
                                            <?php
                                                }
                                            ?>

                                        <?php
                                        }
                                        ?> 
                                    </tr>
                            <?php
                                    $nRng++;
                                }
                            ?>
                                <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                    <td align="center"></td>
                                    <td align="center"><b>Importe Total</b></td>
                                    <td align="center"><b> $ <?= number_format($Cpo[importe],'2') ?></b></td>
                                    <td align="center"><b>Abono Total</b></td>
                                    <td align="right"><b> $ <?= number_format($Cja[importe],'2') ?></b></td>
                                    <td align="center"><b>Saldo $ <?= number_format($Cpo[importe]-$Cja[importe],'2') ?></b></td>
                                    <td align="center"><b><font color="RED"><b>Pagada : <?= $Cpo[pagada] ?></b></font></b></td>
                                    <td align="center"></td>
                                </tr>

                        <?php
                        } elseif ($Traza=='Historial') {
                        ?>
                            <td aling='center'><b>Orden</b></td>
                            <td align='center'><b>Pdf</b></td>
                            <td align='center'><b>Xml</b></td>
                            <td aling='center'><b>Fecha de orden</b></td>
                            <td aling='center'><b>Folio</b></td>
                            <td aling='center'><b>Cliente<b></td>
                            <td aling='center'><b>Envios<b></td>
                        
                            <?php
                            $sql = "SELECT ot.orden, ot.fecha, fcd.id FROM ot LEFT JOIN fcd ON fcd.orden = ot.orden WHERE ot.orden = $Cpo[orden]";
                            $cSql = mysql_query($sql);

                            while ($cc = mysql_fetch_array($cSql)) {
                                $cSqlH = "SELECT clif.nombre
                                FROM clif,fc
                                WHERE clif.id=fc.cliente and fc.id='$cc[id]'";

                                $HesA = mysql_query($cSqlH);
                                $Hes = mysql_fetch_array($HesA);

                                if (is_numeric($cc[id])) {
                                    $nvo = $cc[id];

                                    if ($factura <> $cc[id]) {

                                        if (($nRng % 2) > 0) { $Fdo = '#FFFFFF'; } else { $Fdo = '#D5D8DC'; }

                                        if (is_numeric($cc[id])) {
                                            $nvo = $cc[id];
                                        } else {
                                            $nvo = "<i class='fa fa-times fa-gb' style='color:red;' aria-hidden='true'></i>";
                                        }
                            ?>
                                        <tr  class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#A9DFBF';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                                            <td><?= $cc[orden] ?></td>
                                            <td align='center'><a href=javascript:winuni('ordenesavae.php?busca=<?= $nvo ?>&op=download')><img src='lib/Pdf.gif' alt='Imprime copia pdf' border='0'></a></td>
                                            <td align='center'><a href=javascript:winuni('ordenesavae.php?busca=<?= $nvo ?>&op=xml')><i class="fa fa-file-excel-o" alt='Imprime copia pdf' border='0'></a></i></td>
                                            <td><?= $cc[fecha] ?></td>
                                            <td><?= $nvo ?></td>
                                            <td><?= $Hes[nombre] ?></td>
                                            <td><a class='content1' href=javascript:winuni('facturase1.php?busca=<?= $cc[id] ?>')><b>Correo Anterior</a></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                        ?>
                            <?php
                                $factura = $cc[id];
                                $nRng++;
                            }
                            ?> 

                        <?php
                        } elseif ($Traza=='Linea') {
                        ?>
                            <td aling='center'><b>Fecha</b></td>
                            <td aling='center'><b>Detalle</b></td>
                            <?php
                            $Sql = mysql_query("SELECT * FROM entrega_resultados WHERE orden = $busca order by id DESC limit 6");

                            while ($cc = mysql_fetch_array($Sql)) {
                                if (($nRng % 2) > 0) { $Fdo = '#FFFFFF'; } else { $Fdo = '#D5D8DC'; }
                                ?>
                                <tr  class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#A9DFBF';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                                    <td><?= $cc["fecha"] ?></td>
                                    <td><?= $cc["detalle"] ?></td>
                                </tr>
                                <?php
                                $nRng++;
                            }
                        }
                        ?>
                    </tr>
                </table>
                <input type="hidden" name="id" value="<?= $id ?>"></input>
                <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                <input type="hidden" name="Traza" value="<?= $Traza ?>"></input>
            </form>
            </td>
        </tr>
    </table>

<table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    

    <tr>
        <td valign='top' align='center' width='55%'>
            <table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <tr style="background-color: #2c8e3c">
                    <td class='letratitulo'align="center" colspan="2">
                        ..:: Observaciones ::..
                    </td>
                </tr>
                <tr style="height: 30px">

                    <td class="Inpt">
                        <b>Observación :</b>
                        <br>
                        <div align="center"><?= $Cpo[observaciones] ?></div>
                        <br>
                    </td>
                </tr>
                <tr style="height: 30px">
                    <td class="Inpt">
                        <b>Agregar Observaciones :</b>
                        <br>
                        <div align="center"><TEXTAREA name='Observaciones' cols='60' rows='4' class="letrap"></TEXTAREA></div>
                    </td>
                </tr>
                <tr style="height: 20px;" align="center">
                    <td colspan="2">
                        <input class="letrap" type="submit" name="Boton" value="Guardar_Obs"></input>
                        <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                    </td>
                </tr>
                </form>
            </table> 
        </td>
        <td valign='top' align='center' width='45%'>
                <?php
                TablaDeLogs("/Consulta ordenes/", $busca);
                ?>
        </td>
    </tr>
</table> 

    <br>
    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2'> 
        <tr align="letf"> 
            <td valign='top' width="22%">
                <a class='elim' href='javascript:window.close()'><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table> 
        
</body>

<script type="text/javascript">
    $(document).ready(function () {
        var busca = "<?= $busca ?>";

        $("#Calcular").click(function () {
            const st = new Date();
            var a = st.getFullYear();
            var c = st.getMonth();
            var b = $("#Edad").val();
            if (c < 10 && c !== 0) {
                c = 0 + "" + c;
            } else if (c == 0) {
                c = 0 + "" + 1;
            }
            var year = a - b;
            var fechan = year + "-" + c + "-01";

            $("#Fechan").val(fechan);

        });
    });
</script>

</html>

<?php
mysql_close();
?>