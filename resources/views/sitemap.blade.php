@extends('layouts.master') {{-- 繼承你的主要版型 --}}

@section('nav_sitemap_active', 'active')

@section('content')
    {!! $sitemap !!}
@endsection
