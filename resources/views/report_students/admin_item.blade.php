@extends('layouts.master_clean')

@section('title', '編輯項目 | ')

@section('content')
    <br>
    <h1>{{ $report_student->semester }} {{ $report_student->name }}</h1>
    <form action="{{ route('report_students.admin_item_store') }}" method="post" id="myForm">
        @csrf
    <table class="table table-striped">
        <thead class="thead-light">
            <tr>
                <th style="width: 100px;">序號</th>
                <th>題目</th>
                <th style="width: 100px;">操作</th>
            </tr>
        </thead>
        <tbody id="questionBody">
            <tr>
                <td class="row-index">1</td>
                <td><input type="text" class="form-control" value="" name="name[]"></td>
                <td>
                    
                </td>                
            </tr>
            <tr>
                <td class="row-index">2</td>
                <td><input type="text" class="form-control" value="" name="name[]"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row">移除</button>
                </td>                
            </tr>
            <tr>
                <td class="row-index">3</td>
                <td><input type="text" class="form-control" value="" name="name[]"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row">移除</button>
                </td>                
            </tr>
            <tr>
                <td class="row-index">4</td>
                <td><input type="text" class="form-control" value="" name="name[]"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row">移除</button>
                </td>                
            </tr>
            <tr>
                <td class="row-index">5</td>
                <td><input type="text" class="form-control" value="" name="name[]"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row">移除</button>
                </td>                
            </tr>
        </tbody>
    </table>    
    <input type="hidden" name="report_student_id" value="{{ $report_student->id }}">
    <a href="#!" id="addRow" class="btn btn-primary">多一行題目</a>
    <button type="submit" class="btn btn-success">儲存</button>
    </form>
<script>   
// 監聽表單的 submit 事件
    document.getElementById('myForm').addEventListener('submit', function(e) {
        // 跳出確認視窗
        const result = confirm("確定要儲存這些題目嗎？");

        // 如果使用者點擊「取消」
        if (!result) {
            e.preventDefault(); // 攔截表單，不讓它送出
        }
    });

// 1. 新增行功能
document.getElementById('addRow').addEventListener('click', function(e) {
    e.preventDefault();
    const tbody = document.getElementById('questionBody'); // 確保這裡對應 ID
    if (!tbody) return; // 安全檢查

    const rowCount = tbody.rows.length + 1;

    const newRow = `
        <tr>
            <td class="row-index">${rowCount}</td>
            <td><input type="text" class="form-control" value="" name="name[]"></td>
            <td><button type="button" class="btn btn-danger btn-sm delete-row">移除</button></td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', newRow);
    
    // 讓新行的 input 自動聚焦
    tbody.lastElementChild.querySelector('input').focus();
});

// 2. 刪除行功能
document.getElementById('questionBody').addEventListener('click', function(e) {
    // 檢查點擊的是否為刪除按鈕
    if (e.target.classList.contains('delete-row')) {
        const row = e.target.closest('tr');
        row.remove();
        
        // 3. 重新計算所有行的序號
        reindexRows();
    }
});

// 重新編號的函數
function reindexRows() {
    const rows = document.querySelectorAll('#questionBody tr');
    rows.forEach((row, index) => {
        const indexCell = row.querySelector('.row-index');
        if (indexCell) {
            indexCell.innerText = index + 1;
        }
    });
}
</script>    
@endsection
