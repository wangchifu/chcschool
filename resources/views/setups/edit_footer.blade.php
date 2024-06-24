@extends('layouts.master_clean')

@section('title', '編輯置底 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => ['setups.update_footer'], 'method' => 'patch']) }}
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
    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
            <i class="fas fa-save"></i> 儲存置底
        </button>
    </div>
    {{ Form::close() }}
@endsection
