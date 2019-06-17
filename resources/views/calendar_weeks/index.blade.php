@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-週次設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>校務行事曆-週次設定</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('calendars.index') }}">校務行事曆</a></li>
                    <li class="breadcrumb-item active" aria-current="page">學期開學日設定</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header">
                    <h4>新學期開學日設定</h4>
                </div>
                <div class="card-body">
                    <form name="myform" action="{{ route('calendar_weeks.create') }}" method="post" id="this_form">
                        @csrf
                        <div class="form-group">
                            <label for="open_date">
                                請輸入第一週的週日
                            </label>
                            <input type="text" name="open_date" id="open_date" class="form-control" maxlength="10" placeholder="如：2018-08-30" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-sm">
                                <i class="fas fa-cog"></i> 開始設定
                            </button>
                        </div>
                    </form>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>
                                已設定之學期
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($semesters as $v)
                            <tr>
                                <td>
                                    {{ $v }}
                                </td>
                                <td>
                                    @auth
                                        @if(auth()->user()->admin==1)
                                            <a href="{{ route('calendar_weeks.destroy',$v) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定嗎？')">刪除</a>
                                        @endif
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
