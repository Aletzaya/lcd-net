$(function(){
    $('#form-search').submit(function(e){
        e.preventDefault();
    })
    $('#busca').keyup(fuction(){
        var envio = $('#buscar').val();
        $('#resultado-q').html("<h4>Cargando</h4>");
        $.ajax({
            type: 'post',
            usl: '/ordenesnvas.php',
            data: ('busca='+envio),
            succes: function(respuesta){
                if(respuesta != ''){
                    $('#restultado-q').html(respuesta);
                }
            }
        })
    })
})