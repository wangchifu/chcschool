@include('chcschool.header')
    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
            <h1 class="display-4 fw-bold mb-3">DNS管理</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75 mb-3">
                {{ $dns_data['name'] ?? '' }} ({{ $dns_data['code'] ?? '' }}) - DNS 網域管理
            </p>
            
            <!-- Hero Banner 內的動作按鈕區 -->
            <div class="d-flex justify-content-center align-items-center gap-2">
                <button type="button" class="btn btn-warning rounded-pill px-3 py-1 btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#dnsAdminModal">
                    <i class="bi bi-gear-fill me-1"></i>DNS 管理員名單
                </button>
            </div>
        </div>
    </header>

    <!-- 主要功能按鈕區域 -->
    <main class="container my-5" style="margin-top: -30px !important;">
        
        <!-- 1. 正解 Zone (Forward Lookup) -->
        @if(!empty($dns_data['ipv4']))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span>🌐 正解網域 (Forward Zones)</span>
                    <span class="badge bg-white text-primary">共 {{ count($dns_data['ipv4']) }} 組</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($dns_data['ipv4'] as $domain)
                            <div class="col-md-6">
                                <a href="{{ route('dns_admin.forward', ['domain' => $domain]) }}" 
                                   class="btn btn-outline-primary w-100 py-3 text-start d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong class="d-block text-dark fs-5">{{ $domain }}</strong>
                                        <small class="text-muted">正解 Zone 管理</small>
                                    </span>
                                    <span class="badge bg-primary rounded-pill px-3 py-2">前往管理 &rarr;</span>
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
                <div class="card-header bg-success text-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span>🔄 IPv4 反解網段 (IPv4 PTR Zones)</span>
                    <span class="badge bg-white text-success">共 {{ count($dns_data['ipv4_ptr']) }} 組</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($dns_data['ipv4_ptr'] as $ptrZone)
                            <div class="col-md-6">
                                <a href="{{ route('dns_admin.ptr', ['networkSubnet' => $ptrZone]) }}" 
                                   class="btn btn-outline-success w-100 py-3 text-start d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong class="d-block text-dark fs-5">{{ $ptrZone }}</strong>
                                        <small class="text-muted">IPv4 反解網段</small>
                                    </span>
                                    <span class="badge bg-success rounded-pill px-3 py-2">前往管理 &rarr;</span>
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
                <div class="card-header bg-info text-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span>⚡ IPv6 反解網段 (IPv6 PTR Zones)</span>
                    <span class="badge bg-white text-info">共 {{ count($dns_data['ipv6_ptr']) }} 組</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($dns_data['ipv6_ptr'] as $ptr6Zone)
                            <div class="col-md-6">
                                <a href="{{ route('dns_admin.ptr6', ['networkSubnet' => $ptr6Zone]) }}" 
                                   class="btn btn-outline-info w-100 py-3 text-start d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong class="d-block text-dark fs-5">{{ $ptr6Zone }}</strong>
                                        <small class="text-muted">IPv6 反解網段</small>
                                    </span>
                                    <span class="badge bg-info rounded-pill px-3 py-2">前往管理 &rarr;</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(empty($dns_data['ipv4']) && empty($dns_data['ipv4_ptr']) && empty($dns_data['ipv6_ptr']))
            <div class="alert alert-warning text-center py-4 shadow-sm">
                ⚠️ 目前未找到貴校對應的 DNS 設定資料，請確認 <code>privacy/dns_data.csv</code> 檔案內容。
            </div>
        @endif

    </main>

    <!-- 浮動視窗 (Modal) -->
    <div class="modal fade" id="dnsAdminModal" tabindex="-1" aria-labelledby="dnsAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="dnsAdminModalLabel">
                        <i class="bi bi-shield-lock-fill me-2"></i>DNS 管理員名單管理 (dns_admin.csv)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- 新增管理員區塊 (表單) -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person-plus-fill me-1"></i>新增管理員</h6>
                            <form action="{{ route('dns_admin.add') }}" method="POST" class="row g-2 align-items-center">
                                @csrf
                                <div class="col-md-3">
                                    <input type="text" name="code" class="form-control form-control-sm" placeholder="學校代碼 (例如: 074628)" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="username" class="form-control form-control-sm" placeholder="帳號 Username (例如: wangchifu)" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="姓名 Name (例如: 王志福)" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                                        <i class="bi bi-plus-lg me-1"></i>新增
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- 名單列表 -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 10%;">#</th>
                                    <th scope="col" style="width: 25%;">學校代碼 (Code)</th>
                                    <th scope="col" style="width: 25%;">帳號 (Username)</th>
                                    <th scope="col" style="width: 20%;">姓名 (Name)</th>
                                    <th scope="col" class="text-center" style="width: 20%;">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admin_list as $index => $admin)
                                    <tr>
                                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                        <td><span class="badge bg-secondary font-monospace fs-6">{{ $admin['code'] }}</span></td>
                                        <td class="fw-bold">{{ $admin['username'] }}</td>
                                        <td class="fw-bold">{{ $admin['name'] }}</td>
                                        <td class="text-center">
                                            <!-- 刪除按鈕表單 -->
                                            @if(session('dns_username') != $admin['username'] || session('dns_code') != $admin['code'])
                                                <form action="{{ route('dns_admin.delete') }}" method="POST" onsubmit="return confirm('確定要刪除這筆管理員資料嗎？');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="code" value="{{ $admin['code'] }}">
                                                    <input type="hidden" name="username" value="{{ $admin['username'] }}">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                        <i class="bi bi-trash-fill me-1"></i>刪除
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">目前沒有任何管理員資料</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

@include('chcschool.footer')