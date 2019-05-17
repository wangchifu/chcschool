@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '內部文件 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>內部文件</h1>
            <?php
            $final = end($folder_path);
            $final_key = key($folder_path);
            $p="";
            $f="app/privacy/".$school_code."/inside_files";
            $last_folder = "";
            ?>
            路徑：
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
                    <i class="fa fa-folder-open text-warning"></i> <a href="{{ route('inside_files.index',$p) }}">{{$v}}</a>/
                @else
                    <i class="fa fa-folder text-warning"></i> <a href="{{ route('inside_files.index',$p) }}">{{$v}}</a>/
                @endif
            @endforeach
            <hr>
            <div class="container-fluid">
                <div class="row">
                    @foreach($folders as $folder)
                        <?php
                        $folder_p = $path.'&'.$folder->id;
                        ?>
                        <?php $n = \App\InsideFile::where('folder_id',$folder->id)->count();?>
                        <div class="col-lg-1 col-md2 col-sm-3 col-4">
                            <a href="{{ route('inside_files.index',$folder_p) }}">
                                <img src="{{ asset('images/folder.svg') }}">
                                <small>{{ $folder->name }}({{ $n }})</small>
                            </a>
                            @if($folder->user_id == auth()->user()->id or auth()->user()->admin==1)
                                @if($n == 0)
                                    <a href="{{ route('inside_files.delete',$folder_p) }}" id="delete_folder{{ $folder->id }}" onclick="return confirm('確定刪除目錄嗎？')"><i class="fas fa-minus-square text-danger"></i></a>
                                @endif
                            @endif
                        </div>
                    @endforeach
                    @foreach($files as $file)
                        <?php
                        $file_p = $path.'&'.$file->id;
                        ?>
                        <div class="col-lg-1 col-md2 col-sm-3 col-4">
                            <a href="{{ route('inside_files.download',$file_p) }}">
                                <img src="{{ asset('images/file.svg') }}">
                                <small>{{ $file->name }}</small>
                            </a>
                            @if($file->user_id == auth()->user()->id or auth()->user()->admin==1)
                                <a href="{{ route('inside_files.delete',$file_p) }}" id="delete_file{{ $file->id }}" onclick="return confirm('確定刪除？')"><i class="fas fa-minus-square text-danger"></i></a>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
            <hr>
            @can('create',\App\Upload::class)
                <div class="card my-4">
                    <h3 class="card-header">新增</h3>
                    <div class="card-body">
                        @include('layouts.hd')
                        {{ Form::open(['route' => 'inside_files.create_folder', 'method' => 'POST','id'=>'this_form']) }}
                        <div class="form-group">
                            <label for="name"><strong>1.子目錄</strong></label>
                            {{ Form::text('name',null,['id'=>'name','class' => 'form-control','placeholder'=>'名稱','required'=>'required']) }}
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                            <input type="hidden" name="path" value="{{ $path }}">
                            <button class="btn btn-success btn-sm" onclick="return confirm('確定新增子目錄')"><i class="fas fa-plus"></i> 新增子目錄</button>
                        </div>
                        {{ Form::close() }}
                        <hr>
                        @include('layouts.errors')
                        @if($per < 100)
                            {{ Form::open(['route' => 'inside_files.upload_file', 'method' => 'POST','id'=>'this_form2','files' => true]) }}
                            <div class="form-group">
                                <label for="file"><strong>2.檔案</label>
                                {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button class="btn btn-success btn-sm" onclick="return confirm('確定新增檔案')"><i class="fas fa-plus"></i> 新增檔案</button>
                            </div>
                            {{ Form::close() }}
                        @endif
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
        var validator2 = $("#this_form2").validate();
    </script>
@endsection
