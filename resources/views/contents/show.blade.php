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
                    @auth
                    <?php 
                        //查有無在共同編輯群組中
                        $can_edit = 0;
                        if($content->group_id != null){
                            $check_edit = \App\UserGroup::where('user_id',auth()->user()->id)->where('group_id',$content->group_id)->first();
                            if(!empty($check_edit)){
                                $can_edit = 1;
                            }
                        }else{
                            //行政人員預設可以編
                            $check_edit = \App\UserGroup::where('user_id',auth()->user()->id)->where('group_id',1)->first();
                            if(!empty($check_edit)){
                                $can_edit = 1;
                            }
                        }
                        ?>
                        @if($can_edit)
                        <a href="{{ route('contents.together_edit',$content->id) }}" class="btn btn-primary btn-sm">共同編輯</a>
                        @endif
                        @if(auth()->user()->admin)                        
                            <a href="{{ route('contents.edit',$content->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 編輯</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $content->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                            {{ Form::open(['route' => ['contents.destroy',$content->id], 'method' => 'DELETE','id'=>'delete'.$content->id]) }}
                            {{ Form::close() }}
                        @endif
                        @if(auth()->user()->admin)
                            <a href="{{ route('contents.show_log',$content->id) }}" class="btn btn-info btn-sm" target="_blank">查看 log ({{ $logs_count }})</a>
                        @endif
                    @endauth
                    <button type="button" class="btn btn-dark btn-sm" disabled>
                        點閱 <span class="badge badge-light">{{ $content->views }}</span>
                      </button>                
                    @if($content->power==null)
                    <span class="badge badge-success">公開</span>
                    @elseif($content->power==2)
                    <span class="badge badge-success">須登入</span> <span class="badge badge-warning">在網內</span>
                    @elseif($content->power==3)
                    <span class="badge badge-success">須登入</span>
                    @endif
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
