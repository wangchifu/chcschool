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
                    @if(date('YmdHi') >= str_replace('-','',$club_semester->start_date) and date('YmdHi') <= str_replace('-','',$club_semester->stop_date))
                        <a href="{{ route('clubs.parents_login',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-primary">{{ $club_semester->semester }} 學期「學生特色社團」報名 ({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</a><br><br>
                    @else
                        <span class="btn btn-secondary disabled">{{ $club_semester->semester }} 學期「學生特色社團」報名 ({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</span><br><br>
                    @endif
                    @if(date('YmdHi') >= str_replace('-','',$club_semester->start_date2) and date('YmdHi') <= str_replace('-','',$club_semester->stop_date2))
                        <a href="{{ route('clubs.parents_login',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-primary">{{ $club_semester->semester }} 學期「學生課後活動」報名 ({{ $club_semester->start_date2 }} ~ {{ $club_semester->stop_date2 }})</a><br><br>
                    @else
                        <span class="btn btn-secondary disabled">{{ $club_semester->semester }} 學期「學生課後活動」報名 ({{ $club_semester->start_date2 }} ~ {{ $club_semester->stop_date2 }})</span><br><br>
                    @endif
                @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
