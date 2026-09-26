@extends('layouts.master')

@section('nav_home_active', 'active')

@section('in_head')
    <style>
        /* 無障礙輪播控制按鈕：懸浮於右上角，不佔用版面高度也不與左右箭頭重疊 */
        .carousel-accessibility-control {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 30; /* 高於 Bootstrap 預設控制箭頭 (z-index: 1) 與字幕 (z-index: 10) */
        }
        .carousel-accessibility-control .btn-pause {
            background-color: rgba(0, 0, 0, 0.85);
            color: #ffffff !important;
            border: 1px solid #ffffff;
            padding: 4px 12px;
            font-size: 0.85rem;
            border-radius: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .carousel-accessibility-control .btn-pause:hover {
            background-color: #000000;
        }
        .carousel-accessibility-control .btn-pause:focus-visible,
        .carousel-accessibility-control .btn-pause:focus {
            outline: 3px solid #ffc107 !important;
            outline-offset: 2px !important;
        }

        @if($setup->title_image_style==2)
            .carousel-fade .carousel-inner .carousel-item {
                opacity: 0;
                transition-property: opacity;
                transition-duration: 1s;
                transition-timing-function: ease;
            }

            .carousel-fade .carousel-inner .active {
                opacity: 1;
            }

            .carousel-fade .carousel-inner .carousel-item-next,
            .carousel-fade .carousel-inner .carousel-item-prev,
            .carousel-fade .carousel-inner .carousel-item.active,
            .carousel-fade .carousel-inner .active.carousel-item-left,
            .carousel-fade .carousel-inner .active.carousel-item-right {
                transform: translateX(0);
                -webkit-transform: translateX(0);
                -ms-transform: translateX(0);
            }
        @endif
    </style>
@endsection

{{-- 無障礙 2.4.1 跳過區塊按鈕：採用 Bootstrap 4 原生 sr-only sr-only-focusable --}}
@section('skip_link')
    <a href="#main-content" class="sr-only sr-only-focusable btn btn-dark position-fixed" style="top: 10px; left: 10px; z-index: 99999;">跳到主要內容區塊</a>
@endsection

@section('top_image')
    {{-- 備用區：採用 Bootstrap 4 原生 sr-only sr-only-focusable --}}
    <a href="#main-content" class="sr-only sr-only-focusable btn btn-dark position-fixed" style="top: 10px; left: 10px; z-index: 99999;">跳到主要內容區塊</a>

    @if($setup->title_image)
        @if(!empty($photo_data))
            <?php $carousel_fade =($setup->title_image_style ==2 )?"carousel-fade":""; ?>
            <div id="carouselExampleIndicators" class="carousel slide {{ $carousel_fade }} position-relative" data-ride="carousel" role="region" aria-label="焦點新聞輪播圖">
                
                <!-- 無障礙檢測修正：獨立置於右上角半透明按鈕，不擋住左右按鍵 -->
                <div class="carousel-accessibility-control">
                    <button type="button" id="carouselToggleBtn" class="btn btn-pause" aria-label="暫停輪播圖片" aria-pressed="false">
                        <i class="fas fa-pause me-1" aria-hidden="true"></i> <span>暫停輪播</span>
                    </button>
                </div>

                <ol class="carousel-indicators">
                    <?php $n=0; ?>
                    @foreach($photo_data as $k1=>$v1)
                        @foreach($v1 as $k2=>$v2)
                        <?php $active = ($n==0)?"active":""; ?>
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $n }}" class="{{ $active }}" aria-label="切換至第 {{ $n + 1 }} 張投影片"></li>
                        <?php $n++; ?>
                        @endforeach
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    <?php $n=0; ?>
                    @foreach($photo_data as $k1=>$v1)
                        @foreach($v1 as $k2=>$v2)
                            <?php 
                                $active = ($n==0)?"active":""; 
                                $img_alt = !empty($v2['title']) ? $v2['title'] : (!empty($v2['desc']) ? $v2['desc'] : '橫幅圖片 '.$k1);
                            ?>
                            <div class="carousel-item {{ $active }}">
                                @if($v2['link'] != null)
                                    <a href="{{ $v2['link'] }}" target="_blank" title="{{ $img_alt }} (另開新視窗)">
                                        <img class="d-block w-100" src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" alt="{{ $img_alt }}">
                                    </a>
                                @else
                                    <img class="d-block w-100" src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" alt="{{ $img_alt }}">
                                @endif
                                <div class="carousel-caption d-none d-md-block">
                                    @if($v2['title'] != null)
                                        <p class="h3 font-weight-bold">{{ $v2['title'] }}</p>
                                    @endif
                                    @if($v2['desc'] != null)
                                        <p><strong>{{ $v2['desc'] }}</strong></p>
                                    @endif
                                </div>
                            </div>
                            <?php $n++; ?>
                        @endforeach
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev" aria-label="上一張投影片">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">上一張投影片</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next" aria-label="下一張投影片">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">下一張投影片</span>
                </a>
            </div>
        @endif
    @endif
