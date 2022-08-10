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
            <h1>午餐系統-特殊處理：單日供餐統一變更</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_specials.index') }}">特殊處理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">違約廠商處理</li>
                </ol>
            </nav>

            @if($admin)
                <div class="card">
                    <div class="card-header">
                        某廠商違約了，有訂此廠商的分配至其他廠商！
                    </div>
                    <div class="card-body">
                        <form action="{{ route('lunch_specials.bad_factory2') }}" method="post" id="this_form">
                            @csrf
                        一、步驟一：<br>
                        <div class="form-group">
                            <label>1.選擇違約廠商</label>
                            {{ Form::select('bad_factory_id',$factories,null,['class'=>'form-control','required'=>'required']) }}
                        </div>
                        <label>2.從哪日起？</label>
                        <div class="form-group">
                            {{ Form::text('order_date1',null,['id'=>'order_date1','class' => 'form-control','required'=>'required','maxlength'=>'10','width'=>'276']) }}
                            <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                            <script>
                                $('#order_date1').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    format: 'yyyy-mm-dd',
                                    locale: 'zh-TW',
                                });
                            </script>
                        </div>
                        <label>3.到哪日止？</label>
                        <div class="form-group">
                            {{ Form::text('order_date2',null,['id'=>'order_date2','class' => 'form-control','required'=>'required','maxlength'=>'10','width'=>'276']) }}
                            <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                            <script>
                                $('#order_date2').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    format: 'yyyy-mm-dd',
                                    locale: 'zh-TW',
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出至步驟二</button>
                            @include('layouts.errors')
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
