<?php
// 1. 一次性撈出所有資料並排序，避免 N+1 效能問題
$all_trees = \App\Tree::orderBy('order_by', 'asc')->orderBy('name', 'asc')->get();

// 2. 依照 folder_id 分組，為無限層級遞迴渲染做準備
$tree_groups = $all_trees->groupBy('folder_id');
?>

{{-- 🎨 對齊優化與無障礙樣式 --}}
<style>
    /* 全域盒模型統一與防橫向捲軸 */
    .tree-wrapper,
    .tree-wrapper * {
        box-sizing: border-box !important;
    }

    .tree-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans TC", sans-serif;
    }

    /* 樹狀節點通用間距 */
    .tree-item {
        margin-bottom: 2px !important;
    }

    /* 🎯 關鍵對齊核心：目錄按鈕與獨立連結共用極致對齊外框 */
    .tree-toggle-btn,
    .tree-link {
        display: flex !important;
        align-items: center;
        width: 100%;
        padding: 4px 8px !important;
        border-radius: 4px;
        border: 2px solid transparent !important; /* 預留實體內邊框，解決 Focus 右側被 overflow 裁切問題 */
        background: transparent;
        transition: background-color 0.15s ease, border-color 0.15s ease;
        line-height: 1.4;
        text-align: left;
        text-decoration: none !important;
    }

    .tree-toggle-btn {
        color: #1c2430;
        cursor: pointer;
        user-select: none;
    }

    .tree-link {
        color: #0d47a1 !important; /* 符合 WCAG 4.5:1+ 文字對比度要求 */
        font-weight: 500;
    }

    /* Hover 質感效果 */
    .tree-toggle-btn:hover,
    .tree-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .tree-link:hover {
        color: #002171 !important;
    }

    /* WCAG AA 無障礙聚焦狀態 (實體內邊框 + 高對比底色) */
    .tree-toggle-btn:focus,
    .tree-toggle-btn:focus-visible,
    .tree-link:focus,
    .tree-link:focus-visible {
        outline: none !important;
        border-color: #d97706 !important; /* 高對比橘色焦點線 */
        background-color: #fef3c7 !important; /* 淺黃色背景 */
        box-shadow: none !important;
    }

    /* 📐 圖示對齊雙槽位 (固定寬度與中央對齊) */
    .tree-slot-arrow {
        width: 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .tree-slot-icon {
        width: 1.5rem; /* 給予圖示適當的寬度與呼吸空間 */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 0.25rem;
    }

    /* 🎯 統一所有類型圖示的大小，避免資料夾與連結圖示尺寸不一 */
    .tree-slot-icon i {
        font-size: 0.95rem !important;
    }

    .tree-title-text {
        word-break: break-all;
        flex-grow: 1;
    }

    /* 箭頭旋轉動畫 */
    .tree-toggle-btn[aria-expanded="true"] .tree-arrow {
        transform: rotate(90deg);
    }
    .tree-arrow {
        transition: transform 0.2s ease-in-out;
    }

    /* 原生動態展收 */
    .tree-collapse {
        display: none;
    }
    .tree-collapse.show {
        display: block !important;
    }

    /* 子層容器：層級縮排與 WCAG 高對比左側導引線 */
    .tree-sub-container {
        margin-left: 0.6rem;
        padding-left: 0.4rem;
        border-left: 2px solid #cbd5e1; /* 高對比階層導引線 */
        margin-top: 2px !important;
        margin-bottom: 4px !important;
    }

    /* 無障礙螢幕閱讀器專用隱藏類別 */
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

{{-- 🔄 純 PHP 無限層級遞迴渲染函式 (避免 Blade $__env 作用域報錯) --}}
@php
if (!function_exists('renderTreeBranch')) {
    function renderTreeBranch($parentId, $treeGroups, $level = 1) {
        if (!$treeGroups->has($parentId)) {
            return;
        }

        $items = $treeGroups->get($parentId);

        echo '<ul class="list-group list-group-flush bg-transparent ps-0" role="group">';

        foreach ($items as $item) {
            $hasChildren = $treeGroups->has($item->id);
            $isFolder = $hasChildren || empty($item->url);
            $itemName = e($item->name);
            $itemUrl = e($item->url);

            echo '<li class="list-group-item border-0 px-0 bg-transparent py-0 tree-item" role="treeitem" aria-level="' . $level . '">';

            if ($isFolder) {
                // 【目錄節點】：1.箭頭槽 + 2.資料夾圖示槽(fa-fw等寬) + 3.標題文字
                echo '<button type="button" class="tree-toggle-btn fw-bold" data-target="#folder-content-' . $item->id . '" aria-controls="folder-content-' . $item->id . '" aria-expanded="false">';
                echo '<span class="tree-slot-arrow"><i class="fas fa-chevron-right text-muted tree-arrow" style="font-size: 0.7rem;" aria-hidden="true"></i></span>';
                echo '<span class="tree-slot-icon"><i class="fas fa-folder fa-fw text-warning" aria-hidden="true"></i></span>';
                echo '<span class="tree-title-text">' . $itemName . '</span>';

                if (!empty($item->url)) {
                    echo '<a href="' . $itemUrl . '" target="_blank" rel="noopener noreferrer" class="ms-2 text-decoration-none" title="' . $itemName . ' 頁面 (另開新視窗)" onclick="event.stopPropagation();">';
                    echo '<i class="fas fa-external-link-alt text-muted" style="font-size: 0.65rem;" aria-hidden="true"></i>';
                    echo '<span class="visually-hidden sr-only">(另開新視窗)</span>';
                    echo '</a>';
                }

                echo '</button>';

                // 巢狀子區塊容器
                echo '<div class="tree-collapse tree-sub-container" id="folder-content-' . $item->id . '">';
                renderTreeBranch($item->id, $treeGroups, $level + 1);
                echo '</div>';
            } else {
                // 【獨立連結節點】：1.空白預留槽 + 2.連結圖示槽(fa-fw等寬) + 3.標題文字
                echo '<div class="tree-leaf-item">';
                echo '<a href="' . $itemUrl . '" target="_blank" rel="noopener noreferrer" title="' . $itemName . ' (另開新視窗)" class="tree-link">';
                echo '<span class="tree-slot-arrow"></span>';
                echo '<span class="tree-slot-icon"><i class="fas fa-link fa-fw text-secondary" aria-hidden="true"></i></span>';
                echo '<span class="tree-title-text">' . $itemName . '</span>';
                echo '<i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.65rem;" aria-hidden="true"></i>';
                echo '<span class="visually-hidden sr-only">(另開新視窗)</span>';
                echo '</a>';
                echo '</div>';
            }

            echo '</li>';
        }

        echo '</ul>';
    }
}
@endphp

{{-- 🌐 樹狀圖主要畫面 --}}
<div class="tree-wrapper p-1">
    {{-- 1. 全域展開 / 收合控制按鈕 (符合 WCAG 按鈕標準) --}}
    <div class="mb-2">
        <button type="button" 
                id="btn-tree-toggle" 
                class="btn btn-outline-secondary btn-sm text-decoration-none px-2 py-1" 
                data-status="closed"
                aria-expanded="false"
                aria-label="展開或收合所有目錄">
            <i class="fas fa-folder-open me-1" aria-hidden="true"></i> 全部打開
        </button>
    </div>

    {{-- 2. 開始繪製最外層目錄與連結 (folder_id = 0) --}}
    @php renderTreeBranch(0, $tree_groups); @endphp
</div>

{{-- ⚡ 原生 JavaScript：邏輯與無障礙狀態同步 --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // A. 單一目錄切換 (同步更新 aria-expanded 屬性與 css display)
        const wrapper = document.querySelector('.tree-wrapper');
        if (wrapper) {
            wrapper.addEventListener('click', function(e) {
                const btn = e.target.closest('.tree-toggle-btn');
                if (!btn) return;

                const targetId = btn.getAttribute('data-target');
                const targetEl = document.querySelector(targetId);
                if (!targetEl) return;

                const isExpanded = btn.getAttribute('aria-expanded') === 'true';

                if (isExpanded) {
                    btn.setAttribute('aria-expanded', 'false');
                    targetEl.classList.remove('show');
                } else {
                    btn.setAttribute('aria-expanded', 'true');
                    targetEl.classList.add('show');
                }
            });
        }

        // B. 「全部打開 / 全部關閉」按鈕邏輯
        const toggleBtn = document.getElementById('btn-tree-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const currentStatus = this.getAttribute('data-status');
                const collapseElements = document.querySelectorAll('.tree-collapse');
                const titleButtons = document.querySelectorAll('.tree-toggle-btn');

                if (currentStatus === 'closed') {
                    collapseElements.forEach(el => el.classList.add('show'));
                    titleButtons.forEach(btn => btn.setAttribute('aria-expanded', 'true'));
                    
                    this.innerHTML = '<i class="fas fa-folder me-1" aria-hidden="true"></i> 全部關閉';
                    this.setAttribute('data-status', 'opened');
                    this.setAttribute('aria-expanded', 'true');
                } else {
                    collapseElements.forEach(el => el.classList.remove('show'));
                    titleButtons.forEach(btn => btn.setAttribute('aria-expanded', 'false'));
                    
                    this.innerHTML = '<i class="fas fa-folder-open me-1" aria-hidden="true"></i> 全部打開';
                    this.setAttribute('data-status', 'closed');
                    this.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
</script>