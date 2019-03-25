@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改 | 處室管理')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改處室</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">內容處室</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改處室</li>
                </ol>
            </nav>
            @include('layouts.errors')
            {{ Form::model($department,['route' => ['departments.update',$department->id], 'method' => 'PATCH']) }}
            @include('departments.form')
            {{ Form::close() }}
        </div>
    </div>
@endsection
