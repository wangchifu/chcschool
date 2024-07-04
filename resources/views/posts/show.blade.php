@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '顯示公告 | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
@endsection

@section('content')
    <div class="row justify-content-center">

        <!-- Post Content Column -->
        <div class="col-lg-8">

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
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">公告內容</li>
                </ol>
            </nav>
            @if($can_see)
                <h1>{{ $post->title }}</h1>                             
            @else
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and substr($post->created_at,0,10) < date('Y-m-d H:i:s'))
                    <h1 class="text-danger"><i class="fas fa-ban"></i> [ 內部公告 ]{{ $post->title  }}</h1>                                           
                @endif
                @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                    <h1>本公告已下架</h1>                
                @elseif(substr($post->created_at,0,10) > date('Y-m-d'))
                    <h1>本公告尚未上架</h1>
                @endif
            @endif            

            @if($last_id)
                <a href="{{ route('posts.show',$last_id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-alt-circle-left"></i> 上一則公告</a>
            @else
                <a href="#" class="btn btn-secondary btn-sm disabled"><i class="fas fa-arrow-alt-circle-left"></i> 上一則公告</a>
            @endif
            @if($next_id)
                <a href="{{ route('posts.show',$next_id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-alt-circle-right"></i> 下一則公告</a>
            @else
                <a href="#" class="btn btn-secondary btn-sm disabled"><i class="fas fa-arrow-alt-circle-right"></i> 下一則公告</a>
            @endif

            <br><br>
            <p class="lead">            
                <?php
                    $insite = ($post->insite != null)?$post->insite:0;
                ?>
                <a href="{{ route('posts.type',$insite) }}">{{ $post_type_array[$insite] }}</a> / 張貼者
                <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a>
                @if($post->die_date)
                    張貼至 {{ $post->die_date }}止　　　
                @else
                　　　
                @endif
                @auth
                    @if(auth()->user()->admin)
                        @if($post->top)
                            @if(!empty($post->top_date))
                                <span class="badge badge-secondary">置頂至{{ $post->top_date }}</span>
                            @endif
                            <a href="{{ route('posts.top_down',$post->id) }}" class="btn btn-warning btn-sm" onclick="return confirm('確定要取消置頂？')"><i class="fas fa-sort-amount-down"></i> 取消置頂</a>
                        @else
                            <!--
                            <a href="{{ route('posts.top_up',$post->id) }}" class="btn btn-outline-success btn-sm" onclick="return confirm('確定要置頂？')"><i class="fas fa-sort-amount-up"></i> 置頂</a>
                            -->
                            <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-sort-amount-up"></i> 置頂
                              </button>
                        @endif
                    @endif

                    @if(auth()->user()->id == $post->user_id or auth()->user()->admin ==1)
                        <a href="{{ route('posts.edit',$post->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                        {{ Form::open(['route' => ['posts.destroy',$post->id], 'method' => 'DELETE','id'=>'delete','onsubmit'=>'return false;']) }}
                        {{ Form::close() }}
                    @endif
                @endauth
            </p>

            <hr>

            <!-- Date/Time -->
            <p>
                張貼日期： {{ $post->created_at }}　
                點閱：<a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/'.$post->id.'.txt') }}" target="_blank">{{ $post->views }}</a>
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
                @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and substr($post->created_at,0,10) < date('Y-m-d'))
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

        <div class="col-lg-3">

            <div class="card my-4">
                <h5 class="card-header">近月內熱門公告</h5>
                <div class="card-body">
                @foreach($hot_posts as $hot_post)
                        <li>{{ substr($hot_post->created_at,0,10) }} <span class="badge badge-danger">{{ $hot_post->views }}</span><br>
                            　　<a href="{{ route('posts.show',$hot_post->id) }}">{{ str_limit($hot_post->title,60) }}</a>
                        </li>
                @endforeach
                </div>
            </div>

        </div>

    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">置頂至哪一天？</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="top_up_form" action="{{ route('posts.top_up2',$post->id) }}" method="post">
            @csrf
        <div class="modal-body">
          <input type="date" name="top_date" id="top_date" class="form-control" required="required">
        </div>
        <div class="modal-footer">
          <span class="btn btn-secondary" data-dismiss="modal">取消</span>
          <button type="button" class="btn btn-primary" onclick="send_form()">送出</button>
        </div>
        </form>
      </div>
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
