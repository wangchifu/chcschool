@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '借用系統-管理者 | ')

@section('content')
<?php

$active['index'] ="";
$active['my_list'] ="";
$active['admin'] ="active";
$active['list'] ="";

?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>管理者</h1>
            @include('lends.nav')
            <br>
            <h4>一、新增借用品的類別</h4>
            <form method="post" action="{{ route('lends.store_class') }}" id="store_class_form">
                @csrf                
                <div class="form-group">
                    <label for="create_class">1.類別名稱</label>
                    <input type="text" class="form-control" id="create_class" required name="name">                    
                </div>
                <div class="form-group">
                    <label for="lend_ps">2.注意事項</label>
                    <textarea class="form-control" id="lend_ps" rows="3" name="ps"></textarea>
                  </div>
                <button class="btn btn-success btn-sm" onclick="return confirm('確定儲存嗎？')">新增</button>
            </form>
            @include('layouts.errors')
            <hr>                    
            <table class="table table-bordered table-striped">                        
                <tr>
                    <th>名稱</th>       
                    <th>注意事項</th>                     
                    <th>動作</th>
                </tr>
            @foreach($lend_classes as $lend_class)                        
            <form action="{{ route('lends.update_class',$lend_class) }}" id="update_class_form{{ $lend_class->id }}" method="post" onsubmit="return false">
                @csrf
                <tr>
                    <td><input type="text" class ="form-control" name="name" value="{{ $lend_class->name }}"></td>                    
                    <td>
                        <div class="form-group">                                    
                            <div class="form-floating">
                                <textarea class="form-control" id="lend_ps" style="height: 100px" name="ps">{{ $lend_class->ps }}</textarea>                                
                            </div>
                        </div>
                    </td>                            
                    <td><button class="btn btn-primary btn-sm" onclick="sw_confirm2('確定更新嗎？','update_class_form{{ $lend_class->id }}')">更新</button> <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除嗎？相關借用記錄也會一起刪除喔！')">刪除</a></td>
                </tr>
            </form>
            @endforeach
            </table>
            <hr>
            <h4>二、新增借用品的項目</h4>
            <form method="post" action="{{ route('lends.store_item') }}" id="lend_item_form">
                @csrf
                <label class="form-label">1.借用品類別</label>
                <select class="form-control" aria-label="Default select example" required name="lend_class_id">
                    @foreach($lend_class_array as $k=>$v)
                        <option value="{{ $k }}">-- {{ $v }}</option>
                    @endforeach
                </select>
                <br>
                <div class="form-group">
                    <label for="lend_name" class="form-label">2.名稱</label>
                    <input type="text" class="form-control" id="lend_name" required name="name">
                </div>
                <div class="form-group">
                    <label for="lend_num" class="form-label">3.數量</label>
                    <input type="number" class="form-control" id="lend_num" required name="num">
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" checked name="enable">
                        <label class="form-check-label" for="flexCheckChecked">
                            4.打勾啟用
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="lend_ps" class="form-label">5.備註</label>
                    <div class="form-floating">
                        <textarea class="form-control" id="lend_ps" style="height: 100px" name="ps"></textarea>                        
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">6.可供借還的節數</label>
                    @foreach(config('chcschool.lend_sections') as $k=>$v)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $k }}" id="flexCheckChecked{{ $k }}" checked name="lend_sections[]">
                            <label class="form-check-label" for="flexCheckChecked{{ $k }}">
                                {{ $v }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <button class="btn btn-success btn-sm" onclick="return confirm('確定儲存嗎？')">新增</button>
            </form>
            @include('layouts.errors')
            <hr>
            <h4>三、已建置的項目</h4>
                    <label class="form-label">請選擇類別</label>
                        <select class="form-control" aria-label="Default select example" id="change_lend_class">
                            @foreach($lend_class_array as $k=>$v)
                                <?php
                                    $selected = ($lend_class_id==$k)?"selected":null;
                                ?>
                                <option value="{{ $k }}" {{ $selected }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    <hr>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-secondary text-light">
                            <th>名稱</th>
                            <th>數量</th>
                            <th>節次</th>
                            <th>動作</th>
                        </tr>
                    </thead> 
                    @foreach($lend_items as $lend_item)
                    <tr>
                        <td>
                            @if($lend_item->enable==1)
                            <i class="fas fa-check text-success"></i>
                            @else
                            <i class="fas fa-times-circle text-danger"></i>                                   
                            @endif
                            {{ $lend_item->name }}
                        </td>
                        <td>
                            {{  $lend_item->num }}
                        </td>
                        <td>
                            @foreach(unserialize($lend_item->lend_sections) as $k=>$v)
                                {{ $v }}
                            @endforeach
                        </td>
                        <td>
                            <span class="btn btn-primary btn-sm"><a href="{{ route('lends.admin_edit',$lend_item->id) }}" class="text-light">編輯</a></span>
                            <span class="btn btn-danger btn-sm"><a href="{{ route('lends.delete_item',$lend_item->id) }}" class="text-light" onclick="if(confirm('您確定刪除嗎?')) return true;else return false">刪除</a></span>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <script>
        $('#change_lend_class').on( "change", function() {            
            location="https://{{ $_SERVER['HTTP_HOST'] }}/lends/admin/" + $('#change_lend_class').val();
            });
    </script>
@endsection
