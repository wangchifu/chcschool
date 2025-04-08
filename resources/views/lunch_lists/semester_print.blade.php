<?php

 $date = explode('-',$lunch_setup->all_rece_date);
 $chy = $date[0]-1911;
 $num = $lunch_setup->semester . sprintf("%03s",$lunch_setup->all_rece_num);
echo "<body onload='window.print()'>";

$school_code = school_code();
$seal1 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal1.png');
$path = 'lunches&'.$lunch_setup->id.'&seal1.png';
if(file_exists($seal1)){
    $img1 = "<img src=". route('getImg',$path) ." width=\"140\">";
}else{
    $img1 = "";
}


$seal2 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal2.png');
$path = 'lunches&'.$lunch_setup->id.'&seal2.png';
if(file_exists($seal2)){
    $img2 = "<img src=". route('getImg',$path) ." width=\"140\">";
}else{
    $img2 = "";
}


$seal3 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal3.png');
$path = 'lunches&'.$lunch_setup->id.'&seal3.png';
if(file_exists($seal3)){
    $img3 = "<img src=". route('getImg',$path) ." width=\"140\">";
}else{
    $img3 = "";
}

$seal4 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal4.png');
$path = 'lunches&'.$lunch_setup->id.'&seal4.png';
if(file_exists($seal4)){
    $img4 = "<img src=". route('getImg',$path) ." width=\"140\">";
}else{
    $img4 = "";
}



$i=1;
$total_table="";
foreach($user_datas as $k1 => $v1){
    $table = "
              中華民國{$chy}年{$date[1]}月{$date[2]}日　　　　　　　　　　　　　　　　　　　　　　　　　　　{$lunch_setup->all_rece_no}第{$num}號
              <table cellPadding='0' width='800' border=1 cellSpacing='0' style='border-bottom-style:none;border-top-style:none;border-left-style:none;border-right-style:none;border-collapse:collapse;font-size:15pt'>";
    $count = 0;
    $table .= "
               <tr>
                <td align='center' width='250'>繳　款　人</td>
                <td colspan='2'>{$i}.{$user2name[$k1]}</td>
               </tr>
               <tr>
                <td align='center'>明細</td>
                <td colspan='2'>
                <table width='100%' cellSpacing='0' cellPadding='0' style='font-size:15px;'>";
                $total_money = 0;
                foreach($lunch_orders as $lunch_order){
                    if(!isset($v1[$lunch_order->name])) $v1[$lunch_order->name] = 0;
                    $money = $factory_money*$v1[$lunch_order->name];
                    $count += $v1[$lunch_order->name];
                    $table .= "
                <tr><td>餐期：{$lunch_order->name}</td><td>單價：{$factory_money} </td><td>訂餐數：{$v1[$lunch_order->name]}</td><td>小計：{$money}</td></tr>
                ";
                    $total_money += $factory_money * $v1[$lunch_order->name];
                }

                $total_money2 = round($total_money);

                $cht_monty = num2str($total_money2);
      $table .="
                </table>
                </td>
               </tr>
               <tr>
               <td align='center'>新　臺　幣</td><td>{$cht_monty}</td><td>$ {$total_money2} <small>({$total_money})</small></td>
               </tr>
               <tr>
               <td align='center'>事　　由</td>
               <td colspan='2'>{$lunch_setup->semester}學期 教職員午餐繳費</td>
               </tr>
               </table>
                <center>
               <span stype='font-size:20px'>承辦人　　　　　　　　　主辨出納　　　　　　　　　主辨會計　　　　　　　　　機關長官</span><br>
               <span>{$img1}　　　　{$img2}　　　　{$img3}　　　　{$img4}</span>
                </center>
               ";

    $total_table .= "<span style='font-weight:bold;font-size:20px;'>　　　　　　　　　　　　$lunch_setup->all_rece_name 收款收據 (收執聯)</span><br>".$table."<hr>";
    $total_table .= "<span style='font-weight:bold;font-size:20px;'>　　　　　　　　　　　　$lunch_setup->all_rece_name 收款收據 (報核聯)</span><br>".$table."<hr>";
    $total_table .= "<span style='font-weight:bold;font-size:20px;'>　　　　　　　　　　　　$lunch_setup->all_rece_name 收款收據 (存根聯)</span><br>".$table."<p style='page-break-after:always'></p>";



      $num++;
      $i++;

}
$total_table = substr($total_table,0,-39);
echo $total_table;

?>
