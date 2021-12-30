@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '修改文章 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改文章</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">文章列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改文章</li>
                </ol>
            </nav>
            {{ Form::model($blog,['route' => ['blogs.update',$blog->id],'files' => true, 'method' => 'PATCH','id'=>'this_form']) }}
                <div class="card my-4">
                    <h3 class="card-header">文章資料</h3>
                    <div class="card-body">
                        @include('layouts.errors')
                        <div class="form-group">
                            <label for="content">標題圖片( 不大於5MB )
                                <small class="text-secondary">jpeg, png 檔</small>
                            </label>
                            @if($title_image)
                                <?php
                                $file = "blogs/".$blog->id."/title_image.png";
                                $file = str_replace('/','&',$file);
                                ?>
                                <a href="{{ route('blogs.delete_title_image',$blog->id) }}" class="badge badge-danger" id="fileDel" onclick="return confirm('確定刪標題圖片')"><i class="fas fa-times-circle"></i> 刪</a>
                            @endif
                            {{ Form::file('title_image', ['class' => 'form-control']) }}
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
