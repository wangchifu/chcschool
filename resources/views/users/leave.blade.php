@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '帳號管理')

@section('content')
    <h1>
        帳號管理
    </h1>
    <?php
    $active[1] = "";
    $active[2] = "active";
    ?>
    @include('users.nav',$active)
    @include('users.form')
@endsection
