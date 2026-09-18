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
            $active[2] = "";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            $active[7] = "active";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">網站導覽</h3>
                <div class="card-body">
                    {{ Form::open(['route' => 'setups.sitemap_store', 'method' => 'POST','id'=>'this_form','onsubmit'=>"return submitOnce(this)"]) }}
                    <div class="form-group">
                        <label for="content"><strong class="text-danger">內文*</strong></label>
                        <textarea name="sitemap" id="sitemap" class="form-control" rows="30" required placeholder="請輸入內容">{{ $setup->sitemap }}</textarea>
                    </div>
                    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('sitemap'
                            ,{
                                height: 400,
                                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                            });
                    </script>
                    <div class="form-group">                        
                        <button type="submit" id="submit_button" class="btn btn-primary btn-sm" onclick="if(confirm('您確定送出嗎?')){change_button();return true;}else return false">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    {{ Form::close() }}                    
                </div>
            </div>
        </div>
    </div>   
    <script>
        var validator = $("#this_form").validate();        
    </script>     
@endsection
