@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="";
    $active['show'] ="";    
    $active['list'] ="active";
    $active['score'] ="";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-各式表單</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.list') }}">1.註冊選手名單</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.records') }}">2.項目記錄表</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.scores') }}">3.成績記錄表(檢錄)</a>
                </li>
              </ul>            
              <hr>
              <form name="myform">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                </div>
            </form>
            @include('layouts.errors')
            <table class="table table-striped">
                <thead class="table-primary">
                <tr>
                    <th>
                        @if(!empty($action->id))
                            <a href="{{ route('sport_meeting.action_set_number',$action->id) }}" class="btn btn-info btn-sm" onclick="return confirm('確定？')">學生編入布牌號碼</a> <a href="{{ route('sport_meeting.action_set_number_null',$action->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定清空？')">學生布牌號碼清空</a>
                        @endif
                        資料
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                    @foreach($student_classes as $student_class)
                        <?php
                            if(empty($student_class->user_ids)){
                                $user_name = $student_class->user_names;
                            }else{
                                $user_ids =  explode(',',$student_class->user_ids);
                                $user_name = null;
                                foreach($user_ids as $user_id){
                                    $user = \App\User::find($user_id);
                                    $user_name .= $user->name.'　';
                                }
                            }


                            $cht_num = config('chcschool.cht_num');

                        ?>
                            <span style="font-weight:bold;">{{ $cht_num[$student_class->student_year] }}年{{ $student_class->student_class }}班　　　　領隊：{{ $user_name }}</span><br>
                        <?php
                            $select_students['男'] = [];
                            $select_students['女'] = [];
                            $student_signs = \App\StudentSign::where('action_id',$select_action)
                                ->where('student_year',$student_class->student_year)
                                ->where('student_class',$student_class->student_class)
                                ->where('game_type','<>','class')
                                ->orderBy('num')
                                ->get();
                            foreach($student_signs as $student_sign){
                                $select_students[$student_sign->sex][$student_sign->student_id] = $student_sign->student->name;
                                $student_number[$student_sign->student_id] = $student_sign->student->number;
                            }

                            $numbers = $action->numbers;

                        ?>
                        男子組：<br>
                            <?php
                                $s = 1;
                            ?>
                            @foreach($select_students['男'] as $k=>$v)
                                {{ $student_number[$k] }} {{ $v }},
                            @endforeach
                            <br>
                        女子組：<br>
                            @foreach($select_students['女'] as $k=>$v)
                                {{ $student_number[$k] }} {{ $v }},
                            @endforeach
                            <br>
                        <br>
                    @endforeach
                    </td>
                </tr>
                </tbody>
            </table>
        </div>                
    </div>
    <script>
      function jump(){
          if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
              location="/sport_meeting/list/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
          }
      }
  </script>
@endsection
