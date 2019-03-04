@extends('layouts.master')

@section('nav_home_active', 'active')

@section('title', '首頁')

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
    <div class="row">
        @foreach($setup_cols as $setup_col)
            <div class="col-lg-{{ $setup_col->num }}">
                    @foreach($blocks[$setup_col->id] as $block)
                    <div style="border-width:1px;border-color:#939699;border-style: dotted;background-color:#FFFFFF;margin-bottom: 10px;padding: 10px">
                        @if($block->id != 1)
                            {!! $block->content !!}
                        @else
                            @include('layouts.news')
                        @endif
                    </div>
                    @endforeach
            </div>
        @endforeach

    </div>
@endsection
