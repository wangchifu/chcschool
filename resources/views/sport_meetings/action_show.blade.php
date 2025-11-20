@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="active";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-報名狀況</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('sport_meeting.action') }}">1.報名任務</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.admin') }}">2.學生資料</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.user') }}">3.教師帳號</a>
                  </li>  
              </ul>                          
              <div class="card">
                <div class="card-body">                  
                  @if($admin)
                    <h4>報名狀況</h4>
                    <a href="{{ route('sport_meeting.action') }}" class="btn btn-secondary btn-sm">返回</a>
                    <table class="table table-striped">
                        <thead class="table-primary">
                        <tr>
                            <td>
        
                            </td>
                            <?php $has_game = []; ?>
                            @foreach($items as $item)
                                <td>
                                    {{ $item->name }}
                                </td>
                                <?php                                    
                                    $years_array = unserialize($item->years);
                                    //檢查有無該年級參賽
                                    foreach($student_classes as $student_class){
                                        if(!isset($has_game[$student_class->student_year])){
                                            if(in_array($student_class->student_year,$years_array)){
                                                $has_game[$student_class->student_year] = 1;
                                            }
                                        }                                        
                                    }                                                                                                                                            
                                ?>
                            @endforeach                            
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student_classes as $student_class)
                            @if(isset($has_game[$student_class->student_year]))                                
                                <tr>
                                    <td>
                                        {{ $student_class->student_year }}年{{ $student_class->student_class }}班
                                    </td>
                                    @foreach($items as $item)   
                                        <?php
                                            $years_array = unserialize($item->years);
                                            $student_signs = \App\StudentSign::where('item_id',$item->id)
                                            ->where('student_year',$student_class->student_year)
                                            ->where('student_class',$student_class->student_class)
                                            ->orderBy('sex','DESC')
                                            ->orderBy('group_num')
                                            ->orderBy('is_official','DESC')
                                            ->get();
                                            $boy_count = 0;
                                            $girl_count = 0;
                                            foreach($student_signs as $student_sign){
                                                if($student_sign->sex=="男"){
                                                    $boy_count++;
                                                } 
                                                if($student_sign->sex=="女"){
                                                    $girl_count++;
                                                } 
                                            }
                                            $show_br = 0;
                                        ?>                                 
                                        <td>
                                            @if(!in_array($student_class->student_year,$years_array))
                                                --
                                            @elseif($item->game_type=="group" or $item->game_type=="personal")
                                                @if($item->group==3 or $item->group==1)
                                                    @if($boy_count < $item->people)     
                                                        <a href="#!">                                                            
                                                            <img id="get_boy_students" src="{{ asset('images/boy_plus.png') }}" width="20" data-toggle="modal" data-target="#addModal" data-item_name="{{ $item->name }}" data-action_id="{{ $action->id }}" data-sex="男" data-student_year="{{ $student_class->student_year }}" data-student_class="{{ $student_class->student_class }}">
                                                        </a>
                                                        <?php $show_br = 1; ?>
                                                    @endif                                                    
                                                @endif
                                                @if($item->group==3 or $item->group==2)
                                                    @if($girl_count < $item->people)
                                                        <a href="#!">
                                                            <img id="get_girl_students" src="{{ asset('images/girl_plus.png') }}" width="20" data-toggle="modal" data-target="#addModal" data-item_name="{{ $item->name }}" data-action_id="{{ $action->id }}" data-sex="女" data-student_year="{{ $student_class->student_year }}" data-student_class="{{ $student_class->student_class }}">
                                                        </a>
                                                        <?php $show_br = 1; ?>
                                                    @endif
                                                @endif
                                                @if($show_br == 1)
                                                    <br>
                                                @endif
                                            @endif                                        
                                            @if(in_array($student_class->student_year,$years_array) and count($student_signs)==0 and $item->game_type !="class")                                            
                                                未報名
                                            @endif
                                            @if(in_array($student_class->student_year,$years_array) and $item->game_type =="class")                                            
                                                班際賽
                                            @endif                                        
                                            @foreach($student_signs as $student_sign)
                                                @if($student_sign->item->game_type=="personal")
                                                    @if($student_sign->student->sex == "男")
                                                        <span class="text-primary">
                                                            {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                        </span>
                                                        <br>
                                                    @endif
                                                    @if($student_sign->student->sex == "女")
                                                        <span class="text-danger">
                                                            {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                        </span>
                                                        <br>
                                                    @endif
                                                @endif
                                                @if($item->game_type=="group")
                                                    @if($student_sign->is_official)
                                                        正{{ $student_sign->group_num }}-
                                                    @else
                                                        預{{ $student_sign->group_num }}-
                                                    @endif
                                                    @if($student_sign->student->sex == "男")
                                                        <span class="text-primary">
                                                        {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                    </span>
                                                        <br>
                                                    @endif
                                                    @if($student_sign->student->sex == "女")
                                                        <span class="text-danger">
                                                        {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                    </span>
                                                        <br>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>

                            @endif                            
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('sport_meeting.action') }}" class="btn btn-secondary btn-sm">返回</a>
                  @endif
                </div>
              </div>        
        </div>        
    </div>    

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">請確認</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sport_meeting.student_sign_update') }}" method="post" id="add_form">
                        
                        @csrf
                        @method('patch')
                    <span id="showText"></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                    <button id="do" class="btn btn-primary" onclick="document.getElementById('add_form').submit()">確定</button>
                </div>
            </div>
        </div>
    </div>    
    <script>                         
        $(function () { $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var item_name = button.data('item_name');
            var action_id = button.data('action_id');
            var sex = button.data('sex');
            var student_year = button.data('student_year');
            var student_class = button.data('student_class');
            $.ajax({
                url: '{{ route('sport_meeting.get_students') }}',
                type: 'post',
                dataType: 'json',
                data: {
                     _token: '{{ csrf_token() }}',  // 必加
                    action_id: action_id,
                    sex: sex,
                    student_year: student_year,
                    student_class: student_class
                },
                success: function (result) {    
                    var html = item_name+'<br>'+student_year + '年' + student_class + '班 ' + sex + '生<br>';
                    html += '<select class=\'form-control\' name=\'student_id\'><option>--請選擇--</option>';
                    for (var key in result) {
                        html += '<option value="'+ key +'">'+ result[key] +'</option>';
                    }
                    html += '</select>';                                        
                    $('#showText').html(html)
                },
                error: function () {
                    $('#showText').html('not ok');
                }
            });
            
            })
        });

        

    </script>    
@endsection
