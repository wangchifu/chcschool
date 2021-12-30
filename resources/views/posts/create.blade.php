@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '新增公告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增公告</li>
                </ol>
            </nav>
            <h1>公告系統</h1>
            {{ Form::open(['route' => 'posts.store', 'method' => 'POST', 'files' => true,'id'=>'this_form','onsubmit'=>"return submitOnce(this)"]) }}
            <div class="card my-4">
                <h3 class="card-header">公告資料</h3>
                <div class="card-body">
                    <div class="form-group">
                        <label for="job_title"><strong>1.職稱*</strong></label>
                        {{ Form::text('job_title',auth()->user()->title,['id'=>'job_title','class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="insite">2.公告類別</label>
                        {{ Form::select('insite', $types,null, ['id' => 'insite', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="content">3.標題圖片( 不大於5MB )
                        <small class="text-secondary">jpeg, png 檔</small>
                        </label>
                        {{ Form::file('title_image', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="title"><strong>4.標題*</strong></label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required','placeholder' => '請輸入標題']) }}
                    </div>
                    <div class="form-group">
                        <label for="content"><strong>5.內文*</strong></label>
                        {{ Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control', 'rows' => 10,'required'=>'required', 'placeholder' => '請輸入內容']) }}
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
                        <label for="files[]">6.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://www.ndc.gov.tw/cp.aspx?n=d6d0a9e658098ca2" target="_blank">ODF格式</a> ] [ <a href="{{ asset('ODF.pdf') }}" target="_blank">詳細公文</a> ] [ <a href="{{ asset('office2016_odt_pdf.png') }}" target="_blank">轉檔教學</a> ] )
                        <small class="text-secondary">csv, txt, zip, jpeg, png, pdf, odt, ods 檔</small>
                        </label>
                        @if($per < 100)
                            {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
                        @else
                            <br>
                            <span class="text-danger">容量已滿！無法加附件！</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <button type="submit" id="submit_button" class="btn btn-primary btn-sm" onclick="if(confirm('您確定送出嗎?')){change_button();return true;}else return false">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    @include('layouts.errors')
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
        /**
        var submitcount=0;
        function submitOnce (form){
            if (submitcount == 0){
                submitcount++;
                return true;
            } else{
                alert('正在操作,請不要重複提交,謝謝!');
                return false;
            }
        }
        function change_button(){
            $("#submit_button").removeAttr('onclick');
            $("#submit_button").attr('disabled','disabled');
            $("#submit_button").addClass('disabled');
            $("#this_form").submit();
        }
        */
    </script>
@endsection
