@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">        
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h4>社團報名-學生測試登入頁面</h4></div>

                <div class="card-body">
                    @if(session('parents_test_error')>=3)
                        <div class="alert alert-danger">
                            錯誤次數過多，請稍後再試！若系統生日登記錯誤，請洽學校管理者！
                        </div>
                    @else
                        <form method="POST" action="{{ route('clubs.do_login_test') }}">
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        @csrf
                        <div class="form-group row">
                            <label for="class_num" class="col-sm-4 col-form-label text-md-right">帳號 (班級座號5碼)</label>

                            <div class="col-md-6">
                                <input id="class_num" type="text" class="form-control" name="class_num" value="{{ old('class_num') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pwd" class="col-md-4 col-form-label text-md-right">密碼 (預設生日8碼)</label>

                            <div class="col-md-6">
                                <input id="pwd" type="password" class="form-control" name="pwd" required>
                            </div>
                        </div>                            
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sign-in-alt"></i> 測試登入
                                </button>
                            </div>
                        </div>
                        @include('layouts.errors')
                        </form>
                    @endif     
                    <br>
                    <a href="{{ route('clubs.semester_select') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> 返回學期選擇</a>               
                </div>
            </div>
        </div>
    </div>
@endsection
