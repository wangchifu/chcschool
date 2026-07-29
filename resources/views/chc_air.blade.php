<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<?php

if(date('i')>10){
  $chk_file = date('YmdH0000');
}else{
  if(date('H') <> "00"){
    $last = sprintf("%02s",date('H')-1);
    $chk_file = date('Ymd').$last.'0000.txt';
  }else{
    $chk_file = "nothing.txt";
  }
}
$save_path = storage_path('app/privacy/chc_air/');

if(file_exists($save_path.$chk_file)){
  $air_data = unserialize(file_get_contents($save_path.$chk_file));
  
}elseif($chk_file=="nothing.txt"){
  $air_data = [];
 
}else{
    
  $url = env('AIR_API_URL');
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $html = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($html);
 

  if(file_exists($save_path.date('Ymd').".txt")){
    $count = file_get_contents($save_path.date('Ymd').".txt");
  }else{
    $count = 0;
  }
  $file_count = fopen($save_path.date('Ymd').".txt","w");
  $count++;
  fwrite($file_count,$count);
  fclose($file_count);
  if(!isset($data)){
    $data = [];    
    $air_data=[];
  }else{
    foreach($data as $k=>$v){      
      $air_data[$v->sitename]['AQI'] = $v->aqi;
      $air_data[$v->sitename]['Status'] = $v->status;
      $air_data[$v->sitename]['PublishTime'] = $v->publishtime;     
    }
    $fname = str_replace("/","",$v->publishtime);
    $fname = str_replace(" ","",$fname);
    $fname = str_replace(":","",$fname);
    $file = fopen($save_path.$fname.".txt","w");
    fwrite($file,serialize($air_data));
    fclose($file);    
  }
}
$SiteName = $_REQUEST['SiteName'] ?? null;

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

// 根據 AQI 計算背景主題配色與狀態標籤
$theme_color = "#6c757d"; // 預設灰色
$theme_bg = "#f8f9fa";
$status_label = "資料更新中";

if(isset($air_data[$select_site]['AQI'])){
    $aqi_num = (int)$air_data[$select_site]['AQI'];
    if($aqi_num <= 50){
        $img = "50.jpg";
        $theme_color = "#28a745";
        $theme_bg = "linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%)";
        $status_label = "空氣品質良好";
    } elseif($aqi_num <= 100){
        $img = "100.jpg";
        $theme_color = "#e6a100";
        $theme_bg = "linear-gradient(135deg, #fffde7 0%, #ffffff 100%)";
        $status_label = "普通";
    } elseif($aqi_num <= 150){
        $img = "150.jpg";
        $theme_color = "#fd7e14";
        $theme_bg = "linear-gradient(135deg, #fff3e0 0%, #ffffff 100%)";
        $status_label = "對敏感族群不健康";
    } elseif($aqi_num <= 200){
        $img = "200.jpg";
        $theme_color = "#dc3545";
        $theme_bg = "linear-gradient(135deg, #ffebee 0%, #ffffff 100%)";
        $status_label = "對所有族群不健康";
    } else {
        $img = "300.jpg";
        $theme_color = "#6f42c1";
        $theme_bg = "linear-gradient(135deg, #f3e5f5 0%, #ffffff 100%)";
        $status_label = "非常不健康 / 危害";
    }
}else{
    $img = "000.jpg";
}
?>

<!-- 全新設計風格樣式 -->
<style>
  .air-card {
    border-radius: 20px !important;
    overflow: hidden;
    background: <?php echo $theme_bg; ?>;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
  }
  .air-select-box {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    letter-spacing: 0.5px;
  }
  .aqi-num {
    font-size: 5rem;
    font-weight: 900;
    line-height: 1;
    color: <?php echo $theme_color; ?>;
    letter-spacing: -2px;
    text-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  .status-pill {
    display: inline-block;
    background-color: <?php echo $theme_color; ?>;
    color: #fff;
    font-weight: 600;
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
  }
  .full-bleed-img-container {
    width: 100%;
    margin-top: 1.5rem;
    padding: 0;
    line-height: 0;
  }
  .full-bleed-img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
  }
</style>

<div class="container-fluid p-0 my-2">
    <div class="card border-0 air-card">
        
        <!-- 上方：選單區 -->
        <div class="card-body pb-0 pt-4 px-4">
            <div class="form-group mb-4">
                <small class="text-uppercase font-weight-bold text-muted d-block mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">
                    LOCATION / 觀測站點
                </small>
                <select name="SiteName" id="SiteName" class="form-control custom-select air-select-box shadow-sm">
                    <?php echo $options; ?>
                </select>
            </div>

            <!-- 主數值呈現區 -->
            <div class="text-center my-3">
                <span class="text-uppercase font-weight-bold text-muted d-block mb-1" style="letter-spacing: 1.5px; font-size: 0.8rem;">
                    Air Quality Index
                </span>

                <div class="aqi-num my-2">
                    <?php
                        if(isset($air_data[$select_site]['AQI'])){
                            echo $air_data[$select_site]['AQI'];
                        } else {
                            echo '--';
                        }
                    ?>
                </div>

                <div class="mt-3 mb-2">
                    <span class="status-pill">
                        <?php echo $status_label; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- 中間：幾乎全版圖片區 (Full Bleed Image) -->
        <div class="full-bleed-img-container">
            <img src="{{ asset('images/chc_air')."/".$img }}" class="full-bleed-img" alt="AQI 指標圖">
        </div>

        <!-- 底部：時間戳記 -->
        <div class="card-footer bg-transparent border-0 text-center py-3">
            <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">
                <i class="far fa-clock mr-1"></i> 發布時間：
                <?php
                    if(isset($air_data[$select_site]['PublishTime'])){
                        echo $air_data[$select_site]['PublishTime'];
                    } else {
                        echo '無資料';
                    }
                ?>
            </span>
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