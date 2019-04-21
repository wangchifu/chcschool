@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <?php

    $active['index'] ="active";
    $active['deputy'] ="";
    $active['list'] ="";
    $active['total'] ="";
    $active['admin'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假</h1>
            @include('teacher_absents.nav')
            <br>
            <a href="{{ route('teacher_absents.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增假單</a>
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>
                        狀態
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
                            @if($teacher_absent->status==1)
                                <small>送核</small>
                            @endif
                        </td>
                        <td>
                            {{ $user_name[$teacher_absent->user_id] }}<br>
                            <small>{{ substr($teacher_absent->created_at,0,10) }}</small>
                        </td>
                        <td>
                            {{ $abs_kinds[$teacher_absent->abs_kind] }}
                        </td>
                        <td>
                            {{ $teacher_absent->reason }}
                        </td>
                        <td>
                            {{ $teacher_absent->start_date }}<br>
                            {{ $teacher_absent->end_date }}
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
                            {{ $class_dises[$teacher_absent->class_dis] }}
                        </td>
                        <td>
                            {{ $user_name[$teacher_absent->deputy_user_id] }}<br>
                            @if(empty($teacher_absent->deputy_date))
                                <small class="text-danger">尚未同意</small>
                            @endif
                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
