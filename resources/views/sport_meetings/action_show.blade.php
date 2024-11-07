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
                            @foreach($items as $item)
                                <td>
                                    {{ $item->name }}
                                </td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student_classes as $student_class)
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
                                    ?>
                                    <td>
                                        @if(in_array($student_class->student_year,$years_array) and count($student_signs)==0 and $item->game_type !="class")
                                            未報名
                                        @endif
                                        @if(in_array($student_class->student_year,$years_array) and count($student_signs)==0 and $item->game_type =="class")
                                            班際賽
                                        @endif
                                        @if(!in_array($student_class->student_year,$years_array))
                                            --
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
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('sport_meeting.action') }}" class="btn btn-secondary btn-sm">返回</a>
                  @endif
                </div>
              </div>        
        </div>        
    </div>    
@endsection
