@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '圖片連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>圖片連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">圖片連結</li>
                </ol>
            </nav>
            <div class="table-responsive">
                <table class="table table-striped" style="word-break:break-all;">
                    <thead class="thead-light">
                    <tr>
                        <th>排序</th>
                        <th>名稱<br>網址</th>
                        <th>代表圖片</th>
                        <th>動作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{ Form::open(['route' => 'photo_links.store', 'method' => 'POST','files'=>true,'id'=>'this_form']) }}
                    <tr>
                        <td>
                            {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                        </td>
                        <td>
                            {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                            <br>
                            {{ Form::text('url',null,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
                        </td>
                        <td>
                            <input type="file" name="image" id="image" required>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定新增嗎？')">
                                <i class="fas fa-save"></i> 新增連結
                            </button>
                        </td>
                    </tr>
                    {{ Form::close() }}
                    @include('layouts.errors')
                    @foreach($photo_links as $photo_link)
                        <tr>
                            <td>
                                {{ $photo_link->order_by }}
                            </td>
                            <td>
                                <a href="{{ $photo_link->url }}" target="_blank">{{ $photo_link->name }}</a>
                            </td>
                            <td>
                                <?php
                                    $school_code = school_code();
                                    $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                                ?>
                                <img src="{{ asset($img) }}" height="50">
                            </td>
                            <td>
                                <a href="javascript:open_window('{{ route('photo_links.edit',$photo_link->id) }}','新視窗')" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $photo_link->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                            </td>
                        </tr>
                        {{ Form::open(['route' => ['photo_links.destroy',$photo_link->id], 'method' => 'DELETE','id'=>'delete'.$photo_link->id,'onsubmit'=>'return false;']) }}
                        {{ Form::close() }}
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=300');
        }

        var validator = $("#this_form1").validate();
    </script>
@endsection
