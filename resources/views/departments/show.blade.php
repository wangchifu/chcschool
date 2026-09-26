@extends('layouts.master')

@section('nav_departments_active', 'active')

@section('title', $department->title.' | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">

            <!-- 頂部快速導覽列 (首頁 / 處室介紹 / 網站導覽捷徑) -->
            <nav aria-label="麵包屑導覽與網站捷徑" class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" title="返回首頁" aria-label="返回首頁">
                            <i class="fas fa-home" aria-hidden="true"></i> 首頁
                        </a>
                    </li>                    
                    <li class="breadcrumb-item active" aria-current="page">{{ $department->title }}</li>
                </ol>

                <div>
                    <!-- 網站導覽快速連結 -->
                    <a href="{{ route('sitemap') }}" title="前往網站導覽" aria-label="前往網站導覽" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-sitemap" aria-hidden="true"></i> 網站導覽
                    </a>
                </div>
            </nav>

            <h1>{{ $department->title }}</h1>
            <div class="card my-4">
                {{-- 將原先的 h3 改為 h2，並用 .h3 類別維持原有的視覺字體大小，符合 H1 -> H2 順序 --}}
                <h2 class="card-header h3">
                    @auth
                        <?php 
                        //查有無在共同編輯群組中
                        $can_edit = 0;
                        if($department->group_id != null){
                            $check_edit = \App\UserGroup::where('user_id',auth()->user()->id)->where('group_id',$department->group_id)->first();
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
                        <a href="{{ route('departments.together_edit',$department->id) }}" class="btn btn-primary btn-sm" title="編輯此頁面" aria-label="編輯此頁面">共同編輯</a>
                        @endif
                        @if(auth()->user()->admin)                        
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $department->id }}').submit();else return false;" title="刪除此頁面" aria-label="刪除此頁面"><i class="fas fa-trash" aria-hidden="true"></i> 刪除</a>
                            {{ Form::open(['route' => ['contents.destroy',$department->id], 'method' => 'DELETE','id'=>'delete'.$department->id]) }}
                            {{ Form::close() }}
                        @endif                        
                        @if(auth()->user()->admin)
                            <a href="{{ route('departments.show_log',$department->id) }}" class="btn btn-info btn-sm" target="_blank" rel="noopener noreferrer" title="查看編輯紀錄 (另開新視窗)" aria-label="查看編輯紀錄 (另開新視窗)">查看 log ({{ $logs_count }})</a>
                        @endif
                    @endauth
                    <button type="button" class="btn btn-dark btn-sm" disabled aria-disabled="true">
                        點閱 <span class="badge badge-light">{{ $department->views }}</span>
                    </button>                    
                </h2>
                <div class="card-body">
                    <div class="table-responsive">
                    {!! enhance_content_accessibility(fix_empty_links(clean_font_size_units($department->content))) !!}                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection