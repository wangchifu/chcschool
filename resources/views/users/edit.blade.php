@extends('layouts.master_clean')

@section('title', '編輯帳號 | ')

@section('content')
    <br>
    {{ Form::open(['route' => ['users.update',$user->id], 'method' => 'patch']) }}
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th colspan="5">
                帳號：{{ $user->username }}
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{ Form::text('title',$user->title,['class' => 'form-control','required'=>'required','placeholder'=>'職稱']) }}
            </td>
            <td>
                {{ Form::text('name',$user->name,['class' => 'form-control','required'=>'required','placeholder'=>'姓名']) }}
            </td>
            <td width="100">
                排序：
                {{ Form::text('order_by',$user->order_by,['class' => 'form-control','maxlength'=>'3']) }}
            </td>
            <td width="200">
                群組：
                {{ Form::select('group_id[]', $groups,$default_group, ['id' => 'group_id', 'class' => 'form-control','multiple'=>'multiple', 'placeholder' => '---可多選群組---']) }}
            </td>
            <td>
                <?php
                    $check1 = ($user->disable)?"":"checked";
                    $check2 = ($user->disable)?"checked":"";
                    $admin = ($user->admin)?"checked":"";
                    $disabled = ($user->username=="admin")?"disabled":null;
                ?>
                @if(auth()->user()->id != $user->id)
                    <input type="radio" name="disable" value="" id="enable" {{ $check1 }}> <label for="enable">在職</label>　<input type="radio" name="disable" value="1" id="disable" {{ $check2 }}> <label for="disable" class="text-danger">離職</label>
                @endif
                <br>
                <input type="checkbox" name="admin" value="1" id="admin" {{ $admin }} {{ $disabled }}> <label for="admin">網站管理者</label>
            </td>
        </tr>
        </tbody>
    </table>
    <button class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')"><i class="fas fa-save"></i> 儲存變更</button>
    {{ Form::close() }}
    @include('layouts.errors')
@endsection
