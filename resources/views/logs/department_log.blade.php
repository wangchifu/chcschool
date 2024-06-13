@extends('layouts.master')

@section('title', "department_id:".$id.' | ')

@section('content')
    @foreach($logs as $log)
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h1>{{ $log->title }}</h1>
                <div class="card my-4">
                    <h3 class="card-header text-danger">
                        {{ $log->created_at }} 由 {{ $log->user->name }} 送出
                        <a href="{{  route('departments.delete_log',$log->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定？')">刪除此 log</a>                    
                    </h3>                    
                    <div class="card-body">
                        <div class="table-responsive">
                        {!! $log->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    @endforeach
@endsection
