@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '公告系統 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>公告系統：榮譽榜</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.index') }}">一般公告</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('posts.honor') }}"><img src="{{ asset('images/gold-medal.svg') }}" width="16">榮譽榜</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.insite') }}">內部公告</a>
                </li>
            </ul>
            @include('posts.list')
            {{ $posts->links() }}
        </div>
    </div>
@endsection
