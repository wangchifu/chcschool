@extends('layouts.master_clean')

@section('title', '編輯公告類別 | ')

@section('content')
    <br>
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th>
                排序
            </th>
            <th>
                名稱
            </th>
            <th>
                動作
            </th>
        </tr>
        </thead>
        <tbody>
        {{ Form::open(['route' => 'posts.store_type', 'method' => 'post']) }}
        <tr>
            <td>
                {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '排序']) }}
            </td>
            <td>
                {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
            </td>
            <td>
                <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">新增</button>
            </td>
        </tr>
        {{ Form::close() }}
        <tr>
            <td>
                -
            </td>
            <td>
                一般公告
            </td>
            <td>

            </td>
        </tr>
        @foreach($post_types as $post_type)
        {{ Form::open(['route' => ['posts.update_type',$post_type->id], 'method' => 'patch']) }}
        <tr>
            <td>
                {{ Form::text('order_by',$post_type->order_by,['class' => 'form-control', 'placeholder' => '排序']) }}
            </td>
            <td>
                @if($post_type->id !=1 and $post_type->id !=2 )
                {{ Form::text('name',$post_type->name,['class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                @else
                    @if($post_type->id==1)
                        <input type="hidden" name="name" value="內部公告">
                    @endif
                    @if($post_type->id==2)
                        <input type="hidden" name="name" value="榮譽榜">
                    @endif
                    {{ $post_type->name }}
                @endif
            </td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">儲存修改</button>
                @if($post_type->id !=1 and $post_type->id !=2 )
                     <a href="{{ route('posts.delete_type',$post_type->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('這類別下的所有公告將移至「一般公告」')">刪除</a>
                @else

                @endif
            </td>
        </tr>
        {{ Form::close() }}
        @endforeach
        </tbody>
    </table>
    @include('layouts.errors')
@endsection
