<?php
// (保留上述高效讀取邏輯)
$cache_file = __DIR__ . '/../../service/chc_air/download/air_latest.txt';
$air_data = file_exists($cache_file) ? unserialize(file_get_contents($cache_file)) : [];
$select_site = $_COOKIE['chc_air'] ?? '彰化';
$SiteName = $request->input('SiteName');
if ($SiteName) $select_site = $SiteName;
if (!isset($air_data[$select_site])) $select_site = "彰化";
setcookie("chc_air", $select_site, time() + 31556926);

// 決定顏色等級
$aqi = $air_data[$select_site]['AQI'] ?? 0;
if ($aqi <= 50) { $color = "badge-success"; $text = "良好"; }
elseif ($aqi <= 100) { $color = "badge-warning"; $text = "普通"; }
else { $color = "badge-danger"; $text = "不良"; }
?>

<div class="container mt-4">
    <div class="card shadow-sm" style="max-width: 400px; margin: auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">即時空氣品質指標</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>選擇監測站</label>
                <select class="form-control" id="SiteName">
                    <?php foreach($air_data as $k => $v): ?>
                        <option value="<?php echo $k; ?>" <?php echo ($k == $select_site) ? 'selected' : ''; ?>>
                            <?php echo $k; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center my-4">
                <h1 class="display-3 font-weight-bold"><?php echo $aqi; ?></h1>
                <span class="badge <?php echo $color; ?> p-2 px-3"><?php echo $text; ?></span>
            </div>

            <img src="{{ asset('images/chc_air/') }}/<?php echo ($aqi > 0) ? ($aqi > 200 ? '300.jpg' : (ceil($aqi/50)*50).'.jpg') : '000.jpg'; ?>" 
                 class="img-fluid rounded mb-3" alt="AQI Indicator">

            <div class="text-muted small">
                更新時間：<?php echo $air_data[$select_site]['PublishTime'] ?? '無資料'; ?>
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