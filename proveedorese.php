<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");
#Variables comunes;
$msj = $_REQUEST[msj];
$Titulo = "Proveedores";
if($_REQUEST[Boton] == Cancelar){

    header("Location: proveedores.php");
}elseif($_REQUEST[bt] == "NUEVO") {
                
    $cSql =  "INSERT INTO prv (nombre,direccion,colonia,ciudad,telefono,rfc,codigo,nota,dias,alias,usralta,fechalta,respprv,mail,status,depto)
          VALUES
          ('$_REQUEST[Nombre]','$_REQUEST[Direccion]','$_REQUEST[Colonia]','$_REQUEST[Ciudad]','$_REQUEST[Telefono]','$_REQUEST[Rfc]','$_REQUEST[Codigo]','$_REQUEST[Nota]','$_REQUEST[Dias]','$_REQUEST[Alias]','$Gusr','$Fecha','$_REQUEST[respprv]','$_REQUEST[mail]','$_REQUEST[status]','$_REQUEST[depto]')";    

    $cId = mysql_insert_id();

    $cProceso = "Agrega prv $cId ";

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'PRV';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    $msj = "¡Registro ingresado con exito!";

    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Proveedores/Alta de proveedor'
            ,'prv','$Fecha',$busca)";
            
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }          
    header("Location: proveedores.php?busca=$cId");

}elseif ($_REQUEST[bt] == 'Actualizar') {
            
    $cSql =  "UPDATE prv SET 
               nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',colonia='$_REQUEST[Colonia]',ciudad='$_REQUEST[Ciudad]',telefono='$_REQUEST[Telefono]',rfc='$_REQUEST[Rfc]',codigo='$_REQUEST[Codigo]',nota='$_REQUEST[Nota]',dias='$_REQUEST[Dias]',alias='$_REQUEST[Alias]',usrmod='$Usr',fechamod='$Fecha',respprv='$_REQUEST[respprv]',mail='$_REQUEST[mail]',status='$_REQUEST[status]',depto='$_REQUEST[depto]'
            WHERE id ='$busca'";
           
    $cProceso = "Actualizo Informacion de Proveedor ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'PRV';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    $msj = "Cambio ejecutado con exito";

    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Proveedores/Info. Personal/Actualiza Detalle del proveedor'
            ,'prv','$Fecha',$busca)";

    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }

}elseif($_REQUEST[cId] <> '' ){
    
    $cSql =  "DELETE FROM prv WHERE id = '$_REQUEST[cId]'";    

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'CLICALE';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
      
}

