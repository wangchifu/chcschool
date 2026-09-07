@extends('layouts.master_clean')

@section('title', '編輯公告類別 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：輸入框、按鈕與連結 Focus 高對比視覺提示 */
    .form-control:focus-visible,
    .btn:focus-visible,
    .custom-control-input:focus ~ .custom-control-label::before,
    a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="container-fluid my-3">
    <!-- 無障礙 HM1010301C 修正：補充主要標題結構 -->
    <h1 class="h2 mb-4">編輯公告類別與系統設定</h1>

    <!-- 類別列表管理表格 -->
    <div class="table-responsive mb-5">
        <table class="table table-striped" aria-label="公告類別列表">
            <caption class="sr-only">公告類別列表，提供新增、編輯排序、名稱、刪除與隱藏功能</caption>
            <thead class="thead-light">
            <tr>
                <th scope="col" style="width: 150px;">排序</th>
                <th scope="col" style="min-width: 200px;">名稱</th>
                <th scope="col" style="width: 250px;">動作</th>
            </tr>
            </thead>
            <tbody>
            <!-- 新增類別 Form -->
            {{ Form::open(['route' => 'posts.store_type', 'method' => 'post', 'aria-label' => '新增公告類別表單']) }}
            <tr>
                <td>
                    <!-- 無障礙 HM1150100C 修正：補充專屬 ID 與隱藏 Label -->
                    <label for="order_by_add" class="sr-only">新增類別排序</label>
                    {{ Form::text('order_by', null, ['id' => 'order_by_add', 'class' => 'form-control', 'placeholder' => '排序', 'aria-label' => '新增類別排序']) }}
                </td>
                <td>
                    <label for="name_add" class="sr-only">新增類別名稱</label>
                    {{ Form::text('name', null, ['id' => 'name_add', 'class' => 'form-control', 'required' => 'required', 'placeholder' => '名稱', 'aria-label' => '新增類別名稱']) }}
                </td>
                <td>
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定新增？')">
                        <i class="fas fa-plus" aria-hidden="true"></i> 新增
                    </button>
                </td>
            </tr>
            {{ Form::close() }}

            <!-- 修改既有類別 Form 列表 -->
            @foreach($post_types as $post_type)
            {{ Form::open(['route' => ['posts.update_type', $post_type->id], 'method' => 'patch', 'aria-label' => '修改 '.$post_type->name.' 類別']) }}
            <tr>
                <td>
                    <label for="order_by_{{ $post_type->id }}" class="sr-only">{{ $post_type->name }} 排序</label>
                    {{ Form::text('order_by', $post_type->order_by, ['id' => 'order_by_'.$post_type->id, 'class' => 'form-control', 'placeholder' => '排序', 'aria-label' => $post_type->name.' 排序']) }}
                </td>
                <td>
                    @if($post_type->id != 1 and $post_type->id != 2 and $post_type->id != 0)
                        <label for="name_{{ $post_type->id }}" class="sr-only">{{ $post_type->name }} 名稱</label>
                        {{ Form::text('name', $post_type->name, ['id' => 'name_'.$post_type->id, 'class' => 'form-control', 'required' => 'required', 'placeholder' => '名稱', 'aria-label' => $post_type->name.' 名稱']) }}
                    @else
                        @if($post_type->id==0)
                            <input type="hidden" name="name" value="一般公告">
                        @endif
                        @if($post_type->id==1)
                            <input type="hidden" name="name" value="內部公告">
                        @endif
                        @if($post_type->id==2)
                            <input type="hidden" name="name" value="榮譽榜">
                        @endif
                        @if($post_type->disable==1)
                            <del class="text-muted">{{ $post_type->name }}</del>
                        @else
                            <span class="font-weight-bold">{{ $post_type->name }}</span>
                        @endif
                    @endif
                </td>
                <td>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存修改？')">儲存修改</button>
                    
                    @if($post_type->id != 1 and $post_type->id != 2 and $post_type->id != 0)
                        <a href="{{ route('posts.delete_type', $post_type->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('這類別下的所有公告將移至「一般公告」，確定刪除？')" aria-label="刪除類別 {{ $post_type->name }}">刪除</a>                                     
                    @endif

                    @if($post_type->disable == null)
                        <a href="{{ route('posts.disable_type', $post_type->id) }}" class="btn btn-warning btn-sm" onclick="return confirm('分類公告區塊下將無此類別，確定隱藏？')" aria-label="隱藏類別 {{ $post_type->name }}">隱藏</a>
                    @else
                        <a href="{{ route('posts.disable_type', $post_type->id) }}" class="btn btn-success btn-sm" onclick="return confirm('分類公告區塊下將有此類別，確定再顯示？')" aria-label="再顯示類別 {{ $post_type->name }}">再顯示</a>
                    @endif
                </td>
            </tr>
            {{ Form::close() }}
            @endforeach
            </tbody>
        </table>
    </div>

    <?php 
        $setup = \App\Setup::first();
        $checked = ($setup->all_post) ? "checked" : null;
    ?>

    <!-- 無障礙 HM1010301C 修正：改用結構化的 Section 替代原本排版用的 Table -->
    <main aria-label="進階公告系統設定區塊">
        <!-- 預設顯示選項設定 -->
        <section class="card mb-4">
            <div class="card-body">
                <form action="{{ route('setups.all_post') }}" method="post">
                    @csrf
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" name="all_post" class="custom-control-input" id="customCheck1" {{ $checked }}>
                        <label class="custom-control-label" for="customCheck1">分類公告區塊中，預設顯示「全部公告」</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">確定變更</button>
                </form>
            </div>
        </section>

        <!-- 每頁顯示筆數設定 -->
        <section class="card mb-4">
            <div class="card-body">
                <form action="{{ route('setups.post_show_number') }}" method="post">
                    @csrf                
                    <div class="form-group">
                        <label for="post_show_number">公告的相關區塊中，一次顯示幾則？</label>
                        <div class="d-flex align-items-center">
                            {{ Form::number('post_show_number', $setup->post_show_number, ['id' => 'post_show_number', 'class' => 'form-control mr-2', 'placeholder' => "預設為10則", 'style' => 'max-width: 200px;', 'aria-label' => '公告一次顯示幾則']) }}
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定修改？')">修改筆數</button>
                        </div>
                    </div>
                </form>   
            </div>
        </section>

        <!-- Line Bot 通知權杖與設定 -->
        <section class="card mb-4">
            <div class="card-body">
                <form action="{{ route('setups.post_line_token') }}" method="post">
                    @csrf          
                    <fieldset>
                        <legend class="h6 font-weight-bold">
                            發公告時，順便使用 Line Bot 發訊息 (延後上架者無法使用)
                        </legend>
                        <p class="small text-muted mb-2">
                            參考資源：
                            <!-- 無障礙 HM1200101C 修正：補充另開視窗標籤說明 -->
                            <a href="{{ asset('line_bot.pdf') }}" target="_blank" rel="noopener noreferrer" class="mr-2" aria-label="Line Bot 設定教學 PDF (另開新視窗)">
                                [教學文件 <i class="fas fa-external-link-alt" aria-hidden="true"></i><span class="sr-only">(另開新視窗)</span>]
                            </a>
                            <a href="https://www.youtube.com/watch?v=PgYwIH2bHO0" target="_blank" rel="noopener noreferrer" aria-label="Line Bot 設定教學影片 (另開新視窗)">
                                [影片教學 <i class="fas fa-external-link-alt" aria-hidden="true"></i><span class="sr-only">(另開新視窗)</span>]
                            </a>
                        </p>

                        <!-- 無障礙 HM1150100C 修正：個別標籤與 ID 對應 -->
                        <div class="form-group">
                            <label for="post_line_bot_token">Line Bot 權杖 (Token)</label>
                            {{ Form::text('post_line_bot_token', $setup->post_line_bot_token, ['id' => 'post_line_bot_token', 'class' => 'form-control', 'placeholder' => '輸入 Line Bot 權杖', 'aria-label' => 'Line Bot 權杖']) }}
                        </div>

                        <div class="form-group">
                            <label for="post_line_group_id">Line Group 或 User ID</label>
                            {{ Form::text('post_line_group_id', $setup->post_line_group_id, ['id' => 'post_line_group_id', 'class' => 'form-control', 'placeholder' => '輸入 Line Group 或 User ID', 'aria-label' => 'Line Group 或 User ID']) }}
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">儲存 Line Bot 設定</button>
                    </fieldset>
                </form>
            </div>
        </section>
    </main>

    @include('layouts.errors')
</div>
@endsection