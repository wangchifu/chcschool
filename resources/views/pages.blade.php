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
                <a href="#" class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm fw-bold">
                    <i class="bi bi-person-fill-lock me-1"></i>縣網中心DNS管理者登入
                </a>
            </div>
        </div>
    </nav>

    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
            <h1 class="display-4 fw-bold mb-3">彰化縣學校網站代管中心</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75">
                為減輕各校自管官方網站(首頁)的壓力及符合資安法D級單位規定！由縣網中心提供公版首頁，讓國中小各校申請代管！
            </p>
        </div>
    </header>

    <!-- 主要功能按鈕區域 -->
    <main class="container my-5" style="margin-top: -50px !important;">
        <div class="row g-4 justify-content-center">
            
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold me-2">圖例：</span>
                    <button class="btn btn-secondary btn-sm me-1" type="button">學校名 <span class="badge bg-light text-dark">自管</span></button>
                    <button class="btn btn-info btn-sm me-1 text-white" type="button">學校名 <span class="badge bg-light text-dark">公版-1 ({{ $school3_1 }}校)</span></button>
                    <button class="btn btn-primary btn-sm" type="button">學校名 <span class="badge bg-light text-dark">公版-2 ({{ $school3_2 }}校)</span></button>
                </div>
                <div class="card-body">                                            
                    @foreach($townships as $k1 => $v1)
                        <h4 class="h5 fw-bold text-primary mt-2"><i class="bi bi-geo-alt-fill me-1"></i> {{ $v1 }}</h4>
                        @if(isset($all_school[$v1]))
                            <div class="mb-2">
                            @foreach($all_school[$v1] as $k2 => $v2)    
                                @if(isset($schools[$v2['school']]))                            
                                    @if($schools[$v2['school']] != "50" and $schools[$v2['school']] != "49")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-secondary btn-sm m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-light text-dark">自管</span></a>
                                    @endif                            
                                    @if($schools[$v2['school']] == "50")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-info btn-sm text-white m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-light text-dark">公版-1</span></a>
                                    @endif
                                    @if($schools[$v2['school']] == "49")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-primary btn-sm m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-light text-dark">公版-2</span></a>
                                    @endif
                                @else    
                                    <a href="http://{{ $v2['website'] }}" class="btn btn-secondary btn-sm m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-dark">自管</span></a>
                                @endif                           
                            @endforeach
                            </div>
                        @endif               
                        @if(!$loop->last)
                            <hr class="my-3 opacity-25">
                        @endif
                    @endforeach                                     
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