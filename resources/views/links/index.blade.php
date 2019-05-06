@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '選單連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>選單連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">選單連結</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    連結類別
                </div>
                <div class="card-body">
                        @include('layouts.errors')
                        <form action="{{ route('links.store_type') }}" method="post" id="this_form1">
                            @csrf
                        <table class="table table-striped" style="word-break:break-all;">
                        <tr>
                            <td>
                                {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                            </td>
                            <td>
                                {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="return confirm('確定？')"><i class="fas fa-plus"></i> 新增</button>
                            </td>
                        </tr>
                        </table>
                        </form>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th nowrap>名稱</th>
                            <th nowrap>排序</th>
                            <th nowrap>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($types as $type)
                            {{ Form::open(['route' => ['links.update_type',$type->id], 'method' => 'patch','id'=>'update'.$type->id]) }}
                                @csrf
                                @method('patch')
                            <tr>
                                <td>
                                    {{ Form::text('name',$type->name,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                                </td>
                                <td>
                                    {{ Form::text('order_by',$type->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                                </td>
                                <td nowrap>
                                    <button onclick="return confirm('儲存修改？')" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                                    <a href="#" class="text-danger" onclick="if(confirm('確定刪除？會一併刪除所屬連結喔！')) document.getElementById('delete{{ $type->id }}').submit();else return false;"><i class="fas fa-times-circle"></i></a>
                                </td>
                            </tr>
                            {{ Form::close() }}
                            {{ Form::open(['route' => ['links.destroy_type',$type->id], 'method' => 'DELETE','id'=>'delete'.$type->id]) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <a href="{{ route('links.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增連結</a>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>類別</th>
                    <th>名稱</th>
                    <th>網址</th>
                    <th>排序</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0;$j=0; ?>
                @foreach($links as $link)
                    <tr>
                        <td>
                            {{ $link->type->name }}
                        </td>
                        <td>
                            {{ $link->name }}
                        </td>
                        <td>
                            <a href="{{ $link->url }}" target="_blank"><i class="fas fa-globe"></i> 立即前往</a>

                        </td>
                        <td>
                            {{ $link->order_by }}
                        </td>
                        <td>
                            <a href="{{ route('links.edit',$link->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $link->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                        </td>
                    </tr>
                    {{ Form::open(['route' => ['links.destroy',$link->id], 'method' => 'DELETE','id'=>'delete'.$link->id,'onsubmit'=>'return false;']) }}
                    {{ Form::close() }}
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        var validator = $("#this_form1").validate();
    </script>
@endsection
