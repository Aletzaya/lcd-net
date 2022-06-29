<?php
date_default_timezone_set("America/Mexico_City");

$CjaA = mysql_query("SELECT sum(importe) importe FROM cja WHERE orden='$busca'");
$Cja = mysql_fetch_array($CjaA);

$cSql = "SELECT ot.orden,ot.fecha,ot.descuento,ot.hora,ot.fechae,ot.cliente,cli.nombrec nombrecli,ot.importe,ot.ubicacion,ot.servicio,"
        . "ot.institucion,ot.medico,ot.medicon,ot.diagmedico,med.nombrec nombremed,ot.status,ot.recibio,ot.institucion,ot.pagada,ot.observaciones,"
        . "ot.horae,cli.fechan,ot.suc,ot.recepcionista,ot.idprocedencia,ot.responsableco,ot.receta,inst.nombre nombrei,cli.sexo,cli.numveces,cli.mail climail,med.mail medmail,ot.entmos,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,ot.tentregamed,ot.tentregainst,ot.entemailpac,ot.entwhatpac,ot.entemailmed,ot.entwhatmed,ot.entemailinst,cli.celular "
        . "FROM ot "
        . "LEFT JOIN cli ON ot.cliente = cli.cliente "
        . "LEFT JOIN med ON ot.medico=med.medico "
        . "LEFT JOIN inst ON ot.institucion=inst.institucion "
        . "WHERE orden = " . $busca;

$HeA = mysql_query($cSql);
$Cpo = mysql_fetch_array($HeA);
$cSql = "SELECT ot.orden,ot.fecha,ot.hora,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec,ot.status,ot.recibio,ot.pagada,ot.observaciones,ot.horae,cli.fechan,ot.suc,ot.recepcionista,ot.idprocedencia,ot.responsableco
                FROM ot,cli,med
                WHERE ot.cliente=cli.cliente AND ot.medico=med.medico AND ot.orden='$busca'";
$HeB = mysql_query($cSql);
$He = mysql_fetch_array($HeB);

