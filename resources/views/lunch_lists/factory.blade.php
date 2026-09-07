@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '午餐系統-報表輸出 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：表單與按鈕 Focus 高對比視覺提示 */
    .form-control:focus-visible,
    select:focus-visible,
    .btn:focus-visible,
    a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }

    /* 報表表格無障礙與視覺調校 */
    table.lunch-report-table {
        border: 1px solid #333;
        border-collapse: collapse;
        text-align: center;
        width: 100%;
    }
    table.lunch-report-table th, 
    table.lunch-report-table td {
        border: 1px solid #666;
        padding: 4px;
    }
</style>

<?php
    $setup = \App\Setup::first();
?>

<!-- mt-4 pt-3 修正頂部過度貼近版面的問題 -->
<div class="row justify-content-center mt-4 pt-3">
    <main class="col-md-12" aria-label="午餐系統報表輸出主區域">
        <!-- 無障礙 HM1010301C 修正：主標題結構 -->
        <div class="text-center mb-4">
            <h1 class="h2 font-weight-bold">
                {{ $setup->site_name }}：午餐系統
            </h1>
            @if(!empty(session('factory')))
                <p class="h4 text-secondary">廠商：{{ $factory->name }}</p>
                <div class="text-right">
                    <a href="{{ route('lunch_lists.change_factory') }}" class="btn btn-danger btn-sm" onclick="return confirm('確定要登出廠商嗎？')">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> 廠商登出
                    </a>
                </div>
            @endif
        </div>

        @if(empty(session('factory')))
            <!-- 廠商登入表單 -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h2 class="h5 m-0 font-weight-bold">廠商登入</h2>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('lunch_lists.factory') }}">
                                @csrf
                                <div class="form-group row">
                                    <!-- 無障礙 HM1150100C 修正：專屬 Label 對應 -->
                                    <label for="username" class="col-sm-4 col-form-label text-md-right">帳號</label>
                                    <div class="col-md-6">
                                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
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
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password">
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i> 登入
                                        </button>
                                    </div>
                                </div>
                                @include('layouts.errors')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- 餐期選擇選單 -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <form name="myform" class="form-inline">
                        <label for="lunch_order_id" class="font-weight-bold mr-2">請選擇餐期：</label>
                        {{ Form::select('lunch_order_id', $lunch_order_array, $lunch_order_id, ['id' => 'lunch_order_id', 'class' => 'form-control', 'placeholder' => '--請選擇--', 'onchange' => 'jump()', 'title' => '請選擇餐期']) }}
                    </form>
                </div>
            </div>

            @if($lunch_order_id)
                <!-- 一、教師訂餐明細 -->
                <section class="mb-5" aria-label="教師訂餐明細">
                    <h2 class="h4 font-weight-bold mb-3">一、教師訂餐明細</h2>
                    <div class="table-responsive">
                        <table class="lunch-report-table" aria-label="教師訂餐明細表">
                            <caption class="sr-only">教師訂餐明細表：顯示教師姓名、地點、餐別及每日用餐紀錄與金額小計</caption>
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col">姓名</th>
                                    <th scope="col">地點</th>
                                    <th scope="col">餐別</th>
                                    <?php $i=1; ?>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                        <th scope="col">
                                            <?php
                                            if(get_chinese_weekday2($k)=="六"){
                                                $txt_bg="text-success";
                                            }elseif(get_chinese_weekday2($k)=="日"){
                                                $txt_bg="text-warning";
                                            }else{
                                                $txt_bg="";
                                            }
                                            $d = substr($k,5,5);
                                            ?>
                                            {{ substr($d,0,2) }}/{{ substr($d,3,2) }}<br>
                                            <span class="{{ $txt_bg }}">{{ get_chinese_weekday2($k) }}</span>
                                        </th>
                                        @endif
                                    @endforeach
                                    <th scope="col">天數</th>
                                    <th scope="col">金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_money = 0; $total_days = 0; $p_e_data = []; ?>
                                @foreach($user_data as $k1 => $v1)
                                    <tr>
                                        <!-- 無障礙 HM1010301C 修正：每一列採用 scope="row" -->
                                        <th scope="row" class="font-weight-normal text-nowrap">
                                            {{ $i }}. {{ $user2name[$k1] }}
                                        </th>
                                        <td class="text-nowrap">{{ $place_data[$k1] }}</td>
                                        <td class="text-nowrap">
                                            <!-- 無障礙 HM1120201C 修正：圖片補充說明 alt -->
                                            @if($eat_data[$k1]==1)
                                                <img src="{{ asset('images/meat.png') }}" alt="葷食"> 葷食合菜
                                                <?php $a="葷食合菜"; ?>
                                            @elseif($eat_data[$k1]==2)
                                                @if($eat_data_egg[$k1]==1)
                                                    <img src="{{ asset('images/egg.png') }}" alt="蛋奶素">
                                                @else
                                                    <img src="{{ asset('images/vegetarian.png') }}" alt="奶素">
                                                @endif
                                                素食合菜
                                                <?php $a="素食合菜"; ?>
                                            @elseif($eat_data[$k1]==3)                                        
                                                <img src="{{ asset('images/meat.png') }}" alt="葷食"> 葷食便當
                                                <?php $a="葷食便當"; ?>
                                            @elseif($eat_data[$k1]==4)
                                                @if($eat_data_egg[$k1]==1)
                                                    <img src="{{ asset('images/egg.png') }}" alt="蛋奶素">
                                                @else
                                                    <img src="{{ asset('images/vegetarian.png') }}" alt="奶素">
                                                @endif    
                                                素食便當
                                                <?php $a="素食便菜"; ?>
                                            @endif

                                            @if($eat_data_egg[$k1]==null)
                                                <?php $b=""; ?>
                                            @endif
                                            @if($eat_data_egg[$k1]==1)
                                                (蛋奶素)
                                                <?php $b="(蛋奶素)"; ?>                                    
                                            @endif
                                            @if($eat_data_egg[$k1]==null and ($eat_data[$k1]==2 or $eat_data[$k1]==4))
                                                (奶素)
                                                <?php $b="(奶素)"; ?>                                    
                                            @endif                                    
                                        </td>
                                        @foreach($date_array as $k2=>$v2)                                    
                                            @if($v2==1)
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k2,5,5).' '.$k1.'('.$place_data[$k1].') '.$a.$b }}">
                                                @if(isset($v1[$k2]))
                                                    @if($v1[$k2]['enable']=="eat")
                                                        <?php
                                                        if(!isset($p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2])) $p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2]=0;
                                                        $p_e_data[$place_data[$k1]][$eat_data[$k1]][$k2]++;
                                                        ?>
                                                        <img src="{{ asset('/images/system_red.png') }}" alt="用餐">
                                                    @endif
                                                @endif
                                            </td>
                                            @endif
                                        @endforeach
                                        <td class="font-weight-bold">
                                            <?php if(!isset($days_data[$k1])) $days_data[$k1]= null; ?>
                                            {{ $days_data[$k1] }}
                                            <?php $total_days += $days_data[$k1]; ?>
                                        </td>
                                        <td class="font-weight-bold">
                                            <?php if(!isset($money_data[$k1])) $money_data[$k1]= null; ?>
                                            {{ $money_data[$k1] }}
                                            <?php $total_money += $money_data[$k1]; ?>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <th scope="row">合計</th>
                                    <td></td>
                                    <td></td>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                            <td></td>
                                        @endif
                                    @endforeach
                                    <td>{{ $total_days }}</td>
                                    <td>{{ $total_money }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php
                        $l_o = \App\LunchOrder::where('id',$lunch_order_id)->first();
                        $num = \App\LunchTeaDate::where('lunch_factory_id',$factory->id)->where('semester',$l_o->semester)->where('enable','eat')->count();
                    ?>
                    <p class="text-danger font-weight-bold mt-2">本學期各餐期目前共收入金額為：{{ $num*$teacher_money }} 元</p>
                </section>

                <hr>

                <!-- 二、教師各地點數量 -->
                <section class="mb-5" aria-label="教師各地點數量統計">
                    <h2 class="h4 font-weight-bold mb-3">二、教師各地點數量</h2>
                    <div class="table-responsive">
                        <table class="lunch-report-table" aria-label="教師各地點數量統計表">
                            <caption class="sr-only">教師各地點數量統計表：包含用餐地點與每日葷食、蛋奶素、奶素餐數統計</caption>
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col" rowspan="2">地點</th>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                        <th scope="col" colspan="3">
                                            <?php
                                            if(get_chinese_weekday2($k)=="六"){
                                                $txt_bg="text-success";
                                            }elseif(get_chinese_weekday2($k)=="日"){
                                                $txt_bg="text-warning";
                                            }else{
                                                $txt_bg="";
                                            }
                                            $d = substr($k,5,5);
                                            ?>
                                            {{ substr($d,0,2) }}/{{ substr($d,3,2) }}<br>
                                            <span class="{{ $txt_bg }}">{{ get_chinese_weekday2($k) }}</span>
                                        </th>                             
                                        @endif
                                    @endforeach
                                </tr>
                                <tr class="bg-light">
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                            <th scope="col" style="background-color: #FFECEC"><span class="text-danger">葷</span></th>
                                            <th scope="col"><span class="text-success">蛋奶素</span></th>
                                            <th scope="col"><span class="text-success">奶素</span></th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $one_day1=[]; $one_day41=[]; $one_day4=[]; ?>
                                @foreach($place_data2 as $k=>$v)
                                    <tr>
                                        <th scope="row" class="font-weight-normal text-nowrap">{{ $k }}</th>
                                        @foreach($date_array as $k1=>$v1)
                                            @if($v1==1)
                                                <?php
                                                    if(!isset($v[$k1][1]) or $v[$k1][1]==0) $v[$k1][1] = 0;
                                                    if(!isset($v[$k1][41]) or $v[$k1][41]==0) $v[$k1][41] = 0;
                                                    if(!isset($v[$k1][4]) or $v[$k1][4]==0) $v[$k1][4] = 0;
                                                ?>
                                                <td style="background-color: #FFECEC" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k1,5,5).'('.$k.')' }}">{{ $v[$k1][1] }}</td>
                                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k1,5,5).'('.$k.')' }}">{{ $v[$k1][41] }}</td>
                                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k1,5,5).'('.$k.')' }}">{{ $v[$k1][4] }}</td>
                                                <?php
                                                    if(!isset($one_day1[$k1])) $one_day1[$k1] = 0;
                                                    if(!isset($one_day41[$k1])) $one_day41[$k1] = 0;
                                                    if(!isset($one_day4[$k1])) $one_day4[$k1] = 0;
                                                    $one_day1[$k1] += $v[$k1][1];
                                                    $one_day41[$k1] += $v[$k1][41];
                                                    $one_day4[$k1] += $v[$k1][4];
                                                ?>
                                            @endif                                        
                                        @endforeach                            
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <th scope="row">合計</th>
                                    <?php if(!isset($all)) $all = 0; ?>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                            <?php
                                                if(!isset($one_day1[$k])) $one_day1[$k] = 0;
                                                if(!isset($one_day41[$k])) $one_day41[$k] = 0;
                                                if(!isset($one_day4[$k])) $one_day4[$k] = 0;
                                            ?>
                                            <td style="background-color: #FFECEC" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k,5,5).'(葷)' }}">{{ $one_day1[$k] }}</td>
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k,5,5).'(蛋奶素)' }}">{{ $one_day41[$k] }}</td>
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k,5,5).'(奶素)' }}">{{ $one_day4[$k] }}</td>
                                            <?php $all += $one_day1[$k]+$one_day41[$k]+$one_day4[$k]; ?>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <th scope="row"><span class="sr-only">餐別圖示說明</span></th>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                            <td style="background-color: #FFECEC"><img src="{{ asset('images/meat.png')}}" alt="葷食"></td>                                        
                                            <td><img src="{{ asset('images/egg.png')}}" alt="蛋奶素"></td>
                                            <td><img src="{{ asset('images/vegetarian.png')}}" alt="奶素"></td>
                                        @endif
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                        <p class="font-weight-bold mt-2">總計：{{ $all }} 餐次</p>
                    </div>
                </section>

                <hr>

                <!-- 三、班級學生(+老師)數量 -->
                <section class="mb-5" aria-label="班級學生與教師數量統計">
                    <h2 class="h4 font-weight-bold mb-3">三、班級學生(+老師)數量</h2>
                    <?php $lunch_order = \App\LunchOrder::find($lunch_order_id); ?>
                    @if(!empty($lunch_order->order_ps_ps))
                        <div class="alert alert-warning text-danger mb-3" role="alert">
                            <strong>備註：</strong><br>
                            {!! nl2br(e($lunch_order->order_ps_ps)) !!}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="lunch-report-table" aria-label="班級學生與教師數量統計表">
                            <caption class="sr-only">班級學生與教師數量統計表：列出各班級每日訂餐人數（含導師加點數）</caption>
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col" rowspan="2">班級</th>
                                    @foreach($date_array as $kk=>$vv)
                                        <?php
                                            $dd = explode('-',$kk);
                                            if(get_chinese_weekday2($kk)=="六"){
                                                $txt_bg="text-success";
                                            }elseif(get_chinese_weekday2($kk)=="日"){
                                                $txt_bg="text-warning";
                                            }else{
                                                $txt_bg="";
                                            }
                                        ?>
                                        @if($vv==1)
                                            <th scope="col" colspan="3">
                                                {{ $dd[1] }}/{{ $dd[2] }}<br>
                                                <span class="{{ $txt_bg }}">{{ get_chinese_weekday2($kk) }}</span>
                                            </th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr class="bg-light">                                
                                    @foreach($date_array as $kk=>$vv)
                                        @if($vv=="1")
                                            <th scope="col" style="background-color: #FFECEC"><span class="text-danger">葷</span></th>
                                            <th scope="col"><span class="text-success">蛋奶素</span></th>
                                            <th scope="col"><span class="text-success">奶素</span></th>
                                        @endif
                                    @endforeach
                                </tr>      
                            </thead>
                            <tbody>
                                <?php $all = 0; $one_day = 0; $one_day1=[]; $one_day41=[]; $one_day4=[]; ?>
                                @foreach($student_classes as $student_class)
                                    <tr>
                                        <th scope="row" class="font-weight-normal text-nowrap">
                                            {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }}
                                        </th>
                                        @foreach($date_array as $kk=>$vv)                                   
                                            @if($vv=="1")
                                            <td style="background-color: #FFECEC" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($kk,5,5) }} {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 葷">
                                                @if(isset($lunch_class_data[$student_class->id][$kk][1]))
                                                    {{ $lunch_class_data[$student_class->id][$kk][1] }}
                                                @else                                                                                                
                                                    <?php $lunch_class_data[$student_class->id][$kk][1]=0; ?>
                                                @endif
                                                @if(isset($place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][1]) and $place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][1] !=0)
                                                    <br>
                                                    <small class="text-primary font-weight-bold">+{{ $place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][1] }}</small>
                                                @endif
                                            </td>        
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($kk,5,5) }} {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 蛋奶素">
                                                @if(isset($lunch_class_data[$student_class->id][$kk][41]))
                                                    {{ $lunch_class_data[$student_class->id][$kk][41] }}
                                                @else
                                                    <?php $lunch_class_data[$student_class->id][$kk][41]=0; ?>
                                                @endif
                                                @if(isset($place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][41]) and $place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][41] !=0)
                                                    <br>
                                                    <small class="text-primary font-weight-bold">+{{ $place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][41] }}</small>
                                                @endif
                                            </td>
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($kk,5,5) }} {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 奶素">
                                                @if(isset($lunch_class_data[$student_class->id][$kk][4]))
                                                    {{ $lunch_class_data[$student_class->id][$kk][4] }}
                                                @else
                                                    <?php $lunch_class_data[$student_class->id][$kk][4]=0; ?>
                                                @endif
                                                @if(isset($place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][4]) and $place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][4] !=0)
                                                    <br>
                                                    <small class="text-primary font-weight-bold">+{{ $place_data2[$student_class->student_year.sprintf("%02s",$student_class->student_class).'教室'][$kk][4] }}</small>
                                                @endif
                                            </td>                                         
                                            @endif
                                            <?php
                                                if(!isset($one_day1[$kk])) $one_day1[$kk] = 0;
                                                if(!isset($one_day41[$kk])) $one_day41[$kk] = 0;
                                                if(!isset($one_day4[$kk])) $one_day4[$kk] = 0;
                                                if(!isset($lunch_class_data[$student_class->id][$kk][1])) $lunch_class_data[$student_class->id][$kk][1] = 0;
                                                if(!isset($lunch_class_data[$student_class->id][$kk][41])) $lunch_class_data[$student_class->id][$kk][41] = 0;
                                                if(!isset($lunch_class_data[$student_class->id][$kk][4])) $lunch_class_data[$student_class->id][$kk][4] = 0;
                                                $one_day1[$kk] += $lunch_class_data[$student_class->id][$kk][1];
                                                $one_day41[$kk] += $lunch_class_data[$student_class->id][$kk][41];
                                                $one_day4[$kk] += $lunch_class_data[$student_class->id][$kk][4];
                                            ?>
                                        @endforeach
                                    </tr>                                
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <th scope="row">合計</th>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                        <?php
                                            if(!isset($one_day1[$k])) $one_day1[$k] = 0;
                                            if(!isset($one_day41[$k])) $one_day41[$k] = 0;
                                            if(!isset($one_day4[$k])) $one_day4[$k] = 0;
                                        ?>
                                            <td style="background-color: #FFECEC" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k,5,5) }} 葷">
                                                {{ $one_day1[$k] }}
                                            </td>                                        
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k,5,5) }} 蛋奶素">
                                                {{ $one_day41[$k] }}
                                            </td>
                                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ substr($k,5,5) }} 奶素">
                                                {{ $one_day4[$k] }}
                                            </td>
                                            <?php $one_day += $one_day1[$k]+$one_day41[$k]+$one_day4[$k]; ?>
                                        @endif                                    
                                    @endforeach
                                </tr>
                                <?php $all += $one_day; ?>
                                <tr>
                                    <th scope="row"><span class="sr-only">餐別圖示說明</span></th>
                                    @foreach($date_array as $k=>$v)
                                        @if($v==1)
                                            <td style="background-color: #FFECEC"><img src="{{ asset('images/meat.png')}}" alt="葷食"></td>                                        
                                            <td><img src="{{ asset('images/egg.png')}}" alt="蛋奶素"></td>
                                            <td><img src="{{ asset('images/vegetarian.png')}}" alt="奶素"></td>
                                        @endif
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>   
                        <p class="font-weight-bold mt-2 mb-5">本期總餐數：{{ $all }} (不含老師)</p>
                    </div>                                            
                </section>
            @endif

            <script>
                function jump(){
                    var lunchSelect = document.getElementById('lunch_order_id');
                    if(lunchSelect && lunchSelect.value !== ''){
                        location.href = "/lunch_lists/factory/" + lunchSelect.value;
                    }
                }
            </script>
        @endif
    </main>
</div>
@endsection