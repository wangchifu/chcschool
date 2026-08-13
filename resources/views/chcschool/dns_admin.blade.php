@include('chcschool.header')
    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
            <h1 class="display-4 fw-bold mb-3">DNS管理</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75 mb-3">
                所有 DNS 網域與反解網段管理列表
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
        
        @php
            // 計算各種類別的總數量
            $totalIpv4 = 0;
            $totalIpv4Ptr = 0;
            $totalIpv6Ptr = 0;
            foreach($dns_data as $school) {
                $totalIpv4 += count($school['ipv4'] ?? []);
                $totalIpv4Ptr += count($school['ipv4_ptr'] ?? []);
                $totalIpv6Ptr += count($school['ipv6_ptr'] ?? []);
            }
            $totalAll = $totalIpv4 + $totalIpv4Ptr + $totalIpv6Ptr;
        @endphp

        <!-- 分類切換按鈕區 -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-2 bg-light rounded">
                <div class="d-flex justify-content-center flex-wrap gap-2" id="dns-filter-buttons">
                    <button type="button" class="btn btn-dark active rounded-pill px-4" data-filter="all">
                        🌐 全部 <span class="badge bg-white text-dark ms-1">{{ $totalAll }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-filter="ipv4">
                        🌐 正解網域 <span class="badge bg-primary ms-1">{{ $totalIpv4 }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-4" data-filter="ipv4_ptr">
                        🔄 IPv4 反解 <span class="badge bg-success ms-1">{{ $totalIpv4Ptr }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-info rounded-pill px-4" data-filter="ipv6_ptr">
                        ⚡ IPv6 反解 <span class="badge bg-info ms-1">{{ $totalIpv6Ptr }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- DNS 資料列表區 -->
        <div id="dns-card-container">
            @forelse($dns_data as $school)
                {{-- 1. 正解 Zone --}}
                @foreach($school['ipv4'] ?? [] as $domain)
                    <div class="card shadow-sm mb-3 dns-card-item" data-type="ipv4">
                        <div class="card-body d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="badge bg-primary me-2">正解 Zone</span>
                                <strong class="fs-5 text-dark">{{ $domain }}</strong>
                                <span class="text-muted ms-2">({{ $school['name'] }} - {{ $school['code'] }})</span>
                            </div>
                            <a href="{{ route('dns_admin.forward', ['domain' => $domain]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                前往管理 &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- 2. IPv4 反解 Zone --}}
                @foreach($school['ipv4_ptr'] ?? [] as $ptrZone)
                    <div class="card shadow-sm mb-3 dns-card-item" data-type="ipv4_ptr">
                        <div class="card-body d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="badge bg-success me-2">IPv4 反解</span>
                                <strong class="fs-5 text-dark">{{ $ptrZone }}</strong>
                                <span class="text-muted ms-2">({{ $school['name'] }} - {{ $school['code'] }})</span>
                            </div>
                            <a href="{{ route('dns_admin.ptr', ['networkSubnet' => $ptrZone]) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                前往管理 &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- 3. IPv6 反解 Zone --}}
                @foreach($school['ipv6_ptr'] ?? [] as $ptr6Zone)
                    <div class="card shadow-sm mb-3 dns-card-item" data-type="ipv6_ptr">
                        <div class="card-body d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="badge bg-info me-2">IPv6 反解</span>
                                <strong class="fs-5 text-dark">{{ $ptr6Zone }}</strong>
                                <span class="text-muted ms-2">({{ $school['name'] }} - {{ $school['code'] }})</span>
                            </div>
                            <a href="{{ route('dns_admin.ptr6', ['networkSubnet' => $ptr6Zone]) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">
                                前往管理 &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="alert alert-warning text-center py-4 shadow-sm">
                    ⚠️ 目前未找到任何 DNS 設定資料，請確認 <code>privacy/dns_data.csv</code> 檔案內容。
                </div>
            @endforelse

            <!-- 無搜尋結果提示 -->
            <div id="no-dns-data" class="alert alert-secondary text-center py-4 shadow-sm d-none">
                📭 該分類下目前沒有任何資料。
            </div>
        </div>

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

    <!-- JS 按鈕切換邏輯 -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterButtons = document.querySelectorAll('#dns-filter-buttons button');
            const dnsItems = document.querySelectorAll('.dns-card-item');
            const noDataAlert = document.getElementById('no-dns-data');

            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.getAttribute('data-filter');

                    // 更新按鈕樣式 (Active 狀態切換)
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                        // 恢復外框按鈕樣式
                        if(btn.dataset.filter === 'all') btn.className = 'btn btn-outline-dark rounded-pill px-4';
                        if(btn.dataset.filter === 'ipv4') btn.className = 'btn btn-outline-primary rounded-pill px-4';
                        if(btn.dataset.filter === 'ipv4_ptr') btn.className = 'btn btn-outline-success rounded-pill px-4';
                        if(btn.dataset.filter === 'ipv6_ptr') btn.className = 'btn btn-outline-info rounded-pill px-4';
                    });

                    // 為被點擊的按鈕加上 active 與實心背景
                    this.classList.add('active');
                    if(filter === 'all') this.className = 'btn btn-dark active rounded-pill px-4';
                    if(filter === 'ipv4') this.className = 'btn btn-primary active rounded-pill px-4';
                    if(filter === 'ipv4_ptr') this.className = 'btn btn-success active rounded-pill px-4';
                    if(filter === 'ipv6_ptr') this.className = 'btn btn-info active text-white rounded-pill px-4';

                    // 過濾卡片顯示
                    let visibleCount = 0;
                    dnsItems.forEach(item => {
                        if (filter === 'all' || item.getAttribute('data-type') === filter) {
                            item.style.display = 'block';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // 如果該條件下完全沒資料，顯示無資料提示
                    if (visibleCount === 0) {
                        noDataAlert.classList.remove('d-none');
                    } else {
                        noDataAlert.classList.add('d-none');
                    }
                });
            });
        });
    </script>

@include('chcschool.footer')