@endsection

@section('content')
    <!-- 無障礙 2.4.1 核心修正：主要內容區容器與焦點接應錨點 -->
    <main id="main-content" style="outline: none;">
        <!-- 精確接收 Focus 的隱藏錨點 -->
        <a id="main-content-target" tabindex="-1" style="outline: none; position: absolute;"></a>

        <h1 class="sr-only">{{ $setup->site_name }} - 首頁主要內容區</h1>

        <link href="{{ asset('css/block_style.css') }}" rel="stylesheet">
        <?php $module_setup = get_module_setup(); ?>
        @if(isset($module_setup['校園跑馬燈']))
            <?php
                $school_marquee_width = (empty($setup->school_marquee_width))?"12":$setup->school_marquee_width;
                $school_marquee_color = (empty($setup->school_marquee_color))?"warning":$setup->school_marquee_color;
                $school_marquee_behavior = (empty($setup->school_marquee_behavior))?"scroll":$setup->school_marquee_behavior;
                $school_marquee_direction = (empty($setup->school_marquee_direction))?"up":$setup->school_marquee_direction;
                $school_marquee_scrollamount = (empty($setup->school_marquee_scrollamount))?"2":$setup->school_marquee_scrollamount;
            ?>
            @if($school_marquees->count()>0)
                <div class="row justify-content-center">
                    <div class="col-lg-{{ $school_marquee_width }}">
                        <div class="alert alert-{{ $school_marquee_color }} p-1" style="margin-top: -15px; overflow: hidden;" role="region" aria-label="最新消息跑馬燈">
                            
                            <div class="marquee-wrapper" id="marquee-container" tabindex="0"
                                style="height: 25px; overflow: hidden; position: relative; background: transparent;">                            
                                
                                <div class="marquee-inner" id="marquee-content">
                                    @foreach($school_marquees as$school_marquee)
                                        <span class="marquee-item" style="margin-right: 50px; display: inline-block;">
                                            <span aria-hidden="true">📣</span> {{ $school_marquee->title }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <div class="row justify-content-center">
            @foreach($setup_cols as$setup_col)
                <div class="col-lg-{{ $setup_col->num }}">
                    @foreach($blocks[$setup_col->id] as$block)
                        <?php
                            if(!is_null($block->block_color)){
                                $block_color = explode(',',$block->block_color);
                            }else{
                                $block_color[0] = "original-block";
                                $block_color[1] = "original-title";
                            }
                            $rounded = ($block->disable_block_line == 1)?"rounded":null;
                        ?>

                        @if($block->title == "榮譽榜跑馬燈")                        
                            <div class="table-responsive">
                                <div>                                
                                    @include('layouts.marquee')
                                </div>
                            </div>
                        @else
                        @if($block->disable_block_line != 1)
                        <div class="shadow rounded {{ $block_color[0] }}">
                        @endif
                            @if($block->block_position != "disable")
                            <div class="{{ $block_color[1] }} {{$rounded }}">
                                <?php
                                    $title = (empty($block->new_title))?$block->title:$block->new_title;
                                    $title=str_replace('(系統區塊)','',$title);$title = str_replace_last("_圖文版","",$title);$block_position = ($block->block_position==null)?"text-left":$block->block_position;
                                    if($block->block_position=="disable") $block_position = null;
                                ?>
                                <h2 class="h5 {{ $block_position }}">
                                    @if($block_position) 
                                        {{ $title }}
                                    @endif
                                    @auth
                                        @if(auth()->user()->admin==1)
                                            <div style="float: right;padding-right:10px">
                                                <a href="javascript:open_window('{{ route('setups.edit_block',$block->id) }}','新視窗')" title="編輯區塊：{{ $title }}" aria-label="編輯區塊：{{ $title }}">📝</a>
                                            </div>
                                        @endif
                                    @endauth
                                </h2>
                            </div>
                            @endif
                            <div class="content2" id="block{{ $block->id }}" style="margin-bottom: 5px;">
                                <div class="table-responsive">
                                @if($block->title == "最新公告(系統區塊)")
                                    @include('layouts.news')
                                @elseif($block->title == "彰化空汙旗(系統區塊)")
                                    @include('layouts.chc_air')
                                @elseif($block->title == "樹狀目錄(系統區塊)")
                                    @include('layouts.dtree')
                                @elseif($block->title == "圖片連結(系統區塊)")
                                    @include('layouts.photo_link')
                                @elseif($block->title == "分類公告(系統區塊)")
                                    @include('layouts.post_type')
                                @elseif($block->title == "分類公告_圖文版(系統區塊)")
                                    @include('layouts.post_type2')
                                @elseif($block->title == "校園部落格(系統區塊)")
                                    @include('layouts.blog')
                                @elseif($block->title == "今日餐點1(系統區塊)")
                                    @include('layouts.lunch_today1')
                                @elseif($block->title == "今日餐點2(系統區塊)")
                                    @include('layouts.lunch_today2')
                                @elseif($block->title == "今日餐點3(系統區塊)")
                                    @include('layouts.lunch_today3')
                                @elseif($block->title == "今日餐點4(系統區塊)")
                                    @include('layouts.lunch_today4')
                                @elseif($block->title == "校務月曆(系統區塊)")
                                    @include('layouts.monthly_calendar')
                                @elseif($block->title == "教室預約(系統區塊)")
                                    @include('layouts.classroom_order')
                                @elseif($block->title == "RSS訊息(系統區塊)")
                                    @include('layouts.rss_feed')                           
                                @elseif($block->title == "借用狀態(系統區塊)")
                                    @include('layouts.lend_list')
                                @elseif($block->title == "常駐公告(系統區塊)")
                                    @include('layouts.inbox_posts')
                                @elseif($block->title == "待修通報(系統區塊)")
                                    @include('layouts.fix')
                                @elseif($block->title == "搜尋本站(系統區塊)")
                                    @include('layouts.search_site')
                                @else
                                    {!! $block->content !!}
                                @endif
                            </div>
                            </div>
                        @if($block->disable_block_line != 1)
                        </div>
                        @endif
                        @endif
                    @endforeach
                </div>
            @endforeach

        </div>
    </main>

    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=800');
        }

        /* 無障礙 2.4.1 核心 JS 控制：修正鍵盤焦點無法直接跳入主要內容區問題 */
        $(document).ready(function() {$(document).on('click', 'a[href="#main-content"]', function(e) {
                e.preventDefault();
                var $target =$('#main-content-target');
                if ($target.length) {
                    $target.attr('tabindex', '-1').focus();$('html, body').animate({
                        scrollTop: $('#main-content').offset().top - 20
                    }, 100);
                }
            });

            /* 無障礙控制：輪播圖暫停 / 播放 JS 控制器 */
            var $carousel =$('#carouselExampleIndicators');
            var $toggleBtn =$('#carouselToggleBtn');
            var isPaused = false;

            if ($carousel.length && $toggleBtn.length) {$toggleBtn.on('click', function() {
                    if (!isPaused) {
                        $carousel.carousel('pause');
                        isPaused = true;
                        $(this).attr('aria-pressed', 'true')
                               .attr('aria-label', '播放輪播圖片')
                               .html('<i class="fas fa-play me-1" aria-hidden="true"></i> <span>播放輪播</span>');
                    } else {
                        $carousel.carousel('cycle');
                        isPaused = false;
                        $(this).attr('aria-pressed', 'false')
                               .attr('aria-label', '暫停輪播圖片')
                               .html('<i class="fas fa-pause me-1" aria-hidden="true"></i> <span>暫停輪播</span>');
                    }
                });

                // 當鍵盤焦點進出輪播區時自動暫停/恢復輪播
                $carousel.on('focusin', function() {$carousel.carousel('pause');
                }).on('focusout', function() {
                    if (!isPaused) {
                        $carousel.carousel('cycle');
                    }
                });
            }
        });
    </script>
