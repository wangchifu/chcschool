@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假-新增')

@section('content')
    <?php

    $active['index'] ="active";
    $active['deputy'] ="";
    $active['sir'] ="";
    $active['travel'] ="";
    $active['list'] ="";
    $active['total'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假</h1>
            @include('teacher_absents.nav')
            <br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher_absents.index') }}">假單處理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增假單</li>
                </ol>
            </nav>
            @include('layouts.errors')
            {{ Form::open(['route' => 'teacher_absents.store', 'method' => 'POST', 'files' => true,'id'=>'this_form']) }}
            <div class="form-group">
                <label for="semester"><strong>請假學期*</strong></label>
                <input type="text" name="semester" class="form-control" value="{{ get_date_semester(date('Y-m-d')) }}" required maxlength="4">
            </div>
            <div class="form-group">
                <label for="abs_kind"><strong>假別*</strong></label>
                {{ Form::select('abs_kind',$abs_kinds,null,['class'=>'form-control','required'=>'required','placeholder'=>'']) }}
            </div>
            <div class="form-group">
                <label for="place">出差地點<small class="text-secondary">(公差假時填寫)</small></label>
                <input type="text" name="place" class="form-control">
            </div>
            <div class="form-group">
                <label for="reason"><strong>事由*</strong></label>
                <input type="text" name="reason" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="start_date"><strong>開始日期 時間*</strong></label>
                <input type="text" name="start_date" class="form-control" value="{{ date('Y-m-d') }} 08:00" maxlength="16" required>
            </div>
            <div class="form-group">
                <label for="end_date"><strong>結束日期 時間*</strong></label>
                <input type="text" name="end_date" class="form-control" value="{{ date('Y-m-d') }} 17:00" maxlength="16" required>
            </div>
            <div class="form-group">
                <label><strong>共計*</strong></label><br>
                <input type="text" name="day" maxlength="3">日 <input type="text" name="hour" maxlength="2">時
            </div>
            <div class="form-group">
                <label for="class_dis"><strong>課務安排*</strong></label>
                {{ Form::select('class_dis',$class_dises,null,['class'=>'form-control','required'=>'required','placeholder'=>'','id'=>'class_dis']) }}
            </div>
            <div class="form-group" id="class_file_zone">
                <label for="reason">上傳課務銜接單</label><br>
                <input type="file" name="class_file" id="class_file">
            </div>
            <div class="form-group">
                <label><strong>職務代理人*</strong></label>
                {{ Form::select('deputy_user_id',$user_select,null,['class'=>'form-control','required'=>'required','placeholder'=>'']) }}
            </div>
            <div class="form-group">
                <label for="reason">證明文件說明</label>
                <input type="text" name="note" class="form-control">
            </div>
            <div class="form-group">
                <label for="reason">上傳證明文件</label><br>
                <input type="file" name="note_file">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">送出</button>
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="status" value="1">
            {{ Form::close() }}
        </div>

    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
