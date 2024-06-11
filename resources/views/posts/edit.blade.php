@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '修改公告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <h1><i class="fas fa-bullhorn"></i> 修改公告</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改公告</li>
                </ol>
            </nav>
            {{ Form::model($post,['route' => ['posts.update',$post->id], 'method' => 'PATCH','id'=>'this_form', 'files' => true]) }}
            <div class="card my-4">
                <h3 class="card-header">公告資料</h3>
                <div class="card-body">
                    @include('layouts.errors')
                    <div class="form-group">
                        <label for="job_title"><strong>1.職稱*</strong></label>
                        {{ Form::text('job_title',auth()->user()->title,['id'=>'title','class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="insite">2.公告類別</label>
                        {{ Form::select('insite', $types,$post->insite, ['id' => 'insite', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="content">3.標題圖片</label>
                        @if($title_image)
                            <?php
                            $file = "posts/".$post->id."/title_image.png";
                            $file = str_replace('/','&',$file);
                            ?>
                            <a href="{{ route('posts.delete_title_image',$post->id) }}" class="badge badge-danger" id="fileDel" onclick="return confirm('確定刪標題圖片')"><i class="fas fa-times-circle"></i> 刪</a>
                        @endif
                        {{ Form::file('title_image', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="title"><strong>4.標題*</strong></label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control', 'placeholder' => '請輸入標題','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="die_date">5.下架時間(若空白則不下架)</label>
                        {{ Form::date('die_date',null,['id'=>'die_date','class' => 'form-control','placeholder' => '請選擇日期']) }}
                    </div>
                    <div class="form-group">
                        <label for="content"><strong>6.內文*</strong></label>
                        {{ Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control', 'rows' => 10, 'placeholder' => '請輸入內容','required'=>'required']) }}
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('content'
                            ,{
                                toolbar: [
                                    { name: 'document', items: [ 'Bold', 'Italic','TextColor','-','BulletedList','NumberedList','-','Link','Unlink','-','Outdent', 'Indent', '-', 'Undo', 'Redo' ] },
                                ],
                                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                            });
                    </script>
                    @include('layouts.hd')
                    <div class="form-group">
                        <label for="files[]">7.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://www.ndc.gov.tw/cp.aspx?n=d6d0a9e658098ca2" target="_blank">ODF格式</a> ] [ 詳細公文 ] [ 轉檔教學 ] )</label>
                        <br>
                        @if(!empty($files))
                            @foreach($files as $k=>$v)
                                <a href="{{ route('posts.delete_file',['post'=>$post->id,'filename'=>$v]) }}" class="badge badge-danger" onclick="return confirm('確定刪除？')" style="margin: 5px;"><i class="fas fa-times-circle"></i> {{ $v }}</a>
                            @endforeach
                        @endif
                        @if($per < 100)
                            {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
                        @else
                            <br>
                            <span class="text-danger">容量已滿！無法加附件！</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定修改嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
