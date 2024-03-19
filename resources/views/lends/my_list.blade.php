@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '我的借用 | ')

@section('content')
<?php

$active['index'] ="";
$active['my_list'] ="active";
$active['admin'] ="";
$active['list'] ="";

?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>我的借用</h1>
            @include('lends.nav')
            <br>
            <table class="table table-border table-striped">
                <tr>
                    <th>
                        填寫時間
                    </th>
                    <th>
                        借用人
                    </th>
                    <th>
                        借用物品
                    </th>
                    <th>
                        借用時間
                    </th>
                    <th>
                        歸還時間
                    </th>
                    <th>
                        備註
                    </th>
                </tr>
                @foreach($lend_orders as $lend_order)
                <tr>
                    <td>
                        @if($lend_order->lend_date > date('Y-m-d'))
                        <a href="{{ route('lends.delete_my_order',$lend_order->id) }}" onclick="if(confirm('你確定要刪除嗎?')) return true;else return false"><i class="fas fa-times-circle text-danger"></i></a> 
                        @endif
                        {{ $lend_order->created_at }}
                    </td>
                    <td>
                        {{ $lend_order->user->name }}
                    </td>
                    <td>
                        {{ $lend_order->lend_item->name }}<br>{{ $lend_order->num }}
                    </td>
                    <td>
                        {{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}
                    </td>
                    <td>
                        {{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}
                    </td>
                    <td>
                        {{ $lend_order->ps }}
                    </td>
                </tr>
                @endforeach
            </table>
            {{  $lend_orders->links() }}
        </div>
    </div>
    
@endsection
