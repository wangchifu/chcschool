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
            /* 預設為優雅的深色漸層背景，也可更換下方 url 的圖片網址 */
            background: linear-gradient(135deg, rgba(15, 32, 67, 0.85), rgba(39, 110, 144, 0.85)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
            color: #ffffff;
            padding: 100px 0 120px 0;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* 按鈕卡片懸浮效果 */
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
                <a href="#" class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm fw-bold">
                    <i class="bi bi-person-fill-lock me-1"></i>縣網中心DNS管理者登入
                </a>
            </div>
        </div>
    </nav>

    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-normal mb-3">教育服務網</span>
            <h1 class="display-4 fw-bold mb-3">歡迎光臨彰化縣學校網站代管中心</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75">
                提供穩定、安全、便捷的學校網站託管與校園資訊整合服務
            </p>
        </div>
    </header>

    <!-- 主要功能按鈕區域 -->
    <main class="container my-5" style="margin-top: -50px !important;">
        <div class="row g-4 justify-content-center">
            
            <!-- 按鈕 1：所有代管學校 -->
            <div class="col-md-4">
                <div class="card action-card shadow-sm h-100 p-4 text-center">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-houses-fill"></i>
                            </div>
                            <h3 class="h5 card-title fw-bold mb-3">所有代管學校</h3>
                            <p class="card-text text-muted small">
                                快速查詢與瀏覽彰化縣內所有委託代管網站之學校清單。
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('pages') }}" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">
                                前往查看 <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 按鈕 2：彰化縣空汙旗 -->
            <div class="col-md-4">
                <div class="card action-card shadow-sm h-100 p-4 text-center">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="icon-box bg-success bg-opacity-10 text-success">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                            <h3 class="h5 card-title fw-bold mb-3">彰化縣空汙旗</h3>
                            <p class="card-text text-muted small">
                                即時掌握彰化縣各校區空氣品質指標與空汙旗懸掛資訊。
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('chc_air') }}" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                                即時查詢 <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>            

        </div>
    </main>

    <!-- 頁尾 -->
    <footer class="bg-white py-4 mt-auto border-top">
        <div class="container text-center text-muted small">
            <p class="mb-0">© 彰化縣學校網站代管中心 版權所有</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>