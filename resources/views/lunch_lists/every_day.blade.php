@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <?php

    $active['teacher'] ="";
    $active['list'] ="active";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-報表輸出：教職逐日訂餐</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.index') }}">報表輸出</a></li>
                    <li class="breadcrumb-item active" aria-current="page">教職逐日訂餐</li>
                </ol>
            </nav>
            @if($admin)
                <form name=myform>
                    <div class="form-control">
                        {{ Form::select('lunch_order_id', $lunch_order_array,$lunch_order_id, ['class' => 'form-control','placeholder'=>'--請選擇--','onchange'=>'jump()']) }}
                    </div>
                </form>
                @if($lunch_order_id)
                    <br>
                    <a href="{{ route('lunch_lists.get_money',$lunch_order_id) }}" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> 收費確認表</a>
                    <a href="{{ route('lunch_lists.teacher_money_print',$lunch_order_id) }}" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> 三聯單</a>
                    <br>
                    <br>
                    <table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1">
                        <tr bgcolor='#005DBE' style='color:white;'>
                            <th>
                                姓名
                            </th>
                            <?php $i=1; ?>
                            @foreach($date_array as $k=>$v)
                                @if($v==1)
                                <th>
                                    {{ substr($k,5,5) }}
                                    <?php
                                        if(get_chinese_weekday2($k)=="六"){
                                            $txt_bg="text-success";
                                        }elseif(get_chinese_weekday2($k)=="日"){
                                            $txt_bg="text-danger";
                                        }else{
                                            $txt_bg="";
                                        }
                                    ?>
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
                                    <small>({{ $factory_data[$k1] }})</small>
                                </td>
                                @foreach($date_array as $k2=>$v2)
                                    @if($v2==1)
                                    <td>
                                        @if(isset($v1[$k2]))
                                            @if($v1[$k2]['enable']=="eat")
                                                @if($v1[$k2]['eat_style']==1)
                                                    <img src="{{ asset('/images/meat.png') }}">
                                                @elseif($v1[$k2]['eat_style']==2)
                                                    <img src="{{ asset('/images/vegetarian.png') }}">
                                                @endif
                                                <br>
                                                <small>{{ $v1[$k2]['place'] }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    @endif
                                @endforeach
                                <td>
                                    {{ $days_data[$k1] }}
                                    <?php $total_days += $days_data[$k1]; ?>
                                </td>
                                <td>
                                    {{ $money_data[$k1] }}
                                    <?php $total_money += $money_data[$k1]; ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            @foreach($date_array as $k=>$v)
                                @if($v==1)
                                <td></td>
                                @endif
                            @endforeach
                            <td>{{ $total_days }}</td>
                            <td>{{ $total_money }} 元</td>
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
