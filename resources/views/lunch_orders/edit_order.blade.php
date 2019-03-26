@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-修改餐期')

@section('content')
    <?php
    $active['teacher'] ="";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="active";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-餐期管理</h1>
            @include('lunches.nav')
            <br>
            <div class="card">
                <h3 class="card-header">
                    修改 {{ $lunch_order->name }} 餐期
                </h3>
                <div class="card-body">
                    <form action="{{ route('lunch_orders.order_save',$lunch_order->id) }}" method="post">
                        @csrf
                        @method('patch')
                    <div class="form-group">
                        <label>收據抬頭*</label>
                        <input type="text" name="rece_name" value="{{ $lunch_order->rece_name }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>收據日期*</label>
                        <input type="text" name="rece_date" value="{{ $lunch_order->rece_date }}" class="form-control" required maxlength="10">
                    </div>
                    <div class="form-group">
                        <label>收據啟始號*</label>
                        <input type="text" name="rece_num" value="{{ $lunch_order->rece_num }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>備註</label>
                        <input type="text" name="order_ps" value="{{ $lunch_order->order_ps }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')"><i class="fas fa-save"></i> 儲存</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
