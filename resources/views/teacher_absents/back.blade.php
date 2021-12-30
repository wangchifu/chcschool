@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <form action="{{ route('teacher_absents.store_back',$teacher_absent->id) }}" method="post">
                @csrf

                <div class="form-group">
                    <label><h3>退回原因</h3></label>
                    <input type="text" name="back" class="form-control" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm" onclick="return confirm('確定退回？')">確定退回</button>
                </div>
                <input type="hidden" name="title" value="{{ auth()->user()->title }}">
            </form>
        </div>
    </div>
@endsection
