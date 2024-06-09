@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                網站設定
            </h1>
            <?php
            $active[1] = "";
            $active[2] = "active";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            ?>
            @include('setups.nav',$active)
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
                        {{ Form::open(['route' => 'setups.add_logo', 'method' => 'post','id'=>'this_form1', 'files' => true]) }}
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
            <div class="card my-4">
                <h3 class="card-header">輪播照片</h3>
                <div class="card-body">
                    {{ Form::open(['route' => ['setups.update_title_image',$setup->id], 'method' => 'patch']) }}
                    <div class="form-group">
                        <?php
                        if($setup->title_image){
                            $check1 = "checked";
                            $check2 = "";
                        }else{
                            $check1 = "";
                            $check2 = "checked";
                        }
                        ?>
                        <input type="radio" name="title_image" value="1" id="enable" {{ $check1 }}>
                        <label for="enable">啟用</label>
                        <span>　</span>
                        <input type="radio" name="title_image" value="" id="disable" {{ $check2 }}>
                        <label for="disable">停用</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    {{ Form::close() }}
                    {{ Form::open(['route' => 'setups.add_imgs', 'method' => 'post', 'files' => true,'id'=>'this_form2']) }}
                    <div class="form-group">
                        <label for="files[]">圖檔( 2000 x 400 )</label>
                        {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    {{ Form::close() }}
                    <form method="post" action="{{ route('setups.photo_desc') }}">
                    @csrf
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    出現比重(數字大，出現早)
                                </th>
                                <th>
                                    啟用？
                                </th>
                                <th>
                                    圖片
                                </th>
                                <th>
                                    連結
                                </th>
                                <th>
                                    標題
                                </th>
                                <th>
                                    說明
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($photo_data as $k1=>$v1)
                                @foreach($v1 as $k2=>$v2)
                                    <tr>
                                        <td>
                                            <input type="number" class="form-control" name="order_by[{{ $k2 }}]" value="{{ $k1 }}">
                                        </td>
                                        <td>
                                            <?php
                                             $checked1 = ($v2['disable']==null)?"checked":null;
                                             $checked2 = ($v2['disable'])?"checked":null;
                                            ?>
                                            <input type="radio" name="disable[{{ $k2 }}]" value="" id="enable{{ $k2 }}" {{ $checked1 }}>
                                            <label for="enable{{ $k2 }}">啟用</label>
                                            <span>　</span>
                                            <input type="radio" name="disable[{{ $k2 }}]" value="1" id="disable{{ $k2 }}" {{ $checked2 }}>
                                            <label for="disable{{ $k2 }}">停用</label>
                                        </td>
                                        <td>
                                            <img src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" width="200">
                                            <br>
                                            {{ $k2 }}
                                            <a href="{{ route('setups.del_img',['folder'=>'title_image&random','filename'=>$k2]) }}" onclick="return confirm('確定移除輪播圖片嗎')">
                                                <i class="fas fa-times-circle text-danger"></i></a>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="link[{{ $k2 }}]" value="{{ $v2['link'] }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="title[{{ $k2 }}]" value="{{ $v2['title'] }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="desc[{{ $k2 }}]" value="{{ $v2['desc'] }}">
                                        </td>
                                    </tr>
                                    <input type="hidden" name="image_name[{{ $k2 }}]" value="{{ $k2 }}">
                                @endforeach
                            @endforeach
                            
                        </tbody>
                    </table> 
                        <button class="btn btn-primary" onclick="return confirm('確定？')">全部儲存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var validator1 = $("#this_form1").validate();
        var validator2 = $("#this_form2").validate();
        var validator3 = $("#this_form3").validate();
    </script>
@endsection
