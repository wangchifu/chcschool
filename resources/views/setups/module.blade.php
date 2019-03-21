@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '模組功能')

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
            $active[5] = "active";
            $module_setup = get_module_setup();
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">模組功能</h3>
                <div class="card-body">
                    <div class="form-group">
                        <form action="{{ route('setups.update_module') }}" method="post">
                            @csrf
                            <table class="table table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th>
                                        模組名稱
                                    </th>
                                    <th>
                                        啟用
                                    </th>
                                    <th>
                                        管理權限
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($modules as $k=>$v)
                                    <?php
                                    $check1 = (isset($module_setup[$v]))?"checked":"";
                                    $check2 = (isset($module_setup[$v]))?"":"checked";
                                    ?>
                                    <tr>
                                        <td>
                                            {{ $v }}
                                        </td>
                                        <td>
                                            <input type="radio" name="module[{{ $v }}]" value="1" id="{{ $k }}1" value="1" {{ $check1 }}> <label class="text-success" for="{{ $k }}1">啟用</label>
                                            <input type="radio" name="module[{{ $v }}]" id="{{ $k }}2" value="" {{ $check2 }}> <label class="text-danger" for="{{ $k }}2">停用</label>
                                        </td>
                                        <td>
                                            @if($v=="公告系統")
                                                行政人員可發公告，管理員可置頂
                                            @elseif($v=="檔案庫")
                                                行政人員可掛檔案
                                            @elseif($v=="好站連結")
                                                管理員可綁連結
                                            @elseif($v=="會議文稿")
                                                行政人員可報告事項
                                            @elseif($v=="校務行政")
                                            @else
                                                <a href="" class="btn btn-info btn-sm">指定</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">儲存</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
