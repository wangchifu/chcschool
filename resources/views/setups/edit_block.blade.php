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
                    @if($block->id == 1)
                        {{ Form::text('title',$block->title,['class' => 'form-control','readonly'=>'readonly']) }}
                    @else
                        {{ Form::text('title',$block->title,['class' => 'form-control','required'=>'required']) }}
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                @if($block->id != 1)
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
    @if($block->id != 1)
    <div class="text-right">
        <form action="{{ route('setups.delete_block',$block->id) }}" method="post">
            @csrf
            @method('delete')
            <button class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？若有區塊放置在此欄位，記得去變更！')">刪除</button>
        </form>
    </div>
    @endif
@endsection
