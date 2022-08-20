@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '今日午餐 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="text-danger">
                今日午餐(已停用)
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">今日午餐</li>
                </ol>
            </nav>
            <h3>一、取得學校代碼</h3>
            取得學校代碼步驟：<br>
            1.連至「<a href="https://fatraceschool.k12ea.gov.tw/" target="_blank">校園食材登錄平臺</a>」<br>
            2.選擇「進階查詢」，找到自己學校。<br>
            3.選好後，按「查詢」。<br>
            4.跳轉至另一頁面，上面網址列中 school = xxxxxxxx 八碼數字(不含 &)即是學校代碼。<br>
            <img src="{{ asset('images/lunch_today1.png') }}" width="30%">
            <img src="{{ asset('images/lunch_today2.png') }}" width="30%">
            <hr>
            <h3>二、填入學校代碼</h3>
            最多可以有四個單位的區塊。
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>
                        單位
                    </th>
                    <th>
                        學校代碼(School ID)
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($lunch_todays as $lunch_today)
                <tr>
                    <td>
                        單位{{ $lunch_today->id }}
                    </td>
                    <td>
                        @if(empty($lunch_today->school_id))
                            {{ Form::open(['route' => 'lunch_todays.update', 'method' => 'POST','id'=>'this_form']) }}
                            {{ Form::text('school_id',null,['id'=>'school_id','class' => 'form-control','required'=>'required','maxlength'=>'8', 'placeholder' => '學校代碼']) }}
                            <input type="hidden" name="id" value="{{ $lunch_today->id }}">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                                <i class="fas fa-save"></i> 填入學校代碼
                            </button>
                            {{ Form::close() }}
                        @else
                            <?php
                                $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$lunch_today->school_id."&KitchenId=all&period=2021-05-05");
                                $school = json_decode($s,true);
                            ?>
                            {{ $lunch_today->school_id }}
                            -{{ $lunch_today->school_name }} <a href="{{ route('lunch_todays.delete',$lunch_today->id) }}" onclick="if(confirm('確定刪除？'));else return false;"><i class="fas fa-times-circle text-danger"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <hr>
            <h3>三、啟用「今日午餐」區塊</h3>
            「網站設定」->「區塊內容」，找到「今日午餐」區塊。
        </div>
    </div>
@endsection
