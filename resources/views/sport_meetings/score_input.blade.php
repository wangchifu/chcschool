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
                    <a class="nav-link active" href="{{ route('sport_meeting.score_input') }}">2.成績登錄</a>
                  </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.all_scores') }}">3.成績一覽表</a>                  
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.total_scores') }}">4.田徑賽計分總表</a>
                </li>                
              </ul>    
              <hr>
              <h2>成績登錄</h2>
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
                        序號
                    </th>
                    <th>
                        名稱
                    </th>
                    <th>
                        年級
                    </th>
                    <th>
                        組別
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                <tr>
                    <form action="{{ route('sport_meeting.score_input_do') }}" method="post">
                        @csrf
                    <td>
                        {{ $item->order }}
                    </td>
                    <td>
                        {{ $item->name }}
                        @if($item->game_type=="personal")
                            <span class="badge badge-warning">個人賽</span>
                        @endif
                        @if($item->game_type=="group")
                            <span class="badge badge-primary">團體賽</span>
                        @endif
                        @if($item->game_type=="class")
                            <span class="badge badge-info">班際賽</span>
                        @endif
                    </td>
                    <td>
                        <?php
                            $years = unserialize($item->years);
                        ?>
                        <select class="form-control" name="student_year">
                        @foreach($years as  $v)
                            <option value="{{ $v }}">
                                {{ $v }} 年級
                            </option>
                        @endforeach
                        </select>
                    </td>
                    <td>
                        <select id="sex" class="form-control" name="sex">
                            @if($item->group ==1 or $item->group ==3)
                                <option value="男">男子組</option>
                            @endif
                            @if($item->group ==1 or $item->group ==3)
                                    <option value="女">女子組</option>
                            @endif
                            @if($item->group ==4)
                                <option value="4">不分性別</option>
                            @endif
                        </select>
                    </td>
                        <input type="hidden" name="action_id" value="{{ $select_action }}">
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <td>
                        <button class="btn btn-primary btn-sm">填寫</button>
                    </td>
                    </form>
                </tr>
                @endforeach
                </tbody>
            </table>                  
        </div>

    </div>
    <script>
        function jump(){
            if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
                location="/sport_meeting/score_input/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
