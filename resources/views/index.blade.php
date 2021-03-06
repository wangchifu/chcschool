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

                    @if($block->title == "??????????????????")
                        <div>
                            @include('layouts.marquee')
                        </div>
                    @else
                    <div class="shadow rounded {{ $block_color[0] }}">
                        <div class="{{ $block_color[1] }}">
                            <?php
                                $title = str_replace_last("(????????????)","",$block->title);
                                $title = str_replace_last("_?????????","",$title);
                            ?>
                            <h5>{{ $title }}</h5>
                        </div>
                        <div class="content2">
                            @if($block->title == "????????????(????????????)")
                                @include('layouts.news')
                            @elseif($block->title == "???????????????(????????????)")
                                @include('layouts.chc_air')
                            @elseif($block->title == "????????????(????????????)")
                                @include('layouts.dtree')
                            @elseif($block->title == "????????????(????????????)")
                                @include('layouts.photo_link')
                            @elseif($block->title == "????????????(????????????)")
                                @include('layouts.post_type')
                            @elseif($block->title == "????????????_?????????(????????????)")
                                @include('layouts.post_type2')
                            @elseif($block->title == "???????????????(????????????)")
                                @include('layouts.blog')
                            @else
                                {!! $block->content !!}
                            @endif
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
                        {!! $setup->footer !!}
                    </div>
                </div>
            </div>
        </footer>
    @endif
    <div class="footer-copyright text-center text-black-50 py-3" style="background-color: #CCCCCC">
        2019 Copyright ?????<a href="{{ route('index','index') }}">{{ $setup->site_name }}</a>???????????????:{{ $setup->views }}
    </div>
@endsection
