/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Autor : Alejandro Ayala Gonzalez
 */

//Sirve para la busqueda de clientes
$(function () {
    
    //Ocultamos tabla de resultados
    $('#task-result').hide();
    $('#tbs').hide();

    $('#task-form').submit(function (e) {
        const postData = {
            folio: $('#folio').val(),
            cliente: $('#cliente').val()
        };
        console.log(postData);
        $.post('estudiosbk.php?op=buscar', postData, function (response) {
            //fetchTasks();
            $('#tbs').show();
            $('#task-form').trigger('reset');
            console.log(response);
            let tasks = JSON.parse(response);
            let template = "";
            tasks.forEach(task => {
                template += `<tr>${task.folio} ${task.srt}</tr>`
               
            });
            console.log(template);
        });
        e.preventDefault();
    });
    function fetchTasks() {

        $.ajax({
            url: 'estudiosbk.php?op=ini',
            type: 'GET',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = "";
                tasks.forEach(task => {
                    template += `<tr taskId='${task.id}'>
<td>${task.folio}</td>
<td>
<a href='#' class='task-item'>
${task.nombre}
</a>
</td>
</tr>`
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
            $.post('estudiosbk.php?op=dt', {id}, function (response) {
                console.log(response);
                fetchTasks();
            })
        }
    });
    $(document).on('click', '.task-item', function () {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('taskId');
        //console.log(id);
        $.post('estudiosbk.php?op=edt',{id},function(response){
            console.log(response);
            const task = JSON.parse(response);
            $('#nombre').val(task.nombre);
            $('#apellidop').val(task.apellidop);
            $('#taskId').val(task.id);
            edit = true;
        });
    });
});
