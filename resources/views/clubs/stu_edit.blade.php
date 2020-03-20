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
                        <h4>{{ $club_student->semester }} 修改學生</h4>
                        {{ Form::open(['route' => ['clubs.stu_update',$club_student->id], 'method' => 'PATCH']) }}
                        <div class="form-group">
                            <label for="no"><strong>學號*</strong><small class="text-primary">(6碼 如 108001)</small></label>
                            {{ Form::text('no',$club_student->no,['id'=>'no','class' => 'form-control','maxlength'=>'6','required'=>'required','readonly'=>'readonly']) }}
                        </div>
                        <div class="form-group">
                            <label for="name"><strong>姓名*</strong></label>
                            {{ Form::text('name',$club_student->name,['id'=>'name','class' => 'form-control','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <label for="class_num"><strong>班級座號(帳號)*</strong><small class="text-primary">(5碼 如 10101)</small></label>
                            {{ Form::text('class_num',$club_student->class_num,['id'=>'class_num','class' => 'form-control','maxlength'=>'5','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <label for="pwd"><strong>密碼*</strong></label>
                            {{ Form::text('pwd',$club_student->pwd,['id'=>'pwd','class' => 'form-control','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <label for="birthday"><strong>生日*</strong><small class="text-primary">(8碼 如 20130101)</small></label>
                            {{ Form::text('birthday',$club_student->birthday,['id'=>'birthday','class' => 'form-control','maxlength'=>'8','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <label for="parents_telephone">家長電話</label>
                            {{ Form::text('parents_telephone',$club_student->parents_telephone,['id'=>'parents_telephone','class' => 'form-control','required'=>'required']) }}
                        </div>
                        <a class="btn btn-secondary btn-sm" href="{{ route('clubs.stu_adm',$club_student->semester) }}"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存
                        </button>
                        @include('layouts.errors')
                        {{ Form::close() }}
                    @else
                        <span class="text-danger">你不是管理者</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
