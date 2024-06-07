@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', $content->title.' |')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                {{ $content->title }}
            </h1>
            <div class="card my-4">
                <h3 class="card-header">
                    @can('create',\App\Post::class)
                        <a href="{{ route('contents.exec_edit',$content->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 行政人員編輯</a>
                    @endcan
                </h3>
                <div class="card-body">
                    <div class="table-responsive">
                    {!! $content->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
