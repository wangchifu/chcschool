@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改介紹 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改介紹</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">介紹列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改介紹</li>
                </ol>
            </nav>
            {{ Form::model($department,['route' => ['departments.together_update',$department->id], 'method' => 'PATCH','id'=>'this_form']) }}
            <div class="card my-4">
                <h3 class="card-header">介紹資料</h3>
                <div class="card-body">
                    @include('layouts.errors')
                    <div class="form-group">
                        <label for="order_by">排序</label>
                        {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control','maxlength'=>'3','readonly'=>'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="title">共編群組*</label>
                        {{ Form::select('group_id', $group_array,null, ['id' => 'group_id', 'class' => 'form-control','disabled'=>'disabled']) }}
                    </div>
                    <div class="form-group">
                        <label for="title">標題*</label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '標題']) }}
                    </div>        
                    <div class="form-group">
                        <label for="content">內文*</label>
                        {{ Form::textarea('content',null,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('my-editor'
                            ,{
                                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                            });
                    </script>
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
