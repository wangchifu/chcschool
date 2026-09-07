@extends('layouts.master')

@section('title', '管理登入 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：表單與按鈕 Focus 高對比視覺提示 */
    .form-control:focus-visible,
    .btn:focus-visible,
    a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <!-- 無障礙 HM1010301C：宣告主內容區域 -->
    <main class="col-md-6" aria-label="管理員登入主要區域">
        <div class="card">
            <div class="card-header">
                <!-- 無障礙 HM1010301C：提升標題語意等級 -->
                <h1 class="h4 m-0 font-weight-bold">管理登入</h1>
            </div>

            <div class="card-body">
                @if(session('login_error') < 3)
                <form method="POST" action="{{ route('auth') }}" id="this_form">
                    @csrf

                    <!-- 本機帳號 -->
                    <div class="form-group row">
                        <label for="username" class="col-sm-4 col-form-label text-md-right">本機帳號</label>
                        <div class="col-md-6">
                            <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">

                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 密碼 -->
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">密碼</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 驗證碼圖片與語音 -->
                    <div class="form-group row align-items-center">
                        <div class="col-md-4 text-md-right">
                            <span class="font-weight-bold">圖形驗證碼</span>
                        </div>
                        <div class="col-md-6 text-md-left">
                            <a href="{{ route('admin_login') }}" title="點擊重新產生驗證碼" aria-label="點擊重新產生驗證碼">
                                <img src="{{ route('pic') }}" class="img-fluid border rounded" alt="圖形驗證碼：請閱讀圖片內的國字並轉換為數字輸入">
                            </a>                       
                            <a href="#!" id="loadAudio" class="d-inline-block ml-2 text-primary" role="button" aria-label="播放驗證碼語音">
                                <i class="fas fa-volume-up" aria-hidden="true"></i> [語音播放]
                            </a>
                            <audio id="myAudio">
                                <source src="" type="audio/mp3">                                
                            </audio>                            
                        </div>
                    </div>

                    <!-- 驗證碼輸入 -->
                    <div class="form-group row">
                        <label for="chaptcha" class="col-md-4 col-form-label text-md-right">驗證碼答案</label>
                        <div class="col-md-6">
                            <input type="text" id="chaptcha" class="form-control" name="chaptcha" required placeholder="上圖國字轉阿拉伯數字" maxlength="5" title="請輸入圖形驗證碼對應的數字">
                        </div>
                    </div>

                    <!-- 送出按鈕 -->
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt" aria-hidden="true"></i> 登入
                            </button>
                        </div>
                    </div>
                </form>
                @else
                    <?php
                        $k = rand(100,999);
                        session(['check_bot'=>$k]);
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>登入錯誤超過三次！</strong> 請輸入下方防機器人三碼數字後送出：
                    </div>
                    <form action="{{ route('not_bot') }}" method="post" class="form-inline justify-content-center my-3">
                        @csrf
                        <label for="check_bot" class="sr-only">防機器人驗證碼</label>
                        <input type="text" id="check_bot" name="check_bot" class="form-control mr-2" placeholder="請輸入：{{ session('check_bot') }}" required>
                        <button type="submit" class="btn btn-primary btn-sm">我不是機器人</button>
                    </form>
                @endif

                @include('layouts.errors')

                <hr>

                <!-- OpenID 登入整合 -->
                <div class="text-right">
                    <a href="{{ route('sso') }}" class="d-inline-flex flex-column align-items-center text-decoration-none" aria-label="使用 彰化縣 OpenID 登入">
                        <img src="{{ asset('images/chc.jpg') }}" alt="彰化縣 Education OpenID Logo" width="80" class="mb-1">
                        <span class="small font-weight-bold">彰化 OpenID 登入</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    $(document).ready(function () {
        // 如果有引入 jQuery Validate 套件
        if ($.fn.validate) {
            $("#this_form").validate();
        }

        // 語音播放 AJAX
        $('#loadAudio').click(function (e) {
            e.preventDefault();
            var $btn = $(this);
            $btn.addClass('disabled').html('<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> 載入中...');

            $.ajax({
                url: '{{ route('voice') }}',
                type: 'GET',
                success: function (response) {
                    if (response.startsWith('Error')) {
                        alert(response);
                    } else {
                        const base64Src = `data:audio/mpeg;base64,${response}`;
                        $('#myAudio source').attr('src', base64Src);
                        
                        var audio = $('#myAudio')[0];
                        audio.load();
                        audio.play();
                    }
                },
                error: function () {
                    alert('無法載入音頻數據，請稍後再試！');
                },
                complete: function () {
                    $btn.removeClass('disabled').html('<i class="fas fa-volume-up" aria-hidden="true"></i> [語音播放]');
                }
            });
        });
    });
</script>
@endsection