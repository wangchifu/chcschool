@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '載入Log內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>載入Log內容</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">載入Log內容</li>
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
                        {{ Form::text('title',$log->title,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '標題']) }}
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
                        {{ Form::textarea('content',$log->content,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>                                      
                    <hr>
                    <?php
                        if($log->power==null){
                            $checked1 = "checked";
                            $checked2 = null;
                            $checked3 = null;
                        }
                        if($log->power==2){
                            $checked1 = null;
                            $checked2 = "checked";
                            $checked3 = null;
                        }
                        if($log->power==3){
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
                            <i class="fas fa-save"></i> 覆蓋目前設定
                        </button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script>
        // 1. 阻止 CKEditor 刪除空標籤
        CKEDITOR.dtd.$removeEmpty['i'] = false;
        CKEDITOR.dtd.$removeEmpty['span'] = false;

        // 2. 初始化 CKEditor
        var editor = CKEDITOR.replace('my-editor', {
            fullPage: true,
            allowedContent: true,
            autoParagraph: false,
            fillEmptyBlocks: false, // 關鍵 1：關閉空白區塊自動填充 &nbsp; 的機制
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
        });

        // 3. 處理 getData 事件（過濾 &nbsp; 與剝離 HTML 骨架）
        editor.on('getData', function(evt) {
            var html = evt.data.dataValue;
            if (!html) return;

            try {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');
                var head = doc.head;

                if (head) {
                    var title = head.querySelector('title');
                    var hasTitleText = title && title.textContent.trim().length > 0;
                    var hasCustomHead = head.querySelectorAll('meta, style, link, script, base').length > 0;

                    // 如果 <head> 裡面沒有自訂內容，就剝離外層 <html><head>，只拿 body 內容
                    if (!hasTitleText && !hasCustomHead && doc.body) {
                        var inner = doc.body.innerHTML.trim();

                        // 關鍵 2：如果內文只有 &nbsp; 或空的 <p>，清理為完全空白
                        if (inner === '&nbsp;' || inner === '<p>&nbsp;</p>' || inner === '<p></p>') {
                            inner = '';
                        }

                        evt.data.dataValue = inner;
                    }
                }
            } catch (e) {
                console.error('DOMParser error:', e);
            }
        });

        $(document).ready(function() {
            $("#this_form").validate({
                submitHandler: function(form) {
                    if (typeof CKEDITOR !== 'undefined') {
                        for (var instance in CKEDITOR.instances) {
                            CKEDITOR.instances[instance].updateElement();
                        }
                    }

                    var contentInput = $(form).find('[name="content"]');
                    var val = contentInput.val();

                    if (val) {
                        if (!val.startsWith('HEX:')) {
                            val = stringToHex(val);
                        }
                        contentInput.val(val);
                    }

                    form.submit();
                }
            });
        });

        // UTF-8 轉 Hex 函式
        function stringToHex(str) {
            const encoder = new TextEncoder();
            const bytes = encoder.encode(str);
            return 'HEX:' + Array.from(bytes, b => b.toString(16).padStart(2, '0')).join('');
        }
    </script>
@endsection
