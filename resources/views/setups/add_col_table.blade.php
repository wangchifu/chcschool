@extends('layouts.master_clean')

@section('title', '新增欄位 | ')

@section('content')
    @include('layouts.errors')
    {{ Form::open(['route' => 'setups.add_col', 'method' => 'post','id'=>'this_form']) }}
    <table class="table">
        <tr>
            <td>
                <div class="form-group">
                    <label for="order_by">1.排序</label>
                    {{ Form::text('order_by',null,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="site_name">2.名稱</label>
                    {{ Form::text('title',null,['class' => 'form-control','required'=>'required']) }}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label for="site_name">3.欄位寬度比例 ( 1-12 整數 )</label>
                    {{ Form::text('num',null,['class' => 'form-control','required'=>'required','maxlength'=>'2']) }}
                </div>
            </td>

        </tr>
    </table>
    <div class="form-group">
        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定新增？')">
            <i class="fas fa-plus"></i> 新增欄位
        </button>
    </div>
    {{ Form::close() }}
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
