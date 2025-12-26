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
                    <?php
                        $check_club1 = \App\Club::where('semester',$club_semester->semester)->where('class_id','1')->get();
                        $check_club2 = \App\Club::where('semester',$club_semester->semester)->where('class_id','2')->get();
                        $check_students[$club_semester->semester] = \App\ClubStudent::where('semester', $club_semester->semester)->count();                        
                    ?>
                    @if(date('YmdHi') >= str_replace('-','',$club_semester->start_date) and date('YmdHi') <= str_replace('-','',$club_semester->stop_date))
                        <a href="{{ route('clubs.parents_login',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-primary">{{ $club_semester->semester }} 學期「學生特色社團」報名按這裡</a>
                        <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-info" target="_blank"> <i class="fas fa-hand-point-up"></i> 特色社團一覽</a>
                        <br>
                        <small>報名時間：({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</small>
                    @else
                        @if(count($check_club1) >0)
                        <!--
                        <span class="btn btn-secondary disabled">{{ $club_semester->semester }} 學期「學生特色社團」報名</span>
                        -->
                        <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-info" target="_blank"><i class="fas fa-hand-point-up"></i> 特色社團一覽</a>
                        <br>
                        <small>報名時間：({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</small>                            
                        @endif                                                
                    @endif
                    @if(date('YmdHi') >= str_replace('-','',$club_semester->start_date2) and date('YmdHi') <= str_replace('-','',$club_semester->stop_date2))
                        <hr>
                        <a href="{{ route('clubs.parents_login',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-primary">{{ $club_semester->semester }} 學期「學生課後活動」報名按這裡</a>
                        <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-info" target="_blank"><i class="fas fa-hand-point-up"></i> 課後活動社團一覽</a>
                        <br>
                        <small>報名時間：({{ $club_semester->start_date2 }} ~ {{ $club_semester->stop_date2 }})</small>
                    @else
                        @if(count($check_club2) >0)
                        <hr>
                        <!--
                            <span class="btn btn-secondary disabled">{{ $club_semester->semester }} 學期「學生課後活動」報名</span>
                        -->
                        <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-info" target="_blank"><i class="fas fa-hand-point-up"></i> 課後活動社團一覽</a>
                        <br>
                        <small>報名時間：({{ $club_semester->start_date2 }} ~ {{ $club_semester->stop_date2 }})</small>                            
                        @endif                                                
                    @endif                    
                    @if($check_students[$club_semester->semester] > 0)
                        <hr>
                        <a href="{{ route('clubs.parents_login_test',['semester'=>$club_semester->semester]) }}" class="btn btn-warning">{{ $club_semester->semester }} 學期 測試帳密(非報名)</a>
                    @endif
                @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
