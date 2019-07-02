@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <h2>{{ $semester }} 學期教職員午餐廠商收入表</h2>
    <table class="table table-striped">
        <tr>
            <th>
                廠商
            </th>
            <th>
                單價
            </th>
            <th>
                總訂餐數
            </th>
            <th>
                總計
            </th>
        </tr>
        <?php $total=0; ?>
    @foreach($f as $k=>$v)
        <tr>
            <td>
                {{ $k }}
            </td>
            <td>
                {{ $v['money'] }}
            </td>
            <td>
                {{ $v['num'] }}
            </td>
            <td>
                {{ $v['money']*$v['num'] }}
                <?php $total += $v['money']*$v['num'] ?>
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

            </th>
            <th>
                {{ $total }}
            </th>
        </tr>
    </table>
@endsection
