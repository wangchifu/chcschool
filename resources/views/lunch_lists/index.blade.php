@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <?php

    $active['teacher'] ="";
    $active['student'] ="";
    $active['list'] ="active";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-報表輸出</h1>
            @include('lunches.nav')
            <br>
            @if($admin)
                <a href="{{ route('lunch_lists.every_day') }}" class="btn btn-info btn-sm">分期記錄</a>
                <a href="{{ route('lunch_lists.all_semester') }}" class="btn btn-info btn-sm">全學期記錄</a>
                <a href="{{ route('lunch_lists.factory') }}" class="btn btn-info btn-sm" target="_blank">廠商入口</a>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
