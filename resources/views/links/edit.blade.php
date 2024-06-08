@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改連結 | ')

@section('in_head')
    <script src="{{ asset('js/jquery.slim.min.js') }}"></script>
    <link href="{{ asset('fontawesome-icon-browser-picker/fontawesome-browser.css') }}" rel='stylesheet' type='text/css' />
    <script src="{{ asset('fontawesome-icon-browser-picker/fontawesome-browser.js') }}" type='text/javascript'></script> 
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('links.index') }}">選單連結</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改連結</li>
                </ol>
            </nav>
            @include('layouts.errors')
            {{ Form::model($link,['route' => ['links.update',$link->id], 'method' => 'PATCH','id'=>'this_form']) }}
            <link href="{{ asset('IconPicker/dist/iconpicker-1.5.0.css') }}" rel="stylesheet">
            <script src="{{ asset('IconPicker/dist/iconpicker-1.5.0.js') }}"></script>
            <div class="card my-4">
                <h3 class="card-header">連結資料</h3>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">類別*</label>
                        {{ Form::select('type_id', $types,null, ['id' => 'type_id', 'class' => 'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="name">圖示*</label> <i class="" id="show_icon"></i>
                        <input type="text" class="form-control" name="icon" placeholder="請選圖示" data-fa-browser id="this_input" value="{{ $link->icon }}">
                        <script>
                            $(function($) {
                              $.fabrowser();
                          });
                          function show_icon(){
                            $("#show_icon").attr('class', '');
                            $('#show_icon').addClass($('#this_input').val()); 
                          }
                          $('#show_icon').addClass($('#this_input').val());           
                          </script>
                    </div>        
                    <div class="form-group">
                        <label for="name">名稱*</label>
                        {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                    </div>
                    <div class="form-group">
                        <label for="url">網址*</label>
                        {{ Form::text('url',null,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
                    </div>
                    <div class="form-group">
                        <label for="order_by">排序</label>
                        {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                    </div>
                    <hr>
                    <?php
                        if($link->target==null){
                            $checked1 = "checked";
                            $checked2 = null;
                        }
                        if($link->target=="_self"){
                            $checked1 = null;
                            $checked2 = "checked";
                        }
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" id="flexRadioDefault1" {{ $checked1 }} value="">
                        <label class="form-check-label" for="flexRadioDefault1">
                            開啟新視窗
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" id="flexRadioDefault2" {{ $checked2 }} value="_self">
                        <label class="form-check-label" for="flexRadioDefault2">
                            本視窗開啟
                        </label>
                      </div>
                      <hr>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
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
