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
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "active";
            $active[7] = "";
            ?>
            @include('setups.nav', $active)

            <!-- 頂部標題區 -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3 class="m-0">磁碟空間與模組用量分析</h3>
            </div>

            <!-- 主卡片容器 -->
            <div class="card my-3 shadow-sm border">
                <!-- 頁首：bg-light text-dark -->
                <div class="card-header bg-light text-dark font-weight-bold d-flex justify-content-between align-items-center py-3">
                    <span style="font-size: 1.1rem;">
                        <i class="fas fa-hdd mr-2 text-primary"></i> 網站伺服器磁碟空間管理
                    </span>
                </div>
                
                <div class="card-body bg-light">
                    
                    <!-- 1. 硬碟整體使用率組件 -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body bg-white rounded p-3">
                            @include('layouts.hd')
                        </div>
                    </div>

                    <!-- 2. 全部位於公開目錄之空間分析 -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body bg-white rounded p-0">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-weight-bold text-dark m-0">
                                    <i class="fas fa-globe text-success mr-2"></i> 全部公開目錄用量
                                </h5>
                                <span class="badge badge-success p-2 font-weight-normal" style="font-size: 0.95rem;">
                                    總計使用：<strong>{{ round($quota['public']['all']/1024,2) }}</strong> MB
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 15%;" class="pl-4">存取層級</th>
                                            <th style="width: 35%;">模組分類</th>
                                            <th style="width: 25%;">所佔容量</th>
                                            <th style="width: 25%;">管理與清理操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quota['public'] as $k => $v)
                                            @if($k != "all")
                                                <tr>
                                                    <td class="align-middle pl-4">
                                                        <span class="badge badge-success p-1 px-2">公開</span>
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-dark">
                                                        <i class="far fa-folder-open text-warning mr-2"></i>{{ $k }}
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-primary">
                                                        {{ round($v/1024,2) }} <small class="text-muted">MB</small>
                                                    </td>
                                                    <td class="align-middle">
                                                        @if($k == "公告附件")
                                                            <a href="javascript:open_window('{{ route('setups.batch_delete_posts') }}','新視窗')" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash-alt mr-1"></i> 批次刪除公告及附件
                                                            </a>
                                                        @else
                                                            <span class="text-muted">--</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 3. 全部位於不公開目錄之空間分析 -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body bg-white rounded p-0">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-weight-bold text-dark m-0">
                                    <i class="fas fa-lock text-secondary mr-2"></i> 全部不公開目錄用量
                                </h5>
                                <span class="badge badge-secondary p-2 font-weight-normal" style="font-size: 0.95rem;">
                                    總計使用：<strong>{{ round($quota['privacy']['all']/1024,2) }}</strong> MB
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 15%;" class="pl-4">存取層級</th>
                                            <th style="width: 35%;">模組分類</th>
                                            <th style="width: 25%;">所佔容量</th>
                                            <th style="width: 25%;">備註</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quota['privacy'] as $k => $v)
                                            @if($k != "all")
                                                <tr>
                                                    <td class="align-middle pl-4">
                                                        <span class="badge badge-secondary p-1 px-2">不公開</span>
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-dark">
                                                        <i class="fas fa-folder text-secondary mr-2"></i>{{ $k }}
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-primary">
                                                        {{ round($v/1024,2) }} <small class="text-muted">MB</small>
                                                    </td>
                                                    <td class="align-middle text-muted">
                                                        --
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        function open_window(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=700');
        }
    </script>
@endsection