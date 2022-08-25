@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '午餐系統-學生午餐')

@section('content')
    <?php

    $active['teacher'] ="";
    $active['student'] ="active";
    $active['list'] ="";
    $active['special'] ="";
    $active['order'] ="";
    $active['setup'] ="";
    ?>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>午餐系統-學生午餐</h1>
            @include('lunches.nav')
        @if($admin)            
            @if(count($lunch_class_dates) > 0)
                <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#change_num">班級變更人數</a>
                <a href="{{ route('lunch_stus.delete',$lunch_order_id) }}" class="btn btn-danger btn-sm" onclick="return confirm('你確定要刪除嗎？')">刪除此期班級訂餐資料</a>
                @include('layouts.errors')
            @endif
        <form name="myform">
            <div class="form-control">
                {{ Form::select('lunch_order_id', $lunch_order_array,$lunch_order_id, ['id'=>'lunch_order_id','class' => 'form-control','onchange'=>'jump()']) }}
            </div>
        </form>        
            @if(count($lunch_class_dates) > 0)         
                <form action="{{ route('lunch_stus.store_ps',$lunch_order->id) }}" method="post">
                    @csrf
                <div class="form-group">
                    <label>學生異動說明：</label>
                    <textarea class="form-control" name="order_ps_ps">{{ $lunch_order->order_ps_ps }}</textarea> 
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">儲存</button>
                </div>       
                
                </form>                
                <table>
                    <thead style="background-color:dodgerblue;color:white">
                        <tr>
                            <td rowspan="2">
                                班級
                            </td>
                            <td rowspan="2">
                                廠商
                            </td>
                            @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                @if($lunch_order_date->enable==1)
                                <td nowrap colspan="2">
                                    {{ substr($lunch_order_date->order_date,8,2) }}<br>
                                    <span class="small">({{ get_chinese_weekday2($lunch_order_date->order_date) }})</span>
                                </td>                                           
                                @endif
                            @endforeach                                
                        </tr>
                        <tr>
                            @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                @if($lunch_order_date->enable==1)
                                <td style="background-color:red">
                                    葷
                                </td> 
                                <td style="background-color:green">
                                    素
                                </td>                                          
                                @endif
                            @endforeach  
                        </tr>
                    </thead>
                    <tbody>
                        <?php $all = 0; ?>
                        @foreach($student_classes as $student_class)
                        <tr>
                            <td style="background-color:#D2E9FF">
                                {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }}
                            </td>           
                            <?php $i=1; ?>                           
                            @foreach($lunch_order->lunch_order_dates as $lunch_order_date)
                                @if($lunch_order_date->enable==1)
                                    @if($i==1)
                                        <td>
                                            {{ $lunch_class_data[$student_class->id][$lunch_order_date->order_date]['factory'] }}
                                        </td>  
                                    @endif
                                <td style="background-color:#FFECEC" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $lunch_order_date->order_date }} {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 葷">
                                    {{ $lunch_class_data[$student_class->id][$lunch_order_date->order_date][1] }}
                                </td>
                                <td style="background-color:#DFFFDF" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $lunch_order_date->order_date }} {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 素">
                                    {{ $lunch_class_data[$student_class->id][$lunch_order_date->order_date][4] }}
                                </td>
                                <?php $i++; ?>
                                @endif
                            <?php $all = $all+$lunch_class_data[$student_class->id][$lunch_order_date->order_date][1]+$lunch_class_data[$student_class->id][$lunch_order_date->order_date][4]; ?>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>                  
                總餐次：{{ $all }}   
            @else
            @if(!empty($lunch_orders))
            <form method="post" action="{{ route('lunch_stus.store',$lunch_order_id) }}" id="store_form">
                @csrf                
                <span class="text-danger small">若底下無班級，請至「午餐設定」下方匯入本學期學生資料。</span>
                <br>
                從<input type="date" name="sample_date" id="sample_date" value="{{ date('Y-m-d') }}"><a href="#" class="btn btn-success btn-sm" onclick="copy()">複製</a>
                <table>
                    <thead style="background-color:dodgerblue;color:white">
                        <tr>
                            <td>
                                班級
                            </td>
                            <td>
                                廠商
                            </td>
                            <td>
                                葷 
                            </td>
                            <td>
                                素
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n=1; ?>
                        @foreach($student_classes as $student_class)
                        <tr onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                            <td>
                                {{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }}
                            </td>
                            <td>
                                <select name="lunch_factory_id[{{ $student_class->id }}]">
                                    @foreach($factory_array as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <?php 
                                $e1 = (isset($sample_data[$student_class->id][1]))?$sample_data[$student_class->id][1]:null;
                                $e4 = (isset($sample_data[$student_class->id][4]))?$sample_data[$student_class->id][4]:null;
                            ?>
                            <td>
                                <input type="text" tabindex="{{ $n }}" id="eat_data{{ $n }}" name="eat_data1[{{ $student_class->id }}]" style="width:40px" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 葷" required onkeydown="focusNext(event,{{ $n }})" value="{{ $e1 }}">
                            </td>
                            <?php $n++; ?>
                            <td>
                                <input type="text" tabindex="{{ $n }}" id="eat_data{{ $n }}" name="eat_data4[{{ $student_class->id }}]" style="width:25px" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $student_class->student_year }}{{ sprintf("%02s",$student_class->student_class) }} 素" onkeydown="focusNext(event,{{ $n }})" value="{{ $e4 }}">
                            </td>
                            <?php $n++; ?>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary" onclick="return confirm('確定儲存嗎？資料量大，請等候，不要亂按！')">
                    <i class="fas fa-save"></i> 儲存設定
                </button>
            </form>
            @endif  
            @endif            
        @else
            <h1 class="text-danger">你不是管理者</h1>
        @endif
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="change_num" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">班級變更人數</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('lunch_stus.change_num') }}" method="post" id="change_num_form">
                @csrf
                <div class="form-group">
                    <label for="message-client_secret" class="col-form-label">班級</label>
                    <select name="lunch_class_id" class="form-control" required>
                        @foreach($student_classes as $student_class)
                        <option value="{{ $student_class->id }}">
                            {{ $student_class->student_year }}{{ sprintf('%02s',$student_class->student_class) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="message-client_secret" class="col-form-label">起始日期</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="message-client_secret" class="col-form-label">結束 日期</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="message-client_secret" class="col-form-label">葷食人數</label>
                    <input type="num" class="form-control" name="eat_style1" required>
                </div>
                <div class="form-group">
                    <label for="message-client_secret" class="col-form-label">素食人數</label>
                    <input type="num" class="form-control" name="eat_style4">
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          <button type="button" class="btn btn-primary" onclick="document.getElementById('change_num_form').submit()">送出</button>
        </div>
      </div>
    </div>
  </div>

<script>

    function jump(){
        if(document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value!=''){
            location="/lunch_stus/index/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value;
        }
    }

    function copy(){
        sample_date = $('#sample_date').val();
        location="/lunch_stus/index/" + document.myform.lunch_order_id.options[document.myform.lunch_order_id.selectedIndex].value+'/'+sample_date;
    }
    

    function focusNext(e, n) {
        // arrow down key code = 38 down
      if (e.keyCode === 38 & n >2) {
        // focus next element
        u = n - 2;
        document.getElementById('eat_data'+u).focus();
      }
      // arrow down key code = 40 down
      if (e.keyCode === 40) {
        // focus next element
        d = n + 2;
        document.getElementById('eat_data'+d).focus();
      }
      // arrow down key code = 37 left
      if (e.keyCode === 37 & n>1) {
        // focus next element
        l = n - 1;
        document.getElementById('eat_data'+l).focus();
      }
      // arrow down key code = 39 left
      if (e.keyCode === 39) {
        // focus next element
        r = n + 1;
        document.getElementById('eat_data'+r).focus();
      }
    }

</script>
@endsection
