@extends('layouts.master_clean')

@section('title', '編輯區塊 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => ['setups.update_block',$block->id], 'method' => 'patch']) }}
    <table class="table">
        <tr>
            <td>
                <div class="form-group">
                    <label for="site_name">1.放置欄位</label>
                    {{ Form::select('setup_col_id', $setup_array,$block->setup_col_id, ['class' => 'form-control','placeholder'=>'']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="order_by">2.排序</label>
                    {{ Form::text('order_by',$block->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="site_name">3.名稱</label>
                    @if(strpos($block->title,'系統區塊') or strpos($block->title,'跑馬燈'))
                        {{ Form::text('title',$block->title,['class' => 'form-control','readonly'=>'readonly']) }}
                    @else
                        {{ Form::text('title',$block->title,['class' => 'form-control','required'=>'required']) }}
                    @endif
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="site_name">4.<a href="{{ route('setups.block_color') }}">顏色</a></label>
                    {{ Form::select('block_color', $block_colors,$block->block_color, ['class' => 'form-control','placeholder'=>'']) }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                @if(strpos($block->title,"跑馬燈"))
                    <div class="form-group">
                        <label for="content">4.跑馬燈設定*</label>
                        {{ Form::textarea('content',$block->content,['id'=>'marquee-editor','class'=>'form-control','required'=>'required']) }}
                    </div>
                    <div class="form-group">
                        <div class="alert alert-light" role="alert">
                            方向設定：direction="參數值"；可設定 up（向上）、dun（向下）、left（向左）、right（向右）<br>
                            速度設定：scrollamount="參數值" ；可設定為數字，通常設定 1~10 的範圍，數字越大跑得越快<br>
                            長度設定：height="參數值"；數字，自行設定<br>
                            寬度設定：width="參數值"；數字，自行設定<br>
                            行為設定：behavior="參數值"；可設定 alternate（來回跑）、slide（跑入後停止）<br>
                            背景顏色：bgcolor="參數值"；可設定為顏色的色碼，不設定則沒有顏色<br>
                        </div>
                    </div>
                @elseif(!strpos($block->title,'系統區塊'))
                <div class="form-group">
                    <label for="content">4.內文*</label>
                    {{ Form::textarea('content',$block->content,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
                </div>
                @endif
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
        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
            <i class="fas fa-save"></i> 儲存區塊
        </button>
    </div>
    {{ Form::close() }}
    @if(strpos($block->title,"跑馬燈"))
        
    @elseif(!strpos($block->title,'系統區塊'))
    <div class="text-right">
        <form action="{{ route('setups.delete_block',$block->id) }}" method="post">
            @csrf
            @method('delete')
            <button class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？若有區塊放置在此欄位，記得去變更！')">刪除</button>
        </form>
    </div>
    @endif
@endsection
