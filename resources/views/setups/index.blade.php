@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('colorpicker/css/htmleaf-demo.css') }}">
    <link href="{{ asset('colorpicker/dist/css/bootstrap-colorpicker.css') }}" rel="stylesheet">
    <style type="text/css">
        .colorpicker-component { margin-top: 0px; }
    </style>
    
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>網站設定</h1>

            <?php
            $active[1] = "active";
            $active[2] = "";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            $active[7] = "";
            $nav_color = explode(',',$setup->nav_color);
            $c1 = (empty($nav_color[0]))?"#DD0F20":$nav_color[0];
            $c2 = (empty($nav_color[1]))?"#F18A31":$nav_color[1];
            $c3 = (empty($nav_color[2]))?"#F8EB48":$nav_color[2];
            $c4 = (empty($nav_color[3]))?"#16813D":$nav_color[3];
            $c5 = (empty($setup->bg_color))?"#f0f1f6":$setup->bg_color;            
            ?>
            @include('setups.nav',$active)

            <!-- 頂部標題區 -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3 class="m-0">基本設定與導覽列配置</h3>
            </div>

            <!-- 1. 基本設定卡片 -->
            <div class="card my-3 shadow-sm">
                <div class="card-header bg-light text-dark font-weight-bold">
                    <i class="fas fa-cog mr-1"></i> 網站基本資訊與設定
                </div>
                <div class="card-body bg-light">
                    @include('layouts.errors')
                    {{ Form::open(['route' => ['setups.text',$setup->id], 'method' => 'patch','id'=>'this_form1']) }}
                    
                    <!-- 區塊：基本資訊 -->
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2">
                                <i class="fas fa-info-circle mr-1"></i> 基本資料與外觀
                            </h5>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="site_name" class="font-weight-bold">網站名稱</label>
                                    {{ Form::text('site_name',$setup->site_name,['class' => 'form-control','required'=>'required']) }}
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="views" class="font-weight-bold">累積總瀏覽人數</label>
                                    {{ Form::text('views',$setup->views,['class' => 'form-control','required'=>'required']) }}
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="nav_color4" class="font-weight-bold">網頁背景色</label>
                                    <small class="text-muted">(<a href="https://www.toolskk.com/color" target="_blank">色碼表</a>)</small>
                                    <div id="cp5" class="input-group colorpicker-component">
                                        <input type="text" class="form-control" value="{{ $c5 }}" id="nav_color4" name="bg_color">
                                        <div class="input-group-append">
                                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 區塊：校園 IP 範圍 -->
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2">
                                <i class="fas fa-network-wired mr-1"></i> 學校真實 IP 範圍設定
                                <small class="text-muted ml-2 font-weight-normal">
                                    [<a href="{{ asset('ipv4.xlsx') }}" target="_blank"><i class="far fa-file-excel mr-1"></i>IPv4參考文件</a>]
                                    [<a href="{{ asset('ipv6.xlsx') }}" target="_blank"><i class="far fa-file-excel mr-1"></i>IPv6參考文件</a>]
                                </small>
                            </h5>
                            <div class="row">
                                <div class="col-md-6 form-group mb-md-0">
                                    <label class="font-weight-bold mb-1">IPv4 網段範圍：</label>
                                    <div class="d-flex align-items-center">
                                        <span class="mr-2 text-muted">從</span>
                                        {{ Form::text('ip1',$setup->ip1,['class' => 'form-control mr-2', 'placeholder' => '163.23.xxx.xxx']) }}
                                        <span class="mr-2 text-muted">到</span>
                                        {{ Form::text('ip2',$setup->ip2,['class' => 'form-control', 'placeholder' => '163.23.xxx.xxx']) }}
                                    </div>
                                </div>
                                <div class="col-md-6 form-group mb-0">
                                    <label for="ipv6" class="font-weight-bold mb-1">IPv6 網段範圍：</label>
                                    {{ Form::text('ipv6',$setup->ipv6,['class' => 'form-control','placeholder'=>'如：2001:288:5637::/48']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 區塊：頁尾 Footer -->
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2">
                                <i class="fas fa-shoe-prints mr-1"></i> 頁尾內容 (Footer)
                            </h5>
                            <div class="form-group mb-3">
                                <label for="footer" class="font-weight-bold">置底自訂內容 (id="footer")</label>
                                {{ Form::textarea('footer',$setup->footer,['id'=>'footer','class'=>'form-control']) }}
                            </div>

                            <?php 
                                $disable_right = ($setup->disable_right)?"checked":"";
                                $r1 = (empty($setup->close_website))?"checked":"";
                                $r2 = (empty($setup->close_website))?"":"checked";
                            ?>
                            <div class="form-group border p-3 rounded bg-light mb-0">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="disable_right" name="disable_right" {{ $disable_right }} value="1">
                                    <label class="custom-control-label font-weight-bold text-dark" for="disable_right">
                                        隱藏版權列 (id="footer_bottom")
                                    </label>
                                </div>
                                <div class="small text-muted mb-2">預設版權列真實呈現樣式：</div>
                                <!-- 修正為前台真實深灰色背景與完整的寫法 -->
                                <div class="footer-copyright text-center py-3 text-white rounded shadow-sm" id="footer_bottom" style="background-color: #6c757d;">
                                    {{ date('Y') }} Copyright © <a href="{{ route('index','index') }}" title="返回網站首頁" class="text-white text-decoration-underline">{{ $setup->site_name }}</a> 訪客人次:{{ $setup->views }} 訪客IP：{{ GetIP() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 區塊：網站維護開關 -->
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2">
                                <i class="fas fa-power-off mr-1"></i> 網站開放狀態與維護設定
                            </h5>
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="custom-control custom-radio custom-control-inline mb-2 d-block">
                                        <input type="radio" class="custom-control-input" id="site_open" name="set_close_website" value="on" {{ $r1 }}>
                                        <label class="custom-control-label text-success font-weight-bold" for="site_open">
                                            <i class="fas fa-check-circle mr-1"></i> 網站正常開放
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline d-block">
                                        <input type="radio" class="custom-control-input" id="site_close" name="set_close_website" value="off" {{ $r2 }}>
                                        <label class="custom-control-label text-danger font-weight-bold" for="site_close">
                                            <i class="fas fa-times-circle mr-1"></i> 暫停服務 (關閉網站)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label for="close_website" class="font-weight-bold mb-1">關閉網站原因公告：</label>
                                    {{ Form::text('close_website',$setup->close_website,['class' => 'form-control', 'placeholder' => '如：系統例行維護中，預計今日18:00恢復開放']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 儲存基本設定按鈕 -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-4" onclick="return confirm('確定儲存基本設定？')">
                            <i class="fas fa-save mr-1"></i> 儲存基本設定
                        </button>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>

            <!-- 2. 導覽列設定卡片 -->
            {{ Form::open(['route' => ['setups.nav_color',$setup->id], 'method' => 'patch','id'=>'this_form2']) }}
            <div class="card my-4 shadow-sm">
                <div class="card-header bg-light text-dark font-weight-bold">
                    <i class="fas fa-compass mr-1"></i> 上方導覽列與選單設定
                </div>
                <div class="card-body bg-light">
                    
                    <!-- 導覽列顏色與浮動 -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-palette mr-1"></i> 導覽列外觀與色彩對比</span>
                                <small class="text-muted font-weight-normal">(<a href="https://www.toolskk.com/color" target="_blank"><i class="fas fa-external-link-alt mr-1"></i>線上色碼表</a>)</small>
                            </h5>

                            <?php 
                                $checked = ($setup->fixed_nav)?"checked":null;
                            ?>
                            <div class="custom-control custom-checkbox mb-3 p-2 bg-light border rounded">
                                <input type="checkbox" name="fixed_nav" class="custom-control-input" id="customCheck1" {{ $checked }}>
                                <label class="custom-control-label font-weight-bold text-dark" for="customCheck1">
                                    <i class="fas fa-thumbtack mr-1 text-info"></i> 固定於頁面頂端 (Fixed Navbar)
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="nav_color1" class="font-weight-bold">導覽列背景顏色</label>
                                    <div id="cp1" class="input-group colorpicker-component">
                                        <input type="text" class="form-control" value="{{ $c1 }}" id="nav_color1" name="color[]">
                                        <div class="input-group-append">
                                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="nav_color2" class="font-weight-bold">網站名稱文字顏色</label>
                                    <div id="cp2" class="input-group colorpicker-component">
                                        <input type="text" class="form-control" value="{{ $c2 }}" id="nav_color2" name="color[]">
                                        <div class="input-group-append">
                                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group mb-md-0">
                                    <label for="nav_color3" class="font-weight-bold">選單連結文字顏色</label>
                                    <div id="cp3" class="input-group colorpicker-component">
                                        <input type="text" class="form-control" value="{{ $c3 }}" id="nav_color3" name="color[]">
                                        <div class="input-group-append">
                                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group mb-0">
                                    <label for="nav_color4" class="font-weight-bold">選單連結移上時 (Hover) 顏色</label>
                                    <div id="cp4" class="input-group colorpicker-component">
                                        <input type="text" class="form-control" value="{{ $c4 }}" id="nav_color4" name="color[]">
                                        <div class="input-group-append">
                                            <span class="input-group-addon btn btn-outline-secondary"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 系統按鈕改名 -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body bg-white rounded p-0">
                            <div class="p-3 border-bottom">
                                <h5 class="card-title font-weight-bold text-primary m-0">
                                    <i class="fas fa-edit mr-1"></i> 預設系統功能按鈕自訂名稱
                                </h5>
                            </div>
                            <div class="p-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle mb-0">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th style="width: 14%;">首頁</th>
                                                <th style="width: 14%;">公告系統</th>
                                                <th style="width: 14%;">檔案庫</th>
                                                <th style="width: 14%;">學校介紹</th>
                                                <th style="width: 14%;">校務行政</th>
                                                <th style="width: 14%;">系統設定</th>
                                                <th style="width: 14%;">登入</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ Form::text('homepage_name',$setup->homepage_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'首頁']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('post_name',$setup->post_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'公告系統']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('openfile_name',$setup->openfile_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'檔案庫']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('department_name',$setup->department_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'學校介紹']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('schoolexec_name',$setup->schoolexec_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'校務行政']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('setup_name',$setup->setup_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'系統設定']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('login_name',$setup->login_name,['class' => 'form-control form-control-sm text-center','placeholder'=>'登入']) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                                <a href="{{ route('setups.nav_default') }}" class="btn btn-outline-danger btn-sm" id="default_color" onclick="return confirm('確定要將導覽列還原為預設顏色嗎？')">
                                    <i class="fas fa-undo mr-1"></i> 還原導覽列預設值
                                </a>
                                <button type="submit" class="btn btn-primary px-4" onclick="return confirm('確定儲存導覽列設定？')">
                                    <i class="fas fa-save mr-1"></i> 儲存導覽列設定
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{ Form::close() }}

        </div>
    </div>

    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('footer', {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
        });
    </script>

    <script src="{{ asset('colorpicker/dist/js/bootstrap-colorpicker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#cp1,#cp2,#cp3,#cp4,#cp5').colorpicker();
        });

        var validator1 = $("#this_form1").validate();
        var validator2 = $("#this_form2").validate();
    </script>
@endsection