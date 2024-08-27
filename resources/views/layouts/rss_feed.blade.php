<?php
$rss_feeds = \App\RssFeed::all();
?>
@foreach($rss_feeds as $rss_feed)
<?php
    $rss = new DOMDocument();   
    $context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
    ]);
    $xmlContent = file_get_contents($rss_feed->url, false, $context);
	if($rss->loadXML($xmlContent)){

    }else{
        //dd('123');
    }
    
	$feeds = array();
    $i=1;
	foreach ($rss->getElementsByTagName('item') as $node) {
        if($i>$rss_feed->num) break;
        
        if (!empty($node->getElementsByTagName('description')->item(0)->nodeValue)) {
            $desc = $node->getElementsByTagName('description')->item(0)->nodeValue;
        }else{
            $desc = "說明";
        }
        
        $item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $desc,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			//'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			);
		array_push($feeds, $item);
        $i++;
		
	}
?>
    <h4>{{ $rss_feed->title }}</h4>
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
    <script>
        $('img').addClass('img-fluid');
    </script>
    @endif
@endforeach