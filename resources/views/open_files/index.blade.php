@extends('layouts.master')

@section('nav_open_files_active', 'active')
<?php $openfile_name = (empty($setup->openfile_name))?"檔案庫":$setup->openfile_name; ?>
@section('title', $openfile_name.' | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：鍵盤 Focus 高對比視覺提示 */
    .table a:focus-visible,
    .table button:focus-visible,
    .card button:focus-visible,
    .card a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
    }
</style>

    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="h2 mb-3">
                @if(empty($setup->openfile_name))
                    檔案庫
                @else
                    {{ $setup->openfile_name }}
                @endif
            </h1>
            <?php
            $final = end($folder_path);
            $final_key = key($folder_path);
            $p="";
            $f="app/public/".$school_code."/open_files";
            $last_folder = "";
            ?>
            
            <!-- 無障礙 HM1010301C 修正：麵包屑導覽區塊 -->
            <nav aria-label="目前的檔案目錄路徑" class="mb-3">
                <span>路徑：</span>
                @auth
                    <?php
                        $check_exec = \App\UserGroup::where('user_id',auth()->user()->id)
                        ->where('group_id',1)
                        ->first();                                                          
                    ?>
                @endauth            
                @foreach($folder_path as $k=>$v)
                    <?php
                    if($k=="0"){
                        $k = null;

                    }else{
                        $p .= '&'.$k;
                        $f .=  '/'.$v;
                    }
                    if($k != $final_key and !empty($k)){
                        $last_folder .= '&'.$k;
                    }

                    ?>
                    @if($v == $final)
                        <i class="fa fa-folder-open text-warning" aria-hidden="true"></i> 
                        <a href="{{ route('open_files.index',$p) }}" aria-current="page">{{$v}}</a> /
                    @else
                        <i class="fa fa-folder text-warning" aria-hidden="true"></i> 
                        <a href="{{ route('open_files.index',$p) }}">{{$v}}</a> /
                    @endif
                @endforeach
            </nav>

            <table class="table table-striped" aria-label="檔案與目錄列表">
                <caption class="sr-only">檔案庫目錄與檔案清單列表</caption>
                <thead class="thead-light">
                <tr>
                    <th scope="col">目錄 / 檔案名稱</th>
                    <th scope="col">類型</th>
                    <th scope="col">數量 / 大小</th>
                    <th scope="col">建立者</th>
                    <th scope="col">建立時間</th>
                </tr>
                </thead>
                <tbody>
                @if($path!=null)
                    <tr>
                        <td scope="row">
                            <i class="fas fa-arrow-circle-left" aria-hidden="true"></i> 
                            <a href="{{ route('open_files.index',$last_folder) }}" aria-label="返回上一層目錄">上一層</a>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                @foreach($folders as $folder)
                    <?php
                    $folder_p = $path.'&'.$folder->id;
                    ?>
                    <tr>
                        <td scope="row">
                            <i class="fas fa-folder text-warning" aria-hidden="true"></i> 
                            <a href="{{ route('open_files.index',$folder_p) }}">{{ $folder->name }}</a>
                        </td>
                        <td>
                            <?php 
                                $n = \App\Upload::where('folder_id',$folder->id)->count();                                                                
                            ?>
                            <strong>目錄</strong>
                            @auth                            
                            @if(($folder->user_id == auth()->user()->id and !empty($check_exec)) or auth()->user()->admin==1)
                                    <!-- 無障礙 HM1020401C 修正：改用 button 元素開窗 -->
                                    <button type="button" class="btn btn-link p-0 text-primary" onclick="open_window('{{ route('open_files.edit',[$folder->id,$folder_p]) }}','編輯目錄')" aria-label="編輯目錄：{{ $folder->name }}">
                                        <i class='fas fa-edit' aria-hidden="true"></i>
                                    </button>
                                @if($n == 0)
                                    <a href="{{ route('open_files.delete',$folder_p) }}" id="delete_folder{{ $folder->id }}" onclick="return confirm('確定刪除目錄嗎？')" aria-label="刪除目錄：{{ $folder->name }}">
                                        <i class="fas fa-minus-square text-danger" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endif
                            @endauth
                        </td>
                        <td>
                            {{ $n }} 個項目
                        </td>
                        <td>
                            @if(!empty($folder->job_title))
                                {{ $folder->job_title }}
                            @else
                                @if($folder->user->name == "系統管理員")
                                    系統管理員
                                @else
                                    {{ $folder->user->title }}
                                @endif
                            @endif                            
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$folder->name)))
                                {{ date ("Y-m-d H:i:s",filemtime(storage_path($f.'/'.$folder->name))) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                @foreach($files as $file)
                    <?php
                    $file_p = $path.'&'.$file->id;
                    ?>
                    <tr>
                        <td scope="row">
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                <?php $f2 = str_replace('app/public','',$f); ?>
                                <i class="fas fa-file text-info" aria-hidden="true"></i> 
                                <!-- 無障礙 HM1200101C 修正：另開新視窗補充提示訊息 -->
                                <a href="{{ asset('storage'.$f2.'/'.$file->name) }}" target="_blank" rel="noopener noreferrer" aria-label="開啟/下載檔案：{{ $file->name }} (另開新視窗)">
                                    {{ $file->name }}
                                </a>
                                <span class="sr-only">(另開新視窗)</span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-file" aria-hidden="true"></i> {{ $file->name }} (檔案已遺失)
                                </span>
                            @endif
                        </td>
                        <td>
                            檔案
                            @auth
                                @if(($file->user_id == auth()->user()->id and !empty($check_exec)) or auth()->user()->admin==1)
                                    <button type="button" class="btn btn-link p-0 text-primary" onclick="open_window('{{ route('open_files.edit',[$file->id,$file_p]) }}','編輯檔案')" aria-label="編輯檔案：{{ $file->name }}">
                                        <i class='fas fa-edit' aria-hidden="true"></i>
                                    </button>
                                    <a href="{{ route('open_files.delete',$file_p) }}" id="delete_file{{ $file->id }}" onclick="return confirm('確定刪除？')" aria-label="刪除檔案：{{ $file->name }}">
                                        <i class="fas fa-minus-square text-danger" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endauth
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                {{ filesizekb(storage_path($f.'/'.$file->name)) }} KB
                            @else
                                <small class="text-danger">已遺失</small>
                            @endif
                        </td>
                        <td>
                            @if(!empty($file->job_title))
                                {{ $file->job_title }}
                            @else
                                @if($file->user->name == "系統管理員")
                                    系統管理員
                                @else
                                    {{ $file->user->title }}
                                @endif
                            @endif                            
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                {{ date ("Y-m-d H:i:s",filemtime(storage_path($f.'/'.$file->name))) }}
                            @else
                                <small class="text-danger">已遺失</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @foreach($clouds as $cloud)
                    <?php
                    $file_p = $path.'&'.$cloud->id;
                    ?>
                    <tr>
                        <td scope="row">
                            <span class="text-primary">
                                <i class="fas fa-cloud" aria-hidden="true"></i> 
                                <a href="{{ $cloud->url }}" target="_blank" rel="noopener noreferrer" aria-label="開啟雲端連結：{{ $cloud->name }} (另開新視窗)">
                                    {{ $cloud->name }}
                                </a>
                                <span class="sr-only">(另開新視窗)</span>
                            </span>
                        </td>
                        <td>
                            雲端連結
                            @auth
                                @if(($cloud->user_id == auth()->user()->id and !empty($check_exec)) or auth()->user()->admin==1)
                                    <button type="button" class="btn btn-link p-0 text-primary" onclick="open_window('{{ route('open_files.edit',[$cloud->id,$file_p]) }}','編輯雲端連結')" aria-label="編輯雲端連結：{{ $cloud->name }}">
                                        <i class='fas fa-edit' aria-hidden="true"></i>
                                    </button>
                                    <a href="{{ route('open_files.delete',$file_p) }}" id="delete_file{{ $cloud->id }}" onclick="return confirm('確定刪除？')" aria-label="刪除雲端連結：{{ $cloud->name }}">
                                        <i class="fas fa-minus-square text-danger" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endauth
                        </td>
                        <td>
                            -
                        </td>
                        <td>
                            @if(!empty($cloud->job_title))
                                {{ $cloud->job_title }}
                            @else
                                @if($cloud->user->name == "系統管理員")
                                    系統管理員
                                @else
                                    {{ $cloud->user->title }}
                                @endif
                            @endif                            
                        </td>
                        <td>
                            {{ $cloud->created_at }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <hr>
            
            @can('create',\App\Upload::class)
                <div class="card my-4">
                    <h2 class="card-header h5">新增區塊</h2>
                    <div class="card-body">
                        @include('layouts.hd')
                        
                        {{ Form::open(['route' => 'open_files.create_folder', 'method' => 'POST','id'=>'this_form','onsubmit'=>"return submitOnce(this)"]) }}
                        <div class="form-group">
                            <!-- 無障礙 HM1150100C 修正：補充標籤屬性 -->
                            <label for="folder_name"><strong>1. 子目錄</strong></label>
                            {{ Form::text('name',null,['id'=>'folder_name','class' => 'form-control','placeholder'=>'請輸入子目錄名稱','required'=>'required', 'aria-label' => '子目錄名稱']) }}
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                            <input type="hidden" name="path" value="{{ $path }}">
                            <button type="submit" class="btn btn-success btn-sm" id="submit_button" onclick="if(confirm('您確定新增子目錄嗎?')){change_button1();return true;}else return false">
                                <i class="fas fa-plus" aria-hidden="true"></i> 新增子目錄
                            </button>
                        </div>
                        {{ Form::close() }}
                        <hr>
                        
                        @include('layouts.errors')
                        @if($per < 100)
                            {{ Form::open(['route' => 'open_files.upload_file', 'method' => 'POST','id'=>'this_form2','files' => true,'onsubmit'=>"return submitOnce(this)"]) }}
                            <div class="form-group">
                                <label for="upload_files">
                                    <strong>2. 上傳檔案 (不大於10MB，若為文字檔，請改為 [ 
                                        <a href="https://www.ndc.gov.tw/cp.aspx?n=d6d0a9e658098ca2" target="_blank" rel="noopener noreferrer">ODF格式<span class="sr-only">(另開新視窗)</span></a> ] [ 
                                        <a href="{{ asset('ODF.pdf') }}" target="_blank" rel="noopener noreferrer">詳細公文 (PDF)<span class="sr-only">(另開新視窗)</span></a> ] [ 
                                        <a href="{{ asset('office2016_odt_pdf.png') }}" target="_blank" rel="noopener noreferrer">轉檔教學<span class="sr-only">(另開新視窗)</span></a> ] )
                                    </strong>
                                    <small class="text-secondary d-block">支援格式：csv, txt, zip, jpeg, png, pdf, odt, ods, mp3</small>
                                </label>
                                {{ Form::file('files[]', ['id' => 'upload_files', 'class' => 'form-control-file','multiple'=>'multiple','required'=>'required', 'aria-label' => '選擇上傳檔案']) }}
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="submit" class="btn btn-success btn-sm" id="submit_button2" onclick="if(confirm('您確定新增檔案嗎?')){change_button2();return true;}else return false">
                                    <i class="fas fa-plus" aria-hidden="true"></i> 新增檔案
                                </button>
                            </div>
                            {{ Form::close() }}
                        @endif

                        {{ Form::open(['route' => 'open_files.upload_cloud', 'method' => 'POST','id'=>'this_form3']) }}
                            <div class="form-group">
                                <label for="cloud_name"><strong>3. 雲端連結</strong></label>
                                {{ Form::text('name',null,['id'=>'cloud_name','class' => 'form-control mb-2','placeholder'=>'請輸入雲端資源名稱','required'=>'required', 'aria-label' => '雲端資源名稱']) }}
                                <label for="cloud_url" class="sr-only">雲端網址</label>
                                {{ Form::text('url',null,['id'=>'cloud_url','class' => 'form-control','placeholder'=>'請輸入雲端網址 (例：https://...)','required'=>'required', 'aria-label' => '雲端網址']) }}
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="submit" class="btn btn-success btn-sm" onclick="if(confirm('您確定新增雲端檔案嗎?')){return true;}else return false">
                                    <i class="fas fa-plus" aria-hidden="true"></i> 新增雲端連結
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <script>
        var submitcount = 0;
        function submitOnce (form){
            if (submitcount == 0){
                submitcount++;
                return true;
            } else{
                alert('正在操作,請不要重複提交,謝謝!');
                return false;
            }
        }
        function change_button1(){
            $("#submit_button").removeAttr('onclick');
            $("#submit_button").attr('disabled','disabled');
            $("#submit_button").addClass('disabled');
            $("#this_form").submit();
        }
        function change_button2(){
            $("#submit_button2").removeAttr('onclick');
            $("#submit_button2").attr('disabled','disabled');
            $("#submit_button2").addClass('disabled');
            $("#this_form2").submit();
        }
        function open_window(url, name)
        {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=300');
        }
    </script>
@endsection