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
                  <a class="nav-link" href="{{ route('sport_meeting.all_scores') }}">3.成績一覽表</a>                  
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.total_scores') }}">4.田徑賽積分總表</a>
                </li>                
              </ul>    
              <hr>
              <h2>田徑賽計分總表</h2>
              <form name="myform">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                <thead class="table-primary">
                <tr>
                    <th>
                        資料
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        施工中
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
    <script>
      function jump(){
          if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
              location="/sport_meeting/scores/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
          }
      }
  </script>
@endsection
