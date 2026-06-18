<?php
// 1. 強制顯示錯誤，若有問題會直接顯示在頁面上供你除錯
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. 絕對路徑定義 (請確認此目錄在伺服器上真實存在)
$cache_file = realpath(__DIR__ . '/../../service/chc_air/download/') . '/latest_air.txt';

// 3. 讀取快取
$air_data = [];
if (file_exists($cache_file)) {
    $air_data = unserialize(file_get_contents($cache_file));
}

// 4. 若沒有資料或資料太舊 (超過 1 小時)，則嘗試從 API 更新
if (empty($air_data) || (filemtime($cache_file) < (time() - 3600))) {
    $ch = curl_init(env('AIR_API_URL'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $json = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($json);
    if ($data) {
        $air_data = [];
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

// 5. 確保 $air_data 不為空，避免下拉選單錯誤
if (empty($air_data)) $air_data = ['彰化' => ['AQI' => '--', 'Status' => '無', 'PublishTime' => '暫無資料']];

// 處理選單邏輯
$select_site = $_COOKIE['chc_air'] ?? '彰化';
if (!empty($_GET['SiteName'])) $select_site = $_GET['SiteName'];
if (!isset($air_data[$select_site])) $select_site = array_key_first($air_data);
setcookie("chc_air", $select_site, time() + 31556926);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<div class="container mt-3" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">空氣品質指標</div>
        <div class="card-body">
            <select class="form-control" id="SiteName">
                <?php foreach($air_data as $k => $v): ?>
                    <option value="<?php echo $k; ?>" <?php echo ($k == $select_site) ? 'selected' : ''; ?>>
                        <?php echo $k; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="text-center my-3">
                <h1 class="display-4"><?php echo $air_data[$select_site]['AQI']; ?></h1>
                <p><?php echo $air_data[$select_site]['Status']; ?></p>
            </div>
            <p class="small text-muted">更新：<?php echo $air_data[$select_site]['PublishTime']; ?></p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#SiteName').change(function(){ location="?SiteName=" + $(this).val(); });
</script>