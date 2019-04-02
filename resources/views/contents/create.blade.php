@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '新增內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>新增內容</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增內容</li>
                </ol>
            </nav>
            {{ Form::open(['route' => 'contents.store', 'method' => 'POST','id'=>'this_form']) }}
            @include('contents.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
