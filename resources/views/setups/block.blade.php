@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '區塊內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>網站設定</h1>

            <?php
            $active[1] = "";
            $active[2] = "";
            $active[3] = "";
            $active[4] = "active";
            $active[5] = "";
            $active[6] = "";
            $active[7] = "";
            ?>
            @include('setups.nav', $active)

            <!-- 頂部操作區 -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3 class="m-0">區塊配置與排版預覽</h3>
                <a href="javascript:open_window('{{ route('setups.add_block_table') }}','新視窗')" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> 新增區塊
                </a>
            </div>

            <!-- 1. 已上架區塊版面 (依 Bootstrap 真實欄位寬度呈現) -->
            <div class="card my-3 shadow-sm">
                <div class="card-header bg-light text-dark font-weight-bold">
                    <i class="fas fa-th-large mr-1"></i> 已上架頁面版面配置預覽
                </div>
                <div class="card-body bg-light">
                    @if(isset($setup_cols) && count($setup_cols) > 0)
                        <div class="row justify-content-center">
                            @foreach($setup_cols as $setup_col)
                                <div class="col-lg-{{ $setup_col->num }} mb-3">
                                    <div class="column-box p-3 bg-white border border-info rounded h-100 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                            <strong class="text-info">
                                                <i class="fas fa-columns"></i> 欄位 ID: {{ $setup_col->id }}
                                            </strong>
                                            <span class="badge badge-info">col-lg-{{ $setup_col->num }}</span>
                                        </div>

                                        <?php 
                                            // 取得該欄位內的區塊資料
                                            $col_blocks = isset($up_blocks[$setup_col->id]) ? $up_blocks[$setup_col->id] : (isset($blocks[$setup_col->id]) ? $blocks[$setup_col->id] : []);
                                        ?>

                                        @if(count($col_blocks) > 0)
                                            @foreach($col_blocks as $k1 => $v1)
                                                <?php
                                                    $is_sys = (str_contains($v1['title'], "(系統區塊)") || str_contains($v1['title'], "榮譽榜跑馬燈"));
                                                    $text_color = $is_sys ? "text-info font-weight-bold" : "text-dark font-weight-bold";
                                                ?>
                                                <!-- 單一區塊框線卡片 -->
                                                <div class="card mb-2 border border-secondary shadow-sm">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="badge badge-secondary">排序: {{ $v1['order_by'] }}</span>
                                                            <small class="text-muted"><code>id="block{{ $k1 }}"</code></small>
                                                        </div>
                                                        <div class="my-2 {{ $text_color }}">
                                                            {{ $v1['title'] }}
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                                                            <small class="text-muted"><i class="fas fa-tag"></i> {{ $v1['col'] }}</small>
                                                            <a href="javascript:open_window('{{ route('setups.edit_block', $k1) }}','新視窗')" class="btn btn-outline-primary btn-sm px-2 py-0">
                                                                <i class="fas fa-edit"></i> 編輯
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted py-4 border border-dashed rounded bg-light">
                                                <small>此欄位暫無上架區塊</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">未找到欄位設定資料 ($setup_cols)。</div>
                    @endif
                </div>
            </div>

            <!-- 2. 未上架區塊集合 (不放在欄位內) -->
            <div class="card my-4 border-secondary shadow-sm">
                <div class="card-header bg-secondary text-white font-weight-bold">
                    <i class="fas fa-inbox mr-1"></i> 未上架區塊 (未放置於版面)
                </div>
                <div class="card-body bg-light">
                    @if(isset($down_blocks) && count($down_blocks) > 0)
                        <div class="row">
                            @foreach($down_blocks as $k => $v)
                                <?php
                                    $is_sys = (str_contains($v['title'], "(系統區塊)") || str_contains($v['title'], "榮譽榜跑馬燈"));
                                    $text_color = $is_sys ? "text-info font-weight-bold" : "text-dark font-weight-bold";
                                ?>
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card h-100 border-warning shadow-sm">
                                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge badge-warning text-dark">未上架</span>
                                                    <small class="text-muted"><code>id="block{{ $k }}"</code></small>
                                                </div>
                                                <div class="my-2 {{ $text_color }}">
                                                    {{ $v['title'] }}
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                                                <small class="text-muted">排序: {{ $v['order_by'] }}</small>
                                                <a href="javascript:open_window('{{ route('setups.edit_block', $k) }}','新視窗')" class="btn btn-outline-primary btn-sm px-2 py-0">
                                                    <i class="fas fa-edit"></i> 編輯
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0"><i class="fas fa-check-circle text-success mr-1"></i> 目前沒有任何未上架的區塊。</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function open_window(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=800');
        }
    </script>
@endsection