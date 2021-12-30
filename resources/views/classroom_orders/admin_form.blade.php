<div class="card my-4">
    <h3 class="card-header">教室資料</h3>
    <div class="card-body">
        <div class="form-group">
            <label for="name">名稱*</label>
            {{ Form::text('name',$name,['id'=>'name','class' => 'form-control', 'placeholder' => '名稱','required'=>'required']) }}
        </div>
        <div class="form-group">
            <label for="disable">停用？</label>
            <div class="form-check">
                {{ Form::checkbox('disable', '1',$disable ,['id'=>'disable','class'=>'form-check-input']) }}
                <label class="form-check-label" for="disable"><span class="btn btn-info btn-sm">設定</span></label>
            </div>
        </div>
        <div class="card my-4">
            <div class="card-header">
                <label for="disable">不開放節次打勾</label>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        @foreach(config("chcschool.cht_week") as $v)
                            <td>{{ $v }}</td>
                        @endforeach
                    </tr>
                    @foreach(config("chcschool.class_sections") as $w=>$v)
                        <tr>
                            @for($i=0;$i<7;$i++)
                                <?php
                                if($v=="午　休"){
                                    $btn1 = "btn btn-info btn-sm";
                                    $btn2 = "btn btn-outline-info btn-sm";
                                }elseif($v=="晨　間"){
                                    $btn1 = "btn btn-warning btn-sm";
                                    $btn2 = "btn btn-outline-warning btn-sm";
                                }else{
                                    $btn1 = "btn btn-success btn-sm";
                                    $btn2 = "btn btn-outline-success btn-sm";
                                }
                                ?>
                                <script>
                                    function change_b{{ $i }}{{ $w }}(){
                                        if($("#s{{ $i }}{{ $w }}").prop("checked")){
                                            $("#b{{ $i }}{{ $w }}").removeAttr('class');
                                            $("#b{{ $i }}{{ $w }}").attr('class', '{{ $btn1 }}');
                                        }else {
                                            $("#b{{ $i }}{{ $w }}").removeAttr('class');
                                            $("#b{{ $i }}{{ $w }}").attr('class', '{{ $btn2 }}');
                                        };
                                    }
                                </script>
                                <?php
                                if($v=="午　休"){
                                    $btn = ($close[$i][$w])?"btn btn-outline-info btn-sm":"btn btn-info btn-sm";
                                }elseif($v=="晨　間"){
                                    $btn = ($close[$i][$w])?"btn btn-outline-warning btn-sm":"btn btn-warning btn-sm";
                                }else{
                                    $btn = ($close[$i][$w])?"btn btn-outline-success btn-sm":"btn btn-success btn-sm";
                                }
                                ?>
                                <td>
                                    <div class="form-check">
                                        {{ Form::checkbox('close_section['.$i.']['.$w.']', null,$close[$i][$w] ,['id'=>'s'.$i.$w,'class'=>'form-check-input']) }}
                                        <label class="form-check-label" for="s{{ $i }}{{ $w }}"><span class="{{ $btn }}" id="b{{$i}}{{ $w }}" onclick="change_b{{ $i }}{{ $w }}()">{{ $v }}</span></label>
                                    </div>
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" onclick="return confirm('確定？')">
        </div>
    </div>
</div>
