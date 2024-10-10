<?php
//取登入的學校代碼
if (!function_exists('school_code')) {
    function school_code()
    {
        $database = config('app.database');
        if (isset($_SERVER['HTTP_HOST'])) {
            $code = substr($database[$_SERVER['HTTP_HOST']], 1, 6);
        } else {
            $code = "";
        }
        return $code;
    }
}


if (!function_exists('get_files')) {
    function get_files($folder)
    {
        $files = [];
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != ".." && $file != ".DS_Store") {
                        //去除掉..跟.
                        array_push($files, $file);
                    }
                }
                closedir($handle);
            }
        }
        sort($files);
        return $files;
    }
}

//刪除某目錄所有檔案
if (!function_exists('del_folder')) {
    function del_folder($folder)
    {
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != "..") {
                        //去除掉..跟.
                        unlink($folder . '/' . $file);
                    }
                }
                closedir($handle);
            }
            rmdir($folder);
        }
    }
}


if (!function_exists('run_sql')) {
    function run_sql($file)
    {
        DB::unprepared(File::get($file));
    }
}

//刪除某目錄下的任何東西
if (!function_exists('delete_dir')) {
    function delete_dir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir) || is_link($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!delete_dir($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);

                if (!delete_dir($dir . "/" . $item)) {
                    return false;
                }
            }
        }

        return rmdir($dir);
    }
}

function get_module_setup()
{
    $modules = \App\Module::where('active', 1)->get();
    $module_setup = [];
    foreach ($modules as $module) {
        $module_setup[$module->name] = 1;
    }
    return $module_setup;
}

//轉為kb
if (!function_exists('filesizekb')) {
    function filesizekb($file)
    {
        return number_format(filesize($file) / pow(1024, 1), 2, '.', '');
    }
}

function get_dir_size($f)
{
    $io = popen('/usr/bin/du -sk ' . $f, 'r');
    $size = fgets($io, 4096);
    $size = substr($size, 0, strpos($size, "\t"));
    pclose($io);
    if (!$size) $size = 0;
    return $size;
}

//查某日為中文星期幾
if (!function_exists('get_chinese_weekday')) {
    function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }
}

//查某日為中文星期幾
if (!function_exists('get_chinese_weekday2')) {
    function get_chinese_weekday2($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        return ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }
}

//查指定日期為哪一個學期
if (!function_exists('get_date_semester')) {
    function get_date_semester($date)
    {
        $d = explode('-', $date);
        //查目前學期
        $y = (int)$d[0] - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array($d[1], $array1)) {
            if ($d[1] == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $this_semester = $y . "1";
            }
        } else {
            $this_semester = ($y - 1) . "2";
        }

        return $this_semester;
    }
}

//查指定日期為哪一個學期
if (!function_exists('check_power')) {
    function check_power($module, $type, $user_id)
    {
        $user_power = \App\UserPower::where('user_id', $user_id)
            ->where('name', $module)
            ->where('type', $type)
            ->first();
        if (empty($user_power)) {
            return false;
        } else {
            return true;
        }
    }
}

//查某日為中文星期幾
if (!function_exists('get_chinese_weekday')) {
    function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }
}

//秀某學期的每一天
if (!function_exists('get_semester_dates')) {
    function get_semester_dates($semester)
    {
        $this_year = substr($semester, 0, 3) + 1911;
        $this_seme = substr($semester, -1, 1);
        $next_year = $this_year + 1;
        if ($this_seme == 1) {
            $month_array = ["八月" => $this_year . "-08", "九月" => $this_year . "-09", "十月" => $this_year . "-10", "十一月" => $this_year . "-11", "十二月" => $this_year . "-12", "一月" => $next_year . "-01"];
        } else {
            $month_array = ["二月" => $next_year . "-02", "三月" => $next_year . "-03", "四月" => $next_year . "-04", "五月" => $next_year . "-05", "六月" => $next_year . "-06"];
        }


        foreach ($month_array as $k => $v) {
            $semester_dates[$k] = get_month_date($v);
        }
        return $semester_dates;
    }
}

