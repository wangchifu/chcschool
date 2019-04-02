@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改介紹 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改介紹</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">介紹列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改介紹</li>
                </ol>
            </nav>
            {{ Form::model($department,['route' => ['departments.update',$department->id], 'method' => 'PATCH','id'=>'this_form']) }}
            @include('departments.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