@endsection

@section('footer')
    @if(!empty($setup->footer))
        <style>
            #footer{background-color:#f8f9fa;}
            #footer_bottom{background-color: #6c757d; color: #ffffff;}
            #footer_bottom a{color: #ffffff; text-decoration: underline;}
        </style>
        <footer class="font-small py-4" id="footer" role="contentinfo" aria-label="頁尾資訊區">
            <div class="container-fluid text-center text-md-left">
                    <div class="row justify-content-center">
                        <div class="col-md-11">                            
                            @auth
                                @if(auth()->user()->admin==1)  
                                    <div style="float: right;">
                                        <a href="javascript:open_window('{{ route('setups.edit_footer') }}','新視窗')" title="編輯頁尾內容" aria-label="編輯頁尾內容">📝</a>
                                    </div>
                                @endif
                            @endauth
                            {!! enhance_content_accessibility(fix_empty_links(clean_font_size_units($setup->footer))) !!}
                        </div>
                    </div>
            </div>
        </footer>
    @endif
    @if($setup->disable_right==null)
        <div class="footer-copyright text-center py-3" id="footer_bottom">
            {{ date('Y') }} Copyright © <a href="{{ route('index','index') }}" title="返回網站首頁">{{ $setup->site_name }}</a> 訪客人次:{{ $setup->views }} 訪客IP：{{ GetIP() }}
        </div>
    @endif

    <?php $admin = \App\User::where('username','admin')->first(); ?>
    @auth
        @if(auth()->user()->admin==1)
            @if(Hash::check('demo1234', $admin->password))
            <script>
                $(document).ready(function(){$("#myModal").modal('show');
                });
            </script>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-danger" id="myModalLabel">嚴重資安危險!</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="關閉對話視窗">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        請你立即變更本機帳號 admin 的密碼，不得使用預設密碼。若未變更而發生資安事件，貴校須負相關責任！
                        <br>步驟為：
                        <br>1.本機登入 admin 帳號
                        <br>2.右上角 <i class="fas fa-user" aria-hidden="true"></i> 符號按一下，選擇「更改密碼」
                        <br>3.輸入舊密碼，與兩次新密碼，「送出」完成變更。
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">我知道了</button>
                    </div>
                  </div>
                </div>
            </div>
            @endif
        @endif
    @endauth    
