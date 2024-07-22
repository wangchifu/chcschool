<?php
    if(!empty($_GET['key_word'])){
		$key = $_GET['key_word'];
		$web = $_SERVER['HTTP_HOST'];
		header('Location: https://www.google.com.tw/search?q='.$key."+site:".$web);
    }else{
		echo "沒有輸入關鍵字";
	}
 ?>
