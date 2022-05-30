<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$suc = $_REQUEST[ele];

  if($suc=='1'){

    $Tablapdf    = "elepdf";
    $ele = 0;
    $elesuc = 'Standar';
    
  }elseif($suc=='2'){

    $Tablapdf    = "elealtpdf";
    $ele = 1;
    $elesuc = 'Alternativo';

  }elseif($suc=='3'){

    $Tablapdf    = "elealtpdf2";
    $ele = 2;
    $elesuc = 'Alternativo2';

  }elseif($suc=='4'){

    $Tablapdf    = "elealtpdf3";
    $ele = 3;
    $elesuc = 'Alternativo3';

  }elseif($suc=='5'){

    $Tablapdf    = "elealtpdf4";
    $ele = 4;
    $elesuc = 'Alternativo4';

  }

#Variables comunes;
$Titulo = "Ordenes de estudio";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Fecha = date("Y-m-d");
$date = date("Y-m-d H:i:s");

#Variables comunes;
$busca = $_REQUEST[busca];
$Retornar = "estudios.php";

if ($_REQUEST[Op] == 'Dt') {

    $Msj = "Elemento eliminado con exito";
    $sql = "DELETE FROM $Tablapdf WHERE estudio='$busca' and idnvo='$_REQUEST[idnvo]' limit 1;";
    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error() . "&Error=SI";
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Elementos/'.$elesuc.'/Elimina Elemento '.$_REQUEST[id], "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioselem.php");

}elseif($_REQUEST[Op] == 'ab') {

    $Msj = "Elementos abiertos con exito";
    $sql = "UPDATE est SET bloqele = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error() . "&Error=SI";
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Elementos/'.$elesuc.'/Desbloquea Elementos', "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioselem.php");

}elseif($_REQUEST[Op] == 'cr') {

    $Msj = "Elementos cerrados con exito";
    $sql = "UPDATE est SET bloqele = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis Mysql " . $sql . " : " . mysql_error() . "&Error=SI";
    }
    AgregaBitacoraEventos($Gusr, '/Estudios/Elementos/'.$elesuc.'/Bloquea Elementos', "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioselem.php");

}

