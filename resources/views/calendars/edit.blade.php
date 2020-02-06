@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-新增行事曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1><i class="fas fa-calendar"></i> 校務行事曆-修改行事曆</h1>
            <div class="card">
                <div class="card-header">
                    行事曆資料
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'calendars.update', 'method' => 'POST','id'=>'this_form']) }}
                    <input type="text" name="content" value="{{ $calendar->content }}" required class="form-control">
                    <input type="hidden" name="id" value="{{ $calendar->id }}"><br>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('確定儲存嗎？')">
                        <i class="fas fa-save"></i> 儲存設定
                    </button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
