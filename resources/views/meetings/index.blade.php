@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '會議文稿 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>會議文稿</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">會議列表</li>
                </ol>
            </nav>
            @can('create',\App\Meeting::class)
                <a href="{{ route('meetings.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增會議</a>
            @endcan
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>會議日期</th>
                    <th>會議名稱</th>
                    <th>報告人次</th>
                    <th colspan="2">動作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($meetings as $meeting)
                    <?php
                    $open_date = str_replace('-','',$meeting->open_date);
                    $die_line = (date('Ymd') > $open_date)?"1":"0";
                    ?>
                    <tr>
                        <td>{{ $meeting->open_date }} {{ get_chinese_weekday($meeting->open_date) }}</td>
                        <td>
                            @if($die_line)
                                <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-lock"></i></a>
                            @endif
                            <a href="{{ route('meetings.show',$meeting->id) }}">{{ $meeting->name }}</a>
                        </td>
                        <td>{{ $meeting->reports->count() }}</td>
                        <td>
                            @can('update',$meeting)
                                <a href="{{ route('meetings.edit',$meeting->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            @endcan
                            @can('update',$meeting)
                                <button class="btn btn-danger btn-sm" onclick="if(confirm('您確定要刪除嗎?')) document.getElementById('delete{{ $meeting->id }}').submit();else return false"><i class="fas fa-trash"></i> 刪除</button>
                                {{ Form::open(['route' => ['meetings.destroy',$meeting->id], 'method' => 'DELETE','id'=>'delete'.$meeting->id]) }}

                                {{ Form::close() }}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $meetings->links() }}
        </div>
    </div>
@endsection
