@extends('layouts.master')

@section('nav_departments_active', 'active')

@section('title', '檢視介紹 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $department->title }}</h1>
            <div class="card my-4">
                <h3 class="card-header" style="background-image:url('{{ asset('images/0084.gif') }}');">
                    　
                </h3>
                <div class="card-body" style="background-image:url('{{ asset('images/0067.gif') }}');">
                    {!! $department->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection
