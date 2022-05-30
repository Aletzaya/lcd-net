<?php
#Librerias

session_start();

$_SESSION["Usr"] = array("", "", "", "", "", "", "u938386532_root");

require("lib/lib.php");
$link = conectarse();
include_once ("authconfig.php");
require ("config.php");
require_once 'nusoap.php';
require_once 'class.phpmailer.php';

if ($_REQUEST["op"] == "correo") {
    $sql = "SELECT institucion,nombre,mail,telefono FROM inst WHERE mail='$_REQUEST[Clave]' and telefono='$_REQUEST[Telefono]' LIMIT 1;";
    $cSql = mysql_query($sql);
    if ($cc = mysql_fetch_array($cSql)) {
        $inst=$cc["institucion"];
        $mail=$cc["mail"];
        $nombre=$cc["nombre"];
        header("Location: olvidopass.php?Institucion=$inst&mail=$mail&nombre=$nombre");
    }else{
        $msj="No corresponden los datos registrados, Favor de verificarlos";
    }
}

if ($_REQUEST["Institucion"] <> "") {

    $Institucion = $_REQUEST[Institucion]; 

    $sql2 = "SELECT institucion,nombre,mail,telefono,password FROM inst WHERE institucion='$Institucion' LIMIT 1;";

    $cSql2 = mysql_query($sql2);

    if ($cc2 = mysql_fetch_array($cSql2)) {

        $Correo=$cc2["mail"];

        $nombre=$cc2["nombre"];

        $password=$cc2["password"];

    }

    $SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 2 ORDER BY id");

    $Smtp = mysql_fetch_array($SmtpA);


      try {
        //*******************Envio de correo;
        //      Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        //Tell PHPMailer to use SMTP
        $mail->IsSMTP();
        //Enable SMTP debugging
        //0 = off (for production use)
        //1 = client messages
        //2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'error_log';

        //Set the hostname of the mail server
        $mail->Host = $Smtp[smtpname];

        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = $Smtp[smtpport];    

        //Whether to use SMTP authentication
        //$mail->SMTPSecure = 'ssl';
        
        $mail->SMTPAuth = true;

        error_log("********************************************\n" . $Smtp[smtpuser] . "::" . $Smtp[smtploginpass] ."\n");
        //Username to use for SMTP authentication
        $mail->Username = $Smtp[smtpuser];

        //Password to use for SMTP authentication
        $mail->Password = $Smtp[smtploginpass];
        
        //Set the subject line
        $mail->Subject = "Recuperacion de contraseña - www.dulab.com.mx";

        $mail->Body = "<b>Sistema Automatico de recuperacion de contraseña, Datos almacenados en nuestro sistema: </b><br><br><br><b>Institucion</b> - $Institucion <br><br><b>Nombre</b> - $nombre <br><br><b>E-mail</b> - $Correo <br><br><b>Password</b> - $password <br><br><br><br> CONFIDENCIALIDAD: La información contenida en este correo electrónico, incluyendo cualquier archivo adjunto, es privilegiada y confidencial y para uso exclusivo de los destinatarios y/o de quienes hayan sido autorizados específicamente para leerla. Si ud ha recibido este correo por error, favor de destruirlo y notificarlo al remitente a la dirección electrónica indicada. Cualquier divulgación, distribución o reproducción de este comunicado está estrictamente prohibida y sujeta a las sanciones establecidas en las leyes correspondientes. 'Laboratorio Clínico Duran, S.A. de C.V.' no será responsable en ningún momento por los cambios que este comunicado sufra en su transferencia. <br>";
      

        //Replace the plain text body with one created manually
        $mail->AltBody = 'Recuperacion de contraseña';
        
         //Set who the message is to be sent from
        //$mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');
        //$mail->SetFrom('administracion@dulab.com.mx', 'Laboratorio Clinico Duran, envio de resultado');
        $mail->SetFrom('sistemas@dulab.com.mx', 'Laboratorio Clinico Duran, Recuperacion de contraseña');

        //Set who the message is to be sent to
        $mail->AddAddress($Correo, 'Cliente');

        if (!$mail->Send()) {

            $msj =  "Mailer Error: " . $mail->ErrorInfo;
            $Enviado='No';

        } else {

            $msj="Se ha enviado un Correo electronico con su contraseña";
            $Enviado='Si';

        }

      } catch (phpmailerException $e) {
          error_log($e->errorMessage());
          $msj = $e->errorMessage();
      } catch (Exception $e) {
          error_log($e->getMessage());
          $msj = $e->getMessage();
      }

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Area de Bienvenida LCD-NET</title>
            <link href="estilos.css" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css"></link>
            <style type="text/css" >
                body {
                    background-color: #F6F6F6;
                }
            </style>
    </head>
    <table><tr><td height="100px"></td></tr></table>
    <form name='Sample' method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
        <table width='260' border='0' align='center' cellpadding='0' cellspacing='1' style='height: 300px;width: 292px;background-image:url(images/login.png);border:#000 0px solid;border-color: #999; border-radius: .5em;'> 
            <tr>
                <td width='290' height='235' align='center'>
                    <table width='80%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap'>
                        <tr height='130'></tr>
                        <tr height='30'>
                            <td>E-mail: </td>
                            <td>
                                <input type="text" class="letrap" size="22" name="Clave" autofocus required /></label>
                            </td>
                        </tr>
                        <tr height='30'>
                            <td>Telefono: </td>
                            <td>
                                <input type="text" class="letrap" size="22" name="Telefono"  required/></label>
                            </td>
                        </tr>
                        <tr height='47'></tr>
                        <tr>
                            <td colspan="2" align='right'>
                                <button type="submit" class="btn btn-success btn-block text-center">
                                    Recuperar Contraseña
                                </button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <input type="hidden" name="op" value="correo"></input>
    </form>
    <table width='80%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap'>
    <tr height='30' align="center">
        <td> <?= $msj ?></td>
    </tr>
    </table>
</body>
</html>
<?php
mysql_close();
?>

