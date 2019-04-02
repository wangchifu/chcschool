@extends('layouts.master_clean')

@section('title', '新增使用者權限 | ')

@section('content')
    <br>
    {{ Form::open(['route' =>'user_powers.store', 'method' => 'post']) }}
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th>
                模組
            </th>
            <th>
                使用者
            </th>
            <th>
                權限
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{ $module }}
            </td>
            <td>
                {{ Form::select('user_id',$users,null,['class'=>'form-control']) }}
            </td>
            <td>
                {{ $type }}
            </td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="name" value="{{ $module }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">新增</button>
    {{ Form::close() }}
@endsection