$Fechanac = $Cpo["fechan"];
$Fecha = date("Y-m-d");
$FechaCompleta = date("Y-m-d H:i:s");
$array_nacimiento = explode("-", $Fechanac);
$array_actual = explode("-", $Fecha);
$anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias = $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0) {
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
    switch ($array_actual[1]) {
        case 1: $dias_mes_anterior = 31;
            break;
        case 2: $dias_mes_anterior = 31;
            break;
        case 3: $dias_mes_anterior = 28;
            break;
        case 4: $dias_mes_anterior = 31;
            break;
        case 5: $dias_mes_anterior = 30;
            break;
        case 6: $dias_mes_anterior = 31;
            break;
        case 7: $dias_mes_anterior = 30;
            break;
        case 8: $dias_mes_anterior = 31;
            break;
        case 9: $dias_mes_anterior = 31;
            break;
        case 10: $dias_mes_anterior = 30;
            break;
        case 11: $dias_mes_anterior = 31;
            break;
        case 12: $dias_mes_anterior = 30;
            break;
    }

    $dias = $dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses 
if ($meses < 0) {
    --$anos;
    $meses = $meses + 12;
}

if (is_string($_REQUEST["Tipo"])) {
    $Sql = "UPDATE otd SET recoleccionest = '" . $_REQUEST["Tipo"] . "' WHERE orden = " . $_REQUEST["busca"] . " AND estudio = '" . $_REQUEST["Estudio"] . "' ;";
    if (mysql_query($Sql)) {
        $Msj = "Registro actualizado con Exito!";
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Operativa/Captura Resultados/Captura tipo de toma. Estudio : " . $_REQUEST["Estudio"] . "','otd',$FechaCompleta,$busca);";
        if (mysql_query($Sql)) {
            header("Location: capturaresultadod.php?Msj=$Msj&busca=" . $_REQUEST["busca"]);
        }
    } else {
        $Msj = "Error SQL :" . $Sql;
    }
} elseif (($_REQUEST["Estado"])) {
    if ($_REQUEST["Estado"] === "Pendiente") {
        $e = "TOMA/REALIZ";
    }else{
        $e = "";
    }
    $Sql = "UPDATE otd SET statustom = '$e' WHERE orden = " . $_REQUEST["busca"] . " AND estudio = '" . $_REQUEST["Estudio"] . "' ;";
    if (mysql_query($Sql)) {
        $Msj = "Registro actualizado con Exito!";
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Operativa/Captura Resultados/Captura tipo de toma. Estudio : " . $_REQUEST["Estudio"] . "','otd','$FechaCompleta',$busca);";
        if (mysql_query($Sql)) {
            header("Location: capturaresultadod.php?Msj=$Msj&busca=" . $_REQUEST["busca"]);
        }
    } else {
        $Msj = "Error SQL :" . $Sql;
    }
} else if ($_REQUEST["Boton"] === "Guardar") {

    $cobser     = "SELECT ot.observaciones
            FROM ot
            WHERE ot.orden=$_REQUEST[busca]";

        $obser       = mysql_query($cobser);
        $obs1        = mysql_fetch_array($obser);
        $obs = $obs1[observaciones];

        $obs = $obs.' '.$_REQUEST[Observaciones];

    $Sql = "UPDATE ot SET observaciones = '" . $obs . "' WHERE orden = " . $_REQUEST["busca"];

    if (mysql_query($Sql)) {
        $Msj = "Observaciones Editadas con Exito!";
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Toma de muestras/Edita Observaciones','ot','$FechaCompleta',$busca);";
        if (mysql_query($Sql)) {
            header("Location: capturaresultadod.php?Msj=$Msj&busca=" . $_REQUEST["busca"]);
        }
    }

} else if ($_REQUEST["Boton"] === "Guardar_Obs") {

    $cobser     = "SELECT ot.observaciones
            FROM ot
            WHERE ot.orden=$_REQUEST[busca]";

        $obser       = mysql_query($cobser);
        $obs1        = mysql_fetch_array($obser);
        $obs = $obs1[observaciones];

        $obs = $obs.' '.$_REQUEST[Observaciones];

    $Sql = "UPDATE ot SET observaciones = '" . $obs . "' WHERE orden = " . $_REQUEST["busca"];

    if (mysql_query($Sql)) {
        $Msj = "Observaciones Editadas con Exito!";
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Consulta ordenes/Edita Observaciones','ot','$FechaCompleta',$busca);";
        if (mysql_query($Sql)) {
            header("Location: ordenescone2.php?Msj=$Msj&busca=" . $_REQUEST["busca"]);
        }
    }

} else if ($_REQUEST["op"] == 'pcs') {
    $cSql = "SELECT * FROM proceso
		WHERE proceso.orden='" . $_REQUEST["busca"] . "' AND proceso.est='" . $_REQUEST["Estudio"] . "'";

    if (!$resG == mysql_query($cSql . " ORDER BY proceso.orden")) {
        $lUp = mysql_query("INSERT INTO proceso
			  (orden,est,insumo,cnt,intext,unidad,usr,fecha,pcs)
			  VALUES
			  ('" . $_REQUEST["busca"] . "','" . $_REQUEST["Estudio"] . "','" .
                $_REQUEST["insumo"] . "','" . $_REQUEST["cnt"] . "','" . $_REQUEST["intext"] . "','" .
                $_REQUEST["unidad"] . "','$Gusr','$FechaCompleta','" . $_REQUEST["pcs"] . "')");
        $Msj = "Registro ingresado con exito!";
    } else {
        $lUp = mysql_query("UPDATE proceso SET orden='" . $_REQUEST["busca"] . "',est='" . $_REQUEST["Estudio"] . "',insumo='$_REQUEST[insumo]',cnt='$_REQUEST[cnt]',
			   intext='$_REQUEST[intext]',unidad=$_REQUEST[unidad]',usr='$Gusr',fecha='$FechaCompleta',pcs='$_REQUEST[pcs]'
	            WHERE orden='" . $_REQUEST["busca"] . "' and est='" . $_REQUEST["Estudio"] . "'");
        $Msj = "Registro actualizado con exito!";
    }
    header("Location: capturaresultadod.php?Msj=$Msj&busca=" . $_REQUEST["busca"]);
} else if ($_REQUEST["Boton"] === "Actualiza") {
    $PrmA = mysql_query("SELECT password ps FROM cia WHERE id='1'");
    $Prm = mysql_fetch_array($PrmA);

    $Clave = md5($_REQUEST[Password]);

    if ($Prm["ps"] === $Clave || $Gusr === 'nazario' || $Gusr === "ivett" || $Gusr === "Javier" || $Gusr === "FAZ") {

        $MedA = mysql_query("SELECT nombrec FROM med WHERE medico='$_REQUEST[Medico]'");

        if ($Med = mysql_fetch_array($MedA)) {

            $lUp = mysql_query("UPDATE ot SET medico='$_REQUEST[Medico]',medicon='$_REQUEST[Medicon]',
                   receta='$_REQUEST[Receta]',diagmedico='$_REQUEST[Diagmedico]',
                   observaciones='$_REQUEST[Observaciones]',fecha='$_REQUEST[Fecha]',institucion='$_REQUEST[Institucion]' 
                   WHERE orden='$_REQUEST[busca]'");

            $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                    . "('$Gusr','/Operativa/Captura Resultados/Edita Datos principales','ot','$FechaCompleta',$_REQUEST[busca]);";
            if (mysql_query($Sql)) {
                $Msj = "Registro actualizado con exito!";
                header("Location: capturaresultadoe.php?Msj=$Msj&busca=" . $_REQUEST["busca"]);
            }
        }
    }
}