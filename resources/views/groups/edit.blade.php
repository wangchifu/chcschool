@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '編輯群組 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1><i class="fas fa-users"></i> 編輯群組</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">群組管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">編輯群組</li>
                </ol>
            </nav>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    {{ Form::model($group,['route' => ['groups.update',$group->id], 'method' => 'PATCH','id'=>'this_form']) }}
                    @include('groups.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
