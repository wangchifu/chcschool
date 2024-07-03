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
                    <label for="site_name">3.標題名稱</label>
                    @if(str_contains($block->title,'系統區塊') or str_contains($block->title,'榮譽榜跑馬燈'))
                        <?php 
                            $new_title = (empty($block->new_title))?$block->title:$block->new_title;
                            $new_title=str_replace('(系統區塊)','',$new_title); 
                        ?>
                        {{ Form::text('new_title',$new_title,['class' => 'form-control','required'=>'required']) }}
                        <input type="hidden" name="title" value="{{ $block->title }}">
                    @else
                        {{ Form::text('title',$block->title,['class' => 'form-control','required'=>'required']) }}
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    <label for="site_name">4.<a href="{{ route('setups.block_color') }}">標題底色</a></label>
                    {{ Form::select('block_color', $block_colors,$block->block_color, ['class' => 'form-control','placeholder'=>'']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="block_position">5.標題位置</a></label>
                    <?php
                        if($block->block_position=="text-left"){
                            $select1 = "selected";
                            $select2 = null;
                            $select3 = null;
                            $select4 = null;
                        }
                        if($block->block_position=="text-center"){
                            $select1 = null;
                            $select2 = "selected";
                            $select3 = null;
                            $select4 = null;
                        }
                        if($block->block_position=="text-right"){
                            $select1 = null;
                            $select2 = null;
                            $select3 = "selected";
                            $select4 = null;
                        }
                        if($block->block_position=="disable"){
                            $select1 = null;
                            $select2 = null;
                            $select3 = null;
                            $select4 = "selected";
                        }
                        if($block->block_position==null){
                            $select1 = "selected";
                            $select2 = null;
                            $select3 = null;
                            $select4 = null;
                        }
                    ?>
                    <select name="block_position" id="block_position" class="form-control">
                        <option value="text-left" {{ $select1 }}>置左</option>
                        <option value="text-center" {{ $select2 }}>置中</option>
                        <option value="text-right" {{ $select3 }}>置右</option>
                        <option value="disable" {{ $select4 }}>不顯示標題</option>
                    </select>
                </div>
            </td>
            <td>
                <?php 
                    if($block->disable_block_line==null){
                            $select1 = "selected";
                            $select2 = null;
                        }
                    if($block->disable_block_line=="1"){
                            $select1 = null;
                            $select2 = "selected";
                        }
                ?>
                <div class="form-group">
                    <label for="site_name">6.框線</label>
                    <select class="form-control" name="disable_block_line">
                        <option value="" {{ $select1 }}>*有*框線</option>
                        <option value="1" {{ $select2 }}>*無*框線</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                @if(strpos($block->title,"跑馬燈"))
                    <div class="form-group">
                        <label for="content">6.跑馬燈設定*</label>
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
                    <label for="content">6.內文*</label>
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
    <hr>
    標題底色參考：<br>
    <a href="{{ route('setups.block_color') }}"><img src="{{ asset('color.png') }}" class="img-thumbnail" alt="..."></a>
@endsection
