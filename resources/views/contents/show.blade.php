@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', $content->title.' |')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                {{ $content->title }}
            </h1>
            <div class="card my-4">
                <h3 class="card-header">
                    @can('create',\App\Post::class)
                        <a href="{{ route('contents.exec_edit',$content->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 行政人員編輯</a>
                    @endcan
                    @auth
                        @if(auth()->user()->admin)
                            <a href="{{ route('contents.show_log',$content->id) }}" class="btn btn-info btn-sm" target="_blank">查看 log</a>
                        @endif
                    @endauth
                </h3>
                <div class="card-body">
                    <div class="table-responsive">
                    @if($content->power==null)
                        {!! $content->content !!}
                    @elseif($content->power==2)
                        <?php
                            if(auth()->check() or check_ip()){
                            $can_see = 1;
                            }else{
                                $can_see = 0;
                            }
                        ?>
                        @if($can_see)
                            {!! $content->content !!}
                        @else
                            <h2 class="text-danger">請登入，或在校網內才可觀看</h2>
                        @endif
                    @elseif($content->power==3)
                        @auth
                            {!! $content->content !!}
                        @endauth
                        @guest
                            <h2 class="text-danger">請登入後觀看</h2>
                        @endguest
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
