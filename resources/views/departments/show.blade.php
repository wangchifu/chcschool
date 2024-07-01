@extends('layouts.master')

@section('nav_departments_active', 'active')

@section('title', $department->title.' | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $department->title }}</h1>
            <div class="card my-4">
                <h3 class="card-header">
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
                        <a href="{{ route('departments.together_edit',$department->id) }}" class="btn btn-primary btn-sm">共同編輯</a>
                        @endif
                        @if(auth()->user()->admin)                        
                            <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $department->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                            {{ Form::open(['route' => ['contents.destroy',$department->id], 'method' => 'DELETE','id'=>'delete'.$department->id]) }}
                            {{ Form::close() }}
                        @endif                        
                        @if(auth()->user()->admin)
                            <a href="{{ route('departments.show_log',$department->id) }}" class="btn btn-info btn-sm" target="_blank">查看 log ({{ $logs_count }})</a>
                        @endif
                    @endauth
                    <button type="button" class="btn btn-dark btn-sm" disabled>
                        點閱 <span class="badge badge-light">{{ $department->views }}</span>
                      </button>                    
                </h3>
                <div class="card-body">
                    <div class="table-responsive">
                    {!! $department->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
