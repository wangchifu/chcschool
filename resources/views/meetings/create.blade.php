@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '新增會議 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>新增會議</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">會議列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增會議</li>
                </ol>
            </nav>
            @include('layouts.errors')
            <?php
            $default_date = date('Y-m-d');
            $default_name="教師晨會";
            ?>
            {{ Form::open(['route' => 'meetings.store', 'method' => 'POST','id'=>'this_form']) }}
            @include('meetings.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
