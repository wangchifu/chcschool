<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $zoneDomain }} 正解記錄管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">{{ $schools[auth()->user()->code] }} DNS 管理系統 ({{ $zoneDomain }})(測試中 切勿亂改)</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">{{ auth()->user()->name }} 登出</button>
            </form>
        </div>
    </nav>

    <div class="container mb-5">
        <!-- 訊息通知 -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error') || $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') ?? $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="m-0 font-weight-bold text-primary">
                        Zone: <code>{{ $zoneDomain }}</code> （Server: {{ $dnsServer }}）
                    </h5>
                    <span class="badge bg-secondary">共 {{ count($records) }} 筆記錄</span>
                </div>
                <!-- 浮動面板按鈕 -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    + 新增紀錄
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3" style="min-width: 150px;">域名 (Name)</th>
                                <th style="width: 100px;">類型 (Type)</th>
                                <th style="width: 90px;">TTL</th>
                                <th>記錄值 (Value / IP)</th>
                                <th class="text-center" style="width: 130px; min-width: 130px;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                {{-- 判斷是否有 created_at，若有代表在 3 小時內，套用高亮綠色 --}}
                                <tr class="{{ !empty($record['created_at']) ? 'table-success' : '' }}">
                                    <td class="ps-3">
                                        <code>{{ $record['name'] }}</code>
                                        @if(!empty($record['created_at']))
                                            <span class="badge bg-success ms-1">新增</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $record['type'] === 'A' ? 'bg-primary' : 'bg-info' }}">
                                            {{ $record['type'] }}
                                        </span>
                                    </td>
                                    <td>{{ $record['ttl'] }}s</td>
                                    {{-- 💡 完整顯示資料庫/DNS 查出來的 value（包含結尾點號） --}}
                                    <td style="word-break: break-all;">
                                        <code>{{ $record['value'] }}</code>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <!-- 測試按鈕 -->
                                            <button type="button" 
                                                    class="btn btn-outline-info btn-sm btn-test-dns"
                                                    data-name="{{ $record['name'] }}"
                                                    data-type="{{ $record['type'] }}">
                                                測試
                                            </button>

                                            {{-- 💡 判斷：當域名為 @ 且類型為 NS 時，禁止刪除 --}}
                                            @if($record['name'] === '@' && $record['type'] === 'NS')
                                                <button class="btn btn-secondary btn-sm" disabled title="根網域 NS 記錄保護中，不可刪除">保護中</button>
                                            @else
                                                <!-- 刪除表單按鈕 -->
                                                <form action="{{ route('dns.destroy') }}" method="POST" onsubmit="return confirm('確定要刪除 {{ $record['name'] }} 嗎？');">
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <input type="hidden" name="name" value="{{ $record['name'] }}">
                                                    <input type="hidden" name="type" value="{{ $record['type'] }}">
                                                    <input type="hidden" name="value" value="{{ $record['value'] }}">

                                                    <button type="submit" class="btn btn-danger btn-sm text-nowrap">刪除</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>                               
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        目前沒有找到任何紀錄，或 DNS 伺服器未開放 AXFR 區域轉移。
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 新增紀錄浮動 Modal 視窗 -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addRecordModalLabel">新增 DNS 正解記錄</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dns.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- 主機名稱 -->
                        <div class="mb-3">
                            <label for="name" class="form-label font-weight-bold">主機名稱 (Name)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name" name="name" placeholder="例如: pc1" required>
                                <span class="input-group-text">.{{ $zoneDomain }}</span>
                            </div>
                            <div class="form-text">只需填寫名稱，系統會自動補上 <code>.{{ $zoneDomain }}</code></div>
                        </div>

                        <!-- 記錄類型 -->
                        <div class="mb-3">
                            <label for="type" class="form-label font-weight-bold">記錄類型 (Type)</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="A" selected>A (IPv4 位址)</option>
                                <option value="AAAA">AAAA (IPv6 位址)</option>
                                <option value="CNAME">CNAME (別名)</option>
                                <option value="TXT">TXT (文字記錄)</option>
                                <option value="MX">MX (郵件伺服器 - 優先權固定 20)</option>
                                <option value="SRV">SRV (服務紀錄)</option>
                                <option value="CAA">CAA (憑證授權)</option>
                            </select>
                        </div>

                        <!-- TTL 選項 -->
                        <div class="mb-3">
                            <label for="ttl_option" class="form-label font-weight-bold">快取時間 (TTL)</label>
                            <select class="form-select" id="ttl_option" name="ttl_option" required>
                                <option value="1s">1 秒</option>
                                <option value="1h">1 小時</option>
                                <option value="3h">3 小時</option>
                                <option value="6h">6 小時</option>
                                <option value="12h">12 小時</option>
                                <option value="1d" selected>1 天 (預設)</option>
                                <option value="1w">1 週</option>
                                <option value="1m">1 個月 (30天)</option>
                            </select>
                        </div>

                        <!-- 記錄值 / IP -->
                        <div class="mb-3">
                            <label for="value" class="form-label font-weight-bold">記錄值 (Value / IP)</label>
                            <input type="text" class="form-control" id="value" name="value" placeholder="例如: 163.23.200.10 或 mail.chc.edu.tw." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">確認新增</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 測試結果彈跳視窗 (Modal) -->
    <div class="modal fade" id="testResultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="testModalHeader">
                    <h5 class="modal-title" id="testModalTitle">DNS 解析測試</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 載入中動畫 -->
                    <div id="testLoading" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted mb-0">正在向 DNS 伺服器發送查詢...</p>
                    </div>

                    <!-- 測試結果文字區 -->
                    <div id="testBody" style="display: none;">
                        <pre id="testMessage" class="bg-light p-3 rounded mb-0" style="white-space: pre-wrap; font-family: monospace;"></pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JavaScript 邏輯 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const testModalEl = document.getElementById('testResultModal');
            const testModal = new bootstrap.Modal(testModalEl);
            
            const modalHeader = document.getElementById('testModalHeader');
            const modalTitle  = document.getElementById('testModalTitle');
            const loadingDiv  = document.getElementById('testLoading');
            const bodyDiv     = document.getElementById('testBody');
            const messageEl   = document.getElementById('testMessage');

            // 監聽所有測試按鈕點擊事件
            document.querySelectorAll('.btn-test-dns').forEach(button => {
                button.addEventListener('click', function () {
                    const name = this.getAttribute('data-name');
                    const type = this.getAttribute('data-type');

                    // 初始化 Modal 狀態 (顯示 Loading)
                    modalHeader.className = 'modal-header bg-primary text-white';
                    modalTitle.textContent = `測試中：${name} (${type})`;
                    loadingDiv.style.display = 'block';
                    bodyDiv.style.display = 'none';
                    testModal.show();

                    // 發送 AJAX POST 請求
                    fetch("{{ route('dns.check') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ name: name, type: type })
                    })
                    .then(async response => {
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json();
                        } else {
                            const text = await response.text();
                            throw new Error(`伺服器回應異常 (HTTP ${response.status})。請確認路由 /dns/check 是否正確。`);
                        }
                    })
                    .then(data => {
                        loadingDiv.style.display = 'none';
                        bodyDiv.style.display = 'block';

                        if (data.success) {
                            modalHeader.className = 'modal-header bg-success text-white';
                            modalTitle.textContent = '✅ ' + data.title;
                        } else {
                            modalHeader.className = 'modal-header bg-danger text-white';
                            modalTitle.textContent = '❌ ' + data.title;
                        }
                        messageEl.textContent = data.message;
                    })
                    .catch(error => {
                        loadingDiv.style.display = 'none';
                        bodyDiv.style.display = 'block';
                        modalHeader.className = 'modal-header bg-warning text-dark';
                        modalTitle.textContent = '⚠️ 請求失敗';
                        messageEl.textContent = error.message;
                    });
                });
            });
        });
    </script>
</body>
</html>