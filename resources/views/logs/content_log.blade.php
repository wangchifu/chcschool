@extends('layouts.master')

@section('title', "content_id:".$id.' | ')

@section('content')
    @foreach($logs as $log)
    <hr>
        <h1 class="text-danger">{{ $log->created_at }} 由 {{ $log->user->name }} 送出</h1>
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h1>{{ $log->title }}</h1>
                <div class="card my-4">
                    <h3 class="card-header">
                    </h3>
                    <div class="card-body">
                        <div class="table-responsive">
                        {!! $log->content !!}
                        </div>
                    </div>
                    <h3 class="card-footer">
                        權限：
                        @if($log->power==null)
                        公開
                        @elseif($log->power==2)
                        在校內網域或登入者都可看
                        @elseif($log->power==3)
                        只有登入者可看 
                        @endif
                    </h3>
                </div>
            </div>
        </div>
        <hr>
    @endforeach
@endsection
