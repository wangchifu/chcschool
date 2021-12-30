@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                教室預約
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('classroom_orders.index') }}">教室預約</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('classroom_orders.admin') }}">教室管理</a>
                </li>
                <li class="nav-item">
            </ul>
            {{ Form::open(['route' => 'classroom_orders.admin_store', 'method' => 'POST']) }}
            <?php
            $name=null;
            $disable=null;
            $sections = config("chcschool.class_sections");
            for($i=0;$i<9;$i++){
                foreach($sections as $k=>$v){
                    $close[$i][$k] = null;
                    if($k=="45" or $k=="0" or $i=="0" or $i=="6"){
                        $close[$i][$k] = 1;
                    }
                }
            }
            ?>
            @include('classroom_orders.admin_form')
            {{ Form::close() }}
        </div>
    </div>
@endsection
