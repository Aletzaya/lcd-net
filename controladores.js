inicia();
if (screen.width > 1050) {

    $("#reporte").hover(function () {
        $("#reporte").html("<img src='images/btn_reportes-hover.png'>");
        $("#movil").html("<img src='images/btn_moviles-1.png'>");
        $("#catalogo").html("<img src='images/btn_catalogos-1.png'>");
        $("#recepcion").html("<img src='images/btn_recepcion-1.png'>");
    });
    $("#movil").hover(function () {
        $("#reporte").html("<img src='images/btn_reportes-1.png'>");
        $("#movil").html("<img src='images/btn_moviles-hover.png'>");
        $("#catalogo").html("<img src='images/btn_catalogos-1.png'>");
        $("#recepcion").html("<img src='images/btn_recepcion-1.png'>");
    });
    $("#catalogo").hover(function () {
        $("#reporte").html("<img src='images/btn_reportes-1.png'>");
        $("#movil").html("<img src='images/btn_moviles-1.png'>");
        $("#catalogo").html("<img src='images/btn_catalogos-hover.png'>");
        $("#recepcion").html("<img src='images/btn_recepcion-1.png'>");
    });
    $("#recepcion").hover(function () {
        $("#reporte").html("<img src='images/btn_reportes-1.png'>");
        $("#movil").html("<img src='images/btn_moviles-1.png'>");
        $("#catalogo").html("<img src='images/btn_catalogos-1.png'>");
        $("#recepcion").html("<img src='images/btn_recepcion-hover.png'>");
    });
    $("#administracion").hover(function () {
        $("#administracion").html("<img src='images/btn_administracion-hover.png'>");
        $("#reportes").html('<img src="images/btn_reportes-1.png">');
        $("#promocion").html('<img src="images/btn_promocion-1.png">');
        $("#recursosh").html('<img src="images/btn_recursosh-1.png">');
    });
    $("#reportes").hover(function () {
        $("#administracion").html('<img src="images/btn_administracion-1.png">');
        $("#reportes").html("<img src='images/btn_reportes-hover.png'>");
        $("#promocion").html('<img src="images/btn_promocion-1.png">');
        $("#recursosh").html('<img src="images/btn_recursosh-1.png">');
    });
    $("#promocion").hover(function () {
        $("#administracion").html('<img src="images/btn_administracion-1.png">');
        $("#reportes").html('<img src="images/btn_reportes-1.png">');
        $("#promocion").html("<img src='images/btn_promocion-hover.png'>");
        $("#recursosh").html('<img src="images/btn_recursosh-1.png">');
    });
    $("#recursosh").hover(function () {
        $("#administracion").html('<img src="images/btn_administracion-1.png">');
        $("#reportes").html('<img src="images/btn_reportes-1.png">');
        $("#promocion").html('<img src="images/btn_promocion-1.png">');
        $("#recursosh").html("<img src='images/btn_recursosh-hover.png'>");
    });

    $(".BarraPrincipal").hover(function () {
        $("#reporte").html("<img src='images/btn_reportes-1.png'>");
        $("#movil").html("<img src='images/btn_moviles-1.png'>");
        $("#catalogo").html("<img src='images/btn_catalogos-1.png'>");
        $("#recepcion").html("<img src='images/btn_recepcion-1.png'>");
        $("#administracion").html('<img src="images/btn_administracion-1.png">');
        $("#reportes").html('<img src="images/btn_reportes-1.png">');
        $("#promocion").html('<img src="images/btn_promocion-1.png">');
        $("#recursosh").html('<img src="images/btn_recursosh-1.png">');
    });
    
    $("#EncabezadoPrincipal").hover(function () {
        $("#reporte").html("<img src='images/btn_reportes-1.png'>");
        $("#movil").html("<img src='images/btn_moviles-1.png'>");
        $("#catalogo").html("<img src='images/btn_catalogos-1.png'>");
        $("#recepcion").html("<img src='images/btn_recepcion-1.png'>");
        $("#administracion").html('<img src="images/btn_administracion-1.png">');
        $("#reportes").html('<img src="images/btn_reportes-1.png">');
        $("#promocion").html('<img src="images/btn_promocion-1.png">');
        $("#recursosh").html('<img src="images/btn_recursosh-1.png">');
    });

    $("#reporte").html("<img src='images/btn_reportes-1.png'>");
    $("#movil").html("<img src='images/btn_moviles-1.png'>");
    $("#catalogo").html("<img src='images/btn_catalogos-1.png'>");
    $("#recepcion").html("<img src='images/btn_recepcion-1.png'>");
    $("#administracion").html('<img src="images/btn_administracion-1.png">');
    $("#reportes").html('<img src="images/btn_reportes-1.png">');
    $("#promocion").html('<img src="images/btn_promocion-1.png">');
    $("#recursosh").html('<img src="images/btn_recursosh-1.png">');
} else {


    $("#reporte").html("<a class='ltr'><b>Reporte</b></a>");
    $("#movil").html("<a class='ltr'><b>Movil</b></a>");
    $("#catalogo").html("<a class='ltr'><b>Catalogo</b></a>");
    $("#recepcion").html("<a class='ltr'><b>Recepcion</b></a>");
    $("#administracion").html("<a class='ltr'><b>Admin</b></a>");
    $("#reportes").html("<a class='ltr'><b>Reportes</b></a>");
    $("#promocion").html("<a class='ltr'><b>Promocion</b></a>");
    $("#recursosh").html("<a class='ltr'><b>Rec. H.</b></a>");
}

