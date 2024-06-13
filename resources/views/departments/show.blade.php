@extends('layouts.master')

@section('nav_departments_active', 'active')

@section('title', $department->title.' | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $department->title }}</h1>
            <div class="card my-4">
                <h3 class="card-header">
                    @can('create',\App\Post::class)
                        <a href="{{ route('departments.exec_edit',$department->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 編輯</a>
                    @endcan
                    @auth
                        @if(auth()->user()->admin)                        
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $department->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                            {{ Form::open(['route' => ['contents.destroy',$department->id], 'method' => 'DELETE','id'=>'delete'.$department->id]) }}
                            {{ Form::close() }}
                        @endif                        
                        @if(auth()->user()->admin)
                            <a href="{{ route('departments.show_log',$department->id) }}" class="btn btn-info btn-sm" target="_blank">查看 log ({{ $logs_count }})</a>
                        @endif
                    @endauth
                    點閱：{{ $department->views }}
                </h3>
                <div class="card-body">
                    <div class="table-responsive">
                    {!! $department->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
