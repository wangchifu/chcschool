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
            <h1>午餐系統-特殊處理：逾期教師補訂餐</h1>
            @include('lunches.nav')
            <br>
            @if($admin)
                <div class="card">
                    <div class="card-header">
                        訂餐者資料
                    </div>
                    <div class="card-body">
                        訂餐者：{{ $user->name }}<br>
                        餐期：{{ $lunch_order->name }}
                        <br>
                        備註：<span class="text-danger">{{ $lunch_order->order_ps }}</span>
                    </div>
                </div>
                <hr>
                <form action="{{ route('lunch_specials.late_teacher_store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            1.選擇廠商
                        </div>
                        <div class="card-body">
                            {{ Form::select('lunch_factory_id', $lunch_factory_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            2.選擇取餐地點
                        </div>
                        <div class="card-body">
                            {{ Form::select('lunch_place_id', $lunch_place_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            3.選擇葷素
                        </div>
                        <div class="card-body">
                            {{ Form::select('eat_style', $eat_styles,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            4.訂餐日期
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr>
                                    <th>
                                        訂餐
                                    </th>
                                    <th>
                                        星期
                                    </th>
                                    <th>
                                        供餐日
                                    </th>
                                    <th>
                                        備註
                                    </th>
                                </tr>
                                @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                    <?php
                                    if(get_chinese_weekday($lunch_order_date->order_date)=="星期六"){
                                        $color = "#CCFF99";
                                    }elseif(get_chinese_weekday($lunch_order_date->order_date)=="星期日"){
                                        $color = "#FFB7DD";
                                    }else{
                                        $color = "";
                                    }
                                    ?>
                                    <tr style="background-color: {{ $color }}">
                                        <td>
                                            @if($lunch_order_date->enable)
                                                <input type="checkbox" name="order_date[{{ $lunch_order_date->order_date }}]" value="1" id="enable{{ $lunch_order_date->order_date }}" checked>
                                                <label for="enable{{ $lunch_order_date->order_date }}">訂餐</label>
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            {{ get_chinese_weekday($lunch_order_date->order_date) }}
                                        </td>
                                        <td>
                                            {{ $lunch_order_date->order_date }}
                                        </td>
                                        <td class="text-danger">
                                            {{ $lunch_order_date->date_ps }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？確定後將只能退餐，不能再更改廠商、取餐地點及葷素喔！！')">我要訂餐</button>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="semester" value="{{ $lunch_order->semester }}">
                    <input type="hidden" name="lunch_order_id" value="{{ $lunch_order->id }}">
                </form>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
