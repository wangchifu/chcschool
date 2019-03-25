@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-設定')

@section('content')
    <?php

    $active['teacher'] ="";
    $active['setup'] ="active";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-新增設定</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">午餐系統</li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_setups.index') }}">午餐設定</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增學期設定</li>
                </ol>
            </nav>
            @include('layouts.errors')
            {{ Form::open(['route' => 'lunch_setups.store', 'method' => 'POST','id'=>'setup','files'=>true]) }}
            @include('lunch_setups.form')
            {{ Form::close() }}
        </div>
    </div>
@endsection
