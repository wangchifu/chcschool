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
            <h1>借用清單</h1>
            @include('lends.nav')
            <br>
            <form id="line_form" action="{{ route('store_line_notify') }}" method="post">
                @csrf 
                <table>
                    <tr>
                        <td>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label"><i class="fab fa-line"></i> LINE權杖</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="line_key" value="{{ auth()->user()->line_key }}" required>
                                <div id="emailHelp" class="form-text">有借用單時，會發LINE通知給你.</div>
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
    
    </script>    
@endsection
