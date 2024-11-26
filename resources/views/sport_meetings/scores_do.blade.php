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
            <h1>運動會報名-成績記錄表(檢錄)</h1>
            @include('sport_meetings.nav')   
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.list') }}">1.註冊選手名單</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.records') }}">2.項目記錄表</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.scores') }}">3.成績記錄表(檢錄)</a>
                </li>
              </ul>            
              <hr>              
              <h2>
                {{ $year }}年級-{{ $item->name }}
                @if($sex <> 4)
                    {{ $sex }}子組
                @endif
            </h2>
            <form method="post" action="{{ route('sport_meeting.scores_update') }}">
                @csrf
                <table class="table table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th width="100">
                            組別
                        </th>
                        <th width="100">
                            順序(道次)
                        </th>
                        <th>
                            學生(班級)
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($sex=="男") $img = "<img src='".asset('images/boy.gif')."'>";
                        if($sex=="女") $img = "<img src='".asset('images/girl.gif')."'>";
                        if($sex=="4") $img = "<img src='".asset('images/boy.gif')."'><img src='".asset('images/girl.gif')."'>";
                    ?>
                    @if($item->game_type=="personal")
                        @foreach($student_array as $k=>$v)
                            <tr>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['section_num'] }}" name="section_num[{{ $v['id'] }}]" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['run_num'] }}" name="run_num[{{ $v['id'] }}]" required>
                                </td>
                                <td>
                                    {!! $img !!}{{ $v['number'] }} {{ $v['name'] }}({{ $v['class'] }})
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if($item->game_type=="group")
                        @foreach($student_array as $k=>$v)
                            <?php $names = ""; ?>
                            @foreach($v as $k1=>$v1)
                                <?php
                                    $note = ($v1['is_official'])?"(正)":"(候)";
                                    $names .= $v1['number'].$v1['name'].$note.',';
                                ?>
                            @endforeach
                            <tr>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v1['section_num'] }}" name="section_num[{{ $v1['id'] }}]" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v1['run_num'] }}" name="run_num[{{ $v1['id'] }}]" required>
                                </td>
                                <td>
                                    {!! $img !!}{{ $names }} ({{ $v1['class'] }})
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if($item->game_type=="class")
                        @foreach($student_array as $k=>$v)
                            <tr>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['section_num'] }}" name="section_num[{{ $k }}]" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['run_num'] }}" name="run_num[{{ $k }}]" required>
                                </td>
                                <td>
                                    {!! $img !!}{{ $v['name'] }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <input type="hidden" name="action_id" value="{{ $action->id }}">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <a href="{{ route('sport_meeting.scores') }}" class="btn btn-secondary btn-sm">返回</a>
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出{{ $year }}年級@if($sex <>4){{ $sex }}子組@endif</button>                
                <a href="{{ route('sport_meeting.scores_print',['action'=>$action->id,'item'=>$item->id,'year'=>$year,'sex'=>$sex]) }}" class="btn btn-success btn-sm"><i class="fas fa-print"></i> 列印記錄單(檢錄表)</a>
            </form>    
        </div>

    </div>
@endsection
