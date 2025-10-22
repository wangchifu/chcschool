@extends('layouts.master_clean')

@section('title', '批次刪除公告 | ')

@section('content')
    <h3 class="text-danger text-center">強烈注意！！這是大量刪除公告，做錯了沒人可以救你！！</h3>
    {{ Form::open(['route' => ['setups.batch_delete'], 'method' => 'delete']) }}
    <div class="form-group">
        <label for="order_by">要從哪一篇文章的公告開始刪到最前面？</label>
        <br>
        <br>
        <img src="{{ asset('images/post_no.png') }}">
        <br>
        <br>
        <input type="number" name="post_no" class="form-control" placeholder="請填公告的編號" required>
    </div>
    <div class="form-group">
        <label for="insite"><strong class="text-danger">請選擇要刪除的公告類別*</strong></label>
        {{ Form::select('insite', $types,null, ['id' => 'insite', 'class' => 'form-control']) }}
    </div>
    <br>
    <br>
    <br>
    <br>
    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('真的確定嗎？太大量的話，請等一下，不要再亂按了')"><i class="fas fa-trash-alt"></i> 確定不能挽回的刪除大量公告</button>
    {{ Form::close() }}
@endsection
