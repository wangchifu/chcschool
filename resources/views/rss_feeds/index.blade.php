@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', 'RSS訊息 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                RSS訊息
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">RSS訊息</li>
                </ol>
            </nav>
            <h3>RSS 網站資料</h3>
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>
                        標題
                    </th>
                    <th>
                        網址
                    </th>
                    <th>
                        類別
                    </th>
                    <th>
                        最多顯示幾則
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <form action="{{ route('rss_feeds.store') }}" method="post">
                    @csrf
                    <td>
                        <input type="text" class="form-control" name="title" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="url" required>
                    </td>
                    <td>
                        <select name="type" class="form-control">
                            <option value="1">條列式標題</option>
                            <option value="2">圖片式描述</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="num" value="12" required>
                    </td>
                    <td>
                        <button class="btn btn-success btn-s" onclick="return confirm('確定嗎？')">送出</button>
                    </td>
                </form>
                </tr>
                @foreach($rss_feeds as $rss_feed)
                <tr>
                    <td>
                        {{ $rss_feed->title }}
                    </td>
                    <td>
                        <a href="{{ $rss_feed->url }}" target="_blank">連結</a> 
                    </td>
                    <td>
                        @if($rss_feed->type==1)
                        條列式標題
                        @endif
                        @if($rss_feed->type==2)
                        圖片式描述
                        @endif
                    </td>
                    <td>
                        {{ $rss_feed->num }}
                    </td>
                    <td>
                        <a href="{{ route('rss_feeds.destory',$rss_feed->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除嗎？')">刪除</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
