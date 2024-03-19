<?php
echo "<body onload='window.print()'>";
$num=1;
?>
@foreach($user_datas as $k1=>$v1)
<?php
    $total_days = 0;
    foreach($v1 as $k2=>$v2){
        $total_days += $v2;
    }
?>
<div style="border: solid 1px;width:30%;float:left;margin:10px;page-break-inside:avoid;">
    {{ $lunch_setup->semester }} 學期教師午餐收費通知<br>
    編號：{{ $num }}　{{ $k1 }} 你好：<br>
    請於 {{ substr($die_line,5,5) }} 前繳費，謝謝！<br>
    共計 {{ $total_days }} 餐 * {{ $lunch_setup->teacher_money }} 元<br>合計 {{ round($lunch_setup->teacher_money*$total_days) }} 元
        @foreach($lunch_orders as $lunch_order)
            <div style="border: solid 1px;">
                <?php if(!isset($user_datas_by_order[$k1][$lunch_order->id])) $user_datas_by_order[$k1][$lunch_order->id]=0; ?>
                {{ substr($lunch_order->name,5,2) }}月共 {{ $total_order_date[$lunch_order->id] }} 天，用餐 {{ $user_datas_by_order[$k1][$lunch_order->id] }} 天
            </div>
        @endforeach
</div>
@if($num%9==0)
<p style="page-break-after:always"></p>
@endif
<?php $num++; ?>
@endforeach