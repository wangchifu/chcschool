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
        @foreach($post_types as $post_type)
        {{ Form::open(['route' => ['posts.update_type',$post_type->id], 'method' => 'patch']) }}
        <tr>
            <td>
                {{ Form::text('order_by',$post_type->order_by,['class' => 'form-control', 'placeholder' => '排序']) }}
            </td>
            <td>
                @if($post_type->id !=1 and $post_type->id !=2 and $post_type->id !=0)
                {{ Form::text('name',$post_type->name,['class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                @else
                    @if($post_type->id==0)
                        <input type="hidden" name="name" value="一般公告">
                    @endif
                    @if($post_type->id==1)
                        <input type="hidden" name="name" value="內部公告">
                    @endif
                    @if($post_type->id==2)
                        <input type="hidden" name="name" value="榮譽榜">
                    @endif
                    @if($post_type->disable==1)
                        <del>{{ $post_type->name }}</del>
                    @else
                        <span class="font-weight:bold;">{{ $post_type->name }}</span>
                    @endif
                @endif
            </td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">儲存修改</button>
                @if($post_type->id !=1 and $post_type->id !=2 and $post_type->id !=0)
                     <a href="{{ route('posts.delete_type',$post_type->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('這類別下的所有公告將移至「一般公告」')">刪除</a>                                     
                @endif
                @if($post_type->disable==null)
                <a href="{{ route('posts.disable_type',$post_type->id) }}" class="btn btn-warning btn-sm" onclick="return confirm('分類公告區塊下將無此類別')">隱藏</a>
                @else
                <a href="{{ route('posts.disable_type',$post_type->id) }}" class="btn btn-success btn-sm" onclick="return confirm('分類公告區塊下將有此類別')">再顯示</a>
                @endif
            </td>
        </tr>
        {{ Form::close() }}
        @endforeach
        </tbody>
    </table>
    <?php 
        $setup = \App\Setup::first();
        $checked = ($setup->all_post)?"checked":null;
    ?>
    <table>
        <tr style="margin-top: 50px;">
            <td>
                <form action="{{ route('setups.all_post') }}" method="post">
                    @csrf
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="all_post" class="custom-control-input" id="customCheck1" {{ $checked }}>
                        <label class="custom-control-label" for="customCheck1">分類公告區塊中，預設「全部公告」</label>
                        <br>
                        <button class="btn btn-primary btn-sm">確定</button>
                      </div>
                      
                </form>
            </td>
        </tr>
        <tr>
            <td><hr></td>
        </tr>
        <tr>
            <td>
                <label for="post_show_number">公告的相關區塊中，一次顯示幾則？</label>
                <form action="{{ route('setups.post_show_number') }}" method="post">
                    @csrf                
                {{ Form::number('post_show_number',$setup->post_show_number,['id'=>'post_show_number','class' => 'form-control','placeholder'=>"預設為10則"]) }}
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">修改</button>    
                </form>   
            </td>
        </tr>
        <tr>
            <td><hr></td>
        </tr>
        <tr>
            <td>                
                <form action="{{ route('setups.post_line_token') }}" method="post">
                    @csrf          
                <!--      
                <label for="post_line_token">發公告時，順便使用 line notify 發訊息(但延後上架者無法使用)，權杖：</label>
                {{ Form::text('post_line_token',$setup->post_line_token,['id'=>'post_line_token','class' => 'form-control','placeholder'=>"line notify權杖"]) }}
                <br>
                -->
                <label for="post_line_bot_token">發公告時，順便使用 line bot 發訊息(但延後上架者無法使用)，權杖及ID： [<a href="{{ asset('line_bot.pdf') }}" target="_blank">教學</a>] [<a href="https://www.youtube.com/watch?v=PgYwIH2bHO0" target="_blank">影片</a>]</label>
                {{ Form::text('post_line_bot_token',$setup->post_line_bot_token,['id'=>'post_line_bot_token','class' => 'form-control','placeholder'=>"line bot 權杖"]) }}
                {{ Form::text('post_line_group_id',$setup->post_line_group_id,['id'=>'post_line_group_id','class' => 'form-control','placeholder'=>"line group 或 user id"]) }}
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">儲存</button>    
                </form>
            </td>
        </tr>
    </table>

                                 
    @include('layouts.errors')
@endsection
