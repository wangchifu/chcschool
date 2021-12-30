@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改內容</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改內容</li>
                </ol>
            </nav>
            {{ Form::model($content,['route' => ['contents.update',$content->id], 'method' => 'PATCH','id'=>'this_form']) }}
            @include('contents.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