$("#Clic_Atencion").click(function () {
    tabla1();
    localStorage.setItem("NumeroM", 1);
});
$("#Clic_Operativa").click(function () {
    tabla2();
    localStorage.setItem("NumeroM", 2);

});
$("#Clic_Administrativa").click(function () {
    tabla3();
    localStorage.setItem("NumeroM", 3);
});
$("#Sistemas").click(function () {
    tabla4();
    localStorage.setItem("NumeroM", 4);
});
var estado = 1;
estado = localStorage.getItem('NumeroM');

if (estado == 1) {
    tabla1();
} else if (estado == 2) {
    tabla2();
} else if (estado == 3) {
    tabla3();
} else if (estado == 4) {
    tabla4();
}

function tabla1() {
    $("#Tabla_1").show();
    $("#Tabla_2").hide();
    $("#Tabla_3").hide();
    $("#Tabla_4").hide();
    $("#Admin_muestra").hide();
    $("#Procesos_muestra").hide();
    $("#Respaldos_muestra").hide();
    $("#Recursos_Humanos").hide();
    $("#Recepcion_muestra").show();
    $("#Catalogo_muestra").show();
    $("#Movil_muestra").show();
    $("#Reporte_muestra").show();
    $("#masopc").show();
    $("#masopcalend").show();
}
function tabla2() {
    $("#Tabla_1").hide();
    $("#Tabla_3").hide();
    $("#Tabla_2").show();
    $("#Tabla_4").hide();
    $("#Admin_muestra").hide();
    $("#Recepcion_muestra").hide();
    $("#Catalogo_muestra").hide();
    $("#Movil_muestra").hide();
    $("#Reporte_muestra").hide();
    $("#Recepcion_muestra").hide();
    $("#Respaldos_muestra").hide();
    $("#Recursos_Humanos").hide();
    $("#Procesos_muestra").show();
    $("#masopc").hide();
    $("#masopcalend").show();
}
function tabla3() {
    $("#Tabla_1").hide();
    $("#Tabla_2").hide();
    $("#Tabla_3").show();
    $("#Tabla_4").hide();
    $("#Procesos_muestra").hide();
    $("#Recepcion_muestra").hide();
    $("#Catalogo_muestra").hide();
    $("#Movil_muestra").hide();
    $("#Reporte_muestra").hide();
    $("#Recepcion_muestra").hide();
    $("#Respaldos_muestra").hide();
    $("#Recursos_Humanos").show();
    $("#Admin_muestra").show();
    $("#Promocion_muestra").show();
    $("#masopc").show();
    $("#masopcalend").hide();
}
function tabla4() {
    $("#Tabla_2").hide();
    $("#Tabla_3").hide();
    $("#Tabla_1").hide();
    $("#Tabla_4").show();
    $("#Procesos_muestra").hide();
    $("#Recepcion_muestra").hide();
    $("#Catalogo_muestra").hide();
    $("#Movil_muestra").hide();
    $("#Reporte_muestra").hide();
    $("#Recepcion_muestra").hide();
    $("#Admin_muestra").hide();
    $("#Recursos_Humanos").hide();
    $("#Respaldos_muestra").show();
    $("#masopc").show();
    $("#masopcalend").show();
}
function inicia() {
    $("#Tabla_1").hide();
    $("#Tabla_2").hide();
    $("#Tabla_3").hide();
    $("#Tabla_4").hide();
    $(".burbuja").hide();
    $(".nubes").hide();
    localStorage.setItem("Salimos", "No");
}


function salimos() {
    window.location.href = "index.php";
}

if (localStorage.getItem('Salimos') === "Si") {
    window.setInterval(function () {
        localStorage.setItem("Salimos", "No");
        localStorage.setItem("Color", "");
        salimos();
    }, 50000);
}
if (localStorage.getItem('Salimos') === "No") {
    window.setInterval(function () {
        localStorage.setItem("Color", "black");
        localStorage.setItem("Origen", window.location);
        localStorage.setItem("Salimos", "Si");
        window.location.href = "menu.php";
    }, 7000000);
}
function Sleep() {
    var timeout;
    document.onmousemove = function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            localStorage.setItem("Color", "");
            window.location.href = localStorage.getItem('Origen');
        }, 100);
    };
}

if (localStorage.getItem('Color') === "black") {
    document.body.style.backgroundColor = "#737373";
    $(".burbuja").show();
    $(".nubes").show();
    localStorage.setItem("Salimos", "No");
    Sleep();
}

window.setInterval(function () {
    Check();
}, 90000);

function Check() {
    $.ajax({
        type: "POST",
        url: "status.php",
        success: function (data) {
            if (data === "fuera") {
                salimos();
            }
        },
        error: function (jqXHR, ex) {
            console.log("Status: " + jqXHR.status);
            console.log("Uncaught Error.\n" + jqXHR.responseText);
            console.log(ex);
        }
    }
    );
}
