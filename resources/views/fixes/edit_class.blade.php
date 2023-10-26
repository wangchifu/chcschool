@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '編輯類別 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>編輯類別</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.index') }}">報修列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">編輯類別</li>
                </ol>
            </nav>
            <div class="card my-4">
                <h3 class="card-header">編輯類別</h3>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>狀況</th><th>排序</th><th>啟用?</th><th>名稱 <strong class="text-danger">*</strong></th><th>動作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fix_classes as $fix_class)
                            <form method="post" action="{{ route('fixes.update_class',$fix_class->id) }}">
                                @csrf
                            <tr>
                                <td>
                                    @if($fix_class->disable)
                                    <span class="text-danger">已停用</span> 
                                    @else
                                        啟用
                                    @endif
                                </td>
                                <td>
                                    <input type="text" value="{{ $fix_class->order_by }}" name="order_by" class="form-control">
                                </td>
                                <td>
                                    <?php 
                                        $disable = ($fix_class->disable)?"checked":null;
                                    ?>
                                    <input type="checkbox" name="disable" value=1 id="disable{{ $fix_class->id }}" {{ $disable }}> <label for="disable{{ $fix_class->id }}">停用</label>
                                </td>
                                <td>
                                    <input type="text" value="{{ $fix_class->name }}" name="name" class="form-control" required>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">更新此行</button>
                                </td>
                            </tr>
                            </form>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <form method="post" action="{{ route('fixes.store_class') }}">
                                @csrf
                            <tr>
                                <td>
                                    新增
                                </td>
                                <td>
                                    <input type="text" name="order_by" class="form-control" placeholder="新增的排序">
                                </td>
                                <td>
                                    <input type="checkbox" name="disable" value=1 id="disable"> <label for="disable">停用</label>
                                </td>
                                <td>
                                    <input type="text" name="name" class="form-control" required placeholder="新增的名稱">
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">新增</button>
                                </td>
                            </tr>
                            </form>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
