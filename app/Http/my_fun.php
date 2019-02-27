<?php
//取登入的學校代碼
if (! function_exists('school_code')) {
    function school_code(){
        $database = config('app.database');
        $code = substr($database[$_SERVER['HTTP_HOST']],1,6);
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
