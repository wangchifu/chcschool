@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-修改餐期')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <?php
    $active['teacher'] ="";
    $active['student'] ="";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="active";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-修改餐期</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_orders.index') }}">餐期管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改餐期</li>
                </ol>
            </nav>
            <div class="card">
                <h3 class="card-header">
                    修改 {{ $lunch_order->name }} 餐期
                </h3>
                <div class="card-body">
                    <form action="{{ route('lunch_orders.order_save',$lunch_order->id) }}" method="post" id="this_form">
                        @csrf
                        @method('patch')
                    <div class="form-group">
                        <label>收據抬頭*</label>
                        <input type="text" name="rece_name" value="{{ $lunch_order->rece_name }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>收據日期*</label>
                        {{ Form::text('rece_date',$lunch_order->rece_date,['id'=>'rece_date','class' => 'form-control','required'=>'required','maxlength'=>'10','width'=>'276']) }}
                        <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                        <script>
                            $('#rece_date').datepicker({
                                uiLibrary: 'bootstrap4',
                                format: 'yyyy-mm-dd',
                                locale: 'zh-TW',
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>收據字號*</label>
                        <input type="text" name="rece_no" value="{{ $lunch_order->rece_no }}" class="form-control" required>
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
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
