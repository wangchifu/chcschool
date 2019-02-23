@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '首頁')

@section('content')
    <h1>
        網站設定
    </h1>
    @include('setups.nav')
    <div class="card my-4">
        <h3 class="card-header">網站小圖示</h3>
        <div class="card-body">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <div style="float:left;padding: 10px;">
                    <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="50">
                    <a href="{{ route('setups.del_img',['folder'=>'title_image','filename'=>'logo.ico']) }}" id="del_logo" onclick="return confirm('確定移除小圖示嗎？')">
                        <i class="fas fa-times-circle text-danger"></i></a>
                </div>
            @else
                {{ Form::open(['route' => 'setups.add_logo', 'method' => 'post','id'=>'logo', 'files' => true]) }}
                <div class="form-group">
                    <label for="file">圖檔( .ico .png )</label>
                    {{ Form::file('logo', ['class' => 'form-control','required'=>'required']) }}
                </div>
                <div class="form-group">

                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定上傳？')">
                        <i class="fas fa-save"></i> 儲存設定
                    </button>
                </div>
                @include('layouts.errors')
                {{ Form::close() }}
            @endif
        </div>
    </div>
    {{ Form::open(['route' => 'setups.add_imgs', 'method' => 'post', 'files' => true]) }}
    <div class="card my-4">
        <h3 class="card-header">輪播照片</h3>
        <div class="card-body">
            <div class="form-group">
                <label for="files[]">圖檔( 2000 x 400 )</label>
                {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                    <i class="fas fa-save"></i> 儲存設定
                </button>
            </div>
            @if(!empty($photos))
                @foreach($photos as $k=>$v)
                    <div style="float:left;padding: 10px;">
                        <img src="{{ asset('storage/'.$school_code.'/title_image/random/'.$v) }}" width="200">
                        <a href="{{ route('setups.del_img',['folder'=>'title_image&random','filename'=>$v]) }}" onclick="return confirm('確定移除輪播圖片嗎')">
                            <i class="fas fa-times-circle text-danger"></i></a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    {{ Form::close() }}
@endsection
