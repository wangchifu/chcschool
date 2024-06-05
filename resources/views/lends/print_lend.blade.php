<?php
echo "<body onload='window.print()'>";
?>
<style>
table {
    border-collapse: collapse; 
    border:1px solid #000000;
} 

table tr{
    font-size:20px;
}
table td{
    border:1px dotted #000000;
    padding:5px;    
}
table td:first-child{
    border-left:0px solid #000000;
}
table th{
   border:2px solid #000000;
   padding:5px;
}
</style>

<h1>{{ $this_date }} 借用狀況</h1>
<table>
<tr style="background: #7B7B7B">
    <th>
        動作
    </th>
    <th>
        第幾節下課
    </th>
    <th>
        借用人
    </th>
    <th>
        借用物品
    </th>
    <th>
        數量
    </th>
    <th>
        借用期間
    </th>
    <th>
        備註
    </th>
</tr>
@foreach($lend_data as $k=>$v)
    <tr>
        <th>
            {{ $v['動作'] }}
        </th>
        <th>
            {{ $v['第幾節下課'] }}
        </th>
        <th>
            {{ $v['借用人'] }}
        </th>
        <th>
            {{ $v['借用物品'] }}
        </th>
        <th>
            {{ $v['數量'] }}
        </th>
        <th>
            {{ $v['借用期間'] }}
        </th>
        <th>
            {{ $v['備註'] }}
        </th>
    </tr>
@endforeach
</table>