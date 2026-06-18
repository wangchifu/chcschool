<?php

//$url = "http://opendata.epa.gov.tw/ws/Data/AQI/?\$format=json";
//$url = "http://opendata.epa.gov.tw/webapi/Data/REWIQA/?\$orderby=SiteName&\$skip=0&\$top=1000&format=json";
//$url = "http://opendata.epa.gov.tw/api/v1/AQI?%24skip=0&%24top=1000&%24format=json";
//$url = "http://opendata2.epa.gov.tw/AQI.json";
//curl -X GET "https://data.epa.gov.tw/api/v2/aqx_p_432?api_key=ab9e1a2c-b503-4a4f-a369-b1b5a7b24938" -H "accept: */*"
@file_get_contents("https://chcschool.chc.edu.tw/chc_air/");
@file_get_contents("https://chcschool2.chc.edu.tw/chc_air/");

if(date('i')>10){
    $chk_file = date('YmdH0000');
}else{
    if(date('H') <> '00'){
        $last = sprintf('%02s',date('H')-1);
        $chk_file = date('Ymd').$last.'0000';
    }else{
        $chk_file = "nothing";
    }
    
}

if(file_exists('../../service/chc_air/download/'.$chk_file.'.txt')){
    $air_data = unserialize(file_get_contents('../../service/chc_air/download/'.$chk_file.'.txt'));
}elseif($chk_file=="nothing"){
    // 💡 修正點 1：前 10 分鐘不要直接給空陣列！先試著讀取上一個小時的檔案當備份，避免畫面全白
    $prev_hour = sprintf('%02s', date('H') - 1);
    $backup_file = date('Ymd') . $prev_hour . '0000';
    if(file_exists('../../service/chc_air/download/'.$backup_file.'.txt')){
        $air_data = unserialize(file_get_contents('../../service/chc_air/download/'.$backup_file.'.txt'));
    } else {
        $air_data = [];
    }
}else{
    $url = env('AIR_API_URL');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 💡 修正點 2：縮短超時時間！連線改 0.5 秒，總等待改 1.5 秒。API 只要超過 1.5 秒沒反應就立刻斷開，不讓使用者轉圈圈
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500); 
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1500);       
    $html = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($html);

    if(file_exists('../../service/chc_air/download/'.date('Ymd').'.txt')){
        $count = file_get_contents('../../service/chc_air/download/'.date('Ymd').'.txt');
    }else{
        $count = 0;
    }
    if(file_exists('../../service/chc_air/download/'.date('Ymd').'.txt')){
        $file_count = fopen('../../service/chc_air/download/'.date('Ymd').'.txt','w');    
        $count++;
        fwrite($file_count,$count);
        fclose($file_count);
    }
    
    if(!isset($data) || empty($data)){
        // 💡 修正點 3：萬一 API 真的斷線或超時沒回傳，立刻去抓今天隨便一個現有的快取檔案來頂替，絕對不給空陣列
        $files = glob('../../service/chc_air/download/' . date('Ymd') . '*.txt');
        if (!empty($files)) {
            $air_data = unserialize(file_get_contents(end($files)));
        } else {
            $air_data = [];
        }
    }else{
        foreach($data as $k=>$v){
            $select_data[$v->county][] = $v->sitename;
            $air_data[$v->sitename]['AQI'] = $v->aqi;
            $air_data[$v->sitename]['Status'] = $v->status;
            $air_data[$v->sitename]['PublishTime'] = $v->publishtime;
        }
        if(!isset($v->publishtime)){
            $fname = "no_publishtime";
            $air_data = [];
        }else{
            $fname = str_replace('/','',$v->publishtime);
        }        
        $fname = str_replace(' ','',$fname);
        $fname = str_replace(':','',$fname);
        $file = fopen('../../service/chc_air/download/'.$fname.'.txt','w');
        fwrite($file,serialize($air_data));
    }
}


$SiteName = $request->input('SiteName');

$options = "";
if(!isset($air_data[$SiteName]) and $SiteName != null){
    $SiteName = "彰化";
}
if(empty($_COOKIE['chc_air'])){
    $select_site = "彰化";
}else{
    $select_site = $_COOKIE['chc_air'];
    if($SiteName) $select_site = $SiteName;
}


setcookie("chc_air", $select_site, time()+31556926);


foreach($air_data as $k=>$v){
    $selected = ($k==$select_site)?"selected":"";
    $options .= "<option value='$k' $selected>$k</option>";
}

?>

<div class="container-fluid p-0">
    <div class="card border-0 rounded-0">
        <div class="card-body p-0">
            <div class="form-group mb-0">
                <select name="SiteName" id="SiteName" class="form-control custom-select rounded-0 shadow-sm font-weight-bold text-dark border-top-0 border-left-0 border-right-0">
                    <?php echo $options; ?>
                </select>
            </div>

            <div class="text-center py-3 bg-white">
                <p class="text-muted small mb-0 font-weight-bold">空氣品質指標 (AQI)</p>
                <h1 class="display-3 font-weight-bold mb-2 text-dark">
                    <?php
                        if(isset($air_data[$select_site]['AQI'])){
                            echo $air_data[$select_site]['AQI'];
                        } else {
                            echo '--';
                        }
                    ?>
                </h1>
                
                <?php
                    if(isset($air_data[$select_site]['AQI'])){
                        if($air_data[$select_site]['AQI'] <= 50){
                            $img = "50.jpg";
                        }
                        if($air_data[$select_site]['AQI'] >= 51 and $air_data[$select_site]['AQI'] <= 100){
                            $img = "100.jpg";
                        }
                        if($air_data[$select_site]['AQI'] >= 101 and $air_data[$select_site]['AQI'] <= 150){
                            $img = "150.jpg";
                        }
                        if($air_data[$select_site]['AQI'] >= 151 and $air_data[$select_site]['AQI'] <= 200){
                            $img = "200.jpg";
                        }
                        if($air_data[$select_site]['AQI'] >= 201){
                            $img = "300.jpg";
                        }
                    }else{
                        $img = "000.jpg";
                    }
                ?>
                <div class="w-100">
                    <img src="{{ asset('images/chc_air/'.$img) }}" class="w-100 d-block" alt="AQI 狀況圖">
                </div>
            </div>

            <div class="text-right text-muted pr-2 pb-2 bg-white">
                <small class="font-italic">
                    觀測發布時間：
                    <?php
                        if(isset($air_data[$select_site]['PublishTime'])){
                            echo $air_data[$select_site]['PublishTime'];
                        } else {
                            echo '暫無資料';
                        }
                    ?>
                </small>
            </div>
        </div>
    </div>
</div>

<script>
    $('#SiteName').change(
        function(){
            location="?SiteName=" + encodeURIComponent($('#SiteName').val());
        }
    );
</script>