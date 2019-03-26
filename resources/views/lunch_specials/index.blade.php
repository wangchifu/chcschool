@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-特殊處理')

@section('content')
    <?php

    $active['teacher'] ="";
    $active['list'] ="";
    $active['special'] ="active";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-特殊處理</h1>
            @include('lunches.nav')
            <br>
            @if($admin)
                <a href="{{ route('lunch_specials.late_teacher') }}" class="btn btn-info">逾期教師補訂餐</a>
                <a href="{{ route('lunch_specials.one_day') }}" class="btn btn-info">單日供餐變更</a>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
