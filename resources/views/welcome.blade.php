<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Laravel Ajax CRUD Example</title>

    <!-- Load Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-narrow">
    <h2>Laravel Ajax ToDo App</h2>
    <button id="btn-add" name="btn-add" class="btn btn-primary btn-xs open-modal">Add New Task</button>
    <div>

        <!-- Table-to-load-the-data Part -->
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Description</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="tasks-list" name="tasks-list">
            @foreach ($tasks as $task)
                <tr id="task{{$task->id}}">
                    <td>{{$task->id}}</td>
                    <td>{{$task->task}}</td>
                    <td>{{$task->description}}</td>
                    <td>{{$task->created_at}}</td>
                    <td>
                        <button class="btn btn-warning btn-xs btn-detail open-modal" value="{{$task->id}}">Edit</button>
                        <button class="btn btn-danger btn-xs btn-delete delete-task" value="{{$task->id}}">Delete</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- End of Table-to-load-the-data Part -->
        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Task Editor</h4>
                    </div>
                    <div class="modal-body">
                        <form id="frmTasks" name="frmTasks" class="form-horizontal" novalidate="">
                            <input type="hidden" id="task_id" name="task_id" value="0">
                            <div class="form-group error">
                                <label for="inputTask" class="col-sm-3 control-label">Task</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="task" name="task" placeholder="Task" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-save" value="update" >Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<meta name="_token" content="{!! csrf_token() !!}" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!--<script src="{{asset('/js/ajax-crud.js')}}"></script>-->
<script>
    $('#tasks-list').on('click',".delete-task",function (){
        var task_id = $(this).val();
        var url= "{{ route('task.delete',':task_id')}}";
        url = url.replace(':task_id', $(this).val());
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: "DELETE",
            url: url,
            success: function (data) {
                console.log(data);

                $("#task" + task_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
    $('#btn-add').click(function(){
        $('#btn-save').val("add");
    });

    $('.open-modal').click(function(){
        $("#task_id").val($(this).val());
        $('#myModal').modal();
    });

    $('#tasks-list').on('click', '.open-modal',function(){
            $("#task_id").val($(this).val());
            $('#myModal').modal();
             $('#btn-save').val("update");
    });
    $("#btn-save").click(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        console.log($('#btn-save').val())
        if($('#btn-save').val()!='add') {
            $.ajax({
                type: "POST",
                data: $('form').serialize(),
                url: "{{ route('task.store')}}",
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    var task = '<tr id="task' + data.id + '"><td>' + data.id + '</td><td>' + data.task + '</td><td>' + data.description + '</td><td>' + data.created_at + '</td>';
                    task += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data.id + '">Edit</button>';
                    task += '<button class="btn btn-danger btn-xs btn-delete delete-task" value="' + data.id + '">Delete</button></td></tr>';
                    $("#task" + data.id).replaceWith(task);
                    $('#frmTasks').trigger("reset");
                    $('#myModal').modal('hide');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }else {
            $.ajax({
                type: "POST",
                data: $('form').serialize(),
                url: "{{ route('task.add')}}",
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    var task = '<tr id="task' + data.id + '"><td>' + data.id + '</td><td>' + data.task + '</td><td>' + data.description + '</td><td>' + data.created_at + '</td>';
                    task += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data.id + '">Edit</button>';
                    task += '<button class="btn btn-danger btn-xs btn-delete delete-task" value="' + data.id + '">Delete</button></td></tr>';
                    $('#tasks-list').append(task);
                    $('#frmTasks').trigger("reset");
                    $('#myModal').modal('hide');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }


    });


    //$('#myModal').modal();
</script>
</body>
</html>
