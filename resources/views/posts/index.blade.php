@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '公告系統')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>公告系統</h1>
            @can('create',\App\Post::class)
                <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
            @endauth
            @include('posts.list')
            {{ $posts->links() }}
        </div>
    </div>
@endsection
