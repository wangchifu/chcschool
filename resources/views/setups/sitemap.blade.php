@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>網站設定</h1>

            <?php
            $active[1] = "";
            $active[2] = "";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            $active[7] = "active";
            ?>
            @include('setups.nav', $active)

            <!-- 頂部標題區 -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3 class="m-0">網站導覽頁面內容編輯</h3>
            </div>

            <!-- 主卡片容器與表單 -->
            {{ Form::open(['route' => 'setups.sitemap_store', 'method' => 'POST', 'id' => 'this_form', 'onsubmit' => "return submitOnce(this)"]) }}
                <div class="card my-3 shadow-sm border">
                    <!-- 頁首：bg-light text-dark -->
                    <div class="card-header bg-light text-dark font-weight-bold d-flex justify-content-between align-items-center py-3">
                        <span style="font-size: 1.1rem;">
                            <i class="fas fa-sitemap mr-2 text-primary"></i> 網站導覽 (Sitemap) 內容設定
                        </span>                        
                    </div>
                    <textarea name="sitemap" id="sitemap" class="form-control" rows="30" required placeholder="請輸入網站導覽相關內容及定位點說明...">{{ $setup->sitemap }}</textarea>                  
                <!-- 頁尾儲存按鈕 -->
                    <div class="card-footer bg-light text-right py-3">
                        <button type="submit" id="submit_button" class="btn btn-primary px-4" onclick="if(confirm('您確定送出嗎?')){change_button();return true;}else return false">
                            <i class="fas fa-save mr-1"></i> 儲存網站導覽設定
                        </button>
                    </div>
                </div>
            {{ Form::close() }}

        </div>
    </div>

    <!-- CKEditor 編輯器載入與設定 -->
    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('sitemap', {
            height: 450,
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
        });

        var validator = $("#this_form").validate();
    </script>
@endsection