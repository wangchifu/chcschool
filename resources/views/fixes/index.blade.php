@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>報修系統</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">報修列表</li>
                </ol>
            </nav>
            <a href="{{ route('fixes.index') }}" class="btn btn-dark btn-sm"><i class="fas fa-check-square"></i> 全部列表</a>
            @include('fixes.nav',['situation'=>null])
            <hr>
            @if($fix_admin)
                <!-- 管理者專屬工具箱 (可摺疊/區隔開來，不干擾主表格) -->
                <div class="card border-0 bg-light mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold m-0 text-primary">
                            <i class="fas fa-user-shield me-1"></i> 管理者工具與系統設定
                        </h6>
                    </div>

                    <div class="collapse show" id="adminPanel">
                        <div class="card-body px-3 pt-2 pb-3">
                            <div class="row g-3">
                                
                                <!-- 左側：通知設定表單 -->
                                <div class="col-lg-5 border-end-lg">
                                    <form id="line_form" action="{{ route('fixes.store_notify') }}" method="post" class="h-100 d-flex flex-column justify-content-between">
                                        @csrf
                                        <div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold mb-1 small text-secondary">
                                                    <i class="fab fa-line text-success"></i> LINE BOT 通知
                                                    <span class="fw-normal">
                                                        [<a href="{{ asset('line_bot.pdf') }}" target="_blank">教學</a>] 
                                                        [<a href="https://www.youtube.com/watch?v=PgYwIH2bHO0" target="_blank">影片</a>]
                                                    </span>
                                                </label>
                                                <div class="input-group input-group-sm mb-1">
                                                    <input type="text" class="form-control" name="line_bot_token" value="{{ auth()->user()->line_bot_token }}" placeholder="LINE Bot Token">
                                                    <input type="text" class="form-control" name="line_user_id" value="{{ auth()->user()->line_user_id }}" placeholder="User ID">
                                                </div>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label fw-bold mb-1 small text-secondary">
                                                    <i class="fas fa-envelope text-primary"></i> Email 通知
                                                </label>
                                                <input type="email" class="form-control form-control-sm" name="email" value="{{ auth()->user()->email }}" required placeholder="電子郵件">
                                            </div>
                                        </div>

                                        <div class="text-end mt-2">
                                            <button class="btn btn-primary btn-sm px-3" onclick="return confirm('確定儲存設定嗎？')">
                                                <i class="fas fa-save me-1"></i> 儲存設定
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- 右側：學生登入與管理入口 -->
                                <div class="col-lg-7">
                                    <label class="form-label fw-bold mb-2 small text-secondary">
                                        <i class="fas fa-users-cog"></i> 學生入口與帳號管理
                                    </label>
                                    <div class="row g-2">
                                        <!-- 方式 1 -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-warning text-dark">方式 1</span>
                                                    <span class="text-muted extra-small">需建檔</span>
                                                </div>
                                                <div class="fw-bold small mb-2">使用一般帳號登入</div>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('fixes.stu_adm') }}" class="btn btn-warning btn-sm flex-fill py-0 text-nowrap">
                                                        <i class="fas fa-user-cog"></i> 建立學生帳密
                                                    </a>
                                                    <a href="{{ route('fixes.stu_login') }}" class="btn btn-outline-secondary btn-sm flex-fill py-0 text-nowrap" target="_blank">
                                                        <i class="fas fa-external-link-alt"></i> 登入連結
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 方式 2 -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-primary">方式 2</span>
                                                    <span class="text-muted extra-small">SSO 驗證</span>
                                                </div>
                                                <div class="fw-bold small mb-2">使用學校 EIP 登入</div>
                                                <div>
                                                    <a href="#!" class="btn btn-primary btn-sm w-100 py-0">
                                                        <i class="fas fa-external-link-alt me-1"></i> 使用校網右上角登入
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('fixes.edit_class') }}" class="btn btn-outline-secondary btn-sm me-2"><i class="fas fa-edit me-1"></i> 編輯類別</a>
            @endif                        
            <a href="{{ route('fixes.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增報修</a>                                         
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>類別</th>
                    <th>處理狀況</th>
                    <th>申報日期</th>
                    <th>申報人</th>
                    <th>標題</th>
                    <th>處理日期</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fixes as $fix)
                    <tr>
                        <td>
                            @if($fix_admin)
                                <a href="{{ route('fixes.destroy',$fix->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                            @endif
                            {{ $types[$fix->type] }}
                        </td>
                        <td>
                            <?php
                            $situation=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                            $icon = [
                                '1'=>'<i class="fas fa-check-square text-success"></i>',
                                '2'=>'<i class="fas fa-exclamation-triangle text-warning"></i>',
                                '3'=>'<i class="fas fa-phone-square text-danger"></i>'
                            ];
                            ?>
                            {!! $icon[$fix->situation] !!} {{ $situation[$fix->situation] }}
                        </td>
                        <td>
                            {{ substr($fix->created_at,0,10) }}
                        </td>
                        <td>
                            @if($fix->user_id == 0)
                                學生
                            @else
                                {{ $fix->user->name }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('fixes.show',$fix->id) }}">{{ $fix->title }}</a>
                        </td>
                        <td>
                            @if($fix->situation < 3)
                                {{ substr($fix->updated_at,0,10) }}
                            @endif
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            <div class="table-responsive">
            {{ $fixes->links() }}
            </div>
        </div>
    </div>
@endsection
