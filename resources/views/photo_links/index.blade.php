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
        </div>
        <div class="col-md-4">
            <h2>基本設定</h2>
                <table class="table table-striped" style="word-break:break-all;">
                    <thead class="thead-light">
                        <tr>
                            <th>顯示圖片的數目</th>
                            <th>已建類別</th>
                            <th>新建類別</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $setup = \App\Setup::first(); ?>
                        <tr>
                            <td>
                                <form action="{{ route('setups.photo_link_number') }}" method="post">
                                    @csrf
                                {{ Form::number('photo_link_number',$setup->photo_link_number,['id'=>'photo_link_number','class' => 'form-control', 'placeholder' => '6的倍數為佳']) }}
                                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">修改</button>    
                            </form>
                            </td>
                            <td>
                                @foreach($photo_types as $photo_type)
                                <span class="badge badge-primary">{{ $photo_type->order_by }}.{{ $photo_type->name }}</span> <a href="{{ route('photo_links.type_delete',$photo_type->id) }}" onclick="return confirm('確定刪除？底下這個分類的連結，將改為「不分類」喔！')"><i class="fas fa-times-circle text-danger"></i></a><br>
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('photo_links.type_store') }}" method="post">
                                @csrf
                                {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                                {{ Form::number('order_by',null,['id'=>'order_by','class' => 'form-control','required'=>'required', 'placeholder' => '排序']) }}
                                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">新增</button>
                                </form>
                            </td>
                        </tr>
                        </form>
                    </tbody>
                </table>
        </div>
        <div class="col-md-7">
            <h2>連結設定</h2>
                <table class="table table-striped" style="word-break:break-all;">
                    <thead class="thead-light">
                    <tr>
                        <th>排序</th>
                        <th>代表圖片</th>
                        <th>類別</th>
                        <th>名稱+網址</th>
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
                            <input type="file" name="image" id="image" required>
                        </td>
                        <td>
                            <select name="photo_type_id" class="form-control">
                                <option value="">不分類</option>
                                @foreach($photo_types as $photo_type)
                                    <option value="{{ $photo_type->id }}">{{ $photo_type->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                            <br>
                            {{ Form::text('url',null,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
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
                                <?php
                                    $school_code = school_code();
                                    $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                                ?>
                                <a href="{{ $photo_link->url }}" target="_blank"><img src="{{ asset($img) }}" height="50"></a>
                            </td>
                            <td>
                                <?php $photo_type_id = ($photo_link->photo_type_id)?$photo_link->photo_type_id:0; ?>
                                {{ $photo_type_array[$photo_type_id] }}
                            </td>
                            <td>
                                <a href="{{ $photo_link->url }}" target="_blank">{{ $photo_link->name }}</a>
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
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=300');
        }

        var validator = $("#this_form1").validate();
    </script>
@endsection
