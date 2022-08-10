@extends('layouts.master_clean')

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
            <h1>午餐系統-報表輸出：訂購 {{ $factory->name }} 資訊</h1>
            @if($admin)
                <h3>一、教師訂餐明細</h3>
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
                                    葷食合菜
                                @elseif($eat_data[$k1]==2)
                                    素食合菜
                                @elseif($eat_data[$k1]==3)
                                    葷食便當
                                @elseif($eat_data[$k1]==4)
                                    素食便當
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
                                <td style="background-color:{{ $bg }}">
                                    @if(isset($v1[$k2]))
                                        @if($v1[$k2]['enable']=="eat")
                                            <?php
                                            if(!isset($p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2])) $p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2]=0;
                                            $p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2]++;
                                            ?>
                                            <img src="{{ asset('/images/system_red.png') }}">
                                        @endif
                                    @endif
                                </td>
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
                            <td></td>
                        @endforeach
                        <td>{{ $total_days }}</td>
                        <td>{{ $total_money }}</td>
                    </tr>
                </table>
                <hr>
                <h3>二、各地點及葷素食</h3>
                <table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1">
                    <tr bgcolor='#005DBE' style='color:white;'>
                        <th>
                            地點
                        </th>
                        <th>
                            供餐方式
                        </th>
                        @foreach($date_array as $kk=>$vv)
                            <?php
                            $dd = explode('-',$kk);
                            if(get_chinese_weekday2($kk)=="六"){
                                $txt_bg="text-success";
                            }elseif(get_chinese_weekday2($kk)=="日"){
                                $txt_bg="text-danger";
                            }else{
                                $txt_bg="";
                            }
                            ?>
                            <th>
                                {{ $dd[1] }}<br>{{ $dd[2] }}<br><span class="{{ $txt_bg }}">{{ get_chinese_weekday2($kk) }}</span>
                            </th>
                        @endforeach
                    </tr>
                    <?php
                    ksort($p_e_data);
                    $eat_styles=[
                        '1'=>'葷食合菜',
                        '2'=>'素食合菜',
                        '3'=>'葷食便當',
                        '4'=>'素食便當',
                    ];

                    ?>
                    @foreach($p_e_data as $k11=>$v11)
                        @foreach($eat_styles as $k22=>$v22)
                            @if(!empty($v11[$k22]))
                                <tr bgcolor='#FFFFFF'  bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                    <td>
                                        {{ $k11 }}
                                    </td>
                                    <td>
                                        {{ $v22 }}
                                    </td>
                                    @foreach($date_array as $k33=>$v33)
                                        <?php
                                        if(get_chinese_weekday2($k33)=="六"){
                                            $bg="#CCFF99";
                                        }elseif(get_chinese_weekday2($k33)=="日"){
                                            $bg="#FFB7DD";
                                        }else{
                                            $bg="";
                                        }
                                        ?>
                                        <td style="background-color: {{ $bg }}">
                                            @if(isset($v11[$k22][$k33]))
                                                {{ $v11[$k22][$k33] }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    @endforeach

                </table>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
