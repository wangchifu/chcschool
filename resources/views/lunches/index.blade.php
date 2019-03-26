@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統')

@section('content')
    <?php

    $active['teacher'] ="active";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統</h1>
            @include('lunches.nav')
            <br>
            <form name=myform>
            <div class="form-control">
                {{ Form::select('lunch_order_id', $lunch_order_array,$lunch_order_id, ['class' => 'form-control','placeholder'=>'--請選擇--','onchange'=>'jump()']) }}
            </div>
            </form>
            @if($lunch_order_id)
                @if(!$has_order)
                    @if(date('Ymd') > $month_die_date and $teacher_open==null)
                        <br>
                        <h3 class="text-danger">已逾期！</h3>
                        <h4>最慢訂餐日為{{ $month_die_date }}</h4>
                    @else
                        @if($lunch_order->order_ps)
                        <div class="card">
                            <div class="card-body">
                                備註：<span class="text-danger">{{ $lunch_order->order_ps }}</span>
                            </div>
                        </div>
                        @endif
                        <hr>
                        <form action="{{ route('lunches.store') }}" method="post">
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
                            <input type="hidden" name="semester" value="{{ $lunch_order->semester }}">
                            <input type="hidden" name="lunch_order_id" value="{{ $lunch_order->id }}">
                        </form>
                    @endif
                @else
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            訂餐資訊
                        </div>
                        <div class="card-body">
                            <?php
                                $lunch_tea_dates = \App\LunchTeaDate::where('lunch_order_id',$lunch_order->id)
                                    ->where('user_id',auth()->user()->id)
                                    ->get();
                                foreach($lunch_tea_dates as $lunch_tea_date){
                                    $tea_data[$lunch_tea_date->order_date] = $lunch_tea_date->enable;
                                }
                                $days = \App\LunchTeaDate::where('lunch_order_id',$lunch_order->id)
                                    ->where('user_id',auth()->user()->id)
                                    ->where('enable','eat')
                                    ->count();
                                $factory = $lunch_tea_dates[0]->lunch_factory;
                                $place = $lunch_tea_dates[0]->lunch_place;
                                $eat_style = $lunch_tea_dates[0]->eat_style;
                            ?>
                            <table class="table table-striped">
                                <tr>
                                    <td>
                                        <label>1.廠商</label>
                                        <h4>{{ $factory->name }}</h4>
                                    </td>
                                    <td>
                                        <label>2.取餐地點</label>
                                        <h4>{{ $place->name }}</h4>
                                    </td>
                                    <td>
                                        <label>3.葷素</label>
                                        @if($eat_style==1)
                                            <h4 class="text-danger">葷食</h4>
                                        @else
                                            <h4 class="text-success">素食</h4>
                                        @endif
                                    </td>
                                    <td>
                                        <label>本期訂餐費用</label>
                                        <h4>{{ $factory->teacher_money*$days }} 元</h4>
                                    </td>
                                </tr>
                            </table>
                            <div class="card">
                                <div class="card-header">
                                    4.訂餐日期
                                </div>
                                <div class="card-body">
                                    @if($disable==1)

                                    @else
                                        <h5 class="text-danger">{{ $die_date }} 前之訂餐日不得更動！</h5>
                                        <form action="{{ route('lunches.update') }}" method="post">
                                    @endif
                                        @csrf
                                        @method('patch')
                                    <table class="table table-striped">
                                        <tr>
                                            <th>
                                                已訂餐 {{ $days }} 天
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
                                            $checked = ($tea_data[$lunch_order_date->order_date]=="eat")?"checked":"";
                                            $false = (str_replace('-','',$lunch_order_date->order_date) < $die_date)?"return false;":"";

                                            if($disable) $false="return false;";
                                            ?>
                                            <tr style="background-color: {{ $color }}">
                                                <td>
                                                    @if($lunch_order_date->enable)
                                                        <input type="checkbox" name="order_date[{{ $lunch_order_date->order_date }}]" value="1" id="enable{{ $lunch_order_date->order_date }}" {{ $checked }} onclick="{{ $false }}">
                                                        @if(str_replace('-','',$lunch_order_date->order_date) < $die_date)
                                                            <label for="enable{{ $lunch_order_date->order_date }}">
                                                                <del>訂餐</del>
                                                            </label>
                                                        @else
                                                            <label for="enable{{ $lunch_order_date->order_date }}">
                                                                訂餐
                                                            </label>
                                                        @endif

                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ get_chinese_weekday($lunch_order_date->order_date) }}
                                                </td>
                                                <td>
                                                    @if(str_replace('-','',$lunch_order_date->order_date) < $die_date)
                                                        <del>{{ $lunch_order_date->order_date }}</del>
                                                    @else
                                                        {{ $lunch_order_date->order_date }}
                                                    @endif
                                                </td>
                                                <td class="text-danger">
                                                    {{ $lunch_order_date->date_ps }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <input type="hidden" name="lunch_order_id" value="{{ $lunch_order->id }}">
                                    @if($disable==1)
                                        <h4 class="text-danger">系統已設定停止退餐</h4>
                                    @else
                                        <button class="btn btn-success btn-sm" onclick="return confirm('確定修改嗎？')">我要修改</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

    </div>
    <script language='JavaScript'>

        function jump(){
            if(document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value!=''){
                location="/lunches/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value;
            }
        }
    </script>
@endsection
