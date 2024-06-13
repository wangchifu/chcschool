@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '學校介紹管理 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                學校介紹管理
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">簡介列表</li>
                </ol>
            </nav>
            <a href="{{ route('departments.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增介紹</a>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>id</th>
                    <th width="100">排序</th>
                    <th>標題</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{ $department->order_by }}</td>
                        <td>{{ $department->id }}</td>
                        <td><a href="{{ route('departments.show',$department->id) }}" target="_blank">{{ $department->title }}</a></td>                        
                        <td>
                            <a href="{{ route('departments.edit',$department->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $department->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                        </td>
                    </tr>
                    {{ Form::open(['route' => ['departments.destroy',$department->id], 'method' => 'DELETE','id'=>'delete'.$department->id]) }}
                    {{ Form::close() }}
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
