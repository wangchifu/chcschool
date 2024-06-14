@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '新增圖片連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>新增圖片連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('photo_links.index') }}">圖片連結</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增圖片連結</li>
                </ol>
            </nav>
            {{ Form::open(['route' => 'photo_links.store', 'method' => 'POST','id'=>'this_form','files'=>true]) }}
            <div class="card my-4">
                <h3 class="card-header">連結資料</h3>
                <div class="card-body">
                    <div class="form-group">
                        <label for="photo_type_id">類別</label>
                        <select name="photo_type_id" class="form-control" id="photo_type_id" onclick="change_order_by()"">
                            <option value="0">不分類</option>
                            @foreach($photo_types as $photo_type)
                                <option value="{{ $photo_type->id }}">{{ $photo_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <script>            
                            function change_order_by(){
                                var id = $('#photo_type_id').find(":selected").val();
                                let arr = new Array()
                                @foreach($new_link_order_by as $k=>$v)
                                    arr[{{ $k }}] = {{ $v }};
                                @endforeach
                                
                                $('#order_by').val(arr[id]) ;
                            }
                                
                        </script>
                        <label for="order_by">排序*</label>
                        {{ Form::number('order_by',reset($new_link_order_by),['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                    </div>
                    <div class="form-group">
                        <label for="image">代表圖片*</label>
                        <input type="file" name="image" id="image" required class="form-control">
                    </div>                    
                    <div class="form-group">
                        <label for="name">名稱*</label>
                        {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                    </div>
                    <div class="form-group">
                        <label for="url">網址*</label>
                        {{ Form::text('url',null,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    @include('layouts.errors')
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
