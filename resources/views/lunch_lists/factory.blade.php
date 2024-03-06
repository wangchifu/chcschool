@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出')

@section('content')
    <?php
        $setup =\App\Setup::first();
    ?>
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1>
                {{ $setup->site_name }}：午餐系統
            </h1>
            @if(!empty(session('factory')))
            <h3>廠商：{{ $factory->name }}</h3>
                <div class="text-right"><a href="{{ route('lunch_lists.change_factory') }}" class="btn btn-danger btn-sm" onclick="return confirm('確定？')"><i class="fas fa-sign-out-alt"></i> 廠商登出</a></div>
            @endif
        </div>
        @if(empty(session('factory')))
        <div class="col-md-6">

            <div class="card">
                <div class="card-header"><h4>廠商登入</h4></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('lunch_lists.factory') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="username" class="col-sm-4 col-form-label text-md-right">帳號</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">密碼</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!--
                        <div class="form-group row">
                            <div class="col-md-4 text-md-left">
                            </div>
                            <div class="col-md-6 text-md-left">
                                <img src="{{ route('pic') }}" class="img-fluid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="chaptcha" class="col-md-4 col-form-label text-md-right">驗證碼</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="chaptcha" required placeholder="上圖國字轉阿拉伯數字" maxlength="5">
                            </div>
                        </div>
                        -->

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sign-in-alt"></i> 登入
                                </button>
                            </div>
                        </div>
                        @include('layouts.errors')
                    </form>
                </div>
            </div>
        </div>
        @else
            <div class="col-md-12">
                <form name=myform>
                    <div class="form-control">
                        {{ Form::select('lunch_order_id', $lunch_order_array,$lunch_order_id, ['class' => 'form-control','placeholder'=>'--請選擇--','onchange'=>'jump()']) }}
                    </div>
                </form>
            </div>
            @if($lunch_order_id)
                <div class="col-md-12">
                    <h3>一、教師訂餐明細</h3>
                    <table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1">
                        <tr bgcolor='#005DBE' style='color:white;'>
                            <th>
                                姓名
                            </th>
                            <th>
                                地點
                            </th>
                            <th>
                                餐別
                            </th>
                            <?php $i=1; ?>
                            @foreach($date_array as $k=>$v)
                                <th>
                                    <?php
                                    if(get_chinese_weekday2($k)=="六"){
                                        $txt_bg="text-success";
                                    }elseif(get_chinese_weekday2($k)=="日"){
                                        $txt_bg="text-danger";
                                    }else{
                                        $txt_bg="";
                                    }
                                    $d = substr($k,5,5);
                                    ?>
                                    {{ substr($d,0,2) }}<br>{{ substr($d,3,2) }}
                                    <br>
                                    <span class="{{ $txt_bg }}">{{ get_chinese_weekday2($k) }}</span>
                                </th>
                            @endforeach
                            <th>
                                天數
                            </th>
                            <th>
                                金額
                            </th>
                        </tr>
                        <?php $total_money = 0;$total_days=0;$p_e_data=[]; ?>
                        @foreach($user_data as $k1=>$v1)
                            <tr bgcolor='#FFFFFF'  bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>
                                    {{ $i }}{{ $k1 }}<br>
                                </td>
                                <td>
                                    {{ $place_data[$k1] }}
                                </td>
                                <td>
                                    @if($eat_data[$k1]==1)
                                        <img src="{{ asset('images/meat.png') }}">葷食合菜
                                    @elseif($eat_data[$k1]==2)
                                        <img src="{{ asset('images/vegetarian.png') }}">@if($eat_data_egg[$k1]==1)🥚@endif 素食合菜
                                    @elseif($eat_data[$k1]==3)
                                        <img src="{{ asset('images/meat.png') }}">葷食便當
                                    @elseif($eat_data[$k1]==4)
                                        <img src="{{ asset('images/vegetarian.png') }}">@if($eat_data_egg[$k1]==1)🥚@endif 素食便當
                                    @endif
                                    @if($eat_data_egg[$k1]==1)
                                        (蛋奶素)
                                    @endif
                                    @if($eat_data_egg[$k1]==null and($eat_data[$k1]==2 or $eat_data[$k1]==4))
                                        (奶素)
                                    @endif
                                </td>
                                @foreach($date_array as $k2=>$v2)
                                    <?php
                                    if(get_chinese_weekday2($k2)=="六"){
                                        $bg="#CCFF99";
                                    }elseif(get_chinese_weekday2($k2)=="日"){
                                        $bg="#FFB7DD";
                                    }else{
                                        $bg="";
                                    }
                                    ?>
                                    <td style="background-color:{{ $bg }}">
                                        @if(isset($v1[$k2]))
                                            @if($v1[$k2]['enable']=="eat")
                                                <?php
                                                if(!isset($p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2])) $p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2]=0;
                                                $p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2]++;
                                                ?>
                                                <img src="{{ asset('/images/system_red.png') }}">
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <?php if(!isset($days_data[$k1])) $days_data[$k1]= null ?>
                                    {{ $days_data[$k1] }}
                                    <?php $total_days += $days_data[$k1]; ?>
                                </td>
                                <td>
                                    <?php if(!isset($money_data[$k1])) $money_data[$k1]= null ?>
                                    {{ $money_data[$k1] }}
                                    <?php $total_money += $money_data[$k1]; ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <td></td>
                            <td></td>
                            @foreach($date_array as $k=>$v)
                                <td></td>
                            @endforeach
                            <td>{{ $total_days }}</td>
                            <td>{{ $total_money }}</td>
                        </tr>
                    </table>
                    <?php
                        $l_o = \App\LunchOrder::where('id',$lunch_order_id)->first();
                        $num = \App\LunchTeaDate::where('lunch_factory_id',$factory->id)->where('semester',$l_o->semester)->where('enable','eat')->count();
                    ?>
                    <span class="text-danger">本學期各餐期目前共收入金額為：{{ $num*$teacher_money }}</span>
                </div>
                <hr class="col-md-12">
                <div class="col-md-12">
                    <h3>二、教師各地點數量</h3>
                    
                </div>
                <hr class="col-md-12">
                <div class="col-md-12">
                    <h3>三、班級學生(+老師)數量</h3>
                            
                </div>

            @endif


            <script language='JavaScript'>

                function jump(){
                    if(document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value!=''){
                        location="/lunch_lists/factory/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value;
                    }
                }
            </script>
        @endif
    </div>
@endsection
