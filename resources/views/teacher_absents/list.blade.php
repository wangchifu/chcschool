@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <?php
    $active['index'] ="";
    $active['deputy'] ="";
    $active['sir'] ="";
    $active['travel'] ="";
    $active['list'] ="active";
    $active['total'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假：差假列表</h1>
            @include('teacher_absents.nav')
            <br>
            @if(empty($not_admin))
                <span class="text-danger">你沒有管理權</span>
            @else
                {{ Form::select('select_semester',$semesters,$semester,['id'=>'select_semester']) }}
                {{ Form::select('select_teacher',$teachers,$teacher,['id'=>'select_teacher','placeholder'=>'選擇教師']) }}
                {{ Form::select('select_abs',$abses,$abs,['id'=>'select_abs','placeholder'=>'選擇假別']) }}
                {{ Form::select('select_month',$monthes,$month,['id'=>'select_month']) }}
                <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>
                            #
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
                            </td>
                            <td>
                                {{ $user_name[$teacher_absent->user_id] }}<br>
                                <small>{{ substr($teacher_absent->created_at,0,10) }}</small>
                            </td>
                            <td>
                                <small>{{ $abs_kinds[$teacher_absent->abs_kind] }}</small>
                                @if($teacher_absent->abs_kind == 52)
                                    <br><small class="text-primary">{{ $teacher_absent->place }}</small>
                                @endif
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
            @endif
        </div>

    </div>
    <script>
        $('#select_semester').change(function(){
            if(!$('#select_teacher').val()){
                t=0;
            }else{
                t=$('#select_teacher').val();
            }
            if(!$('#select_abs').val()){
                a=0;
            }else{
                a=$('#select_abs').val();
            }
            location= '/teacher_absents/list/'+ $('#select_semester').val()+'/'+ t +'/'+ a +'/'+$('#select_month').val();
        });

        $('#select_teacher').change(function(){
            if(!$('#select_teacher').val()){
                t=0;
            }else{
                t=$('#select_teacher').val();
            }
            if(!$('#select_abs').val()){
                a=0;
            }else{
                a=$('#select_abs').val();
            }
            location= '/teacher_absents/list/'+ $('#select_semester').val()+'/'+ t +'/'+ a +'/'+$('#select_month').val();
        });

        $('#select_abs').change(function(){
            if(!$('#select_teacher').val()){
                t=0;
            }else{
                t=$('#select_teacher').val();
            }
            if(!$('#select_abs').val()){
                a=0;
            }else{
                a=$('#select_abs').val();
            }
            location= '/teacher_absents/list/'+ $('#select_semester').val()+'/'+ t +'/'+ a +'/'+$('#select_month').val();
        });

        $('#select_month').change(function(){
            if(!$('#select_teacher').val()){
                t=0;
            }else{
                t=$('#select_teacher').val();
            }
            if(!$('#select_abs').val()){
                a=0;
            }else{
                a=$('#select_abs').val();
            }
            location= '/teacher_absents/list/'+ $('#select_semester').val()+'/'+ t +'/'+ a +'/'+$('#select_month').val();
        });
    </script>
@endsection
