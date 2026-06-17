<?php
// --- 優化後的讀取與更新邏輯 ---
$cache_file = '../../service/chc_air/download/latest_air_data.txt';
$air_data = [];

// 1. 優先從本機快取讀取，不執行任何網路請求 (速度最快)
if (file_exists($cache_file)) {
    $air_data = unserialize(file_get_contents($cache_file));
}

// 2. 判斷是否需要更新：若快取不存在，或檔案已超過 1 小時，才執行 API 抓取
if (empty($air_data) || (filemtime($cache_file) < (time() - 3600))) {
    $url = env('AIR_API_URL');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // 限制連線時間，避免卡死
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);       // 限制總執行時間
    $html = curl_exec($ch);
    $data = json_decode($html);
    curl_close($ch);

    if ($data) {
        $temp_data = [];
        foreach($data as $v){
            $temp_data[$v->sitename]['AQI'] = $v->aqi;
            $temp_data[$v->sitename]['Status'] = $v->status;
            $temp_data[$v->sitename]['PublishTime'] = $v->publishtime;
        }
        $air_data = $temp_data;
        file_put_contents($cache_file, serialize($air_data)); // 更新快取
    }
}

// --- 以下維持你原有的顯示邏輯 ---
$SiteName = $request->input('SiteName');
if(!isset($air_data[$SiteName]) and $SiteName != null){ $SiteName = "彰化"; }

if(empty($_COOKIE['chc_air'])){
    $select_site = "彰化";
}else{
    $select_site = $_COOKIE['chc_air'];
    if($SiteName) $select_site = $SiteName;
}
setcookie("chc_air", $select_site, time()+31556926);

$options = "";
foreach($air_data as $k => $v){
    $selected = ($k == $select_site) ? "selected" : "";
    $options .= "<option value='$k' $selected>$k</option>";
}
?>

<select name="SiteName" id="SiteName">
    <?php echo $options; ?>
</select>
<small>AQI：<?php echo $air_data[$select_site]['AQI'] ?? '暫無資料'; ?></small>
<br>
<?php
    $aqi = $air_data[$select_site]['AQI'] ?? 0;
    if ($aqi <= 50) $img = "50.jpg";
    elseif ($aqi <= 100) $img = "100.jpg";
    elseif ($aqi <= 150) $img = "150.jpg";
    elseif ($aqi <= 200) $img = "200.jpg";
    elseif ($aqi > 200) $img = "300.jpg";
    else $img = "000.jpg";
?>
<img src="{{ asset('images/chc_air/'.$img) }}" width="100%">
<br>
<?php echo $air_data[$select_site]['PublishTime'] ?? ''; ?>

<script>
    $('#SiteName').change(function(){ location="?SiteName=" + $('#SiteName').val(); });
</script>