@extends('layouts.master')

@section('title','管理登入頁面')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>管理員登入</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ route('auth') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="username" class="col-sm-4 col-form-label text-md-right">帳號</label>

                        <div class="col-md-6">
                            <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">密碼</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 text-md-left">
                        </div>
                        <div class="col-md-6 text-md-left">
                            <img src="{{ route('pic') }}" class="img-fluid">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="chaptcha" class="col-md-4 col-form-label text-md-right">驗證碼</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="chaptcha" required placeholder="上圖數字" maxlength="5">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt"></i> 登入
                            </button>
                        </div>
                    </div>
                    @include('layouts.errors')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
