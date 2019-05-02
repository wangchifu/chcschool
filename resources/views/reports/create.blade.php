@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '新增報告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>新增報告</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">會議列表</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.show',$meeting->id) }}">{{ $meeting->open_date }} {{ $meeting->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增報告</li>
                </ol>
            </nav>
            {{ Form::open(['route' => 'meetings_reports.store', 'method' => 'POST','id'=>'this_form', 'files' => true]) }}
            <div class="card my-4">
                <h3 class="card-header">{{ $meeting->open_date }} {{ $meeting->name }} 報告資料</h3>
                <div class="card-body">
                    @include('layouts.errors')
                    <div class="form-group">
                        <label for="job_title"><strong>職稱*</strong></label>
                        {{ Form::text('job_title',auth()->user()->title,['id'=>'job_title','class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="content"><strong>內容*</strong></label>
                        {{ Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control', 'rows' => 10, 'placeholder' => '請輸入內容','required'=>'required']) }}
                    </div>
                    @include('layouts.hd')
                    <div class="form-group">
                        <label for="files[]">( 不大於5MB )</label>
                        @if($per < 100)
                            {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
                        @else
                            <br>
                            <span class="text-danger">容量已滿！無法加附件！</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <a href="{{ route('meetings.show',$meeting->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
