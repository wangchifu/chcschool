@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '模組功能 | ')

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
            $active[6] = "";
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
                                            @elseif($v=="內部文件")
                                                行政人員可增加檔案
                                            @elseif($v=="會議文稿")
                                                行政人員可報告事項
                                            @elseif($v=="校務行政")
                                                --
                                            @elseif($v=="處室介紹")
                                                管理員編修
                                            @elseif($v=="報修系統")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定</a>
                                                <?php
                                                    $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「可回覆」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                            @elseif($v=="校務行事曆")
                                                管理員設置年度後，行政人員可編行事
                                            @elseif($v=="校務月曆")
                                                行政人員可編行事
                                            @elseif($v=="午餐系統")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「午餐業務」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                            @elseif($v=="教師差假")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定「校長」權限</a><br>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    已指定「校長權限」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                                <br>
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'B']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定「人事主任」權限</a><br>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','B')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    已指定「人事主任權限」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                                <!--
                                                <br>
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'C']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定「會計主任」權限</a><br>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','C')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    已指定「會計主任權限」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                                -->
                                                <br>
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'D']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定「單位主管」權限</a><br>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','D')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    已指定「單位主管權限」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                                <br>
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'E']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定「教學組長」權限</a><br>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','E')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    已指定「教學組長權限」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                                <br>
                                            @elseif($v=="社團報名")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「社團業務」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                            @elseif($v=="校園部落格")
                                                行政人員可編輯新文章，管理員可刪除任一文章
                                            @elseif($v=="教室預約")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「可編輯教室」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                            @elseif($v=="借用系統")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「可管理借用」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                            @elseif($v=="運動會報名")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定系統管理</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「可系統管理」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                                <br>
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'B']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定成績輸入</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','B')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「可輸入成績」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach
                                            @elseif($v=="填報學生")
                                                <a href="javascript:open_window('{{ route('user_powers.create',['module'=>$v,'type'=>'A']) }}','新視窗')" class="btn btn-info btn-sm"><i class="fas fa-mouse-pointer"></i> 新指定系統管理</a>
                                                <?php
                                                $user_powers = \App\UserPower::where('name',$v)->where('type','A')->get();
                                                ?>
                                                @foreach($user_powers as $user_power)
                                                    <br>
                                                    已指定「可系統管理」：
                                                    {{ $user_power->user->name }}<a href="{{ route('user_powers.destroy',$user_power->id) }}" onclick="return confirm('確定刪除？')"><i class="text-danger fas fa-times-circle"></i></a>,
                                                @endforeach                                                                                              
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')"><i class="fas fa-save"></i> 儲存</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=330');
        }
    </script>
@endsection
