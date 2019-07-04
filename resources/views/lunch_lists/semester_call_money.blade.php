<?php
echo "<body onload='window.print()'>";
$i=1;
?>
<table cellspacing='1' cellpadding='0' bgcolor='#C6D7F2' border="1" width="100%" style="font-size: 20px;">
    <?php $all_money = 0; $all_days=0;?>
    <tr>
        <th>
            項目
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
    @foreach($user_datas as $k1=>$v1)
        <?php $total_days=0; ?>
        <tr bgcolor='#FFFFFF'  bgcolor='#FFFFFF'>
            <td>
                {{ $lunch_setup->semester }} 學期
                教師午餐收費通知
            </td>
            <td>
                {{ $i }} - {{ $k1 }}<br>
            </td>
            <td>
                單價：{{ $lunch_setup->teacher_money }}
            </td>
            <td>
                <?php
                    foreach($v1 as $k2=>$v2){
                        $total_days += $v2;
                    }
                ?>
                餐次：{{ $total_days }}
                <?php $all_days += $total_days; ?>
            </td>
            <td>
                {{ $lunch_setup->teacher_money*$total_days }}
                <?php
                $all_money+= $lunch_setup->teacher_money*$total_days;
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
        <td>{{ $all_days }}</td>
        <td>{{ $all_money }} 元</td>
        <td>
        </td>
    </tr>
</table>
