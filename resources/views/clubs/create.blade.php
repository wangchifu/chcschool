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
                    <h4>新增學期</h4>
                    {{ Form::open(['route' => 'clubs.semester_store', 'method' => 'POST']) }}
                    <div class="form-group">
                        <label for="semester"><strong>學期*</strong><small class="text-primary">(如 1091)</small></label>
                        {{ Form::number('semester',null,['id'=>'semester','class' => 'form-control', 'maxlength'=>'4','placeholder'=>'4碼數字','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="start_date"><strong>開始報名日期*</strong><small class="text-primary">(如 2020-09-20)</small></label>
                        {{ Form::text('start_date',null,['id'=>'start_date','class' => 'form-control', 'maxlength'=>'10','placeholder'=>'10碼','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="stop_date"><strong>結束報名日期(含)*</strong><small class="text-primary">(如 2020-09-30)</small></label>
                        {{ Form::text('stop_date',null,['id'=>'stop_date','class' => 'form-control', 'maxlength'=>'10','placeholder'=>'10碼','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="club_limit"><strong>學生最多可報名幾個*</strong></label>
                        {{ Form::number('club_limit',null,['id'=>'club_limit','class' => 'form-control', 'maxlength'=>'2','placeholder'=>'數字','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clubs.index') }}"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存
                        </button>
                    </div>
                    @include('layouts.errors')
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
@endsection
