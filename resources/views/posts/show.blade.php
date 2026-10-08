@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', $post->title.' | ')

@section('in_head')
    <!-- VenoBox CDN (CSS & JS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/venobox/2.1.8/venobox.min.css" type="text/css" media="screen">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/venobox/2.1.8/venobox.min.js"></script>
@endsection

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：連結、按鈕 Focus 高對比視覺提示 */
    a:focus-visible,
    button:focus-visible,
    .btn:focus-visible,
    .form-control:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }

    /* 無障礙 2.4.7 修正：VenoBox 燈箱按鈕 Focus 時高對比黃色外框提示 */
    .vbox-close:focus,
    .vbox-prev:focus,
    .vbox-next:focus,
    .vbox-left:focus,
    .vbox-right:focus {
        outline: 3px solid #ffc107 !important;
        outline-offset: 3px !important;
        box-shadow: 0 0 0 2px #000 !important;
        border-radius: 4px !important;
    }

    /* =========================================================
       FCKeditor & Word 複製貼上之防護覆蓋樣式 (CSS Override)
       ========================================================= */
    .post-content-body {
        font-size: 1.15rem;
        line-height: 1.8;
        word-break: break-word; /* 避免超長網址或無空格文字撐破版面 */
        overflow-wrap: break-word;
    }

    /* 重置背景色與寬度 (避免從 Word 貼上時帶有固定的背景色或撐破頁面) */
    .post-content-body span,
    .post-content-body p,
    .post-content-body div,
    .post-content-body font {
        background-color: transparent !important;
    }

    /* 針對 Word/FCKeditor 固定寬度表格進行 RWD 縮放防護 */
    .post-content-body table {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        table-layout: auto;
    }

    /* 針對內文圖片自動縮放 */
    .post-content-body img {
        max-width: 100% !important;
        height: auto !important;
    }

    /* 標題標籤 (h1~h6) 保持語意化放大 */
    .post-content-body h1 { font-size: 1.8rem !important; font-weight: bold; margin-top: 1rem; }
    .post-content-body h2 { font-size: 1.5rem !important; font-weight: bold; margin-top: 1rem; }
    .post-content-body h3 { font-size: 1.3rem !important; font-weight: bold; margin-top: 1rem; }
    .post-content-body h4, 
    .post-content-body h5, 
    .post-content-body h6 { font-size: 1.15rem !important; font-weight: bold; }

    /* 無障礙螢幕閱讀器專用隱藏文字 */
    .sr-only {
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

<div class="row justify-content-center">

    <!-- 主內容區塊 (無障礙修正：避免與 master.blade.php 的 main 重複衝突，改用 role="region") -->
    <div class="col-lg-8" role="region" aria-label="公告詳細內容">

        <?php
        if($post->insite==1){
            if(auth()->check() or check_ip()){
                $can_see = 1;
            }else{
                $can_see = 0;
            }
        }else{
            $can_see = 1;
        };
        //下架日比今天早(小)，不能看
        if($post->die_date != null and $post->die_date < date('Y-m-d')){
            $can_see = 0;
        }
        //上架日比今天晚(大)，不能看
        if(substr($post->created_at,0,10) > date('Y-m-d')){
            $can_see = 0;
        }
        //作者可以看
        if(auth()->check()){
            if($post->user_id == auth()->user()->id){
            $can_see = 1;
            }
        }            
        ?>

        <!-- 麵包屑導覽列 -->
        <nav aria-label="麵包屑導覽">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}" title="返回首頁" aria-label="返回首頁">首頁</a></li>
                <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" title="返回公告列表" aria-label="返回公告列表">公告列表</a></li>
                <li class="breadcrumb-item active" aria-current="page">公告內容</li>
            </ol>
        </nav>

        <!-- 公告主標題 -->
        @if($can_see)
            <h1 class="h2 mb-3">{!! a11y_emoji($post->title) !!}</h1>                             
        @else
            @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                <h1 class="h2 text-danger mb-3">
                    <i class="fas fa-ban" aria-hidden="true"></i> [ 內部公告 ] {!! a11y_emoji($post->title) !!}
                </h1>                                           
            @endif
            @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                <h1 class="h2 mb-3">本公告已下架</h1>                
            @elseif(substr($post->created_at,0,10) > date('Y-m-d'))
                <h1 class="h2 mb-3">本公告尚未上架</h1>
            @endif
        @endif            

        <!-- 上一則 / 下一則切換導覽 -->
        <nav aria-label="前後公告切換" class="mb-3">
            @if($last_id)
                <a href="{{ route('posts.show',$last_id) }}" class="btn btn-secondary btn-sm" title="前往上一則公告" aria-label="前往上一則公告">
                    <i class="fas fa-arrow-alt-circle-left" aria-hidden="true"></i> 上一則公告
                </a>
            @else
                <button class="btn btn-secondary btn-sm" disabled aria-disabled="true">
                    <i class="fas fa-arrow-alt-circle-left" aria-hidden="true"></i> 上一則公告
                </button>
            @endif

            @if($next_id)
                <a href="{{ route('posts.show',$next_id) }}" class="btn btn-secondary btn-sm ml-1" title="前往下一則公告" aria-label="前往下一則公告">
                    下一則公告 <i class="fas fa-arrow-alt-circle-right" aria-hidden="true"></i>
                </a>
            @else
                <button class="btn btn-secondary btn-sm ml-1" disabled aria-disabled="true">
                    下一則公告 <i class="fas fa-arrow-alt-circle-right" aria-hidden="true"></i>
                </button>
            @endif
        </nav>

        <!-- 中間資訊與管理功能列 -->
        <div class="lead my-3" style="font-size: 1.1rem;">            
            <?php
                $insite = ($post->insite != null) ? $post->insite : 0;
            ?>
            <span class="mr-2">類別：<a href="{{ route('posts.type',$insite) }}" title="分類：{{ $post_type_array[$insite] }}" aria-label="分類：{{ $post_type_array[$insite] }}">{{ $post_type_array[$insite] }}</a></span>
            <span class="mr-2">張貼者：<a href="{{ route('posts.job_title',$post->job_title) }}" title="張貼者：{{ $post->job_title }}" aria-label="張貼者：{{ $post->job_title }}">{{ $post->job_title }}</a></span>
            @if($post->die_date)
                <span class="mr-2">張貼至：{{ $post->die_date }} 止</span>
            @endif

            @auth
                @if(auth()->user()->admin)
                    @if($post->top)
                        @if(!empty($post->top_date))
                            <span class="badge badge-secondary">置頂至 {{ $post->top_date }}</span>
                        @endif
                        <a href="{{ route('posts.top_down',$post->id) }}" class="btn btn-warning btn-sm ml-1" onclick="return confirm('確定要取消置頂？')" title="取消置頂" aria-label="取消置頂">
                            <i class="fas fa-sort-amount-down" aria-hidden="true"></i> 取消置頂
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-success btn-sm ml-1" data-toggle="modal" data-target="#exampleModal" title="設定置頂" aria-label="設定置頂">
                            <i class="fas fa-sort-amount-up" aria-hidden="true"></i> 置頂
                        </button>
                    @endif

                    @if($post->inbox)
                        <a href="{{ route('posts.inbox',$post->id) }}" class="btn btn-secondary btn-sm ml-1" onclick="return confirm('確定取消常駐公告？')" title="取消常駐公告" aria-label="取消常駐公告">
                            <i class="fas fa-inbox" aria-hidden="true"></i> 取消常駐
                        </a>
                    @else
                        <a href="{{ route('posts.inbox',$post->id) }}" class="btn btn-outline-warning btn-sm ml-1" onclick="return confirm('確定放進常駐公告區塊？')" title="放入常駐公告區塊" aria-label="放入常駐公告區塊">
                            <i class="fas fa-inbox" aria-hidden="true"></i> 常駐
                        </a>
                    @endif
                @endif

                @if(auth()->user()->id == $post->user_id or auth()->user()->admin == 1)
                    <a href="{{ route('posts.edit',$post->id) }}" class="btn btn-outline-primary btn-sm ml-1" title="修改公告" aria-label="修改公告">
                        <i class="fas fa-edit" aria-hidden="true"></i> 修改
                    </a>
                    <button type="button" class="btn btn-danger btn-sm ml-1" onclick="if(confirm('確定刪除？')) document.getElementById('delete').submit();" title="刪除公告" aria-label="刪除公告">
                        <i class="fas fa-trash" aria-hidden="true"></i> 刪除
                    </button>
                    {{ Form::open(['route' => ['posts.destroy',$post->id], 'method' => 'DELETE', 'id' => 'delete', 'style' => 'display:none;']) }}
                    {{ Form::close() }}
                @endif
            @endauth
        </div>

        <hr>

        <!-- 張貼時間與點閱數 -->
        <p>
            張貼日期： {{ $post->created_at }} 
            點閱：
            <!-- 無障礙檢測修正：明確標示檔名、副檔名 (txt) 及另開新視窗資訊 -->
            <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/'.$post->id.'.txt') }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               title="查看點閱記錄檔：{{ $post->id }}.txt (副檔名：txt，另開新視窗)"
               aria-label="查看點閱記錄檔：{{ $post->id }}.txt (副檔名：txt，另開新視窗)">
                {{ $post->views }}
                <span class="small text-muted">({{ $post->id }}.txt)</span>
                <i class="fas fa-external-link-alt small" aria-hidden="true"></i>
                <span class="sr-only">(副檔名：txt，另開新視窗)</span>
            </a>
        </p>

        <hr>

        <!-- 標題圖片 -->
        @if($can_see && !empty($post->title_image))                    
            <img class="img-fluid rounded mb-3" src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" alt="{{ $post->title }} 的代表圖片">
            <hr>                    
        @endif            

        <!-- 公告內文區塊：無障礙 2.1.1 修正，加上 tabindex="0" 讓鍵盤可停留並使用方向鍵捲動 -->
        <article class="p-3 mb-4 rounded border" style="background-color: #ffffff; border-style: dotted !important; border-color: #939699 !important;" tabindex="0" aria-label="{{ $post->title }} 公告詳細內容區">
            @if($can_see)
                <div class="post-content-body">                                                                                                                                             
                    {!! sanitize_accessibility_html($post->content) !!}
                </div>
            @else
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                    <p class="text-danger font-weight-bold mb-0">
                        <i class="fas fa-lock" aria-hidden="true"></i> [ 內部公告 ] 請登入後瀏覽！
                    </p>                                                                                              
                @endif
            @endif
        </article>

        <!-- 相關照片區塊 -->
        @if(!empty($photos) and $can_see)
            <section class="card my-4" aria-label="相關照片區塊">
                <h2 class="card-header h5">相關照片</h2>
                <div class="card-body">
                    <div class="row">
                    @foreach($photos as $k => $v)
                        <div class="col-lg-3 col-md-4 col-6 mb-3">
                            <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="venobox d-block" data-gall="gall1" title="放大檢視相關照片第 {{ $k + 1 }} 張" aria-label="放大檢視相關照片第 {{ $k + 1 }} 張">
                                <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" alt="相關照片第 {{ $k + 1 }} 張" class="img-thumbnail w-100">
                            </a>
                        </div>
                    @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- 附件下載區塊 -->
        @if(!empty($files) and $can_see)                    
            <section class="card my-4" aria-label="附件下載區塊">
                <h2 class="card-header h5">附件下載</h2>
                <div class="card-body">
                @foreach($files as $k => $v)
                    <?php 
                        $ext = pathinfo($v, PATHINFO_EXTENSION);
                    ?>
                    <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/files/'.$v) }}" 
                       class="btn btn-outline-primary btn-sm my-1" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       title="下載附件：{{ $v }} (副檔名：{{ $ext }}，另開新視窗)"
                       aria-label="下載附件：{{ $v }} (副檔名：{{ $ext }}，另開新視窗)">
                        <i class="fas fa-download" aria-hidden="true"></i> {{ $v }}
                        <span class="sr-only">(副檔名：{{ $ext }}，另開新視窗)</span>
                    </a>
                @endforeach
                </div>
            </section>                    
        @endif            
    </div>

    <!-- 側邊欄區塊 -->
    <aside class="col-lg-3" aria-label="側邊欄資訊">
        <div class="card my-4">
            <h2 class="card-header h5">近月內熱門公告</h2>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                @foreach($hot_posts as $hot_post)
                    <li class="mb-2 pb-2 border-bottom">
                        <small class="text-muted">{{ substr($hot_post->created_at,0,10) }}</small>
                        <span class="badge badge-danger ml-1" title="點閱數 {{ $hot_post->views }} 次" aria-label="點閱數 {{ $hot_post->views }} 次">{{ $hot_post->views }}</span>
                        <br>
                        <a href="{{ route('posts.show',$hot_post->id) }}" class="font-weight-normal" title="閱讀公告：{{ $hot_post->title }}" aria-label="閱讀公告：{{ $hot_post->title }}">
                            {{ str_limit($hot_post->title,60) }}
                        </a>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    </aside>

