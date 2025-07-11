@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '借用清單 | ')

@section('content')
<?php

$active['index'] ="";
$active['my_list'] ="";
$active['admin'] ="";
$active['list'] ="active";

?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>借用清單<a href="{{ route('lends.list_clean') }}" target="_blank"><i class="fas fa-share-square"></i></a></h1>
            @include('lends.nav')
            <br>
            <form id="line_form" action="{{ route('store_line_notify') }}" method="post">
                @csrf 
                <table>
                    <tr>
                        <td>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label"><i class="fab fa-line"></i> LINE BOT [<a href="{{ asset('line_bot.pdf') }}" target="_blank">教學</a>] [<a href="https://www.youtube.com/watch?v=PgYwIH2bHO0" target="_blank">影片</a>]</label>                                                                    
                                <input type="text" class="form-control" id="line_bot_token" name="line_bot_token" value="{{ auth()->user()->line_bot_token }}" placeholder="line bot token">
                                <input type="text" class="form-control" id="line_user_id" name="line_user_id" value="{{ auth()->user()->line_user_id }}" placeholder="user_id">                                                                
                                <input type="text" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="email">                                                                
                                <div id="emailHelp" class="form-text">有借用單時，會發通知給你.</div>
                              </div>
                        </td>
                        <td>
                            <button class="btn btn-success" onclick="return confirm('確定嗎？')">儲存</button>
                        </td>
                    </tr>
                </table>
                </form>


                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">今日要借出</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">今日要歸還</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">全部借單</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="false">逐月借用</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="send_date_form()"><i class="fas fa-print"></i> <span id="ch_date">{{ date('Y-m-d') }}</span> Table</button>
                    </li>
                    <form id="this_form" action="{{ route('lends.print_lend') }}" method="post" target="_blank">
                        @csrf
                        <input type="hidden" id="this_date" name="this_date" value="{{ date('Y-m-d') }}">
                    </form>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <br>
                        <table>
                            <tr>
                                <td>
                                    <a href="#" onclick="change_date(-1,'last_lend','lend_date')">
                                    <i class="fas fa-angle-left"></i>往前
                                    </a>
                                </td>
                                <td>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="this_date1" readonly style="font-size:20px;font-weight:bold;color:black">
                                </td>
                                <td>
                                    <a href="#" onclick="change_date(1,'last_lend','lend_date')">
                                    往後<i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <div class="table-responsive">
                            <div id="last_lend">
                                <table class="table table-border table-striped">
                                    <tr>
                                        <th>
                                            填寫時間
                                        </th>
                                        <th>
                                            借用人
                                        </th>
                                        <th>
                                            借用物品
                                        </th>
                                        <th>
                                            借用時間
                                        </th>
                                        <th>
                                            歸還時間
                                        </th>
                                        <th>
                                            備註
                                        </th>
                                    </tr>                                    
                                        @foreach($lend_orders2 as $lend_order)
                                        <tr>
                                            <td>
                                                {{ $lend_order->created_at }}
                                            </td>
                                            <td>
                                                {{ $lend_order->user->name }}
                                            </td>
                                            <td>
                                                {{ $lend_order->lend_item->name }}<br>{{ $lend_order->num }}
                                            </td>
                                            <td>
                                                {{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}
                                            </td>
                                            <td>
                                                {{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}
                                            </td>
                                            <td>
                                                {{ $lend_order->ps }}
                                            </td>
                                        </tr>
                                        @endforeach                                    
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <br>
                        <table>
                            <tr>
                                <td>
                                    <a href="#" onclick="change_date(-1,'next_lend','back_date')">
                                    <i class="fas fa-angle-left"></i>往前
                                    </a>
                                </td>
                                <td>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="this_date2" readonly style="font-size:20px;font-weight:bold;color:black">
                                </td>
                                <td>
                                    <a href="#" onclick="change_date(1,'next_lend','back_date')">
                                    往後<i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <div class="table-responsive">
                            <div id="next_lend">
                            <table class="table table-border table-striped">
                                <tr>
                                    <th>
                                        填寫時間
                                    </th>
                                    <th>
                                        借用人
                                    </th>
                                    <th>
                                        借用物品
                                    </th>
                                    <th>
                                        借用時間
                                    </th>
                                    <th>
                                        歸還時間
                                    </th>
                                    <th>
                                        備註
                                    </th>
                                </tr>
                                <div id="today_back">
                                    @foreach($lend_orders3 as $lend_order)
                                    <tr>
                                        <td>                                        
                                            {{ $lend_order->created_at }}
                                        </td>
                                        <td>
                                            {{ $lend_order->user->name }}
                                        </td>
                                        <td>
                                            {{ $lend_order->lend_item->name }}<br>{{ $lend_order->num }}
                                        </td>
                                        <td>
                                            {{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}
                                        </td>
                                        <td>
                                            {{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}
                                        </td>
                                        <td>
                                            {{ $lend_order->ps }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </div>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <br>
                        <div class="table-responsive">
                            <table class="table table-border table-striped">
                                <tr>
                                    <th>
                                        填寫時間
                                    </th>
                                    <th>
                                        借用人
                                    </th>
                                    <th>
                                        借用物品
                                    </th>
                                    <th>
                                        借用時間
                                    </th>
                                    <th>
                                        歸還時間
                                    </th>
                                    <th>
                                        備註
                                    </th>
                                    <th>
                                        動作
                                    </th>
                                </tr>
                                @foreach($lend_orders as $lend_order)
                                <?php
                                    $lend_items = \App\LendItem::where('lend_class_id',$lend_order->lend_item->lend_class_id)->get();
                                ?>
                                <form id="update_form{{ $lend_order->id }}" action="{{ route('lends.update_other_order',$lend_order->id) }}" method="post">
                                    @csrf
                                <tr>
                                    <td>
                                        <a href="{{ route('lends.delete_order',$lend_order->id) }}" onclick="if(confirm('您確定要刪除嗎?')) return true;else return false"><i class="fas fa-times-circle text-danger"></i></a> 
                                        {{ $lend_order->created_at }}
                                    </td>
                                    <td>
                                        {{ $lend_order->user->name }}
                                    </td>
                                    <td>                              
                                        <select class="form-control" name="lend_item_id">
                                            @foreach ($lend_items as $lend_item)
                                                <?php
                                                    $lend_sections = config('chcschool.lend_sections');
                                                    $selected = ($lend_order->lend_item->id == $lend_item->id)?"selected":null;
                                                ?>
                                                <option value="{{ $lend_item->id }}" {{ $selected }}>{{ $lend_item->name }}</option>
                                            @endforeach
                                        </select>                                                                                          
                                        <input type="number" class="form-control" name="num" value="{{ $lend_order->num }}">                                                                              
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="lend_date" value="{{ $lend_order->lend_date }}">
                                        <select class="form-control" name="lend_section">
                                            @foreach($lend_sections as $k=>$v)
                                                <?php  
                                                    $selected = ($k == $lend_order->lend_section)?"selected":null;
                                                ?>
                                                <option value="{{ $k }}" {{ $selected }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="back_date" value="{{ $lend_order->back_date }}">
                                        <select class="form-control" name="back_section">
                                            @foreach($lend_sections as $k=>$v)
                                                <?php  
                                                    $selected = ($k == $lend_order->back_section)?"selected":null;
                                                ?>
                                                <option value="{{ $k }}" {{ $selected }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ $lend_order->ps }}
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="return confirm('確定修改別人的借用單？')">更新</button>
                                    </td>
                                </tr>
                                </form>
                                @endforeach
                            </table>
                            {{  $lend_orders->links() }}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                        <br>
                        <?php 
                            //查每個月每日剩餘數量 
                            $this_month = get_month_date(substr($this_date,0,7));
                            $lend_items = \App\LendItem::where('enable','1')->get();
                            $check_num = [];
                            foreach($this_month as $k=>$v){
                                foreach($lend_items as $lend_item){
                                    $check_lend_orders = \App\LendOrder::where('lend_date','<=',$v)
                                        ->where('back_date','>=',$v)
                                        ->where('lend_item_id',$lend_item->id)
                                        ->get();
                                    foreach($check_lend_orders as $lend_order){
                                        if(!isset($check_num[$v][$lend_item->id])) $check_num[$v][$lend_item->id]=0;
                                        $check_num[$v][$lend_item->id] += $lend_order->num;
                                    }
                                }                                  
                            }
                                                                      
                        ?>
                        <table>
                            <tr>
                                <td>
                                    <a href="#" onclick="change_month(-1,'month_data')">
                                    <i class="fas fa-angle-left"></i>往前
                                    </a>
                                </td>
                                <td>
                                    <input type="date" value="{{ $this_date }}" class="form-control" id="this_date3" readonly style="font-size:20px;font-weight:bold;color:black">
                                </td>
                                <td>
                                    <a href="#" onclick="change_month(1,'month_data')">
                                    往後<i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
        
                        <div class="table-responsive" id="month_data">
                            <table class="table table-bordered table-striped">
                                <tr style="background-color:#E0E0E0">
                                    <th>
                                        日期
                                    </th>
                                    @foreach($lend_items as $lend_item)
                                        <th>
                                            {{ $lend_item->name }}
                                        </th>
                                    @endforeach
                                </tr>                                    
                                @foreach($this_month as $k=>$v)
                                <tr>
                                    <td>
                                        {{ $v }} ({{ get_chinese_weekday($v) }})
                                    </td>
                                    @foreach($lend_items as $lend_item)
                                        <?php 
                                            if(!isset($check_num[$v][$lend_item->id])) $check_num[$v][$lend_item->id] = 0;                                                                                                                                             
                                        ?>
                                    @if($check_num[$v][$lend_item->id] > 0)
                                    <td class="text-danger">
                                        {{ $lend_item->num - $check_num[$v][$lend_item->id] }}/{{ $lend_item->num }}
                                    </td>
                                    @else
                                    <td>
                                        {{ $lend_item->num - $check_num[$v][$lend_item->id] }}/{{ $lend_item->num }}
                                    </td>
                                    @endif
                                    @endforeach
                                </tr>
                            @endforeach
                            </table>
                        </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
    <script>
        function change_date(n,id,action){
            if(action == 'lend_date'){
                var this_date = $('#this_date1').val();
            }
            if(action == 'back_date'){
                var this_date = $('#this_date2').val();
            }
            var date = new Date(this_date);
            date.setDate(date.getDate() + n );
            date = formatDate(date);    
            if(action == 'lend_date'){
                $('#this_date1').val(date);
            }
            if(action == 'back_date'){
                $('#this_date2').val(date); 
            }

            $('#this_date').val(date);
            document.getElementById('ch_date').innerHTML = date;
                   
            //alert(date);
            $.ajax({
                url: 'https://{{ $_SERVER['HTTP_HOST'] }}'+'/lends/check_order_out/'+date+'/'+action,
                type : 'get',
                dataType : 'json',
                //data : $('#sunday_form').serialize(),
                success : function(result) {
                    if(result != 'failed') {
                        document.getElementById(id).innerHTML = get_table(result);
                    }
                },
                error: function(result) {
                    alert('失敗');
                }
            })
        }

        function change_month(n,id){
        var this_date = $('#this_date3').val();
        var date = new Date(this_date);
        date.setMonth(date.getMonth() + n);
        date = formatDate(date); 
        $('#this_date3').val(date);
        $.ajax({
            url: 'https://{{ $_SERVER['HTTP_HOST'] }}'+'/lends/check_order_month/'+date,
            type : 'get',
            dataType : 'json',
            //data : $('#sunday_form').serialize(),
            success : function(result) {
                if(result != 'failed') {
                    document.getElementById(id).innerHTML = get_month_table(result);
                }
            },
            error: function(result) {
                alert('失敗');
            }
        })
    }
    
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
    
            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;
    
            return [year, month, day].join('-');
        }
    
        
    
        function get_table(result){
            data = "<table class='table table-border table-striped'><tr><th>填寫時間</th><th>借用人</th><th>借用物品</th><th>借用時間</th><th>歸還時間</th><th>備註</th></tr>";
            for(var k in result){
                var d = new Date(result[k]['created_at']);
                dt = d.toLocaleString('sv');
                data = data+"<tr><td>"+dt+"</td><td>"+result[k]['user']+"</td><td>"+result[k]['lend_item']+"<br>"+result[k]['num']+"</td><td>"+result[k]['lend_date']+"<br>"+result[k]['lend_section']+"</td><td>"+result[k]['back_date']+"<br>"+result[k]['back_section']+"</td><td>"+result[k]['ps']+"</td></tr>";
            }
            data = data+"</table>";
            return data;
        }

        function get_month_table(result){
        data = "<table class='table table-bordered table-striped'>";
        data = data+"<tr style='background-color:#E0E0E0'>";
        data = data+"<th>日期</th>";
        for(var k in result['item']){
            data = data+"<th>"+result['item'][k]+"</th>";
        }
        data = data+"</tr><tr>"
        for(var k in result['data']){
            data = data+"<td>"+k+"</td>";
            for(var k1 in result['item']){
                if(result['data'][k][k1]['all'] > result['data'][k][k1]['left']){
                    data = data+"<td class='text-danger'>"+result['data'][k][k1]['left']+"/"+result['data'][k][k1]['all']+"</td>";
                }else{
                    data = data+"<td>"+result['data'][k][k1]['left']+"/"+result['data'][k][k1]['all']+"</td>";
                }
                
            }
            data = data +"</tr>";
        }
        return data;
    }

        function send_date_form(){
        $('#this_form').submit();
    }
    
    </script>    
@endsection
