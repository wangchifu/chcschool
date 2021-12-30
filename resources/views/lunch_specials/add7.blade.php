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
            @if(!$semester)
                <form method="get">
                <div class="form-group">
                    <label for="semester"><strong>要增加7月訂餐的學期*</strong><small class="text-primary">(如 1082)</small></label>
                    {{ Form::text('semester',null,['id'=>'semester','class' => 'form-control', 'maxlength'=>'4','placeholder'=>'4碼數字','required'=>'required']) }}
                    <input type="submit" value="送出">
                </form>
                </div>
            @else
                @if($has7)
                    <span class="text-danger">本學期已有7月餐期</span>
                @else
                {{ Form::open(['route' => 'lunch_specials.store7', 'method' => 'POST','id'=>'store']) }}
                @foreach($semester_dates as $k=>$v)
                    <?php
                    $this_date_w = get_chinese_weekday($v);
                    ?>
                    @if($this_date_w=="星期六")
                        <?php
                        $checked = "";
                        ?>
                        {{ $v }}-<label class="text-success" for=id"{{ $v }}">{{ $this_date_w }}</label>
                    @elseif($this_date_w=="星期日")
                        <?php
                        $checked = "";
                        ?>
                        {{ $v }}-<label class="text-danger" for=id"{{ $v }}">{{ $this_date_w }}</label>

                    @else
                        <?php
                        $checked = "checked";
                        ?>
                        {{ $v }}-<label for=id"{{ $v }}">{{ $this_date_w }}</label>
                    @endif
                    <input type="checkbox" name="order_date[{{ $v }}]" {{ $checked }} id=id"{{ $v }}">
                    <input type="text" placeholder="備註" name="ps[{{ $v }}]"><br>
                @endforeach
                <input type="hidden" name="name" value="{{ $this_year }}-07">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <input type="submit" value="送出" onclick="return confirm('確定？')">
                {{ Form::close() }}
                @endif
            @endif
        </div>
    </div>
@endsection
