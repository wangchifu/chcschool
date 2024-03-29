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
                    @if(isset($photo_desc[$v]['link']) and !empty($photo_desc[$v]['link']))
                        <a href="{{ $photo_desc[$v]['link'] }}" target="_blank">
                            <img class="d-block w-100" src="{{ asset('storage/' . $school_code . '/title_image/random/' . $v) }}">
                        </a>
                    @else
                        <img class="d-block w-100" src="{{ asset('storage/' . $school_code . '/title_image/random/' . $v) }}">
                    @endif
                    <div class="carousel-caption d-none d-md-block">
                        @if(isset($photo_desc[$v]['title']))
                        <h1>{{ $photo_desc[$v]['title'] }}</h1>
                        @endif
                        <p>
                            @if(isset($photo_desc[$v]['desc']))
                            <strong>{{ $photo_desc[$v]['desc'] }}</strong> 
                            @endif
                            @if(isset($photo_desc[$v]['link']) and !empty($photo_desc[$v]['link']))
                            <!--
                                <a href="{{ $photo_desc[$v]['link'] }}" target="_blank" class="btn btn-info btn-sm">詳情</a>
                            -->
                            @endif
                        </p>
                    </div>
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
    <link href="{{ asset('css/block_style.css') }}" rel="stylesheet">
    <div class="row justify-content-center">
        @foreach($setup_cols as $setup_col)
            <div class="col-lg-{{ $setup_col->num }}">
                @foreach($blocks[$setup_col->id] as $block)
                    <?php
                        if(!is_null($block->block_color)){
                            $block_color = explode(',',$block->block_color);
                        }else{
                            $block_color[0] = "original-block";
                            $block_color[1] = "original-title";
                        }
                    ?>

                    @if($block->title == "榮譽榜跑馬燈")
                        <div class="table-responsive">
                            <div>
                                @include('layouts.marquee')
                            </div>
                        </div>
                    @else
                    <div class="shadow rounded {{ $block_color[0] }}">
                        <div class="{{ $block_color[1] }}">
                            <?php
                                $title = str_replace_last("(系統區塊)","",$block->title);
                                $title = str_replace_last("_圖文版","",$title);
                            ?>
                            <h5>{{ $title }}</h5>
                        </div>
                        <div class="content2">
                            <div class="table-responsive">
                            @if($block->title == "最新公告(系統區塊)")
                                @include('layouts.news')
                            @elseif($block->title == "彰化空汙旗(系統區塊)")
                                @include('layouts.chc_air')
                            @elseif($block->title == "樹狀目錄(系統區塊)")
                                @include('layouts.dtree')
                            @elseif($block->title == "圖片連結(系統區塊)")
                                @include('layouts.photo_link')
                            @elseif($block->title == "分類公告(系統區塊)")
                                @include('layouts.post_type')
                            @elseif($block->title == "分類公告_圖文版(系統區塊)")
                                @include('layouts.post_type2')
                            @elseif($block->title == "校園部落格(系統區塊)")
                                @include('layouts.blog')
                            @elseif($block->title == "今日餐點1(系統區塊)")
                                @include('layouts.lunch_today1')
                            @elseif($block->title == "今日餐點2(系統區塊)")
                                @include('layouts.lunch_today2')
                            @elseif($block->title == "今日餐點3(系統區塊)")
                                @include('layouts.lunch_today3')
                            @elseif($block->title == "今日餐點4(系統區塊)")
                                @include('layouts.lunch_today4')
                            @elseif($block->title == "校務月曆(系統區塊)")
                                @include('layouts.monthly_calendar')
                            @elseif($block->title == "教室預約(系統區塊)")
                                @include('layouts.classroom_order')
                            @elseif($block->title == "RSS訊息(系統區塊)")
                                @include('layouts.rss_feed')                           
                            @elseif($block->title == "借用狀態(系統區塊)")
                                @include('layouts.lend_list')
                            @else
                                {!! $block->content !!}
                            @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endforeach

    </div>

@endsection

@section('footer')
    <br>
    <br>
    @if(!empty($setup->footer))
        <footer class="font-small bg-light py-4">
            <div class="container-fluid text-center text-md-left">
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <div class="table-responsive">
                            {!! $setup->footer !!}
                            </div>
                        </div>
                    </div>
            </div>
        </footer>
    @endif
    <div class="footer-copyright text-center text-black-50 py-3" style="background-color: #CCCCCC">
        {{ date('Y') }} Copyright ©　<a href="{{ route('index','index') }}">{{ $setup->site_name }}</a>　訪客人次:{{ $setup->views }} 訪客IP：{{ GetIP() }}
    </div>
@endsection
