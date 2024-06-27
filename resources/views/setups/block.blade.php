@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '區塊內容 | ')

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
            $active[6] = "";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">區塊內容</h3>
                <div class="card-body">
                    <a href="javascript:open_window('{{ route('setups.add_block_table') }}','新視窗')" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> 新增區塊
                    </a>
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
                                    @if($block->setup_col_id)
                                    {{ $block->setup_col->title }} ({{ $block->setup_col_id }})
                                    @else
                                        <small class="text-secondary">(下架中)</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $block->order_by }}
                                </td>
                                <?php
                                    if(str_contains($block->title,"(系統區塊)")==true or str_contains($block->title,"榮譽榜跑馬燈")==true){
                                        $text_color = "text-info";
                                    }else{
                                        $text_color = "text-dark";
                                    };
                                ?>
                                <td class="{{ $text_color }}">                                    
                                    {{ $block->title }}
                                </td>
                                <td>
                                    <a href="javascript:open_window('{{ route('setups.edit_block',$block->id) }}','新視窗')" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 編輯</a>
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
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=800');
        }
        // -->

        var validator = $("#this_form").validate();
    </script>
@endsection
