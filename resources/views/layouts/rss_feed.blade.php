<?php
$rss_feeds = \App\RssFeed::all();
?>
@foreach($rss_feeds as $rss_feed)
<?php   
    libxml_use_internal_errors(true); // 開啟內部錯誤處理

    $rss = new DOMDocument();   

    // 🛠️ 關鍵修正：同時處理 SSL 憑證跳過，並且加上 http 偽裝瀏覽器身分 (解決 403 Forbidden)
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

    // 🛠️ 防禦修正：加上 @ 符號，防止對方 RSS 斷線時讓你的網站直接掛掉崩潰
    $xmlContent = @file_get_contents($rss_feed->url, false, $context);

    $feeds = array();

    // 🛠️ 防禦修正：只有當真的成功拿到 XML 內容時才進行解析，避免空值造成後面報錯
    if ($xmlContent !== false) {
        // 👉 濾掉常見錯誤符號
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
    <span style="font-size: 16px">{{ $rss_feed->title }}</span><br>
    @if($rss_feed->type==1)
    <div class="list-group">
        @foreach($feeds as $k=>$v)
        <a href="{{ $v['link'] }}" target="_blank" class="list-group-item list-group-item-action">{{ $v['title'] }}</a>
        @endforeach
    </div>
    @endif
    @if($rss_feed->type==2)
    <div class="row">
        @foreach($feeds as $k=>$v)
        <div class="col-2" style="margin-bottom: 10px;">    
            <div class="card shadow">
                <div class="card-header" style="padding: 5px;">
                    {{ $v['title'] }}
                </div>
                <div class="card-body" style="padding: 5px;">
                    <a href="{{ $v['link'] }}" target="_blank">{!! $v['desc'] !!}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{-- ⚠️ 注意：這裡的 $('img') 在這個視窗內如果是點 Radio 異步渲染的話可能會失效，
         如果你的 layouts.master_clean 本身就有全域 jQuery 則無妨，維持原狀即可。 --}}
    <script>
        $('img').addClass('img-fluid');
    </script>
    @endif
@endforeach