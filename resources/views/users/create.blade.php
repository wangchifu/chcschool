@extends('layouts.master_clean')

@section('title', '新增本機帳號 | ')

@section('content')
    <br>
    <h2>新增本機帳號</h2>
    {{ Form::open(['route' =>'users.store', 'method' => 'store']) }}
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th>
                帳號
            </th>
            <th>
                密碼
            </th>
            <th>
                職稱
            </th>
            <th>
                姓名
            </th>
            <th>
                排序
            </th>
            <th>
                群組
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{ Form::text('username',null,['id'=>'username','class' => 'form-control','required'=>'required','placeholder' => '帳號']) }}
            </td>
            <td>
                demo1234
            </td>
            <td>
                {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '職稱']) }}
            </td>
            <td>
                {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required','placeholder' => '姓名']) }}
            </td>
            <td>
                {{ Form::text('order_by',null,['class' => 'form-control','maxlength'=>'3']) }}
            </td>
            <td>
                {{ Form::select('group_id[]', $groups,null, ['id' => 'group_id', 'class' => 'form-control','multiple'=>'multiple', 'placeholder' => '---可多選群組---']) }}
            </td>
        </tr>
        </tbody>
    </table>
    @include('layouts.errors')
    <button class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')"><i class="fas fa-save"></i> 儲存變更</button>
    {{ Form::close() }}
@endsection
