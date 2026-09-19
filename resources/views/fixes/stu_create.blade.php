@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '學生新增報修 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>學生新增報修</h1>          
            <a href="{{ route('fixes.stu_logout') }}" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> 學生登出</a>                          
            {{ Form::open(['route' => 'fixes.stu_store', 'method' => 'POST','id'=>'this_form']) }}
            <div class="card my-4">
                <h3 class="card-header">報修資料</h3>
                <div class="card-body">
                    @include('layouts.errors')                    
                    <div class="form-group">
                        <label for="type"><strong>類別*</strong></label>                        
                        {{ Form::select('type', $types,null, ['id' => 'type', 'class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="title"><strong>標題*</strong></label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control', 'placeholder' => '請輸入標題','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="content"><strong>內文*</strong></label>
                        {{ Form::textarea('content', '設備地點：'."\r\n".'待修狀況：', ['id' => 'content', 'class' => 'form-control','required'=>'required','rows' => 10, 'placeholder' => '請寫清楚發生位置和情況']) }}
                    </div>
                    <div class="form-group">
                        <a href="{{ route('fixes.stu_list') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection