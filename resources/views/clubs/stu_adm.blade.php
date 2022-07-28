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
                        <input type="submit" class="btn btn-success btn-sm" value="匯入學生" onclick="return confirm('確定嗎？')">
                        {{ Form::close() }}
                        @include('layouts.errors')
                        <a href="{{ asset('images/cloudschool_club.png') }}" target="_blank">請先至 cloudschool 下載列表</a>
                    @else
                        <span class="text-danger">你不是管理者</span>
                    @endif
                </div>
            </div>
            <br>
            <h4>已匯入學生班級資料</h4>
                <table class="table">
                    <thead class="table-warning">
                    <tr>
                        <th>
                            學期
                        </th>
                        <th>
                            班級數
                        </th>
                        <th>
                            學生數
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                              {{ $semester }}
                            </td>
                            <td>
                                {{ $class_num }} <a href="{{ route('clubs.stu_adm_more',['semester'=>$semester,'student_class_id'=>null]) }}" class="btn btn-info btn-sm">詳細資料</a>
                            </td>
                            <td>
                                {{ $club_student_num }}   
                            </td>
                        </tr>
                    </tbody>
                </table>
            @if($admin)
                <div class="card">
                    <div class="card-header">
                        <h5>
                            學生黑名單列表
                        </h5>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => 'clubs.store_black', 'method' => 'POST']) }}
                        <table>
                            <tr>
                                <td>
                                    被處罰無法選社團的學期
                                </td>
                                <td>
                                    社團類別
                                </td>
                                <td>
                                    學號
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ Form::text('semester',null,['id'=>'semester','class' => 'form-control', 'maxlength'=>'4','required'=>'required']) }}
                                </td>
                                <td>
                                    <select class="form-control" name="class_id" required>
                                        <option></option>
                                        <option value="1">
                                            1.學生特色社團
                                        </option>
                                        <option value="2">
                                            2.學生課後活動
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    {{ Form::text('no',null,['id'=>'no','class' => 'form-control','maxlength'=>'6','required'=>'required']) }}
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定送出嗎？')">
                                        <i class="fas fa-save"></i> 新增送出
                                    </button>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="back_semester" value="{{ $semester }}">
                        {{ Form::close() }}
                        @include('layouts.errors')
                        <hr>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        被處罰的學期
                                    </th>
                                    <th>
                                        類別
                                    </th>
                                    <th>
                                        學生
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($club_blacks as $club_black)
                                    <tr>
                                        <td>
                                            {{ $club_black->semester }}
                                        </td>
                                        <td>
                                            @if($club_black->class_id==1)
                                                1.學生特色社團
                                            @endif
                                            @if($club_black->class_id==2)
                                                2.學生課後活動
                                            @endif
                                        </td>
                                        <td>
                                            學號 {{ $club_black->no }} {{ $club_black->club_student->class_num }}班 {{ $club_black->club_student->name }}
                                            <a href="{{ route('clubs.destroy_black',[$semester,$club_black->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定嗎？')">刪除</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
