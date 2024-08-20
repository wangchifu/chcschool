@extends('layouts.master')

@section('title', '校園跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園跑馬燈
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.setup') }}">管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.index') }}">列表</a>
                </li>
            </ul>
            <br>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        跑馬燈列表
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('school_marquee.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增跑馬燈</a>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th>id</th>
                            <th>標題</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                            <th>上架者</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;$j=0; ?>
                        @foreach($school_marquees as $school_marquee)
                            <tr>
                                <td>
                                    {{ $school_marquee->id }}
                                </td>
                                <td>
                                    {{ $school_marquee->title }}
                                </td>
                                <td>
                                    {{ $school_marquee->start_date }}
                                </td>
                                <td>
                                    {{ $school_marquee->stop_date }}
                                </td>
                                <td>
                                    {{ $school_marquee->user->name }}
                                </td>
                                <td>
                                    @if($school_marquee->user_id == auth()->user()->id)                                        
                                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $school_marquee->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                                    @endif
                                </td>
                            </tr>
                            {{ Form::open(['route' => ['school_marquee.destroy',$school_marquee->id], 'method' => 'DELETE','id'=>'delete'.$school_marquee->id,'onsubmit'=>'return false;']) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
