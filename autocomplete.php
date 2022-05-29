<?php
session_start();
require("lib/lib.php");
$link = conectarse();
$Gusr = $_SESSION[Usr][0];
$Vta = $_SESSION[cVarVal][0];
$str = $_GET['q'];
$k = $_GET['k'];
$Inst   = $_REQUEST[Inst];

if ($k == "cal") {
    $sql="SELECT * FROM calendario";
    $resultado = mysql_query($sql);
    error_log($sql);
    $datos = array();
    $n=1;
    while ($row = mysql_fetch_array($resultado)) {
        $datos[$n] = $row['titulo'] ." ". $row['inicia'];
        $n++;
    }
}elseif($k == "pst"){

    $Palabras  = str_word_count($str);  //Dame el numero de palabras
    if($Palabras > 1){
         $P=str_word_count($str,1);          //Metelas en un arreglo
         for ($i = 0; $i < $Palabras; $i++) {
                if(!isset($BusInt)){$BusInt=" cli.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and cli.nombrec like '%$P[$i]%' ";}
         }
    }else{
         $BusInt=" cli.nombrec like '%$str%' ";  
    }

    $sql="SELECT cliente,nombrec,fechan,numveces FROM cli WHERE cliente LIKE ('%$str%') or $BusInt;";
    $resultado = mysql_query($sql);
    error_log($sql);
    $datos = array();
    $n=1;
    while ($row = mysql_fetch_array($resultado)) {

        $Fechanac  =  $row['fechan'];
        $Fecha   = date("Y-m-d");
        $array_nacimiento = explode ( "-", $Fechanac ); 
        $array_actual = explode ( "-", $Fecha ); 
        $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
        $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
        $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

        if ($dias < 0) 
        { 
            --$meses; 

            //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
            switch ($array_actual[1]) { 
                   case 1:     $dias_mes_anterior=31; break; 
                   case 2:     $dias_mes_anterior=31; break; 
                   case 3:  $dias_mes_anterior=28; break;
        //                      if (bisiesto($array_actual[0])) 
        //                      { 
        //                          $dias_mes_anterior=29; break; 
        //                      } else { 
        //                          $dias_mes_anterior=28; break; 
        //                      } 
                   case 4:     $dias_mes_anterior=31; break; 
                   case 5:     $dias_mes_anterior=30; break; 
                   case 6:     $dias_mes_anterior=31; break; 
                   case 7:     $dias_mes_anterior=30; break; 
                   case 8:     $dias_mes_anterior=31; break; 
                   case 9:     $dias_mes_anterior=31; break; 
                   case 10:     $dias_mes_anterior=30; break; 
                   case 11:     $dias_mes_anterior=31; break; 
                   case 12:     $dias_mes_anterior=30; break; 
            } 

            $dias=$dias + $dias_mes_anterior; 
        } 
        //
        //ajuste de posible negativo en $meses 
        if ($meses < 0) 
        { 
            --$anos; 
            $meses=$meses + 12; 
        } 

        if($anos>='110'){
            $Edad='Verificar';
        }elseif($anos=='0' and $meses=='0'){
            $Edad=$dias.' Dias';
        }elseif($anos=='0' and $meses>='1'){
            $Edad=$meses.' Meses';
        }elseif($anos>='1'){
            $Edad=$anos .' Años ';
        }



        $datos[$n] = $row['cliente'] ." ". $row['nombrec'] ." - ". $row['fechan'] ." - ". $Edad ." - *". $row['numveces'];
 //       $datos[$n] = $row['cliente'] ." ". $row['nombrec'] ." - ". $row['fechan'] ." - *". $row['numveces'];
        $n++;
    }
}elseif($k == "md"){

    $Palabras  = str_word_count($str);  //Dame el numero de palabras
    if($Palabras > 1){
         $P=str_word_count($str,1);          //Metelas en un arreglo
         for ($i = 0; $i < $Palabras; $i++) {
                if(!isset($BusInt)){$BusInt=" med.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and med.nombrec like '%$P[$i]%' ";}
         }
    }else{
         $BusInt=" med.nombrec like '%$str%' ";  
    }

    //$sql="SELECT buscador FROM med WHERE buscador LIKE ('%$str%');";
    $sql="SELECT id, medico, apellidop, apellidom, nombre, nombrec, status FROM med WHERE status='Activo' and (id LIKE ('%$str%') or  medico LIKE ('%$str%') or $BusInt);";

    $resultado = mysql_query($sql);
    error_log($sql);
    $datos = array();
    $n=1;
    while ($row = mysql_fetch_array($resultado)) {
        $datos[$n] = $row['id'] ." ". $row['medico'] ." ". $row['nombrec'];
       // $datos[$n] = $row['buscador'];
        $n++;
    }
}elseif($k == "est"){
    $OtnvaA   = mysql_query("SELECT lista,inst FROM otnvas WHERE usr='$Gusr' and venta='$Vta'");

    $Otnva = mysql_fetch_array($OtnvaA);

    $Lista = "lt".ltrim($Otnva[lista]);

    $Ins = $Otnva[inst];

    $InstA   = mysql_query("SELECT descuento FROM inst WHERE institucion='$Ins'");

    $Inst = mysql_fetch_array($InstA);

    $descto=$Inst[descuento];

    $sql="SELECT id, estudio, descripcion,$Lista as preciosl, activo FROM est WHERE activo='Si' and (id LIKE ('%$str%') or  estudio LIKE ('%$str%') or descripcion LIKE ('%$str%'))";
    $resultado = mysql_query($sql);
    error_log($sql);
    $datos = array();
    $n=1;

    while ($row = mysql_fetch_array($resultado)) {

        $precio=$row['preciosl']-($row['preciosl']*$descto)/100;

        $datos[$n] = $row['id'] ." ". $row['estudio'] ." ". $row['descripcion'] ." $ ". $precio;
        $n++;
    }
}elseif($k == "inst"){
    $sql="SELECT institucion,alias,status FROM inst WHERE status='Activo' and (alias LIKE ('%$str%') or institucion LIKE ('%$str%'));";
    $resultado = mysql_query($sql);
    error_log($sql);
    $datos = array();
    $n=1;
    while ($row = mysql_fetch_array($resultado)) {
        $datos[$n] = $row['institucion'] ." ". $row['alias'];
        $n++;
    }
}elseif($k == "estudio"){

    $LtA = mysql_query("SELECT lista,institucion FROM inst WHERE institucion='$Inst'");
    $Lt=mysql_fetch_array($LtA);
    $Lista="lt".ltrim($Lt[lista]);

    $Ins = $Lt[institucion];

    $InstA   = mysql_query("SELECT descuento FROM inst WHERE institucion='$Ins'");

    $Inst2 = mysql_fetch_array($InstA);

    $descto=$Inst2[descuento];

    $sql="SELECT id, estudio, descripcion,$Lista as preciosl, activo FROM est WHERE activo='Si' and (id LIKE ('%$str%') or  estudio LIKE ('%$str%') or descripcion LIKE ('%$str%'))";
    $resultado = mysql_query($sql);
    error_log($sql);
    $datos = array();
    $n=1;

    while ($row = mysql_fetch_array($resultado)) {

        $precio=$row['preciosl']-($row['preciosl']*$descto)/100;

        $datos[$n] = $row['id'] ." ". $row['estudio'] ." ". $row['descripcion'] ." $ ". $precio;
        $n++;
    }
}
echo json_encode($datos);
