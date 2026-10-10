@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '修改內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>修改內容</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改內容</li>
                </ol>
            </nav>
            {{ Form::model($content,['route' => ['contents.update',$content->id], 'method' => 'PATCH','id'=>'this_form']) }}
            <div class="card my-4">
                <h3 class="card-header">內容資料</h3>
                <div class="card-body">
                    @include('layouts.errors')
                    <div class="form-group">
                        <div class="form-check">
                            <?php $nothing_checked = ($content->nothing==1)?"checked":null; ?>
                            <input class="form-check-input" type="checkbox" name="nothing" id="nothing" value="1" {{ $nothing_checked }}>
                            <label class="form-check-label" for="nothing">
                            套用全空白頁面
                            </label>
                        </div>
                        <hr>
                        <label for="title">標題*</label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '標題']) }}
                    </div>
                    <div class="form-group">
                        <label for="title">共編群組*</label>
                        {{ Form::select('group_id', $group_array,null, ['id' => 'group_id', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="tags">標籤</label><small class="text-secondary"> (請用,分隔多個標籤)</small>
                        {{ Form::text('tags',null,['id'=>'tags','class' => 'form-control', 'placeholder' => '標籤']) }}
                    </div>
                    <div class="form-group">
                        <label for="content">內文*</label>
                        {{ Form::textarea('content',null,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
                    <script>
                        // 1. 阻止 CKEditor 自動刪除「空的標籤」（例如 FontAwesome 圖示 <i class="fa ..."></i>）
                        CKEDITOR.dtd.$removeEmpty['i'] = false;
                        CKEDITOR.dtd.$removeEmpty['span'] = false;

                        // 2. 初始化 CKEditor 並關閉 HTML 自動過濾
                        CKEDITOR.replace('my-editor', {
                            fullPage: true,        // 關鍵設定：開啟完整頁面模式，保留 <!DOCTYPE>、<html>、<head>、<title> 等標籤
                            allowedContent: true,  // 完全關閉 ACF 過濾器，保留所有原始 HTML 標籤與屬性
                            autoParagraph: false,   // 防止自動在沒有標籤的文字外層包裹 <p> 標籤（可依需求開啟/關閉）

                            // 原本的檔案管理者設定
                            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                        });
                    </script>                    
                    <hr>
                    <?php
                        if($content->power==null){
                            $checked1 = "checked";
                            $checked2 = null;
                            $checked3 = null;
                        }
                        if($content->power==2){
                            $checked1 = null;
                            $checked2 = "checked";
                            $checked3 = null;
                        }
                        if($content->power==3){
                            $checked1 = null;
                            $checked2 = null;
                            $checked3 = "checked";
                        }

                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="power" id="power1" {{ $checked1 }} value="">
                        <label class="form-check-label" for="power1">
                          公開
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="power" id="power2" {{ $checked2 }} value="2">
                        <label class="form-check-label" for="power2">
                          在校內網域或登入者都可看
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="power" id="power3" {{ $checked3 }} value="3">
                        <label class="form-check-label" for="power3">
                          只有登入者可看
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
        $(document).ready(function() {
            var validator = $("#this_form").validate();

            $("#this_form").on('submit', function(e) {
                // 1. 同步 CKEditor 內容回原生 textarea (#my-editor)
                if (typeof CKEDITOR !== 'undefined') {
                    for (var instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }

                // 2. 取得內文並進行 B64 編碼
                var contentInput = $(this).find('[name="content"]');
                var val = contentInput.val();

                if (val && !val.startsWith('B64:')) {
                    var encodedContent = 'B64:' + btoa(unescape(encodeURIComponent(val)));
                    contentInput.val(encodedContent);
                }
            });
        });
    </script>
@endsection
