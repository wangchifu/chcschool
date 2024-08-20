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
                        新增跑馬燈
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    {{ Form::open(['route' => 'school_marquee.store', 'method' => 'POST']) }}                   
                    <div class="form-group">
                        <label for="name">標題*</label>
                        <input type="text" name="title" class="form-control" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="url">開始日期*</label>
                        <input id="start_date" name="start_date" type="date" required maxlength="10" class="form-control" style="width:250px">                        
                        <script>
                            $('#start_date').datepicker({
                                uiLibrary: 'bootstrap4',
                                format: 'yyyy-mm-dd',
                                locale: 'zh-TW',
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="url">結束日期*</label>
                        <input id="stop_date" name="stop_date" type="date" required maxlength="10" class="form-control" style="width:250px">
                        <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('school_marquee.index') }}" class="btn btn-secondary btn-sm">返回</a>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>                    
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
