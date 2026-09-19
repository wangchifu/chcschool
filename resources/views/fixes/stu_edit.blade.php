@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 |')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>報修系統 | 編輯學生</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.index') }}">報修列表</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.stu_adm',$semester) }}">學生管理</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.stu_adm_more',$semester) }}">學生列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">編輯學生</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">                                        
                        <h4>{{ $club_student->semester }}學期 修改 {{ $sc }}班 學生</h4>
                        {{ Form::open(['route' => ['fixes.stu_update',$club_student->id], 'method' => 'PATCH']) }}
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
                        <a class="btn btn-secondary btn-sm" href="#" onclick="history.go(-1)"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存
                        </button>
                        @include('layouts.errors')
                        <input type="hidden" name="student_class_id" value={{ $student_class_id }}>
                        {{ Form::close() }}                    
                </div>
            </div>
        </div>
    </div>
@endsection
