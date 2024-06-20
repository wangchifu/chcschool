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
                        <label for="job_title"><strong class="text-danger">1.職稱*</strong></label>
                        {{ Form::text('job_title',auth()->user()->title,['id'=>'job_title','class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="insite"><strong class="text-danger">2.公告類別*</strong></label>
                        {{ Form::select('insite', $types,null, ['id' => 'insite', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="content">3.標題圖片( 不大於5MB )
                        <small class="text-secondary">jpeg, png 檔</small>
                        </label>
                        {{ Form::file('title_image', ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="title"><strong class="text-danger">4.標題*</strong></label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required','placeholder' => '請輸入標題']) }}
                    </div>
                    <table>
                        <tr>
                            <td colspan="2">
                                5.上架起迄日期 ( 可不填 )
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="live_date">起</label>
                                    {{ Form::date('live_date',null,['id'=>'live_date','class' => 'form-control','placeholder' => '請選擇日期','onchange'=>'check_today()']) }}
                                    <small>(不填代表即刻貼出)</small>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="die_date">迄(含)</label>
                                    {{ Form::date('die_date',null,['id'=>'die_date','class' => 'form-control','placeholder' => '請選擇日期','onchange'=>'check_date()']) }}
                                    <small>(不填代表不下架)</small>
                                </div>
                            </td>
                        </tr>
                        <script>
                            function check_date(){                                        
                                if($('#die_date').val() < $('#live_date').val()){
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
                        <label for="photos[]">7.相關照片( 單檔不大於5MB的圖檔 )</label>
                        <small class="text-danger">(注意！請勿將公告當成圖庫相簿使用，單次也不要超過十張以上的照片，若造成伺服器負擔，經查證將取消貴校此功能。)</small>
                        @if($per < 100)
                        {{ Form::file('photos[]', ['class' => 'form-control','multiple'=>'multiple', 'accept'=>'image/*']) }}
                        @else
                        <br>
                        <span class="text-danger">容量已滿！無法上傳照片了！</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="files[]">8.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://www.ndc.gov.tw/cp.aspx?n=d6d0a9e658098ca2" target="_blank">ODF格式</a> ] [ <a href="{{ asset('ODF.pdf') }}" target="_blank">詳細公文</a> ] [ <a href="{{ asset('office2016_odt_pdf.png') }}" target="_blank">轉檔教學</a> ] )
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
