@extends('layouts.master')

@section('title', '錯誤')

@section('content')
<div class="container">
  <div class="jumbotron">
    <span style="font-size: 32px;" class="display-4 text-dark">Hello, 你弄錯了!</span>
    <p class="lead">這是錯誤頁面，你有東西搞錯了，想想你做了什麼事情不對，然後返回再試一次吧！</p>
    <hr class="my-4">
    <span style="font-size: 24px;" class="text-danger">錯誤說明：<strong>{{ $words }}</strong></span>
    <p class="lead">
      <a class="btn btn-secondary btn-lg" href="#" role="button" onclick="history.back()"><i class="fas fa-backward"></i> 返回上一頁</a>
    </p>
  </div>
</div>
@endsection
