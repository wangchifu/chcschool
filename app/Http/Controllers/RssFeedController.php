<?php

namespace App\Http\Controllers;

use App\RssFeed;
use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    public function index()
    {
        $rss_feeds = RssFeed::all();
        $data = [
            'rss_feeds' => $rss_feeds,
        ];
        return view('rss_feeds.index', $data);
    }

    public function store(Request $request)
    {
        $att = $request->all();
        RssFeed::create($att);
        return redirect()->route('rss_feeds.index');
    }

    public function destory(RssFeed $rss_feed)
    {
        $rss_feed->delete();
        return redirect()->route('rss_feeds.index');
    }
}
