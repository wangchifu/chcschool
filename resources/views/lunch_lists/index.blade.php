@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <?php

    $active['teacher'] ="";
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
                <a href="" class="btn btn-info">廠商頁面</a>
                <a href="" class="btn btn-info">匯出全學期收據</a>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
