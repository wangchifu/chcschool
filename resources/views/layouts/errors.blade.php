@if($errors->any())
    {{-- 無障礙 4.1.3 修正：加入 role="alert" 與 aria-live="assertive"，讓報讀軟體第一時間主動提醒使用者 --}}
    <div class="alert alert-danger role-alert mb-3" role="alert" aria-live="assertive">
        {{-- 無障礙 HM1130100C 修正：新增結構化小標題 --}}
        <h2 class="h6 font-weight-bold mb-2">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i> 請修正以下表單輸入錯誤：
        </h2>
        
        <ul class="mb-0 pl-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif