<?php

//$url = "http://opendata.epa.gov.tw/ws/Data/AQI/?\$format=json";
//$url = "http://opendata.epa.gov.tw/webapi/Data/REWIQA/?\$orderby=SiteName&\$skip=0&\$top=1000&format=json";
//$url = "http://opendata.epa.gov.tw/api/v1/AQI?%24skip=0&%24top=1000&%24format=json";
$url = "http://opendata2.epa.gov.tw/AQI.json";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
curl_close($ch);
$data = json_decode($html);
if(is_null($data)){
    $data = [];
    $select_data=[];
}

foreach($data as $k=>$v){
    $select_data[$v->County][] = $v->SiteName;
    $air_data[$v->SiteName]['AQI'] = $v->AQI;
    $air_data[$v->SiteName]['Status'] = $v->Status;
    $air_data[$v->SiteName]['PublishTime'] = $v->PublishTime;
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


foreach($select_data as $k=>$v){
    foreach($v as $k2=>$v2){
        $selected = ($v2==$select_site)?"selected":"";
        $options .= "<option value='$v2' $selected>$v2</option>";
    }
}

?>
<select name="SiteName" id="SiteName">
    <?php echo $options; ?>
</select>
<small>AQI：
    <?php
        if(isset($air_data[$select_site]['AQI'])){
            echo $air_data[$select_site]['AQI'];
        }

    ?>
</small>
<br>
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
<img src="{{ asset('images/chc_air/'.$img) }}" width="100%">
<?php
    if(isset($air_data[$select_site]['PublishTime'])){
        echo $air_data[$select_site]['PublishTime'];
    }
?>
<script>
    $('#SiteName').change(
        function(){
            location="?SiteName=" +$('#SiteName').val() ;
        }
    );
</script>
