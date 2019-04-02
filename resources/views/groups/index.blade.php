@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '群組管理 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1><i class="fas fa-users"></i> 群組管理</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">群組管理</li>
                </ol>
            </nav>
            <a href="{{ route('groups.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增群組</a>
            <div class="card my-4">
                <h3 class="card-header">群組列表</h3>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>序號</th>
                            <th>名稱</th>
                            <th>所屬人員</th>
                            <th>停用?</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i =1; ?>
                        @foreach($groups as $group)
                            <tr>
                                <td>
                                    {{ $i }}
                                </td>
                                <td>
                                    {{ $group->name }}
                                </td>
                                <th>
                                    @if(!empty($user_group_data[$group->id]))
                                        {{ count($user_group_data[$group->id]) }}
                                    @else
                                        0
                                    @endif
                                    <a href="{{ route('users_groups',$group->id) }}" class="btn btn-info btn-sm"><i class="fas fa-list"></i> 人員</a>
                                </th>
                                <td>
                                    @if($group->disable)
                                        <strong class="text-danger">已停用</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($group->id > 4)
                                        <a href="{{ route('groups.edit',$group->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('您確定送出嗎?')) document.getElementById('delete{{ $group->id }}').submit();else return false"><i class="fas fa-trash"></i> 刪除</a>
                                    @else
                                        內定群組
                                    @endif
                                </td>
                            </tr>
                            <?php $i++; ?>
                            {{ Form::open(['route' => ['groups.destroy',$group->id], 'method' => 'DELETE','id'=>'delete'.$group->id]) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
