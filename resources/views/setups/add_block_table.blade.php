@extends('layouts.master_clean')

@section('title', '新增區塊 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => 'setups.add_block', 'method' => 'post','id'=>'this_form']) }}
    <table class="table">
        <tr>
            <td>
                <div class="form-group">
                    <label for="site_name">1.放置欄位</label>
                    {{ Form::select('setup_col_id', $setup_array,null, ['class' => 'form-control','placeholder'=>'']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="order_by">2.排序</label>
                    {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="site_name">3.名稱</label>
                    {{ Form::text('title',null,['class' => 'form-control','required'=>'required']) }}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    <label for="site_name">4.<a href="{{ route('setups.block_color') }}">顏色</a></label>
                    {{ Form::select('block_color', $block_colors,null, ['class' => 'form-control','placeholder'=>'']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="block_position">5.標題位置</a></label>
                    <select name="block_position" id="block_position" class="form-control">
                        <option value="text-left">置左</option>
                        <option value="text-center">置中</option>
                        <option value="text-right">置右</option>
                        <option value="disable">不顯示標題</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="site_name">6.框線</label>
                    <select class="form-control" name="disable_block_line">
                        <option value="">*有*框線</option>
                        <option value="1">*無*框線</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="form-group">
                    <label for="content">7.內文*</label>
                    {{ Form::textarea('content',null,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
                </div>
            </td>
        </tr>
    </table>
    <script src="{{ asset('mycke/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('my-editor'
            ,{
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
            });
    </script>
    <div class="form-group">
        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定新增？')">
            <i class="fas fa-plus"></i> 新增區塊
        </button>
    </div>
    {{ Form::close() }}
@endsection
