@extends('layouts.master')

@section('nav_home_active', 'active')

@section('top_image')
    @if($setup->title_image)
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($photos as $k=>$v)
                <?php $active = ($k==0)?"active":""; ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $k }}" class="{{ $active }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($photos as $k=>$v)
                <?php $active = ($k==0)?"active":""; ?>
                <div class="carousel-item {{ $active }}">
                    <img class="d-block w-100" src="{{ $v }}">
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    @endif
@endsection

@section('content')

    <div class="row justify-content-center">
        @foreach($setup_cols as $setup_col)
            <div class="col-lg-{{ $setup_col->num }}">
                    @foreach($blocks[$setup_col->id] as $block)
                    <div style="box-shadow:1px 1px 1px 1px #cccccc;border-width:1px;border-color:#939699;border-style: solid;background-color:#FFFFFF;margin-bottom: 10px;padding: 10px">
                        @if($block->title == "最新公告(系統區塊)")
                            @include('layouts.news')
                        @elseif($block->title == "彰化空汙旗(系統區塊)")
                            @include('layouts.chc_air')
                        @elseif($block->title == "樹狀目錄(系統區塊)")
                            @include('layouts.dtree')
                        @elseif($block->title == "榮譽榜")
                            @include('layouts.honor')
                        @else
                            {!! $block->content !!}
                        @endif
                    </div>
                    @endforeach
            </div>
        @endforeach

    </div>

@endsection

@section('footer')
    <br>
    <br>
    <footer class="font-small bg-light py-4">
        <div class="container-fluid text-center text-md-left">
            @if(!empty($setup->footer))
            <div class="row justify-content-center">
                <div class="col-md-11">
                    {!! $setup->footer !!}
                </div>
            </div>
            @endif
            <div class="row justify-content-center" style="background-color: #cccccc;">

            </div>
        </div>
    </footer>
    <div class="footer-copyright text-center text-black-50 py-3" style="background-color: #CCCCCC">
        2019 Copyright ©　<a href="{{ route('index') }}">{{ $setup->site_name }}</a>　訪客人次:{{ $setup->views }}
    </div>
@endsection
