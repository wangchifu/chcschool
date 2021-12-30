<?php
$schools = config('chcschool.schools');
$school_name = str_replace('彰化縣','',$schools[$school_code]);

$check = \App\UserGroup::where('user_id',$user->id)
    ->where('group_id',1)
    ->first();
?>
<nav class="navbar navbar-light bg-light">
        {{ $schools[$school_code] }} <span><i class="fas fa-user"></i> {{ $user->name }}</span> <span><i class="fas fa-info-circle"></i></span> <a href="{{ route('tasks.logout') }}" onclick="if(confirm('登出嗎?')) return true;else return false"><i class="fas fa-sign-out-alt text-danger"></i></a>
</nav>
@if($check)
    {{ Form::open(['route' => 'tasks.store', 'method' => 'POST','id'=>'tasks_store','files' => true]) }}
    <table width="100%">
        <tr>
            <td width="60%">
                {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '給大家的事項']) }}
            </td>
            <td>
                {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
            </td>
            <td>
                <button type="submit" class="btn btn-success btn-sm" onclick="if(confirm('您確定送出嗎?')) return true;else return false">
                    <i class="fas fa-plus"></i> 新增
                </button>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <small>請簡短扼要；一次新增一事項。</small>
            </td>
        </tr>
    </table>
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    {{ Form::close() }}
@endif
