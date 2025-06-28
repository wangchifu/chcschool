@extends('layouts.master')

@section('title','教職員登入 | ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">            
            <div class="card-header d-flex align-items-center">
                <a href="https://eip.chc.edu.tw" target="_blank"><img src="{{ asset('images/chc2.png') }}" alt="CHC Logo" width="50" class="me-2" style="margin-right:10px; border:1px solid #000000;"></a>
                彰化縣教育雲端帳號登入
            </div>
            <div class="card-body">                
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">                        
                        <div class="text-center">
                            <a href="{{ route('sso') }}" class="image-button">
                                <img src="{{ asset('images/chc.jpg') }}" alt="彰化chc的logo" width="120">
                            </a>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin_login') }}"><i class="fas fa-cog"></i> 使用本機帳號</a>
                        </div>                                                                      
                    </div>                               
                  </div>                                  
            </div>
        </div>
    </div>
</div>
@endsection
