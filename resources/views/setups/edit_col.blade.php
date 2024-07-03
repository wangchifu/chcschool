@extends('layouts.master_clean')

@section('title', '編輯欄位 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => ['setups.update_col',$setup_col->id], 'method' => 'patch','id'=>'this_form']) }}
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th>
                排序
            </th>
            <th>
                名稱
            </th>
            <th>
                所佔比例 ( bootstrap 網頁一行佔 12 )
            </th>
            <th>
                動作
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{ Form::text('order_by',$setup_col->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
            </td>
            <td>
                {{ Form::text('title',$setup_col->title,['class' => 'form-control','required'=>'required']) }}
            </td>
            <td>
                {{ Form::text('num',$setup_col->num,['class' => 'form-control','required'=>'required','maxlength'=>'2']) }}
            </td>
            <td>
                <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> 儲存變更</button>
            </td>
        </tr>
        </tbody>
    </table>
    {{ Form::close() }}
    <form action="{{ route('setups.delete_col',$setup_col->id) }}" method="post">
        @csrf
        @method('delete')
        <button class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？若有區塊放置在此欄位，記得去變更！')"><i class="fas fa-trash"></i>刪除</button>
    </form>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
