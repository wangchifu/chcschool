@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="";
    $active['teacher'] ="active";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-導師填報</h1>
            @include('sport_meetings.nav')
            <hr>
            <table class="table table-striped">
                <thead class="table-primary">
                <tr>
                    <th>
                        報名期限
                    </th>
                    <th>
                        名稱
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($actions as $action)
                    <tr>
                        <td>
                            {{ $action->started_at }}<br>
                            {{ $action->stopped_at }}
                        </td>
                        <td>
                            {{ $action->name }}
                            @if($action->disable==1)
                                <span class="text-danger">[已停止報名]</span>
                            @endif
                        </td>
                        <td>
                            <?php                                     
                                $check = [];
                                if(isset($teacher_class[$action->semester]['year']) and $teacher_class[$action->semester]['class']){
                                    if(!empty($teacher_class)){
                                    $check = \App\StudentSign::where('action_id',$action->id)
                                    ->where('student_year',$teacher_class[$action->semester]['year'])
                                    ->where('student_class',$teacher_class[$action->semester]['class'])
                                    ->where('game_type','<>','class')
                                    ->first();
                                }                          
                                }                                       
                            ?>
                            @if(!isset($teacher_class[$action->semester]))
                                非導師
                            @else
                                @if($action->disable==1)
                                    已停止報名
                                @else
                                    @if(empty($check->id))
                                        <a href="{{ route('sport_meeting.sign_up_do',$action->id) }}" class="btn btn-primary">報名</a>
                                    @else
                                        已報過名 <a href="{{ route('sport_meeting.sign_up_show',$action->id) }}" class="btn btn-info">詳細資料...</a>
                                    @endif
                                @endif                                
                            @endif                            
                        </td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
                </tbody>
            </table>  
        </div>
    </div>
@endsection