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

        <!-- 頂部頁頭區塊 -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom gap-2">
            <div>
                <h1 class="h2 fw-bold mb-1">學生首頁</h1>
                <span class="text-secondary small">
                    <i class="fas fa-user-circle me-1"></i> 已登入帳號：<strong>{{ session('stu_data') }}</strong>
                </span>
            </div>
            <div>
                <a href="{{ route('stu.logout') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> 登出
                </a>
            </div>
        </div>

        <!-- 按鈕矩陣區塊 -->
        <div class="row g-4">

            <!-- 【按鈕項目 1】：學生報修（未來複製此方塊即可） -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('fixes.stu_list') }}" class="text-decoration-none">
                    <div class="card dashboard-btn-card h-100 shadow-sm text-center p-3">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <!-- Font Awesome 工具圖示 -->
                            <div class="icon-box">
                                <i class="fas fa-tools fs-2"></i>
                            </div>
                            <h5 class="card-title fw-bold text-dark m-0">學生報修</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 【按鈕項目 2】：預留新增按鈕（更換圖示範例） -->
            <!-- 
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="text-decoration-none">
                    <div class="card dashboard-btn-card h-100 shadow-sm text-center p-3">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="icon-box">
                                <i class="fas fa-user-cog fs-2"></i>
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