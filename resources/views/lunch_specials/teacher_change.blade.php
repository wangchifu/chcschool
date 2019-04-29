@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-特殊處理')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <?php

    $active['teacher'] ="";
    $active['list'] ="";
    $active['special'] ="active";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-特殊處理：單日教師退訂餐</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_specials.index') }}">特殊處理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">單日教師退訂餐</li>
                </ol>
            </nav>
            @if($admin)
                <div class="card">
                    <div class="card-header">
                        教師某日忘了退訂！幫忙他退餐！
                    </div>
                    <div class="card-body">
                        @include('layouts.errors')
                        <form action="{{ route('lunch_specials.teacher_change_store') }}" method="post" id="this_form">
                            @csrf
                        <div class="form-group">
                            <label>
                                1.選擇教職員
                            </label>
                            {{ Form::select('user_id', $user_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <label>2.欲變更的日期</label>
                            {{ Form::text('order_date',null,['id'=>'order_date','class' => 'form-control','required'=>'required','maxlength'=>'10','width'=>'276']) }}
                            <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                            <script>
                                $('#order_date').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    format: 'yyyy-mm-dd',
                                    locale: 'zh-TW',
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label>3.變更選項</label>
                            <select name="action" class="form-control" required>
                                <option value="">--請選擇--</option>
                                <option value="eat">訂餐</option>
                                <option value="not_eat">不訂餐</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出變更</button>
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
