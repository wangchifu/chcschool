@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '新增連結 | ')

@section('in_head')
    <script src="{{ asset('js/jquery.slim.min.js') }}"></script>
    <link href="{{ asset('fontawesome-icon-browser-picker/fontawesome-browser.css') }}" rel='stylesheet' type='text/css' />
    <script src="{{ asset('fontawesome-icon-browser-picker/fontawesome-browser.js') }}" type='text/javascript'></script> 
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>新增連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('links.index') }}">選單連結</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增連結</li>
                </ol>
            </nav>
            @include('layouts.errors')
            {{ Form::open(['route' => 'links.store', 'method' => 'POST','id'=>'this_form']) }}
            @include('links.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
