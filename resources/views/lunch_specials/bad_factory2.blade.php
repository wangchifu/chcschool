@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-特殊處理')

@section('content')
    <?php

    $active['teacher'] ="";
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
                        <span class="text-danger">要將 {{ $bad_factory }} 從 {{ $order_date1 }} 到 {{ $order_date2 }} 之間的訂餐分配</span><br>
                        <form action="{{ route('lunch_specials.bad_factory3') }}" method="post" id="this_form">
                            @csrf
                        二、步驟二：<br>
                        <div class="form-group">
                            <label>4.選擇分給哪家廠商</label>
                            {{ Form::select('good_factory_id',$factories,null,['class'=>'form-control','required'=>'required']) }}
                        </div>
                        <label>5.選擇教職員</label>
                        <div class="form-group">
                            {{ Form::select('teas[]',$teas,null,['id'=>'order_date','class' => 'form-control','required'=>'required', 'multiple'=>'multiple','size'=>'10']) }}
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出儲存</button>
                            @include('layouts.errors')
                        </div>
                            <input type="hidden" name="bad_factory_id" value="{{ $bad_factory_id }}">
                            <input type="hidden" name="order_date1" value="{{ $order_date1 }}">
                            <input type="hidden" name="order_date2" value="{{ $order_date2 }}">
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
