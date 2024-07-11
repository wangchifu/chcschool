@extends('layouts.master_clean')

@section('nav_post_active', 'active')

@section('title', '顯示公告 | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
@endsection

@section('content')
    <div class="row justify-content-center">

        <!-- Post Content Column -->
        <div class="col-lg-11">

            <!-- Title -->
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

            @if($can_see)
                <h1>{{ $post->title }}</h1>                             
            @else
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                    <h1 class="text-danger"><i class="fas fa-ban"></i> [ 內部公告 ]{{ $post->title  }}</h1>                                           
                @endif
                @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                    <h1>本公告已下架</h1>                
                @elseif(substr($post->created_at,0,10) > date('Y-m-d'))
                    <h1>本公告尚未上架</h1>
                @endif
            @endif            

            <br><br>
            <p class="lead">            
                <?php
                    $insite = ($post->insite != null)?$post->insite:0;
                ?>
                <button class="btn btn-secondary btn-sm" onclick="window.history.back ();">返回</button>
                {{ $post_type_array[$insite] }} / 張貼者
                {{ $post->job_title }}
                @if($post->die_date)
                    張貼至 {{ $post->die_date }}止　　　
                @else
                　　　
                @endif
                
            </p>

            <hr>

            <!-- Date/Time -->
            <p>
                張貼日期： {{ $post->created_at }}　
                點閱：{{ $post->views }}
            </p>

            <hr>
            @if($can_see)
                @if(!empty($post->title_image))                    
                    <img class="img-fluid rounded" src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" alt="標題圖片">
                    <hr>                    
                @endif                     
            @endif            

            <!-- Post Content -->
            @if($can_see)
                <div style="border-width:1px;border-color:#939699;border-style: dotted;background-color:#FFFFFF;padding: 10px">
                    <p style="font-size: 1.2rem;">                                                    
                        {!! $post->content !!}                                                                                                    
                    </p>
                </div>
            @else
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                    <div style="border-width:1px;border-color:#939699;border-style: dotted;background-color:#FFFFFF;padding: 10px">
                        <p style="font-size: 1.2rem;">                                                    
                            <p class="text-danger">[ 內部公告 ] 請登入後瀏覽！</p>                                                                                              
                        </p>
                    </div>
                @endif
            @endif

                @if(!empty($photos) and $can_see)
                    <hr>
                    <div class="card my-4">
                        <h5 class="card-header">相關照片</h5>
                        <div class="card-body">
                        @foreach($photos as $k=>$v)
                        <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="venobox" data-gall="gall1">
                            <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" alt="..." class="img-thumbnail col-2">
                        </a>
                        @endforeach
                        </div>
                    </div>
                @endif
                @if(!empty($files) and $can_see)                    
                    <hr>
                    <div class="card my-4">
                        <h5 class="card-header">附件下載</h5>
                        <div class="card-body">
                        @foreach($files as $k=>$v)
                            <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/files/'.$v) }}" class="btn btn-primary btn-sm" style="margin:3px" target="_blank"><i class="fas fa-download"></i> {{ $v }}</a>
                        @endforeach
                        </div>
                    </div>                    
                @endif            
        </div>

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
            //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
            spinner: 'rotating-plane'
        });
    
        $(document).on('click', '.vbox-close', function() {
            vb.close();
        });
    
    </script>
@endsection
