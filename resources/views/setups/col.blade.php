@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>網站設定</h1>

            <?php
            $active[1] = "";
            $active[2] = "";
            $active[3] = "active";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            $active[7] = "";
            ?>
            @include('setups.nav', $active)

            <!-- 頂部操作區 -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3 class="m-0">首頁欄位與結構配置</h3>
                <a href="javascript:open_window('{{ route('setups.add_col_table') }}','新視窗')" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> 新增欄位
                </a>
            </div>

            <!-- 首頁欄位真實比例預覽區塊 -->
            <div class="card my-3 shadow-sm">
                <div class="card-header bg-loght text-dark font-weight-bold">
                    <i class="fas fa-columns mr-1"></i> 首頁欄位排版真實比例預覽
                </div>
                <div class="card-body bg-light">
                    @if(isset($setup_cols) && count($setup_cols) > 0)
                        <div class="row justify-content-center">
                            @foreach($setup_cols as $setup_col)
                                <div class="col-lg-{{ $setup_col->num }} mb-3">
                                    <div class="card h-100 border border-info shadow-sm bg-white">
                                        <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom pb-2">
                                            <span class="badge badge-info">排序: {{ $setup_col->order_by }}</span>
                                            <span class="badge badge-secondary">col-lg-{{ $setup_col->num }} ({{ $setup_col->num }}/12)</span>
                                        </div>
                                        <div class="card-body d-flex flex-column justify-content-between p-3 text-center">
                                            <div class="my-3">
                                                <h5 class="text-dark font-weight-bold mb-2">
                                                    <i class="fas fa-th-large text-info mr-1"></i> {{ $setup_col->title }}
                                                </h5>
                                                <small class="text-muted">欄位 ID: {{ $setup_col->id }}</small>
                                            </div>
                                            <div class="mt-3 pt-2 border-top">
                                                <a href="javascript:open_window('{{ route('setups.edit_col', $setup_col->id) }}','新視窗')" class="btn btn-outline-primary btn-sm btn-block">
                                                    <i class="fas fa-edit"></i> 編輯欄位
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">目前尚無任何欄位設定。</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function open_window(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=230');
        }
    </script>
@endsection