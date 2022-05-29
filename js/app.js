/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Autor : Alejandro Ayala Gonzalez
 */

//Sirve para la busqueda de clientes
$(function () {
    
    let edit = false;
    
    //Ocultamos tabla de resultados
    $('#task-result').hide();

    $('#search').keyup(function () {
        //Cuando search tiene valor entra
        if ($('#search').val()) {
            let search = $('#search').val();

            //Aqui mostramos lo escrito en el buscador
            console.log(search);
            //type=tipo de dato a resivir
            //data=enviamos el string a buscar
            //success= si sucede y la url trae algo
            $.ajax({
                url: 'clienteseajaxp.php',
                type: 'POST',
                data: {search},
                success: function (response) {
                    let tasks = JSON.parse(response);
                    let template = '';
                    tasks.forEach(task => {
                        template += `<li>${task.buscador}</li>`
                    });
                    
                    $('#container').html(template);
                    $('#task-result').show();
                }
            })

        } else {
            $('#task-result').hide();
        }
    })
    $('#task-form').submit(function (e) {
        const postData = {
            nombre: $('#nombre').val(),
            apellidop: $('#apellidop').val(),
            id: $('#taskId').val()
        };
        let url = edit === false ? 'clienteseajaxp.php?op=dts' : 'clienteseajaxp.php?op=edit';
        console.log(postData);
        $.post(url, postData, function (response) {
            fetchTasks();
            $('#task-form').trigger('reset');
            edit=false;
        });
        e.preventDefault();
    });
    fetchTasks();
    function fetchTasks() {

        $.ajax({
            url: 'clienteseajaxp.php?op=ini',
            type: 'GET',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = "";
                tasks.forEach(task => {
                    template += `<tr taskId='${task.id}'>
<td>${task.id}</td>
<td>
<a href='#' class='task-item'>
${task.nombre}
</a>
</td>
<td>${task.apellidop}</td>
<td><button class='task-delete btn btn-danger'>Delete</button></td></tr>`
                });
                $('#tasks').html(template);
            }
        });
    }
    $(document).on('click', '.task-delete', function () {
        if (confirm('Estas seguro de eliminar el campo')) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('taskId');
            console.log(id);
            $.post('clienteseajaxp.php?op=dt', {id}, function (response) {
                console.log(response);
                fetchTasks();
            })
        }
    });
    $(document).on('click', '.task-item', function () {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('taskId');
        //console.log(id);
        $.post('clienteseajaxp.php?op=edt',{id},function(response){
            console.log(response);
            const task = JSON.parse(response);
            $('#nombre').val(task.nombre);
            $('#apellidop').val(task.apellidop);
            $('#taskId').val(task.id);
            edit = true;
        });
    });
});
