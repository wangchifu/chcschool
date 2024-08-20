@extends('layouts.master_clean')

@section('title', '校園跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <br>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        修改跑馬燈
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    {{ Form::open(['route' => ['school_marquee.update',$school_marquee->id], 'method' => 'POST']) }}                   
                    <div class="form-group">
                        <label for="name">標題*</label>
                        <input type="text" name="title" class="form-control" required value="{{ $school_marquee->title }}">
                    </div>
                    <div class="form-group">
                        <label for="url">開始日期*</label>
                        <input id="start_date" name="start_date" type="date" required maxlength="10" class="form-control" style="width:250px" value="{{ $school_marquee->start_date }}">                        
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
                        <input id="stop_date" name="stop_date" type="date" required maxlength="10" class="form-control" style="width:250px" value="{{ $school_marquee->stop_date }}">
                        <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                    </div>
                    <div class="form-group">                        
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
