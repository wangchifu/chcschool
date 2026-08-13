@include('chcschool.header')
    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
            <h1 class="display-4 fw-bold mb-3">DNS管理</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75">
                {{ $dns_data['name'] ?? '' }} ({{ $dns_data['code'] ?? '' }}) - DNS 網域管理
            </p>
        </div>
    </header>

    <!-- 主要功能按鈕區域 -->
    <main class="container my-5" style="margin-top: -50px !important;">
        
        <!-- 1. 正解 Zone (Forward Lookup) -->
        @if(!empty($dns_data['ipv4']))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold py-3">
                    🌐 正解網域 (Forward Zones)
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($dns_data['ipv4'] as $domain)
                            <div class="col-md-6">
                                <a href="{{ route('dns_admin.forward', ['domain' => $domain]) }}" 
                                   class="btn btn-outline-primary w-100 py-3 text-start d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong class="d-block text-dark">{{ $domain }}</strong>
                                        <small class="text-muted">正解 Zone 管理</small>
                                    </span>
                                    <span class="badge bg-primary rounded-pill">前往管理 &rarr;</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- 2. IPv4 反解 Zone (PTR) -->
        @if(!empty($dns_data['ipv4_ptr']))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white fw-bold py-3">
                    🔄 IPv4 反解網段 (IPv4 PTR Zones)
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($dns_data['ipv4_ptr'] as $ptrZone)
                            <div class="col-md-6">
                                <a href="{{ route('dns_admin.ptr', ['networkSubnet' => $ptrZone]) }}" 
                                   class="btn btn-outline-success w-100 py-3 text-start d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong class="d-block text-dark">{{ $ptrZone }}</strong>
                                        <small class="text-muted">IPv4 反解網段</small>
                                    </span>
                                    <span class="badge bg-success rounded-pill">前往管理 &rarr;</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- 3. IPv6 反解 Zone (PTR6) -->
        @if(!empty($dns_data['ipv6_ptr']))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white fw-bold py-3">
                    ⚡ IPv6 反解網段 (IPv6 PTR Zones)
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($dns_data['ipv6_ptr'] as $ptr6Zone)
                            <div class="col-md-6">
                                <a href="{{ route('dns_admin.ptr6', ['networkSubnet' => $ptr6Zone]) }}" 
                                   class="btn btn-outline-info w-100 py-3 text-start d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong class="d-block text-dark">{{ $ptr6Zone }}</strong>
                                        <small class="text-muted">IPv6 反解網段</small>
                                    </span>
                                    <span class="badge bg-info rounded-pill">前往管理 &rarr;</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(empty($dns_data['ipv4']) && empty($dns_data['ipv4_ptr']) && empty($dns_data['ipv6_ptr']))
            <div class="alert alert-warning text-center py-4">
                ⚠️ 目前未找到貴校對應的 DNS 設定資料，請確認 <code>privacy/dns_data.csv</code> 檔案內容。
            </div>
        @endif

    </main>

@include('chcschool.footer')