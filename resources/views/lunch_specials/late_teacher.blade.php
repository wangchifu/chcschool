@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-特殊處理')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
      <!-- Chosen v1.8.2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.min.css" rel="stylesheet" />
  <link href="{{ asset('css/component-chosen.min.css') }}" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js"></script>
    <?php

    $active['teacher'] ="";
    $active['student'] ="";
    $active['list'] ="";
    $active['special'] ="active";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-特殊處理：逾期教師補訂餐</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_specials.index') }}">特殊處理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">逾期教師補訂餐</li>
                </ol>
            </nav>
            @if($admin)
                <div class="card">
                    <div class="card-header">
                        教師忘了訂餐，可是逾期了。想要幫他訂！
                    </div>
                    <div class="card-body">
                        @include('layouts.errors')
                        <form action="{{ route('lunch_specials.late_teacher_show') }}" method="post" id="this_form">
                            @csrf
                        <div class="form-group"> 
                            <label>
                                選擇教職員
                            </label>
                            {{ Form::select('user_id', $user_array,null, ['class' => 'form-control search_selet','placeholder'=>'--請選擇--','required'=>'required']) }}
                            <script>
                                $( ".search_selet" ).chosen({
                                    search_contains: true,
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label>
                                選擇餐期
                            </label>
                            {{ Form::select('lunch_order_id', $lunch_order_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm">送出</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
