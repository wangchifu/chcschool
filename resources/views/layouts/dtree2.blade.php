<?php
// 在後端將資料分類：先撈出所有目錄（type=1），再撈出所有連結（type=2）
$folders_list = \App\Tree::where('type', 1)->orderBy('order_by')->orderBy('name')->get();
$links_list   = \App\Tree::where('type', 2)->orderBy('order_by')->orderBy('name')->get();
?>

{{-- 基礎安全 CSS，黃金比例緊湊間距與無障礙樣式 --}}
<style>
    /* 🎯 1. 主目錄列間距 */
    .tree-folder-item {
        margin-bottom: 2px !important;
    }

    /* 主目錄按鈕區域：使用 calc 扣除 margin 避免寬度溢出產生捲軸 */
    .tree-toggle-btn {
        cursor: pointer;
        user-select: none;
        padding: 3px 6px !important;
        border-radius: 4px;
        transition: background-color 0.15s ease-in-out;
        line-height: 1.35;
        background: transparent;
        border: none;
        width: calc(100% - 4px); /* 扣除左右 margin 避免 100% 溢出 */
        margin-left: 2px;
        box-sizing: border-box;
        text-align: left;
    }
    .tree-toggle-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .tree-toggle-btn:focus,
    .tree-list-group-item a:focus {
        outline: 2px solid #0056b3 !important;
        outline-offset: 1px !important;
    }

    /* 箭頭旋轉動畫 */
    .tree-toggle-btn[aria-expanded="true"] .fa-chevron-right {
        transform: rotate(90deg);
    }
    .tree-toggle-btn .fa-chevron-right {
        transition: transform 0.2s ease-in-out;
    }

    /* 原生展開/收合 CSS 動態切換 */
    .tree-collapse {
        display: none;
    }
    .tree-collapse.show {
        display: block !important;
    }

    /* 🎨 2. 子連結容器：縮排與導引線 */
    .tree-sub-container {
        margin-left: 0.5rem;
        padding-left: 0.5rem;
        border-left: 2px solid #e9ecef;
        margin-top: 2px !important;
        margin-bottom: 4px !important;
    }

    /* 3. 子連結項目間距：防止長字串撐開橫向捲軸 */
    .tree-list-group-item {
        border: none;
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        line-height: 1.35;
    }
    .tree-list-group-item a {
        color: #0d6efd;
        display: inline-block;
        max-width: calc(100% - 25px); /* 扣除圖示寬度 */
        word-break: break-all; /* 超長標題/網址自動斷行 */
        padding: 1px 4px;
        margin-left: 2px;
        transition: color 0.15s ease-in-out;
        vertical-align: middle;
    }
    .tree-list-group-item a:hover {
        color: #0a58ca;
        text-decoration: underline !important;
    }

    /* 4. 無障礙螢幕閱讀器專用隱藏文字 */
    .sr-only, .visually-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }
</style>

