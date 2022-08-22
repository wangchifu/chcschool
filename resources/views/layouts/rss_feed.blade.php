<?php
$rss_feeds = \App\RssFeed::all();
?>
@foreach($rss_feeds as $rss_feed)
<?php
    $rss = new DOMDocument();   
	if($rss->load($rss_feed->url)){

    }else{
        dd('123');
    }
    
	$feeds = array();
    $i=1;
	foreach ($rss->getElementsByTagName('item') as $node) {
        if($i>$rss_feed->num) break;
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			//'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			);
		array_push($feeds, $item);
        $i++;
	}
?>
    @if($rss_feed->type==1)
    <button type="button" class="btn btn-primary">
        {{ $rss_feed->title }} <span class="badge badge-light">{{ $rss_feed->num }}</span>
    </button>
    <div class="list-group">
        @foreach($feeds as $k=>$v)
        <a href="{{ $v['link'] }}" target="_blank" class="list-group-item list-group-item-action">{{ $v['title'] }}</a>
        @endforeach
    </div>
    @endif
    @if($rss_feed->type==2)
    <button type="button" class="btn btn-primary">
        {{ $rss_feed->title }} <span class="badge badge-light">{{ $rss_feed->num }}</span>
    </button>
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