</div>

<!-- 置頂日期選擇 Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="exampleModalLabel">置頂設定</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="關閉視窗">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="top_up_form" action="{{ route('posts.top_up2',$post->id) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="top_date">置頂至哪一天？<span class="text-danger">*</span></label>
                        <input type="date" name="top_date" id="top_date" class="form-control" required="required">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" onclick="return send_form();">送出置頂</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function send_form(){
        if($('#top_date').val().length === 0){
            alert('請選日期！');
            return false;
        }
        return true;
    }

    /* 無障礙 2.1.2 核心修正：VenoBox 燈箱全鍵盤控制與 Focus Trap 鎖定 */
    var triggerElement = null;
    var lightboxKeyHandler = null;

    var vb = new VenoBox({
        selector: '.venobox',
        numeration: true,
        infinigall: true,
        spinner: 'rotating-plane',
        onPostOpen: function(content, obj, gallIndex, then) {
            // 1. 記錄開啟前的觸發元件，供關閉時歸還焦點
            triggerElement = document.activeElement;

            setTimeout(function() {
                var overlay = document.querySelector('.vbox-overlay');
                if (!overlay) return;

                // 2. 動態強制修復並取得當前畫面上所有可見的可聚焦按鈕
                function getFocusables() {
                    var allControls = overlay.querySelectorAll('.vbox-close, .vbox-prev, .vbox-next, .vbox-left, .vbox-right');
                    
                    // 強制為 DOM 內部所有控制按鈕補上 Focus 關鍵屬性
                    allControls.forEach(function(el) {
                        el.setAttribute('tabindex', '0');
                        el.setAttribute('role', 'button');
                        if (el.classList.contains('vbox-close')) {
                            el.setAttribute('aria-label', '關閉懸浮視窗');
                            el.setAttribute('title', '關閉懸浮視窗');
                        } else if (el.classList.contains('vbox-prev') || el.classList.contains('vbox-left')) {
                            el.setAttribute('aria-label', '檢視上一張照片');
                            el.setAttribute('title', '檢視上一張照片');
                        } else if (el.classList.contains('vbox-next') || el.classList.contains('vbox-right')) {
                            el.setAttribute('aria-label', '檢視下一張照片');
                            el.setAttribute('title', '檢視下一張照片');
                        }
                    });

                    // 依 HTML DOM 順序選取潛在焦點
                    var candidates = overlay.querySelectorAll('.vbox-close, .vbox-next, .vbox-prev, .vbox-left, .vbox-right, a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])');
                    
                    return Array.from(candidates).filter(function(el) {
                        var style = window.getComputedStyle(el);
                        return el.offsetWidth > 0 && 
                               el.offsetHeight > 0 && 
                               style.display !== 'none' && 
                               style.visibility !== 'hidden' && 
                               style.opacity !== '0';
                    });
                }

                // 3. 預設將焦點定在第一個可聚焦按鈕（關閉按鈕）
                var focusables = getFocusables();
                if (focusables.length > 0) {
                    focusables[0].focus();
                }

                // 4. 一體化鍵盤控制（包含 Tab 鎖定循環、Enter/Space 觸發、Esc 關閉、左右方向鍵）
                lightboxKeyHandler = function(e) {
                    var currentOverlay = document.querySelector('.vbox-overlay');
                    if (!currentOverlay || !document.body.contains(currentOverlay)) return;

                    var key = e.key;
                    var keyCode = e.keyCode;
                    var activeEl = document.activeElement;

                    // A. Tab 鍵 Focus Trap (嚴格依據 DOM 順序於燈箱內部循環)
                    if (key === 'Tab' || keyCode === 9) {
                        var items = getFocusables();
                        if (items.length === 0) return;

                        var firstItem = items[0];
                        var lastItem = items[items.length - 1];

                        if (e.shiftKey) { // Shift + Tab (反向移動)
                            if (activeEl === firstItem || !currentOverlay.contains(activeEl)) {
                                e.preventDefault();
                                lastItem.focus();
                            }
                        } else { // Tab (正向移動)
                            if (activeEl === lastItem || !currentOverlay.contains(activeEl)) {
                                e.preventDefault();
                                firstItem.focus();
                            }
                        }
                    }

                    // B. Enter (13) 或 Space (32) 鍵觸發對應動作，並維持焦點不掉落
                    else if (key === 'Enter' || key === ' ' || keyCode === 13 || keyCode === 32) {
                        if (activeEl) {
                            if (activeEl.classList.contains('vbox-close') || activeEl.closest('.vbox-close')) {
                                e.preventDefault();
                                vb.close();
                            } else if (activeEl.classList.contains('vbox-prev') || activeEl.classList.contains('vbox-left') || activeEl.closest('.vbox-prev, .vbox-left')) {
                                e.preventDefault();
                                vb.prev();
                                setTimeout(function() {
                                    var p = document.querySelector('.vbox-prev, .vbox-left');
                                    if (p) p.focus();
                                }, 80);
                            } else if (activeEl.classList.contains('vbox-next') || activeEl.classList.contains('vbox-right') || activeEl.closest('.vbox-next, .vbox-right')) {
                                e.preventDefault();
                                vb.next();
                                setTimeout(function() {
                                    var n = document.querySelector('.vbox-next, .vbox-right');
                                    if (n) n.focus();
                                }, 80);
                            }
                        }
                    }

                    // C. Escape (27) 關閉懸浮視窗
                    else if (key === 'Escape' || keyCode === 27) {
                        e.preventDefault();
                        vb.close();
                    }

                    // D. 左右方向鍵 (← / →) 直接切換照片
                    else if (key === 'ArrowLeft' || keyCode === 37) {
                        e.preventDefault();
                        vb.prev();
                        setTimeout(function() {
                            var p = document.querySelector('.vbox-prev, .vbox-left');
                            if (p) p.focus();
                        }, 80);
                    } else if (key === 'ArrowRight' || keyCode === 39) {
                        e.preventDefault();
                        vb.next();
                        setTimeout(function() {
                            var n = document.querySelector('.vbox-next, .vbox-right');
                            if (n) n.focus();
                        }, 80);
                    }
                };

                document.addEventListener('keydown', lightboxKeyHandler);
            }, 100);
        },
        onPostClose: function() {
            // 5. 關閉燈箱時解除鍵盤監聽，並將焦點歸還給原本點擊的圖片連結
            if (lightboxKeyHandler) {
                document.removeEventListener('keydown', lightboxKeyHandler);
                lightboxKeyHandler = null;
            }
            if (triggerElement && typeof triggerElement.focus === 'function') {
                triggerElement.focus();
            }
        }
    });
</script>
@endsection