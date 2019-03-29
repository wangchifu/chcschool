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

                <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>
                            1.請先選擇學期
                        </th>
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
                        <a href="{{ route('lunch_lists.factory') }}" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-link"></i> 給廠商頁面連結</a>
                        <form name="myform" action="{{ route('lunch_lists.show_more_list') }}" method="post">
                            @csrf
                            <td>
                                {{ Form::select('lunch_setup_id', $lunch_setup_array,$lunch_setup_id, ['class' => 'form-control','placeholder'=>'--請選擇--','required'=>'required','onchange'=>'jump()']) }}
                            </td>
                        </form>
                        <form>
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
                    <button class="btn btn-info btn-sm">送出</button>
                </div>
                </form>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
    <script language='JavaScript'>

        function jump(){
            if(document.myform.lunch_setup_id.options[document.myform.lunch_setup_id.selectedIndex].value!=''){
                location="/lunch_lists/more_list/" + document.myform.lunch_setup_id.options[document.myform.lunch_setup_id.selectedIndex].value;
            }
        }
    </script>
@endsection
