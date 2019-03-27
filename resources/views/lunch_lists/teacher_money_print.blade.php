<?php

 $date = explode('-',$lunch_order->rece_date);
 $chy = $date[0]-1911;
 $num = $lunch_order->semester . sprintf("%03s",$lunch_order->rece_num);
echo "<body onload='window.print()'>";

$school_code = school_code();
$seal1 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal1.png');
$path = 'lunches&'.$lunch_setup->id.'&seal1.png';
if(file_exists($seal1)){
    $img1 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img1 = "";
}


$seal2 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal2.png');
$path = 'lunches&'.$lunch_setup->id.'&seal2.png';
if(file_exists($seal2)){
    $img2 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img2 = "";
}


$seal3 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal3.png');
$path = 'lunches&'.$lunch_setup->id.'&seal3.png';
if(file_exists($seal3)){
    $img3 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img3 = "";
}

$seal4 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id.'/seal4.png');
$path = 'lunches&'.$lunch_setup->id.'&seal4.png';
if(file_exists($seal4)){
    $img4 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img4 = "";
}



$i=1;
$total_table="";
foreach($user_datas as $k1 => $v1){
    $table = "
              中華民國{$chy}年{$date[1]}月{$date[2]}日　　　　　　　　　　　　　　　　　　　　　　　　　　　{$lunch_order->rece_no}第{$num}號
              <table cellPadding='0' width='800' border=1 cellSpacing='0' style='border-bottom-style:none;border-top-style:none;border-left-style:none;border-right-style:none;border-collapse:collapse;font-size:18pt'>";
    $count = 0;
    $table .= "
               <tr>
                <td align='center' width='250'>繳　款　人</td>
                <td colspan='2'>{$i}.{$k1}</td>
               </tr>
               <tr>
                <td align='center'>明細</td>
                <td colspan='2'>
                <table width='100%' cellSpacing='0' cellPadding='0'>";
                foreach($v1 as $k2 => $v2){
                $money = $factory_money[$k1]*$v2;
                $count += $v2;
                $table .= "
                <tr><td>餐期：{$k2}</td><td>訂餐數：{$v2}</td><td>小計：{$money}</td></tr>
                ";
                }
                $total_money = $factory_money[$k1] * $count;

                $total_money2 = round($total_money);

                $cht_monty = num2str($total_money2);
      $table .="
                </table>
                </td>
               </tr>
               <tr>
               <td align='center'>新　臺　幣</td><td>{$cht_monty}</td><td>$ {$total_money2}</td>
               </tr>
               <tr>
               <td align='center'>事　　由</td>
               <td colspan='2'>教職員午餐繳費</td>
               </tr>
               </table>
               <p stype='font-size:20px'>承辦人　　　　　　　　　主辨出納　　　　　　　　　主辨會計　　　　　　　　　機關長官</p>
               <p stype='font-size:20px'>{$img1}　　　{$img2}　　　{$img3}　　　{$img4}</p>
               ";

    $total_table .= "<h2 align='center'>$lunch_order->rece_name 收款收據 (收執聯)</h2>".$table."<hr>";
    $total_table .= "<h2 align='center'>$lunch_order->rece_name 收款收據 (報核聯)</h2>".$table."<hr>";
    $total_table .= "<h2 align='center'>$lunch_order->rece_name 收款收據 (存根聯)</h2>".$table."<p style='page-break-after:always'></p>";



      $num++;
      $i++;

}
$total_table = substr($total_table,0,-39);
echo $total_table;

function num2str($num){
    $string = "";
    $numc ="零,壹,貳,參,肆,伍,陸,柒,捌,玖";
    $unic = ",拾,佰,仟";
    $unic1 = "元整,萬,億,兆,京";
    $numc_arr = explode(",", $numc);
    $unic_arr = explode(",", $unic);
    $unic1_arr = explode(",", $unic1);
    $i = str_replace(",", "", $num);
    $c0 = 0;
    $str = array();
    do{
        $aa = 0;
        $c1 = 0;
        $s = "";
        $lan = (strlen($i) >= 4) ? 4 : strlen($i);
        $j = substr($i, -$lan);
        while($j > 0){
            $k = $j % 10;
            if($k > 0) {
                $aa = 1;
                $s = $numc_arr[$k].$unic_arr[$c1].$s;
            }elseif($k == 0) {
                if($aa == 1) $s = "0".$s;
            }
            $j = intval($j / 10);
            $c1 += 1;
        }
        $str[$c0] = ($s == '') ? '' : $s.$unic1_arr[$c0];
        $count_len = strlen($i) - 4;
        $i = ($count_len > 0) ? substr($i, 0, $count_len) : '';
        $c0 += 1;
    }while($i != '');
    foreach($str as $v) $string .= array_pop($str);
    $string = preg_replace('/0+/', '零', $string);
    return $string;
}
?>
