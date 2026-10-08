@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', $content->title.' | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                {{ $content->title }}
            </h1>
            <div class="card my-4">
                {{-- 將原先的 h3 改為 h2，並以 .h3 類別維持原有的視覺字體大小，符合 H1 -> H2 無障礙標題階層 --}}
                <h2 class="card-header h3">                                     
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
                        <a href="{{ route('contents.together_edit',$content->id) }}" class="btn btn-primary btn-sm" title="共同編輯此頁面" aria-label="共同編輯此頁面">共同編輯</a>
                        @endif
                        @if(auth()->user()->admin)                        
                            <a href="{{ route('contents.edit',$content->id) }}" class="btn btn-primary btn-sm" title="編輯此頁面" aria-label="編輯此頁面"><i class="fas fa-edit" aria-hidden="true"></i> 編輯</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $content->id }}').submit();else return false;" title="刪除此頁面" aria-label="刪除此頁面"><i class="fas fa-trash" aria-hidden="true"></i> 刪除</a>
                            {{ Form::open(['route' => ['contents.destroy',$content->id], 'method' => 'DELETE','id'=>'delete'.$content->id]) }}
                            {{ Form::close() }}
                        @endif
                        @if(auth()->user()->admin)
                            <a href="{{ route('contents.show_log',$content->id) }}" class="btn btn-info btn-sm" target="_blank" rel="noopener noreferrer" title="查看編輯紀錄 (另開新視窗)" aria-label="查看編輯紀錄 (另開新視窗)">查看 log ({{ $logs_count }})</a>
                        @endif
                    @endauth
                    <button type="button" class="btn btn-dark btn-sm" disabled aria-disabled="true">
                        點閱 <span class="badge badge-light">{{ $content->views }}</span>
                    </button>                
                    @if($content->power==null)
                    <span class="badge badge-success">公開</span>
                    @elseif($content->power==2)
                    <span class="badge badge-success">須登入</span> <span class="badge badge-warning">在網內</span>
                    @elseif($content->power==3)
                    <span class="badge badge-success">須登入</span>
                    @endif
                </h2>

                <!-- 無障礙 2.1.1 修正：加上 tabindex="0" 使文章內容區可接收 Tab 鍵焦點與方向鍵捲動，避免焦點滑出至瀏覽器工具列 -->
                <div class="card-body" tabindex="0" aria-label="{{ $content->title }} 文章詳細內容區">
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
                            {!! sanitize_accessibility_html($content->content) !!}
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