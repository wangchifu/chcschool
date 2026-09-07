@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：按鈕與連結 Focus 高對比視覺提示 */
    .btn:focus-visible,
    a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <main class="col-md-8 col-lg-6" aria-label="社團報名與一覽選單">
        <h1 class="h2 mb-4">社團報名</h1>
        
        <div class="card">
            <div class="card-body">
            @foreach($club_semesters as $club_semester)
                <?php
                    $check_club1 = \App\Club::where('semester',$club_semester->semester)->where('class_id','1')->get();
                    $check_club2 = \App\Club::where('semester',$club_semester->semester)->where('class_id','2')->get();
                    $check_students[$club_semester->semester] = \App\ClubStudent::where('semester', $club_semester->semester)->count();                        
                ?>

                <!-- 學生特色社團 -->
                <section class="mb-4" aria-label="{{ $club_semester->semester }} 學期學生特色社團">
                    @if(date('YmdHi') >= str_replace('-','',$club_semester->start_date) and date('YmdHi') <= str_replace('-','',$club_semester->stop_date))
                        <div class="mb-2">
                            <a href="{{ route('clubs.parents_login',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-primary mb-1">
                                {{ $club_semester->semester }} 學期「學生特色社團」報名按這裡
                            </a>
                            
                            <!-- 無障礙 HM1200101C 修正：另開新視窗提示 -->
                            <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-info mb-1" target="_blank" rel="noopener noreferrer" aria-label="{{ $club_semester->semester }} 學期特色社團一覽 (另開新視窗)">
                                <i class="fas fa-hand-point-up" aria-hidden="true"></i> 特色社團一覽
                                <i class="fas fa-external-link-alt small ml-1" aria-hidden="true"></i>
                                <span class="sr-only">(另開新視窗)</span>
                            </a>
                        </div>
                        <small class="text-muted d-block">報名時間：({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</small>
                    @else
                        @if(count($check_club1) > 0)
                            <div class="mb-2">
                                <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'1']) }}" class="btn btn-info mb-1" target="_blank" rel="noopener noreferrer" aria-label="{{ $club_semester->semester }} 學期特色社團一覽 (另開新視窗)">
                                    <i class="fas fa-hand-point-up" aria-hidden="true"></i> 特色社團一覽
                                    <i class="fas fa-external-link-alt small ml-1" aria-hidden="true"></i>
                                    <span class="sr-only">(另開新視窗)</span>
                                </a>
                            </div>
                            <small class="text-muted d-block">報名時間：({{ $club_semester->start_date }} ~ {{ $club_semester->stop_date }})</small>                            
                        @endif                                                
                    @endif
                </section>

                <hr>

                <!-- 學生課後活動 -->
                <section class="mb-4" aria-label="{{ $club_semester->semester }} 學期學生課後活動">
                    @if(date('YmdHi') >= str_replace('-','',$club_semester->start_date2) and date('YmdHi') <= str_replace('-','',$club_semester->stop_date2))
                        <div class="mb-2">
                            <a href="{{ route('clubs.parents_login',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-primary mb-1">
                                {{ $club_semester->semester }} 學期「學生課後活動」報名按這裡
                            </a>
                            
                            <!-- 無障礙 HM1200101C 修正：另開新視窗提示 -->
                            <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-info mb-1" target="_blank" rel="noopener noreferrer" aria-label="{{ $club_semester->semester }} 學期課後社團一覽 (另開新視窗)">
                                <i class="fas fa-hand-point-up" aria-hidden="true"></i> 課後社團一覽
                                <i class="fas fa-external-link-alt small ml-1" aria-hidden="true"></i>
                                <span class="sr-only">(另開新視窗)</span>
                            </a>
                        </div>
                        <small class="text-muted d-block">報名時間：({{ $club_semester->start_date2 }} ~ {{ $club_semester->stop_date2 }})</small>
                    @else
                        @if(count($check_club2) > 0)
                            <div class="mb-2">
                                <a href="{{ route('clubs.show_clubs',['semester'=>$club_semester->semester,'class_id'=>'2']) }}" class="btn btn-info mb-1" target="_blank" rel="noopener noreferrer" aria-label="{{ $club_semester->semester }} 學期課後社團一覽 (另開新視窗)">
                                    <i class="fas fa-hand-point-up" aria-hidden="true"></i> 課後社團一覽
                                    <i class="fas fa-external-link-alt small ml-1" aria-hidden="true"></i>
                                    <span class="sr-only">(另開新視窗)</span>
                                </a>
                            </div>
                            <small class="text-muted d-block">報名時間：({{ $club_semester->start_date2 }} ~ {{ $club_semester->stop_date2 }})</small>                            
                        @endif                                                
                    @endif                    
                </section>

                <!-- 測試帳密按鈕 -->
                @if($check_students[$club_semester->semester] > 0)
                    <hr>
                    <div class="mb-2">
                        <a href="{{ route('clubs.parents_login_test',['semester'=>$club_semester->semester]) }}" class="btn btn-warning">
                            {{ $club_semester->semester }} 學期 測試帳密(非報名)
                        </a>
                    </div>
                @endif
            @endforeach
            </div>
        </div>
    </main>
</div>
@endsection