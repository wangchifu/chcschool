@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統')

@section('content')
    <?php

    $active['teacher'] ="active";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統</h1>
            @include('lunches.nav')
        </div>
    </div>
@endsection
