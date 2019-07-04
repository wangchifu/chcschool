@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <h2>{{ $lunch_setup->semester }} 學期教職員午餐廠商收入表</h2>
    <table border="1" width="100%">
        <tr>
            <th>
                廠商
            </th>
            <th>
                訂餐者
            </th>
            <th>
                總訂餐數
            </th>
            <th>
                總計
            </th>
        </tr>
        <?php $all_days=0; ?>
    @foreach($order_data as $k=>$v)
        <tr>
            <td>
                {{ $k }}
            </td>
            <td>
                <table border="1" width="100%">
                    <tr>
                        <th>
                            姓名
                        </th>
                        <th>
                            訂餐數
                        </th>
                    </tr>
                <?php $total_days=0; ?>
                @foreach($v as $k2=>$v2)
                    <tr>
                        <td>
                            {{ $k2 }}
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
    @endforeach
        <tr>
            <th>
                合計
            </th>
            <th>

            </th>
            <th>
                {{ $all_days }}
            </th>
            <th>
                {{ $lunch_setup->teacher_money*$all_days }}
            </th>
        </tr>
    </table>
@endsection
