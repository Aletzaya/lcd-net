
<link href = "estilos.css?var=1.3" rel = "stylesheet" type = "text/css"/>
<link href = "menu.css?var=2.1" rel = "stylesheet" type = "text/css" />
<script language = "JavaScript" src = "js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel='icon' href='favicon.ico' type='image/x-icon' />
<script src="js/jquery-1.8.2.min.js"></script>
<script src="jquery-ui/jquery-ui.min.js"></script>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link href="css/estilos_lcd_net.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    $(document).ready(function () {
        var observaciones = "<?= $Msj ?>";
        var error1 = "<?= $Error ?>";
        var error = "<?= $_REQUEST[Error] ?>";
        var time = 2000;
        var status = "success";
        if (error1 == "SI") {
            time = 10000
            status = "error";
        }
        if (error == "SI") {
            time = 10000
            status = "error";
        }
        if (observaciones != "") {
            Swal.fire({
                title: observaciones,
                position: "top-right",
                icon: status,
                timer: time,
                toast: true
            })
        }
    });
</script>
