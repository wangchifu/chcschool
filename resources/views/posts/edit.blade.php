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
                        <label for="job_title"><strong class="text-danger">1.職稱*</strong></label>
                        {{ Form::text('job_title',null,['id'=>'title','class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="insite"><strong class="text-danger">2.公告類別*</strong></label>
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
                        <label for="title"><strong class="text-danger">4.標題*</strong></label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control', 'placeholder' => '請輸入標題','required'=>'required']) }}
                    </div>
                    <table>
                        <tr>
                            <td colspan="2">
                                5.上架起迄日期 ( 可不填 ) [<a href="{{ asset('live_date.png') }}" target="_blank">教學</a>]
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="live_date">起</label>
                                    {{ Form::date('live_date',substr($post->created_at,0,10),['id'=>'live_date','class' => 'form-control','onchange'=>'check_today()']) }}
                                    {{ Form::time('live_time',substr($post->created_at,11,8),['id'=>'live_time','class' => 'form-control']) }}
                                    <small>(不填代表即刻貼出)</small>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="die_date">迄(含)</label>
                                    {{ Form::date('die_date',null,['id'=>'die_date','class' => 'form-control','onchange'=>'check_date()']) }}
                                    <small>(不填代表不下架)</small>
                                </div>
                            </td>
                        </tr>
                        <script>
                            function check_date(){                                        
                                if($('#die_date').val() < $('#live_date').val() & document.getElementById("die_date").value !== ""){
                                    $('#die_date').val("");
                                    alert('迄日，不得小於起日！');
                                }
                            }
                            function check_today(){                               
                                check_date();         
                                if('{{ date('Y-m-d') }}'>= $('#live_date').val()){
                                    $('#live_date').val("");
                                    alert('不能選今天以前的日子！');
                                }
                            }
                        </script>
                    </table> 
                    <div class="form-group">
                        <label for="content"><strong class="text-danger">6.內文*</strong></label>
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
                        <label for="photos[]">7.相關照片( 單檔不大於5MB的圖檔 )</label>
                        <small class="text-danger">(注意！請勿將公告當成圖庫相簿使用，單次也不要超過十張以上的照片，若造成伺服器負擔，經查證將取消貴校此功能。)</small>
                        <br>
                        @if(!empty($photos))
                            @foreach($photos as $k=>$v)
                            <figure class="figure col-2">
                                <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">
                                <figcaption class="figure-caption"><a href="{{ route('posts.delete_photo',['post'=>$post->id,'filename'=>$v]) }}" class="badge badge-danger" onclick="return confirm('確定刪除？')" style="margin: 5px;"><i class="fas fa-times-circle"></i> {{ $v }}</a></figcaption>
                              </figure>
                            @endforeach
                        @endif
                        @if($per < 100)
                        {{ Form::file('photos[]', ['class' => 'form-control','multiple'=>'multiple', 'accept'=>'image/*']) }}
                        @else
                        <br>
                        <span class="text-danger">容量已滿！無法上傳照片了！</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="files[]">8.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://moda.gov.tw/digital-affairs/digital-service/app-services/248" target="_blank">ODF格式</a> ] [ 詳細公文 ] [ 轉檔教學 ] )</label>
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
