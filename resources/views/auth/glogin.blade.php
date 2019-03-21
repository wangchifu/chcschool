@extends('layouts.master')

@section('title','GSuite登入頁面')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>教職員登入</h4></div>

            <div class="card-body">
                <a href="https://gsuite.chc.edu.tw" target="_blank"><img src="{{ asset('images/gsuite_logo.png') }}"></a>
                <form id="form" method="POST" action="{{ route('gauth') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">帳號</label>
                        <div class="input-group col-md-6">
                            <input tabindex="1" id="username" type="text" class="form-control" name="username" required autofocus aria-label="Recipient's username" aria-describedby="basic-addon2" placeholder="教育處 Gsuite 帳號">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">@chc.edu.tw</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">密碼</label>

                        <div class="col-md-6">
                            <input tabindex="2" id="password" type="password" class="form-control" name="password" required placeholder="OpenID 密碼">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button tabindex="3" type="submit" class="btn btn-primary btn-sm">
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
