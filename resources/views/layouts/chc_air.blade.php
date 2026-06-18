<?php
// 1. 使用 Laravel 官方推薦的 base_path() 定義絕對路徑，徹底解決找不到目錄的問題
$cache_dir = base_path('service/chc_air/download');
$cache_file = $cache_dir . '/air_latest.txt';

// 自動建立目錄（防呆：若目錄不存在就自動建立，避免再次報錯）
if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

// 2. 讀取快取
$air_data = [];
if (file_exists($cache_file)) {
    $air_data = unserialize(file_get_contents($cache_file));
}

// 3. 若沒有快取或快取超過 1 小時，則向 API 抓取新資料
if (empty($air_data) || (filemtime($cache_file) < (time() - 3600))) {
    $url = env('AIR_API_URL');
    
    if ($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $html = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($html);

        if ($data) {
            $temp_data = [];
            foreach($data as $v){
                $temp_data[$v->sitename] = [
                    'AQI' => $v->aqi,
                    'Status' => $v->status,
                    'PublishTime' => $v->publishtime
                ];
            }
            $air_data = $temp_data;
            file_put_contents($cache_file, serialize($air_data));
        }
    }
}

// 4. 變數防呆處理，確保後續畫面不會因為沒資料而報錯
if (empty($air_data)) {
    $air_data = ['彰化' => ['AQI' => '--', 'Status' => '暫無資料', 'PublishTime' => '尚未取得時間']];
}

// 5. 處理選單與 Cookie 邏輯 (Laravel 推薦使用 request() 輔助函式)
$SiteName = request()->input('SiteName');
$select_site = $_COOKIE['chc_air'] ?? '彰化';
if ($SiteName) $select_site = $SiteName;
if (!isset($air_data[$select_site])) $select_site = array_key_first($air_data);

setcookie("chc_air", $select_site, time() + 31556926, "/");

// 6. 決定 AQI 狀態與顏色
$aqi = $air_data[$select_site]['AQI'];
if ($aqi === '--' || $aqi <= 0) { $color = "badge-secondary"; $text = "未知"; }
elseif ($aqi <= 50) { $color = "badge-success"; $text = "良好"; }
elseif ($aqi <= 100) { $color = "badge-warning"; $text = "普通"; }
else { $color = "badge-danger"; $text = "不良"; }
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="card shadow-sm mx-auto" style="max-width: 400px;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold">彰化空品監測</h5>
            <span class="badge badge-light">即時更新</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="SiteName" class="font-weight-bold text-muted">切換監測站：</label>
                <select class="form-control" id="SiteName">
                    <?php foreach($air_data as $k => $v): ?>
                        <option value="<?php echo $k; ?>" <?php echo ($k == $select_site) ? 'selected' : ''; ?>>
                            <?php echo $k; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center my-4">
                <div class="text-muted small">空氣品質指標 (AQI)</div>
                <h1 class="display-2 font-weight-bold mb-1 text-dark"><?php echo $aqi; ?></h1>
                <span class="badge <?php echo $color; ?> p-2 px-4 shadow-sm" style="font-size: 1rem;">
                    <?php echo $text; ?>
                </span>
            </div>

            <div class="text-center mb-3">
                <?php
                    if ($aqi === '--' || $aqi <= 0) $img = "000.jpg";
                    elseif ($aqi > 200) $img = "300.jpg";
                    else $img = (ceil($aqi / 50) * 50) . ".jpg";
                ?>
                <img src="{{ asset('images/chc_air/'.$img) }}" class="img-fluid rounded border" alt="AQI Indicator" style="width: 100%;">
            </div>

            <div class="text-right text-muted x-small">
                <i class="far fa-clock"></i> 觀測時間：<?php echo $air_data[$select_site]['PublishTime']; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#SiteName').change(function(){
        location="?SiteName=" + encodeURIComponent($(this).val());
    });
</script>