@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '公告系統 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>公告系統</h1>
            @include('posts.list')
            {{ $posts->links() }}
        </div>
    </div>
@endsection
