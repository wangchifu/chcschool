@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="active";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-成績處理</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.score') }}">1.自訂獎狀</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.score_input') }}">2.成績登錄</a>
                  </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.all_scores') }}">3.成績一覽表</a>                  
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.total_scores') }}">4.田徑賽計分總表</a>
                </li>                
              </ul>    
              <hr>
              <h2>成績一覽表</h2>
              <form name="myform">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                </div>
            </form>
            <a href="{{ route('sport_meeting.all_scores_print',$select_action) }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 下載 docx 檔</a>
            @foreach($year_item as $k=>$v)
                <h2>{{ $action->name }} {{ $cht_num[$k] }}年級田徑賽成績一覽表</h2>
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                    <tr>                    
                        <th colspan="2" rowspan="2">
                            
                        </th>
                        @for($i=1;$i<=5;$i++)
                            <th colspan="3">
                                第{{ $cht_num[$i] }}名
                            </th>
                        @endfor
                    </tr>
                    <tr>
                        @for($i=1;$i<=5;$i++)
                            <th>
                                班級
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                紀錄
                            </th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>     
                    <?php $last_sex=""; ?>
                    @foreach($v as $k1=>$v1)                                                       
                        @if($last_sex != $k1)
                        <tr>
                            <td rowspan="{{ count($v1) }}">
                                {{ $k1 }}生
                            </td>                        
                        @endif
                        @foreach($v1 as $k2=>$v2)
                            @if($last_sex == $k1) <tr> @endif
                                <td>{{ $item_name[$v2] }}</td>
                                @for($i=1;$i<=5;$i++)
                                    <td>
                                        @if(isset($student_score[$k][$k1][$v2][$i]['class']))
                                            {{ $student_score[$k][$k1][$v2][$i]['class'] }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($student_score[$k][$k1][$v2][$i]['name']))
                                            {{ $student_score[$k][$k1][$v2][$i]['name'] }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($student_score[$k][$k1][$v2][$i]['achievement']))
                                            {{ $student_score[$k][$k1][$v2][$i]['achievement'] }}
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                            <?php $last_sex=$k1; ?>
                        @endforeach                                                                        
                    @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>

    </div>
    <script>
      function jump(){
          if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
              location="/sport_meeting/all_scores/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
          }
      }
  </script>
@endsection
