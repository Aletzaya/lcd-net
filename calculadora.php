<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <meta charset="UTF-8">
        <title>Calculadora con JavaScript</title>
        <link type="text/css" href="estilocalcu.css" rel="stylesheet">
    </head>
    <body onload="init();">
        <table class="calculadora">
            <tr>
                <td colspan="4"><span id="resultado"></span></td>
            </tr>
            <tr>
                <td><button id="siete">7</button></td><td><button id="ocho">8</button></td><td><button id="nueve">9</button></td><td><button id="division">/</button></td>
            </tr>
            <tr>
                <td><button id="cuatro">4</button></td><td><button id="cinco">5</button></td><td><button id="seis">6</button></td><td><button id="multiplicacion">*</button></td>
            </tr>
            <tr>
                <td><button id="uno">1</button></td><td><button id="dos">2</button></td><td><button id="tres">3</button></td><td><button id="resta">-</button></td>
            </tr>
            <tr>
                <td><button id="igual">=</button></td><td><button id="reset">C</button></td><td><button id="cero">0</button></td><td><button id="suma">+</button></td>
            </tr>
            <tr>
                <td colspan="4"><span id="creditos">Hecho para DenisseEstrada.com</span></td>
            </tr>
        </table>
        <script src="funcionalidad.js">
        //Declaramos variables
        var operandoa;
        var operandob;
        var operacion;
        function init() {
            //variables
            var resultado = document.getElementById('resultado');
            var reset = document.getElementById('reset');
            var suma = document.getElementById('suma');
            var resta = document.getElementById('resta');
            var multiplicacion = document.getElementById('multiplicacion');
            var division = document.getElementById('division');
            var igual = document.getElementById('igual');
            var uno = document.getElementById('uno');
            var dos = document.getElementById('dos');
            var tres = document.getElementById('tres');
            var cuatro = document.getElementById('cuatro');
            var cinco = document.getElementById('cinco');
            var seis = document.getElementById('seis');
            var siete = document.getElementById('siete');
            var ocho = document.getElementById('ocho');
            var nueve = document.getElementById('nueve');
            var cero = document.getElementById('cero');

            //Eventos de click
            uno.onclick = function (e) {
                resultado.textContent = resultado.textContent + "1";
            }
            dos.onclick = function (e) {
                resultado.textContent = resultado.textContent + "2";
            }
            tres.onclick = function (e) {
                resultado.textContent = resultado.textContent + "3";
            }
            cuatro.onclick = function (e) {
                resultado.textContent = resultado.textContent + "4";
            }
            cinco.onclick = function (e) {
                resultado.textContent = resultado.textContent + "5";
            }
            seis.onclick = function (e) {
                resultado.textContent = resultado.textContent + "6";
            }
            siete.onclick = function (e) {
                resultado.textContent = resultado.textContent + "7";
            }
            ocho.onclick = function (e) {
                resultado.textContent = resultado.textContent + "8";
            }
            nueve.onclick = function (e) {
                resultado.textContent = resultado.textContent + "9";
            }
            cero.onclick = function (e) {
                resultado.textContent = resultado.textContent + "0";
            }
            reset.onclick = function (e) {
                resetear();
            }
            suma.onclick = function (e) {
                operandoa = resultado.textContent;
                operacion = "+";
                limpiar();
            }
            resta.onclick = function (e) {
                operandoa = resultado.textContent;
                operacion = "-";
                limpiar();
            }
            multiplicacion.onclick = function (e) {
                operandoa = resultado.textContent;
                operacion = "*";
                limpiar();
            }
            division.onclick = function (e) {
                operandoa = resultado.textContent;
                operacion = "/";
                limpiar();
            }
            igual.onclick = function (e) {
                operandob = resultado.textContent;
                resolver();
            }
        }
        function limpiar() {
            resultado.textContent = "";
        }
        function resetear() {
            resultado.textContent = "";
            operandoa = 0;
            operandob = 0;
            operacion = "";
        }
        function resolver() {
            var res = 0;
            switch (operacion) {
                case "+":
                    res = parseFloat(operandoa) + parseFloat(operandob);
                    break;
                case "-":
                    res = parseFloat(operandoa) - parseFloat(operandob);
                    break;
                case "*":
                    res = parseFloat(operandoa) * parseFloat(operandob);
                    break;
                case "/":
                    res = parseFloat(operandoa) / parseFloat(operandob);
                    break;
            }
            resetear();
            resultado.textContent = res;
        }
        </script>
    </body>
</html>
