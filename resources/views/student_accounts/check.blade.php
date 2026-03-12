@extends('layouts.master')

@section('title', '學生帳號查詢 | ')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">學生 彰化縣帳號(OpenID)查詢</h5>                    
                </div>
                <div class="card-body">
                    @if($has_file)
                        @include('layouts.errors')
                        @if(session('student_check_error')>0)
                            <div class="alert alert-danger">                                
                                目前已經嘗試 {{ session('student_check_error') }} 次。
                            </div>
                        @endif
                        @if(session('student_check_error')>5)
                            <div class="alert alert-danger">
                                嘗試次數超過5次，請稍後再試。
                            </div>
                        @else
                            <form action="{{ route('student_account.do_check') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="classnum" class="form-label fw-bold">1.班級座號5碼</label>
                                    <input type="text" 
                                        name="classnum" 
                                        id="classnum" 
                                        class="form-control @error('classnum') is-invalid @enderror" 
                                        placeholder="例如：60101" 
                                        maxlength="5"
                                        required 
                                        value="{{ old('classnum') }}">

                                </div>

                                <div class="mb-3">
                                    <label for="birthday" class="form-label fw-bold">2.西元8碼生日</label>
                                    <input type="text" 
                                        name="birthday" 
                                        id="birthday" 
                                        class="form-control @error('birthday') is-invalid @enderror" 
                                        placeholder="例如：20120520" 
                                        maxlength="8" 
                                        pattern="\d{8}"
                                        title="請輸入8位數字的西元生日"
                                        required>
                                    <div class="form-text text-secondary">請輸入完整 8 位數字，例如 2012 年 5 月 20 日請輸入 20120520</div>

                                </div>
                                <div class="mb-3">                                
                                    <label for="captcha" class="form-label fw-bold">3.驗證碼</label>
                                    <img src="{{ route('pic') }}" class="img-fluid" alt="驗證碼圖片">
                                    <input type="text" 
                                        name="chaptcha" 
                                        id="captcha" 
                                        class="form-control @error('captcha') is-invalid @enderror" 
                                        placeholder="請輸入5碼驗證碼" 
                                        pattern="\d{5}"
                                        maxlength="5"
                                        required>
                                    <div class="form-text text-secondary">請將圖片中的國字，改為阿拉伯數字</div>
                                </div>

                                <div class="d-grid">
                                    <span class="text-danger mb-2">盜用他人帳號是違法行為，請勿以身試法。</span><br>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-search"></i> 開始查詢
                                    </button>
                                </div>
                            </form>
                        @endif
                                                
                    @else
                        <div class="alert alert-warning">   
                            尚未上傳學生帳號清單，請聯絡學校管理員。
                        </div>
                    @endif                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection