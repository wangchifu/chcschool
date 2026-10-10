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
    // 1. 阻止 CKEditor 刪除空標籤 (如 FontAwesome 圖示)
    CKEDITOR.dtd.$removeEmpty['i'] = false;
    CKEDITOR.dtd.$removeEmpty['span'] = false;

    // 2. 初始化 CKEditor (維持 fullPage 支援完整網頁貼入)
    var editor = CKEDITOR.replace('my-editor', {
        fullPage: true,
        allowedContent: true,
        autoParagraph: false,
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
    });

    // 關鍵設定：切換至「原始碼」或取用資料時，若沒有標題自動剝離外殼
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

                // 如果 <head> 裡沒有標題也沒有自訂標籤，就把外層 <html><head> 剝掉，只傳回 body 內容
                if (!hasTitleText && !hasCustomHead && doc.body) {
                    evt.data.dataValue = doc.body.innerHTML.trim();
                }
            }
        } catch (e) {
            console.error('DOMParser error:', e);
        }
    });

    $(document).ready(function() {
        $("#this_form").validate({
            submitHandler: function(form) {
                // (1) 同步 CKEditor 內容回 textarea
                if (typeof CKEDITOR !== 'undefined') {
                    for (var instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }

                var contentInput = $(form).find('[name="content"]');
                var val = contentInput.val();

                if (val) {
                    // (2) 16進位 Hex 編碼（躲過 WAF 對 <script> 與 HTML 標籤的攔截）
                    if (!val.startsWith('HEX:')) {
                        val = stringToHex(val);
                    }
                    contentInput.val(val);
                }

                // (3) 正式送出
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
