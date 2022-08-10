@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-特殊處理')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <?php

    $active['teacher'] ="";
    $active['student'] ="";
    $active['list'] ="";
    $active['special'] ="active";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-特殊處理：修改教師餐期</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_specials.index') }}">特殊處理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改教師餐期</li>
                </ol>
            </nav>
            @if($admin)
                <div class="card">
                    <div class="card-header">
                        教師某一餐期的一些資料錯了！！
                    </div>
                    <div class="card-body">
                        @include('layouts.errors')
                        <form action="{{ route('lunch_specials.teacher_change_month_show') }}" method="post" id="this_form">
                            @csrf
                        <div class="form-group">
                            <label>
                                選擇教職員
                            </label>
                            {{ Form::select('user_id', $user_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <label>
                                選擇餐期
                            </label>
                            {{ Form::select('lunch_order_id', $lunch_order_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm">送出</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
