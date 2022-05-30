<?php
$busca = $_REQUEST[busca];

require ("config.php");       //Parametros de colores;
?>

<HTML>
    <HEAD>

        <SCRIPT LANGUAGE="JavaScript">

            /* Creamos la función que se ejecuta a la carga de la página */
            function Redireccionar(URL) {

                /* Definimos las variables */
                Tiempo = 4000
                RedireccionURL = URL;

                /* Damos al cuadro de texto el valor definido en la variable Tiempo */
                document.getElementById('restante').value = Tiempo / 1000
                /* Creamos la función que redirecciona a la URL definida */
                setTimeout("window.location.href=RedireccionURL;", Tiempo);
                /* Creamos la función que ejecuta Restar() cada 1 segundo */
                setInterval("Restar()", 1000);

            }

            /* Creamos la función */
            function Restar() {

                /* Verifica que el valor del cuadro de texto sea igual a 1 */
                if (restante.value == 1) {
                    restante.value = "Cargando " + RedireccionURL
                }
                /* Mientras la primera condición sea falsa se ejecuta esta condición restando 
                 un dígito al valor actual del cuadro de texto */
                if (restante.value >= 0) {
                    restante.value--
                }

            }
            window.setInterval(function () {
                window.location.href = 'nomina.php';
            }, 9000);
        </SCRIPT>
    </HEAD>

    <!-- Colocamos el evento onLoad que llama a la función -->
    <BODY onLoad="Redireccionar('importar.php?busca=<?php echo $busca; ?>')">

        <p>&nbsp;</p> 
        <p>&nbsp;</p>
        <p align='center'><img src='lib/img_espere.gif'></p>

    <CENTER>

        <INPUT type="hidden" id="restante" size="30" style="border:none; ">  

    </CENTER>

</BODY>
</HTML>