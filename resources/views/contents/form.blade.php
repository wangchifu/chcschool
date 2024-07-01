<div class="card my-4">
    <h3 class="card-header">內容資料</h3>
    <div class="card-body">
        @include('layouts.errors')
        <div class="form-group">
            <label for="title">標題*</label>
            {{ Form::text('title',null,['id'=>'title','class' => 'form-control','required'=>'required', 'placeholder' => '標題']) }}
        </div>
        <div class="form-group">
          <label for="title">共編群組*</label>
          {{ Form::select('group_id', $group_array,null, ['id' => 'group_id', 'class' => 'form-control']) }}
      </div>
        <div class="form-group">
          <label for="tags">標籤</label><small class="text-secondary"> (請用,分隔多個標籤)</small>
          {{ Form::text('tags',null,['id'=>'tags','class' => 'form-control', 'placeholder' => '標籤']) }}
      </div>
        <div class="form-group">
            <label for="content">內文*</label>
            {{ Form::textarea('content',null,['id'=>'my-editor','class'=>'form-control','required'=>'required']) }}
        </div>
        <script src="{{ asset('mycke/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('my-editor'
                ,{
                    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                });
        </script>
        <hr>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="power" id="power1" checked value="">
            <label class="form-check-label" for="power1">
              公開
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="power" id="power2" value="2">
            <label class="form-check-label" for="power2">
              在校內網域或登入者都可看
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="power" id="power3" value="3">
            <label class="form-check-label" for="power3">
              只有登入者可看
            </label>
          </div>
        <hr>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                <i class="fas fa-save"></i> 儲存設定
            </button>
        </div>
    </div>
</div>
