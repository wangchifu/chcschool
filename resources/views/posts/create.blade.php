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
            <h1>
                @if(empty($setup->post_name))
                  公告系統
                @else
                  {{ $setup->post_name }}
                @endif
            </h1>
            {{ Form::open(['route' => 'posts.store', 'method' => 'POST', 'files' => true,'id'=>'this_form','onsubmit'=>"return submitOnce(this)"]) }}
            <div class="card my-4">
                <h3 class="card-header">公告資料</h3>
                <div class="card-body">
                    <div class="form-group">
                        <?php $job_title = (auth()->user()->username=="admin")?"系統管理":auth()->user()->title; ?>
                        <label for="job_title"><strong class="text-danger">1.職稱*</strong></label>
                        {{ Form::text('job_title',$job_title,['id'=>'job_title','class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <label for="insite"><strong class="text-danger">2.公告類別*</strong></label>
                        {{ Form::select('insite', $types,null, ['id' => 'insite', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="content">
                            <a data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3" style="color:black;">
                                3.標題圖片( 不大於5MB )
                            </a>                            
                        <small class="text-secondary">jpeg, png 檔</small>
                        </label>
                        <div class="collapse" id="collapse3">
                            {{ Form::file('title_image', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title"><strong class="text-danger">4.標題*</strong></label>
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required','placeholder' => '請輸入標題']) }}
                    </div>
                    <p>
                    <a data-toggle="collapse" href="#collapse5" role="button" aria-expanded="false" aria-controls="collapse5" style="color:black;">
                        5.上架起迄日期 ( 可不填 )
                    </a>
                    [<a href="{{ asset('live_date.png') }}" target="_blank">教學</a>]
                    </p>
                    <div class="collapse" id="collapse5">
                        <table style="width:300px">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="live_date">起</label>
                                        {{ Form::date('live_date',null,['id'=>'live_date','class' => 'form-control','onchange'=>'check_today()']) }}
                                        {{ Form::time('live_time',null,['id'=>'live_time','class' => 'form-control']) }}
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
                    </div>
                                                     
                    <div class="form-group">
                        <label for="content"><strong class="text-danger">6.內文*</strong></label>
                        {{ Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control', 'rows' => 10,'required'=>'required', 'placeholder' => '請輸入內容']) }}
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('content'
                            ,{
                                toolbar: [
                                    { name: 'document', items: [ 'Bold', 'Italic','TextColor','PasteFromWord','-','BulletedList','NumberedList','-','Link','Unlink','-','Outdent', 'Indent', '-', 'Undo', 'Redo' ] },
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
                        <label for="files[]">8.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://moda.gov.tw/digital-affairs/digital-service/app-services/248" target="_blank">ODF格式</a> ] [ <a href="{{ asset('ODF.pdf') }}" target="_blank">詳細公文</a> ] [ <a href="{{ asset('office2016_odt_pdf.png') }}" target="_blank">轉檔教學</a> ] )
                        <small class="text-secondary">csv, txt, zip, jpeg, png, pdf, odt, ods, mp3 檔</small>
                        </label>
                        @if($per < 100)
                            {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
                        @else
                            <br>
                            <span class="text-danger">容量已滿！無法加附件！</span>
                        @endif
                    </div>                  
                    @if($setup->post_line_token)
                    <!--
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="send_line_token" name="send_line_token" value="yes">
                        <label class="form-check-label text-danger" for="send_line_token"><i class="fab fa-line text-success h3"></i> 同步發至 line notify (三月底停用)</label>
                    </div>
                    -->
                    @endif  
                    @if($setup->post_line_bot_token)
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="send_line_bot_token" name="send_line_bot_token" value="yes">
                        <label class="form-check-label text-danger" for="send_line_bot_token"><i class="fab fa-line text-success h3"></i> 同步發至 line bot (1.未來公告則不會發出，2.僅有200則免費的推播，群組一則以總人數計)</label>
                    </div>
                    @endif
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
