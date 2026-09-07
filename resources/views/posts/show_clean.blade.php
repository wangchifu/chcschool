@extends('layouts.master_clean')

@section('nav_post_active', 'active')

@section('title', $post->title.' | 公告詳細內容 | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
@endsection

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：返回按鈕、燈箱連結與下載連結 Focus 高對比視覺提示 */
    .btn:focus-visible,
    a.venobox:focus-visible,
    a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
    }
</style>

<div class="row justify-content-center">

    <!-- 公告內文核心區塊 -->
    <main class="col-lg-11" aria-label="公告詳細內容區塊">

        <?php
        if($post->insite==1){
            if(auth()->check() or check_ip()){
                $can_see = 1;
            }else{
                $can_see = 0;
            }
        }else{
            $can_see = 1;
        };
        //下架日比今天早(小)，不能看
        if($post->die_date != null and $post->die_date < date('Y-m-d')){
            $can_see = 0;
        }
        //上架日比今天晚(大)，不能看
        if(substr($post->created_at,0,10) > date('Y-m-d')){
            $can_see = 0;
        }
        //作者可以看
        if(auth()->check()){
            if($post->user_id == auth()->user()->id){
            $can_see = 1;
            }
        }            
        ?>

        <!-- 公告標題區域 -->
        <article>
            @if($can_see)
                <h1 class="h2 mb-3">{{ $post->title }}</h1>                             
            @else
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                    <h1 class="h2 text-danger mb-3">
                        <i class="fas fa-ban" aria-hidden="true"></i> [ 內部公告 ] {{ $post->title }}
                    </h1>                                           
                @endif
                @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                    <h1 class="h2 mb-3">本公告已下架</h1>                
                @elseif(substr($post->created_at,0,10) > date('Y-m-d'))
                    <h1 class="h2 mb-3">本公告尚未上架</h1>
                @endif
            @endif            

            <div class="lead my-3 d-flex flex-wrap align-items-center">            
                <?php
                    $insite = ($post->insite != null)?$post->insite:0;
                ?>
                <button type="button" class="btn btn-secondary btn-sm mr-3" onclick="window.history.back();" aria-label="返回上一頁">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> 返回
                </button>
                <span>
                    <strong>類別：</strong>{{ $post_type_array[$insite] }} &nbsp;|&nbsp; 
                    <strong>張貼者：</strong>{{ $post->job_title }}
                    @if($post->die_date)
                        &nbsp;|&nbsp; <strong>張貼至：</strong>{{ $post->die_date }} 止
                    @endif
                </span>
            </div>

            <hr>

            <!-- 發佈時間與點閱資訊 -->
            <p class="text-muted">
                張貼日期：{{ $post->created_at }} | 點閱數：{{ $post->views }}
            </p>

            <hr>

            <!-- 標題封面圖片 -->
            @if($can_see)
                @if(!empty($post->title_image))                    
                    <!-- 無障礙 HM1120201C 修正：補充標題圖片 alt -->
                    <img class="img-fluid rounded my-2" src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" alt="{{ $post->title }} 封面圖片">
                    <hr>                    
                @endif                     
            @endif            

            <!-- 公告內文 -->
            @if($can_see)
                <div class="p-3 bg-white rounded border" style="border-style: dotted !important; font-size: 1.2rem;">
                    {!! $post->content !!}
                </div>
            @else
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                    <div class="p-3 bg-white rounded border" style="border-style: dotted !important; font-size: 1.2rem;">
                        <p class="text-danger m-0">[ 內部公告 ] 請登入後瀏覽！</p>
                    </div>
                @endif
            @endif
        </article>

        <!-- 相關照片圖庫區塊 -->
        @if(!empty($photos) and $can_see)
            <hr>
            <section class="card my-4" aria-label="相關照片區塊">
                <!-- 無障礙 HM1010301C 修正：標題階層調整為 h2 -->
                <h2 class="card-header h5 m-0">相關照片</h2>
                <div class="card-body">
                    <div class="row">
                    @foreach($photos as $k=>$v)
                        <div class="col-6 col-md-3 mb-3">
                            <!-- 無障礙 HM1120201C 修正：圖片與燈箱開窗標籤 -->
                            <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="venobox d-block" data-gall="gall1" aria-label="放大檢視照片第 {{ $k+1 }} 張：{{ $v }}">
                                <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" alt="相關照片第 {{ $k+1 }} 張" class="img-thumbnail w-100">
                            </a>
                        </div>
                    @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- 附件下載區塊 -->
        @if(!empty($files) and $can_see)                    
            <hr>
            <section class="card my-4" aria-label="附件下載區塊">
                <!-- 無障礙 HM1010301C 修正：標題階層調整為 h2 -->
                <h2 class="card-header h5 m-0">附件下載</h2>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 d-flex flex-wrap">
                    @foreach($files as $k=>$v)
                        <li class="mr-2 mb-2">
                            <!-- 無障礙 HM1200101C 修正：另開視窗下載提示與隱藏輔具文字 -->
                            <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/files/'.$v) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer" aria-label="下載附件：{{ $v }} (另開新視窗)">
                                <i class="fas fa-download" aria-hidden="true"></i> {{ $v }}
                                <i class="fas fa-external-link-alt ml-1" aria-hidden="true"></i>
                                <span class="sr-only">(另開新視窗)</span>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </section>                    
        @endif            
    </main>

</div>

<script>
    function send_form(){
        if($('#top_date').val().length === 0){
            alert('請選日期！')
        }else{
            $('#top_up_form').submit();
        }
        
    }
    var vb = new VenoBox({
        selector: '.venobox',
        numeration: true,
        infinigall: true,
        spinner: 'rotating-plane'
    });

    $(document).on('click', '.vbox-close', function() {
        vb.close();
    });
</script>
@endsection