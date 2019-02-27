@extends('layouts.master_clean')

@section('title', '編輯帳號')

@section('content')
    {{ Form::open(['route' => ['users.update',$user->id], 'method' => 'patch']) }}
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th nowrap>
                id 序號
            </th>
            <th nowrap>
                帳號
            </th>
            <th nowrap>
                排序
            </th>
            <th nowrap>
                姓名
            </th>
            <th nowrap>
                職稱
            </th>
            <th nowrap>
                群組
            </th>
            <th nowrap>
                動作
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{ $user->id }}
            </td>
            <td>
                {{ $user->username }}
            </td>
            <td>
                {{ Form::text('order_by',$user->order_by,['class' => 'form-control','maxlength'=>'3']) }}
            </td>
            <td>
                {{ $user->name }}
            </td>
            <td>
                {{ $user->title }}
            </td>
            <td>
                {{ Form::select('group_id', $groups,$user->group_id, ['class' => 'form-control','placeholder'=>'']) }}
            </td>
            <td>
                <?php
                    $check1 = ($user->disable)?"":"checked";
                    $check2 = ($user->disable)?"checked":"";
                ?>
                <input type="radio" name="disable" value="" id="enable" {{ $check1 }}> <label for="enable">在職</label>　<input type="radio" name="disable" value="1" id="disable" {{ $check2 }}> <label for="disable" class="text-danger">離職</label>
            </td>
        </tr>
        </tbody>
    </table>
    <button class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">儲存變更</button>
    {{ Form::close() }}
@endsection
