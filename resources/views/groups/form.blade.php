<div class="card my-4">
    <h3 class="card-header">群組資料</h3>
    <div class="card-body">
        <div class="form-group">
            <label for="name">名稱</label>
            {{ Form::text('name',null,['id'=>'name','class' => 'form-control', 'placeholder' => '名稱','required'=>'required']) }}
        </div>
        <div class="form-group">
            <div class="form-check">
                {{ Form::checkbox('disable', '1',null,['id'=>'disable','class'=>'form-check-input']) }}
                <label class="form-check-label" for="disable">停用</label>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                <i class="fas fa-save"></i> 儲存設定
            </button>
        </div>
    </div>
</div>
