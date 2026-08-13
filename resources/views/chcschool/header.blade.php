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
                @if(session('dns_admin') == 1)
                    <button type="button" class="btn btn-warning rounded-pill px-3 py-1 btn-sm fw-bold me-2" data-bs-toggle="modal" data-bs-target="#dnsAdminModal">
                        <i class="bi bi-gear-fill me-1"></i>DNS 管理員名單
                    </button>
                    <!-- 已登入狀態：顯示 學校代碼 + 職稱 + 姓名 (若無設定姓名預設顯示帳號) -->
                    <span class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm fw-bold disabled border-0">
                        <i class="bi bi-person-check-fill me-1"></i>
                        {{ session('dns_code') }} {{ session('dns_title') }} {{ session('dns_name') }}
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