@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '新增內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>新增內容</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增內容</li>
                </ol>
            </nav>
            {{ Form::open(['route' => 'contents.store', 'method' => 'POST','id'=>'this_form']) }}
            @include('contents.form')
            {{ Form::close() }}
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#this_form").validate();

            $("#this_form").on('submit', function(e) {
                // 1. 同步 CKEditor 內容回 textarea
                if (typeof CKEDITOR !== 'undefined') {
                    for (var instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }

                var contentInput = $(this).find('[name="content"]');
                var val = contentInput.val();

                // 2. 如果內容存在，且還沒有 B64: 前綴，就進行編碼並加上前綴
                if (val && !val.startsWith('B64:')) {
                    var encodedContent = 'B64:' + btoa(unescape(encodeURIComponent(val)));
                    contentInput.val(encodedContent);
                }
            });
        });
    </script>    
@endsection