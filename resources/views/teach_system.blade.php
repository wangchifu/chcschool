@extends('layouts.master')

@section('nav_user_active', 'active')

@section('title', '系統教學 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                系統教學
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">系統教學</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        校網集中代管方案三系統說明
                    </h3>
                </div>
                <div class="card-body">
                    <p>
                        本系統於2018年由縣網中心黃技士及和東國小資訊組因應中小學學校網站集中代管而設計，過程中還有台中陳老師幫助 apache 優化。<br>
                        願此系統可以簡化各校資訊組的工作，並能活化校網功能與應用。<br>
                        全縣各校的網站集中概況，可以由 <a href="https://chcschool.chc.edu.tw" target="_blank">https://chcschool.chc.edu.tw</a> 看到。<br>
                        你可以多參考其他一樣使用方案三的學校，看看別人如何應用目前各模組功能，來美化、活用方案三。
                    </p>
                    <hr>
                    <h4>如果你在使用本系統有困難，建議你參考底下教學影片</h4>
                    <a href="{{ env('TEA_URL') }}" target="_blank">教學影片點此至 youtube 看</a>
                </div>
            </div>
        </div>
    </div>
@endsection
