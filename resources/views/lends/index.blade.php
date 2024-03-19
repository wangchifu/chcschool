@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '借用系統 | ')

@section('content')
<?php

$active['index'] ="active";
$active['my_list'] ="";
$active['admin'] ="";
$active['list'] ="";

?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>借用系統</h1>
            @include('lends.nav')
        <br>
        <h2>教職員借用</h2>
        @if($errors->any())
                        <h1 class="text-danger">操作失敗！！</h1>
                    @endif
                    @include('layouts.errors')
                    <label class="form-label text-danger">請選擇類別</label>
                        <select class="form-control" aria-label="Default select example" id="change_lend_class">
                            @foreach($lend_classes as $lend_class)
                                <?php
                                    $selected = ($lend_class_id==$lend_class->id)?"selected":null;
                                ?>
                                <option value="{{ $lend_class->id }}" {{ $selected }}>{{ $lend_class->name }}({{ $lend_class->user->name }})</option>
                            @endforeach
                        </select>
                        <hr>
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <td>
                                    <a href="{{ route('lends.index',['lend_class_id'=>$lend_class_id,'this_date'=>$this_dt->subDay()->toDateString()]) }}">
                                    <i class="fas fa-angle-left"></i>往前
                                    </a>
                                </td>
                                <td>
                                    <input type="date" value="{{ $this_date }}" class="form-control" id="change_date" style="font-size:20px;font-weight:bold;color:black">
                                </td>
                                <td>
                                    <a href="{{ route('lends.index',['lend_class_id'=>$lend_class_id,'this_date'=>$this_dt->addDays(2)->toDateString()]) }}">
                                    往後<i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>                        
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-primary text-light">
                                    <th colspan="6">
                                        <h4 class="text-light">借用情形一覽</h4>
                                    </th>
                                </tr>
                                <tr class="bg-light text-dark">
                                    <th>品名</th>
                                    <th>數量(餘/總)</th>
                                    <th colspan="5">注意事項</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lend_items as $lend_item)
                                <?php if(!isset($all_lend_num[$lend_item->id])) $all_lend_num[$lend_item->id]=0; ?>
                                <tr style="background:#E0E0E0;font-weight:bold;color:black">
                                    <td>{{ $lend_item->name }}</td>
                                    <td>剩 {{ $lend_item->num-$all_lend_num[$lend_item->id] }} / {{ $lend_item->num }}</td>
                                    <td colspan="4">
                                    @if($lend_item->ps)
                                    <small class="text-primary">
                                        {!!  nl2br($lend_item->ps) !!}
                                    </small>
                                    @endif
                                    </td>
                                </tr>
                                @if(isset($lend_item_data[$lend_item->id]))
                                @if(count($lend_item_data[$lend_item->id])>0)
                                <tr>
                                    <td>
                                        借出單
                                    </td>
                                    <td>借用人</td>
                                    <td>借用日期</td>
                                    <td>歸還日期</td>
                                    <td colspan="2">備註</td>                                
                                </tr>
                                @endif
                                @endif
                                    @if(isset($lend_item_data[$lend_item->id]))
                                        @foreach($lend_item_data[$lend_item->id] as $k=>$v)
                                            @foreach($v as $kk=>$vv)
                                                <tr>
                                                    <td class="text-danger">借出 {{ $vv['num'] }}</td>
                                                    <td>{{ $kk }}</td>
                                                    <td>{{ $vv['lend_date'] }}</td>
                                                    <td>{{ $vv['back_date'] }}</td>
                                                    <td class="text-danger" colspan="2">{{ $vv['ps'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        <hr>
                            <p>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    整月借出情況
                                </button>
                              </p>
                              <div class="collapse" id="collapseExample">
                                <div class="card card-body">
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
                                <div class="table-responsive">
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
                        <hr>
                        @if(!empty($lend_class_id))
                            <?php $lend_class = \App\LendClass::find($lend_class_id); ?>
                            @if($lend_class->ps)
                            <p class="text-primary">
                                注意事項：<br>
                                {!! nl2br($lend_class->ps) !!}
                            </p>
                            @endif
                        @endif
                        <hr>
                        <div class="table-responsive">
                            <div class="card">
                                <div class="card-header h4" style="background: #E0E0E0">
                                  填寫借用申請單
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('lends.order') }}" method="post" id="order_form">
                                        @csrf
                                        <table class="table table-bordered">
                                            <tr style="background: #F0F0F0">
                                                <td>品名</td>
                                                <td>要借數量</td>
                                                <td colspan="">備註</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select id="change_lend_item" class="form-control" name="lend_item_id" required>
                                                        <?php $i=1;$first_owner="";$first_item_num="";$first_item_sections=[]; ?>
                                                        @foreach($lend_items as $lend_item)
                                                            <option value="{{ $lend_item->id }}">{{ $lend_item->name }}</option>
                                                            <?php 

                                                                if($i==1){
                                                                    $first_item_num = $lend_item->num;
                                                                    $first_item_sections = unserialize($lend_item->lend_sections);
                                                                    $first_owner = $lend_item->user_id;
                                                                }
                                                                
                                                                $i++;
                                                            ?>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="owner_user_id" id="owner_user_id" value="{{ $first_owner }}">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="num" id="num" required>
                                                    @for($n=1;$n<=$first_item_num;$n++ )
                                                        <option value="{{ $n }}">{{ $n }}</option>
                                                    @endfor
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="ps">
                                                </td>
                                                <td>

                                                </td>
                                            </tr> 
                                            <tr style="background: #F0F0F0">
                                                <td>借用日期</td>
                                                <td>第幾節來借</td>
                                                <td>歸還日期</td>
                                                <td>第幾節來還</td>
                                            </tr>
                                            <td>
                                                <input type="date" class="form-control" name="lend_date" required>
                                            </td>
                                            <td>
                                                <?php $section_array = config('chcschool.lend_sections');  ?>
                                                <select class="form-control" name="lend_section" id="lend_section" required>
                                                @foreach($first_item_sections as $k =>$v)
                                                    <option value="{{ $v }}">{{ $section_array[$v] }}</option>
                                                @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="back_date" required>
                                            </td>
                                            <td>
                                                <select class="form-control" name="back_section" id="back_section" required>
                                                @foreach($first_item_sections as $k =>$v)
                                                    <option value="{{ $v }}">{{ $section_array[$v] }}</option>
                                                @endforeach
                                                </select>
                                            </td>
                                        </table>   
                                        <input type="hidden" name="to_go" value="index">                            
                                        <button class="btn btn-success btn-sm" onclick="return confirm('確定預訂嗎？')">我要借用</button>
                                    </form>
                                    @include('layouts.errors')
                                </div>
                              </div>
                        </div>
        </div>
    </div>
    <script>
        $('#change_lend_class').on( "change", function() {
            location="https://{{ $_SERVER['HTTP_HOST'] }}/lends/index/" + $('#change_lend_class').val();
            });
    
        $('#change_date').on( "change", function() {
            location="https://{{ $_SERVER['HTTP_HOST'] }}/lends/index/{{ $lend_class_id }}/" + $('#change_date').val();
            });
    
        $('#change_lend_item').on( "change", function() {
            $.ajax({
                url: 'https://{{ $_SERVER['HTTP_HOST'] }}'+'/lends/check_item_num/'+$('#change_lend_item').val(),
                type : 'get',
                dataType : 'json',
                //data : $('#sunday_form').serialize(),
                success : function(result) {
                    if(result != 'failed') {
                        $('#owner_user_id').val(result['owner_user_id']);
                        document.getElementById('num').innerHTML = get_option(result['num']);
                        document.getElementById('lend_section').innerHTML = get_option2(result['lend_sections'],result['section_array']);
                        document.getElementById('back_section').innerHTML = get_option2(result['lend_sections'],result['section_array']);
                    }
                },
                error: function(result) {
                    alert('失敗');
                }
                })
            });
    
        function get_option(num){
            data = "";
            for (var i = 1; i <= num; i++) {
                data = data+"<option value="+i+">"+i+"</option>";
                }
            return data;
        }
    
        function get_option2(sections,section_array){
            data = "";            
            for (var i in sections) {                
                data = data+"<option value="+sections[i]+">"+section_array[sections[i]]+"</option>";
                }
            return data;
        }
    
    
    </script>
@endsection
