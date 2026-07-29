@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', 'DNS 網域設定 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                DNS 網域設定(測試中,請勿使用)
            </h1>                       
            <div class="container-fluid">
                <form method="post" action="/dns/zonemng" id="add1rrset">
                    <input type="hidden" name="op" id="op" value="add">

                    <!-- 記錄名稱 -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white">記錄名稱 *</span>
                        </div>
                        <input type="text" name="fqdn" id="fqdn" value="" maxlength="40" class="form-control" placeholder="主機名稱 ex:www, sfs3, @" pattern="[A-Za-z0-9.\-@_*]+" required>
                    </div>

                    <!-- 類型 -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text bg-warning text-dark">類  型 *</span>
                        </div>
                        <select name="type" class="form-control rrtype">
                        <option value="A" selected>A</option>
                        <option value="AAAA">AAAA</option>
                        <option value="CNAME">CNAME</option>
                        <option value="TXT">TXT</option>
                        <option value="MX">MX</option>
                        <option value="SRV">SRV</option>
                        <option value="CAA">CAA</option>
                        </select>
                    </div>

                    <!-- 有效時間 -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text bg-warning text-dark">有效時間 *</span>
                        </div>
                        <select name="ttl" class="form-control">
                        <option value="1">一秒</option>
                        <option value="3600">一小時</option>
                        <option value="10800">三小時</option>
                        <option value="36000">六小時</option>
                        <option value="43200">十二小時</option>
                        <option value="86400" selected>一天</option>
                        <option value="600000">一週</option>
                        <option value="2500000">一個月</option>
                        </select>
                    </div>

                    <!-- MX 序號 -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text bg-warning text-dark">MMX序號</span>
                        </div>
                        <select name="mxo" class="form-control mxo" disabled>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20" selected>20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="60">60</option>
                        </select>
                    </div>

                    <!-- 記錄資料 -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white">記錄資料 *</span>
                        </div>
                        <input type="text" name="rdata" id="rdata" value="" maxlength="250" class="form-control" placeholder="ex:163.17.40.31, host.example.com." required>
                    </div>

                    <!-- 備註說明 -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark">備註說明</span>
                        </div>
                        <input type="text" name="remark" value="" class="form-control" placeholder="記錄說明">
                    </div>

                    <!-- 按鈕區塊 -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-info mr-2" id="add_btn" style="width: 45%;">
                        ＋新增記錄
                        </button>
                        <button type="button" class="btn btn-warning" id="cancel_btn" onclick="window.close();">
                        取消
                        </button>
                    </div>
                </form>                                
            </div>            
        </div>
    </div>    
@endsection
