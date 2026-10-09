@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>網站設定</h1>

            <?php
            $active[1] = "";
            $active[2] = "active";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            $active[7] = "";
            ?>
            @include('setups.nav', $active)

            <!-- 頂部標題區 -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3 class="m-0">網站圖示與首頁輪播照片</h3>
            </div>

            <!-- 1. 網站小圖示設定卡片 -->
            <div class="card my-3 shadow-sm">
                <div class="card-header bg-light text-dark font-weight-bold">
                    <i class="fas fa-icons mr-1"></i> 網站小圖示設定 (Favicon / Logo)
                </div>
                <div class="card-body bg-light">
                    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                        <div class="d-flex align-items-center bg-white p-3 border rounded shadow-sm" style="max-width: 400px;">
                            <div class="mr-3 border p-2 bg-light rounded text-center">
                                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="48" height="48" alt="Logo">
                            </div>
                            <div>
                                <h6 class="mb-1 font-weight-bold text-dark">目前網站小圖示</h6>
                                <small class="text-muted d-block mb-2">logo.ico</small>
                                <a href="{{ route('setups.del_img',['folder'=>'title_image','filename'=>'logo.ico']) }}" id="del_logo" class="btn btn-outline-danger btn-sm" onclick="return confirm('確定移除小圖示嗎？')">
                                    <i class="fas fa-trash-alt mr-1"></i> 移除圖示
                                </a>
                            </div>
                        </div>
                    @else
                        {{ Form::open(['route' => 'setups.add_logo', 'method' => 'post', 'id' => 'this_form1', 'files' => true]) }}
                        <div class="bg-white p-3 border rounded shadow-sm" style="max-width: 600px;">
                            <div class="form-group mb-3">
                                <label for="logo" class="font-weight-bold">上傳新圖示 (支援 .ico, .png 格式)</label>
                                {{ Form::file('logo', ['class' => 'form-control-file border p-2 rounded w-100', 'required' => 'required']) }}
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm px-3" onclick="return confirm('確定上傳？')">
                                <i class="fas fa-upload mr-1"></i> 上傳並儲存圖示
                            </button>
                        </div>
                        @include('layouts.errors')
                        {{ Form::close() }}
                    @endif
                </div>
            </div>

            <!-- 2. 輪播照片管理卡片 -->
            <div class="card my-4 shadow-sm">
                <div class="card-header bg-light text-dark font-weight-bold">
                    <i class="fas fa-images mr-1"></i> 首頁輪播相片管理
                </div>
                <div class="card-body bg-light">
                    
                    <!-- 輪播播放模式與開關設定 -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2">
                                <i class="fas fa-sliders-h mr-1"></i> 輪播展示與效果設定
                            </h5>
                            {{ Form::open(['route' => ['setups.update_title_image', $setup->id], 'method' => 'patch']) }}
                            <?php
                            $check1 = $setup->title_image ? "checked" : "";
                            $check2 = !$setup->title_image ? "checked" : "";
                            
                            $title_image_style_check1 = ($setup->title_image_style == 1 || $setup->title_image_style == null) ? "checked" : "";
                            $title_image_style_check2 = ($setup->title_image_style == 2) ? "checked" : "";
                            ?>
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <label class="font-weight-bold d-block mb-2">輪播功能啟用狀態：</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="title_image" value="1" id="enable" class="custom-control-input" {{ $check1 }}>
                                        <label class="custom-control-label text-success font-weight-bold" for="enable">
                                            <i class="fas fa-check-circle mr-1"></i> 啟用輪播
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="title_image" value="" id="disable" class="custom-control-input" {{ $check2 }}>
                                        <label class="custom-control-label text-secondary" for="disable">
                                            <i class="fas fa-ban mr-1"></i> 停用輪播
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <label class="font-weight-bold d-block mb-2">切換動畫效果：</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="title_image_style" value="1" id="title_image_style1" class="custom-control-input" {{ $title_image_style_check1 }}>
                                        <label class="custom-control-label" for="title_image_style1">左右滑動 (Slide)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="title_image_style" value="2" id="title_image_style2" class="custom-control-input" {{ $title_image_style_check2 }}>
                                        <label class="custom-control-label" for="title_image_style2">淡入淡出 (Fade)</label>
                                    </div>
                                </div>
                                <div class="col-md-2 text-md-right">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block" onclick="return confirm('確定儲存輪播設定嗎？')">
                                        <i class="fas fa-save mr-1"></i> 儲存設定
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <!-- 批次新增圖片 -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body bg-white rounded">
                            <h5 class="card-title font-weight-bold text-primary border-bottom pb-2">
                                <i class="fas fa-file-upload mr-1"></i> 批次新增輪播照片
                            </h5>
                            {{ Form::open(['route' => 'setups.add_imgs', 'method' => 'post', 'files' => true, 'id' => 'this_form2']) }}
                            <div class="row align-items-end">
                                <div class="col-md-9 mb-2 mb-md-0">
                                    <label for="files[]" class="font-weight-bold mb-1">選擇圖檔 (可多選)</label>
                                    <small class="text-info d-block mb-2">
                                        <i class="fas fa-info-circle"></i> 建議圖片尺寸：<strong>2000 x 400 像素</strong>，以獲得最佳視覺呈現。
                                    </small>
                                    {{ Form::file('files[]', ['class' => 'form-control-file border p-2 rounded w-100', 'multiple' => 'multiple', 'required' => 'required']) }}
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-sm btn-block" onclick="return confirm('確定上傳選擇的照片嗎？')">
                                        <i class="fas fa-cloud-upload-alt mr-1"></i> 開始上傳圖片
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <!-- 輪播照片清單管理表格 -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body bg-white rounded p-0">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-weight-bold text-primary m-0">
                                    <i class="fas fa-list-ol mr-1"></i> 現有輪播照片內容與排序設定
                                </h5>
                                <span class="badge badge-info">共 {{ count($photo_data) }} 個圖片群組</span>
                            </div>

                            <form method="post" action="{{ route('setups.photo_desc') }}">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 110px;" class="text-center">出現比重</th>
                                                <th style="width: 140px;" class="text-center">狀態</th>
                                                <th style="width: 220px;">圖片預覽 / 檔名</th>
                                                <th style="width: 200px;">點擊連結</th>
                                                <th>標題與說明文字</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($photo_data) > 0)
                                                @foreach($photo_data as $k1 => $v1)
                                                    @foreach($v1 as $k2 => $v2)
                                                        <tr>
                                                            <td class="text-center align-middle">
                                                                <input type="number" class="form-control form-control-sm text-center font-weight-bold" name="order_by[{{ $k2 }}]" value="{{ $k1 }}">
                                                                <small class="text-muted">數字大者優先</small>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <?php
                                                                 $checked1 = ($v2['disable'] == null) ? "checked" : null;
                                                                 $checked2 = ($v2['disable']) ? "checked" : null;
                                                                ?>
                                                                <div class="custom-control custom-radio mb-1 text-left pl-4">
                                                                    <input type="radio" name="disable[{{ $k2 }}]" value="" id="enable{{ $k2 }}" class="custom-control-input" {{ $checked1 }}>
                                                                    <label class="custom-control-label text-success font-weight-bold" for="enable{{ $k2 }}">顯示</label>
                                                                </div>
                                                                <div class="custom-control custom-radio text-left pl-4">
                                                                    <input type="radio" name="disable[{{ $k2 }}]" value="1" id="disable{{ $k2 }}" class="custom-control-input" {{ $checked2 }}>
                                                                    <label class="custom-control-label text-secondary" for="disable{{ $k2 }}">隱藏</label>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="position-relative d-inline-block mb-1">
                                                                    <img src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" class="img-thumbnail shadow-sm" style="max-width: 180px; height: auto;" alt="輪播圖">
                                                                    <a href="{{ route('setups.del_img',['folder'=>'title_image&random','filename'=>$k2]) }}" 
                                                                       class="btn btn-danger btn-sm rounded-circle position-absolute" 
                                                                       style="top: -8px; right: -8px; padding: 2px 6px;" 
                                                                       title="刪除照片"
                                                                       onclick="return confirm('確定移除這張輪播圖片嗎？')">
                                                                        <i class="fas fa-times"></i>
                                                                    </a>
                                                                </div>
                                                                <small class="d-block text-muted text-break"><code>{{ $k2 }}</code></small>
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="form-group mb-0">
                                                                    <label class="sr-only">連結</label>
                                                                    <input type="text" class="form-control form-control-sm" name="link[{{ $k2 }}]" value="{{ $v2['link'] }}" placeholder="https://...">
                                                                </div>
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="form-group mb-2">
                                                                    <input type="text" class="form-control form-control-sm font-weight-bold" name="title[{{ $k2 }}]" value="{{ $v2['title'] }}" placeholder="圖片標題 (選填)">
                                                                </div>
                                                                <div class="form-group mb-0">
                                                                    <input type="text" class="form-control form-control-sm text-secondary" name="desc[{{ $k2 }}]" value="{{ $v2['desc'] }}" placeholder="圖片詳細說明/內容簡述 (選填)">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="image_name[{{ $k2 }}]" value="{{ $k2 }}">
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        <i class="far fa-image fa-2x mb-2 d-block"></i>
                                                        目前尚無任何輪播照片，請使用上方表單上傳照片。
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 bg-light border-top text-right">
                                    <button type="submit" class="btn btn-primary px-4" onclick="return confirm('確定儲存所有輪播照片設定嗎？')">
                                        <i class="fas fa-save mr-1"></i> 儲存全部輪播相片變更
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        var validator1 = $("#this_form1").validate();
        var validator2 = $("#this_form2").validate();
    </script>
@endsection