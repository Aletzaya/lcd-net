<?php
#Librerias

session_start();

//$_SESSION["Usr"] = array("", "", "", "", "", "", "Admon");
$_SESSION["Usr"] = array("", "", "", "", "", "", "u938386532_root");

if ($_REQUEST[op] == 9) {
    $Msj = "<a class='msj'>Sesión cerrada con exito</a>";
} elseif ($_REQUEST[op] == 99) {
    $Msj = "Datos de usuario no coinciden";
    $Error = "SI";
} elseif ($_REQUEST[op] == 8) {
    $Msj = "Sesión caduca";
    $Error = "SI";
} elseif ($_REQUEST[op] == 7) {
    $Msj = "Usuario asignado para otra Cia";
    $Error = "SI";
}

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");
//$Gusr     = "Admon";
//$_SESSION['usr']='Admon';

require("lib/lib.php");

$link = conectarse();

$Cia = $_REQUEST[Cia];

include_once ("authconfig.php");

require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Area de Bienvenida LCD-NET</title>
        <?php require ("./config_add.php"); ?>
    </head>

    <?php
    echo "<body>";

    echo "<br><br><br>";

    if($Cia==''){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='0'){

        $formato0="style='font-weight: bold;' color=DarkRed";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='1'){

        $formato0="";
        $formato1="style='font-weight: bold;' color=DarkRed";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='2'){

        $formato0="";
        $formato1="";
        $formato2="style='font-weight: bold;' color=DarkRed";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='3'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="style='font-weight: bold;' color=DarkRed";
        $formato4="";
        $formato5="";
        $formato6="";

    }elseif($Cia=='4'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="style='font-weight: bold;' color=DarkRed";
        $formato5="";
        $formato6="";

    }elseif($Cia=='5'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="style='font-weight: bold;' color=DarkRed";
        $formato6="";

    }elseif($Cia=='6'){

        $formato0="";
        $formato1="";
        $formato2="";
        $formato3="";
        $formato4="";
        $formato5="";
        $formato6="style='font-weight: bold;' color=DarkRed";

    }


    ?>

    <table width='290' border='0' align='center' cellpadding='0' cellspacing='1' style='background-image:url(images/login2p.png);border:#000 0px solid;border-color: #999; border-radius: .5em;'> 
        <tr>
            <td width='290' height='335' align='center'>
                <table width='85%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap'>
                        <tr style="height: 75px">
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td width='15%' height='50' rowspan='8'>Sucursal:</td>
                        </tr>
                        <tr>
                            <td colspan="2"  width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=0'><font size='2' <?=$formato0?>>(0) Administracion</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"  width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=1'><font size='2' <?=$formato1?>>(1) LCD Matriz</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"  width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=2'><font size='2' <?=$formato2?>>(2) LCD OHF</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"  width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=3'><font size='2' <?=$formato3?>>(3) LCD Tepexpan</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"  width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=4'><font size='2' <?=$formato4?>>(4) LCD Los Reyes</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"  width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=5'><font size='2' <?=$formato5?>>(5) LCD Camarones</font></a>
                            </td>
                        </tr>
                        <tr>
                            <td  colspan="2" width='75%' height='25' style="border-radius: 25px;" onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand';this.style.textAlign='light'; onMouseOut=this.style.backgroundColor='';this.style.textAlign='left';>
                                <a class='edit' href='index.php?Cia=6'><font size='2' <?=$formato6?>>(6) LCD Sn Vicente Ch.</font></a>
                            </td>
                        </tr>
                    <?php
                    if($Cia==''){
                    ?>                       
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                        <tr height='30'><td colspan='2' align="center"><b>Seleccione primero una sucursal... </b></td></tr>
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                    <?php
                    }else{
                    ?>
                        <tr height='30'>
                            <td colspan="2"></td>
                        </tr>
                        <form name='Sample' method='post' action='<?= $resultpage ?>'>
                            <tr height='25'>
                                <td colspan="2"> </td>
                                <td rowspan="3" align='right' valign="bottom">
                                    <input type="submit" class="letrap" name="Boton" value="Enviar"/> &nbsp; &nbsp; &nbsp;
                                </td>
                            </tr>
                            <tr height='30'>
                                <td>Usuario:</td>
                                <td>
                                    <input type="text" style="width: 90%;" class="letrap" size="22" id="usuario" autofocus name="username" /></label>
                                </td>
                            </tr>
                            <tr height='30'>
                                <td>Password:</td>
                                <td>
                                    <input type="password" style="width: 80%;"  class="letrap" size="22" id="password" name="password" /> <i style="color: #101213" id="view" class="fa fa-eye" aria-hidden="true"></i>
                                </td>
                            </tr>

                            <input type="hidden" name="Cia" value="<?= $Cia ?>"></input>
                        </form>
                    <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <table align='center'>
        <tr>
            <td class='letrap' align='center'>
                Para el correcto uso del sistema es necesario utilizar el navegador 
                <b>Google Crome </b>
                <i class='fa fa-google fa-lg' aria-hidden='true' style='color:#D63E2F'></i>
            </td>
        </tr>
    </table>

    <?php
    
    echo"</body>";
    ?>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        var btnClick = document.getElementById('view');

        /*Cuando se haga clic*/
        btnClick.onmousedown = function () {
            $("#password").attr('type', 'text');
        };

        /*Cuando se deje de hacer clic*/
        btnClick.onmouseup = function () {
            $("#password").attr('type', 'password');
        };
    });
</script>