<?php
$GtitleNavegador = ":: Lcd-Net - Sistema de administracion ";
$Gdir = "Fray Pedro de Gante Norte No 320";
$Gcol = "Texcoco de Mora Cp.56100 ";
$Gtel = "Tel. 5959540495";
$aSucursal = array('Administrativo', 'Texcoco', 'H.Futura', 'Tepexpan', 'Los reyes','Camarones','SnVicente');
$Gfdogrid = "#E1E1E1";       // Fondo del Grid
$Gbarra = "#99CC00";       // Color de la barra de movto dentro del grid   /7AAODD
$Gbgsubtitulo = '#61ad53';     // Azul=313654 , Verde=61ad53	
$Giva = .16;
?>

<script language='JavaScript1.2'>

    function wingral(url) {
        window.open(url, 'wingeneral', 'status=no,tollbar=yes,scrollbars=yes,menubar=no,width=1200,height=800,left=50,top=50')
    }
    
    function wingral2(url) {
        window.open(url, 'wingeneral2', 'status=no,tollbar=yes,scrollbars=yes,menubar=no,width=1200,height=800,left=50,top=50')
    }

    function winmed(url) {
        window.open(url, 'winmediano', 'status=no,tollbar=yes,scrollbars=yes,menubar=no,width=850,height=600,left=50,top=50')
    }    

    function winmed2(url) {
        window.open(url, 'winmediano2', 'status=no,tollbar=yes,scrollbars=yes,menubar=no,width=900,height=450,left=170,top=50')
    }
    
    function winuni(url) {
        window.open(url, 'filtros', 'status=no,tollbar=yes,scrollbars=yes,menubar=no,width=650,height=550,left=250,top=80')
    }

    function wineti(url) {
        window.open(url, 'filtros', 'status=no,tollbar=yes,scrollbars=yes,menubar=no,width=500,height=200,left=250,top=80')
    }

    function winmin(url) {
        window.open(url, 'miniwin', 'width=350,height=500,left=200,top=120,location=no')
    }

    function confirmar(mensaje, url) {
        if (confirm(mensaje)) {
            document.location.href = url;
        }
    }

</script>
