@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '圖片連結 | ')

@section('content')
<style>
    /* 🎯 1. 高對比度分類頁籤 (WCAG 1.4.3: 對比度 > 7:1) */
    .custom-pills {
        padding-top: 4px; /* 留出鍵盤 Focus 框空間 */
    }
    .custom-pills .nav-link {
        color: #1e293b;
        background-color: #ffffff;
        border: 2px solid #64748b;
        border-radius: 50rem;
        padding: 0.5rem 1.25rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
        transition: all 0.2s ease-in-out;
    }
    .custom-pills .nav-link:hover {
        background-color: #f1f5f9;
        color: #003d82;
        border-color: #003d82;
    }
    .custom-pills .nav-link.active {
        background-color: #003d82 !important;
        color: #ffffff !important;
        border-color: #003d82 !important;
        box-shadow: 0 4px 8px rgba(0, 61, 130, 0.3);
    }

    /* 🖼️ 2. 卡片整體可點擊與高對比邊框 */
    .photo-link-card {
        border-radius: 10px !important;
        border: 2px solid #cbd5e1 !important;
        overflow: hidden;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .photo-link-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
        border-color: #003d82 !important;
    }

    /* 🎯 3. 無障礙 WCAG 2.4.7 / HM1020401C：全卡片高對比焦點框 */
    .photo-link-card:focus-within {
        outline: 3px solid #003d82 !important;
        outline-offset: 3px !important;
        background-color: #fef3c7 !important;
    }

    /* 📸 4. 圖片區域：大幅提升圖片佔比 (16:9 滿版切齊卡片) */
    .card-img-wrapper {
        width: 100%;
        aspect-ratio: 16 / 9; /* 保持 16:9 大比例，可依需求改為 4 / 3 */
        background-color: #f8fafc;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
    }
    .photo-link-img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* 滿版裁切，消除四周空隙 */
        transition: transform 0.3s ease;
    }
    .photo-link-card:hover .photo-link-img {
        transform: scale(1.06); /* 滑鼠懸浮時微放大 */
    }

    /* 5. 標題與文字顏色調深 (確保對比度 9.5:1) */
    .card-title-link {
        color: #003d82 !important;
        font-weight: 700;
        font-size: 1.05rem;
        text-decoration: underline !important;
    }
    .card-title-link:hover {
        color: #00224a !important;
    }

    .link-title-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.8rem;
        line-height: 1.4;
    }

    .text-dark-custom {
        color: #1e293b !important;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11">
        {{-- 1. 頂部標題與麵包屑 --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 border-bottom pb-3">
            <div>
                <nav aria-label="麵包屑導覽">
                    <ol class="breadcrumb bg-transparent p-0 mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-dark-custom">首頁</a></li>
                        <li class="breadcrumb-item active text-dark-custom font-weight-bold" aria-current="page">圖片連結</li>
                    </ol>
                </nav>
                <h1 class="h2 font-weight-bold text-dark-custom mb-0">
                    <i class="fas fa-photo-video me-2 text-primary" aria-hidden="true"></i>圖片連結專區
                </h1>
            </div>
        </div>

        {{-- 2. 分類頁籤 --}}
        <nav class="mb-4" aria-label="圖片連結分類">
            <ul class="nav nav-pills custom-pills">
                <li class="nav-item">
                    <a class="nav-link {{ $photo_type_id == null ? 'active' : '' }}" href="{{ route('photo_links.show') }}">
                        <i class="fas fa-th-large me-1" aria-hidden="true"></i> 全部
                    </a>
                </li>
                @foreach($photo_types as $photo_type)
                    @php $isActive = ($photo_type->id == $photo_type_id) ? 'active' : ''; @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive }}" href="{{ route('photo_links.show', $photo_type->id) }}">
                            {{ $photo_type->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- 3. 大圖片卡片網格 --}}
        <ul class="row list-unstyled row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-0" aria-label="圖片連結清單">
            @forelse($photo_links as $photo_link)
                @php
                    $school_code = school_code();
                    $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                @endphp
                <li class="col mb-4">
                    <div class="card h-100 photo-link-card shadow-sm position-relative">
                        {{-- 滿版大圖顯示區域 --}}
                        <div class="card-img-wrapper">
                            <img src="{{ asset($img) }}" class="photo-link-img" alt="" aria-hidden="true">
                        </div>
                        
                        {{-- 卡片文字內容區域 --}}
                        <div class="card-body d-flex flex-column justify-content-between p-3">
                            <div class="mb-2">
                                <a href="{{ $photo_link->url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="card-title-link stretched-link link-title-text" 
                                   title="{{ $photo_link->name }} (另開新視窗)">
                                    {{ $photo_link->name }}
                                </a>
                            </div>
                            
                            {{-- 底部資訊 --}}
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                <span class="badge bg-light text-dark border font-weight-bold">
                                    排序 {{ $photo_link->order_by }}
                                </span>
                                <span class="btn btn-outline-dark btn-sm px-2 py-0 font-weight-bold" aria-hidden="true">
                                    前往 <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7rem;"></i>
                                </span>
                                <span class="sr-only">（另開新視窗）</span>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="col-12 w-100">
                    <div class="alert alert-info text-center my-5" role="alert">
                        <i class="fas fa-info-circle me-2" aria-hidden="true"></i>目前尚無相關圖片連結。
                    </div>
                </li>
            @endforelse
        </ul>

        {{-- 4. 分頁按鈕 --}}
        <nav class="d-flex justify-content-center mt-4" aria-label="分頁導覽">
            {{ $photo_links->links() }}
        </nav>
    </div>
</div>
@endsection