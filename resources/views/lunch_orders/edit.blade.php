@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-修改日期')

@section('content')
    <?php
    $active['teacher'] ="";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="active";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>供餐日修改</h1>
            <h3 class="text-danger">若有訂餐資料了，請勿再更動，請由特殊處理！！</h3>
            @include('lunches.nav')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lunches.index') }}">午餐系統</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lunch_setups.index') }}">午餐設定</a></li>
                    <li class="breadcrumb-item active" aria-current="page">供餐日修改</li>
                </ol>
            </nav>
            @if($admin)
                <div class="row">
                    <div class="col-md-11">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ $semester }}學期各月份選取供餐日</h3>
                            </div>
                            <div class="card-body">
                                {{ Form::open(['route' => 'lunch_orders.update', 'method' => 'patch','id'=>'store']) }}
                                @foreach($semester_dates as $k=>$v)
                                    <h3>{{ $k }}</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-danger">日</th>
                                            <th>一</th>
                                            <th>二</th>
                                            <th>三</th>
                                            <th>四</th>
                                            <th>五</th>
                                            <th class="text-success">六</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $first_w = get_date_w($v[1]);
                                        ?>
                                        <tr>
                                            @foreach($v as $k2 => $v2)
                                                <?php
                                                $checked = ($order_date_data[$v2])?"checked":"";
                                                $this_date_w = get_date_w($v2);
                                                if($this_date_w==0){
                                                    $text_color = "btn btn-danger btn-sm";

                                                }elseif($this_date_w==6){
                                                    $text_color = "btn btn-success btn-sm";

                                                }else{
                                                    $text_color = "btn btn-outline-dark btn-sm";

                                                }
                                                ?>
                                                @if($k2 == 1)
                                                    @for($i=1;$i<=$first_w;$i++)
                                                        <td></td>
                                                    @endfor
                                                @endif
                                                <td><input type="checkbox" name="order_date[{{ $v2 }}]" id="d{{ $v2 }}" {{ $checked }}>
                                                    <label for="d{{ $v2 }}" class="{{ $text_color }}">{{ substr($v2,5,5) }}</label>
                                                    <input type="text" class="form-control" placeholder="備註" name="ps[{{ $v2 }}]" value="{{ $ps[$v2] }}">
                                                </td>
                                                @if($this_date_w == 6)
                                        </tr>
                                        @endif
                                        <?php  ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <hr>
                                @endforeach
                                <input type="hidden" name="semester" value="{{ $semester }}">
                                <button class="btn btn-success" onclick="return confirm('確定修改嗎？')">確定修改</button>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <h1 class="text-danger">你不是管理者</h1>
            @endif
        </div>
    </div>
@endsection
