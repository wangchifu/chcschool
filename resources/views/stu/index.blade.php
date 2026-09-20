@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '學生首頁')

@section('content')
<style>
    /* 可複製按鈕卡片的懸浮動畫效果 */
    .dashboard-btn-card {
        transition: all 0.25s ease-in-out;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        background: #ffffff;
    }
    .dashboard-btn-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        border-color: #0d6efd;
    }
    .dashboard-btn-card .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #e7f1ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        transition: all 0.25s ease-in-out;
    }
    .dashboard-btn-card:hover .icon-box {
        background-color: #0d6efd;
        color: #ffffff;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11">
        <h1 class="mb-4 fw-bold">學生首頁 ({{ session('stu_data') }} 已登入)</h1>                                    
        <a href="{{ route('stu.logout') }}" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> 登出 ({{ session('stu_data') }})</a>                        
        <!-- 按鈕矩陣區塊 -->
        <div class="row g-4">

            <!-- 【按鈕項目 1】：學生報修（未來複製此方塊即可） -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('fixes.stu_list') }}" class="text-decoration-none">
                    <div class="card dashboard-btn-card h-100 shadow-sm text-center p-3">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <!-- 圖示：使用 Bootstrap Icons 或 FontAwesome -->
                            <div class="icon-box">
                                <i class="bi bi-wrench-adjustable-circle fs-2"></i>
                            </div>
                            <h5 class="card-title fw-bold text-dark m-0">學生報修</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 【按鈕項目 2】：範例預留（未來新增按鈕範例） -->
            <!-- 
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="text-decoration-none">
                    <div class="card dashboard-btn-card h-100 shadow-sm text-center p-3">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="icon-box">
                                <i class="bi bi-person-gear fs-2"></i>
                            </div>
                            <h5 class="card-title fw-bold text-dark m-0">個人設定</h5>
                        </div>
                    </div>
                </a>
            </div>
            -->

        </div>
    </div>
</div>
@endsection