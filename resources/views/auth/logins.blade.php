@extends('layouts.master')

@section('title', '教職員登入 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：按鈕與連結 Focus 高對比視覺提示 */
    a:focus-visible,
    .btn:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <!-- 無障礙 HM1010301C：主內容區域宣告 -->
    <main class="col-md-5 col-lg-4" aria-label="教職員登入主要區域">
        <div class="card shadow-sm">            
            <div class="card-header d-flex align-items-center bg-light">
                <!-- 另開新視窗無障礙標籤補充 -->
                <a href="https://eip.chc.edu.tw" target="_blank" rel="noopener noreferrer" class="mr-2" aria-label="前往彰化縣教育雲端頁面 (另開新視窗)">
                    <img src="{{ asset('images/chc2.png') }}" alt="彰化縣教育雲端 Logo" width="45" class="border rounded">
                </a>
                <!-- 無障礙 HM1010301C：提升至 h1 語意標題 -->
                <h1 class="h5 m-0 font-weight-bold">彰化縣教育雲端帳號登入</h1>
            </div>

            <div class="card-body p-4">                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">                        
                        
                        <!-- OpenID 登入整合按鈕 (無障礙與語意化) -->
                        <div class="text-center my-3">
                            <a href="{{ route('sso') }}" class="d-inline-flex flex-column align-items-center text-decoration-none p-2 rounded border hover-shadow" aria-label="使用 彰化縣 OpenID 登入">
                                <img src="{{ asset('images/chc.jpg') }}" alt="彰化縣 Education OpenID Logo" width="120" class="img-fluid mb-2">
                                <span class="h6 font-weight-bold mb-0 text-dark">OpenID 登入</span>
                            </a>
                        </div>

                        @include('layouts.errors')

                        <!-- 忘記密碼 (另開新視窗無障礙修復) -->
                        <div class="text-center my-4">
                            <a href="https://eip.chc.edu.tw/recovery-password" target="_blank" rel="noopener noreferrer" class="btn btn-warning font-weight-bold" aria-label="忘記密碼？前往密碼重設頁面 (另開新視窗)">
                                <i class="fas fa-question-circle" aria-hidden="true"></i> 忘記密碼？
                                <span class="sr-only">(另開新視窗)</span>
                            </a>              
                        </div>

                        <hr>

                        <!-- 本機帳號入口 -->
                        <div class="text-right">
                            <a href="{{ route('admin_login') }}" class="text-secondary small">
                                <i class="fas fa-cog" aria-hidden="true"></i> 使用本機帳號登入
                            </a>
                        </div>                                                                      
                    </div>                               
                </div>                                  
            </div>
        </div>
    </main>
</div>
@endsection