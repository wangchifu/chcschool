@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-修改設定')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <?php
    $active['teacher'] ="";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="active";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-修改學期設定</h1>
            @include('lunches.nav')
            @include('layouts.errors')
            {{ Form::model($lunch_setup,['route' => ['lunch_setups.update',$lunch_setup->id], 'method' => 'patch','id'=>'setup','files'=>true]) }}
            <div class="card my-4">
                <h3 class="card-header">午餐設定資料</h3>
                <div class="card-body">
                    <div class="form-group">
                        <label for="semester"><strong>學期*</strong><small class="text-danger">(如 1062)</small></label>
                        {{ Form::text('semester',null,['id'=>'semester','class' => 'form-control', 'maxlength'=>'4','placeholder'=>'4碼數字','readonly'=>'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="die_line"><strong>允許最慢幾天前訂退餐*</strong></label>
                        {{ Form::text('die_line',null,['id'=>'die_line','class' => 'form-control', 'maxlength'=>'1','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="tea_open">隨時可訂餐<small class="text-danger">(僅供暫時開放，切記關閉它)</small></label>
                        <div class="form-check">
                            {{ Form::checkbox('teacher_open',null,null,['id'=>'tea_open','class'=>'form-check-input']) }}
                            <label class="form-check-label" for="tea_open"><span class="btn btn-danger btn-sm">打勾為隨時可訂</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disable">停止退餐<small class="text-danger">(僅供學期末計費時使用)</small></label>
                        <div class="form-check">
                            {{ Form::checkbox('disable',null,null,['id'=>'disable','class'=>'form-check-input']) }}
                            <label class="form-check-label" for="disable"><span class="btn btn-danger btn-sm">打勾為全面停止退餐</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="all_rece_name"><strong>全學期收據抬頭名稱*</strong><small class="text-danger">(如 彰化縣xx鎮xx國民小學)</small></label>
                        {{ Form::text('all_rece_name',null,['id'=>'all_rece_name','class' => 'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="all_rece_date"><strong>全學期收據開立日期*</strong><small class="text-danger">(如 2019-06-30)</small></label>
                        {{ Form::text('all_rece_date',null,['id'=>'all_rece_date','class' => 'form-control','required'=>'required','maxlength'=>'10','width'=>'276']) }}
                        <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                        <script>
                            $('#all_rece_date').datepicker({
                                uiLibrary: 'bootstrap4',
                                format: 'yyyy-mm-dd',
                                locale: 'zh-TW',
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="all_rece_name"><strong>全學期收據字號*</strong><small class="text-danger">(如 彰和東午字)</small></label>
                        {{ Form::text('all_rece_no',null,['id'=>'all_rece_no','class' => 'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="all_rece_num"><strong>全學期收據起始號*</strong></label>
                        {{ Form::text('all_rece_num',null,['id'=>'all_rece_num','class' => 'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="all_rece_num">經手人印章圖檔
                            <?php
                            $school_code = school_code();
                            $seal1 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal1.png');
                            $path = 'lunches&'.$lunch_setup->id.'&seal1.png';
                            ?>
                            @if(file_exists($seal1))
                                <img src="{{ route('getImg',$path) }}" width="180"><a href="{{ route('lunch_setups.del_file',[$path,$lunch_setup->id]) }}" onclick="return confirm('刪除？')"><li class="fas fa-times-circle text-danger"></li></a>
                            @endif
                        </label>
                        {{ Form::file('file1', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="all_rece_num">主辦出納印章圖檔
                            <?php
                            $seal2 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal2.png');
                            $path = 'lunches&'.$lunch_setup->id.'&seal2.png';
                            ?>
                            @if(file_exists($seal2))
                                <img src="{{ route('getImg',$path) }}" width="180"><a href="{{ route('lunch_setups.del_file',[$path,$lunch_setup->id]) }}" onclick="return confirm('刪除？')"><li class="fas fa-times-circle text-danger"></li></a>
                            @endif
                        </label>
                        {{ Form::file('file2', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="all_rece_num">主辦會計印章圖檔
                            <?php
                            $seal3 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal3.png');
                            $path = 'lunches&'.$lunch_setup->id.'&seal3.png';
                            ?>
                            @if(file_exists($seal3))
                                <img src="{{ route('getImg',$path) }}" width="180"><a href="{{ route('lunch_setups.del_file',[$path,$lunch_setup->id]) }}" onclick="return confirm('刪除？')"><li class="fas fa-times-circle text-danger"></li></a>
                            @endif
                        </label>
                        {{ Form::file('file3', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="all_rece_num">機關長官印章圖檔
                            <?php
                            $seal4 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal4.png');
                            $path = 'lunches&'.$lunch_setup->id.'&seal4.png';
                            ?>
                            @if(file_exists($seal4))
                                <img src="{{ route('getImg',$path) }}" width="180"><a href="{{ route('lunch_setups.del_file',[$path,$lunch_setup->id]) }}" onclick="return confirm('刪除？')"><li class="fas fa-times-circle text-danger"></li></a>
                            @endif
                        </label>
                        {{ Form::file('file4', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
