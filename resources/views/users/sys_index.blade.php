@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '帳號管理 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                系統管理員已登入
            </h1>            
            <a href="{{ route('sys_logout') }}" class="btn btn-danger btn-sm" onclick="return confirm('確定登出？')"><i class="fas fa-sign-out-alt"></i> 登出系統管理員</a>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>排序</th>
                    <th>姓名(帳號)</th>
                    <th>職稱</th>
                    <th>群組</th>
                    <th>類別</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td nowrap>
                            @if($user->disable)
                                <i class="fas fa-times-circle text-danger"></i>
                            @else
                                <i class="fas fa-check-circle text-success"></i>
                            @endif
                            {{ $user->order_by }}
                        </td>
                        <td>
                            @if($user->admin)
                                <i class="fas fa-crown"></i>
                            @endif
                            {{ $user->name }} ({{ $user->username }})
                        </td>
                        <td>
                            {{ $user->title }}
                        </td>
                        <td>
                            @foreach($user->groups as $group)
                                {{ $group->group->name }}
                            @endforeach
                        </td>
                        <td>
                            @if($user->login_type=="local")
                                本機帳號
                            @elseif($user->login_type=="gsuite")
                                gsuite帳號
                            @elseif($user->login_type=="openID")
                                openID帳號
                            @endif
                        </td>
                        <td>                                                        
                            <a href="{{ route('sys_sim',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定模擬？')"><i class="fas fa-user-ninja"></i> 模擬登入</a>                            
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>             
        </div>
    </div>
@endsection
