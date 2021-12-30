@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                教室預約
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('classroom_orders.index') }}">教室預約</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('classroom_orders.admin') }}">教室管理</a>
                </li>
                <li class="nav-item">
            </ul>

            <a href="{{ route('classroom_orders.admin_create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增教室</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th nowrap>序號</th>
                    <th nowrap>名稱</th>
                    <th nowrap>狀態</th>
                    <th nowrap>管理動作</th>
                </tr>
                </thead>
                <tbody>
                <?php $i =1; ?>
                @foreach($classrooms as $classroom)
                    <tr>
                        <td>
                            {{ $i }}
                        </td>
                        <td>
                            {{ $classroom->name }}
                        </td>
                        <td>
                            @if($classroom->disable)
                                <p class="text-danger">停用</p>
                            @else
                                <p class="text-success">啟用</p>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('classroom_orders.admin_edit',$classroom->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            <a href="{{ route('classroom_orders.admin_destroy',$classroom->id) }}" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) return true;else return false;"><i class="fas fa-trash"></i> 刪除</a>
                        </td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
