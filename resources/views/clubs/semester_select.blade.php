@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1>社團報名</h1>
            <div class="card">
                <div class="card-body">
                @foreach($club_semesters as $club_semester)
                    @if(date('Ymd') >= str_replace('-','',$club_semester->start_date) and date('Ymd') <= str_replace('-','',$club_semester->stop_date))
                        <a href="{{ route('clubs.parents_login',$club_semester->semester) }}" class="btn btn-primary">{{ $club_semester->semester }} 學期學生社團報名系統 ({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</a><br><br>
                    @else
                        <span class="btn btn-secondary disabled">{{ $club_semester->semester }} 學期學生社團報名系統 ({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</span><br><br>
                    @endif
                @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
