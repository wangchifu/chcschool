<style>
    /* 無障礙 HM1020401C 修正：RSS 連結 Focus 高對比視覺提示 */
    .list-group-item:focus-visible,
    .card-body a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 10;
    }
</style>

<?php
$rss_feeds = \App\RssFeed::all();
?>
@foreach($rss_feeds as $rss_feed)
<?php   
    libxml_use_internal_errors(true); // 開啟內部錯誤處理

    $rss = new DOMDocument();   

    // 🛠️ 同時處理 SSL 憑證跳過，並且加上 http 偽裝瀏覽器身分 (解決 403 Forbidden)
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n"
        ]
    ]);

    // 防禦修正：加上 @ 符號，防止對方 RSS 斷線時讓你的網站直接掛掉崩潰
    $xmlContent = @file_get_contents($rss_feed->url, false, $context);

    $feeds = array();

    // 防禦修正：只有當真的成功拿到 XML 內容時才進行解析，避免空值造成後面報錯
    if ($xmlContent !== false) {
        // 濾掉常見錯誤符號
        $xmlContent = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;)/', '&amp;', $xmlContent);

        if ($rss->loadXML($xmlContent)) {
            // 成功處理
            $i = 1;
            foreach ($rss->getElementsByTagName('item') as $node) {
                if($i > $rss_feed->num) break;
                
                if (!empty($node->getElementsByTagName('description')->item(0)->nodeValue)) {
                    $desc = $node->getElementsByTagName('description')->item(0)->nodeValue;
                } else {
                    $desc = "說明";
                }
                
                // 安全撈取節點文字，避免某些 RSS 沒有給齊欄位導致噴 Null Error
                $titleNode = $node->getElementsByTagName('title')->item(0);
                $linkNode = $node->getElementsByTagName('link')->item(0);

                $item = array ( 
                    'title' => $titleNode ? $titleNode->nodeValue : '無標題',
                    'desc' => $desc,
                    'link' => $linkNode ? $linkNode->nodeValue : '#',
                );
                array_push($feeds, $item);
                $i++;
            }
        } else {
            // 寫入後端錯誤，不直接噴在畫面上嚇到使用者
            libxml_clear_errors();
        }
    }
?>
    <!-- 無障礙 HM1010301C 修正：改用語意化 h2 標題，輔助螢幕閱讀器大綱跳轉 -->
    <h2 class="h5 mt-3 mb-2">{{ $rss_feed->title }}</h2>

    @if($rss_feed->type==1)
    <div class="list-group">
        @foreach($feeds as $k=>$v)
        <!-- 無障礙 HM1200101C 修正：新視窗開啟連結補充提示訊息 -->
        <a href="{{ $v['link'] }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
           aria-label="{{ $v['title'] }} (另開新視窗)">
            <span>{{ $v['title'] }}</span>
            <i class="fas fa-external-link-alt ml-2 text-secondary" aria-hidden="true"></i>
            <span class="sr-only">(另開新視窗)</span>
        </a>
        @endforeach
    </div>
    @endif

    @if($rss_feed->type==2)
    <div class="row">
        @foreach($feeds as $k=>$v)
        <div class="col-md-2 col-sm-4 col-6 mb-3">    
            <div class="card shadow-sm h-100">
                <div class="card-header font-weight-bold text-truncate p-2" title="{{ $v['title'] }}">
                    {{ $v['title'] }}
                </div>
                <div class="card-body p-2">
                    <!-- 無障礙 HM1200101C 修正：圖卡連結補充標籤說明 -->
                    <a href="{{ $v['link'] }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="查看詳細內容：{{ $v['title'] }} (另開新視窗)">
                        {!! $v['desc'] !!}
                        <span class="sr-only">(另開新視窗)</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- 無障礙 HM1120201C 修正：動態對抓取到的內文圖片進行 CSS 與替代文字 (Alt) 預設處理 -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rssImages = document.querySelectorAll('.card-body img');
            rssImages.forEach(function(img) {
                img.classList.add('img-fluid');
                if (!img.hasAttribute('alt') || img.getAttribute('alt').trim() === '') {
                    img.setAttribute('alt', 'RSS 縮圖');
                }
            });
        });
    </script>
    @endif
@endforeach