<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>彰化縣學校網站代管中心</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Hero 大橫幅背景樣式 */
        .hero-header {
            background: linear-gradient(135deg, rgba(15, 32, 67, 0.85), rgba(39, 110, 144, 0.85)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
            color: #ffffff;
            padding: 100px 0 120px 0;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .action-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
        }

        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12) !important;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            font-size: 1.8rem;
        }
    </style>
</head>
<body class="bg-light">

    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-anchor navbar-brand fw-bold" href="{{ route('index') }}">
                <i class="bi bi-building-fill-gear me-2"></i>彰化縣學校網站代管中心
            </a>
            <!-- 右上角登入按鈕 -->
            <div class="ms-auto">
                @if(session('dns_admin') == 1)
                    <!-- 已登入狀態：顯示 學校代碼 + 職稱 + 姓名 (若無設定姓名預設顯示帳號) -->
                    <span class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm fw-bold disabled border-0">
                        <i class="bi bi-person-check-fill me-1"></i>
                        {{ session('code') }} {{ session('title') ?? session('job_title') }} {{ session('name') ?? session('username') }}
                    </span>                    
                    <a href="{{ route('chcschool_logout') }}" class="btn btn-sm btn-danger rounded-pill ms-1">登出</a>
                @else
                    <!-- 未登入狀態：顯示登入按鈕 -->
                    <a href="{{ route('chcschool_sso') }}" class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm fw-bold">
                        <i class="bi bi-person-fill-lock me-1"></i>縣網中心DNS管理者登入
                    </a>
                @endif
            </div>                        
        </div>
    </nav>

    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
            <h1 class="display-4 fw-bold mb-3">DNS管理</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75">
                1234
            </p>
        </div>
    </header>

    <!-- 主要功能按鈕區域 -->
    <main class="container my-5" style="margin-top: -50px !important;">
        <div class="row g-4 justify-content-center">
            dns admin                    

        </div>
    </main>

    <!-- 頁尾 -->
    <footer class="bg-white py-4 mt-auto border-top">
        <div class="container text-center text-muted small">
            <p class="mb-0">© 彰化縣教育處縣網中心 版權所有</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>