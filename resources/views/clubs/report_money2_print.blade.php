<?php
 $date = explode('-',date('Y-m-d'));
 $chy = $date[0]-1911;

echo "<body onload='window.print()'>";

$school_code = school_code();
$schools = config('chcschool.schools');
$school_name = $schools[$school_code];
$lunch_setup_id =(empty($lunch_setup))?1:$lunch_setup->id;
$seal1 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup_id.'/seal1.png');
$path = 'lunches&'.$lunch_setup_id.'&seal1.png';
if(file_exists($seal1)){
    $img1 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img1 = "";
}


$seal2 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup_id.'/seal2.png');
$path = 'lunches&'.$lunch_setup_id.'&seal2.png';
if(file_exists($seal2)){
    $img2 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img2 = "";
}


$seal3 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup_id.'/seal3.png');
$path = 'lunches&'.$lunch_setup_id.'&seal3.png';
if(file_exists($seal3)){
    $img3 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img3 = "";
}

$seal4 = storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup_id.'/seal4.png');
$path = 'lunches&'.$lunch_setup_id.'&seal4.png';
if(file_exists($seal4)){
    $img4 = "<img src=". route('getImg',$path) ." width=\"150\">";
}else{
    $img4 = "";
}



$i=1;
$total_table="";
$num = $semester."001";
foreach($open_clubs as $k=>$v){        
    if(isset($register_data[$v])){
        foreach($register_data[$v] as $k1=>$v1){        
            $cht_money = num2str($register_data[$v][$k1]);        
            $stu_class = (int)$students[$k1]['class'];        
            $table = "<table cellPadding='0' width='800' border=1 cellSpacing='0' style='border-bottom-style:none;border-top-style:none;border-left-style:none;border-right-style:none;border-collapse:collapse;font-size:18pt'>";
            $table .= "
            <tr>
                <td colspan='5' width='62.5%'>
                    中華民國{$chy}年{$date[1]}月{$date[2]}日
                </td>
                <td colspan='3'>
                    編號 {$num}
                </td>
            </tr>
            <tr>
                <td colspan='2' width='25%'>
                    繳款人
                </td>
                <td colspan='6'>
                    {$students[$k1]['year']}年{$stu_class}班{$students[$k1]['num']}號{$students[$k1]['name']}
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    款項用途
                </td>
                <td colspan='6'>
                第{$semester}學期 {$open_clubs_name[$v]} 社報名費
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    金額
                </td>
                <td colspan='6'>
                    新台幣 {$cht_money}
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    備註
                </td>
                <td colspan='6'>
                    請妥善保存，因故申請退費須繳回本收據
                </td>
            </tr>
        </table>
                    <p stype='font-size:20px'>經收人　　　　　　　　　主辨出納　　　　　　　　　主辨會計　　　　　　　　　機關長官</p>
                <p stype='font-size:20px'>　　　　　　　　　{$img2}　　　{$img3}　　　{$img4}</p>";
            $total_table .= "<h2 align='center'>{$school_name} 收款收據 (存根聯：送出納組)</h2>".$table."<hr>";
            $total_table .= "<h2 align='center'>{$school_name} 收款收據 (報核聯：送會計室)</h2>".$table."<hr>";
            $total_table .= "<h2 align='center'>{$school_name} 收款收據 (收據聯：交繳款人)</h2>".$table."<p style='page-break-after:always'></p>";
            $num++;
        }
    }    
}
$total_table = substr($total_table,0,-39);

echo $total_table;
?>
