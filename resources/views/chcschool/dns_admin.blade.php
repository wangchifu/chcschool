@include('chcschool.header')
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
            <button type="button" class="btn btn-warning rounded-pill px-3 py-1 btn-sm fw-bold me-2" data-bs-toggle="modal" data-bs-target="#dnsAdminModal">
                <i class="bi bi-gear-fill me-1"></i>DNS 管理員名單
            </button>                   

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
                            <div class="col-md-5">
                                <input type="text" name="code" class="form-control form-control-sm" placeholder="學校代碼 (例如: 074628)" required>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="username" class="form-control form-control-sm" placeholder="帳號 Username (例如: wangchifu)" required>
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
                                <th scope="col" style="width: 35%;">學校代碼 (Code)</th>
                                <th scope="col" style="width: 35%;">帳號 (Username)</th>
                                <th scope="col" class="text-center" style="width: 20%;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admin_list as $index => $admin)
                                <tr>
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-secondary font-monospace fs-6">{{ $admin['code'] }}</span></td>
                                    <td class="fw-bold">{{ $admin['username'] }}</td>
                                    <td class="text-center">
                                        <!-- 刪除按鈕表單 -->
                                        <form action="{{ route('dns_admin.delete') }}" method="POST" onsubmit="return confirm('確定要刪除這筆管理員資料嗎？');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="code" value="{{ $admin['code'] }}">
                                            <input type="hidden" name="username" value="{{ $admin['username'] }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                <i class="bi bi-trash-fill me-1"></i>刪除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">目前沒有任何管理員資料</td>
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