<div class="w-100">
    {{-- 1. 切換按鈕 (符合無障礙規範與狀態同步) --}}
    <div class="mb-1">
        <button type="button" 
                id="btn-tree-toggle" 
                class="btn btn-link btn-sm text-decoration-none p-0 text-secondary" 
                data-status="closed"
                aria-expanded="false"
                aria-label="展開或收合所有目錄">
            全部打開
        </button>
    </div>

    {{-- 2. 樹狀內容主體 --}}
    <div class="ps-1">
        <ul class="list-group list-group-flush bg-transparent">
            
            {{-- 先渲染所有「子目錄」 --}}
            @foreach($folders_list as $folder)
                <li class="list-group-item border-0 px-0 bg-transparent py-0 tree-folder-item">
                    {{-- 使用原生 button 標籤，具備語意化 role="button" 與 aria 控制屬性 --}}
                    <button type="button"
                            class="tree-toggle-btn d-flex align-items-center text-dark fw-bold" 
                            data-target="#folder-content-{{ $folder->id }}" 
                            aria-controls="folder-content-{{ $folder->id }}"
                            aria-expanded="false">
                        <i class="fas fa-chevron-right text-muted btn-sm me-2" style="font-size: 0.7rem;" aria-hidden="true"></i>
                        <i class="fas fa-folder text-warning me-2" aria-hidden="true"></i>
                        <span>{{ $folder->name }}</span>
                    </button>

                    {{-- 該目錄底下的內容物容器 --}}
                    <div class="tree-collapse tree-sub-container" id="folder-content-{{ $folder->id }}">
                        <ul class="list-group list-group-flush bg-transparent">
                            <?php
                                $sub_items = $links_list->where('folder_id', $folder->id);
                            ?>
                            @forelse($sub_items as $item)
                                <li class="list-group-item tree-list-group-item bg-transparent px-0">
                                    <i class="fas fa-link text-secondary me-2" style="font-size: 0.7rem;" aria-hidden="true"></i>
                                    <a href="{{ $item->url }}" 
                                       target="_blank" 
                                       title="{{ $item->name }} (另開新視窗)" 
                                       class="text-decoration-none">
                                        {{ $item->name }}
                                        <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.65rem;" aria-hidden="true"></i>
                                        <span class="visually-hidden sr-only">(另開新視窗)</span>
                                    </a>
                                </li>
                            @empty
                                <li class="list-group-item tree-list-group-item bg-transparent text-muted px-0">
                                    <small class="fst-italic">(此目錄目前無連結)</small>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </li>
            @endforeach

            {{-- 再渲染直接屬於根目錄的獨立「連結」 --}}
            <?php 
                $root_links = $links_list->whereIn('folder_id', [0, null]);
            ?>
            @foreach($root_links as $link)
                <li class="list-group-item border-0 px-0 py-0 bg-transparent tree-folder-item">
                    <div class="ps-2 py-0">
                        <i class="fas fa-link text-secondary me-2" style="font-size: 0.7rem;" aria-hidden="true"></i>
                        <a href="{{ $link->url }}" 
                           target="_blank" 
                           title="{{ $link->name }} (另開新視窗)" 
                           class="text-decoration-none">
                            {{ $link->name }}
                            <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.65rem;" aria-hidden="true"></i>
                            <span class="visually-hidden sr-only">(另開新視窗)</span>
                        </a>
                    </div>
                </li>
            @endforeach

        </ul>
    </div>
</div>

{{-- 原生 JavaScript 邏輯 --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // A. 單個目錄點擊與切換 (原生按鈕支援 Enter/Space 鍵與 aria-expanded 狀態同步)
        const toggles = document.querySelectorAll('.tree-toggle-btn');
        toggles.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetEl = document.querySelector(targetId);
                if (!targetEl) return;

                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                if (isExpanded) {
                    this.setAttribute('aria-expanded', 'false');
                    targetEl.classList.remove('show');
                } else {
                    this.setAttribute('aria-expanded', 'true');
                    targetEl.classList.add('show');
                }
            });
        });

        // B. 「全部打開 / 全部關閉」按鈕控制與同步更新 aria-expanded 屬性
        const toggleBtn = document.getElementById('btn-tree-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const currentStatus = this.getAttribute('data-status');
                const collapseElements = document.querySelectorAll('.tree-collapse');
                const titleButtons = document.querySelectorAll('.tree-toggle-btn');

                if (currentStatus === 'closed') {
                    collapseElements.forEach(el => el.classList.add('show'));
                    titleButtons.forEach(btn => btn.setAttribute('aria-expanded', 'true'));
                    
                    this.textContent = '全部關閉';
                    this.setAttribute('data-status', 'opened');
                    this.setAttribute('aria-expanded', 'true');
                } else {
                    collapseElements.forEach(el => el.classList.remove('show'));
                    titleButtons.forEach(btn => btn.setAttribute('aria-expanded', 'false'));
                    
                    this.textContent = '全部打開';
                    this.setAttribute('data-status', 'closed');
                    this.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
</script>