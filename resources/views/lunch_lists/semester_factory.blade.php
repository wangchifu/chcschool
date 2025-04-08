@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <?php
    echo "<body onload='window.print()'>"; ?>
        <?php $all_days=0; ?>
    @foreach($order_data as $k=>$v)
        <h2>{{ $lunch_setup->semester }} 學期教職員午餐 {{ $k }} 收入明細</h2>
        <table border="1" width="100%">
        <tr>
            <th width="20%">
                廠商
            </th>
            <th width="40">
                訂餐者
            </th>
            <th width="20%">
                總訂餐數
            </th>
            <th width="20%">
                總計
            </th>
        </tr>
        <tr>
            <td>
                {{ $k }}
            </td>
            <td>
                <table border="1" width="100%">
                    <tr>
                        <th width="50%">
                            姓名
                        </th>
                        <th width="50%">
                            訂餐數
                        </th>
                    </tr>
                <?php $total_days=0; ?>
                @foreach($v as $k2=>$v2)
                    <tr>
                        <td>
                            {{ $user2name[$k2] }}
                        </td>
                        <td>
                            {{ $v2 }}
                            <?php $total_days += $v2; ?>
                        </td>
                    </tr>
                @endforeach
                </table>
            </td>
            <td>
                {{ $total_days }}
                <?php $all_days += $total_days; ?>
            </td>
            <td>
                {{ $lunch_setup->teacher_money*$total_days }}
            </td>
        </tr>
        </table>
        <p style='page-break-after:always'></p>
    @endforeach
    <table border="1" width="100%">
        <tr>
            <th width="20%">
                合計
            </th>
            <th width="40%">

            </th>
            <th width="20%">
                {{ $all_days }}
            </th>
            <th width="20%">
                {{ $lunch_setup->teacher_money*$all_days }}
            </th>
        </tr>
    </table>
@endsection
