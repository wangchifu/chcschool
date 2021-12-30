@extends('layouts.master_clean')

@section('title', '編輯內部文件 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => ['inside_files.update',$inside_file->id], 'method' => 'patch']) }}


    <div class="form-group">
        <table class="table table-striped" style="word-break:break-all;">
            <tr>
                <th>
                    名稱
                </th>
                <th>

                </th>
            </tr>
            <tr>
                <td>
                    {{ Form::text('name',$inside_file->name,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                </td>
                <td>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
                        <i class="fas fa-save"></i> 儲存
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="path" value="{{ $path }}">
    {{ Form::close() }}
@endsection
