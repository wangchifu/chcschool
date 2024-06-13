@extends('layouts.master_clean')

@section('title', '編輯樹狀連結 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => ['trees.update',$tree->id], 'method' => 'patch']) }}


    <div class="form-group">
        <table class="table table-striped" style="word-break:break-all;">
            <tr>
                <th width="100">
                    排序
                </th>
                <th>
                    名稱
                </th>
                <th>
                    類別
                </th>
                <th>
                    所屬目錄
                </th>
                <th>
                    連結(建目錄免填)
                </th>
                <th></th>
            </tr>
            <tr>
                <td>
                    {{ Form::number('order_by',$tree->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '排序']) }}
                </td>
                <td>
                    {{ Form::text('name',$tree->name,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                </td>
                <td>
                    <?php
                        if($tree->type=="1"){
                            $check1 = "checked";
                            $check2 = null;
                        }elseif($tree->type=="2"){
                            $check2 = "checked";
                            $check1 = null;
                        }
                    ?>
                    <input type="radio" name="type" value="1" checked id="radio1" {{ $check1 }}><label for="radio1"><i class="fas fa-folder-open"></i> 子目錄</label>
                    <br>
                    <input type="radio" name="type" value="2" id="radio2" {{ $check2 }}><label for="radio2"><i class="fas fa-file"></i> 連結</label>
                </td>
                <td>
                    {{ Form::select('folder_id', $folders,$tree->folder_id, ['class' => 'form-control']) }}
                </td>
                <td>
                    {{ Form::text('url',$tree->url,['id'=>'order_by','class' => 'form-control', 'placeholder' => 'http://...(選目錄免填)']) }}
                </td>
                <td>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存？')">
                        <i class="fas fa-save"></i> 儲存區塊
                    </button>
                </td>
            </tr>
        </table>
    </div>
    {{ Form::close() }}
@endsection
