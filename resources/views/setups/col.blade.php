@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定')

@section('content')
    <h1>
        網站設定
    </h1>
    <?php
        $active[1] = "";
        $active[2] = "";
        $active[3] = "active";
        $active[4] = "";
     ?>
    @include('setups.nav',$active)
    <div class="card my-4">
        <h3 class="card-header">首頁欄位</h3>
        <div class="card-body">
            {{ Form::open(['route' => 'setups.add_col', 'method' => 'post']) }}
            <div class="form-group">
                <label for="site_name">欄位寬度比例 ( 1-12 整數 )</label>
                {{ Form::text('num',null,['class' => 'form-control','required'=>'required','maxlength'=>'2']) }}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定新增？')">
                    <i class="fas fa-plus"></i> 新增欄位
                </button>
            </div>
            {{ Form::close() }}
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>
                        欄 id
                    </th>
                    <th>
                        所佔比例 ( bootstrap 網頁一行佔 12 )
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($setup_cols as $setup_col)
                <tr>
                    <td>
                        {{ $setup_col->id }}
                    </td>
                    <td>
                        {{ $setup_col->num }}
                    </td>
                    <td>
                        <a href="javascript:open_window('{{ route('setups.edit_col',$setup_col->id) }}','新視窗')" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 編輯</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        <!--
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=230');
        }
        // -->
    </script>
@endsection
