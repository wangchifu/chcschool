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
            <h1>午餐系統-報表輸出：查詢結果</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.index') }}">報表輸出</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.more_list') }}">各項列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">查詢結果</li>
                </ol>
            </nav>
            @if($admin)


            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
