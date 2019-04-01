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
            <h1>午餐系統-報表輸出：各項列表</h1>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_lists.index') }}">報表輸出</a></li>
                    <li class="breadcrumb-item active" aria-current="page">分項列表</li>
                </ol>
            </nav>
            @if($admin)
                <form name="myform" action="{{ route('lunch_lists.show_more_list') }}" method="post">
                    @csrf
                <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>
                            餐期
                        </th>
                        <th>
                            廠商
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            {{ Form::select('lunch_order_id', $lunch_order_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </td>
                        <td>
                            {{ Form::select('factory_id', $factory_array,null, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required']) }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <button class="btn btn-info btn-sm" onclick="return confirm('確定？')">送出</button>
                </div>
                </form>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
