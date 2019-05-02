@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '修改報告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改報告</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">會議列表</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.show',$report->meeting_id) }}">{{ $report->meeting->open_date }} {{ $report->meeting->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改報告</li>
                </ol>
            </nav>
            {{ Form::model($report,['route' => ['meetings_reports.update',$report->id], 'method' => 'PATCH','id'=>'this_form', 'files' => true]) }}
            <div class="card my-4">
                <h3 class="card-header">{{ $report->meeting->open_date }} {{ $report->meeting->name }} 報告資料</h3>
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
                        <br>
                        @if(!empty($files))
                            @foreach($files as $k=>$v)
                                <?php
                                $file = "reports/".$report->id."/".$v;
                                $file = str_replace('/','&',$file);
                                ?>
                                <a href="{{ url('meetings_reports/'.$file.'/fileDel') }}" class="btn btn-danger btn-sm" id="fileDel{{ $k }}" onclick="bbconfirm_Link('fileDel{{ $k }}','確定刪附件？')"><i class="fas fa-times-circle"></i> {{ $v }}</a>
                            @endforeach
                        @endif
                        @if($per < 100)
                            {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
                        @else
                            <span class="text-danger">容量已滿！無法加附件！</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <a href="{{ route('meetings.show',$report->meeting_id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