@endsection

@if(isset($module_setup['校園跑馬燈']))
    @if($school_marquees->count()>0)
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const behavior = "{{ $school_marquee_behavior }}";     
            const direction = "{{ $school_marquee_direction }}";   
            const amount = parseInt("{{ $school_marquee_scrollamount }}") || 6;

            const container = document.getElementById('marquee-container');
            const content = document.getElementById('marquee-content');

            if (container && content) {
                content.style.position = 'absolute';
                content.style.display = 'flex';
                content.style.whiteSpace = 'nowrap';
                
                if (direction === 'up' || direction === 'down') {
                    content.style.flexDirection = 'column';
                }

                const contentWidth = content.offsetWidth;
                const containerWidth = container.offsetWidth;
                const contentHeight = content.offsetHeight;
                const containerHeight = container.offsetHeight;

                let keyframes = '';
                if (direction === 'left') {
                    keyframes = `@keyframes marqueeMove { 
                        0% { transform: translateX(${containerWidth}px); } 
                        100% { transform: translateX(-${contentWidth}px); } 
                    }`;
                } else if (direction === 'right') {
                    keyframes = `@keyframes marqueeMove { 
                        0% { transform: translateX(-${contentWidth}px); } 
                        100% { transform: translateX(${containerWidth}px); } 
                    }`;
                } else if (direction === 'up') {
                    keyframes = `@keyframes marqueeMove { 
                        0% { transform: translateY(${containerHeight}px); } 
                        100% { transform: translateY(-${contentHeight}px); } 
                    }`;
                } else if (direction === 'down') {
                    keyframes = `@keyframes marqueeMove { 
                        0% { transform: translateY(-${containerHeight}px); } 
                        100% { transform: translateY(${containerHeight}px); } 
                    }`;
                }

                const style = document.createElement('style');
                style.innerHTML = keyframes;
                document.head.appendChild(style);

                const duration = (direction === 'left' || direction === 'right') 
                                ? (contentWidth + containerWidth) / (amount * 10) 
                                : (contentHeight + containerHeight) / (amount * 5);

                content.style.animation = `marqueeMove ${duration}s linear infinite`;

                if (behavior === 'slide') {
                    content.style.animationIterationCount = '1';
                    content.style.animationFillMode = 'forwards';
                } else if (behavior === 'alternate') {
                    content.style.animationDirection = 'alternate';
                }

                container.onmouseover = () => content.style.animationPlayState = 'paused';
                container.onmouseout = () => content.style.animationPlayState = 'running';
                container.onfocusin = () => content.style.animationPlayState = 'paused';
                container.onfocusout = () => content.style.animationPlayState = 'running';
            }
        });
        </script>
    @endif
@endif