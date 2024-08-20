@extends('layouts.master')

@section('title', '校園跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園跑馬燈
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.setup') }}">管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.index') }}">列表</a>
                </li>
            </ul>
            <br>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        跑馬燈管理
                    </h3>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
@endsection
