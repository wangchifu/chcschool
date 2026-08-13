@include('chcschool.header')    
    <!-- 主要功能按鈕區域 -->
    <main class="container my-5">
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
                            IPv6 PTR Zone: <code>{{ $ptrZoneDomain }}</code> （Server: {{ $dnsServer }}）
                        </h5>
                        <span class="badge bg-secondary">共 {{ count($records) }} 筆紀錄</span>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPtr6RecordModal">
                        + 新增 IPv6 PTR 紀錄
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">主機前綴 (Host Nibbles)</th>
                                    <th style="width: 100px;">類型</th>
                                    <th style="width: 90px;">TTL</th>
                                    <th>指向域名 (PTR Domain Name)</th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                    <tr class="{{ !empty($record['created_at']) ? 'table-success' : '' }}">
                                        <td class="ps-3">
                                            <code>{{ $record['host_part'] }}</code>
                                            @if(!empty($record['created_at']))
                                                <span class="badge bg-success ms-1">新增</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">PTR6</span>
                                        </td>
                                        <td>{{ $record['ttl'] }}s</td>
                                        <td style="word-break: break-all;">
                                            <code>{{ $record['domain'] }}</code>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <!-- 測試按鈕 -->
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-sm btn-test-dns"
                                                        data-name="{{ $record['raw_fqdn'] }}"
                                                        data-type="PTR6">
                                                    測試
                                                </button>

                                                <!-- 刪除表單按鈕 -->
                                                <form action="{{ route('dns_admin.ptr6.destroy') }}" method="POST" onsubmit="return confirm('確定要刪除這筆 IPv6 反解紀錄嗎？');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="network_subnet" value="{{ $networkSubnet }}">
                                                    <input type="hidden" name="raw_fqdn" value="{{ $record['raw_fqdn'] }}">
                                                    <input type="hidden" name="domain" value="{{ $record['domain'] }}">
                                                    <button type="submit" class="btn btn-danger btn-sm text-nowrap">刪除</button>
                                                </form>
                                            </div>
                                        </td>                               
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            目前沒有找到任何 IPv6 PTR 反解紀錄，或 DNS 伺服器未開放 AXFR 區域轉移。
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 新增 IPv6 PTR 紀錄 Modal -->
        <div class="modal fade" id="addPtr6RecordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">新增 IPv6 PTR 反解記錄</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('dns_admin.ptr6.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="network_subnet" value="{{ $networkSubnet }}">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="host_part" class="form-label font-weight-bold">記錄名稱 (IPv6 位址或前綴) *</label>
                                <input type="text" class="form-control" id="host_part" name="host_part" placeholder="例如: 2001:288:5860::1 或 1.0.0.0" required>
                                <div class="form-text">支援填入標準完整 IPv6 位址（例如：<code>2001:288:5860::1</code>）或 Nibble 前綴（例如：<code>1.0.0.0</code>）</div>
                            </div>

                            <div class="mb-3">
                                <label for="ttl_option" class="form-label font-weight-bold">有效時間 (TTL) *</label>
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

                            <div class="mb-3">
                                <label for="domain" class="form-label font-weight-bold">記錄資料 (指向域名) *</label>
                                <input type="text" class="form-control" id="domain" name="domain" placeholder="例如: host.example.com." required>
                                <div class="form-text">請輸入完整域名，末端建議加上點號（例如：<code>host.example.com.</code>）</div>
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

        <!-- 測試 Modal -->
        <div class="modal fade" id="testResultModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" id="testModalHeader">
                        <h5 class="modal-title" id="testModalTitle">IPv6 PTR 解析測試</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="testLoading" class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted mb-0">正在發送反解查詢...</p>
                        </div>
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

                document.querySelectorAll('.btn-test-dns').forEach(button => {
                    button.addEventListener('click', function () {
                        const name = this.getAttribute('data-name');
                        const type = this.getAttribute('data-type');

                        modalHeader.className = 'modal-header bg-primary text-white';
                        modalTitle.textContent = `IPv6 PTR 測試中...`;
                        loadingDiv.style.display = 'block';
                        bodyDiv.style.display = 'none';
                        testModal.show();

                        fetch("{{ route('dns_admin.check') }}", {
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
                                throw new Error(`伺服器回應異常 (HTTP ${response.status})。`);
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
        

    </main>
@include('chcschool.footer')