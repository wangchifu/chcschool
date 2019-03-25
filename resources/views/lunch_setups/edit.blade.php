@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-修改設定')

@section('seal1')
    <?php
    $school_code = school_code();
    $seal1 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal1.png');
    $path = 'lunches&'.$lunch_setup->id.'&seal1.png';
    ?>
    @if(file_exists($seal1))
        <img src="{{ route('getImg',$path) }}" width="180">
    @endif
@endsection

@section('seal2')
    <?php
    $school_code = school_code();
    $seal2 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal2.png');
    $path = 'lunches&'.$lunch_setup->id.'&seal2.png';
    ?>
    @if(file_exists($seal2))
        <img src="{{ route('getImg',$path) }}" width="180">
    @endif
@endsection

@section('seal3')
    <?php
    $school_code = school_code();
    $seal3 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal3.png');
    $path = 'lunches&'.$lunch_setup->id.'&seal3.png';
    ?>
    @if(file_exists($seal3))
        <img src="{{ route('getImg',$path) }}" width="180">
    @endif
@endsection

@section('seal4')
    <?php
    $school_code = school_code();
    $seal4 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal4.png');
    $path = 'lunches&'.$lunch_setup->id.'&seal4.png';
    ?>
    @if(file_exists($seal4))
        <img src="{{ route('getImg',$path) }}" width="180">
    @endif
@endsection

@section('content')
    <?php

    $active['teacher'] ="";
    $active['setup'] ="active";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-修改設定</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">午餐系統</li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_setups.index') }}">午餐設定</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改學期設定</li>
                </ol>
            </nav>
            @include('layouts.errors')
            {{ Form::model($lunch_setup,['route' => ['lunch_setups.update',$lunch_setup->id], 'method' => 'patch','id'=>'setup','files'=>true]) }}
            @include('lunch_setups.form')
            {{ Form::close() }}
        </div>
    </div>
@endsection
