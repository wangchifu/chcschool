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
                    @include('layouts.errors')
                    <span>*無號碼者，請記得去「各式表單」--「1.註冊選手名單」--「學生編入布牌號碼」</span><br>
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
                                                <?php 
                                                    if($item->game_type=="group"){
                                                        $item_group =1;
                                                        $item_group_people =$item->people;
                                                        $item_group_official =$item->official;
                                                        $item_group_reserve =$item->reserve;
                                                    }
                                                    if($item->game_type=="personal"){
                                                        $item_group =null;
                                                        $item_group_people =null;
                                                        $item_group_official =null;
                                                        $item_group_reserve =null;
                                                    }
                                                    $group_show_img = 0;                                                    
                                                ?>                                            
                                                @if($item->group==3 or $item->group==1)
                                                    @if(($boy_count < $item->people) or $item->game_type=="group")
                                                        @if($item->game_type=="group")
                                                            <?php
                                                                $group = [];
                                                                foreach($student_signs as $student_sign){
                                                                    $group[$student_sign->group_num] = $group[$student_sign->group_num] ?? [];                                                                    
                                                                    $group[$student_sign->group_num]['is_official'] = $group[$student_sign->group_num]['is_official'] ?? 0;
                                                                    $group[$student_sign->group_num]['reserve'] = $group[$student_sign->group_num]['reserve'] ?? 0;
                                                                    if($student_sign->sex=="男"){
                                                                        if($student_sign->is_official==1){
                                                                            $group[$student_sign->group_num]['is_official']++ ;
                                                                        }else{
                                                                            $group[$student_sign->group_num]['reserve']++ ;
                                                                        }
                                                                    }                                                                    
                                                                }
                                                                if(count($group)==0){
                                                                    $group[1]['is_official'] = 0;
                                                                    $group[1]['reserve'] = 0;
                                                                }
                                                                for($i=1;$i<=$item->people;$i++){
                                                                    if($group[$i]['is_official'] < $item->official or $group[$i]['reserve'] < $item->reserve){
                                                                        $group_show_img = 1;                                                                        
                                                                    }
                                                                }                                                                   
                                                            ?>
                                                        @endif 
                                                        <?php
                                                        // 判斷是否應該顯示圖片的條件
                                                            $should_show_image = (
                                                                ($boy_count < $item->people) || ($item->game_type == "group")
                                                            ) && !(
                                                                ($group_show_img == 0) && ($item->game_type == "group")
                                                            );
                                                            if($should_show_image) $show_br = 1;
                                                        ?>               
                                                        @if($should_show_image)                                       
                                                            <a href="#!">                                                            
                                                                <img id="get_boy_students" src="{{ asset('images/boy_plus.png') }}" width="20" data-toggle="modal" data-target="#addModal" data-item_id="{{ $item->id }}" data-item_group="{{ $item_group }}" data-item_group_people="{{ $item_group_people }}" data-item_group_official="{{ $item_group_official }}" data-item_group_reserve="{{ $item_group_reserve }}" data-item_name="{{ $item->name }}" data-action_id="{{ $action->id }}" data-sex="男" data-student_year="{{ $student_class->student_year }}" data-student_class="{{ $student_class->student_class }}">
                                                            </a>               
                                                        @endif                                                                          
                                                    @endif                                                    
                                                @endif
                                                @if($item->group==3 or $item->group==2)
                                                    @if(($girl_count < $item->people) or $item->game_type=="group")
                                                        @if($item->game_type=="group")
                                                            <?php
                                                                $group = [];
                                                                foreach($student_signs as $student_sign){
                                                                    $group[$student_sign->group_num] = $group[$student_sign->group_num] ?? [];                                                                    
                                                                    $group[$student_sign->group_num]['is_official'] = $group[$student_sign->group_num]['is_official'] ?? 0;
                                                                    $group[$student_sign->group_num]['reserve'] = $group[$student_sign->group_num]['reserve'] ?? 0;
                                                                    if($student_sign->sex=="女"){
                                                                        if($student_sign->is_official==1){
                                                                            $group[$student_sign->group_num]['is_official']++ ;
                                                                        }else{
                                                                            $group[$student_sign->group_num]['reserve']++ ;
                                                                        }
                                                                    }                                                                    
                                                                }
                                                                if(count($group)==0){
                                                                    $group[1]['is_official'] = 0;
                                                                    $group[1]['reserve'] = 0;
                                                                }
                                                                for($i=1;$i<=$item->people;$i++){
                                                                    if($group[$i]['is_official'] < $item->official or $group[$i]['reserve'] < $item->reserve){
                                                                        $group_show_img = 1;                                                                        
                                                                    }
                                                                }                                                                   
                                                            ?>
                                                        @endif 
                                                        <?php
                                                        // 判斷是否應該顯示圖片的條件
                                                            $should_show_image = (
                                                                ($boy_count < $item->people) || ($item->game_type == "group")
                                                            ) && !(
                                                                ($group_show_img == 0) && ($item->game_type == "group")
                                                            );
                                                            if($should_show_image) $show_br = 1;
                                                        ?>               
                                                        @if($should_show_image)                                                    
                                                        <a href="#!">
                                                            <img id="get_girl_students" src="{{ asset('images/girl_plus.png') }}" width="20" data-toggle="modal" data-target="#addModal" data-item_id="{{ $item->id }}" data-item_group="{{ $item_group }}" data-item_group_people="{{ $item_group_people }}" data-item_group_official="{{ $item_group_official }}" data-item_group_reserve="{{ $item_group_reserve }}" data-item_name="{{ $item->name }}" data-action_id="{{ $action->id }}" data-sex="女" data-student_year="{{ $student_class->student_year }}" data-student_class="{{ $student_class->student_class }}">
                                                        </a>
                                                        @endif                                                        
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
                                                            @if(empty($student_sign->student->number))
                                                                無號碼 {{ $student_sign->student->name }}
                                                            @else
                                                                {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                            @endif
                                                            <a href="{{ route('sport_meeting.get_students_delete',$student_sign->id) }}" onclick="return confirm('確定刪除 {{ $student_sign->student->name }} 嗎？')"><i class="fas fa-times-circle text-danger"></i></a>                                                           
                                                        </span>
                                                        <br>
                                                    @endif
                                                    @if($student_sign->student->sex == "女")
                                                        <span class="text-danger">
                                                            @if(empty($student_sign->student->number))
                                                                無號碼 {{ $student_sign->student->name }}
                                                            @else
                                                                {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                            @endif
                                                            <a href="{{ route('sport_meeting.get_students_delete',$student_sign->id) }}" onclick="return confirm('確定刪除 {{ $student_sign->student->name }} 嗎？')"><i class="fas fa-times-circle text-danger"></i></a>                                                           
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
                                                            @if(empty($student_sign->student->number))
                                                                無號碼 {{ $student_sign->student->name }}
                                                            @else
                                                                {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                            @endif
                                                            <a href="{{ route('sport_meeting.get_students_delete',$student_sign->id) }}" onclick="return confirm('確定刪除 {{ $student_sign->student->name }} 嗎？')"><i class="fas fa-times-circle text-danger"></i></a>                                                           
                                                    </span>
                                                        <br>
                                                    @endif
                                                    @if($student_sign->student->sex == "女")
                                                        <span class="text-danger">
                                                            @if(empty($student_sign->student->number))
                                                                無號碼 {{ $student_sign->student->name }}
                                                            @else
                                                                {{ $student_sign->student->number }} {{ $student_sign->student->name }}
                                                            @endif
                                                            <a href="{{ route('sport_meeting.get_students_delete',$student_sign->id) }}" onclick="return confirm('確定刪除 {{ $student_sign->student->name }} 嗎？')"><i class="fas fa-times-circle text-danger"></i></a>                                                           
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
                    <form action="{{ route('sport_meeting.get_students_do') }}" method="post" id="add_form">                        
                        @csrf
                        <input type="hidden" name="action_id" value="{{ $action->id }}">
                        <input type="hidden" id="item_id" name="item_id">                  
                        <input type="hidden" id="is_group" name="is_group">
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
            var item_id = button.data('item_id');
            var item_group = button.data('item_group');            
            var item_group_people = button.data('item_group_people');
            var item_group_official = button.data('item_group_official');
            var item_group_reserve = button.data('item_group_reserve');            

            $('#item_id').val(item_id);
            if(item_group_official){
                $('#is_group').val(1);
            }
            
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
                    var head = item_name+'<br>'+student_year + '年' + student_class + '班 ' + sex + '生<br>';
                    var select_official = '<select class=\'form-control\' name=\'official_student_ids[]\'><option>--請選擇--</option>';
                    for (var key in result) {
                        select_official += '<option value="'+ key +'">'+ result[key] +'</option>';
                    }
                    select_official += '</select>';
                    var select_reserve = '<select class=\'form-control\' name=\'reserve_student_ids[]\'><option>--請選擇--</option>';
                    for (var key in result) {
                        select_reserve += '<option value="'+ key +'">'+ result[key] +'</option>';
                    }
                    select_reserve += '</select>';

                    if(item_group==1){              
                        var group_num = "<table><tr><td>請選組別</td><td><select class=\'form-control\' name=\'group_num\'>";
                        for(var i=1;i<=item_group_people;i++){
                            group_num += '<option value="'+ i +'">'+ i +'</option>';
                        }
                        group_num += '</select></td></tr></table><hr>';
                        var table1 = "<table>";
                        for(var i=1;i<=item_group_official;i++){
                            table1 += '<tr><td>正式'+i+'</td><td>'+select_official+'</td></tr>';
                        }        
                        table1 += '</table><hr>';   
                        var table2 = "<table>";
                        for(var j=1;j<=item_group_reserve;j++){
                            table2 += '<tr><td>預備'+j+'</td><td>'+select_reserve+'</td></tr>';
                        }        
                        table2 += '</table>';   
                        html = group_num+table1 + table2;                        
                    }else{
                        html = select_official;
                    }
                    html = head + html;
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
