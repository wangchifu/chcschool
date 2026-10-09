@extends('layouts.master') {{-- 繼承你的主要版型 --}}

@section('nav_sitemap_active', 'active')

@section('content')
    <!-- 網站導覽內文開始 -->
    {!! $sitemap !!}
    <!-- 網站導覽內文結束 -->
@endsection
