@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '類別搜尋 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $type_name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">類別搜尋</li>
                </ol>
            </nav>
            @include('posts.list')
            {{ $posts->links() }}
        </div>
    </div>
@endsection
