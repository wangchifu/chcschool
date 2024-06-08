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
