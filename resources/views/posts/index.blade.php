@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '全部公告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>公告系統</h1>
            @can('create',\App\Post::class)
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('posts.index') }}">架上公告</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('posts.index_my') }}">我的公告</a>
                </li>
              </ul>
            @endcan
            @include('posts.list')
            {{ $posts->links() }}
        </div>
    </div>
@endsection
