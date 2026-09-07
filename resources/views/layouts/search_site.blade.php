<style>
    /* 無障礙 HM1020401C 修正：搜尋輸入框與按鈕 Focus 高對比視覺提示 */
    #key_word:focus-visible,
    #key_form button:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<!-- 無障礙修復：移除排版用 table，改用 Bootstrap input-group 結構 -->
<form method="get" action="{{ asset('search_site.php') }}" target="_blank" id="key_form" aria-label="全站搜尋">
    <div class="input-group">
        <!-- 無障礙 HM1150100C 修正：為輸入框設定 aria-label 替代標籤 -->
        <input type="text" 
               name="key_word" 
               class="form-control" 
               id="key_word" 
               placeholder="請輸入搜尋關鍵字..." 
               aria-label="站內搜尋關鍵字" 
               required>
        
        <div class="input-group-append">
            <!-- 無障礙 HM1020401C 修正：使用語意化 button 取代 a 標籤 -->
            <button type="button" 
                    class="btn btn-primary" 
                    onclick="clean()" 
                    aria-label="執行站內搜尋 (另開新視窗)">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span class="sr-only">搜尋 (另開新視窗)</span>
            </button>
        </div>
    </div>
</form>

<script>
    function clean() {
        var keyInput = document.getElementById('key_word');
        if (keyInput.value.trim() === "") {
            alert('沒有輸入關鍵字');
            keyInput.focus();
        } else {
            document.getElementById('key_form').submit();
            // 延遲清空，避免部分瀏覽器在未成功送出前就將值清空
            setTimeout(function() {
                keyInput.value = "";
            }, 500);
        }        
    }
</script>