if (!function_exists('get_month_date')) {
    //秀某年某月的每一天
    function get_month_date($year_month)
    {
        $this_date = explode("-", $year_month);
        $days = array("01" => "31", "02" => "28", "03" => "31", "04" => "30", "05" => "31", "06" => "30", "07" => "31", "08" => "31", "09" => "30", "10" => "31", "11" => "30", "12" => "31");
        //潤年的話，二月29天
        if (checkdate(2, 29, $this_date[0])) {
            $days['02'] = 29;
        } else {
            $days['02'] = 28;
        }

        for ($i = 1; $i <= $days[$this_date[1]]; $i++) {
            $order_date[$i] = $this_date[0] . "-" . $this_date[1] . "-" . sprintf("%02s", $i);
        }
        return $order_date;
    }
}

//查某日星期幾
if (!function_exists('get_date_w')) {
    function get_date_w($d)
    {
        $arrDate = explode("-", $d);
        $week = date("w", mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));
        return $week;
    }
}


if (!function_exists('get_user_name')) {
    function get_user_name()
    {
        $user_name = \App\User::where('disable', null)
            ->where('username', '<>', 'admin')
            ->pluck('name', 'id')
            ->toArray();
        return $user_name;
    }
}

function num2str($num)
{
    $string = "";
    $numc = "零,壹,貳,參,肆,伍,陸,柒,捌,玖";
    $unic = ",拾,佰,仟";
    $unic1 = "元整,萬,億,兆,京";
    $numc_arr = explode(",", $numc);
    $unic_arr = explode(",", $unic);
    $unic1_arr = explode(",", $unic1);
    $i = str_replace(",", "", $num);
    $c0 = 0;
    $str = array();
    do {
        $aa = 0;
        $c1 = 0;
        $s = "";
        $lan = (strlen($i) >= 4) ? 4 : strlen($i);
        $j = substr($i, -$lan);
        while ($j > 0) {
            $k = $j % 10;
            if ($k > 0) {
                $aa = 1;
                $s = $numc_arr[$k] . $unic_arr[$c1] . $s;
            } elseif ($k == 0) {
                if ($aa == 1) $s = "0" . $s;
            }
            $j = intval($j / 10);
            $c1 += 1;
        }
        $str[$c0] = ($s == '') ? '' : $s . $unic1_arr[$c0];
        $count_len = strlen($i) - 4;
        $i = ($count_len > 0) ? substr($i, 0, $count_len) : '';
        $c0 += 1;
    } while ($i != '');
    foreach ($str as $v) $string .= array_pop($str);
    $string = preg_replace('/0+/', '零', $string);
    return $string;
}

//輸出樹狀目錄
function get_tree($trees, $i)
{
    foreach ($trees as $tree) {
        if ($tree->type == 1) {
            for ($k = 0; $k < $i; $k++) {
                echo "　";
            }
            echo "<i class=\"fas fa-folder-open text-warning\"></i>" . $tree->name ." <small>(排序：".$tree->order_by.")</small>". " <a href=\"javascript:open_window('" . route('trees.edit', $tree->id) . "','新視窗')\"><i class='fas fa-edit'></i></a> <a href=\"" . route('trees.delete', $tree->id) . "\" onclick=\"return confirm('連同底下連結一起刪喔！？')\"><i class=\"fas fa-times-circle text-danger\"></i></a><br>";
            $links = \App\Tree::where('folder_id', $tree->id)
                ->orderBy('type')
                ->orderBy('order_by')
                ->orderBy('name')
                ->get();
            if ($links->count() > 0) {
                get_tree($links, $i + 1);
            }
        } elseif ($tree->type == 2) {
            for ($k = 0; $k < $i; $k++) {
                echo "　";
            }
            echo "<i class=\"fas fa-file\"></i> <a href=\"" . $tree->url . "\" target=\"_blank\">" . $tree->name . " <small>(排序：".$tree->order_by.")</small></a> <a href=\"javascript:open_window('" . route('trees.edit', $tree->id) . "','新視窗')\"><i class='fas fa-edit'></i></a> <a href=\"" . route('trees.delete', $tree->id) . "\" onclick=\"return confirm('確定刪除嗎？')\"><i class=\"fas fa-times-circle text-danger\"></i></a><br>";
        }
    }
}

