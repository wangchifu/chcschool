<?php
//取登入的學校代碼
if (! function_exists('school_code')) {
    function school_code(){
        $database = config('app.database');
        if(isset($_SERVER['HTTP_HOST'])) {
            $code = substr($database[$_SERVER['HTTP_HOST']], 1, 6);
        }else{
            $code = "";
        }
        return $code;
    }
}


if (! function_exists('get_files')) {
    function get_files($folder){
        $files = [];
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != "..") {
                        //去除掉..跟.
                        array_push($files,$file);
                    }
                }
                closedir($handle);
            }
        }
        sort($files);
        return $files;
    }
}

if (! function_exists('run_sql')) {
    function run_sql($file)
    {
        DB::unprepared(File::get($file));
    }
}

//刪除某目錄下的任何東西
if (! function_exists('delete_dir')) {
    function delete_dir($dir)
    {
        if (!file_exists($dir))
        {
            return true;
        }

        if (!is_dir($dir) || is_link($dir))
        {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item)
        {
            if ($item == '.' || $item == '..')
            {
                continue;
            }

            if (!delete_dir($dir . "/" . $item))
            {
                chmod($dir . "/" . $item, 0777);

                if (!delete_dir($dir . "/" . $item))
                {
                    return false;
                }
            }
        }

        return rmdir($dir);
    }
}

function get_module_setup(){
    $modules = \App\Module::where('active',1)->get();
    $module_setup = [];
    foreach($modules as $module){
        $module_setup[$module->name] = 1;
    }
    return $module_setup;
}

//轉為kb
if(! function_exists('filesizekb')) {
    function filesizekb($file)
    {
        return number_format(filesize($file) / pow(1024, 1), 2, '.', '');
    }
}

//查某日為中文星期幾
if(! function_exists('get_chinese_weekday')){
    function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }
}

//查指定日期為哪一個學期
if(! function_exists('get_date_semester')){
    function get_date_semester($date)
    {
        $d = explode('-',$date);
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
if(! function_exists('check_power')){
    function check_power($module,$type,$user_id)
    {
        $user_power = \App\UserPower::where('user_id',$user_id)
            ->where('name',$module)
            ->where('type',$type)
            ->first();
        if(empty($user_power)){
            return false;
        }else{
            return true;
        }
    }
}
