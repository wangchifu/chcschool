@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '圖片連結 | ')

@section('content')
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