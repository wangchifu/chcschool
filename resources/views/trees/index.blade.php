@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '樹狀目錄 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>樹狀目錄</h1>
            @include('layouts.errors')
            <form action="{{ route('trees.store') }}" method="post" id="this_form1">
                @csrf
                <table class="table table-striped" style="word-break:break-all;">
                    <tr>
                        <th>
                            排序
                        </th>
                        <th>
                            名稱
                        </th>
                        <th>
                            類別
                        </th>
                        <th>
                            所屬目錄
                        </th>
                        <th>
                            連結(建目錄免填)
                        </th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            {{ Form::number('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '排序']) }}
                        </td>
                        <td>
                            {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                        </td>
                        <td>
                            <input type="radio" name="type" value="1" checked id="radio1"><label for="radio1"><i class="fas fa-folder-open"></i> 子目錄</label>
                            <br>
                            <input type="radio" name="type" value="2" id="radio2"><label for="radio2"><i class="fas fa-file"></i> 連結</label>
                        </td>
                        <td>
                            {{ Form::select('folder_id', $folders,null, ['class' => 'form-control']) }}
                        </td>
                        <td>
                            {{ Form::text('url',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => 'http://...(選目錄免填)']) }}
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="return confirm('確定？')"><i class="fas fa-plus"></i> 新增</button>
                        </td>
                    </tr>
                </table>
            </form>
            <div class="card">
                <div class="card-header">
                    目錄結構
                </div>
                <div class="card-body">
                    根目錄：<br>
                    {{ get_tree($trees,0) }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=300');
        }

        var validator = $("#this_form1").validate();
    </script>
@endsection
