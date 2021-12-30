@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '顯示會議 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1>{{ $meeting->open_date }} {{ get_chinese_weekday($meeting->open_date) }} {{ $meeting->name }} <a href="{{ route('meetings.txtDown',$meeting->id) }}" class="btn btn-primary"><i class="fas fa-download"></i> 報告內容下載</a></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">會議列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">會議內容</li>
                </ol>
            </nav>
            @can('create',\App\Meeting::class)
                @if($has_report=="0" and $die_line =="0")
                    <a href="{{ route('meetings_reports.create',$meeting->id) }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增報告</a>
                @endif
            @endcan
            <hr>
            <?php $i=1; ?>
            @foreach($reports as $report)
                <?php
                //有無附件
                $school_code = school_code();
                $files = get_files(storage_path('app/privacy/'.$school_code.'/reports/'.$report->id));
                ?>
                <div class="card my-4">
                    <h3 class="card-header">
                        {{ $i }}.{{ $report->job_title }}
                        @if($has_report == "1" and $report->user_id == auth()->user()->id and $die_line =="0")
                            <a href="{{ route('meetings_reports.edit',$report->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="confirm_form('delete{{ $report->id }}','確定刪除？')"><i class="fas fa-trash"></i> 刪除</a>
                            {{ Form::open(['route' => ['meetings_reports.destroy',$report->id], 'method' => 'DELETE','id'=>'delete'.$report->id]) }}
                            {{ Form::close() }}
                        @endif
                    </h3>
                    <div class="card-body">
                        <p style="font-size: 1.2rem;">
                            <?php $content = str_replace(chr(13) . chr(10), '<br>', $report->content);?>
                            {!! $content !!}
                        </p>
                    </div>
                    @if(!empty($files))
                        <div class="card-footer">
                            附件：
                            @foreach($files as $k=>$v)
                                <?php
                                $file = $school_code."/reports/".$report->id."/".$v;
                                $file = str_replace('/','&',$file);
                                ?>
                                <a href="{{ url('file/'.$file) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> {{ $v }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <?php $i++; ?>
            @endforeach
            <hr>
        </div>
        <div class="col-lg-3">
            <div class="card my-4">
                <h5 class="card-header">相關資訊</h5>
                <div class="card-body">
                    @if($die_line == 1)
                        <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-lock"></i> 已鎖定</a>
                    @endif
                    <p class="lead">
                        報告人次：{{ $meeting->reports->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirm_form($id,$message){
            if(confirm($message))
                return document.getElementById($id).submit();
            else return false
        }
    </script>
@endsection
