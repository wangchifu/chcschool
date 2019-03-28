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
            <h1>午餐系統-報表輸出：匯出全學期收據</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.index') }}">報表輸出</a></li>
                    <li class="breadcrumb-item active" aria-current="page">匯出全學期收據</li>
                </ol>
            </nav>
            @if($admin)
                <form action="{{ route('lunch_lists.semester_print') }}" method="post"  target="_blank">
                    @csrf
                    <div class="form-group">
                        {{ Form::select('lunch_setup_id', $lunch_setup_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--']) }}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-info btn-sm"><i class="fas fa-print"></i> 印出</button>
                    </div>
                </form>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
