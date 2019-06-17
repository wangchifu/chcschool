@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <?php

    $active['index'] ="active";
    $active['deputy'] ="";
    $active['sir'] ="";
    $active['travel'] ="";
    $active['list'] ="";
    $active['total'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假</h1>
            @include('teacher_absents.nav')
            <br>
            @if(auth()->user()->username != "admin")
                <a href="{{ route('teacher_absents.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增假單</a>
            @endif
            {{ Form::select('select_semester',$semesters,$semester,['id'=>'select_semester']) }}
            小計：事假({{ $abs_kind_total[11] }}) 家庭照顧假({{ $abs_kind_total[12] }}) 病假({{ $abs_kind_total[21] }}) 生理假({{ $abs_kind_total[22] }}) 休假({{ $abs_kind_total[81] }})
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
                            @if($teacher_absent->status==1 or $teacher_absent->status==3)
                                <small>
                                    @if(empty($teacher_absent->check1_date))
                                        <a href="{{ route('teacher_absents.edit',$teacher_absent->id) }}"><i class="fas fa-edit text-primary"></i></a>
                                        <a href="{{ route('teacher_absents.destroy',$teacher_absent->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                                        <br>
                                    @endif
                                    @if($teacher_absent->status==1)
                                        送核
                                    @endif
                                    @if($teacher_absent->status==3)
                                        <span class="text-danger">退回</span>
                                    @endif
                                </small>
                            @endif
                            @if($teacher_absent->status==2)
                                <small class="text-success">
                                    核准
                                </small>
                            @endif
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
                            @if(empty($teacher_absent->deputy_date))
                                <small class="text-danger">尚未同意</small>
                            @else
                                <small>{{ substr($teacher_absent->deputy_date,0,10) }}</small>
                            @endif
                        </td>
                        <td>
                            @if(!empty($teacher_absent->check1_date))
                                {{ $user_name[$teacher_absent->check1_user_id] }}
                                <br>
                                <small>{{ substr($teacher_absent->check1_date,0,10) }}</small>
                            @endif
                        </td>
                        <td>
                            @if(!empty($teacher_absent->check4_date))
                                {{ $user_name[$teacher_absent->check4_user_id] }}
                                <br>
                                <small>{{ substr($teacher_absent->check4_date,0,10) }}</small>
                            @endif
                        </td>
                        <td>
                            @if(!empty($teacher_absent->check2_date))
                                {{ $user_name[$teacher_absent->check2_user_id] }}
                                <br>
                                <small>{{ substr($teacher_absent->check2_date,0,10) }}</small>
                            @endif
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
            {{ $teacher_absents->links() }}
        </div>

    </div>
    <script>
        $('#select_semester').change(function(){
            location= '/teacher_absents/index/'+ $('#select_semester').val();
        });
    </script>
@endsection
