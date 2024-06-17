<link href="{{ asset('IconPicker/dist/iconpicker-1.5.0.css') }}" rel="stylesheet">
<script src="{{ asset('IconPicker/dist/iconpicker-1.5.0.js') }}"></script>
<div class="card my-4">
    <h3 class="card-header">連結資料</h3>
    <div class="card-body">
        <div class="form-group">
            <label for="name">類別*</label>
            {{ Form::select('type_id', $type_array,null, ['id' => 'type_id', 'class' => 'form-control','required'=>'required','onchange'=>'change_order_by()']) }}
        </div>       
        <div class="form-group">
            <label for="order_by">排序</label>
            <script>
            
                function change_order_by(){
                    var id = $('#type_id').find(":selected").val();
                    let arr = new Array()
                    @foreach($new_link_order_by as $k=>$v)
                        arr[{{ $k }}] = {{ $v }};
                    @endforeach
                    
                    $('#order_by').val(arr[id]) ;
                }
                    
            </script>
            {{ Form::text('order_by',reset($new_link_order_by),['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
        </div>
        <div class="form-group">
            <label for="name">圖示*</label> <i class="" id="show_icon"></i>
            <input type="text" class="form-control" name="icon" placeholder="請選圖示" data-fa-browser id="this_input">
            <script>
                $(function($) {
                  $.fabrowser();
              });
              function show_icon(){
                $("#show_icon").attr('class', '');
                $('#show_icon').addClass($('#this_input').val()); 
              }
              $('#show_icon').addClass($('#this_input').val());           
              </script>
        </div> 
        <div class="form-group">
            <label for="name">名稱*</label>
            {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
        </div>
        <div class="form-group">
            <label for="url">網址*</label>
            {{ Form::text('url',null,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
        </div>
        <hr>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="target" id="flexRadioDefault1" checked value="">
            <label class="form-check-label" for="flexRadioDefault1">
                開啟新視窗
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="target" id="flexRadioDefault2" value="_self">
            <label class="form-check-label" for="flexRadioDefault2">
                本視窗開啟
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
