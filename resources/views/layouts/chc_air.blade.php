<?php
// --- 1. 高效讀取邏輯 ---
// 請確認此路徑在你的伺服器上是真實存在的，且 PHP 有權限讀取/寫入
$cache_dir = __DIR__ . '/../../service/chc_air/download/';
$cache_file = $cache_dir . 'air_latest.txt';

$air_data = [];
if (file_exists($cache_file)) {
    $air_data = unserialize(file_get_contents($cache_file));
}

// 若快取不存在或已過期 (1小時)，則更新
if (empty($air_data) || (filemtime($cache_file) < (time() - 3600))) {
    $url = env('AIR_API_URL');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $html = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($html);

    if ($data) {
        foreach($data as $v){
            $air_data[$v->sitename] = [
                'AQI' => $v->aqi,
                'Status' => $v->status,
                'PublishTime' => $v->publishtime
            ];
        }
        file_put_contents($cache_file, serialize($air_data));
    }
}

// --- 2. 變數處理 ---
$SiteName = isset($_GET['SiteName']) ? $_GET['SiteName'] : null;
$select_site = isset($_COOKIE['chc_air']) ? $_COOKIE['chc_air'] : '彰化';
if ($SiteName) $select_site = $SiteName;
if (!isset($air_data[$select_site])) $select_site = "彰化";

setcookie("chc_air", $select_site, time()+31556926);

// --- 3. UI 顯示 (Bootstrap 4) ---
?>
<div class="container mt-3" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-header bg-info text-white">空氣品質指標</div>
        <div class="card-body">
            <select class="form-control mb-3" id="SiteName">
                <?php foreach($air_data as $k => $v): ?>
                    <option value="<?php echo $k; ?>" <?php echo ($k == $select_site) ? 'selected' : ''; ?>>
                        <?php echo $k; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="text-center">
                <h2 class="display-4"><?php echo $air_data[$select_site]['AQI'] ?? '--'; ?></h2>
                <?php
                    $aqi = $air_data[$select_site]['AQI'] ?? 0;
                    $img = ($aqi > 200) ? "300.jpg" : (ceil($aqi/50)*50).".jpg";
                    if($aqi == 0) $img = "000.jpg";
                ?>
                <img src="{{ asset('images/chc_air/'.$img) }}" class="img-fluid my-2" style="max-height: 200px;">
                <p class="text-muted small">更新時間：<?php echo $air_data[$select_site]['PublishTime'] ?? '無資料'; ?></p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#SiteName').change(function(){
        location="?SiteName=" + $(this).val();
    });
</script>