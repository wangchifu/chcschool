@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '行政待辦 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            @include('tasks.form')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index') }}">待辦</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index2') }}">完成</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index3') }}">無關</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('tasks.self') }}"><i class="fas fa-plus"></i> 自己</a>
                </li>
            </ul>
            <hr>
            {{ Form::open(['route' => 'tasks.self_store', 'method' => 'POST','id'=>'tasks_self_store','files' => true]) }}
            <table width="100%">
                <tr>
                    <td width="60%">
                        {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '自己的事項']) }}
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
                    <td colspan="2">
                        <small>請簡短扼要；一次新增一事項。</small>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{ Form::close() }}

        </div>
    </div>
@endsection
