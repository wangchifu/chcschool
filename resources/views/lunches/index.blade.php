@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統')

@section('content')
    <?php

    $active['teacher'] ="active";
    $active['student'] ="";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統</h1>
            @include('lunches.nav')
            <br>
            <form name=myform>
            <div class="form-group">
                {{ Form::select('lunch_order_id', $lunch_order_array,$lunch_order_id, ['id'=>'lunch_order_id','class' => 'form-control','placeholder'=>'--請選擇餐期--','onchange'=>'jump()']) }}
            </div>
            </form>
            @if($lunch_order_id)
                @if(!$has_order)
                    @if(date('Ymd') > $month_die_date and $teacher_open==null)
                        <br>
                        <h3 class="text-danger">已逾期！</h3>
                        <h4>最慢訂餐日為{{ $month_die_date }}</h4>
                    @else
                        @if($lunch_order->order_ps)
                        <div class="card">
                            <div class="card-body">
                                備註：<span class="text-danger">{{ $lunch_order->order_ps }}</span>
                            </div>
                        </div>
                        @endif
                        <hr>
                        <form action="{{ route('lunches.store') }}" method="post" id="store_form" onsubmit="return submitOnce(this);">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    1.選擇廠商
                                </div>
                                <div class="card-body">
                                    {{ Form::select('lunch_factory_id', $lunch_factory_array,null, ['id'=>'lunch_factory_id','class' => 'form-control','placeholder'=>'--請選擇廠商--','required'=>'required']) }}
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <div class="card-header">
                                    2.選擇取餐地點 (<small>導師請選班級教室，填入3碼班級代碼</small>)
                                </div>
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="radio" name="select_place" id="s1" checked value="place_select"> <label for="s1">指定地點　　　　　　</label>
                                            </td>
                                            <td>
                                                <input type="radio" name="select_place" id="s2" value="place_class"> <label for="s2">班級代號3碼</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ Form::select('lunch_place_id', $lunch_place_array,null, ['id'=>'place_select','class' => 'form-control','placeholder'=>'--請選擇地點--','required'=>'required']) }}
                                            </td>
                                            <td>
                                                <input type="text" name="class_no" id="place_class" maxlength="3" class="form-control" style="display: none;" placeholder="班級代號3碼" required value="1">
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <div class="card-header">
                                    3.選擇葷素
                                </div>
                                <div class="card-body">
                                    {{ Form::select('eat_style', $eat_array,null, ['id'=>'eat_style','class' => 'form-control','placeholder'=>'--請選擇葷素--','required'=>'required']) }}
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <div class="card-header">
                                    4.訂餐日期
                                </div>
                                <div class="card-body">
                                    <h3>{{ $lunch_order->name }}</h3>
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
                                        $first_w = get_date_w($lunch_order->name."-01");
                                        ?>
                                        <tr>
                                            @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                                <?php
                                                $this_date_w = get_date_w($lunch_order_date->order_date);
                                                if($this_date_w==0){
                                                    $text_color = "btn btn-danger btn-sm";
                                                }elseif($this_date_w==6){
                                                    $text_color = "btn btn-success btn-sm";

                                                }else{
                                                    $text_color = "btn btn-outline-dark btn-sm";

                                                }
                                                $d = explode('-',$lunch_order_date->order_date);
                                                ?>
                                                @if($d[2]== "01")
                                                    @for($i=1;$i<=$first_w;$i++)
                                                        <td></td>
                                                    @endfor
                                                @endif
                                                <td>
                                                    @if($lunch_order_date->enable)
                                                        <input type="checkbox" name="order_date[{{ $lunch_order_date->order_date }}]" value="1" id="enable{{ $lunch_order_date->order_date }}" checked>
                                                        訂餐
                                                    @else
                                                        --
                                                    @endif
                                                    <label for="enable{{ $lunch_order_date->order_date }}" class="{{ $text_color }}">{{ substr($lunch_order_date->order_date,5,5) }}</label>
                                                    <br>
                                                    <small>{{ $lunch_order_date->date_ps }}</small>
                                                </td>
                                                @if($this_date_w == 6)
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button class="btn btn-success btn-sm" onclick="if(confirm('確定嗎？確定後將只能退餐，不能再更改廠商、取餐地點及葷素喔！！')){change_button();return true;}else return false;" id="submit_button"><i class="fas fa-plus"></i>  我要訂餐</button>
                                </div>
                            </div>
                            <input type="hidden" name="semester" value="{{ $lunch_order->semester }}">
                            <input type="hidden" name="lunch_order_id" value="{{ $lunch_order->id }}">
                        </form>
                    @endif
                @else
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            訂餐資訊
                        </div>
                        <div class="card-body">
                            <?php
                                $lunch_tea_dates = \App\LunchTeaDate::where('lunch_order_id',$lunch_order->id)
                                    ->where('user_id',auth()->user()->id)
                                    ->get();
                                foreach($lunch_tea_dates as $lunch_tea_date){
                                    $tea_data[$lunch_tea_date->order_date] = $lunch_tea_date->enable;
                                }
                                $days = \App\LunchTeaDate::where('lunch_order_id',$lunch_order->id)
                                    ->where('user_id',auth()->user()->id)
                                    ->where('enable','eat')
                                    ->count();
                                $factory = $lunch_tea_dates[0]->lunch_factory;
                                $eat_style = $lunch_tea_dates[0]->eat_style;
                            ?>
                            <table class="table table-striped">
                                <tr>
                                    <td>
                                        <label>1.廠商</label>
                                        <h4>{{ $factory->name }}</h4>
                                    </td>
                                    <td>
                                        <label>2.取餐地點</label>
                                        @if(substr($lunch_tea_dates[0]->lunch_place_id,0,1)=="c")
                                            <h4>{{ substr($lunch_tea_dates[0]->lunch_place_id,1,4) }}教室</h4>
                                        @else
                                            <h4>{{ $lunch_tea_dates[0]->lunch_place->name }}</h4>
                                        @endif

                                    </td>
                                    <td>
                                        <label>3.葷素</label>
                                        @if($eat_style==1)
                                            <h4 class="text-danger">葷食合菜</h4>
                                        @elseif($eat_style==2)
                                            <h4 class="text-success">素食合菜</h4>
                                        @elseif($eat_style==3)
                                            <h4 class="text-danger">葷食便當</h4>
                                        @elseif($eat_style==4)
                                            <h4 class="text-success">素食便當</h4>
                                        @endif
                                    </td>
                                    <td>
                                        <label>本期訂餐費用</label>
                                        <h4>{{ $lunch_setup->teacher_money*$days }} 元</h4>
                                    </td>
                                </tr>
                            </table>
                            <div class="card">
                                <div class="card-header">
                                    4.訂餐日期( 已訂餐 {{ $days }} 天 )
                                </div>
                                <div class="card-body">
                                    @if($disable==1)

                                    @else
                                        <h5 class="text-danger">{{ $die_date }} 前之訂餐日不得更動！</h5>
                                        <form action="{{ route('lunches.update') }}" method="post">
                                    @endif
                                        @csrf
                                        @method('patch')


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
                                            $first_w = get_date_w($lunch_order->name."-01");
                                            ?>
                                            <tr>
                                                @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                                    <?php
                                                    $this_date_w = get_date_w($lunch_order_date->order_date);
                                                    if($this_date_w==0){
                                                        $text_color = "btn btn-danger btn-sm";
                                                    }elseif($this_date_w==6){
                                                        $text_color = "btn btn-success btn-sm";

                                                    }else{
                                                        $text_color = "btn btn-outline-dark btn-sm";

                                                    }
                                                    $d = explode('-',$lunch_order_date->order_date);

                                                    $checked = ($tea_data[$lunch_order_date->order_date]=="eat")?"checked":"";
                                                    $false = (str_replace('-','',$lunch_order_date->order_date) < $die_date)?"return false;":"";

                                                    if($disable) $false="return false;";

                                                    ?>
                                                    @if($d[2]== "01")
                                                        @for($i=1;$i<=$first_w;$i++)
                                                            <td></td>
                                                        @endfor
                                                    @endif
                                                    <td>
                                                        @if($lunch_order_date->enable)
                                                            <input type="checkbox" name="order_date[{{ $lunch_order_date->order_date }}]" value="1" id="enable{{ $lunch_order_date->order_date }}" {{ $checked }} onclick="{{ $false }}">
                                                            @if(str_replace('-','',$lunch_order_date->order_date) < $die_date)
                                                                <label for="enable{{ $lunch_order_date->order_date }}">
                                                                    <del>訂餐</del>
                                                                </label>
                                                            @else
                                                                <label for="enable{{ $lunch_order_date->order_date }}">
                                                                    訂餐
                                                                </label>
                                                            @endif

                                                        @else
                                                            --
                                                        @endif
                                                        <label for="enable{{ $lunch_order_date->order_date }}" class="{{ $text_color }}">{{ substr($lunch_order_date->order_date,5,5) }}</label>
                                                        <br>
                                                        <small>{{ $lunch_order_date->date_ps }}</small>
                                                    </td>
                                                    @if($this_date_w == 6)
                                            </tr>
                                            @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    <input type="hidden" name="lunch_order_id" value="{{ $lunch_order->id }}">
                                    @if($disable==1)
                                        <h4 class="text-danger">系統已設定停止退餐</h4>
                                    @else
                                        <button class="btn btn-success btn-sm" onclick="return confirm('確定修改嗎？')"><i class="fas fa-edit"></i> 我要修改</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <h2>各學期訂餐統計</h2>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <?php $n=1; ?>
                    @foreach($all_lunch_tea as $k=>$v)
                        <?php
                            $active = ($n==1)?"active":"";
                            $select = ($n==1)?"true":"false";
                        ?>
                    <li class="nav-item">
                        <a class="nav-link {{ $active }}" id="pills-lunch{{ $n }}-tab" data-toggle="pill" href="#pills-lunch{{ $n }}" role="tab" aria-controls="pills-lunch{{ $n }}" aria-selected="{{ $select }}">{{ $k }}</a>
                    </li>
                        <?php $n++; ?>
                    @endforeach
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <?php $n=1; ?>
                    @foreach($all_lunch_tea as $k=>$v)
                        <?php
                        $active = ($n==1)?"show active":"";
                        ?>
                    <div class="tab-pane fade {{ $active }}" id="pills-lunch{{ $n }}" role="tabpanel" aria-labelledby="pills-lunch{{ $n }}">
                        <?php $total=0; ?>
                        <table class="table table-striped">
                            <tr>
                                <th>
                                    學期
                                </th>
                                <th>
                                    餐期
                                </th>
                                <th>
                                    單價
                                </th>
                                <th>
                                    訂餐數
                                </th>
                                <th>
                                    小計
                                </th>
                            </tr>
                        @foreach($v as $k1=>$v1)
                            <tr>
                                <td>
                                    {{ $k }}
                                </td>
                                <td>
                                    {{ $k1 }}
                                </td>
                                <td>
                                    {{ $v1['t_money'] }}
                                </td>
                                <td>
                                    {{ $v1['num'] }}
                                </td>
                                <td>
                                    {{ $v1['t_money']*$v1['num'] }}
                                    <?php $total += $v1['t_money']*$v1['num']; ?>
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <th>
                                    本學期合計
                                </th>
                                <th>
                                </th>
                                <th>
                                </th>
                                <th>
                                </th>
                                <th>
                                    {{ $total }}
                                </th>
                            </tr>
                        </table>
                    </div>
                        <?php $n++; ?>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
    <br>
    <br>
    <script language='JavaScript'>

        function jump(){
            if(document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value!=''){
                location="/lunches/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value;
            }
        }

        $('#s1').click(function(){
            $('#place_class').hide();
            $('#place_select').show();
            $('#place_class').val('1');
        });
        $('#s2').click(function(){
            $('#place_class').show();
            $('#place_select').hide();
            $('#place_class').val('');
            $('#place_select').val('1');
        });


        var validator = $("#store_form").validate();

        var submitcount=0;
        function submitOnce (form){
            if (submitcount == 0){
                submitcount++;
                return true;
            } else{
                alert('正在操作,請不要重複提交,謝謝!');
                return false;
            }
        }
        function change_button(){
            $("#submit_button").removeAttr('onclick');
            $("#submit_button").attr('disabled','disabled');
            $("#submit_button").addClass('disabled');
            $("#store_form").submit();
        }

    </script>
@endsection
