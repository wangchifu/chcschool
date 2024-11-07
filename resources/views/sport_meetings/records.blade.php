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
                  <a class="nav-link" href="{{ route('sport_meeting.list') }}">1.註冊選手名單</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.records') }}">2.項目記錄表</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.scores') }}">3.成績記錄單</a>
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
                        <a href="{{ route('sport_meeting.action_set_number',$action->id) }}" class="btn btn-outline-primary btn-sm" onclick="return confirm('確定？')">學生編入布牌號碼</a> <a href="{{ route('sport_meeting.action_set_number_null',$action->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('確定清空？')">學生布牌號碼清空</a>
                        資料 <a href="{{ route('sport_meeting.download_records',$action->id) }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 下載</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php $cht_num = config('chcschool.cht_num'); ?>
                    @foreach($years as $k=>$v)
                        <h3>
                            {{ $cht_num[$k] }}年級
                        </h3>
                        <?php $n=1; ?>
                        @foreach($items as $item)
                            <?php
                             $years_array = unserialize($item->years);
                            ?>
                            @if(in_array($k,$years_array))
                                <h4>
                                    ({{ $n }}) {{ $item->name }}
                                </h4>
                                <?php
                                    $n++;
                                ?>
                                @if(isset($year_students[$k][$item->id]))
                                    @if(isset($year_students[$k][$item->id]['男']))
                                        男子組：<br>
                                        <?php ksort($year_students[$k][$item->id]['男']); ?>
                                        @foreach($year_students[$k][$item->id]['男'] as $k1=>$v1)
                                            {{ $st_number[$k1] }} {{ $v1 }},
                                        @endforeach
                                        <br>
                                        錄取：
                                            @for($i=1;$i<=$item->reward;$i++)
                                                {{ $i }}、__________
                                            @endfor
                                        <br>
                                    @endif
                                    @if(isset($year_students[$k][$item->id]['女']))
                                        女子組：<br>
                                        <?php ksort($year_students[$k][$item->id]['女']); ?>
                                        @foreach($year_students[$k][$item->id]['女'] as $k1=>$v1)
                                            {{ $st_number[$k1] }} {{ $v1 }},
                                        @endforeach
                                        <br>
                                        錄取：
                                            @for($i=1;$i<=$item->reward;$i++)
                                                {{ $i }}、__________
                                            @endfor
                                        <br>
                                    @endif
                                    @if(isset($year_students[$k][$item->id][4]))
                                        @foreach($year_students[$k][$item->id][4] as $k1=>$v1)
                                            {{ $v1 }},
                                        @endforeach
                                        <br>
                                        錄取：
                                        @for($i=1;$i<=$item->reward;$i++)
                                            {{ $i }}、__________
                                        @endfor
                                        <br>
                                    @endif
                                @endif
                                <br>
                            @endif
                        @endforeach
                        <br><br>
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
                location="/sport_meeting/records/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
