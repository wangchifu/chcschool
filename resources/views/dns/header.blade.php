<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS 管理系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">
                {{ $schools[auth()->user()->code] ?? '' }} DNS 管理系統
            </a>

            <!-- 功能切換按鈕群組 -->
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dns.index') }}" 
                   class="btn btn-sm {{ request()->routeIs('index') ? 'btn-primary' : 'btn-outline-light' }}">
                    🌐 正解檔 (A/CNAME)
                </a>
                
                <a href="{{ route('dns.ptr') }}" 
                   class="btn btn-sm {{ request()->routeIs('dns.ptr.*') ? 'btn-primary' : 'btn-outline-light' }}">
                    🔄 IPv4 反解 (PTR)
                </a>

                <a href="{{ route('dns.ptr6') }}" 
                   class="btn btn-sm {{ request()->routeIs('dns.ptr6.*') ? 'btn-primary' : 'btn-outline-light' }}">
                    ⚡ IPv6 反解 (PTR6)
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0 ms-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        {{ auth()->user()->name }} 登出
                    </button>
                </form>
            </div>
        </div>
    </nav>