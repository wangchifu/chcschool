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
              <h2>
                {{ $year }}年級-{{ $item->name }}
                @if($sex <> 4)
                    {{ $sex }}子組
                @endif
            </h2>
            <form method="post" action="{{ route('sport_meeting.score_input_update') }}">
                @csrf
                <table class="table table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th width="100">
                            排序
                        </th>
                        <th>
                            學生(班級)
                        </th>
                        <th>
                            成績
                        </th>
                        <th>
                            名次                                    
                            <input type="checkbox"  name="checkbox" id="itemB{{ $year }}"> <label for="itemB{{ $year }}">自動排名</label>                                    
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
                                    <input type="number" class="form-control" value="{{ $v['order'] }}" name="order[{{ $v['id'] }}]">
                                </td>
                                <td>
                                    {!! $img !!}{{ $v['number'] }} {{ $v['name'] }}
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="{{ $v['achievement'] }}" name="achievement[{{ $v['id'] }}]">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['ranking'] }}" name="ranking[{{ $v['id'] }}]" placeholder="自動排名時可不填">
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
                                    <input type="number" class="form-control" value="{{ $v1['order'] }}" name="order[{{ $v1['id'] }}]">
                                </td>
                                <td>
                                    {!! $img !!}{{ $names }}
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="{{ $v1['achievement'] }}" name="achievement[{{ $v1['id'] }}]">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v1['ranking'] }}" name="ranking[{{ $v1['id'] }}]" placeholder="自動排名時可不填">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if($item->game_type=="class")
                        @foreach($student_array as $k=>$v)
                            <tr>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['order'] }}" name="order[{{ $k }}]">
                                </td>
                                <td>
                                    {!! $img !!}{{ $v['name'] }}
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="{{ $v['achievement'] }}" name="achievement[{{ $k }}]">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $v['ranking'] }}" name="ranking[{{ $k }}]" placeholder="自動排名時可不填">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <input type="hidden" name="action_id" value="{{ $action->id }}">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <a href="{{ route('sport_meeting.score_input') }}" class="btn btn-secondary btn-sm">返回</a>
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出{{ $year }}年級@if($sex <>4){{ $sex }}子組@endif成績</button>                
                @if(is_file(storage_path('app/public').'/demo.odt'))
                    <a href="{{ route('sport_meeting.score_input_print',['action'=>$action->id,'item'=>$item->id,'year'=>$year,'sex'=>$sex]) }}" class="btn btn-success btn-sm"><i class="fas fa-print"></i> 列印獎狀
                    @if($item->game_type == "group")
                        (團體)
                    @endif
                    </a>
                    @if($item->game_type == "group")
                        <a href="{{ route('sport_meeting.score_input_print2',['action'=>$action->id,'item'=>$item->id,'year'=>$year,'sex'=>$sex]) }}" class="btn btn-success btn-sm"><i class="fas fa-print"></i> 列印獎狀 (個人)</a>
                    @endif
                @else
                    <a href="{{ route('sport_meeting.score_print') }}" class="btn btn-danger btn-sm">請先上傳獎狀範本，即可列印獎狀</a>
                @endif
            </form>    
        </div>

    </div>
@endsection
