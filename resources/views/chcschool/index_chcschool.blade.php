@include('chcschool.header')

    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
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
            @if(session('dns_admin') == 1)
                <!-- 按鈕：DNS 管理 -->
                <div class="col-md-4">
                    <div class="card action-card shadow-sm h-100 p-4 text-center">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="icon-box bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-hdd-network-fill"></i>
                                </div>
                                <h3 class="h5 card-title fw-bold mb-3">DNS 管理</h3>
                                <p class="card-text text-muted small">
                                    管理與設定校園網域名稱解析 (DNS) 紀錄與相關網路組態。
                                </p>
                            </div>
                            <div class="mt-4">
                                <!-- 請將 route('dns_index') 替換為您實際的 DNS 管理路由名稱 -->
                                <a href="{{ route('dns_admin') }}" class="btn btn-info text-white w-100 py-2 rounded-pill fw-bold">
                                    進入管理 <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>            
            @endif
        </div>
    </main>

    @include('chcschool.footer')