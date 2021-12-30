@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改介紹 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改介紹</h1>
            {{ Form::model($department,['route' => ['departments.exec_update',$department->id], 'method' => 'PATCH','id'=>'this_form']) }}
            @include('departments.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
