@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-特殊處理 | ')

@section('content')
<script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
<link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
<?php

$active['teacher'] = "";
$active['student'] = "";
$active['list'] = "";
$active['special'] = "active";
$active['order'] = "";
$active['setup'] = "";
?>
<div class="row justify-content-center">
    <div class="col-md-11">
        <h1>午餐系統-特殊處理：教師餐期變更</h1>
        @include('lunches.nav')
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lunch_specials.index') }}">特殊處理</a></li>
                <li class="breadcrumb-item active" aria-current="page">教師餐期變更</li>
            </ol>
        </nav>
        @if($admin)
        <div class="card">
            <div class="card-header">
                教師某一餐期的一些資料錯了！！
            </div>
            <div class="card-body">
                @include('layouts.errors')
                <form action="{{ route('lunch_specials.teacher_update_month') }}" method="post" id="this_form">
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
                        <label>
                            選擇廠商
                        </label>
                        {{ Form::select('lunch_factory_id', $lunch_factory_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label>
                            選擇地點
                        </label>
                        <table>
                            <tr>
                                <td>
                                    <input type="radio" name="select_place" id="s1" checked value="place_select"> <label for="s1">指定地點　　　　　　</label>
                                </td>
                                <td>
                                    <input type="radio" name="select_place" id="s2" value="place_class"> <label for="s2">班級教室</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ Form::select('lunch_place_id', $lunch_place_array,null, ['id'=>'place_select','class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                                </td>
                                <td>
                                    <input type="text" name="class_no" id="place_class" maxlength="3" class="form-control" style="display: none;" placeholder="三碼班級代號" required value="1">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>選擇葷素</label>
                        {{ Form::select('eat_style', $eat_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">送出</button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <h1 class="text-danger">你不是管理者</h1>
        @endif
    </div>
</div>
<script language='JavaScript'>
    $('#s1').click(function() {
        $('#place_class').hide();
        $('#place_select').show();
        $('#place_class').val('1');
    });
    $('#s2').click(function() {
        $('#place_class').show();
        $('#place_select').hide();
        $('#place_class').val('');
        $('#place_select').val('1');
    });

    var validator = $("#this_form").validate();
</script>
@endsection