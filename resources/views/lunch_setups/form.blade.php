<div class="card my-4">
    <h3 class="card-header">午餐設定資料</h3>
    <div class="card-body">
        <div class="form-group">
            <label for="semester"><strong>學期*</strong><small class="text-danger">(如 1062)</small></label>
            {{ Form::text('semester',null,['id'=>'semester','class' => 'form-control', 'maxlength'=>'4','placeholder'=>'4碼數字','required'=>'required']) }}
        </div>
        <div class="form-group">
            <label for="die_line"><strong>允許最慢幾天前退餐*</strong></label>
            {{ Form::text('die_line',null,['id'=>'die_line','class' => 'form-control', 'maxlength'=>'1','required'=>'required']) }}
        </div>
        <div class="form-group">
            <label for="tea_open">隨時可訂餐<small class="text-danger">(僅供暫時開放，切記關閉它)</small></label>
            <div class="form-check">
                {{ Form::checkbox('teacher_open',null,null,['id'=>'tea_open','class'=>'form-check-input']) }}
                <label class="form-check-label" for="tea_open"><span class="btn btn-danger btn-sm">打勾為隨時可訂</span></label>
            </div>
        </div>
        <div class="form-group">
            <label for="disable">停止退餐<small class="text-danger">(僅供學期末計費時使用)</small></label>
            <div class="form-check">
                {{ Form::checkbox('disable',null,null,['id'=>'disable','class'=>'form-check-input']) }}
                <label class="form-check-label" for="disable"><span class="btn btn-danger btn-sm">打勾為全面停止退餐</span></label>
            </div>
        </div>
        <div class="form-group">
            <label for="all_rece_name"><strong>全學期收據抬頭名稱*</strong><small class="text-danger">(如 xx國小x學期午餐費)</small></label>
            {{ Form::text('all_rece_name',null,['id'=>'all_rece_name','class' => 'form-control','required'=>'required']) }}
        </div>
        <div class="form-group">
            <label for="all_rece_date"><strong>全學期收據開立日期*</strong><small class="text-danger">(如 2019-06-30)</small></label>
            {{ Form::text('all_rece_date',null,['id'=>'all_rece_date','class' => 'form-control','required'=>'required','maxlength'=>'10']) }}
        </div>
        <div class="form-group">
            <label for="all_rece_num"><strong>全學期收據起始號*</strong></label>
            {{ Form::text('all_rece_num',null,['id'=>'all_rece_num','class' => 'form-control','required'=>'required']) }}
        </div>
        <div class="form-group">
            <label for="all_rece_num">經手人印章圖檔</label>
            {{ Form::file('file1', ['class' => 'form-control']) }}
            @yield('seal1')
        </div>
        <div class="form-group">
            <label for="all_rece_num">主辦出納印章圖檔</label>
            {{ Form::file('file2', ['class' => 'form-control']) }}
            @yield('seal2')
        </div>
        <div class="form-group">
            <label for="all_rece_num">主辦會計印章圖檔</label>
            {{ Form::file('file3', ['class' => 'form-control']) }}
            @yield('seal3')
        </div>
        <div class="form-group">
            <label for="all_rece_num">機關長官印章圖檔</label>
            {{ Form::file('file4', ['class' => 'form-control']) }}
            @yield('seal4')
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" onclick="return confirm('確定儲存嗎？')">
                <i class="fas fa-save"></i> 儲存設定
            </button>
        </div>
    </div>
</div>
