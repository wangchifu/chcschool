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
            <h1>午餐系統-特殊處理：逾期教師補訂餐</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_specials.index') }}">特殊處理</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_specials.late_teacher') }}">逾期教師補訂餐</a></li>
                    <li class="breadcrumb-item active" aria-current="page">處理中...</li>
                </ol>
            </nav>
            <br>
            @if($admin)
                <div class="card">
                    <div class="card-header">
                        訂餐者資料
                    </div>
                    <div class="card-body">
                        <h2>
                            訂餐者：{{ $user->name }}
                        </h2>
                        <h3>
                            餐期：{{ $lunch_order->name }}
                        </h3>
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
                                        {{ Form::select('lunch_place_id', $lunch_place_array,null, ['id'=>'place_select','class' => 'form-control','placeholder'=>'--請選擇地點--','required'=>'required']) }}
                                    </td>
                                    <td>
                                        <input type="text" name="class_no" id="place_class" maxlength="4" class="form-control" style="display: none;" placeholder="班級代號" required value="1">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            3.選擇供餐別
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
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-danger">日</th>
                                    <th>一</th>
                                    <th>二</th>
                                    <th>三</th>
                                    <th>四</th>
                                    <th>五</th>
                                    <th class="text-success">六</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $first_w = get_date_w($lunch_order->name."-01");
                                ?>
                                <tr>
                                    @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                        <?php
                                        $this_date_w = get_date_w($lunch_order_date->order_date);
                                        if($this_date_w==0){
                                            $text_color = "btn btn-danger btn-sm";
                                        }elseif($this_date_w==6){
                                            $text_color = "btn btn-success btn-sm";

                                        }else{
                                            $text_color = "btn btn-outline-dark btn-sm";

                                        }
                                        $d = explode('-',$lunch_order_date->order_date);
                                        ?>
                                        @if($d[2]== "01")
                                            @for($i=1;$i<=$first_w;$i++)
                                                <td></td>
                                            @endfor
                                        @endif
                                        <td>
                                            @if($lunch_order_date->enable)
                                                <input type="checkbox" name="order_date[{{ $lunch_order_date->order_date }}]" value="1" id="enable{{ $lunch_order_date->order_date }}" checked>
                                                訂餐
                                            @else
                                                --
                                            @endif
                                            <label for="enable{{ $lunch_order_date->order_date }}" class="{{ $text_color }}">{{ substr($lunch_order_date->order_date,5,5) }}</label>
                                            <br>
                                            <small>{{ $lunch_order_date->date_ps }}</small>
                                        </td>
                                        @if($this_date_w == 6)
                                </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>




                            <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？確定要幫別人訂餐嗎？！！')">幫忙訂餐</button>
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
    <script language='JavaScript'>

        function jump(){
            if(document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value!=''){
                location="/lunches/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value;
            }
        }

        $('#s1').click(function(){
            $('#place_class').hide();
            $('#place_select').show();
            $('#place_class').val('1');
        });
        $('#s2').click(function(){
            $('#place_class').show();
            $('#place_select').hide();
            $('#place_class').val('');
            $('#place_select').val('1');
        });


        var validator = $("#store_form").validate();

    </script>
    <br>
    <br>
@endsection
