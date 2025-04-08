<?php
echo "<body onload='window.print()'>";
$i=1;
?>
<table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1" width="100%">
    <?php $total_money = 0;$total_money2 = 0;$total_days=0; ?>
    <tr>
        <th>
            餐期
        </th>
        <th>
            姓名
        </th>
        <th>
            單價
        </th>
        <th>
            餐次
        </th>
        <th>
            收費
        </th>
        <th>
            備註
        </th>
    </tr>
    @foreach($user_data as $k1=>$v1)
        <tr bgcolor='#FFFFFF'  bgcolor='#FFFFFF'>
            <td>
                教師午餐收費通知<br>
                餐期：{{ $lunch_order->name }}
            </td>
            <td>
                {{ $i }} - {{ $user2name[$k1] }}<br>
            </td>
            <td>
                單價：{{ $teacher_money }}
            </td>
            <td>
                餐次：{{ $days_data[$k1] }}
                <?php $total_days += $days_data[$k1]; ?>
            </td>
            <td>
                {{ round($money_data[$k1]) }}元 ({{ $money_data[$k1] }})
                <?php
                $total_money += $money_data[$k1];
                $total_money2 += round($money_data[$k1]);
                ?>
            </td>
            <td>

            </td>
        </tr>
        <?php $i++; ?>
    @endforeach
    <tr>
        <td>合計</td>
        <td></td>
        <td>
        </td>
        <td>{{ $total_days }}</td>
        <td>{{ $total_money2 }} 元<small>({{ $total_money }})</small></td>
        <td>
        </td>
    </tr>
</table>
