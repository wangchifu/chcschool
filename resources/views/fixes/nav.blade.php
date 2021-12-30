<?php
if($situation==1){
    $btn1 = "btn-success";
    $btn2 ="btn-outline-warning";
    $btn3= "btn-outline-danger";
}
if($situation==2){
    $btn1 = "btn-outline-success";
    $btn2 ="btn-warning";
    $btn3= "btn-outline-danger";
}
if($situation==3){
    $btn1 = "btn-outline-success";
    $btn2 ="btn-outline-warning";
    $btn3= "btn-danger";
}
if($situation==null){
    $btn1 = "btn-outline-success";
    $btn2 ="btn-outline-warning";
    $btn3= "btn-outline-danger";
}
?>
<a href="{{ route('fixes.search',1) }}" class="btn {{ $btn1 }} btn-sm"><i class="fas fa-check-square"></i> 處理完畢列表</a>
<a href="{{ route('fixes.search',2) }}" class="btn {{ $btn2 }} btn-sm"><i class="fas fa-exclamation-triangle"></i> 處理中列表</a>
<a href="{{ route('fixes.search',3) }}" class="btn {{ $btn3 }} btn-sm"><i class="fas fa-phone-square"></i> 申報中列表</a>
