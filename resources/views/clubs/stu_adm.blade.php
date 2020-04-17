@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>社團報名</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('clubs.index') }}">學期設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.setup') }}">社團設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.report') }}">報表輸出</a>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <?php
                        $admin = check_power('社團報名','A',auth()->user()->id);
                    ?>
                    @if($admin)
                        <h4>學生管理</h4>
                        {{ Form::open(['route' => ['clubs.stu_import',$semester], 'method' => 'POST', 'files' => true]) }}
                        <input type="file" name="file" required>
                        <input type="submit" class="btn btn-success btn-sm" value="匯入學生" onclick="return confirm('會先清空學生，也會清空這學期已報名的資料喔！')">
                        {{ Form::close() }}
                        <a href="{{ asset('images/cloudschool_club.png') }}" target="_blank">請先至 cloudschool 下載列表</a>
                    @else
                        <span class="text-danger">你不是管理者</span>
                    @endif
                </div>
            </div>
            <br>
            @if($admin)
                <div class="card">
                <div class="card-header">
                    <h5>
                        {{ $semester }} 學生列表
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('clubs.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                    <a href="{{ route('clubs.stu_create',$semester) }}" class="btn btn-success btn-sm">新增學生</a>
                    <table class="table table-hover">
                        <tr>
                            <th>
                                序
                            </th>
                            <th>
                                學號
                            </th>
                            <th>
                                班級座號(帳號)
                            </th>
                            <th>
                                密碼
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                生日
                            </th>
                            <th>
                                家長電話
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        <?php $i=1; ?>
                        @foreach($club_students as $club_student)
                            <tr>
                                <td>
                                    {{ $i }}
                                </td>
                                <td>
                                    {{ $club_student->no }}
                                </td>
                                <td>
                                    {{ $club_student->class_num }}
                                </td>
                                <td>
                                    {{ $club_student->pwd }}
                                </td>
                                <td>
                                    {{ $club_student->name }}
                                </td>
                                <td>
                                    {{ $club_student->birthday }}
                                </td>
                                <td>
                                    {{ $club_student->parents_telephone }}
                                </td>
                                <td>
                                    <a href="{{ route('clubs.stu_edit',$club_student->id) }}" class="btn btn-primary btn-sm">編輯</a>
                                    <a href="{{ route('clubs.stu_delete',$club_student->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？')">刪除</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