$Msj = $_REQUEST[Msj];
$CpoA = mysql_query("SELECT * FROM prv WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Proveedores - Info. Confidencial</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>

    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Informacion Principal de <?= $Cpo[id] . ' - ' . ucwords(strtolower($Cpo[nombre])) ?> ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='45%'>
                        <table width='98%' align='center' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle del Proveedor ::..
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Proveedor : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombre' size='50' value='<?= $Cpo[nombre] ?>' MAXLENGTH='50'> &nbsp; Id : <?= $Cpo[id] ?>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Alias : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Alias' size='30' value='<?= $Cpo[alias] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Direccion : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Direccion' size='30' value='<?= $Cpo[direccion] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Colonia : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Colonia' size='30' value='<?= $Cpo[colonia] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Cod.Postal : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Codigo' size='10' value='<?= $Cpo[codigo] ?>' MAXLENGTH='10'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Ciudad : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Ciudad' size='30' value='<?= $Cpo[ciudad] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    R.f.c : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Rfc' size='30' value='<?= $Cpo[rfc] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Responsable Proveedor : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='respprv' size='30' value='<?= $Cpo[respprv] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Email : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='mail' size='50' value='<?= $Cpo[mail] ?>' MAXLENGTH='50'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Telefonos : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Telefono' size='30' value='<?= $Cpo[telefono] ?>' MAXLENGTH='30'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Dias Credito : &nbsp;
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Dias' size='10' value='<?= $Cpo[dias] ?>' MAXLENGTH='10'>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Departamento : &nbsp;
                                </td>
                                <td class="Inpt"><select name='depto'>
                                    <option value='Insumos'>Insumos</option>
                                    <option value='Reactivo'>Reactivo</option>
                                    <option value='Papeleria'>Papeleria</option>
                                    <option value='Computacion'>Computacion</option>
                                    <option selected value='<?= $Cpo[depto] ?>'><?= $Cpo[depto]?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr style="height: 30px">
                                <td width='40%' align="right" class="Inpt">
                                    Status : &nbsp;
                                </td>
                                <td class="Inpt"><select name='status'>
                                    <option value='Activo'>Activo</option>
                                    <option value='Inactivo'>Inactivo</option>
                                    <option selected value='<?= $Cpo[status] ?>'><?= $Cpo[status]?></option>
                                    </select>
                                </td>
                            </tr>




                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <a href="proveedores.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a> &nbsp; &nbsp; &nbsp; &nbsp;
   
                                    <?php
                                    if ($busca == "NUEVO") {
                                        ?>
                                        <input class="letrap" type="submit" value='NUEVO' name='bt'></input> &nbsp; &nbsp; &nbsp; &nbsp;
                                        <?php
                                    } else {
                                        ?>
                                        <input class="letrap" type="submit" value='Actualizar' name='bt'></input> &nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input> &nbsp; &nbsp; &nbsp; &nbsp;
                                        <?php
                                    }


                                    $ImgA = mysql_query("SELECT archivo FROM doctosprv WHERE id='$busca' and usrelim=''");

                                    $Img = mysql_fetch_array($ImgA);
                                    
                                    if ($Img[archivo] <> '') {
                                        echo "<a class='edit' href=javascript:winuni('displaydoctosprv.php?op=1&busca=$busca')><i class='fa fa-search fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a>";
                                    } else {
                                        echo "<a class='edit' href=javascript:winuni('displaydoctosprv.php?op=1&busca=$busca')><i class='fa fa-upload fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a>";
                                    }

                                    ?>
                                </td>
                            </tr>
                        </table>

                        <table width='98%' align='center' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <tr>
                            <td valign='top' width="22%">
                                <a class="cMsj">
                                    <?= $msj ?>
                                </a>
                            </td>
                        </tr>
                        </table>  

                        <table width='98%' align='center' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Documentos Cargados ::..
                                </td>
                            </tr>
                            <?php
                            $ImgA = mysql_query("SELECT archivo,idnvo,usr,fechasub FROM doctosprv WHERE id='$busca' and usrelim=''");
                            while ($row = mysql_fetch_array($ImgA)) {
                                $Pos = strrpos($row[archivo], ".");
                                $cExt = strtoupper(substr($row[archivo], $Pos + 1, 4));
                                $foto = $row[archivo];
                                $archivo=base64_encode($row[archivo]);
                                if ($cExt == 'PDF') {
                                    //echo "<td align='center'><embed src='http://lcd-system.com/lcd-net/doctosprv/$foto' type='application/pdf' width='25%' /></td>";
                                    echo "<tr><td align='center'><a href=javascript:wingral('displaydoctosprvdet.php?busca=$busca&archivo=$archivo')><i class='fa fa-file-pdf-o fa-3x' aria-hidden='true' style='color:#FF0000' title='Vista preliminar'></i></a></td><td align='left' class='Inpt'> &nbsp; " . ucfirst(strtolower($row[archivo])) . "  &nbsp; &nbsp; Usuario:  ".ucfirst(strtolower($row[usr]))." &nbsp; &nbsp; Fecha/Hora: ".$row[fechasub]."</font></td></tr>";

                                } elseif ($cExt == 'DOCX') {
                                    echo "<tr><td align='center'><a href=javascript:wingral('displaydoctosprvdet.php?busca=$busca&archivo=$archivo')><i class='fa fa-file-word-o fa-3x' aria-hidden='true' style='color:#FF0000' title='Vista preliminar'></i></a></td><td align='left' class='Inpt'> &nbsp; " . ucfirst(strtolower($row[archivo])) . "  &nbsp; &nbsp; Usuario:  ".ucfirst(strtolower($row[usr]))." &nbsp; &nbsp; Fecha/Hora: ".$row[fechasub]."</font></td></tr>";
                                } elseif ($cExt == 'XLSX') {
                                    echo "<tr><td align='center'><a href=javascript:wingral('displaydoctosprvdet.php?busca=$busca&archivo=$archivo')><i class='fa fa-file-excel-o fa-3x' aria-hidden='true' style='color:#FF0000' title='Vista preliminar'></i></a></td><td align='left' class='Inpt'> &nbsp; " . ucfirst(strtolower($row[archivo])) . "  &nbsp; &nbsp; Usuario:  ".ucfirst(strtolower($row[usr]))." &nbsp; &nbsp; Fecha/Hora: ".$row[fechasub]."</font></td></tr>";
                                } else {

                                    echo "<tr><td align='center'><a href=javascript:wingral('displaydoctosprvdet.php?busca=$busca&archivo=$archivo')><i class='fa fa-file-image-o fa-3x' aria-hidden='true' style='color:#FF0000' title='Vista preliminar'></i></a></td><td align='left' class='Inpt'> &nbsp; " . ucfirst(strtolower($row[archivo])) . "  &nbsp; &nbsp; Usuario:  ".ucfirst(strtolower($row[usr]))." &nbsp; &nbsp; Fecha/Hora: ".$row[fechasub]."</font></td></tr>";
                                }

                            }
                            ?>
                        </table>
                    </td>
                    <td valign='top' width='45%'>
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Observaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td class='Inpt' align='right'>
                                Observaciones : 
                            </td>
                            <td> 
                                <textarea class="cinput" name="Nota" type="text" rows="3" cols="45"><?= $Cpo[nota] ?></textarea>
                            </td>
                        </tr>
                        </table>
                    </form>

                    <table><tr><td>&nbsp;</td></tr></table>

                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Modificaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table align="center" width="95%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                    <tr class="letrap">
                                        <td>
                                            <b>&nbsp; Id</b>
                                        </td>
                                        <td>
                                            <b>&nbsp; Fecha</b>
                                        </td>
                                        <td>
                                            <b>&nbsp; Usuario</b>
                                        </td>
                                        <td>
                                            <b>&nbsp; Accion</b>
                                        </td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Catalogos/Proveedores%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 6;";
                                    //echo $sql;
                                    $PgsA = mysql_query($sql);
                                    while ($rg = mysql_fetch_array($PgsA)) {
                                        if (($nRng % 2) > 0) {
                                            $Fdo = 'FFFFFF';
                                        } else {
                                            $Fdo = $Gfdogrid;
                                        }
                                        ?>
                                        <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                            <td>
                                                <b>&nbsp;<?= $rg[id] ?></b>
                                            </td>
                                            <td align="center">
                                                &nbsp;<?= $rg[fecha] ?>
                                            </td>
                                            <td>
                                                <?= $rg[usr] ?>
                                            </td>
                                            <td>
                                                <?= $rg[accion] ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $nRng++;
                                    }
                                    ?>
                                </table>

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

