@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <?php

    $active['teacher'] ="";
    $active['student'] ="";
    $active['list'] ="active";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-報表輸出：分期記錄</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.index') }}">報表輸出</a></li>
                    <li class="breadcrumb-item active" aria-current="page">分期記錄</li>
                </ol>
            </nav>
            @if($admin)
                <form name=myform>
                    <div style="margin: 20px">
                        {{ Form::select('lunch_order_id', $lunch_order_array,$lunch_order_id, ['class' => 'form-control','placeholder'=>'--請選擇--','onchange'=>'jump()']) }}
                    </div>
                </form>
                @if($lunch_order_id)
                    <br>
                    <a href="{{ route('lunch_lists.call_money',$lunch_order_id) }}" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> 本期收費通知</a>
                    <a href="{{ route('lunch_lists.get_money',$lunch_order_id) }}" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> 本期收費確認表</a>
                    <a href="{{ route('lunch_lists.teacher_money_print',$lunch_order_id) }}" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> 本期三聯單</a>
                    <a href="{{ route('lunch_lists.every_day_download',$lunch_order_id) }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 下載此期「彰化智慧校園」收費模組匯入單</a>
                    <br>
                    <br>
                    <table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1">
                        <tr bgcolor='#005DBE' style='color:white;'>
                            <th>
                                姓名
                            </th>
                            <th>
                                地點
                            </th>
                            <th>
                                供餐方式
                            </th>
                            <?php $i=1; ?>
                            @foreach($date_array as $k=>$v)
                                @if($v=="1")
                                <th>
                                    <?php
                                        if(get_chinese_weekday2($k)=="六"){
                                            $txt_bg="text-success";
                                        }elseif(get_chinese_weekday2($k)=="日"){
                                            $txt_bg="text-danger";
                                        }else{
                                            $txt_bg="";
                                        }
                                        $d = substr($k,5,5);
                                    ?>
                                    {{ substr($d,0,2) }}<br>{{ substr($d,3,2) }}
                                    <br>
                                    <span class="{{ $txt_bg }}">{{ get_chinese_weekday2($k) }}</span>
                                </th>
                                @endif
                            @endforeach
                            <th>
                                天數
                            </th>
                            <th>
                                金額
                            </th>
                        </tr>
                        <?php $total_money = 0;$total_days=0; ?>
                        @foreach($user_data as $k1=>$v1)
                            <tr bgcolor='#FFFFFF'  bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>
                                    {{ $i }}{{ $k1 }}<br>
                                </td>
                                <td>
                                    {{ $place_data[$k1] }}
                                </td>
                                <td>
                                    @if($eat_data[$k1]==1)
                                        <img src="{{ asset('images/meat.png') }}">葷食合菜
                                    @elseif($eat_data[$k1]==2)
                                        <img src="{{ asset('images/vegetarian.png') }}">@if($eat_data_egg[$k1]==1)🥚@endif 素食合菜
                                        @if($eat_data_egg[$k1]==1)
                                            <br>(蛋奶素) 
                                        @else
                                            <br>(奶素)
                                        @endif
                                    @elseif($eat_data[$k1]==3)
                                        <img src="{{ asset('images/meat.png') }}">葷食便當
                                    @elseif($eat_data[$k1]==4)
                                        <img src="{{ asset('images/vegetarian.png') }}">@if($eat_data_egg[$k1]==1)🥚@endif 素食便當
                                        @if($eat_data_egg[$k1]==1)
                                            <br>(蛋奶素)
                                        @else
                                            <br>(奶素)
                                        @endif
                                    @endif
                                </td>
                                @foreach($date_array as $k2=>$v2)
                                    <?php
                                    if(get_chinese_weekday2($k2)=="六"){
                                        $bg="#CCFF99";
                                    }elseif(get_chinese_weekday2($k2)=="日"){
                                        $bg="#FFB7DD";
                                    }else{
                                        $bg="";
                                    }
                                    ?>
                                    @if($v2=="1")
                                    <td style="background-color:{{ $bg }}">
                                        @if(isset($v1[$k2]))
                                            @if($v1[$k2]['enable']=="eat")
                                                <a href="{{ route('lunch_lists.more_list_factory',[$lunch_order_id,$factory_data[$k1][$k2]['id']]) }}" target="_blank"><span class="badge badge-pill badge-danger">{{ mb_substr($factory_data[$k1][$k2]['name'],0,1) }}</span></a>
                                                <?php 
                                                    if(!isset($count_one_day[$k2])) $count_one_day[$k2]=0;
                                                    $count_one_day[$k2]++; 
                                                ?>
                                            @endif
                                        @endif
                                    </td>
                                    @endif
                                @endforeach
                                <td>
                                    @if(isset($days_data[$k1]))
                                        {{ $days_data[$k1] }}
                                        <?php $total_days += $days_data[$k1]; ?>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($money_data[$k1]))
                                        {{ $money_data[$k1] }}
                                        <?php $total_money += $money_data[$k1]; ?>
                                    @endif
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <td></td>
                            <td></td>
                            @foreach($date_array as $k=>$v)
                                @if($v=="1")
                                    <td>
                                        @if(isset($count_one_day[$k]))
                                            {{ $count_one_day[$k] }}
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                            <td>{{ $total_days }}</td>
                            <td>{{ $total_money }}</td>
                        </tr>
                    </table>
                @endif
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
    <script language='JavaScript'>

        function jump(){
            if(document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value!=''){
                location="/lunch_lists/every_day/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value;
            }
        }
    </script>
@endsection
