@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '檢視 | 內容管理')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card my-4">
                <h3 class="card-header">
                    {{ $content->title }}
                </h3>
                <div class="card-body">
                    {!! $content->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection
