@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '內容管理 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                內容管理
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    @if(empty($tag))
                        <li class="breadcrumb-item active" aria-current="page">內容列表</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">標籤「{{ $tag }}」</li>
                    @endif
                </ol>
            </nav>
            <a href="{{ route('contents.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增內容</a>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>id</th>
                    <th>權限</th>
                    <th>共編群組</th>
                    <th>標題</th>
                    <th>標籤</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr>
                        <td>{{ $content->id }}</td>
                        <td>
                            @if($content->power==null)
                                公開
                            @elseif($content->power==2)
                                校內 | 登入
                            @elseif($content->power==3)
                                登入
                            @endif
                        </td>
                        <td>
                            <?php $group_id = (empty($content->group_id))?"1":$content->group_id; ?>
                            {{ $group_array[$group_id] }}
                        </td>
                        <td><a href="{{ route('contents.show',$content->id) }}" target="_blank">{{ $content->title }}</a></td>
                        <td>
                            <?php $t = explode(',',$content->tags); ?>
                            @if(!empty($t))
                                @foreach($t as $k=>$v)
                                    @if($v)
                                    <a class="btn btn-info btn-sm" href="{{ route('contents.search',$v) }}">{{ $v }}</a> 
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('contents.edit',$content->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $content->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                        </td>
                    </tr>
                    {{ Form::open(['route' => ['contents.destroy',$content->id], 'method' => 'DELETE','id'=>'delete'.$content->id]) }}
                    {{ Form::close() }}
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
