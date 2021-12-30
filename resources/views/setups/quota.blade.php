@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

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
            $active[4] = "";
            $active[5] = "";
            $active[6] = "active";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">空間管理</h3>
                <div class="card-body">
                    @include('layouts.hd')

                    <h4>
                        全部公開目錄：{{ round($quota['public']['all']/1024,2) }} MB
                    </h4>
                    <table class="table table-striped">
                        <tr>
                            <th>
                                位置
                            </th>
                            <th>
                                模組
                            </th>
                            <th>
                                所佔空間
                            </th>
                            <th>
                                備註
                            </th>
                        </tr>
                        @foreach($quota['public'] as $k=>$v)
                            @if($k != "all")
                            <tr>
                                <td>
                                    公開
                                </td>
                                <td>
                                    {{ $k }}
                                </td>
                                <td>
                                    {{ round($v/1024,2) }} MB
                                </td>
                                <td>
                                    @if($k=="公告附件")
                                        <a href="javascript:open_window('{{ route('setups.batch_delete_posts') }}','新視窗')" class="btn btn-danger">批次刪除公告及附件</a>
                                    @endif

                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </table>
                    <hr>
                    <h4>
                        全部不公開目錄：{{ round($quota['privacy']['all']/1024,2) }} MB
                    </h4>
                    <table class="table table-striped">
                        <tr>
                            <th>
                                位置
                            </th>
                            <th>
                                模組
                            </th>
                            <th>
                                所佔空間
                            </th>
                            <th>
                                備註
                            </th>
                        </tr>
                        @foreach($quota['privacy'] as $k=>$v)
                            @if($k != "all")
                                <tr>
                                    <td>
                                        不公開
                                    </td>
                                    <td>
                                        {{ $k }}
                                    </td>
                                    <td>
                                        {{ round($v/1024,2) }} MB
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=700');
        }
    </script>
@endsection
