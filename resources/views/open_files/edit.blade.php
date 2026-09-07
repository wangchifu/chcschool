@extends('layouts.master_clean')
<?php $openfile_name = (empty($setup->openfile_name))?"檔案庫":$setup->openfile_name; ?>
@section('title', '編輯'.$openfile_name.' | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：輸入框與按鈕 Focus 高對比視覺提示 */
    .form-control:focus-visible,
    .btn:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
    }
</style>

<div class="container py-3">
    <h1 class="h4 mb-3">編輯{{ $openfile_name }}項目</h1>
    
    @include('layouts.errors')

    {{ Form::open(['route' => ['open_files.update',$upload->id], 'method' => 'patch', 'aria-label' => '編輯'.$openfile_name.'項目表單']) }}

    <!-- 無障礙 HM1010301C 修正：移除排版用 table，改用標準語意化 Form 結構 -->
    <div class="card p-3 shadow-sm">
        <div class="form-group">
            <!-- 無障礙 HM1150100C 修正：補上對應的 Label -->
            <label for="name" class="font-weight-bold">項目名稱 <span class="text-danger">*</span></label>
            {{ Form::text('name', $upload->name, [
                'id' => 'name',
                'class' => 'form-control mb-2',
                'required' => 'required', 
                'placeholder' => '請輸入名稱',
                'aria-label' => '項目名稱'
            ]) }}

            @if($upload->type == 3)
                <label for="url" class="font-weight-bold mt-2">雲端連結網址 <span class="text-danger">*</span></label>
                {{ Form::text('url', $upload->url, [
                    'id' => 'url',
                    'class' => 'form-control',
                    'required' => 'required', 
                    'placeholder' => '請輸入連結 (例如: https://...)',
                    'aria-label' => '雲端連結網址'
                ]) }}
            @endif
        </div>

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn btn-primary" onclick="return confirm('確定儲存？')">
                <i class="fas fa-save" aria-hidden="true"></i> 儲存變更
            </button>
        </div>
    </div>

    <input type="hidden" name="path" value="{{ $path }}">
    {{ Form::close() }}
</div>
@endsection