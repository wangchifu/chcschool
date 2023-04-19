<?php

//$url = "http://opendata.epa.gov.tw/ws/Data/AQI/?\$format=json";
//$url = "http://opendata.epa.gov.tw/webapi/Data/REWIQA/?\$orderby=SiteName&\$skip=0&\$top=1000&format=json";
//$url = "http://opendata.epa.gov.tw/api/v1/AQI?%24skip=0&%24top=1000&%24format=json";
//$url = "http://opendata2.epa.gov.tw/AQI.json";
//curl -X GET "https://data.epa.gov.tw/api/v2/aqx_p_432?api_key=ab9e1a2c-b503-4a4f-a369-b1b5a7b24938" -H "accept: */*"

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
    $air_data = [];
}else{
    $url = "https://data.epa.gov.tw/api/v2/aqx_p_432?api_key=".env('AIR_API_KEY');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $html = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($html);

    if(file_exists('../../service/chc_air/download/'.date('Ymd').'.txt')){
        $count = file_get_contents('../../service/chc_air/download/'.date('Ymd').'.txt');
    }else{
        $count = 0;
    }
    $file_count = fopen('../../service/chc_air/download/'.date('Ymd').'.txt','w');
    $count++;
    fwrite($file_count,$count);
    fclose($file_count);

    if(!isset($data->records)){
        $data = [];
        //$select_data=[];
        $air_data=[];
    }else{
        foreach($data->records as $k=>$v){
            $select_data[$v->county][] = $v->sitename;
            $air_data[$v->sitename]['AQI'] = $v->aqi;
            $air_data[$v->sitename]['Status'] = $v->status;
            $air_data[$v->sitename]['PublishTime'] = $v->publishtime;
        }
        if(!isset($v->publishtime)){
            $fname = "no_publishtime";
            //$air_data = [];
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
