@extends('layouts.master_clean')

@section('title', '導師填報 | ')

@section('content')
<style>
    /* 整體背景色，讓卡片更突出 */
    body {
        background-color: #f8f9fa;
    }

    /* 標題樣式 */
    .report-title {
        border-left: 5px solid #28a745;
        padding-left: 15px;
        margin-bottom: 30px;
        color: #333;
    }

    /* 題目卡片設計 */
    .question-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease; /* 動態過渡效果 */
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        list-style: none;
    }

    /* 滑鼠游標移上去的效果 */
    .question-card:hover {
        transform: translateY(-3px); /* 輕微浮起 */
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #28a745;
    }

    /* 序號標記 */
    .question-index {
        background: #28a745;
        color: white;
        padding: 2px 10px;
        border-radius: 50px;
        font-size: 0.9rem;
        margin-right: 10px;
    }
</style>
    <br>
    <div class="container py-5">
        <h3>{{ $student_year }}年{{ $student_class }}班</h3>
        <h1 class="report-title">{{ $report_student->semester }} {{ $report_student->name }}</h1>

        <form action="{{ route('report_students.save_teacher_fill', $report_student->id) }}" method="post" id="myForm">
            @csrf
            <div class="p-0">
                @foreach($report_student->items as $index => $item)
                    <div class="question-card">
                        <div class="d-flex align-items-center">
                            <span class="question-index">{{ $index + 1 }}</span>
                            <span class="h4 mb-0 text-secondary">{{ $item->name }}</span>
                        </div>
                        <div class="mt-3">       
                            <select name="answers[{{ $item->id }}]" class="form-control" required onchange="this.style.borderColor=''">
                                <option value="" disabled {{ !isset($answers[$item->id]) ? 'selected' : '' }}>--請選擇--</option>                                
                                
                                @foreach($all_students as $id => $name)
                                    <option value="{{ $id }}" 
                                        {{ (isset($answers[$item->id]) && $answers[$item->id] == $id) ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>                                                 
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                    <i class="fas fa-save"></i> 儲存所有資料
                </button>
            </div>
        </form>
    </div>    
<script>
    document.getElementById('myForm').addEventListener('submit', function(e) {
    // 1. 取得表單內所有的 select 元素
    const selects = this.querySelectorAll('select');
    let allSelected = true;
    let firstEmptySelect = null;

    // 2. 檢查是否有任何一個 select 沒有選值
    selects.forEach(function(select) {
        if (select.value === "" || select.value === null) {
            allSelected = false;
            if (!firstEmptySelect) firstEmptySelect = select; // 紀錄第一個沒選的，待會幫使用者聚焦
        }
    });

    // 3. 如果有沒選的，跳出警告並阻止表單送出
    if (!allSelected) {
        e.preventDefault(); // 阻止送出
        alert("還有題目尚未選擇學生，請檢查後再儲存！");
        
        // 自動聚焦到第一個沒選的下拉選單，提升使用者體驗
        if (firstEmptySelect) {
            firstEmptySelect.focus();
            // 如果你想讓該外框變紅，可以加上這行
            firstEmptySelect.style.borderColor = 'red';
        }
    } else {
        // 4. 如果都填好了，才執行最後的確認
        if (!confirm('確定要儲存所有資料嗎？')) {
            e.preventDefault();
        }
    }
});
</script>
@endsection
