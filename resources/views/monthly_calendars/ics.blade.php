@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務月曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校務月曆
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">從google ics 匯入</li>
                </ol>
            </nav>
            {{ Form::open(['route' => 'monthly_calendars.file_store','method' => 'POST','id'=>'this_form']) }}
            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">
                <i class="fas fa-save"></i> 把勾選的匯入
            </button>
            <hr>
            @foreach($item as $v)
                @foreach($v as $k=>$v2)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck{{ $k }}" checked name="items[{{ $v2['DTSTART'] }}]" value="{{ $v2['SUMMARY'] }}">
                    <label class="form-check-label" for="exampleCheck{{ $k }}">{{ $v2['DTSTART'] }} - {{ $v2['SUMMARY'] }}</label>
                </div>
                @endforeach
            @endforeach
            <hr>
            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">
                <i class="fas fa-save"></i> 把勾選的匯入
            </button>
            {{ Form::close() }}
        </div>
    </div>
@endsection
