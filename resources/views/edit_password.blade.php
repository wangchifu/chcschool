@extends('layouts.master')

@section('nav_user_active', 'active')

@section('title', '更改管理者')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>
                        變更密碼
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('update_password') }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <label for="exampleInputPassword0">舊密碼*</label>
                            <input type="password" class="form-control" name="password0" id="exampleInputPassword0" required tabindex="1" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">新密碼*</label>
                            <input type="password" class="form-control" name="password1" id="exampleInputPassword1" required tabindex="2">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">確認新密碼*</label>
                            <input type="password" class="form-control" name="password2" id="exampleInputPassword2" required tabindex="3">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" tabindex="5" onclick="return confirm('確定？')"><i class="fas fa-save"></i> 送出</button>
                    </form>
                    @include('layouts.errors')
                </div>
            </div>
        </div>
    </div>
@endsection
