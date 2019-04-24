@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <?php

    $active['index'] ="";
    $active['deputy'] ="";
    $active['sir'] ="";
    $active['travel'] ="active";
    $active['travel_print'] ="";
    $active['list'] ="";
    $active['total'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假：差旅費列表</h1>
            @include('teacher_absents.nav')
            <br>
            {{ Form::select('select_semester',$semesters,$semester,['id'=>'select_semester']) }}
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th width="70">
                        #狀況
                    </th>
                    <th>
                        姓名
                    </th>
                    <th>
                        假別
                    </th>
                    <th>
                        事由
                    </th>
                    <th>
                        開始時間<br>
                        結束時間
                    </th>
                    <th>
                        日數時數
                    </th>
                    <th>
                        課務
                    </th>
                    <th>
                        職務代理人
                    </th>
                    <th>
                        單位主管
                    </th>
                    <th>
                        教學組長
                    </th>
                    <th>
                        校長
                    </th>
                    <th>
                        人事主任
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($teacher_absents as $teacher_absent)
                    <tr>
                        <td>
                            <small>
                            {{ $teacher_absent->id }}
                            </small>
                            <br>
                            <small class="text-success">
                                核准
                            </small>
                        </td>
                        <td>
                            {{ $user_name[$teacher_absent->user_id] }}<br>
                            <small>{{ substr($teacher_absent->created_at,0,10) }}</small>
                        </td>
                        <td>
                            <small>{{ $abs_kinds[$teacher_absent->abs_kind] }}</small>
                            <br>
                            <small class="text-primary">{{ $teacher_absent->place }}</small>
                        </td>
                        <td>
                            <small>
                                {{ $teacher_absent->reason }}<br>
                                <span class="text-primary">{{ $teacher_absent->note }}</span>
                            </small>
                            @if($teacher_absent->note_file)
                                <?php $file = $school_code.'&teacher_absent&'.$teacher_absent->id.'&'.$teacher_absent->note_file; ?>
                                <a href="{{ route('openFile',$file) }}" target="_blank">
                                    <img src="{{ asset('images/file.png') }}">
                                </a>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $teacher_absent->start_date }}<br>
                                {{ $teacher_absent->end_date }}
                            </small>
                        </td>
                        <td>
                            @if($teacher_absent->day)
                                {{ $teacher_absent->day }}日
                            @endif
                            @if($teacher_absent->hour)
                                {{ $teacher_absent->hour }}時
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $class_dises[$teacher_absent->class_dis] }}
                            </small>
                            @if($teacher_absent->class_file)
                                <?php $file = $school_code.'&teacher_absent&'.$teacher_absent->id.'&'.$teacher_absent->class_file; ?>
                                <a href="{{ route('openFile',$file) }}" target="_blank">
                                    <img src="{{ asset('images/file.png') }}">
                                </a>
                            @endif
                        </td>
                        <td>
                            {{ $user_name[$teacher_absent->deputy_user_id] }}<br>
                            <small>{{ substr($teacher_absent->deputy_date,0,10) }}</small>
                        </td>
                        <td>
                            {{ $user_name[$teacher_absent->check1_user_id] }}
                            <br>
                            <small>{{ substr($teacher_absent->check1_date,0,10) }}</small>
                        </td>
                        <td>
                            @if(!empty($teacher_absent->check4_date))
                                {{ $user_name[$teacher_absent->check4_user_id] }}
                                <br>
                                <small>{{ substr($teacher_absent->check4_date,0,10) }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $user_name[$teacher_absent->check2_user_id] }}
                            <br>
                            <small>{{ substr($teacher_absent->check2_date,0,10) }}</small>
                        </td>
                        <td>
                            @if(!empty($teacher_absent->check3_date))
                                {{ $user_name[$teacher_absent->check3_user_id] }}
                                <br>
                                <small>{{ substr($teacher_absent->check3_date,0,10) }}</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('#select_semester').change(function(){
            location= '/teacher_absents/travel/'+ $('#select_semester').val();
        });
    </script>
@endsection
