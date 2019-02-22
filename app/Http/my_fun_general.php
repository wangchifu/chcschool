<?php
///////////////////1.功能性一般函式及檔案管理///////////////////////////////
//補足中文字數
if (! function_exists('mb_str_pad')) {
    function mb_str_pad(
        $input,
        $pad_length,
        $pad_string=" ",
        $pad_style=STR_PAD_RIGHT,
        $encoding="UTF-8")
    {
        return str_pad(
            $input,
            strlen($input)-mb_strlen($input,$encoding)+$pad_length,
            $pad_string,
            $pad_style);
    }
}

//公告模組中，顯示某目錄下的檔案，除了 title_image.png 不要顯示
if (! function_exists('get_files')) {
    function get_files($folder){
        $files = [];
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != ".." && $file != "title_image.png") {
                        //去除掉..跟.
                        array_push($files,$file);
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }
}

//顯示某目錄下的所有的檔案
if (! function_exists('get_folders_files')) {
    function get_folders_files($folder){
        $folders_files = [];
        $i=0;
        $k=0;
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($name = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($name != "." && $name != "..") {
                        //去除掉..跟.
                        if(is_dir($folder."/".$name)){
                            $folders_files['folders'][$i] = $name;
                            $i++;
                        }else{
                            $folders_files['files'][$k] = $name;
                            $k++;
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $folders_files;
    }
}

//轉為kb
if(! function_exists('filesizekb')) {
    function filesizekb($file)
    {
        return number_format(filesize($file) / pow(1024, 1), 2, '.', '');
    }
}