if($_REQUEST[Boton] == 'Agregar'){

    $lUp   = mysql_query("INSERT INTO $Tablapdf (estudio,descripcion,tipo,unidad,longitud,decimales,id,min,max,nota,vlogico,vtexto,alineacion,celda1,celda2,celda3,celdas,valref,idvalor1,parentesis1,valor1,operador1,idvalor2,valor2,parentesis2,operador2,idvalor3,valor3,parentesis3,operador3,idvalor4,valor4,parentesis4,calculo,condiciona) 
          VALUES 
          ('$busca','$_REQUEST[Descripcion]','$_REQUEST[Tipo]','$_REQUEST[Unidad]','$_REQUEST[Longitud]','$_REQUEST[Decimales]','$_REQUEST[Id]','$_REQUEST[Min]','$_REQUEST[Max]','$_REQUEST[Nota]','$_REQUEST[Vlogico]','$_REQUEST[Vtexto]','$_REQUEST[Alineacion]','$_REQUEST[Celda1]','$_REQUEST[Celda2]','$_REQUEST[Celda3]','$_REQUEST[Celdas]','$_REQUEST[Valref]','$_REQUEST[Idvalor1]','$_REQUEST[Parentesis1]','$_REQUEST[Valor1]','$_REQUEST[Operador1]','$_REQUEST[Idvalor2]','$_REQUEST[Valor2]','$_REQUEST[Parentesis2]','$_REQUEST[Operador2]','$_REQUEST[Idvalor3]','$_REQUEST[Valor3]','$_REQUEST[Parentesis3]','$_REQUEST[Operador3]','$_REQUEST[Idvalor4]','$_REQUEST[Valor4]','$_REQUEST[Parentesis4]','$_REQUEST[Calculo]','$_REQUEST[Condiciona]')");

      $lUp2   = mysql_query("UPDATE $Tablapdf SET fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

    $Msj = "Elemento agregado con exito";

    AgregaBitacoraEventos($Gusr, '/Estudios/Elementos/'.$elesuc.'/Agrega Elemento '.$_REQUEST[Id], "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioselem.php");

}elseif($_REQUEST[Boton] == 'Actualizar'){
    
      $lUp   = mysql_query("UPDATE $Tablapdf SET descripcion='$_REQUEST[Descripcion]',tipo='$_REQUEST[Tipo]',unidad='$_REQUEST[Unidad]',
               longitud='$_REQUEST[Longitud]',decimales='$_REQUEST[Decimales]',id='$_REQUEST[Id]',
               min='$_REQUEST[Min]',max='$_REQUEST[Max]',nota='$_REQUEST[Nota]',vlogico='$_REQUEST[Vlogico]',vtexto='$_REQUEST[Vtexto]',alineacion='$_REQUEST[Alineacion]',celda1='$_REQUEST[Celda1]',celda2='$_REQUEST[Celda2]',celda3='$_REQUEST[Celda3]',celdas='$_REQUEST[Celdas]',valref='$_REQUEST[Valref]',idvalor1='$_REQUEST[Idvalor1]',parentesis1='$_REQUEST[Parentesis1]',valor1='$_REQUEST[Valor1]',operador1='$_REQUEST[Operador1]',idvalor2='$_REQUEST[Idvalor2]',valor2='$_REQUEST[Valor2]',parentesis2='$_REQUEST[Parentesis2]',operador2='$_REQUEST[Operador2]',idvalor3='$_REQUEST[Idvalor3]',valor3='$_REQUEST[Valor3]',parentesis3='$_REQUEST[Parentesis3]',operador3='$_REQUEST[Operador3]',idvalor4='$_REQUEST[Idvalor4]',valor4='$_REQUEST[Valor4]',parentesis4='$_REQUEST[Parentesis4]',calculo='$_REQUEST[Calculo]',condiciona='$_REQUEST[Condiciona]'
               WHERE idnvo='$_REQUEST[Up]'");

      $lUp2   = mysql_query("UPDATE $Tablapdf SET fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

    $Msj = "Elemento actualizado con exito";

    AgregaBitacoraEventos($Gusr, '/Estudios/Elementos/'.$elesuc.'/Actualiza Elemento '.$_REQUEST[Id], "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioselem.php");

}elseif($_REQUEST[Boton] == 'Reg_Dato_Estad'){

    $lUp2   = mysql_query("UPDATE $Tablapdf SET estadistica='No' WHERE estudio='$_REQUEST[busca]'");

    $lUp3   = mysql_query("UPDATE $Tablapdf SET estadistica='Si' WHERE estudio='$_REQUEST[busca]' and id='$_REQUEST[id]'");

    $Msj = "Dato Estadistico actualizado con exito";

    AgregaBitacoraEventos($Gusr, '/Estudios/Elementos/'.$elesuc.'/Dato Estadistico Actualizado '.$_REQUEST[id], "est", $date, $busca, $Msj . "&ele=" . $suc, "estudioselem.php");

}

if($_REQUEST[cEd]<>''){
    $CpoA  = mysql_query("SELECT * FROM $Tablapdf WHERE idnvo='$_REQUEST[cEd]'");
    $Cpo   = mysql_fetch_array($CpoA); 

    if($Cpo[tipo]=='c'){$Disp = '1.- Caracter';
    }elseif($Cpo[tipo]=='n'){$Disp='2.- Numerico';
    }elseif($Cpo[tipo]=='d'){$Disp='3.- Fecha';
    }elseif($Cpo[tipo]=='l'){$Disp='4.- Logico[Positivo/Negativo]';
    }elseif($Cpo[tipo]=='t'){$Disp='5.- Texto';
    }elseif($Cpo[tipo]=='v'){$Disp='6.- Espacio *';
    }elseif($Cpo[tipo]=='z'){$Disp='7.- Columnas *';
    }elseif($Cpo[tipo]=='e'){$Disp='8.- Encabezado *';
    }elseif($Cpo[tipo]=='s'){$Disp='9.- Seccion *';}

    if($Cpo[vlogico]=='Positivo'){$Vlogico = '1.- Positivo';
    }elseif($Cpo[vlogico]=='Negativo'){$Vlogico='2.- Negativo';
    }else{$Vlogico=$Cpo[vlogico];}

    if($Cpo[alineacion]=='right'){
        $Alineacion = '1.- Derecha';
    }elseif($Cpo[alineacion]=='left'){
        $Alineacion='2.- Izquierda';
    }elseif($Cpo[alineacion]=='center'){
        $Alineacion='3.- Centro';
    }
          
    if($Cpo[celdas]=='Si'){
        $Celdas = '1.- Si';
    }elseif($Cpo[celdas]=='No'){
        $Celdas = '2.- No';
    }

    if($Cpo[valref]=='Si'){
        $Valref = '1.- Si';
    }elseif($Cpo[valref]=='No'){
        $Valref = '2.- No';
    }

    if($Cpo[operador1]=='+'){
        $Operador1 = '+';
    }elseif($Cpo[operador1]=='-'){
        $Operador1 = '-';
    }elseif($Cpo[operador1]=='*'){
        $Operador1 = '*';
    }elseif($Cpo[operador1]=='/'){
        $Operador1 = '/';
    }elseif($Cpo[operador1]==''){
        $Operador1 = 'N/A';
    }  

    if($Cpo[operador2]=='+'){
        $Operador2 = '+';
    }elseif($Cpo[operador2]=='-'){
        $Operador2 = '-';
    }elseif($Cpo[operador2]=='*'){
        $Operador2 = '*';
    }elseif($Cpo[operador2]=='/'){
        $Operador2 = '/';
    }elseif($Cpo[operador2]==''){
        $Operador2 = 'N/A';
    }  

    if($Cpo[operador3]=='+'){
        $Operador3 = '+';
    }elseif($Cpo[operador3]=='-'){
        $Operador3 = '-';
    }elseif($Cpo[operador3]=='*'){
        $Operador3 = '*';
    }elseif($Cpo[operador3]=='/'){
        $Operador3 = '/';
    }elseif($Cpo[operador3]==''){
        $Operador3 = 'N/A';
    } 

    if($Cpo[operador4]=='+'){
        $Operador4 = '+';
    }elseif($Cpo[operador4]=='-'){
        $Operador4 = '-';
    }elseif($Cpo[operador4]=='*'){
        $Operador4 = '*';
    }elseif($Cpo[operador4]=='/'){
        $Operador4 = '/';
    }elseif($Cpo[operador4]==''){
        $Operador4 = 'N/A';
    }  
}

$CpoB = mysql_query("SELECT descripcion,bloqele FROM est WHERE (estudio= '$busca')");
$Cpob = mysql_fetch_array($CpoB);

if ($Cpob[bloqele] == 'Si') {
    $bloqueado='disabled';
}else{
    $bloqueado='';
}

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Elementos de Estudios</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
 //       encabezados();
  //      menu($Gmenu,$Gusr);

//Tabla contenedor de brighs
        echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr >
                <td colspan="3" bgcolor='#2c8e3c' width='90%' class='Subt' align='center'>
                    ...::: Detalle de estudios (<?= $busca ?>) <?= $Cpob[descripcion] ?> :::...
                </td>
            </tr>
            <tr>
            <td valign='top' width='88%' align='center'>
                <table width='100%' align='center' border='0' cellpadding='1' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;border-bottom: 0px;'>
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo'align="center" colspan="2">
                            ..:: Detalle ::..
                        </td>
                    </tr>
                    <tr style="height: 5px">
                        <td colspan="3">
                            <form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>
                                <table border="0" width="100%" align="center" cellpadding="3" cellspacing="0" style='border-collapse: collapse; border: 1px solid #c0c0c0;'>
                                    <tr class="letrap">
                                        <?php
                                        if ($suc == 1) {
                                            ?>
                                            <td class="ssbm" bgcolor="#6580A2" height="30px" align="center">
                                                <a href="estudioselem.php?ele=1&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu"><font color='#FFF'><b>Elementos Standard</b></font></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td class="ssbm" height="30px" align="center">
                                                <a href="estudioselem.php?ele=1&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu">Elementos Standard</a>
                                            </td>
                                            <?php
                                        }
                                        if ($suc == 2) {
                                            ?>
                                            <td class="ssbm" bgcolor="#6580A2" align="center">
                                                <a href="estudioselem.php?ele=2&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu"><font color='#FFF'><b>Elementos Alternativos</b></font></a>
                                            </td>
                                        <?php } else {
                                            ?>
                                            <td class="ssbm" align="center">
                                                <a href="estudioselem.php?ele=2&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu">Elementos Alternativos</a>
                                            </td>
                                            <?php
                                        }
                                        if ($suc == 3) {
                                            ?>
                                            <td class="ssbm" bgcolor="#6580A2" align="center">
                                                <a href="estudioselem.php?ele=3&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu"><font color='#FFF'><b>Elementos Alternativos 2</b></font></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td class="ssbm" align="center">
                                                <a href="estudioselem.php?ele=3&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu">Elementos Alternativos 2</a>
                                            </td>
                                            <?php
                                        }
                                        if ($suc == 4) {
                                            ?>
                                            <td class="ssbm" bgcolor="#6580A2" align="center">
                                                <a href="estudioselem.php?ele=4&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu"><font color='#FFF'><b>Elementos Alternativos 3</b></font></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td class="ssbm" align="center">
                                                <a href="estudioselem.php?ele=4&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu">Elementos Alternativos 3</a>
                                            </td>
                                            <?php
                                        }
                                        if ($suc == 5) {
                                            ?>
                                            <td class="ssbm" bgcolor="#6580A2" align="center">
                                                <a href="estudioselem.php?ele=5&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu"><font color='#FFF'><b>Elementos Alternativos 4</b></font></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td class="ssbm" align="center">
                                                <a href="estudioselem.php?ele=5&busca=<?= $_REQUEST[busca] ?>&opm=elementos" class="sbmnu">Elementos Alternativos 4</a>
                                            </td>
                                            <?php
                                        }
                                        ?>                                    
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3">
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Id :</b> 
                                        <input style="width: 60px" class='letrap' type="text" value='<?= $Cpo[id] ?>' name='Id' required <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Descripción :</b> 
                                        <input style="width: 250px" class='letrap' type="text" value='<?= $Cpo[descripcion] ?>' name='Descripcion' required <?= $bloqueado ?>></input>
                                    </td>

                                    <?php
                                    if ($Cpo[tipo] == 'c') {
                                        $Disp = '1.- Caracter';
                                    } elseif ($Cpo[tipo] == 'n') {
                                        $Disp = '2.- Numerico';
                                    } elseif ($Cpo[tipo] == 'd') {
                                        $Disp = '3.- Fecha';
                                    } elseif ($Cpo[tipo] == 'l') {
                                        $Disp = '4.- Logico[Positivo/Negativo]';
                                    } elseif ($Cpo[tipo] == 't') {
                                        $Disp = '5.- Texto';
                                    } elseif ($Cpo[tipo] == 'v') {
                                        $Disp = '6.- Espacio *';
                                    } elseif ($Cpo[tipo] == 'z') {
                                        $Disp = '7.- Columnas *';
                                    } elseif ($Cpo[tipo] == 'e') {
                                        $Disp = '8.- Encabezado *';
                                    } elseif ($Cpo[tipo] == 's') {
                                        $Disp = '9.- Seccion *';
                                    }
                                    ?>

                                    <td class="ssbm" align="center">
                                        <b>Tipo : </b>
                                        <select style="width: 160px" name='Tipo' <?= $bloqueado ?>>";
                                            <option value='c'>1.- Caracter</option>
                                            <option value='n'>2.- Numerico</option>
                                            <option value='d'>3.- Fecha</option>
                                            <option value='l'>4.- Logico[Positivo/Negativo]</option>
                                            <option value='t'>5.- Texto</option>
                                            <option value='v'>6.- Espacio *</option>
                                            <option value='z'>7.- Columnas *</option>
                                            <option value='e'>8.- Encabezado *</option>
                                            <option value='s'>9.- Seccion *</option>
                                            <option selected value='<?= $Cpo[tipo] ?>'><?= $Disp ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Unidad :</b> 
                                        <input style="width: 150px" class='letrap' type="text" value='<?= $Cpo[unidad] ?>' name='Unidad' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Longitud : </b>
                                        <input style="width: 50px" class='letrap' type="text" value='<?= $Cpo[longitud] ?>' name='Longitud' <?= $bloqueado ?>></input>
                                    </td>                                
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Decimales : </b>
                                        <input style="width: 50px" class='letrap' type="text" value='<?= $Cpo[decimales] ?>' name='Decimales'<?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Min : </b> 
                                        <input style="width: 50px" class='letrap' type="text" value='<?= $Cpo[min] ?>' name='Min' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Max : </b>
                                        <input style="width: 50px" class='letrap' type="text" value='<?= $Cpo[max] ?>' name='Max' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>V.Ref : </b> 
                                        <select style="width: 70px;" name="Valref" <?= $bloqueado ?>>
                                            <option value="Si">1.- Si</option>
                                            <option value="No">2.- No</option>
                                            <option selected value='<?= $Cpo[valref] ?>'> <?= $Valref ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Nota : </b>   
                                        <textarea name="Nota" type="text" rows="2" cols="43" <?= $bloqueado ?>><?= $Cpo[nota] ?></textarea>
                                    </td>                                
                                </tr>
                            </table>          
                        </td>
                    </tr>
                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Ref. Logica:</b>
                                        <select name='Vlogico' <?= $bloqueado ?>>
                                            <option value='Positivo'>1.- Positivo</option>
                                            <option value='Negativo'>2.- Negativo</option>
                                            <option selected value='<?= $Cpo[vlogico] ?>'><?= $Vlogico ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Ref. Caracter:</b>
                                        <input type="text" value="<?= $Cpo[vtexto] ?>" name="Vtexto" <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Alineac: (Encab/Secc)</b>
                                        <select name='Alineacion' <?= $bloqueado ?>>
                                            <option value='right'>1.- Derecha</option>
                                            <option value='left'>2.- Izquierda</option>
                                            <option value='center'>3.- Centro</option>
                                            <option selected value='<?= $Cpo[alineacion] ?>'> <?= $Alineacion ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Condiciona:</b>  
                                        <input type="text" value="<?= $Cpo[condiciona] ?>" name="Condiciona" <?= $bloqueado ?>></input>
                                    </td>                              
                                </tr>
                            </table>          
                        </td>
                    </tr>
                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Celda Izquierda:</b>
                                        <textarea name="Celda1" type="text" rows="3" cols="55" <?= $bloqueado ?>><?= $Cpo[celda1] ?></textarea>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Celda Centro:</b>
                                        <textarea name="Celda2" type="text" rows="3" cols="55" <?= $bloqueado ?>><?= $Cpo[celda2] ?></textarea>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>
                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <td class="ssbm" height="30px" align="center" width="50%">
                                        <b>Celda Derecha:</b>
                                        <textarea name="Celda3" type="text" rows="3" cols="55" <?= $bloqueado ?>><?= $Cpo[celda3] ?></textarea>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Aplicar tabla?</b>
                                        <select name='Celdas' <?= $bloqueado ?>>
                                            <option value='Si'>1.- Si</option>
                                            <option value='No'>2.- No</option>
                                            <option selected value='<?= $Cpo[celdas] ?>'> <?= $Celdas ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>
                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <?php
                                    if ($Cpo[idvalor1] == 'ID') {
                                        $Idvalor1 = '1.- ID';
                                    } elseif ($Cpo[idvalor1] == 'VALOR') {
                                        $Idvalor1 = '2.- VALOR';
                                    } elseif ($Cpo[idvalor1] == '') {
                                        $Idvalor1 = '3.- N/A';
                                    }

                                    if ($Cpo[parentesis1] == '(') {
                                        $Parentesis1 = '(';
                                    } elseif ($Cpo[parentesis1] == '') {
                                        $Parentesis1 = 'N/A';
                                    }
                                    ?>
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Id / Valor(1):</b>
                                        <select name='Idvalor1' <?= $bloqueado ?>>
                                            <option value='ID'>1.- ID</option>
                                            <option value='VALOR'>2.- VALOR</option>
                                            <option value=''>3.- N/A</option>
                                            <option selected value='<?= $Cpo[idvalor1] ?>'><?= $Idvalor1 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>( )</b>
                                        <select name='Parentesis1' <?= $bloqueado ?>>
                                            <option value='('> ( </option>
                                            <option value=''> N/A </option>
                                            <option selected value='<?= $Cpo[parentesis1] ?>'><?= $Parentesis1 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Id o Valor(1)</b> 
                                        <input type="text" name='Valor1' value='<?= $Cpo[valor1] ?>' size='8' maxlength='8' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Operacion(1)</b>
                                        <select name='Operador1' <?= $bloqueado ?>>
                                            <option value='+'> + </option>
                                            <option value='-'> - </option>
                                            <option value='*'>*</option>
                                            <option value='/'>/</option>
                                            <option value=''>N/A</option>
                                            <option selected value='<?= $Cpo[operador1] ?>'><?= $Operador1 ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>

                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <?php
                                    if ($Cpo[idvalor2] == 'ID') {
                                        $Idvalor2 = '1.- ID';
                                    } elseif ($Cpo[idvalor2] == 'VALOR') {
                                        $Idvalor2 = '2.- VALOR';
                                    } elseif ($Cpo[idvalor2] == '') {
                                        $Idvalor2 = '3.- N/A';
                                    }

                                    if ($Cpo[parentesis2] == '(') {
                                        $Parentesis2 = '(';
                                    } elseif ($Cpo[parentesis2] == ')') {
                                        $Parentesis2 = ')';
                                    } elseif ($Cpo[parentesis2] == '') {
                                        $Parentesis2 = 'N/A';
                                    }
                                    ?>
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Id / Valor(2):</b>
                                        <select name='Idvalor2' <?= $bloqueado ?>>
                                            <option value='ID'>1.- ID</option>
                                            <option value='VALOR'>2.- VALOR</option>
                                            <option value=''>3.- N/A</option>
                                            <option selected value='<?= $Cpo[idvalor2] ?>'><?= $Idvalor2 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Id o Valor(2)</b> 
                                        <input type="text" name='Valor2' value='<?= $Cpo[valor2] ?>' size='8' maxlength='8' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>( )</b>
                                        <select name='Parentesis2' <?= $bloqueado ?>>
                                            <option value='('> ( </option>
                                            <option value=')'> ) </option>
                                            <option value=''> N/A </option>
                                            <option selected value='<?= $Cpo[parentesis2] ?>'><?= $Parentesis2 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Operacion(2)</b>
                                        <select name='Operador2' <?= $bloqueado ?>>
                                            <option value='+'> + </option>
                                            <option value='-'> - </option>
                                            <option value='*'>*</option>
                                            <option value='/'>/</option>
                                            <option value=''>N/A</option>
                                            <option selected value='<?= $Cpo[operador2] ?>'><?= $Operador2 ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>

                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <?php
                                    if ($Cpo[idvalor3] == 'ID') {
                                        $Idvalor3 = '1.- ID';
                                    } elseif ($Cpo[idvalor3] == 'VALOR') {
                                        $Idvalor3 = '2.- VALOR';
                                    } elseif ($Cpo[idvalor3] == '') {
                                        $Idvalor3 = '3.- N/A';
                                    }

                                    if ($Cpo[parentesis3] == '(') {
                                        $Parentesis3 = '(';
                                    } elseif ($Cpo[parentesis3] == ')') {
                                        $Parentesis3 = ')';
                                    } elseif ($Cpo[parentesis3] == '') {
                                        $Parentesis3 = 'N/A';
                                    }
                                    ?>
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Id / Valor(3):</b>
                                        <select name='Idvalor3' <?= $bloqueado ?>>
                                            <option value='ID'>1.- ID</option>
                                            <option value='VALOR'>2.- VALOR</option>
                                            <option value=''>3.- N/A</option>
                                            <option selected value='<?= $Cpo[idvalor3] ?>'><?= $Idvalor3 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Id o Valor(3)</b> 
                                        <input type="text" name='Valor3' value='<?= $Cpo[valor3] ?>' size='8' maxlength='8' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>( )</b>
                                        <select name='Parentesis3' <?= $bloqueado ?>>
                                            <option value='('> ( </option>
                                            <option value=')'> ) </option>
                                            <option value=''> N/A </option>
                                            <option selected value='<?= $Cpo[parentesis3] ?>'><?= $Parentesis3 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Operacion(3)</b>
                                        <select name='Operador3' <?= $bloqueado ?>>
                                            <option value='+'> + </option>
                                            <option value='-'> - </option>
                                            <option value='*'>*</option>
                                            <option value='/'>/</option>
                                            <option value=''>N/A</option>
                                            <option selected value='<?= $Cpo[operador3] ?>'><?= $Operador3 ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>

                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <?php
                                    if ($Cpo[idvalor4] == 'ID') {
                                        $Idvalor4 = '1.- ID';
                                    } elseif ($Cpo[idvalor4] == 'VALOR') {
                                        $Idvalor4 = '2.- VALOR';
                                    } elseif ($Cpo[idvalor4] == '') {
                                        $Idvalor4 = '3.- N/A';
                                    }

                                    if ($Cpo[parentesis4] == '(') {
                                        $Parentesis4 = '(';
                                    } elseif ($Cpo[parentesis4] == ')') {
                                        $Parentesis4 = ')';
                                    } elseif ($Cpo[parentesis4] == '') {
                                        $Parentesis4 = 'N/A';
                                    }
                                    ?>
                                    <td class="ssbm" height="30px" align="center">
                                        <b>Id / Valor(4):</b>
                                        <select name='Idvalor4' <?= $bloqueado ?>>
                                            <option value='ID'>1.- ID</option>
                                            <option value='VALOR'>2.- VALOR</option>
                                            <option value=''>3.- N/A</option>
                                            <option selected value='<?= $Cpo[idvalor4] ?>'><?= $Idvalor4 ?></option>
                                        </select>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>Id o Valor(4)</b> 
                                        <input type="text" name='Valor4' value='<?= $Cpo[valor4] ?>' size='8' maxlength='8' <?= $bloqueado ?>></input>
                                    </td>

                                    <td class="ssbm" align="center">
                                        <b>( )</b>
                                        <select name='Parentesis4' <?= $bloqueado ?>>
                                            <option value=')'> ) </option>
                                            <option value=''> N/A </option>
                                            <option selected value='<?= $Cpo[parentesis4] ?>'><?= $Parentesis4 ?></option>
                                        </select>
                                    </td>

                                    <?php
                                    if ($Cpo[calculo] == 'Si') {
                                        $Calculo = '1.- Si';
                                    } elseif ($Cpo[calculo] == 'No') {
                                        $Calculo = '2.- No';
                                    }
                                    ?>

                                    <td class="ssbm" align="center">
                                        <b>Calculo?</b>
                                        <select name='Calculo' <?= $bloqueado ?>>
                                            <option value='Si'>1.- Si</option>
                                            <option value='No'>2.- No</option>
                                            <option selected value='<?= $Cpo[calculo] ?>'><?= $Calculo ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>

                    <tr style="height: 5px" valign="middle">
                        <td align="left" class='letrap' colspan="3"><br>
                            <table border="0" width="100%" align="center">
                                <tr class="letrap">
                                    <td height="40px" colspan="2" align="center" class="letrap">
                                    <?php  
                                    if ($Cpob[bloqele] == 'Si') {
                                    ?>
                                        <a class="edit" href="estudioselem.php?Op=ab&busca=<?= $busca ?>&ele=<?= $suc ?>">
                                            Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                        </a>
                                    <?php
                                    }else{
                                    ?>
                                        <a class="edit" href="estudioselem.php?Op=cr&busca=<?= $busca ?>&ele=<?= $suc ?>">
                                            Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                    </td>
                                    <td height="40px" colspan="2" align="center" colspan="2" class="letrap">
                                        <?php  
                                        if ($Cpob[bloqele] == 'No') {
                                        ?>
                                            <?php
                                                if($_REQUEST[cEd]<>''){
                                            ?>
                                                <input type='hidden' name='Up' value="<?= $_REQUEST[cEd]?>"></input>
                                                <input class="letrap" type="submit" name='Boton' value='Actualizar'></input>
                                            <?php
                                                }else{
                                            ?>
                                                <input class="letrap" type="submit" name='Boton' value='Agregar'></input>
                                            <?php
                                                }
                                            ?>
                                            <input class="letrap" type="hidden" name="busca" value="<?= $busca ?>"></input>
                                            <input class="letrap" type="hidden" name="ele" value="<?= $suc ?>"></input>

                                        <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                            </table>          
                        </td>
                    </tr>
                </table>
            </td>
            <td valign='top' width="22%">
                <?php
                $sbmn='Elementos';
                Sbmenu();
                ?>
            </td>
            </td>
            </tr>    
        </table> 
        <br>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr >
                <td width='50%' class='Subt' align='center' valign='top'>
                <form name="form1" method="get" action="<?= $_SERVER['PHP_SELF'] ?>">
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo'align="center" colspan="2">
                            ..:: Datos Registrados ::..
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table align="center" cellpadding="3" cellspacing="2" width="97%" border='0'>
                                <tr><td height="5px"></td></tr>
                                <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                    <td height="22px">
                                        <b>Edt</b>
                                    </td>
                                    <td>
                                        <b>Id</b>
                                    </td>
                                    <td>
                                        <b>Descripcion</b>
                                    </td>
                                    <td>
                                        <b>Tipo</b>
                                    </td>
                                    <td>
                                        <b>Unidad</b>
                                    </td>
                                    <td>
                                        <b>Long</b>
                                    </td>
                                    <td>
                                        <b>Elim</b>
                                    </td>
                                    <td>
                                        <b>Estad</b>
                                    </td>
                                </tr>
                                <?php
                                $sqlA= mysql_query("SELECT * FROM $Tablapdf WHERE estudio = '$busca' order by id");
                                while ($registro = mysql_fetch_array($sqlA)) {
                                    if ($registro[tipo] == "c") {
                                        $cTt = "Caracter";
                                        $Bloqestad='';
                                    } elseif ($registro[tipo] == "d") {
                                        $cTt = "Fecha";
                                        $Bloqestad='disabled';
                                    } elseif ($registro[tipo] == "n") {
                                        $cTt = "Numerico";
                                        $Bloqestad='';
                                    } elseif ($registro[tipo] == "t") {
                                        $cTt = "Texto";
                                        $Bloqestad='disabled';
                                    } elseif ($registro[tipo] == "l") {
                                        $cTt = "Logico";
                                        $Bloqestad='';
                                    } elseif ($registro[tipo] == "v") {
                                        $cTt = "Espacio *";
                                        $Bloqestad='disabled';
                                    } elseif ($registro[tipo] == "z") {
                                        $cTt = "Columnas *";
                                        $Bloqestad='disabled';
                                    } elseif ($registro[tipo] == "e") {
                                        $cTt = "Encabezado *";
                                        $Bloqestad='disabled';
                                    } else {
                                        $cTt = "Seccion *";
                                        $Bloqestad='disabled';
                                    }

                                    if (($nRng % 2) > 0) {
                                        $Fdo = 'FFFFFF';
                                    } else {
                                        $Fdo = 'DDE8FF';
                                    }    //El resto de la division;

                                    echo "<tr class='letrap' align='center' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                            
                                    if ($Cpob[bloqele] == 'No') {

                                        echo "<td class='Seleccionar' align='center'><a class='edit' href='estudioselem.php?Id=$registro[id]&busca=$busca&ele=$suc&cEd=$registro[idnvo]'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";

                                    }else{

                                        echo "<td class='Seleccionar' align='center'> - </td>";

                                    }

                                    echo "<td align='center'>$Gfont $registro[id]</font></td>";
                                    echo "<td>$Gfont $registro[descripcion]</font></td>";
                                    echo "<td>$Gfont $cTt </font></td>";
                                    echo "<td>$Gfont $registro[unidad] $Gfon</td>";
                                    echo "<td>$Gfont $registro[longitud] $Gfon</td>";
                                    echo "<td>";

                                    if ($Cpob[bloqele] == 'No') {

                                        echo "<a href='estudioselem.php?ele=$suc&idnvo=$registro[idnvo]&id=$registro[id]&busca=$busca&Op=Dt'><i class='fa fa-trash-o fa-2x' aria-hidden='true'></i> </a>";

                                    }else{

                                        echo " - ";

                                    }
                                    echo "</td>";

                                    if ($Cpob[bloqele] == 'Si') {

                                        $Bloqestad='disabled';

                                    }

                                    if ($registro[estadistica] == 'Si') {

                                        $selecc='checked';

                                    }else{

                                        $selecc='';

                                    }

                                    echo "<td>$Gfont <input type='radio' name='id' value='$registro[id]' $Bloqestad $selecc></td>";

                                    echo "</tr>";
                                    $nRng++;
                                }//fin while
                                ?>
                            </table>

                        </td>
                    </tr>

                </table>
                <br>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 0px solid #999;'>
                <tr align="right"><td>
                    <?php
                    if ($Cpob[bloqele] == 'No') {
                    ?>
                        <input class="letrap" type="hidden" name="busca" value="<?= $busca ?>"></input>
                        <input class="letrap" type="hidden" name="ele" value="<?= $suc ?>"></input>
                        <input class="letrap" type="submit" name='Boton' value='Reg_Dato_Estad' $Bloqestad></input>
                    <?php
                    }
                    ?>
                </form>
                </td></tr>
                </table>
                
                </td>
                <td width='50%' class='Subt' align='center' valign='top'>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo'align="center" colspan="2">
                            ..:: PDF ::..
                        </td>
                    </tr>
                    <tr class="letrap">
                        <td align="center">
                        <embed src='resultapdf.php?Estudio=<?= $busca  ?>&alterno=<?= $ele ?>' type='application/pdf' width='100%' height='710px' />
                        </td>
                    </tr>
                    <tr class="letrap">
                        <td align="center">
                        </td>
                    </tr>
                    <tr class="letrap">
                    <td valign='top' width='45%'>
                        <?php
                        TablaDeLogs("/Estudios/Elementos/".$elesuc."/", $busca);
                        ?>
                    </td>
                    </tr>
                </table>
                </td>
            </tr>

        </table>  
    </body>
</html>
<?php
mysql_close();
?>
