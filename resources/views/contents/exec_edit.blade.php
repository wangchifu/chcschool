@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改內容</h1>
            {{ Form::model($content,['route' => ['contents.exec_update',$content->id], 'method' => 'PATCH','id'=>'this_form']) }}
            @include('contents.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