if (!function_exists('get_url')) {
    function get_url($url)
    {
        $data = [];
        if (function_exists('curl_init')) {
            $ch = curl_init();
            $timeout1 = 3;
            $timeout2 = 3;
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout2);
            $data = curl_exec($ch);
            curl_close($ch);
        } elseif (function_exists('file_get_contents')) {
            $arrContextOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'timeout' => 10,
                ],
            ];
            $data = file_get_contents($url, false, stream_context_create($arrContextOptions));
        } else {
            // die('fopen');
            $handle = fopen($url, 'rb');
            $data = stream_get_contents($handle);
            fclose($handle);
        }

        return $data;
    }
}

//發email
if (!function_exists('send_mail')) {
    function send_mail($to, $subject, $body)
    {
        $data = array("subject" => $subject, "body" => $body, "receipt" => "{$to}");
        $data_string = json_encode($data);
        $ch = curl_init('https://school.chc.edu.tw/api/mail');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'AUTHKEY: #' . env('MAIL_AUTH') . '#'
            )
        );
        $result = curl_exec($ch);
        $obj = json_decode($result, true);
        if ($obj["success"] == true) {
            //echo "<body onload=alert('已mail通知')>";
        };
    }
}


//隨機亂數
if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
function GetIP()
{
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
        $cip = $_SERVER["REMOTE_ADDR"];
    } else {
        $cip = "無法取得IP位址！";
    }
    return $cip;
}

function check_ip()
{
    $my_ip = GetIP();
    $local_ip1 = "::1";
    $local_ip2 = "127.0.0.1";
    $setup = \App\Setup::first();
    $r_ip1 = explode('.', $setup->ip1);
    $r_ip2 = explode('.', $setup->ip2);
    $ipv6_check = substr($setup->ipv6, 0, 13);
    if (empty($setup->ip1) or $setup->ip2) {
        $my_ip_array = explode('.', $my_ip);
        if ($my_ip == $local_ip1) {
            return true;
        } elseif ($my_ip == $local_ip2) {
            return true;
        } elseif ($my_ip_array[0] == $r_ip1[0] and $my_ip_array[1] == $r_ip1[1] and $my_ip_array[2] == $r_ip1[2] and $my_ip_array[3] >= $r_ip1[3] and $my_ip_array[3] <= $r_ip2[3]) {
            return true;
        } elseif ($ipv6_check == substr($my_ip, 0, 13)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function substr_cut_name($user_name){
	//获取字符串长度
	$strlen = mb_strlen($user_name, 'utf-8');
	//如果字符创长度小于2，不做任何处理
	if($strlen<2){
		return $user_name;
	}else{
		//mb_substr — 获取字符串的部分
		$firstStr = mb_substr($user_name, 0, 1, 'utf-8');
		$lastStr = mb_substr($user_name, -1, 1, 'utf-8');
		//str_repeat — 重复一个字符串
		return $strlen == 2 ? $firstStr . str_repeat('〇', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("〇", $strlen - 2) . $lastStr;
	}
}

function line_notify($token,$string){
    $headers = array(
        'Content-Type: multipart/form-data',
        'Authorization:Bearer '.$token
    );
    $message = array(
        'message' => $string
    );
    $ch = curl_init();
    curl_setopt($ch , CURLOPT_URL , "https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    $result = curl_exec($ch);
    curl_close($ch);
}

function line_bot($group_id,$token,$string){       
    $message=array(
        "to"=>$group_id,
        "messages"=>
            [
                [			
                "type"=>"text",	
                "text"=>$string,
                ]
            ]
                );

    $ch = curl_init();
    curl_setopt($ch , CURLOPT_URL , "https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
}
