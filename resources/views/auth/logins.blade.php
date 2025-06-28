@extends('layouts.master')

@section('title','教職員登入 | ')

@section('content')
<style>
    .image-button {
        border: 2px solid #ccc;       /* 邊框 */
        border-radius: 10px;          /* 圓角 */
        padding: 4px;                 /* 內距讓圖片不貼邊 */
        transition: 0.3s ease-in-out; /* 平滑過渡效果 */
        display: inline-block;
    }

    .image-button:hover {
        border-color: #007bff;        /* hover 邊框顏色 */
        box-shadow: 0 0 10px rgba(0,123,255,0.4); /* hover 陰影 */
        transform: scale(1.03);       /* 微微放大 */
    }

    .image-button img {
        border-radius: 10px; /* 圖片本身也圓角 */
        display: block;
    }
</style>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">            
            <div class="card-header d-flex align-items-center">
                <a href="https://eip.chc.edu.tw" target="_blank"><img src="{{ asset('images/chc2.png') }}" alt="CHC Logo" width="50" class="me-2" style="margin-right:10px; border:1px solid #000000;"></a>
                彰化縣教育雲端帳號登入
            </div>
            <div class="card-body">                
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">                        
                        <div class="text-center">
                            <a href="{{ route('sso') }}" class="image-button">
                                <img src="{{ asset('images/chc.jpg') }}" alt="彰化chc的logo" width="120">
                            </a>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin_login') }}"><i class="fas fa-cog"></i> 使用本機帳號</a>
                        </div>                                                                      
                    </div>                               
                  </div>                                  
            </div>
        </div>
    </div>
</div>
@endsection
