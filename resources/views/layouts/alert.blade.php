@extends('layouts.master')

@section('title', '錯誤')

@section('content')
<div class="container">
  <div class="jumbotron">
    {{-- 無障礙 HM1130100C & CS2140401C：使用 h1 標題且以 rem/Bootstrap 類別取代 32px --}}
    <h1 class="display-4 text-dark h2 mb-3">Hello, 你弄錯了!</h1>
    
    <p class="lead">這是錯誤頁面，你有東西搞錯了，想想你做了什麼事情不對，然後返回再試一次吧！</p>
    <hr class="my-4">
    
    {{-- 無障礙 HM1130100C & CS2140401C：使用 h2 標題且以 rem/Bootstrap 類別取代 24px --}}
    <h2 class="h4 text-danger mb-4">
      錯誤說明：<strong>{{ $words }}</strong>
    </h2>
    
    <p class="lead">
      {{-- 無障礙 HM1240401C：補上 title 與 aria-label，圖示設定 aria-hidden --}}
      <a class="btn btn-secondary btn-lg" href="#" role="button" onclick="history.back(); return false;" title="返回上一頁" aria-label="返回上一頁">
        <i class="fas fa-backward" aria-hidden="true"></i> 返回上一頁
      </a>
    </p>
  </div>
</div>
@endsection