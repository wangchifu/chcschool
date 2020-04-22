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
                        <h4>學期列表</h4>
                        <a href="{{ route('clubs.semester_create') }}" class="btn btn-success btn-sm">新增學期</a>
                        <table class="table table-striped">
                            <tr>
                                <th>
                                    學期
                                </th>
                                <th>
                                    特色社團開始
                                </th>
                                <th>
                                    特色社團結束
                                </th>
                                <th>
                                    課後活動開始
                                </th>
                                <th>
                                    課後活動結束
                                </th>
                                <th>
                                    最多可報
                                </th>
                                <th>
                                    動作
                                </th>
                            </tr>
                            @foreach($club_semesters as $club_semester)
                            <tr>
                                <td>
                                    {{ $club_semester->semester }}
                                </td>
                                <td>
                                    {{ $club_semester->start_date }}
                                </td>
                                <td>
                                    {{ $club_semester->stop_date }}
                                </td>
                                <td>
                                    {{ $club_semester->start_date2 }}
                                </td>
                                <td>
                                    {{ $club_semester->stop_date2 }}
                                </td>
                                <td>
                                    {{ $club_semester->club_limit }}
                                </td>
                                <td>
                                    <a href="{{ route('clubs.stu_adm',$club_semester->semester) }}" class="btn btn-warning btn-sm">學生管理</a>
                                    <a href="{{ route('clubs.semester_edit',$club_semester->id) }}" class="btn btn-primary btn-sm">編輯</a>
                                    <a href="{{ route('clubs.semester_delete',$club_semester->semester) }}" class="btn btn-danger btn-sm" onclick="return confirm('底下所有的資料都會清除喔！')">刪除</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    @else
                        <span class="text-danger">你不是管理者</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
