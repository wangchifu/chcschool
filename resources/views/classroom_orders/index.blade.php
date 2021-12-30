@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                教室預約
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('classroom_orders.index') }}">教室預約</a>
                </li>
                @if($classroom_admin)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classroom_orders.admin') }}">教室管理</a>
                    </li>
                @endif
                <li class="nav-item">
            </ul>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th nowrap>序號</th>
                    <th nowrap>名稱</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($classrooms as $classroom)
                    <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $classroom->name }}</td>
                    <td>
                        <a href="{{ route('classroom_orders.show',['classroom'=>$classroom->id,'select_sunday'=>date('Y-m-d')]) }}" class="btn btn-info btn-sm"><i class="fas fa-check-circle"></i> 預約</a>
                    </td>
                    <?php $i++; ?>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
