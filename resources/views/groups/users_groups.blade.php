@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '使用者-群組列表 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1><i class="fas fa-users"></i> 使用者-[ {{ $group->name }} ]列表管理</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">群組管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $group->name }}列表管理</li>
                </ol>
            </nav>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>序號</th>
                        <th>帳號</th>
                        <th>姓名</th>
                        <th>職稱</th>
                        <th>在職狀況</th>
                        <th>動作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i =1; ?>
                    @foreach($user_data as $k=>$v)
                        <tr>
                            <td>
                                {{ $i }}
                            </td>
                            <td>
                                {{ $v['username'] }}
                            </td>
                            <td>
                                {{ $v['name'] }}
                            </td>
                            <td>
                                {{ $v['title'] }}
                            </td>
                            <td>
                                @if($v['disable'])
                                    <strong class="text-danger">已離職</strong>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="if(confirm('確定離開嗎?')) document.getElementById('delete{{ $v['id'] }}').submit();else return false"><i class="fas fa-walking"></i> 離開群組</button>
                            </td>
                            {{ Form::open(['route' => 'users_groups.destroy', 'method' => 'DELETE','id'=>'delete'.$v['id']]) }}
                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                            <input type="hidden" name="user_id" value="{{ $v['id'] }}">
                            {{ Form::close() }}
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                    </tbody>
                </table>
                </div>

                <div class="col-md-3">
                    {{ Form::open(['route' => 'users_groups.store', 'method' => 'POST','id'=>'add']) }}
                    {{ Form::select('user_id[]', $user_menu,null, ['id' => 'user_id', 'class' => 'form-control','multiple'=>'multiple','size'=>'20', 'placeholder' => '---可多選---']) }}
                    <br>
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定加入？')"><i class="fas fa-plus"></i> 加入使用者</button>
                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
