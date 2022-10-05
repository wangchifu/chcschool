@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('colorpicker/css/htmleaf-demo.css') }}">
    <link href="{{ asset('colorpicker/dist/css/bootstrap-colorpicker.css') }}" rel="stylesheet">
    <style type="text/css">
        .colorpicker-component{margin-top: 10px;}
    </style>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                網站設定
            </h1>
            <?php
            $active[1] = "active";
            $active[2] = "";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">基本設定</h3>
                <div class="card-body">
                    @include('layouts.errors')
                    {{ Form::open(['route' => ['setups.text',$setup->id], 'method' => 'patch','id'=>'this_form1']) }}
                    <div class="form-group">
                        <label for="site_name">網站名稱</label>
                        {{ Form::text('site_name',$setup->site_name,['class' => 'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="views">學校真實IP範圍</label><br>
                        <small>[<a href="http://gg.gg/chc_ipv4"  target="_blank">ipv4參考文件</a>]</small> <small>[<a href="http://gg.gg/chc_ipv6" target="_blank">ipv6參考文件</a>]</small>
                        <table>
                            <tr>
                                <td>
                                    IPv4 從
                                </td>
                                <td>
                                    {{ Form::text('ip1',$setup->ip1,['class' => 'form-control']) }}
                                </td>
                                <td>
                                    到
                                </td>
                                <td>
                                    {{ Form::text('ip2',$setup->ip2,['class' => 'form-control']) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    IPv6
                                </td>
                                <td colspan="3">
                                    {{ Form::text('ipv6',$setup->ipv6,['class' => 'form-control','placeholder'=>'如：2001:288:5637::/48']) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="views">瀏覽人數</label>
                        {{ Form::text('views',$setup->views,['class' => 'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <label for="footer">置底</label>
                        {{ Form::textarea('footer',$setup->footer,['id'=>'footer','class'=>'form-control']) }}
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('footer'
                            ,{
                                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                            });
                    </script>
                    <?php 
                        $r1 = (empty($setup->close_website))?"checked":"";
                        $r2 = (empty($setup->close_website))?"":"checked";
                    ?>
                    <div class="form-group">
                        <label for="footer">關閉網站</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="set_close_website" value="on" {{ $r1 }}>設定為「網站開放」
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label text-danger">
                                          <input type="radio" class="form-check-input" name="set_close_website" value="off" {{ $r2 }}>設定為「網站關閉」
                                        </label>
                                    </div>
                                    <label for="close_website">原因</label>
                                    {{ Form::text('close_website',$setup->close_website,['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            <?php
                $nav_color = explode(',',$setup->nav_color);
                $c1 = (empty($nav_color[0]))?"#DD0F20":$nav_color[0];
                $c2 = (empty($nav_color[1]))?"#F18A31":$nav_color[1];
                $c3 = (empty($nav_color[2]))?"#F8EB48":$nav_color[2];
                $c4 = (empty($nav_color[3]))?"#16813D":$nav_color[3];
            ?>
            {{ Form::open(['route' => ['setups.nav_color',$setup->id], 'method' => 'patch','id'=>'this_form2']) }}
            <div class="card my-4">
                <h3 class="card-header">顏色設定</h3>
                <div class="card-body">
                    <div id="cp1" class="input-group mb-3 colorpicker-component">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">導覽列顏色</span>
                        </div>
                        <input type="text" class="form-control input-lg" value="{{ $c1 }}" id="nav_color1" name="color[]">
                        <div class="input-group-append">
                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                        </div>
                    </div>
                    <div id="cp2" class="input-group mb-3 colorpicker-component">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2">文字顏色</span>
                        </div>
                        <input type="text" class="form-control input-lg" value="{{ $c2 }}" id="nav_color2" name="color[]">
                        <div class="input-group-append">
                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                        </div>
                    </div>
                    <div id="cp3" class="input-group mb-3 colorpicker-component">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">連結文字顏色</span>
                        </div>
                        <input type="text" class="form-control input-lg" value="{{ $c3 }}" id="nav_color3" name="color[]">
                        <div class="input-group-append">
                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                        </div>
                    </div>
                    <div id="cp4" class="input-group mb-3 colorpicker-component">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon4">連結文字移上時顏色</span>
                        </div>
                        <input type="text" class="form-control input-lg" value="{{ $c4 }}" id="nav_color4" name="color[]">
                        <div class="input-group-append">
                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                        @if(!empty($setup->nav_color))
                            <a href="{{ route('setups.nav_default') }}" class="btn btn-danger btn-sm" id="default_color" onclick="return confirm('確定還原嗎')">
                                <i class="fas fa-trash"></i> 還原預設
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script src="{{ asset('colorpicker/dist/js/bootstrap-colorpicker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#mycp').colorpicker();
        });
        $(function () {
            $('#cp1,#cp2,#cp3,#cp4').colorpicker();
        });


        var validator1 = $("#this_form1").validate();
        var validator2 = $("#this_form2").validate();
    </script>
@endsection
