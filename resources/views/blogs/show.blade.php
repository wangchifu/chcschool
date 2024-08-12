@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', $blog->title.' | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
@endsection

@section('content')
    <style>
        .image-container{
            max-width: 90%;

            margin: 0 5%;
        }

        .image1{
            float: right;
        }

        .image2{
            float: left;
            margin-right: 20px;
        }

    </style>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園部落格
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">文章列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                </ol>
            </nav>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2>
                            {{ $blog->title }}
                            </h2>
                            @if($blog->user->name == "系統管理員")
                                        系統管理員
                            @else
                                {{ substr_cut_name($blog->user->name) }}
                            @endif
                             / {{ $blog->created_at }} / 點閱：{{ $blog->views }}
                            @auth
                                @if(auth()->user()->id == $blog->user_id)
                                    <a href="{{ route('blogs.edit',$blog->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                @endif
                                @if(auth()->user()->id == $blog->user_id or auth()->user()->admin ==1)
                                    <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $blog->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                                @endif
                            @endauth
                            {{ Form::open(['route' => ['blogs.destroy',$blog->id], 'method' => 'DELETE','id'=>'delete'.$blog->id]) }}
                            {{ Form::close() }}
                        </div>
                        <div class="card-body">
                            <div class="image-container">
                                @if($blog->title_image)
                                    <a href="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="venobox" data-gall="gall1">
                                    <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="image2 img-fluid rounded" width="40%">
                                    </a>
                                @endif
                                <p class="pp1">
                                    <div class="table-responsive">
                                    {!! $blog->content !!}
                                    </div>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer">
                            @if($last_id)
                                <a href="{{ route('blogs.show',$last_id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-alt-circle-left"></i> 上一篇文章</a>
                            @else
                                <a href="#" class="btn btn-secondary btn-sm disabled"><i class="fas fa-arrow-alt-circle-left"></i> 上一篇文章</a>
                            @endif
                            @if($next_id)
                                <a href="{{ route('blogs.show',$next_id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-alt-circle-right"></i> 下一篇文章</a>
                            @else
                                <a href="#" class="btn btn-secondary btn-sm disabled"><i class="fas fa-arrow-alt-circle-right"></i> 下一篇文章</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
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
