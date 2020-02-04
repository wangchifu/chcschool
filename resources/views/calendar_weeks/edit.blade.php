@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-週次修改 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>校務行事曆-週次設定</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('calendars.index') }}">校務行事曆</a></li>
                    <li class="breadcrumb-item active" aria-current="page">週次修改</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header">
                    <h4>{{ $semester }}學期 週次修改</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'calendar_weeks.update','id'=>'save' ,'method' => 'POST']) }}
                    @foreach($calendar_weeks as $calendar_week)
                        第{{ $calendar_week->week }}週 <input type="text" name="week_id[{{ $calendar_week->id }}]" value="{{ $calendar_week->start_end }}" maxlength="11" required><br>
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">
                        <i class="fas fa-save"></i> 儲存設定
                    </button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
