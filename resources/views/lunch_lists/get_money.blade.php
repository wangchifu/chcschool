<?php
echo "<body onload='window.print()'>";
?>
<h2>餐期：{{ $lunch_order->name }}</h2>
<table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1">
    <tr bgcolor='#005DBE' style='color:white;'>
        <th>
            姓名
        </th>
        <?php $i=1; ?>
        <th>
            天數
        </th>
        <th>
            金額
        </th>
        <th>
            已收費者打勾
        </th>
    </tr>
    <?php $total_money = 0;$total_days=0; ?>
    @foreach($user_data as $k1=>$v1)
        <tr bgcolor='#FFFFFF'  bgcolor='#FFFFFF'>
            <td>
                {{ $i }}{{ $user2name[$k1] }}<br>
            </td>
            <td>
                {{ $days_data[$k1] }}
                <?php $total_days += $days_data[$k1]; ?>
            </td>
            <td>
                {{ round($money_data[$k1]) }}元 ({{ $money_data[$k1] }})
                <?php $total_money += round($money_data[$k1]); ?>
            </td>
            <td>

            </td>
        </tr>
        <?php $i++; ?>
    @endforeach
    <tr>
        <td>合計</td>
        <td>{{ $total_days }}</td>
        <td>{{ $total_money }} 元</td>
        <td></td>
    </tr>
</table>
