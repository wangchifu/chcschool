@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '區塊內容')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                網站設定
            </h1>
            <?php
            $active[1] = "";
            $active[2] = "";
            $active[3] = "";
            $active[4] = "active";
            $active[5] = "";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">區塊內容</h3>
                <div class="card-body">
                    {{ Form::open(['route' => 'setups.add_block', 'method' => 'post']) }}
                    <table class="table">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="site_name">1.放置欄位</label>
                                    {{ Form::select('setup_col_id', $setup_array,null, ['class' => 'form-control','placeholder'=>'']) }}
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="order_by">2.排序</label>
                                    {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="site_name">3.名稱</label>
                                    {{ Form::text('title',null,['class' => 'form-control','required'=>'required']) }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="form-group">
                                    <label for="content">4.內文*</label>
                                    {{ Form::textarea('content',null,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
                                </div>
                            </td>
                        </tr>
                    </table>
                    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('my-editor'
                            ,{
                                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                            });
                    </script>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定新增？')">
                            <i class="fas fa-plus"></i> 新增區塊
                        </button>
                    </div>
                    {{ Form::close() }}
                    <table class="table table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>
                                放置欄位名稱 (id)
                            </th>
                            <th>
                                排序
                            </th>
                            <th>
                                名稱
                            </th>
                            <th>
                                編輯
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($blocks as $block)
                            <tr>
                                <td>
                                    {{ $block->setup_col->title }} ({{ $block->setup_col_id }})
                                </td>
                                <td>
                                    {{ $block->order_by }}
                                </td>
                                <td>
                                    {{ $block->title }}
                                </td>
                                <td>
                                    <a href="javascript:open_window('{{ route('setups.edit_block',$block->id) }}','新視窗')" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 編輯</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        <!--
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=700');
        }
        // -->
    </script>
@endsection
