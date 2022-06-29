<?php

require_once("phpmailer/PHPMailer.php");
require_once("data/SmtpDAO.php");

class MailSender {

    /**
     * 
     * @param CiaVO $sucursal
     * @param ClientesVO $cliente
     * @param string $uuid
     * @param string $xml
     * @param string $pdf
     * @param string $message
     * @return boolean
     */
    public static function send($sucursal, $cliente, $folio, $uuid, $xml, $pdf, $b64image = "", $message = "") {

        try {
            error_log("Enviando CFDI a " . $cliente->getCorreo());

            $smtp = SmtpDAO::active();
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->IsSMTP(true);
            $mail->CharSet = \PHPMailer\PHPMailer\PHPMailer::CHARSET_UTF8;
            $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
            $mail->Debugoutput = 'error_log';
            $mail->Host = $smtp->getServer();
            $mail->Port = $smtp->getPort();

            if ($smtp->getAuth()==1) {
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = $smtp->getAuthtype();
                $mail->SMTPOptions = array (
                            'ssl' => array(
                            'verify_peer'  => false,
                            'verify_peer_name'  => false,
                            'allow_self_signed' => true));
            }

            $mail->Username = $smtp->getLoginuser();
            $mail->Password = $smtp->getLoginpass();

            //Attach an image file
            $mail->AddStringAttachment($pdf, $uuid . ".pdf", "base64", "application/pdf");
            $mail->AddStringAttachment($xml, $uuid . ".xml", "base64", "application/xml");
            // Attach inline image
            $mail->addStringEmbeddedImage(base64_decode($b64image), "logo", "logo.png", "base64");

            $mail->ContentType = 'multipart/mixed';
            $mail->Subject = "Envío de Factura Electrónica " . $folio;
            $mail->Body = "Estimado <b>" . $cliente->getNombre() . "</b>:<br /><br />"
                    . "Le estamos enviando por este medio el <b>CFDI Comprobante Fiscal Digital (Factura Electrónica) </b> "
                    . "correspondiente a su operación con <b>" . $sucursal->getNombre() . "</b>"
                    . "<br/><br/>Nos ponemos a sus órdenes para cualquier aclaración al respecto."
                    . "<br/><br/>"
                    . "<table style=\"width: 100%; border-collapse: collapse; background-color:#233C71; color: #FFFFFF; border-radius:8px; -moz-border-radius:12px;\">"
                    . "<tr style=\"font-weight: bold; font-size: 30px; text-decoration: none; vertical-align: middle;\">"
                    . "<td style=\"padding: 10px; width: 1%; \"><img src=\"cid:logo\" width=\"250\"></td>"
                    . "<td style=\"padding: 10px; text-align: left;\">DETIFAC - Sistema de Facturación Electrónica</td></tr>"
                    . "<tr><td  style=\"padding: 10px; \" colspan=\"2\">"
                    . "&copy; Deti Desarrollo y Transferencia de Informática S.A. de C.V. / detisa.com.mx</td></tr></table>"
                     . "<br/><small>Los correos se envían a través de un sistema automatizado. Favor de no responder.</small>";
            $mail->AltBody = "Estimado " . $cliente->getNombre() . ":"
                    . "\r\n\r\nLe estamos enviando por este medio el CFDI Comprobante Fiscal Digital (Factura Electrónica) correspondiente a su operación con " . $sucursal->getNombre()
                    . "\r\nNos ponemos a sus órdenes para cualquier aclaración al respecto."
                    . "\r\n----------------------------------------------------------------------------------------------------------------------------------\r\n"
                    . "Sistema de Facturacion Electronica / Detisa / Deti Desarrollo y Transferencia de Informática S.A. de C.V. / detisa.com.mx"
                    . "\r\nLos correos se envían a través de un sistema automatizado. Favor de no responder.";
            ini_set('log_errors_max_len', 0);
            error_log($mail->Body);
            if (!empty($message)) {
                $mail->Body .= "<br/><br/>PS: " . $message . "</b></i>";
                $mail->AltBody .= "\r\n\r\nPS: " . $message;
            }
            //Set who the message is to be sent from
            $mail->SetFrom($smtp->getSender(), 'Facturación Electrónica Detifac');

            $mail->AddAddress($cliente->getCorreo(), $cliente->getNombre());

            //Send the message, check for errors
            $sended = $mail->Send();
            error_log($mail->ErrorInfo);
            return $sended;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
