@extends('layouts.master')

@section('title','教職員登入 | ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>教職員GSuite登入</h4></div>

            <div class="card-body">
                <a href="https://gsuite.chc.edu.tw" target="_blank"><img src="{{ asset('images/gsuite_logo.png') }}" alt="彰化gsuite的logo"></a>
                @if(session('login_error') < 3)
                <form id="this_form" method="POST" action="{{ route('gauth') }}" onsubmit="change_button()">
                    @csrf
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">教職員帳號</label>
                        <div class="input-group col-md-6">
                            <input tabindex="1" id="username" type="text" class="form-control" name="username" required autofocus aria-label="Recipient's username" aria-describedby="basic-addon2" placeholder="教育處 Gsuite 帳號">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">@chc.edu.tw</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">密碼</label>

                        <div class="col-md-6">
                            <input tabindex="2" id="password" type="password" class="form-control" name="password" required placeholder="OpenID 密碼">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4 text-md-left">
                        </div>
                        <div class="col-md-6 text-md-left">
                            <a href="{{ route('login') }}"><img src="{{ route('pic') }}" class="img-fluid" alt="驗證碼圖片"></a>                                                        
                            <a href="#!" id="loadAudio"><i class="fas fa-volume-up"></i> [語音播放]</a>
                            <audio id="myAudio">
                                <source src="" type="audio/mp3">                                
                            </audio>   
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="chaptcha" class="col-md-4 col-form-label text-md-right">驗證碼</label>                        
                        <div class="col-md-6">
                            <input tabindex="3" id="chaptcha" type="text" class="form-control" name="chaptcha" required placeholder="上圖國字轉阿拉伯數字" maxlength="5" title="請輸入驗證碼">                            
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button tabindex="4" type="submit" class="btn btn-primary btn-sm" id="submit_button">
                                <i class="fas fa-sign-in-alt"></i> 登入
                            </button>
                            <div class="text-right">
                                <a href="{{ route('admin_login') }}"><i class="fas fa-cog"></i> 使用本機帳號</a>
                            </div>
                        </div>
                    </div>
                </form>
                @else
                    <?php
                    $k = rand(100,999);
                    session(['check_bot'=>$k]);
                    ?>
                    <span class="text-danger">登入錯誤超過三次，請輸入三碼數字後送出： </span>
                    <form action="{{ route('not_bot') }}" method="post">
                        @csrf
                        <input type="text" name="check_bot" placeholder="請輸入：{{ session('check_bot') }}">
                        <button class="btn btn-primary btn-sm">我不是機器人</button>
                    </form>
                @endif
                @include('layouts.errors')
            </div>
        </div>
    </div>
</div>
<script>
    //var validator = $("#this_form").validate();

    function change_button(){
        $("#submit_button").attr('disabled','disabled');
        $("#submit_button").addClass('disabled');
    }

    $(document).ready(function () {
            $('#loadAudio').click(function () {
                // 發送 AJAX 請求到 PHP 文件
                $.ajax({
                    url: '{{ route('voice') }}', // PHP 文件路徑
                    type: 'GET',
                    success: function (response) {
                        if (response.startsWith('Error')) {
                            alert(response); // 錯誤提示
                        } else {
                            // 動態設置 <audio> 的 src
                            const base64Src = `data:audio/mpeg;base64,${response}`;
                            $('#myAudio source').attr('src', base64Src);
                            
                            // 必須重新加載音頻以使新設置生效
                            $('#myAudio')[0].load();
                            $('#myAudio')[0].play(); // 播放音頻
                        }
                    },
                    error: function () {
                        alert('無法載入音頻數據！');
                    }
                });
            });
        });
</script>
@endsection
