<div class="card my-4">
    <h3 class="card-header">連結資料</h3>
    <div class="card-body">
        <div class="form-group">
            <label for="name">類別*</label>
            {{ Form::select('type_id', $types,null, ['id' => 'type_id', 'class' => 'form-control','required'=>'required']) }}
        </div>
        <div class="form-group">
            <label for="name">名稱*</label>
            {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
        </div>
        <div class="form-group">
            <label for="url">網址*</label>
            {{ Form::text('url',null,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
        </div>
        <div class="form-group">
            <label for="order_by">排序</label>
            {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                <i class="fas fa-save"></i> 儲存設定
            </button>
        </div>
    </div>
</div>
