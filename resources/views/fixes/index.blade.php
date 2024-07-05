@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>報修系統</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">報修列表</li>
                </ol>
            </nav>
            <a href="{{ route('fixes.index') }}" class="btn btn-dark btn-sm"><i class="fas fa-check-square"></i> 全部列表</a>
            @include('fixes.nav',['situation'=>null])
            <hr>
            @if($fix_admin)
                <form id="line_form" action="{{ route('fixes.store_notify') }}" method="post">
                    @csrf
                    <table>
                        <tr>
                            <td>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label"><i class="fab fa-line"></i> LINE權杖</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="line_key" value="{{ auth()->user()->line_key }}" required>
                                    <div id="emailHelp" class="form-text">新張貼會發LINE通知給你.</div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label"><i class="fas fa-envelope-square"></i> email通知</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" value="{{ auth()->user()->email }}" required>
                                    <div id="emailHelp" class="form-text">新張貼會發email通知給你.</div>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">儲存</button>
                            </td>
                        </tr>
                    </table>
                </form>
            @endif
            <a href="{{ route('fixes.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增報修</a>
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>類別 @if($fix_admin)<a href="{{ route('fixes.edit_class') }}" class="btn btn-secondary btn-sm"> <i class="fas fa-edit"></i> 編輯類別</a>@endif</th>
                    <th>處理狀況</th>
                    <th>申報日期</th>
                    <th>申報人</th>
                    <th>標題</th>
                    <th>處理日期</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fixes as $fix)
                    <tr>
                        <td>
                            @if($fix_admin)
                                <a href="{{ route('fixes.destroy',$fix->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                            @endif
                            {{ $types[$fix->type] }}
                        </td>
                        <td>
                            <?php
                            $situation=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                            $icon = [
                                '1'=>'<i class="fas fa-check-square text-success"></i>',
                                '2'=>'<i class="fas fa-exclamation-triangle text-warning"></i>',
                                '3'=>'<i class="fas fa-phone-square text-danger"></i>'
                            ];
                            ?>
                            {!! $icon[$fix->situation] !!} {{ $situation[$fix->situation] }}
                        </td>
                        <td>
                            {{ substr($fix->created_at,0,10) }}
                        </td>
                        <td>
                            {{ $fix->user->name }}
                        </td>
                        <td>
                            <a href="{{ route('fixes.show',$fix->id) }}">{{ $fix->title }}</a>
                        </td>
                        <td>
                            @if($fix->situation < 3)
                                {{ substr($fix->updated_at,0,10) }}
                            @endif
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            <div class="table-responsive">
            {{ $fixes->links() }}
            </div>
        </div>
    </div>
@endsection
