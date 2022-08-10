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
            <h1>午餐系統-報表輸出：全學期記錄</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.index') }}">報表輸出</a></li>
                    <li class="breadcrumb-item active" aria-current="page">全學期記錄</li>
                </ol>
            </nav>
            @if($admin)
                <form action="{{ route('lunch_lists.semester_print') }}" method="post"  target="_blank">
                    @csrf
                    <div class="form-group">
                        {{ Form::select('lunch_setup_id', $lunch_setup_array,null, ['class' => 'form-control','required'=>'required','placeholder'=>'--請選擇--']) }}
                    </div>
                    <div class="form-group">
                        <input name="submit" type="submit" class="btn btn-info btn-sm" value="印出教師全學期收費通知">
                        <input name="submit" type="submit" class="btn btn-info btn-sm" value="印出教師全學期收據">
                        <input name="submit" type="submit" class="btn btn-info btn-sm" value="印出廠商全學期收入">
                        <input name="submit" type="submit" class="btn btn-success btn-sm" value="下載全學期「彰化智慧校園」收費模組匯入單">
                    </div>
                </form>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
