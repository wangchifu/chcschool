@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '行政待辦 | ')

@section('content')
    <style>
        .gif {
            position:absolute;
            top:20%;
            left:10%;
            z-index:10;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-11">
            @include('tasks.form')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('tasks.index') }}">待辦</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index2') }}">完成</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index3') }}">無關</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.self') }}"><i class="fas fa-plus"></i> 自己</a>
                </li>
            </ul>
            <br>
            <div id="task_content">
                @foreach($user_tasks as $user_task)
                    <?php
                        $files = get_files(storage_path('app/privacy/'.$school_code.'/tasks/'.$user_task->task_id));
                    ?>
                    {{ Form::open(['route' => ['tasks.user_condition',$user_task->id], 'method' => 'POST','id'=>'user_condition'.$user_task->id,'onsubmit'=>'return false']) }}
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="condition" id="inlineRadio{{ $user_task->id }}_2" value="2" onchange="go_submit('{{ $user_task->id }}')">
                        <label class="form-check-label text-success" for="inlineRadio{{ $user_task->id }}_2">完成</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="condition" id="inlineRadio{{ $user_task->id }}_3" value="3" onchange="go_submit('{{ $user_task->id }}')">
                        <label class="form-check-label text-primary" for="inlineRadio{{ $user_task->id }}_3">無關</label>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="user_task_id" value="{{ $user_task->id }}">
                        <input type="hidden" name="old_condition" value="{{ $user_task->condition }}">
                    {{ Form::close() }}
                    @if($user_task->task->disable)
                    <span style="text-decoration:line-through;text-decoration-color: red;word-wrap:break-word;">
                        <span class="badge badge-danger">已廢</span> {{ $user_task->task->title }}
                    </span>
                    @else
                    <span style="word-wrap:break-word;">
                        {{ $user_task->task->title }}
                    </span>
                    @endif
                        @if(!empty($files))
                            <br>
                            附件：
                            <?php $n=1; ?>
                            @foreach($files as $k=>$v)
                                <?php
                                $file = $school_code."/tasks/".$user_task->task_id."/".$v;
                                $file = str_replace('/','&',$file);
                                ?>
                                <a href="{{ url('file_open/'.$file) }}" class="badge badge-primary" target="_blank"><i class="fas fa-download"></i> 檔{{ $n }}</a>
                            <?php $n++; ?>
                            @endforeach
                        @endif
                    <br>
                    <small class="text-secondary">({{ $user_task->task->user->name }} {{ $user_task->task->created_at }})</small>
                    @if($user_task->task->user->id == $user->id and $user_task->task->disable==null)
                        <i class="fas fa-question-circle"></i>
                        <a href="{{ route('tasks.disable',$user_task->task_id) }}" onclick="if(confirm('作廢嗎?')) return true;else return false"><i class="fas fa-times-circle text-danger"></i></a>
                    @endif
                    <hr>
                @endforeach
                @if(count($user_tasks) == 0)
                    乾乾淨淨
                @endif
            </div>
            <div class="gif">
                <img src="{{ asset('images/celebration.gif') }}" style="display: none" id="img2" onclick="hideGIF();">
            </div>
        </div>
    </div>
    <script>
        function go_submit(id){
            $.ajax({
                url: '{{ route('tasks.user_condition') }}',
                type : 'post',
                dataType : 'json',
                data : $('#user_condition'+id).serialize(),
                success : function(result) {
                    if(result != 'failed') {
                        showGIF();
                        total_data = show_conntent(result);
                        document.getElementById('task_content').innerHTML = total_data;
                    }
                },
                error: function(result) {
                    alert('失敗！');
                }
            })
        };
        function show_conntent(result){
            data = '';
            for(var k in result['user_task']){
                data = data + '<form method="POST" action="{{ route('tasks.user_condition') }}'+result['user_task'][k]['user_task_id']+'" accept-charset="UTF-8" id="user_condition'+result['user_task'][k]['user_task_id']+'" onsubmit="return false">';
                data = data + '<input name="_token" type="hidden" value="'+result['token']+'">';
                data = data + '<div class="form-check form-check-inline">';
                data = data + '<input class="form-check-input" type="radio" name="condition" id="inlineRadio'+result['user_task'][k]['user_task_id']+'_2" value="2" onchange="go_submit(\''+result['user_task'][k]['user_task_id']+'\')">';
                data = data + '<label class="form-check-label text-success" for="inlineRadio'+result['user_task'][k]['user_task_id']+'_2">完成</label>';
                data = data + '</div>';
                data = data + '<div class="form-check form-check-inline">';
                data = data + '<input class="form-check-input" type="radio" name="condition" id="inlineRadio'+result['user_task'][k]['user_task_id']+'_3" value="3" onchange="go_submit(\''+result['user_task'][k]['user_task_id']+'\')">';
                data = data + '<label class="form-check-label text-primary" for="inlineRadio'+result['user_task'][k]['user_task_id']+'_3">無關</label>';
                data = data + '</div>';
                data = data + '<input type="hidden" name="user_id" value="{{ $user->id }}">';
                data = data + '<input type="hidden" name="user_task_id" value="'+result['user_task'][k]['user_task_id']+'">';
                data = data + '<input type="hidden" name="old_condition" value="'+result['old_condition']+'">';
                data = data + '</form>';
                if(result['user_task'][k]['disable']==1){
                    data = data + '<span style="text-decoration:line-through;text-decoration-color: red;word-wrap:break-word;">';
                    data = data + '<span class="badge badge-danger">已廢</span> '+result['user_task'][k]['title'];
                    data = data + '</span>';
                }else{
                    data = data + '<span style="word-wrap:break-word;">';
                    data = data + result['user_task'][k]['title'];
                    data = data +'</span>';
                }
                if(result['files'][k] != null){
                    if(result['files'][k][1] != 0){
                        data = data + '<br>';
                        data = data + '附件：';
                        for(var j in result['files'][k]){
                            data = data + '<a href="{{ url('file_open') }}/'+result['files'][k][j]+'" class="badge badge-primary" target="_blank"><i class="fas fa-download"></i> 檔'+j+'</a> ';
                        }
                    }
                }
                data = data + '<br>';
                t = result['user_task'][k]['created_at'].replace(',',' ');
                data = data +'<small class="text-secondary">('+result['user_task'][k]['name']+' '+t+')</small>';
                if(result['user_task'][k]['user_id'] == {{ $user->id }} && result['user_task'][k]['disable']==null){
                    data = data + '<a href="{{ url('tasks.disable') }}/'+result['user_task'][k]['user_task_id']+'" onclick="if(confirm(\'作廢嗎?\')) return true;else return false"><i class="fas fa-times-circle text-danger"></i></a>';
                }

                data =data + '<hr>';
            }

            if(result['count'] == 0){
                data = data = '乾乾淨淨';
            }

            return data;
        }


        function showGIF(){
            $("#img2").fadeToggle();
            $("#img2").fadeToggle("fast");
            $("#img2").fadeToggle(500);
            $("#img2").hide();
        }

        function hideGIF(){
            $("#img2").hide();
        }
    </script>
@endsection
