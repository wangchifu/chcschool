@extends('layouts.master_clean')

@section('title', '編輯圖片連結 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => ['photo_links.update',$photo_link->id],'files'=>true, 'method' => 'patch']) }}
    <div class="form-group">
        <table class="table table-striped" style="word-break:break-all;">
            <thead class="thead-light">
            <tr>
                <th>排序</th>
                <th>代表圖片</th>
                <th>類別</th>
                <th>名稱</th>
                <th>網址</th>
                <th>動作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    {{ Form::text('order_by',$photo_link->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                </td>
                <td>
                    <input type="file" name="image" id="image"><small class="text-secondary">(不改照片則免填，圖片有暫存的問題)</small>
                </td>
                <td>
                    <?php 
                        $selected0 = ($photo_link->photo_type_id==null)?"selected":null;
                    ?>
                    <select name="photo_type_id" class="form-control">
                        <option value="" {{ $selected0 }}>不分類</option>
                        @foreach($photo_types as $photo_type)
                            <?php 
                                $selected = ($photo_link->photo_type_id ==$photo_type->id)?"selected":null;
                            ?>
                            <option value="{{ $photo_type->id }}" {{ $selected }}>{{ $photo_type->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    {{ Form::text('name',$photo_link->name,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                </td>
                <td>
                    {{ Form::text('url',$photo_link->url,['id'=>'url','class' => 'form-control','required'=>'required', 'placeholder' => 'https://']) }}
                </td>
                <td>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定新增嗎？')">
                        <i class="fas fa-save"></i> 修改連結
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    {{ Form::close() }}
@endsection
