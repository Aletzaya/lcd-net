<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr    = $check['uname'];
  $link   = conectarse();
  
  $busca  = $_REQUEST[Id];

  $Tabla  = "dpag_ref";
  $Titulo = "Detalle de pagos";
  $Fecha  = date('Y-m-d H:m:s'); 
  $error="Sin error";

require ("config.php");

 $sql = "SELECT dpag_ref.id,dpag_ref.concept,dpag_ref.autoriza,dpag_ref.hospi,cptpagod.referencia,cptpagod.id idref,"
        . "cpagos.id idpagos,cpagos.clave,cpagos.concepto,dpag_ref.fecha,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.orden_h,"
        . "dpag_ref.tipopago,dpag_ref.cerrada,dpag_ref.fechapago,dpag_ref.recibe,dpag_ref.id_ref FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE dpag_ref.id='$busca' ORDER BY id";

        ?>
        <html>
        
        <head>
        <meta charset="UTF-8">
        <title><?php echo $Titulo;?></title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
                <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        </head>

<body onLoad="cFocus1()">

<?php 
//echo $sql;
$Cpoa=  mysql_query($sql);
$Cpo = mysql_fetch_array($Cpoa);


?>

<table width="80%" border="0" align='center'>
    <?php
    echo "<tr>";
        cInput("Id: ", "Text", "10", "busca", "right", $busca, "5", 1, 1, '');

        if ($Cpo[fechapago] <> "") {
            cInput("Fecha del pago: ", "Text", "14", "Fechapago", "right", $Cpo[fechapago], "10", 1, 1, '');
        } elseif ($error == "error") {
            cInput("Fecha del pago: ", "Text", "14", "Fechapago", "right", $_REQUEST[Fechapago], "10", 1, 1, '');
        } else {
            cInput("Fecha del pago: ", "Text", "14", "Fechapago", "right", date('Y-m-d'), "10", 1, 1, '');
        }

        cInput("Tipo de pago: ", "Text", "50", "Referencia", "right", $Cpo[referencia], "10", 1, 1, '');

        cInput("Referencia a laboratorio: ", "Text", "50", "Hospi", "right", $Cpo[hospi], "10", 1, 1, '');


        if ($Cpo[orden_h] <> "") {
            cInput("No. de orden del paciente: ", "Text", "5", "Orden_h", "right", $Cpo[orden_h], "7", 1, 1, '');
        } elseif($error == "error") {
            cInput("No. de orden del paciente: ", "Text", "5", "Orden_h", "right", $_REQUEST[Orden_h], "7", 1, 1, '');
            
        }else{
            cInput("No. de orden del paciente: ", "Text", "5", "Orden_h", "right","", "7", 1, 1, '');
        }

        if ($Cpo[concept] <> "") {
            cInput("Concepto de laboratorio : ", "Text", "40", "Concept", "right", $Cpo[concept], "60", 1, 1, '');
        } elseif($error == "error") {
            cInput("Concepto de laboratorio : ", "Text", "40", "Concept", "right", $_REQUEST[Concept], "60", 1, 1, '');
        }else{
            cInput("Concepto de laboratorio : ", "Text", "40", "Concept", "right", "", "60", 1, 1, '');
        }
        
        if ($error != "error") {
            cInput("Quien recibe: ", "Text", "35", "Recibe", "right", $Cpo[recibe], "60", 1, 1, '');
        } else {
            cInput("Quien recibe: ", "Text", "35", "Recibe", "right", $_REQUEST[Recibe], "60", 1, 1, '');
        }

        if ($Cpo[autoriza] <> "") {
            cInput("Quien autoriza : ", "Text", "35", "Autoriza", "right", $Cpo[autoriza], "60", 1, 1, '');
        }elseif($error == "error") {
            cInput("Quien autoriza : ", "Text", "40", "Autoriza", "right", $_REQUEST[Autoriza], "60", 1, 1, '');
        }else{
            cInput("Quien autoriza : ", "Text", "35", "Autoriza", "right","", "60", 1, 1, '');
        }

        cInput("Forma de pago : ", "Text", "35", "Tipopago", "right",$Cpo[concepto] ."|".  $Cpo[clave], "60", 1, 1, '');

        //echo $cSql;
        if ($Cpo[monto] <> "") {
            cInput("Importe : $", "Text", "4", "Monto", "right", $Cpo[monto], "40", 1, 1, '');
        } elseif($error == "error") {
            cInput("Importe : $", "Text", "4", "Monto", "right", $_REQUEST[Monto], "40", 1, 1, '');
        }else{
            cInput("Importe : $", "Text", "4", "Monto", "right", "", "40", 1, 1, '');
        }

        cInput("Observaciones :", "Text", "4", "Observaciones", "right", $Cpo[observaciones], "100", 1, 1, '');


        if ($error == "error") {
            echo"<tr><td align='center' colspan='2'><p style='color:#FF0000';>FAVOR DE AGREGAR TODOS LOS CAMPOS FALTANTES</p></td></tr>";
        }
        
        if ($Cpo[cerrada] == "0") {
            echo"<tr><td align='center' colspan='2'><p style='color:#FF0000';>*** ORDEN SIN CERRAR, NO SE RECIBIRA ***</p></td></tr>";
        }
    
    echo "</div></td></tr>";

    echo "</td></tr></table>";
    mysql_close();
    ?>
  </td>
  </tr>
</table>
</body>
</html>