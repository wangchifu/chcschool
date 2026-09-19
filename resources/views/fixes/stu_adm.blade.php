@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 |')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>報修系統 | 學生管理</h1>    
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.index') }}">報修列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">學生管理</li>
                </ol>
            </nav>        
            <div class="card">
                <div class="card-body">                                        
                    <h4>學生管理</h4>
                    {{ Form::open(['route' => ['fixes.stu_import',$semester], 'method' => 'POST', 'files' => true]) }}
                    <input type="file" name="file" required>
                    <input type="submit" class="btn btn-success btn-sm" value="匯入學生" onclick="return confirm('確定嗎？')">
                    {{ Form::close() }}
                    @include('layouts.errors')
                    <a href="{{ asset('images/cloudschool_club.png') }}" target="_blank">請先至 cloudschool 下載列表</a>                    
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
                                @if($class_num > 0)
                                    {{ $class_num }} <a href="{{ route('fixes.stu_adm_more',['semester'=>$semester,'student_class_id'=>null]) }}" class="btn btn-info btn-sm">詳細資料</a>
                                @endif
                            </td>
                            <td>
                                {{ $club_student_num }}   
                            </td>
                        </tr>
                    </tbody>
                </table>            
        </div>
    </div>
@endsection
