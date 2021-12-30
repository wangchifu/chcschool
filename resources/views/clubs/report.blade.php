@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>社團報名</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.index') }}">學期設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.setup') }}">社團設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('clubs.report') }}">報表輸出</a>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <?php
                        $admin = check_power('社團報名','A',auth()->user()->id);
                    ?>
                    @if($admin)
                        <a href="{{ route('clubs.report_situation') }}" class="btn btn-info btn-sm">報名狀況</a>
                        <a href="{{ route('clubs.report_money') }}" class="btn btn-info btn-sm">收費報表</a>
                    @endif
                        <a href="{{ route('clubs.semester_select') }}" class="btn btn-info btn-sm" target="_blank">學生入口 <i class="fas fa-forward"></i> </i></a>
                </div>
            </div>

        </div>
    </div>
@endsection
